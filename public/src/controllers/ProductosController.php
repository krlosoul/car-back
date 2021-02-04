<?php

namespace App\Controllers;

use App\models\ProductosModel;
use Exception;
use Slim\Http\Response as Response;
use Slim\Http\ServerRequest as Request;

final class ProductosController
{

    public function __construct(ProductosModel $model)
    {
      $this->model = $model;
    }

    public function get(Request $req, Response $res)
    {
        try {
            $skip = $req->getParam("skip");
            $take = $req->getParam("take");

            $data = $this->model->get($skip, $take);
            $returnData = [
                "data" => $data['data'],
                "total" => $data['total']
            ];
            return $res->withStatus(200)->withJson($returnData);
        } catch (Exception $ex) {
            return $res->withStatus(500)->withJson(['message' => $ex->getMessage(), 'code' => '500']);
        }
    }

    public function post(Request $req, Response $res)
    {
        try {
            $producto = $req->getParsedBody();

            $this->model->post($producto);

            return $res->withJson(null)->withStatus(200);
        } catch (Exception $ex) {
            return $res->withStatus(500)->withJson(['message' => $ex->getMessage(), 'code' => '500']);
        }
    }

    public function put(Request $req, Response $res)
    {
        try {
            $producto = $req->getParsedBody();
            $this->model->put($producto);
            return $res->withJson(null)->withStatus(200);
        } catch (Exception $ex) {
            return $res->withStatus(500)->withJson(['message' => $ex->getMessage(), 'code' => '500']);
        }
    }

    public function delete(Request $req, Response $res)
    {
        try {
            $codigo = $req->getQueryParam("pro_referencia");
            $this->model->delete($codigo);
            return $res->withJson(null)->withStatus(200);
        } catch (Exception $ex) {
            return $res->withStatus(500)->withJson(['message' => $ex->getMessage(), 'code' => '500']);
        }
    }

}
