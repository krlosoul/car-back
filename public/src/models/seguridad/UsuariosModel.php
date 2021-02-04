<?php

namespace App\models\Seguridad;

use Config\providers\DatabaseManager as DbManager;
use Exception;

final class UsuariosModel
{
    private $db;

    public function __construct(DbManager $db)
    {
        $this->db = $db;
    }

    public function get(string $skip, string $take)
    {
        try {
            $query = "SELECT u.usu_usuario, u.usu_clave, p.per_nombres, p.per_apellidos 
            FROM usuarios u
            JOIN personas p ON p.per_codigo = u.per_codigo 
            OFFSET :skip LIMIT :take";

            $res = $this->db->getData($query, [['Name' => 'skip', 'Value' => $skip], ['Name' => 'take', 'Value' => $take]]);

            $total = $this->db->getData("SELECT COUNT(u.usu_codigo) total 
            FROM usuarios u 
            JOIN personas p ON p.per_codigo = u.per_codigo");

            return [
                'data' => $res,
                'total' => $total[0]['total']
            ];
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function post($usuario)
    {
        try {
            $query = 'INSERT INTO personas (per_nombres, per_apellidos) VALUES 
                (:per_nombres, :per_apellidos)';

            $data = [
                ['Name' => 'per_nombres', 'Value' => $usuario['persona']['per_nombres']],
                ['Name' => 'per_apellidos', 'Value' => $usuario['persona']['per_apellidos']],
            ];

            $this->db->beginTx();
            $this->db->execute($query, $data);

            $idPersona = $this->db->getLastId('personas', 'per_codigo');

            $query2 = "INSERT INTO usuarios (per_codigo, usu_usuario, usu_clave) VALUES (:per_codigo, :usu_usuario, :usu_clave)";
            $data2 = [
                ['Name' => 'per_codigo', 'Value' => $idPersona],
                ['Name' => 'usu_usuario', 'Value' => $usuario['usu_usuario']],
                ['Name' => 'usu_clave', 'Value' => $usuario['usu_clave']]
            ];

            $this->db->execute($query2, $data2);

            $this->db->commit();

            return $this->db->getLastId('usuarios', 'usu_codigo');
        } catch (Exception $ex) {
            $this->db->rollback();
            throw $ex;
        }
    }

    public function isExists(String $usuario)
    {
        try {
            $usu = $this->db->getData("SELECT COUNT(usu_codigo) usuario FROM usuarios WHERE usu_usuario=:usu_usuario", [['Name' => ':usu_usuario', 'Value' => $usuario]]);
            return  $usu[0]['usuario'];
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function login($usuario, $clave)
    {
        try {
            $query = "SELECT usu_codigo, usu_usuario, usu_clave FROM USUARIOS WHERE usu_usuario = :usu_usuario";

            $res = $this->db->getData($query, [['Name' => 'usu_usuario', 'Value' => $usuario]]);

            if (count($res) == 0) {
                return false;
            }

            if ($clave != $res[0]['usu_clave']) {
                return false;
            }

            return $res[0]['usu_codigo'];
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
