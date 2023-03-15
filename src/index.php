<?php

require "./includes/Db.class.php";
require "./includes/Lists.class.php";

$db = new Db();
$lists = new Lists($db);

$listss = $lists->getListById(1);


echo "<pre>";
var_dump($listss);
exit;
