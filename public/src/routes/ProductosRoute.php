<?php

use Slim\Routing\RouteCollectorProxy;

$app->group('/api/productos', function (RouteCollectorProxy $group) {
    $group->get('', 'App\Controllers\ProductosController:get');
    $group->post('', 'App\Controllers\ProductosController:post');
    $group->put('', 'App\Controllers\ProductosController:put');
    $group->delete('', 'App\Controllers\ProductosController:delete');
});
