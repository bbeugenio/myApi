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
        $olapic_id = "X5000GXB";
        $olapic_latitude = "-31.4256195";
        $olapic_longitude = "-64.1876011";
        $olapic_name = "Olapic Argentina S.A";
        $olapic_address = "Santiago Derqui 33, Córdoba, Argentina";
        $olapic_map = "http://maps.googleapis.com/maps/api/staticmap?center=-31.4256195%20-64.1876011&zoom=15&scale=false&size=640x480&maptype=roadmap&format=png&visual_refresh=true&markers=size:mid%7Ccolor:0xff3900%7Clabel:A%7C-31.4256195%20-64.1876011";

        $olapic_location = new location(null, $olapic_latitude, $olapic_longitude, $olapic_name, $olapic_address, $olapic_map);
        $olapic_location->__set("id", $olapic_id);

        $this->assertEquals($olapic_id, $olapic_location->__get("id"));
        $this->assertEquals($olapic_latitude, $olapic_location->__get("latitude"));
        $this->assertEquals($olapic_longitude, $olapic_location->__get("longitude"));
        $this->assertEquals($olapic_name, $olapic_location->__get("name"));
        $this->assertEquals($olapic_address, $olapic_location->__get("address"));
        $this->assertEquals($olapic_map, $olapic_location->__get("image"));
    }
}
