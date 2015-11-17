<?php

namespace App;

use Silex\Application;

class RoutesLoader
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
        $this->instantiateControllers();

    }

    private function instantiateControllers()
    {
    	$application = $this->app;
        $this->app['media.controller'] = $this->app->share(
            function ()
            {
                return new Controllers\MediaController();
            }
        );
    }

    public function bindRoutesToControllers()
    {
        $api = $this->app["controllers_factory"];
        $api->get('/photo/{id}', "media.controller:getInformationPhotoById");

        $this->app->mount($this->app["api.endpoint"].'/', $api);
    }
}

