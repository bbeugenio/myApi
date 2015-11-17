<?php

namespace Tests\Services;
use App\Services\Location;


class LocationTest extends \PHPUnit_Framework_TestCase
{
	/***
		Method: testLocationClass
		Description: This method test that location's constructor, the set and get method are working fine.
	***/
	public function testLocationClass()
	{
		$olapicId = "X5000GXB";
		$olapicLatitude = "-31.4256195";
		$olapicLongitude = "-64.1876011";
		$olapicName = "Olapic Argentina S.A";
		$olapicAddress = "Santiago Derqui 33, Córdoba, Argentina";
		$olapicMap = "http://maps.googleapis.com/maps/api/staticmap?center=-31.4256195%20-64.1876011&zoom=15&scale=false&size=640x480&maptype=roadmap&format=png&visual_refresh=true&markers=size:mid%7Ccolor:0xff3900%7Clabel:A%7C-31.4256195%20-64.1876011";

		$olapicLocation = new location(null,$olapicLatitude,$olapicLongitude,$olapicName,$olapicAddress,$olapicMap);
		$olapicLocation->__set("id",$olapicId);

		$this->assertEquals($olapicId, $olapicLocation->__get("id"));
		$this->assertEquals($olapicLatitude, $olapicLocation->__get("latitude"));
		$this->assertEquals($olapicLongitude, $olapicLocation->__get("longitude"));
		$this->assertEquals($olapicName, $olapicLocation->__get("name"));
		$this->assertEquals($olapicAddress, $olapicLocation->__get("address"));
		$this->assertEquals($olapicMap, $olapicLocation->__get("image"));
	}
}
