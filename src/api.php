<?php
require "./includes/Db.class.php";
require "./includes/Lists.class.php";

// $args['qsparts'] = explode('/', $args['qs']);



$args = $_REQUEST; //Gets the Query from the url
// var_dump($args); //If you check this for http://localhost/api/lists/2, you get array{resource=>lists, id=>2}
$method = $_SERVER["REQUEST_METHOD"]; //Gets the method from the requests; eg. GET, POST, DELETE
$response = new StdClass;
$response->args = $args; //Easy for developing, so you can see the query in the response

// echo "<pre>";
// echo "lol";
// var_dump($response);
// exit;

//Open Database connection
$db = new Db();
$Lists = new Lists($db);

if ($method == "GET") {
    if ($args == []) {
        $response->data = $Lists->getAll();
        header('Content-Type: application/json; charset=utf-8');
        print json_encode($response);
        exit;
    }


    if ($args["resource"] == "lists") {
        $response->data = $Lists->getAllLists();
        header('Content-Type: application/json; charset=utf-8');
        print json_encode($response);
        exit;
    }
    if ($args["resource"] == "list" && is_numeric($args["id"])) {
        $response->data = $Lists->getListById($args["id"]);
        header('Content-Type: application/json; charset=utf-8');
        print json_encode($response);
        exit;
    }
} else if ($method == "POST") {
}

header('Content-Type: application/json; charset=utf-8');
print json_encode($response);
exit;
