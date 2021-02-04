<?php

namespace App\Controllers;

use App\models\CarrosModel;
use Exception;
use Slim\Http\Response as Response;
use Slim\Http\ServerRequest as Request;

final class CarrosController
{

    public function __construct(CarrosModel $model)
    {
      $this->model = $model;
    }

    public function get(Request $req, Response $res)
    {
        try {
            $skip = $req->getParam("skip");
            $take = $req->getParam("take");
            $usu_codigo = $req->getParam("usu_codigo");

            $data = $this->model->get($usu_codigo,$skip, $take);
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
        $carro = $req->getParsedBody();
        $exists = $this->model->isExists($carro['pro_referencia'],$carro['usu_codigo']);
        if ($exists > 0) {
          return $res->withStatus(500)->withJson(['message' => 'El producto de referencia "' . $carro['pro_referencia'] . '" ya fue adquirido.', 'code' => 501]);
        } else {
            $this->model->post($carro);
            return $res->withJson(null)->withStatus(200);
        }
      } catch (Exception $ex) {
        return $res->withStatus(500)->withJson(['message' => $ex->getMessage(), 'code' => '500']);
      }
    }

    public function putProducto(Request $req, Response $res)
    {
        try {
            $producto = $req->getParsedBody();
            $this->model->putProducto($producto);
            return $res->withJson(null)->withStatus(200);
        } catch (Exception $ex) {
            return $res->withStatus(500)->withJson(['message' => $ex->getMessage(), 'code' => '500']);
        }
    }

    public function deleteProducto(Request $req, Response $res)
    {
        try {
            $codigo = $req->getQueryParam("pro_referencia");
            $this->model->deleteProducto($codigo);
            return $res->withJson(null)->withStatus(200);
        } catch (Exception $ex) {
            return $res->withStatus(500)->withJson(['message' => $ex->getMessage(), 'code' => '500']);
        }
    }

    public function clear(Request $req, Response $res)
    {
        try {
            $codigo = $req->getQueryParam("usu_codigo");
            $this->model->clear($codigo);
            return $res->withJson(null)->withStatus(200);
        } catch (Exception $ex) {
            return $res->withStatus(500)->withJson(['message' => $ex->getMessage(), 'code' => '500']);
        }
    }

    public function save(Request $req, Response $res)
    {
        try {
            $producto = $req->getParsedBody();
            $this->model->save($producto);
            return $res->withJson(null)->withStatus(200);
        } catch (Exception $ex) {
            return $res->withStatus(500)->withJson(['message' => $ex->getMessage(), 'code' => '500']);
        }
    }

    public function historic(Request $req, Response $res)
    {
        try {
            $skip = $req->getParam("skip");
            $take = $req->getParam("take");
            $usu_codigo = $req->getParam("usu_codigo");

            $data = $this->model->historic($usu_codigo,$skip, $take);
            $returnData = [
                "data" => $data['data'],
                "total" => $data['total']
            ];
            return $res->withStatus(200)->withJson($returnData);
        } catch (Exception $ex) {
            return $res->withStatus(500)->withJson(['message' => $ex->getMessage(), 'code' => '500']);
        }
    }

}
