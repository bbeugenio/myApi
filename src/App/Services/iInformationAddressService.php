<?php

namespace App\Services;

interface IInformationAddressService
{
    public function getAddressFromService($latitude, $longitude);
}
