<?php

require "./includes/Db.class.php";
require "./includes/Lists.class.php";

$db = new Db();
$lists = new Lists($db);

$list = $lists->addList("list-test", 2, NULL, 0, "black", NULL);
$lists = $lists->getAllLists();


echo "<pre>";
var_dump($lists);
exit;
