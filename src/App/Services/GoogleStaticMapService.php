<?php

namespace App\Services;

use GuzzleHttp\Client;

class GoogleStaticMapService implements iStaticMapService
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

    /**
     *	getURLMapLocation's method.
     *  This method receives a place's latitude and longitude and returns a URL from a Static map that marks that place.
     */

    public function getURLMapLocation($latitude, $longitude)
    {
        $this->url = "http://maps.googleapis.com/maps/api/staticmap?center=". $latitude .",". $longitude ."&zoom=15&scale=false&size=640x480&maptype=roadmap&format=png&visual_refresh=true&markers=size:mid%7Ccolor:0xff3900%7Clabel:A%7C". $latitude. ",". $longitude;
        return $this->url;
    }

    /**
     *	getURLMapRelation's method.
     *  This method receives latitude and longitude that they will be the principal coordinates. Also receives an array of the Nearest Places.
     *  The method will return a static map that marks the main place on red and the nearest places on green.
     */

    public function getURLMapRelation($principal_latitude, $principal_longitude, $arrayNearestPlaces)
    {
        $this->url = "http://maps.googleapis.com/maps/api/staticmap?center=".$principal_latitude.",".$principal_longitude."&zoom=15&scale=false&size=640x480&maptype=roadmap&format=png&visual_refresh=true&";
        $this->url.= "markers=size:mid%7Ccolor:0xff3900%7Clabel:A%7C". $principal_latitude. "," .$principal_longitude. "&";

        for ($i = 0; $i < count($arrayNearestPlaces);$i++) {
            $this->url.= "markers=size:mid%7Ccolor:0x2dbd02%7Clabel:-%7C". $arrayNearestPlaces[$i]['Location'][0]['Geopoint'][0]['Latitude']. ",". $arrayNearestPlaces[$i]['Location'][0]['Geopoint'][0]['Longitude'] ."&";
        }
        $this->url = rtrim($this->url, "&");
        return $this->url;
    }
}
