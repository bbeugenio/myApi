<?php

namespace App;

use Silex\Application;

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
            function ()
            {
                return new Controllers\MediaController(
                	$this->app['information.photo.service'], $this->app['information.address.service'], $this->app['static.map.service'],$this->app['location']
                	);
            }
        );

        $this->app['information.photo.service'] = $this->app->share(
            function ()
            {
                return new Services\InstagramInformationPhotoService();
            }
        );

        $this->app['information.address.service'] = $this->app->share(
            function ()
            {
                return new Services\GoogleInformationAddressService();
            }
        );

        $this->app['static.map.service'] = $this->app->share(
            function ()
            {
                return new Services\GoogleStaticMapService();
            }
        );

        $this->app['location'] = $this->app->share(
            function ()
            {
                return new Services\Location(null,null,null,null,null,null);
            }
        );
    }

    public function bindRoutesToControllers()
    {
        $api = $this->app["controllers_factory"];
        $api->get('/photo/{id},{token_id}', "media.controller:getInformationPhotoById");

        $this->app->mount($this->app["api.endpoint"].'/', $api);
    }
}

