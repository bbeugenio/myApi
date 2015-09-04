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
    
        $this->app['media.controller'] = $this->app->share(function () 
        {
            return new Controllers\MediaController();
        });
    }

    public function bindRoutesToControllers()
    {
        $api = $this->app["controllers_factory"];

        $api->get('/json', "media.controller:getJson");
        $api->get('/photo/{id}', "media.controller:getPhotoById");

        $this->app->mount($this->app["api.endpoint"].'/'/*.$this->app["api.version"]*/, $api);
    }
}

