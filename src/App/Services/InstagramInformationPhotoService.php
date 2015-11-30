<?php

namespace App\Services;

use GuzzleHttp\Client;

class InstagramInformationPhotoService implements iInformationPhotoService
{
    private $client;
    private $url;
    private $service_response;
    private $json;
    private $resultArray;
    private $code;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->url = "";
        $this->service_response = null;
        $this->json = null;
        $this->resultArray = array();
        $this->code = "";
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
    }

    /**
     *	getPhotoInformationFromService's method.
     *  This method receives an ID photo and a token ID from Instagram and returns an array with the location of the photo.
     */

    public function getPhotoInformationFromService($id, $token_id)
    {
        $this->url = "https://api.instagram.com/v1/media/".$id."?access_token=" . $token_id;
        $this->service_response = $this->client->request('GET', $this->url, ['verify' => false]);
        $this->json = json_decode((string)$this->service_response->getBody(), true);
        $this->code = $this->json['meta']['code'];
        if ($this->json['meta']['code'] == 200) {
            $this->resultArray = null;
            $this->resultArray = null;
            if (!is_null($this->json['data']['location'])) {
                $this->resultArray['latitude'] = $this->json['data']['location']['latitude'];
                $this->resultArray['longitude'] = $this->json['data']['location']['longitude'];
            }
        }
        return $this->resultArray;
    }

    /**
     *	getPhotoInformationFromServiceNearestPlaces' method.
     *  This method receives a photo's latitude and longitude and a token ID from Instagram and returns an array with information about the nearest places of the location that receive.
     */

    public function getPhotoInformationFromServiceNearestPlaces($latitude, $longitude)
    {
        $this->url = "https://api.instagram.com/v1/locations/search?lat=". $latitude ."&lng=". $longitude ."&client_id=5fa500be04134056ab745cc48cf0382f";
        $this->service_response = $this->client->request('GET', $this->url, ['verify' => false]);
        $this->json = json_decode((string)$this->service_response->getBody(), true);
        $this->code = $this->json['meta']['code'];
        if ($this->json['meta']['code'] == 200) {
            $this->resultArray = null;
            $this->resultArray = null;
            if (!is_null($this->json['data'])) {
                for ($i = 0; $i < count($this->json['data']); $i++) {
                    $this->resultArray[$i]['id'] = $this->json['data'][$i]['id'];
                    $this->resultArray[$i]['latitude'] = $this->json['data'][$i]['latitude'];
                    $this->resultArray[$i]['longitude'] = $this->json['data'][$i]['longitude'];
                    $this->resultArray[$i]['name'] = $this->json['data'][$i]['name'];
                }
            }
        }
        return $this->resultArray;
    }
}
