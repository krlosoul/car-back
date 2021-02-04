<?php

use DI\ContainerBuilder;
use Slim\App;

require_once __DIR__ . '/../../../vendor/autoload.php';
$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions(__DIR__ . '/Container.php');

$container = $containerBuilder->build();

$app = $container->get(App::class);

(require __DIR__ . '/Routes.php')($app);

(require __DIR__ . '/../middleware/Middleware.php')($app);

return $app;
