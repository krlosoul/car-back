<?php

namespace App\Controllers;

use Exception;
use Slim\Http\Response as Response;
use Slim\Http\ServerRequest as Request;

final class IndexController
{

    public function __construct()
    {
    }

    public function index(Request $req, Response $res)
    {
        return $res->withStatus(200)->write('Hola Mundo');
    }
}
