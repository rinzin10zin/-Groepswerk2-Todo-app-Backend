<?php

require "./includes/Db.class.php";

$db = new Db();

$data = $db->executeGetQuery("SELECT * FROM list");

echo "<pre>";
var_dump($data);
exit;
