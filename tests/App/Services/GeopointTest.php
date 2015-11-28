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
        $olapic_latitude = "-31.4256195";
        $olapic_longitude = "-64.1876011";

        $geopoint = new Geopoint();
        $geopoint->__set("latitude", $olapic_latitude);
        $geopoint->__set("longitude", $olapic_longitude);

        $this->assertEquals($olapic_latitude, $geopoint->__get("latitude"));
        $this->assertEquals($olapic_longitude, $geopoint->__get("longitude"));
    }
}
