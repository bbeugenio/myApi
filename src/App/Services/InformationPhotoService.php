<?php

namespace App\Services;

use GuzzleHttp\Client;

class InformationPhotoService implements iInformationPhotoService
{
	/***
        Method: getURLInstagramPhoto
        Description: This method receives an ID photo from Instagram and returns a Json with a lot of information about the photo ID.
        Example: location.
    ***/

    public function getPhotoInformationFromService($id, $token_id)
    {
    	$url_photo = "https://api.instagram.com/v1/tags/nofilter/media/recent?client_id=". $token_id ."&max_tag_id=" . $id;
        $client = new Client();
		$url = $client->request('GET', $url_photo, ['verify' => false]);
		$json = json_decode((string)$url->getBody(), true);
		return $json;
    }

    /***
        Method: getURLInstagramNearestPlaces
        Description: This method receives a photo's latitude and longitude from Instagram and returns a Json with information about the nearest places of the location that receive.
        Example: latitude, longitude.
    ***/

    public function getPhotoInformationFromServiceNearestPlaces($latitude, $longitude,$token_id)
    {
    	$client = new Client();
    	$url_places = "https://api.instagram.com/v1/locations/search?lat=". $latitude ."&lng=". $longitude ."&client_id=" . $token_id;
        $url = $client->request('GET', $url_places, ['verify' => false]);
        $json = json_decode((string)$url->getBody(), true);
        return $json;
    }

    /***
        Method: getAddressByLatitudeLongitude
        Description: This method receives a place's latitude and longitude and returns a Json with information about this place.
    ***/

    public function getAddressFromService($latitude, $longitude)
    {
    	$client = new Client();
		$url_address = $client->request('GET', "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&sensor=true", ['verify' => false]);
        $json = json_decode($url_address->getBody(), true);
        $address = $json['results'][0]['formatted_address'];
        return $address;
    }

    /***
        Method: getURLMapLocation
        Description: This method receives a place's latitude and longitude and returns a URL from a Static map that marks that place.
    ***/

    public function getURLMapLocation($latitude, $longitude)
    {
    	$url_map = "http://maps.googleapis.com/maps/api/staticmap?center=". $latitude .",". $longitude ."&zoom=15&scale=false&size=640x480&maptype=roadmap&format=png&visual_refresh=true&markers=size:mid%7Ccolor:0xff3900%7Clabel:A%7C". $latitude. ",". $longitude;
        return $url_map;
    }

     /***
        Method: getURLMapRelation
        Description: This method receives two different place's latitudes and longitudes. The first two coordinates will be used to center and mark the map.
                     The other two will be used to mark the other place. This will be returned a static map that marks the main places on red and the other one on green and a line will be traced between this two points marking the road.
    ***/

    /***
        Method: getAllGeopointsURLMap
        Description: This method receives two Arrays. The first array contains information about the ID photo that we use in the getInformationPhotoById method.
                     The other one contains information about the nearest places. The method will be returned a static map that marks the main place on red and the nearest places on green.

    ***/

    public function getURLMapRelation($principal_latitude, $principal_longitude, $arrayNearestPlaces)
    {
    	$url_map = "http://maps.googleapis.com/maps/api/staticmap?center=".$principal_latitude.",".$principal_longitude."&zoom=15&scale=false&size=640x480&maptype=roadmap&format=png&visual_refresh=true&";
        $url_map.= "markers=size:mid%7Ccolor:0xff3900%7Clabel:A%7C". $principal_latitude. "," .$principal_longitude. "&";

        for($i = 0; $i < count($arrayNearestPlaces);$i++)
        {
            $url_map.= "markers=size:mid%7Ccolor:0x2dbd02%7Clabel:-%7C". $arrayNearestPlaces[$i]['Location'][0]['Geopoint'][0]['Latitude']. ",". $arrayNearestPlaces[$i]['Location'][0]['Geopoint'][0]['Longitude'] ."&";
        }
        $url_map = rtrim($url_map,"&");
        return $url_map;
    }
}
