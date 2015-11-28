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
        $olapic_name = "Olapic Argentina S.A";
        $olapic_address = "Santiago Derqui 33, Córdoba, Argentina";

        $place = new Place();
        $place->__set("name", $olapic_name);
        $place->__set("address", $olapic_address);

        $this->assertEquals($olapic_name, $place->__get("name"));
        $this->assertEquals($olapic_address, $place->__get("address"));
    }
}
