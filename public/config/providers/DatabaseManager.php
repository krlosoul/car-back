<?php

namespace Config\providers;

use Exception;
use PDO;

/**
 * Proveedor que permite administrar las operaciones en la base de datos.
 */
final class DatabaseManager
{

    /**
     * @var Cn
     */
    private $cn;

    public function __construct(PDO $cn)
    {
        $this->cn = $cn;
        $this->cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Inicia una nueva transacción para suprimir el auto commit
     */
    public function beginTx()
    {
        $this->cn->beginTransaction();
    }

    /**
     * Finaliza una transacción y realiza el guardado de los datos
     */
    public function commit()
    {
        $this->cn->commit();
    }

    /**
     * Finaliza una transacción y cancelando el guardado de los datos
     */
    public function rollback()
    {
        $this->cn->rollBack();
    }

    /**
     * Obtiene información desde la base de datos
     * @param string $query Instrucción sql que se ejecutará en la base de datos, para el manejo de parametros, estos deberán llevar el sufijo ":" y luego el nombre del parametro ejemplo SELECT * FROM TABLE WHERE CAMPO = :campo
     * @param array $data Parametros de ejecución para la consulta sql. Los parametros llevan una estructura de arreglo bidimencional con la siguiente estructura [['Name' => ':campo', 'Value': $campo]] donde ':campo' hace referencia al nombre del parametro y $campo hace referencia al valor del mismo
     * @return array Arreglo con la información encontrada en la base de datos
     */
    public function getData(string $query, $data = null): array
    {
        try {
            $rows = [];
            $tmp = $this->cn->prepare($query);
            if (isset($data) && count($data) > 0) {
                foreach ($data as $d) {
                    $tmp->bindParam($d['Name'], $d['Value']);
                }
            }

            $tmp->execute();
            while ($row = $tmp->fetch(PDO::FETCH_ASSOC)) {
                $rows[] = $row;
            }

            return $rows;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Obtiene información desde la base de datos
     * @param string $query Instrucción sql que se ejecutará en la base de datos, para el manejo de parametros, estos deberán llevar el sufijo ":" y luego el nombre del parametro ejemplo SELECT * FROM TABLE WHERE CAMPO = :campo
     * @param array $data Parametros de ejecución para la consulta sql. Los parametros llevan una estructura de arreglo bidimencional con la siguiente estructura [['Name' => ':campo', 'Value': $campo]] donde ':campo' hace referencia al nombre del parametro y $campo hace referencia al valor del mismo
     * @return int Valor entero que informa el código de la última fila insertada
     */
    public function execute(string $query, $data = null, $lastId = null): int
    {
        try {

            $tmp = $this->cn->prepare($query);
            if (isset($data) && count($data) > 0)
                foreach ($data as $d) {
                    $tmp->bindParam($d['Name'], $d['Value']);
                }
            $tmp->execute();
            $result = -1;
            if ($lastId !== null)
                $result = (int)$this->cn->lastInsertId();
            else
                return $result;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getLastId(string $table, string $column)
    {
        try {
            $table = strtolower($table);
            $column = strtolower($column);
            $tmp = $this->getData("SELECT CURRVAL(pg_get_serial_sequence('$table','$column')) id");
            return $tmp[0]['id'];
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getSequenceValue(string $sequence, string $table, string $column, string $prefix = '')
    {

        try {
            $query = "SELECT NEXTVAL('" . $sequence . "') as value";

            $data = $this->getData($query);

            $value = $data[0]['value'];

            $query = "SELECT " . $column . " FROM " . $table . " WHERE " . $column . " = ";
            if ($prefix !== '') {
                $query .= "'" . $prefix . $value . "'";
            } else {
                $query .= $value;
            }

            if (count($this->getData($query)) === 0) {
                return $value;
            } else {
                return $this->getSequenceValue($sequence, $table, $column, $prefix);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
