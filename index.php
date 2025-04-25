<?php

require_once __DIR__ . "/framework/DB.class.php";

use Framework\DB;

$db = new DB;

$data = $db->get("select * from users");

echo "<pre>";
print_r($data);
