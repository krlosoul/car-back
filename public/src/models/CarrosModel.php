<?php

namespace App\models;

use Config\providers\DatabaseManager as DbManager;
use Cake\Chronos\Date;
use Exception;

final class CarrosModel
{
    private $db;

    public function __construct(DbManager $db)
    {
        $this->db = $db;
    }

    public function get(int $usu_codigo, string $skip, string $take)
    {
        try {
            $query = "SELECT c.car_codigo, c.pro_referencia, c.usu_codigo, c.car_cantidad, c.car_total, c.car_estado, 
            p.pro_descripcion, p.pro_valor 
            FROM carros c 
            JOIN productos p ON c.pro_referencia = p.pro_referencia 
            WHERE c.usu_codigo = :usu_codigo AND c.car_estado != 3 
            OFFSET :skip LIMIT :take";

            $res = $this->db->getData($query, [['Name' => 'skip', 'Value' => $skip], ['Name' => 'take', 'Value' => $take],['Name' => 'usu_codigo', 'Value' => $usu_codigo]]);

            $total = $this->db->getData("SELECT COUNT(c.car_codigo) total 
            FROM carros c 
            JOIN productos p ON c.pro_referencia = p.pro_referencia 
            WHERE c.usu_codigo = :usu_codigo AND c.car_estado != 3",[['Name' => 'usu_codigo', 'Value' => $usu_codigo]]);

            return [
                'data' => $res,
                'total' => $total[0]['total']
            ];
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function post($carro)
    {
        try {
            $query = 'INSERT INTO carros (pro_referencia, usu_codigo, car_cantidad, car_total, car_estado) VALUES 
                (:pro_referencia, :usu_codigo, :car_cantidad, :car_total, :car_estado)';

            $data = [
                ['Name' => 'pro_referencia', 'Value' => $carro['pro_referencia']],
                ['Name' => 'usu_codigo', 'Value' => $carro['usu_codigo']],
                ['Name' => 'car_cantidad', 'Value' => $carro['car_cantidad']],
                ['Name' => 'car_total', 'Value' => $carro['car_total']],
                ['Name' => 'car_estado', 'Value' => $carro['car_estado'] ]
            ];

            $this->db->beginTx();
            $this->db->execute($query, $data);
            $this->db->commit();
        } catch (Exception $ex) {
            $this->db->rollback();
            throw $ex;
        }
    }

    public function putProducto($carro)
    {
        try {
            $query = 'UPDATE carros SET car_cantidad=:car_cantidad, car_estado=:car_estado, car_total=:car_total 
            WHERE pro_referencia=:pro_referencia AND usu_codigo=:usu_codigo AND car_estado != 3';


            $data = [
                ['Name' => 'car_cantidad', 'Value' => $carro['car_cantidad']],
                ['Name' => 'car_estado', 'Value' => $carro['car_estado']],
                ['Name' => 'car_total', 'Value' => $carro['car_total']],
                ['Name' => 'pro_referencia', 'Value' => $carro['pro_referencia']],
                ['Name' => 'usu_codigo', 'Value' => $carro['usu_codigo']]
            ];

            $this->db->beginTx();
            $this->db->execute($query, $data);
            $this->db->commit();
        } catch (Exception $ex) {
            $this->db->rollback();
            throw $ex;
        }
    }

    public function deleteProducto(string $codigo)
    {
        try {
            $query = "DELETE FROM carros WHERE pro_referencia = '" . $codigo . "'";
            $this->db->beginTx();
            $this->db->execute($query);
            $this->db->commit();
        } catch (Exception $ex) {
            $this->db->rollback();
            throw $ex;
        }
    }

    public function clear(int $codigo)
    {
        try {
            $query = "DELETE FROM carros WHERE usu_codigo = " . $codigo . " AND car_estado != 3";
            $this->db->beginTx();
            $this->db->execute($query);
            $this->db->commit();
        } catch (Exception $ex) {
            $this->db->rollback();
            throw $ex;
        }
    }

    public function save($carro)
    {
        try {
            $query = 'UPDATE carros SET car_estado=:car_estado 
            WHERE usu_codigo=:usu_codigo AND car_estado != 3';


            $data = [
                ['Name' => 'usu_codigo', 'Value' => $carro['usu_codigo']],
                ['Name' => 'car_estado', 'Value' => $carro['car_estado']]
            ];

            $this->db->beginTx();
            $this->db->execute($query, $data);
            $this->db->commit();
        } catch (Exception $ex) {
            $this->db->rollback();
            throw $ex;
        }
    }

    public function isExists(String $pro_referencia, int $usu_codigo)
    {
        try {
            $usu = $this->db->getData("SELECT COUNT(car_codigo) total 
            FROM carros 
            WHERE pro_referencia = :pro_referencia AND usu_codigo=:usu_codigo", 
            [
                ['Name' => ':pro_referencia', 'Value' => $pro_referencia],
                ['Name' => ':usu_codigo', 'Value' => $usu_codigo]
            ]);

            return  $usu[0]['total'];
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function historic(int $usu_codigo, string $skip, string $take)
    {
        try {
            $query = "SELECT c.car_codigo, c.pro_referencia, c.usu_codigo, c.car_cantidad, c.car_total, c.car_estado, 
            p.pro_descripcion, p.pro_valor 
            FROM carros c 
            JOIN productos p ON c.pro_referencia = p.pro_referencia 
            WHERE c.usu_codigo = :usu_codigo AND c.car_estado = 3 
            OFFSET :skip LIMIT :take";

            $res = $this->db->getData($query, [['Name' => 'skip', 'Value' => $skip], ['Name' => 'take', 'Value' => $take],['Name' => 'usu_codigo', 'Value' => $usu_codigo]]);

            $total = $this->db->getData("SELECT COUNT(c.car_codigo) total 
            FROM carros c 
            JOIN productos p ON c.pro_referencia = p.pro_referencia 
            WHERE c.usu_codigo = :usu_codigo AND c.car_estado = 3",[['Name' => 'usu_codigo', 'Value' => $usu_codigo]]);

            return [
                'data' => $res,
                'total' => $total[0]['total']
            ];
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
