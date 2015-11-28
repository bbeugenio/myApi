<?php

namespace App\Services;

use GuzzleHttp\Client;

class GoogleInformationAddressService implements iInformationAddressService
{
	private $client;
	private $url;
	private $service_response;
	private $json;

	public function __construct(Client $client)
	{
		$this->client = $client;
		$this->url = "";
		$this->service_response = null;
		$this->json = null;

	}

	public function __get($property)
	{
	    if (property_exists($this, $property))
	    {
	      	return $this->$property;
	    }
  	}

  	public function __set($property, $value)
  	{
    	if (property_exists($this, $property))
	    {
	    	$this->$property = $value;
	    }
		return $this;
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
		$address = null;
		if(!is_null($this->json['results'][0]['formatted_address']))
		{
			$address = $this->json['results'][0]['formatted_address'];
		}
		return $address;
	}
}
