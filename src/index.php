<?php

// require "./includes/Db.class.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

var_dump("Hello WORLD!");

require $_SERVER["DOCUMENT_ROOT"] . "/todos/includes/Db.class.php";
require $_SERVER["DOCUMENT_ROOT"] . "/todos/includes/Lists.class.php";

$db = new Db();
$lists = new Lists($db);

// $list = $lists->deleteTodo(5);
// $list = $lists->deleteList(10);
$lists = $lists->getAllLists();


echo "<pre>";
var_dump($lists);
exit;
