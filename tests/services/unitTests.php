<?php

namespace Tests\Services;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use App\Services\geopoint;
use App\Services\map;
use App\Services\place;
use App\Services\location;
use App\Controllers\MediaController;


class unitTests extends \PHPUnit_Framework_TestCase
{

    public function testGeopointClass()
    {
        $olapicLatitude = "-31.4256195";
        $olapicLongitude = "-64.1876011";

        $geopoint = new geopoint();
        $geopoint->__set("latitude",$olapicLatitude);
        $geopoint->__set("longitude",$olapicLongitude);

        $this->assertEquals($olapicLatitude, $geopoint->__get("latitude"));
        $this->assertEquals($olapicLongitude, $geopoint->__get("longitude"));
    }

    public function testMapClass()
    {
        $olapicImage = "http://maps.googleapis.com/maps/api/staticmap?center=-31.4256195%20-64.1876011&zoom=15&scale=false&size=640x480&maptype=roadmap&format=png&visual_refresh=true&markers=size:mid%7Ccolor:0xff3900%7Clabel:A%7C-31.4256195%20-64.1876011";

        $map = new map();
        $map->__set("image",$olapicImage);

        $this->assertEquals($olapicImage, $map->__get("image"));
    }

    public function testPlaceClass()
    {
        $olapicName = "Olapic Argentina S.A";
        $olapicAddress = "Santiago Derqui 33, Córdoba, Argentina";

        $place = new place();
        $place->__set("name",$olapicName);
        $place->__set("address",$olapicAddress);

        $this->assertEquals($olapicName, $place->__get("name"));
        $this->assertEquals($olapicAddress, $place->__get("address"));
    }

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
