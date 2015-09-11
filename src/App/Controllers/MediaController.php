<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;

class MediaController
{

    public function __construct()
    {

    }

    public function getInformationPhotoById($id)
    {
        $url = file_get_contents($this->getURLInstagramPhoto($id));
        $jsonOrg = json_decode($url, true);

        $latitude = $jsonOrg['data'][0]['location']['latitude'];  
        $longitude = $jsonOrg['data'][0]['location']['longitude'];  

        $urlPlaces = file_get_contents($this->getURLInstagramNearestPlaces($latitude,$longitude));
        $jsonPlaces = json_decode($urlPlaces, true);
        $length = count($jsonPlaces['data']);

        $idPlacePhoto = $jsonPlaces['data'][0]['id'];
        $latitudePhoto = $jsonPlaces['data'][0]['latitude'];  
        $longitudePhoto = $jsonPlaces['data'][0]['longitude'];
        $namePlacePhoto = $jsonPlaces['data'][0]['name'];
        $addressPhoto = $this->getAddressByLatitudeLongitude($latitudePhoto,$longitudePhoto);
        $imagePhoto = $this->getURLMapLocation($latitudePhoto,$longitudePhoto);

        $arrayMedia = array();
        $arrayMedia['Id'] = $id;
        $arrayMedia['Location'][0]['Id'] = $idPlacePhoto;
        $arrayMedia['Location'][0]['Geopoint'][0]['Latitude'] = $latitudePhoto;
        $arrayMedia['Location'][0]['Geopoint'][0]['Longitude'] = $longitudePhoto;
        $arrayMedia['Location'][0]['Place'][0]['Name'] = $namePlacePhoto;
        $arrayMedia['Location'][0]['Place'][0]['Address'] = $addressPhoto;
        $arrayMedia['Location'][0]['Map'][0]['Image'] = $imagePhoto;

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
                $image = $this->getURLMapRelation($latitudePhoto,$longitudePhoto,$latitude,$longitude);

                $arrayMyNearest = array(); 
                $arrayMyNearest['Location'][0]['Id'] = $idPlace;
                $arrayMyNearest['Location'][0]['Geopoint'][0]['Latitude'] = $latitude;
                $arrayMyNearest['Location'][0]['Geopoint'][0]['Longitude'] = $longitude;
                $arrayMyNearest['Location'][0]['Place'][0]['Name'] = $namePlace;
                $arrayMyNearest['Location'][0]['Place'][0]['Address'] = $address;
                $arrayMyNearest['Location'][0]['Map'][0]['Image'] = $image;
                
                array_push($arrayNearest, $arrayMyNearest);
            }
        }
        $arrayMedia['General Map'][0]['Image'] = $this->getAllGeopointsURLMap($arrayMedia,$arrayNearest);
        $arrayMedia['Nearest Places'] = $arrayNearest;

        return new JsonResponse($arrayMedia);
    }

    private function getAddressByLatitudeLongitude($latitude, $longitude)
    {
        $urlAddress = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&sensor=true");
        $json = json_decode($urlAddress, true);
        $address = $json['results'][0]['formatted_address'];  
        return $address;
    }

    private function getURLInstagramPhoto($id)
    {
        $urlPhoto = "https://api.instagram.com/v1/tags/nofilter/media/recent?client_id=5fa500be04134056ab745cc48cf0382f&max_tag_id=" . $id;
        return $urlPhoto;
    }

    private function getURLInstagramNearestPlaces($latitude, $longitude)
    {
        $urlPlaces = "https://api.instagram.com/v1/locations/search?lat=". $latitude ."&lng=". $longitude ."&client_id=5fa500be04134056ab745cc48cf0382f";
        return $urlPlaces;
    }

    private function getURLMapLocation($latitude, $longitude)
    {
        $urlMap = "http://maps.googleapis.com/maps/api/staticmap?center=". $latitude .",". $longitude ."&zoom=15&scale=false&size=640x480&maptype=roadmap&format=png&visual_refresh=true&markers=size:mid%7Ccolor:0xff3900%7Clabel:A%7C". $latitude. ",". $longitude;
        return $urlMap;
    }

    private function getAllGeopointsURLMap($arrayMedia,$arrayNearest)
    {
        $urlMap = "http://maps.googleapis.com/maps/api/staticmap?center=".$arrayMedia['Location'][0]['Geopoint'][0]['Latitude'].",".$arrayMedia['Location'][0]['Geopoint'][0]['Longitude']."&zoom=15&scale=false&size=640x480&maptype=roadmap&format=png&visual_refresh=true&";
        $urlMap.= "markers=size:mid%7Ccolor:0xff3900%7Clabel:A%7C". $arrayMedia['Location'][0]['Geopoint'][0]['Latitude']. "," .$arrayMedia['Location'][0]['Geopoint'][0]['Longitude']. "&";

        for($i = 0; $i < count($arrayNearest);$i++)
        {
            $urlMap.= "markers=size:mid%7Ccolor:0x2dbd02%7Clabel:-%7C". $arrayNearest[$i]['Location'][0]['Geopoint'][0]['Latitude']. ",". $arrayNearest[$i]['Location'][0]['Geopoint'][0]['Longitude'] ."&";
        }
        $urlMap = rtrim($urlMap,"&");
        return $urlMap;
    }

    private function getURLMapRelation($latitudePhoto, $longitudePhoto, $nearestLaditude, $nearestLongitude)
    {
        $urlMap = "http://maps.googleapis.com/maps/api/staticmap?center=". $latitudePhoto.",".$longitudePhoto."&zoom=15&scale=false&size=640x480&path=color:0x0000ff|weight:5|".$latitudePhoto.",".$longitudePhoto."|".$nearestLaditude.",".$nearestLongitude."&maptype=roadmap&format=png&visual_refresh=true&markers=size:mid%7Ccolor:0xff3900%7Clabel:A%7C".$latitudePhoto.",".$longitudePhoto."&markers=size:mid%7Ccolor:0x2dbd02%7Clabel:-%7C". $nearestLaditude. ",". $nearestLongitude;
        return $urlMap;
    }
}
