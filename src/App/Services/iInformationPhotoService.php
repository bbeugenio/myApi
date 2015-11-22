<?php

namespace App\Services;


interface iInformationPhotoService
{
    public function getPhotoInformationFromService($id, $token_id);
    public function getPhotoInformationFromServiceNearestPlaces($latitude, $longitude,$token_id);
    public function getAddressFromService($latitude, $longitude);
    public function getURLMapLocation($latitude, $longitude);
    public function getURLMapRelation($principal_latitude, $principal_longitude, $arrayNearestPlaces);
}
