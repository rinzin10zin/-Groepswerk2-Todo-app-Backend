<?php
require "./includes/Db.class.php";
require "./includes/Lists.class.php";
require "./includes/functions.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");



$args = $_REQUEST; //Gets the Query from the url
$method = $_SERVER["REQUEST_METHOD"]; //Gets the method from the requests; eg. GET, POST, DELETE
$response = new StdClass;
// $response->args = $args; //Easy for developing, so you can see the query in the response


//Open Database connection
$db = new Db();
$lists = new Lists($db);

// localhost/api
if (!isset($args["resource"])) {
    $response->data = $lists->getAll();
    header('Content-Type: application/json; charset=utf-8');
    print json_encode($response);
    exit;
}
switch ($args["resource"]) {
    case 'lists':
        if ($method === "GET" && !isset($args["id"])) {
            $response->data = $lists->getAllLists();
            $response->status = "success";
            header('Content-Type: application/json; charset=utf-8');
            print json_encode($response);
            exit;
        }
        break;

    case 'list':
        switch ($method) {
            case 'POST':
                if (!isset($args["id"])) {
                    // needed data: $name, $type_id, $category_id, $important, $color, $photo
                    // $data = ["name" => "CNTest", "type_id" => "2", "category_id" => NULL, "important" => "0", "color" => "pink", "photo" => NULL];
                    $response->executed = $lists->addList($_POST);

                    $response->status = $response->executed ?  "success" : "failed";

                    header('Content-Type: application/json; charset=utf-8');
                    print json_encode($response);
                    exit;
                }
                break;
            case 'DELETE':
                if (is_numeric($args["id"])) {
                    $lists->deleteList($args["id"]);
                    header('Content-Type: application/json; charset=utf-8');
                    print json_encode(["status" => "success", "message" => "Successfully deleted list with id: " . $args["id"]]);
                    exit;
                }
                break;

            default:
                // GET: localhost/api/list/id
                if (is_numeric($args["id"])) {
                    $response->data = $lists->getListById($args["id"]);
                    $response->status = "success";
                    header('Content-Type: application/json; charset=utf-8');
                    print json_encode($response);
                    exit;
                }
                break;
        }
        break;

    case 'todos':
        if ($method === "GET" && isset($args["id"]) && is_numeric($args["id"])) {
            $response->data = $lists->getAllTodoByList($args["id"]);
            $response->status = "success";
            header('Content-Type: application/json; charset=utf-8');
            print json_encode($response);
            exit;
        }
        break;

    case 'check':
        if ($method === "PATCH" && isset($args["id"]) && is_numeric($args["id"])) {
            $lists->checkTodo($args["id"]);
            header('Content-Type: application/json; charset=utf-8');
            print json_encode(["status" => "success", "message" => "Id: " . $args["id"] . " is succesfully checked"]);
            exit;
        }
        break;
    case 'uncheck':
        if ($method === "PATCH" && isset($args["id"]) && is_numeric($args["id"])) {
            $lists->uncheckTodo($args["id"]);
            header('Content-Type: application/json; charset=utf-8');
            print json_encode(["status" => "success", "message" => "Id: " . $args["id"] . " is succesfully unchecked"]);
            exit;
        }
        break;

    case 'todo':
        switch ($method) {
            case 'POST':
                if (!isset($args["id"])) {
                    // needed data:  `name`, `checked`, `list_id`
                    $response->data = $lists->addTodo($_POST);
                    $response->status = "success";
                    header('Content-Type: application/json; charset=utf-8');
                    print json_encode($response);
                    exit;
                }
                break;
            case 'DELETE':
                // todo/id => checks and unchecks
                if (isset($args["id"]) && is_numeric($args["id"])) {
                    $lists->deleteTodo($args["id"]);
                    header('Content-Type: application/json; charset=utf-8');
                    print json_encode(["status" => "success", "message" => "Successfully deleted todo with id: " . $args["id"]]);
                    exit;
                }
                break;

            default:
                if (isset($args["id"]) && is_numeric($args["id"])) {
                    $response->data = $lists->getTodo($args["id"]);
                    header('Content-Type: application/json; charset=utf-8');
                    print json_encode($response);
                    exit;
                }
                break;
        }
        break;

    case "categories":
        switch ($method) {
            case "GET":
                $response->data = $lists->getAllCategoryNames();
                header('Content-Type: application/json; charset=utf-8');
                print json_encode($response);
                exit;
        }
}


header('Content-Type: application/json; charset=utf-8');
print json_encode($response);
exit;
