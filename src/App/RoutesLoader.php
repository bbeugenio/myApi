<?php

namespace App;

use Silex\Application;
use GuzzleHttp\Client;

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
                    $this->app['information.photo.service'], $this->app['information.address.service'], $this->app['static.map.service'], $this->app['location']
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

        $this->app['location'] = $this->app->share(
            function () {
                return new Services\Location(null, null, null, null, null, null);
            }
        );
    }

    public function bindRoutesToControllers()
    {
        $api = $this->app["controllers_factory"];
        $api->get('/media/{id},{token_id}', "media.controller:getInformationPhotoById");
        $this->app->mount(null, $api);
    }
}
