<?php

require_once __DIR__ . "/framework/DB.class.php";

use Framework\DB;

$db = new DB;



/* $id = $db->table('users')->insert([
    'username' => 'muath',
    'email' => 'info@melooni.com',
    'password' => sha1(132),
]);

echo $id; */

/* $db->table('users')->where('id', 20)->update([
    'username' => 'ali 102030'
]);
 */

//echo $db->table('users')->delete();

$data = $db->table('users')->where('active', '!=', "active")->orderBy('id', 'desc')->limit(1, 5)->get();

echo "<pre>";
print_r($data);
