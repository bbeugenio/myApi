<?php

namespace App;

use Silex\Application;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RoutesLoader
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->instantiateControllers();
    }

    private function instantiateControllers()
    {
        $this->app['media.controller'] = $this->app->share(
            function () {
                return new Controllers\MediaController(
                    $this->app['information.photo.service'], $this->app['information.address.service'], $this->app['static.map.service']
                    );
            }
        );

        $this->app['information.photo.service'] = $this->app->share(
            function () {
                return new Services\InstagramInformationPhotoService(new Client());
            }
        );

        $this->app['information.address.service'] = $this->app->share(
            function () {
                return new Services\GoogleInformationAddressService(new Client());
            }
        );

        $this->app['static.map.service'] = $this->app->share(
            function () {
                return new Services\GoogleStaticMapService();
            }
        );
    }

    public function bindRoutesToControllers()
    {
        $api = $this->app["controllers_factory"];
        $api->get('/media/{id}', "media.controller:getInformationPhotoById");
        $api->get(
            '/token_info',
            function (Request $request) {
                return new JsonResponse([
                    "Intructions" => "Take your token from the URL of this page in the access_token parameter and send it as instagram_token in the query string section of your requests"
                ]);
            }
        );
        $this->app->mount("/", $api);
    }
}
