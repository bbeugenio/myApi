<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use exception;

class MediaController
{

    public function __construct()
    {

    }

    /*** 
        Method: getInformationPhotoById
        Description: This method receives an ID photo from Instagram and returns a Json with a lot of information about the photo ID and the first 19 nearest places. 
    ***/
    public function getInformationPhotoById($id)
    {
        try
        {
            $url = file_get_contents($this->getURLInstagramPhoto($id));
            $jsonOrg = json_decode($url, true);

            if($jsonOrg['meta']['code'] == 200)
            {
                if(!is_null($jsonOrg['data'][0]['location']))
                {
                    $latitude = $jsonOrg['data'][0]['location']['latitude'];  
                    $longitude = $jsonOrg['data'][0]['location']['longitude'];  

                    $urlPlaces = file_get_contents($this->getURLInstagramNearestPlaces($latitude,$longitude));
                    $jsonPlaces = json_decode($urlPlaces, true);

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
                    $length = count($jsonPlaces['data']);
                    $isFirst = true;
                    for ($i = 0; $i < $length; $i++) {
                        if($isFirst)
                        {
                            //the first element is the same that receives getURLInstagramNearestPlaces and we used in the arrayMedia.
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
                }
                else
                {
                    throw new Exception("Photo's location can not be Null.");
                }
            }
            else
            {
                throw new Exception("An error ocurred getting the id photo of instagram.");
            }
        }
        catch(exception $e)
        {
            throw new Exception($e->getMessage());
            exit();
        }
        return new JsonResponse($arrayMedia);
    }

    /*** 
        Method: getURLInstagramPhoto
        Description: This method receives an ID photo from Instagram and returns a Json with a lot of information about the photo ID. 
        Example: location.
    ***/
    private function getURLInstagramPhoto($id)
    {
        $urlPhoto = "https://api.instagram.com/v1/tags/nofilter/media/recent?client_id=5fa500be04134056ab745cc48cf0382f&max_tag_id=" . $id;
        return $urlPhoto;
    }

    /*** 
        Method: getURLInstagramNearestPlaces
        Description: This method receives a photo's latitude and longitude from Instagram and returns a Json with information about the nearest places of the location that receive. 
        Example: latitude, longitude.
    ***/
    private function getURLInstagramNearestPlaces($latitude, $longitude)
    {
        $urlPlaces = "https://api.instagram.com/v1/locations/search?lat=". $latitude ."&lng=". $longitude ."&client_id=5fa500be04134056ab745cc48cf0382f";
        return $urlPlaces;
    }

    /*** 
        Method: getAddressByLatitudeLongitude
        Description: This method receives a place's latitude and longitude and returns a Json with information about this place. 
    ***/

    private function getAddressByLatitudeLongitude($latitude, $longitude)
    {
        $urlAddress = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&sensor=true");
        $json = json_decode($urlAddress, true);
        $address = $json['results'][0]['formatted_address'];  
        return $address;
    }

    /*** 
        Method: getURLMapLocation
        Description: This method receives a place's latitude and longitude and returns a URL from a Static map that marks that place. 
    ***/

    private function getURLMapLocation($latitude, $longitude)
    {
        $urlMap = "http://maps.googleapis.com/maps/api/staticmap?center=". $latitude .",". $longitude ."&zoom=15&scale=false&size=640x480&maptype=roadmap&format=png&visual_refresh=true&markers=size:mid%7Ccolor:0xff3900%7Clabel:A%7C". $latitude. ",". $longitude;
        return $urlMap;
    }

    /*** 
        Method: getURLMapRelation
        Description: This method receives two different place's latitudes and longitudes. The first two coordinates will be used to center and mark the map.
                     The other two will be used to mark the other place. This will be returned a static map that marks the main places on red and the other one on green and a line will be traced between this two points marking the road.
    ***/

    private function getURLMapRelation($latitudePhoto, $longitudePhoto, $nearestLaditude, $nearestLongitude)
    {
        $urlMap = "http://maps.googleapis.com/maps/api/staticmap?center=". $latitudePhoto.",".$longitudePhoto."&zoom=15&scale=false&size=640x480&path=color:0x0000ff|weight:5|".$latitudePhoto.",".$longitudePhoto."|".$nearestLaditude.",".$nearestLongitude."&maptype=roadmap&format=png&visual_refresh=true&markers=size:mid%7Ccolor:0xff3900%7Clabel:A%7C".$latitudePhoto.",".$longitudePhoto."&markers=size:mid%7Ccolor:0x2dbd02%7Clabel:-%7C". $nearestLaditude. ",". $nearestLongitude;
        return $urlMap;
    }

    /*** 
        Method: getAllGeopointsURLMap
        Description: This method receives two Arrays. The first array contains information about the ID photo that we use in the getInformationPhotoById method.
                     The other one contains information about the nearest places. The method will be returned a static map that marks the main place on red and the nearest places on green.
        
    ***/

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
}
