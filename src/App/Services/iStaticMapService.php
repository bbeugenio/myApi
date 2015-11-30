<?php

namespace App\Services;

interface IStaticMapService
{
    public function getURLMapLocation($latitude, $longitude);
    public function getURLMapRelation($principal_latitude, $principal_longitude, $arrayNearestPlaces);
}
