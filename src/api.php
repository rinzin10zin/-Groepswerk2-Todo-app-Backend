<?php
require "./includes/Db.class.php";
require "./includes/Lists.class.php";
require "./includes/functions.php";

// $args['qsparts'] = explode('/', $args['qs']);



$args = $_REQUEST; //Gets the Query from the url
// var_dump($args); //If you check this for http://localhost/api/lists/2, you get array{resource=>lists, id=>2}
$method = $_SERVER["REQUEST_METHOD"]; //Gets the method from the requests; eg. GET, POST, DELETE
$response = new StdClass;
$response->args = $args; //Easy for developing, so you can see the query in the response
$allowed_filters = ["resource", "id", "pma_lang", "pmaUser-1"];

// echo "lol";
// var_dump($_REQUEST);
// var_dump(makeBody([], $allowed_filters));
// var_dump(makeFilter(["name", "year", "word", "important", "color", "photo"]));
// var_dump(makeFilter(["name", "type_id", "category_id", "important", "color", "photo"]));

// echo "<pre>";
// var_dump($_POST);
// exit;

//Open Database connection
$db = new Db();
$lists = new Lists($db);

// echo "<pre>";
// var_dump($lists->todoIsChecked(1));
// exit;

// $data = ["name" => "list2-test", "type_id" => "2", "category_id" => NULL, "important" => "0", "color" => "pink", "photo" => NULL];
// $lists->addList($data);
// echo "added";
// exit;

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
                // makeBody(["name", "type_id", "category_id"], ["important", "color", "photo"]);
                if (!isset($args["id"])) {
                    // needed data: $name, $type_id, $category_id, $important, $color, $photo
                    // $data = ["name" => "list1-test", "type_id" => "2", "category_id" => NULL, "important" => "0", "color" => "pink", "photo" => NULL];
                    $response->data = $lists->addList($_POST);
                    $response->status = "success";
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
            case 'PATCH':
                // todo/id => checks
                if (isset($args["id"]) && is_numeric($args["id"])) {
                    $lists->checkTodo($args["id"]);
                    header('Content-Type: application/json; charset=utf-8');
                    print json_encode(["status" => "success", "message" => "Id: " . $args["id"] . " is succesfully checked"]);
                    exit;
                }
                break;
                // case 'PATCH':
                //     // todo/id => checks and unchecks
                //     if (isset($args["id"]) && is_numeric($args["id"])) {
                //         $isChecked = $lists->todoIsChecked($args["id"]);
                //         $isChecked ? $lists->uncheckTodo($args["id"]) : $lists->checkTodo($args["id"]);
                //         $msg = $isChecked ? "unchecked" : "checked";
                //         header('Content-Type: application/json; charset=utf-8');
                //         print json_encode(["status" => "success", "message" => "Id: " . $args["id"] . " is succesfully " . $msg]);
                //         exit;
                //     }
                //     break;
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
}


header('Content-Type: application/json; charset=utf-8');
print json_encode($response);
exit;
