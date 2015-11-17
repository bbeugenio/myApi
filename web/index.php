<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

require __DIR__ . '/../resources/config/Prod.php';

require __DIR__ . '/../src/App.php';

$app['http_cache']->run();
