<?php

error_reporting(0);
ini_set('display_errors', '0');
date_default_timezone_set('America/Bogota');

return $config = [
    'error_handler_middleware' => [
        'display_error_details' => true,
        'log_errors' => true,
        'log_error_details' => true,
    ],
    'routes' => [
        'back' => 'http://localhost:8080/car-back',
        'front' => 'http://localhost:8080/car-front',
        'root' => dirname(__DIR__),
        'temp' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'tmp',
        'public' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public',
        'path' => '/car-back'
    ],
    'database' => [
        'host' => 'localhost',
        'username' => 'postgres',
        'password' => 'desarrollo',
        'port' => '5432',
        'database'=> 'car'
    ],
];
