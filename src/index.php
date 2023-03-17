<?php

require "./includes/Db.class.php";
require "./includes/Lists.class.php";

$db = new Db();
$lists = new Lists($db);

// $list = $lists->deleteTodo(5);
// $list = $lists->deleteList(10);
$lists = $lists->getAllLists();


echo "<pre>";
var_dump($lists);
exit;
