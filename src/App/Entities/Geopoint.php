<?php

namespace App\Entities;

class Geopoint extends Place
{
    protected $latitude;
    protected $longitude;

    public function __construct()
    {
        $this->latitude = null;
        $this->longitude = null;
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
    }
}
