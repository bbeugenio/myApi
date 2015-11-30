<?php

namespace Tests\Services;

use Mockery;
use App\Services\InstagramInformationPhotoService;

class InstagramInformationPhotoServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     *	testGetPhotoInformationFromServiceGoodWay's method.
     *  This method is testing, that following the good way, the getPhotoInformationFromService method is working fine making a mock test on Instagram Services.
     */
    public function testGetPhotoInformationFromServiceGoodWay()
    {
        $response = Mockery::mock('Psr\Http\Message\MessageInterface');
        $response->shouldReceive('getBody')->andReturn($this->getPhotoInformationFromServiceJson(true));
        $client_mock = Mockery::mock('GuzzleHttp\Client');
        $client_mock->shouldReceive('request')->andReturn($response);
        $service = new InstagramInformationPhotoService($client_mock);
        $service_respose = $service->getPhotoInformationFromService(314256195641876011, "token_id");
        $this->assertEquals(200, $service->__get("code"));
        $this->assertEquals(2, $service_respose['latitude']);
        $this->assertEquals(4, $service_respose['longitude']);
    }

    /**
     *	testGetPhotoInformationFromServiceWrongWay's method.
     *  This method is testing, that following the wrong way, the getPhotoInformationFromService method is working fine making a mock test on Instagram Services.
     */

    public function testGetPhotoInformationFromServiceWrongWay()
    {
        $response = Mockery::mock('Psr\Http\Message\MessageInterface');
        $response->shouldReceive('getBody')->andReturn($this->getPhotoInformationFromServiceJson(false));
        $client_mock = Mockery::mock('GuzzleHttp\Client');
        $client_mock->shouldReceive('request')->andReturn($response);
        $service = new InstagramInformationPhotoService($client_mock);
        $service_respose = $service->getPhotoInformationFromService(314256195641876011, "token_id");
        $this->assertNotEquals(200, $service->__get("code"));
        $is_empty = false;
        if (Count($service_respose) < 1) {
            $is_empty = true;
        }
        $this->assertEquals(true, $is_empty);
    }

    /**
     *	testGetPhotoInformationFromServiceNearestPlacesGoodWay's method.
     *  This method is testing, that following the good way, the getPhotoInformationFromServiceNearestPlaces method is working fine making a mock test on Instagram Services.
     */

    public function testGetPhotoInformationFromServiceNearestPlacesGoodWay()
    {
        $response = Mockery::mock('Psr\Http\Message\MessageInterface');
        $response->shouldReceive('getBody')->andReturn($this->getPhotoInformationFromServiceNearestPlacesJson(true));
        $client_mock = Mockery::mock('GuzzleHttp\Client');
        $client_mock->shouldReceive('request')->andReturn($response);
        $service = new InstagramInformationPhotoService($client_mock);
        $service_respose = $service->getPhotoInformationFromServiceNearestPlaces(31.4256195, 64.1876011, "token_id");
        $this->assertEquals(200, $service->__get("code"));
        $this->assertEquals(2, $service_respose[0]['latitude']);
        $this->assertEquals(4, $service_respose[0]['longitude']);
        $this->assertEquals(6, $service_respose[1]['latitude']);
        $this->assertEquals(8, $service_respose[1]['longitude']);
    }

    /**
     *	testGetPhotoInformationFromServiceNearestPlacesWrongWay's method.
     *  This method is testing, that following the wrong way, the getPhotoInformationFromServiceNearestPlaces method is working fine making a mock test on Instagram Services.
     */

    public function testGetPhotoInformationFromServiceNearestPlacesWrongWay()
    {
        $response = Mockery::mock('Psr\Http\Message\MessageInterface');
        $response->shouldReceive('getBody')->andReturn($this->getPhotoInformationFromServiceNearestPlacesJson(false));
        $client_mock = Mockery::mock('GuzzleHttp\Client');
        $client_mock->shouldReceive('request')->andReturn($response);
        $service = new InstagramInformationPhotoService($client_mock);
        $service_respose = $service->getPhotoInformationFromServiceNearestPlaces(31.4256195, 64.1876011, "token_id");
        $this->assertNotEquals(200, $service->__get("code"));
        $is_empty = false;
        if (Count($service_respose) < 1) {
            $is_empty = true;
        }
        $this->assertEquals(true, $is_empty);
    }

    /**
     *	getPhotoInformationFromServiceJson's method.
     *  This method returns a string that simulate a Json in the good or wrong way.
     */

    private function getPhotoInformationFromServiceJson($is_correct_json)
    {
        if ($is_correct_json) {
            $json = '
			{
				"meta":{
					"code":200
				},
				"data":
					{
						"location" : {
							"latitude" : 2,
							"longitude" : 4
						}
					}
			}
					';
        } else {
            $json = '
			{
				"meta":{
					"code":500
				},
				"data":
					{
						"location" : null
					}
			}
					';
        }
        return $json;
    }

    /**
     *	getPhotoInformationFromServiceNearestPlacesJson's method.
     *  This method returns a string that simulate a Json in the good or wrong way.
     */

    private function getPhotoInformationFromServiceNearestPlacesJson($is_correct_json)
    {
        if ($is_correct_json) {
            $json = '
			{
				"meta":{
					"code":200
				},
				"data": [
					{
						"id" : 1,
						"latitude" :2,
						"longitude": 4,
						"name": "test1"
					},
					{
						"id" : 2,
						"latitude" :6,
						"longitude": 8,
						"name": "test2"
					}
				]
			}
					';
        } else {
            $json = '
			{
				"meta":{
					"code":500
				},
				"data": null
			}
					';
        }
        return $json;
    }
}
