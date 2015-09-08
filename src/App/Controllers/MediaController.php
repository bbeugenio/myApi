<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;

class MediaController
{

    public function __construct()
    {

    }

    public function getPhotoById($id)
    {
        $url = file_get_contents("https://api.instagram.com/v1/tags/nofilter/media/recent?client_id=5fa500be04134056ab745cc48cf0382f&max_tag_id=" . $id);
        $jsonOrg = json_decode($url, true);

        $latitude = $jsonOrg['data'][0]['location']['latitude'];  
        $longitude = $jsonOrg['data'][0]['location']['longitude'];  

        $urlPlaces = file_get_contents("https://api.instagram.com/v1/locations/search?lat=".$latitude."&lng=".$longitude. "&client_id=5fa500be04134056ab745cc48cf0382f");
        $jsonPlaces = json_decode($urlPlaces, true);
        $length = count($jsonPlaces['data']);

        $idPlace = $jsonPlaces['data'][0]['id'];
        $latitude = $jsonPlaces['data'][0]['latitude'];  
        $longitude = $jsonPlaces['data'][0]['longitude'];
        $namePlace = $jsonPlaces['data'][0]['name'];
        $address = $this->getAddressByLatitudeLongitude($latitude,$longitude);

        $arrayMedia = array();
        $arrayMedia['Id'] = $id;
        $arrayMedia['Location'][0]['Id'] = $idPlace;
        $arrayMedia['Location'][0]['Geopoint'][0]['Latitude'] = $latitude;
        $arrayMedia['Location'][0]['Geopoint'][0]['Longitude'] = $longitude;
        $arrayMedia['Location'][0]['Place'][0]['Name'] = $namePlace;
        $arrayMedia['Location'][0]['Place'][0]['Address'] = $address;

        $arrayNearest = array();
        $isFirst = true;
        for ($i = 0; $i < $length; $i++) {
            if($isFirst)
            {
                $isFirst = false;
            }
            else
            {
                $idPlace = $jsonPlaces['data'][$i]['id'];
                $latitude = $jsonPlaces['data'][$i]['latitude'];  
                $longitude = $jsonPlaces['data'][$i]['longitude'];
                $namePlace = $jsonPlaces['data'][$i]['name'];
                $address = $this->getAddressByLatitudeLongitude($latitude,$longitude);

                $arrayMyNearest = array(); 
                $arrayMyNearest['Location'][0]['Id'] = $idPlace;
                $arrayMyNearest['Location'][0]['Geopoint'][0]['Latitude'] = $latitude;
                $arrayMyNearest['Location'][0]['Geopoint'][0]['Longitude'] = $longitude;
                $arrayMyNearest['Location'][0]['Place'][0]['Name'] = $namePlace;
                $arrayMyNearest['Location'][0]['Place'][0]['Address'] = $address;
                
                array_push($arrayNearest, $arrayMyNearest);
            }
        }
        $arrayMedia['Nearest Places'] = $arrayNearest;

        return new JsonResponse($arrayMedia);
    }

    public function getAddressByLatitudeLongitude($latitude, $longitude)
    {
        $urlAddress = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&sensor=true");
        $json = json_decode($urlAddress, true);
        $address = $json['results'][0]['formatted_address'];  
        return $address;
    }
}
