<?php

namespace Framework;

require_once __DIR__ . "/../config.php";

use PDO;

class DB
{
    protected $conn = null;
    protected $table = "";
    protected $where = "";

    function __construct()
    {
        if (is_null($this->conn)) {
            $this->conn = new PDO("mysql:host=localhost;dbname=" . __DB_NAME . ";charset=utf8", __DB_USERNAME, __DB_PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function where($key, $proccess, $value = null)
    {
        if (in_array($proccess, ['=', '!=', '>', "<", 'like'])) {
            $this->where .= empty($this->where) ? "where $key $proccess '$value'" : " and $key $proccess '$value'";
        } else {
            $this->where .= empty($this->where) ? "where $key = '$proccess'" : " and $key = '$proccess'";
        }
        return $this;
    }


    public function get()
    {
        $sql = "select * from $this->table $this->where";
        $q = $this->conn->prepare($sql);
        $q->execute();
        return $q->fetchAll(PDO::FETCH_ASSOC);
    }
}
