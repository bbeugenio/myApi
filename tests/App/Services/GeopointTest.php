<?php

namespace Tests\Services;
use App\Services\Geopoint;


class GeopointTest extends \PHPUnit_Framework_TestCase
{
	/***
        Method: testGeopointClass
        Description: This method test that geopoint's constructor, the set and get method are working fine.
    ***/
    public function testGeopointClass()
    {
        $olapicLatitude = "-31.4256195";
        $olapicLongitude = "-64.1876011";

        $geopoint = new Geopoint();
        $geopoint->__set("latitude",$olapicLatitude);
        $geopoint->__set("longitude",$olapicLongitude);

        $this->assertEquals($olapicLatitude, $geopoint->__get("latitude"));
        $this->assertEquals($olapicLongitude, $geopoint->__get("longitude"));
    }
}
