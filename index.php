<?php

require_once __DIR__ . "/framework/DB.class.php";

use Framework\DB;

$db = new DB;

$data = $db->table('users')->where('id', 1)->where('active', '!=', 'active')->get();

echo "<pre>";
print_r($data);
