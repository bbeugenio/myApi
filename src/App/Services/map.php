<?php

namespace App\Services;

class Map
{
    protected $image;

     public function __construct()
    {
        $this->image = null;
    }

	public function __get($property)
	{
	    if (property_exists($this, $property))
	    {
	      	return $this->$property;
	    }
  	}

  	public function __set($property, $value)
  	{
    	if (property_exists($this, $property))
	    {
	    	$this->$property = $value;
	    }
		return $this;
	}
}
