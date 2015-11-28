<?php

namespace Tests\Services;

use Mockery;
use App\Services\GoogleInformationAddressService;

class GoogleInformationAddressServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAddressFromServiceGoodWay()
    {
        $response = Mockery::mock('Psr\Http\Message\MessageInterface');
        $response->shouldReceive('getBody')->andReturn($this->getAddressFromServiceJson(true));
        $client_mock = Mockery::mock('GuzzleHttp\Client');
        $client_mock->shouldReceive('request')->andReturn($response);
        $service = new GoogleInformationAddressService($client_mock);
        $service_respose = $service->getAddressFromService(31.4256195, 64.1876011);
        $this->assertEquals("False Street", $service_respose);
    }

    public function testGetAddressFromServiceWrongWay()
    {
        $response = Mockery::mock('Psr\Http\Message\MessageInterface');
        $response->shouldReceive('getBody')->andReturn($this->getAddressFromServiceJson(false));
        $client_mock = Mockery::mock('GuzzleHttp\Client');
        $client_mock->shouldReceive('request')->andReturn($response);
        $service = new GoogleInformationAddressService($client_mock);
        $service_respose = $service->getAddressFromService(31.4256195, 64.1876011);
        $this->assertEquals(null, $service_respose);
    }

    private function getAddressFromServiceJson($is_correct_json)
    {
        if ($is_correct_json) {
            $json = '
			{
				"results": [
					{
						"formatted_address" : "False Street"
					}
				]
			}
					';
        } else {
            $json = '
			{
				"results": [
					{
						"formatted_address" : null
					}
				]
			}
					';
        }
        return $json;
    }
}
