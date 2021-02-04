<?php

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Selective\Config\Configuration;
use Slim\App;
use Slim\Factory\AppFactory;

return [
    Configuration::class => function () {
        return new Configuration(require __DIR__ . '/Config.php');
    },
    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);
        $config = $container->get(Configuration::class);
        $app = AppFactory::create();
        $app->setBasePath($config->getString('routes.path'));
        $app->add(\Config\Middleware\CorsMiddleware::class);
        $app->addRoutingMiddleware();
        $app->addErrorMiddleware(true, true, true);
        return $app;
    },
    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getResponseFactory();
    },
    PDO::class => function (ContainerInterface $container) {
        $config = $container->get(Configuration::class);
        $host = $config->getString('database.host');
        $dbname = $config->getString('database.database');
        $user = $config->getString('database.username');
        $pass = $config->getString('database.password');
        $port = $config->getString('database.port');
        return new PDO("pgsql:host=" . $host . ";port=". $port . ";dbname=" . "$dbname", $user, $pass);
    }
];
