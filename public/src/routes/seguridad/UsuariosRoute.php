<?php

use Slim\Routing\RouteCollectorProxy;

$app->group('/api/seguridad/usuarios', function (RouteCollectorProxy $group) {
    $group->get('', 'App\Controllers\Seguridad\UsuariosController:get');
    $group->post('', 'App\Controllers\Seguridad\UsuariosController:post');
    $group->post('/login', 'App\Controllers\Seguridad\UsuariosController:login');
});
