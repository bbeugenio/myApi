<?php

namespace App\Services;

use GuzzleHttp\Client;

class GoogleInformationAddressService implements iInformationAddressService
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
}
