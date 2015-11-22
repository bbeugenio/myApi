<?php

namespace App\Services;

use GuzzleHttp\Client;

class InstagramInformationPhotoService implements iInformationPhotoService
{
	private $client;
	private $url;
	private $service_response;
	private $json;

	public function __construct()
    {
        $this->client = new Client();
        $this->url = "";
        $this->service_response = null;
        $this->json = null;

    }
	/***
        Method: getPhotoInformationFromService
        Description: This method receives an ID photo and a token ID from Instagram and returns a Json with a lot of information about the photo ID.
        Example: location.
    ***/

    public function getPhotoInformationFromService($id, $token_id)
    {
    	$this->url = "https://api.instagram.com/v1/tags/nofilter/media/recent?client_id=". $token_id ."&max_tag_id=" . $id;
		$this->service_response = $this->client->request('GET', $this->url, ['verify' => false]);
		$this->json = json_decode((string)$this->service_response->getBody(), true);
		return $this->json;
    }

    /***
        Method: getPhotoInformationFromServiceNearestPlaces
        Description: This method receives a photo's latitude and longitude and a token ID from Instagram and returns a Json with information about the nearest places of the location that receive.
        Example: latitude, longitude.
    ***/

    public function getPhotoInformationFromServiceNearestPlaces($latitude, $longitude,$token_id)
    {
    	$this->url = "https://api.instagram.com/v1/locations/search?lat=". $latitude ."&lng=". $longitude ."&client_id=" . $token_id;
        $this->service_response = $this->client->request('GET', $this->url, ['verify' => false]);
        $this->json = json_decode((string)$this->service_response->getBody(), true);
        return $this->json;
    }

    /***
        Method: getAddressFromService
        Description: This method receives a place's latitude and longitude and returns its address.
    ***/

    public function getAddressFromService($latitude, $longitude)
    {
    	$this->url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&sensor=true";
		$this->service_response = $this->client->request('GET', $this->url, ['verify' => false]);
        $this->json = json_decode($this->service_response->getBody(), true);
        $address = $this->json['results'][0]['formatted_address'];
        return $address;
    }

    /***
        Method: getURLMapLocation
        Description: This method receives a place's latitude and longitude and returns a URL from a Static map that marks that place.
    ***/

    public function getURLMapLocation($latitude, $longitude)
    {
    	$this->url = "http://maps.googleapis.com/maps/api/staticmap?center=". $latitude .",". $longitude ."&zoom=15&scale=false&size=640x480&maptype=roadmap&format=png&visual_refresh=true&markers=size:mid%7Ccolor:0xff3900%7Clabel:A%7C". $latitude. ",". $longitude;
        return $this->url;
    }

    /***
     	Method: getURLMapRelation
		Description: This method receives latitude and longitude that they will be the principal coordinates.
					 Also receives an array of the Nearest Places.
					 The method will return a static map that marks the main place on red and the nearest places on green.
    ***/

    public function getURLMapRelation($principal_latitude, $principal_longitude, $arrayNearestPlaces)
    {
    	$this->url = "http://maps.googleapis.com/maps/api/staticmap?center=".$principal_latitude.",".$principal_longitude."&zoom=15&scale=false&size=640x480&maptype=roadmap&format=png&visual_refresh=true&";
        $this->url.= "markers=size:mid%7Ccolor:0xff3900%7Clabel:A%7C". $principal_latitude. "," .$principal_longitude. "&";

        for($i = 0; $i < count($arrayNearestPlaces);$i++)
        {
            $this->url.= "markers=size:mid%7Ccolor:0x2dbd02%7Clabel:-%7C". $arrayNearestPlaces[$i]['Location'][0]['Geopoint'][0]['Latitude']. ",". $arrayNearestPlaces[$i]['Location'][0]['Geopoint'][0]['Longitude'] ."&";
        }
        $this->url = rtrim($this->url,"&");
        return $this->url;
    }
}
