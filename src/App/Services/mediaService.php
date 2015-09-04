<?php

namespace App\Services;

class mediaService 
{
    public $id;
    public $latitud;
    public $longitud;
    public $address;
/*
    public function __construct($id,$latitud,$longitud,$address)
    {
        $this->id = $id;
        $this->latitud = $latitud;
        $this->longitud = $longitud;
        $this->address = $address;
    }*/
     public function __construct()
    {
        $this->id = null;
        $this->latitud = null;
        $this->longitud = null;
        $this->address = null;
    }

    public function getBruno()
    {
        //return $this->id . $this->latitud . $this->longitud . $this->address;
        return get_object_vars($this);
    }
}
