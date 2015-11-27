<?php

namespace App\Services;


interface iInformationPhotoService
{
    public function getPhotoInformationFromService($id, $token_id);
    public function getPhotoInformationFromServiceNearestPlaces($latitude, $longitude,$token_id);
}
