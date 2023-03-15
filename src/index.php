<?php

require "./includes/Db.class.php";
require "./includes/Lists.class.php";

$db = new Db();
$lists = new Lists($db);

$list = $lists->addTodo("todo1", 8);
$lists = $lists->getAllLists();


echo "<pre>";
var_dump($lists);
exit;
