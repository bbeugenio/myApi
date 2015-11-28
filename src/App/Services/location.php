<?php

namespace App\Services;

class Location extends Geopoint
{
    protected $id;

    public function __construct($id, $latitude, $longitude, $name, $address, $image)
    {
        $this->id = $id;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->name = $name;
        $this->address = $address;
        $this->image = $image;
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
