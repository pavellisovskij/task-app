<?php

namespace app\core;

use PDO;

class Db
{
    protected $db;
    protected $db_name;

    public function __construct()
    {
        $db_connection = require 'app/config/db.php';
        $this->db_name = $db_connection['db_name'];
        $this->db = new PDO(
            'mysql:host=' . $db_connection['host'] .
            ';port=' . $db_connection['port'] .
            ';dbname=' . $db_connection['db_name'],
            $db_connection['user'],
            $db_connection['password']
        );
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);

        if (!empty($params)) {
            foreach ($params as $key => $val) {
                $stmt->bindValue(':' . $key, $val);
            }
        }
        $stmt->execute();
        return $stmt;
    }

    public function row($sql, $params = [])
    {;
        $result = $this->query($sql, $params);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function column($sql, $params = [])
    {
        $result = $this->query($sql, $params = []);
        return $result->fetchColumn();
    }

    public function insert($table_name, $values)
    {
        $column_names = $this->getColumnsNames($table_name);
        $binds = array_map(fn($column_name) => ':' . $column_name, $column_names);
        $str_binds = implode(', ', $binds);
        $column_names = implode(', ', $column_names);

        $sql = "INSERT $table_name ($column_names) VALUES ($str_binds)";
        $stmt = $this->db->prepare($sql);

        foreach ($binds as $bind) {
            $stmt->bindParam($bind,$values[substr($bind, 1)]);
        }

        try {
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
        } catch (\Exception $e){
            return $e->getMessage(); //return exception
        }
    }

    public function getColumnsNames($table)
    {
        $sql = "DESCRIBE $table";
        $stmt = $this->db->prepare($sql);

        try {
            if($stmt->execute()){
                $column_names = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
                unset($column_names[0]);
                return $column_names;
            }
        } catch (\Exception $e){
            return $e->getMessage(); //return exception
        }
    }
}