<?php		
		
namespace App\Services;		
		
class place extends map		
{			
    protected $name;		
    protected $address;			
		
     public function __construct()		
    {		
        $this->name = null;		
        $this->address = null;			
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