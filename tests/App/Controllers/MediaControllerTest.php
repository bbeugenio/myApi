<?php

namespace Tests\Services;
use App\Controllers\MediaController;


class MediaControllerTest extends \PHPUnit_Framework_TestCase
{
	/***
		Method: testMediaControllerClass
		Description: This method test that json's value is not null, that json is an object, that json dont have any errors and that json is a valid Json.
	***/
	public function testMediaControllerClass()
	{
		$media_controller = new MediaController();
		//$json = $media_controller->getInformationPhotoById("1062276093275560411");
		$json = $media_controller->getInformationPhotoById();
		$this->assertNotNull($json);

		$succesful = true;
		if(!is_object($json))
			$succesful = false;
		$this->assertTrue($succesful);

		if(json_last_error() == JSON_ERROR_NONE)
			$succesful = true;
		else
			$succesful = false;
		$this->assertTrue($succesful);

		$this->assertJson(json_encode($json));
	}
}
