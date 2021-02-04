<?php

namespace App\models;

use Config\providers\DatabaseManager as DbManager;
use Cake\Chronos\Date;
use Exception;

final class ProductosModel
{
    private $db;

    public function __construct(DbManager $db)
    {
        $this->db = $db;
    }

    public function get(string $skip, string $take)
    {
        try {
            $query = "SELECT pro_referencia, pro_descripcion, pro_valor FROM productos OFFSET :skip LIMIT :take";
            $res = $this->db->getData($query, [['Name' => 'skip', 'Value' => $skip], ['Name' => 'take', 'Value' => $take]]);
            $total = $this->db->getData("SELECT COUNT(pro_referencia) total FROM productos ");

            return [
                'data' => $res,
                'total' => $total[0]['total']
            ];
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function post($producto)
    {
        try {
            $query = 'INSERT INTO productos (pro_referencia, pro_descripcion, pro_valor) VALUES 
                (:pro_referencia, :pro_descripcion, :pro_valor)';

            $data = [
                ['Name' => 'pro_referencia', 'Value' => $producto['pro_referencia']],
                ['Name' => 'pro_descripcion', 'Value' => $producto['pro_descripcion']],
                ['Name' => 'pro_valor', 'Value' => $producto['pro_valor']]
            ];

            $this->db->beginTx();
            $this->db->execute($query, $data);
            $this->db->commit();
        } catch (Exception $ex) {
            $this->db->rollback();
            throw $ex;
        }
    }

    public function put($producto)
    {
        try {
            $query = 'UPDATE productos SET pro_descripcion=:pro_descripcion, pro_valor=:pro_valor 
            WHERE pro_referencia=:pro_referencia';


            $data = [
                ['Name' => 'pro_referencia', 'Value' => $producto['pro_referencia']],
                ['Name' => 'pro_descripcion', 'Value' => $producto['pro_descripcion']],
                ['Name' => 'pro_valor', 'Value' => $producto['pro_valor']]
            ];

            $this->db->beginTx();
            $this->db->execute($query, $data);
            $this->db->commit();
        } catch (Exception $ex) {
            $this->db->rollback();
            throw $ex;
        }
    }

    public function delete(string $codigo)
    {
        try {
            $query = "DELETE FROM productos WHERE pro_referencia = '" . $codigo . "'";
            $this->db->beginTx();
            $this->db->execute($query);
            $this->db->commit();
        } catch (Exception $ex) {
            $this->db->rollback();
            throw $ex;
        }
    }

}
