<?php

namespace Tests\Services;

use App\Services\Map;

class MapTest extends \PHPUnit_Framework_TestCase
{
    /***
        Method: testMapClass
        Description: This method test that map's constructor, the set and get method are working fine.
    ***/
    public function testMapClass()
    {
        $olapic_image = "http://maps.googleapis.com/maps/api/staticmap?center=-31.4256195%20-64.1876011&zoom=15&scale=false&size=640x480&maptype=roadmap&format=png&visual_refresh=true&markers=size:mid%7Ccolor:0xff3900%7Clabel:A%7C-31.4256195%20-64.1876011";

        $map = new Map();
        $map->__set("image", $olapic_image);

        $this->assertEquals($olapic_image, $map->__get("image"));
    }
}
