<?php

namespace Tests\Services;
use App\Services\Place;


class PlaceTest extends \PHPUnit_Framework_TestCase
{
	/***
		Method: testPlaceClass
		Description: This method test that place's constructor, the set and get method are working fine.
	***/
	public function testPlaceClass()
	{
		$olapicName = "Olapic Argentina S.A";
		$olapicAddress = "Santiago Derqui 33, Córdoba, Argentina";

		$place = new Place();
		$place->__set("name",$olapicName);
		$place->__set("address",$olapicAddress);

		$this->assertEquals($olapicName, $place->__get("name"));
		$this->assertEquals($olapicAddress, $place->__get("address"));
	}
}
