<?php

namespace App\Services;

interface iInformationAddressService
{
    public function getAddressFromService($latitude, $longitude);
}
