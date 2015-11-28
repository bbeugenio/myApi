<?php

namespace App\Services;

interface iStaticMapService
{
    public function getURLMapLocation($latitude, $longitude);
    public function getURLMapRelation($principal_latitude, $principal_longitude, $arrayNearestPlaces);
}
