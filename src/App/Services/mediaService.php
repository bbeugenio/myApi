<?php

namespace App\Services;

class mediaService 
{
    public $id;
    public $latitude;
    public $longitude;
    public $address;

     public function __construct($id,$latitude,$longitude,$address)
    {
        $this->id = $id;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->address = $address;
    }

    public function toString()
    {
        //$aux = $this->id;
        $aux= "{ " . "\n";
        $aux.= "Latitude: "  . $this->latitude  . "," . "\r\n". " ";
        $aux.= "Longitude: ". $this->longitude . "," . "\r\n" . " ";
        $aux.= "Address: " . $this->address  . "," . "\r\n" . " ";
        $aux.= "}" . "\r\n";
        return $aux;
    }
}
