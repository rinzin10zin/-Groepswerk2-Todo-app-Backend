<?php

// require "./includes/Db.class.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// var_dump("Hello WORLD!");
// var_dump($_SERVER["DOCUMENT_ROOT"]);

require "./includes/Db.class.php";
require "./includes/Lists.class.php";

$db = new Db();
$lists = new Lists($db);

// $list = $lists->deleteTodo(5);
// $list = $lists->deleteList(10);
// $lists = $lists->getAllLists();


echo "<pre>";
var_dump($lists);
exit;
