<?php

use Slim\App;

return function (App $app) {
    (require __DIR__ . '/../../src/routes/IndexRoute.php');
    (require __DIR__ . '/../../src/routes/seguridad/UsuariosRoute.php');
    (require __DIR__ . '/../../src/routes/ProductosRoute.php');
    (require __DIR__ . '/../../src/routes/CarrosRoute.php');
};
