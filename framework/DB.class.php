<?php

namespace Framework;

require_once __DIR__ . "/../config.php";

use PDO;

class DB
{
    protected $conn = null;
    protected $table = "";
    protected $where = "";
    protected $select = "*";
    protected $order_by = "";
    protected $limit = "";
    protected $where_values = [];

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
            $this->where .= empty($this->where) ? "where $key $proccess ?" : " and $key $proccess ?";
            $this->where_values[] = $value;
        } else {
            $this->where .= empty($this->where) ? "where $key = ?" : " and $key = ?";
            $this->where_values[] = $proccess;
        }
        return $this;
    }

    public function select($value)
    {
        $this->select = $value;
        return $this;
    }

    public function limit($page, $count = null)
    {
        if (is_null($count)) {
            $this->limit = "limit $page";
        } else {
            $p = ($page - 1) * $count;
            $this->limit = "limit $p, $count";
        }
        return $this;
    }

    public function orderBy($key, $type = 'asc')
    {
        $this->order_by = "order by $key $type";
        return $this;
    }


    public function get()
    {  
        $this->where .= empty($this->where) ? "where deleted_at is null" : ' and deleted_at is null';
        $sql = "select $this->select from $this->table $this->where $this->order_by $this->limit";
        $q = $this->conn->prepare($sql);
        $q->execute($this->where_values);
        $data = $q->fetchAll(PDO::FETCH_ASSOC);
        $this->clear_data();
        return $data;
    }

    public function clear_data()
    {
        $this->table = "";
        $this->where = "";
        $this->select = "*";
        $this->order_by = "";
        $this->limit = "";
        $this->where_values = [];
    }

    public function insert($data) {
        $k = "";
        $v = "";
        $k_v = [];
        foreach ($data as $key => $val) {
            $k .= "$key,";
            $v .= "?,";
            $k_v[] = $val;
        }
        $k = substr($k, 0, strlen($k) - 1);
        $v = substr($v, 0, strlen($v) - 1);
        
        $sql = "insert into $this->table ($k) values ($v)";
        $q = $this->conn->prepare($sql);
        $q->execute($k_v);
        return $this->conn->lastInsertId();
    }

    public function update($data) {
        $code = "";
        $value = [];
        foreach($data as $k => $val) {
            $code .= "$k = ?,";
            $value[] = $val;
        }
        $new_values = array_merge($value, $this->where_values);
        $code = substr($code, 0, strlen($code) - 1);
        $sql = "update $this->table set $code $this->where";
        $q = $this->conn->prepare($sql);
        $q->execute($new_values);
        $this->clear_data();
        return 1;
    }

    public function delete() {
        
        $value[] = date('Y-m-d h:i:sa');
        $new_values = array_merge($value, $this->where_values);
        $sql = "update $this->table set deleted_at = ? $this->where";
        $q = $this->conn->prepare($sql);
        $q->execute($new_values);
        $this->clear_data();
        return 1;
    }

}
