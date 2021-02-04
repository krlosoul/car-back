<?php

use Slim\Routing\RouteCollectorProxy;

$app->group('/api/carros', function (RouteCollectorProxy $group) {
    $group->get('', 'App\Controllers\CarrosController:get');
    $group->post('', 'App\Controllers\CarrosController:post');    

    $group->put('/producto', 'App\Controllers\CarrosController:putProducto');
    $group->delete('/producto', 'App\Controllers\CarrosController:deleteProducto');
    $group->delete('/limpiar', 'App\Controllers\CarrosController:clear');
    $group->put('/guardar', 'App\Controllers\CarrosController:save');

    $group->get('/historial', 'App\Controllers\CarrosController:historic');
});
