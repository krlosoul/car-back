<?php

namespace App\controllers\seguridad;

use App\models\seguridad\UsuariosModel;
use Exception;
use Slim\Http\Response as Response;
use Slim\Http\ServerRequest as Request;

final class UsuariosController
{
  private $model;

  public function __construct(UsuariosModel $model)
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
      $usuario = $req->getParsedBody();
      $exists = $this->model->isExists($usuario['usu_usuario']);
      if ($exists > 0) {
        return $res->withStatus(500)->withJson(['message' => 'El usuario "' . $usuario['usu_usuario'] . '" ya se encuentra registrado en la base de datos', 'code' => 501]);
      } else {
        $result = $this->model->post($usuario);

        $response = [
          'auth' => $result,
        ];

        return $res->withJson($response)->withStatus(200);
      }
    } catch (Exception $ex) {
      return $res->withStatus(500)->withJson(['message' => $ex->getMessage(), 'code' => '500']);
    }
  }

  public function login(Request $req, Response $res)
  {
    try {
      $usuario = $req->getParsedBody()['usu_usuario'];
      $clave = $req->getParsedBody()['usu_clave'];

      $result = $this->model->login($usuario,$clave);

      if ($result === false) {
        return $res->withStatus(401)->withJson(['message' => 'Usuario y/o clave incorrectos', 'code' => 501]);
      }

      $response = [
        'auth' => $result,
      ];

      return $res->withJson($response)->withStatus(200);
    } catch (Exception $ex) {
      return $res->withStatus(500)->withJson(['message' => $ex->getMessage(), 'code' => '500']);
    }
  }
}
