<?php

namespace App\Services;

interface IInformationPhotoService
{
    public function getPhotoInformationFromService($id, $token_id);
    public function getPhotoInformationFromServiceNearestPlaces($latitude, $longitude);
}
