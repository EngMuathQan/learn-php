<?php

namespace Framework;

require_once __DIR__ . "/../config.php";

use PDO;

class DB
{
    protected $conn;
    function __construct()
    {
        $this->conn = new PDO("mysql:host=localhost;dbname=" . __DB_NAME . ";charset=utf8", __DB_USERNAME, __DB_PASSWORD);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function get($sql)
    {
        $q = $this->conn->prepare($sql);
        $q->execute();
        return $q->fetchAll(PDO::FETCH_ASSOC);
    }
}
