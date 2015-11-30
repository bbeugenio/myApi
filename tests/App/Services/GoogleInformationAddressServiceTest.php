<?php

namespace Tests\Services;

use Mockery;
use App\Services\GoogleInformationAddressService;

class GoogleInformationAddressServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     *	testGetAddressFromServiceGoodWay's method.
     *  This method is testing, that following the good way, the getAddressFromService method is working fine making a mock test on Google Services.
     */
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

    /**
     *	testGetAddressFromServiceWrongWay's method.
     *  This method is testing, that following the wrong way, the getAddressFromService method is working fine making a mock test on Google Services.
     */

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

    /**
     *	getAddressFromServiceJson's method.
     *  This method returns a string that simulate a Json in the good or wrong way.
     */

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
