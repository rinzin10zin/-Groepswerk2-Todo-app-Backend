<?php
require "./includes/Db.class.php";
require "./includes/Lists.class.php";
require "./includes/functions.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");



$args = $_REQUEST; //Gets the Query from the url
$method = $_SERVER["REQUEST_METHOD"]; //Gets the method from the requests; eg. GET, POST, DELETE
if (isset($args["method"])) {
    $method = $args["method"];
}
$response = new StdClass();
$response->status = "loading";


//Open Database connection
$db = new Db();
$lists = new Lists($db);

// localhost/api
if (!isset($args["resource"])) {

    $response->error = "Invalid endpoint";
} else {
    switch ($args["resource"]) {
        case 'lists':
            if ($method === "GET" && !isset($args["id"])) {
                $response->data = $lists->getAllLists();
                $response->status = "success";
                http_response_code(200);
            } else {
                $response->error = "${method} is not a valid method for this endpoint";
            }
            break;



        case 'list':
            switch ($method) {
                case 'POST':
                    if (!isset($args["id"])) {
                        $data = $_POST;
                        if (!isset($data["name"])) {
                            $response->error = "Name is required";
                            break 2;
                        };
                        $response->id = $lists->addList($_POST);
                        $id = $response->id;
                        $response->status = $response->id ?  "success" : "failed";
                        $response->message = $response->id ?  "list with id ${id} inserted" : "failed";
                        http_response_code(201);;
                    }
                    break;
                case 'DELETE':
                    if (is_numeric($args["id"])) {
                        $id = $args["id"];
                        $response->success = $lists->deleteList($args["id"]);
                        if ($response->success) {
                            $response->message = "List with id ${id} succesfully deleted";
                            $response->status = "succes";
                            http_response_code(200);
                            break;
                        } else {
                            $response->message = "List with id ${id} not found";
                            $response->status = "failed";
                            http_response_code(404);;
                            break;
                        }
                    }
                case "GET":
                    if (is_numeric($args["id"])) {
                        $response->data = $lists->getListById($args["id"]);
                        // var_dump($response->data);
                        if ($response->data !== false) {
                            $response->status = "success";
                        } else {
                            $id = $args["id"];
                            $response->error = "list with id ${id} not found";
                            $response->status = "failed";
                            http_response_code(404);
                        }
                    }

                    break;

                case 'PATCH':

                    //IMPORTANT FOR PATCH, DOESNT WORK WITH POSTMAN SO ADD ANOTHER FORMDATA RULE WITH METHOD = PATCH TO 
                    //GET IN THIS CASE

                    // Check if the ID is set and is a number
                    if (!isset($args["id"]) || !is_numeric($args["id"])) {
                        $response->status = "failed";
                        $response->message = "Invalid ID provided";
                        break;
                    }

                    // Check if the required fields are set in the request body
                    $required_fields = array("name", "important", "color", "photo");
                    foreach ($required_fields as $field) {
                        if (!isset($_REQUEST[$field])) {
                            $response->status = "failed";
                            $response->message = "Missing required field: $field";
                            http_response_code(400);

                            break 2;
                        }
                    }

                    // Update the list with the provided data

                    $data = array(
                        "name" => $_REQUEST["name"],
                        "important" => $_REQUEST["important"],
                        "color" => $_REQUEST["color"],
                        "photo" => $_REQUEST["photo"]
                    );

                    $response->executed = $lists->updateList($args["id"], $data);
                    $response->status = $response->executed ? "success" : "failed";
                    $response->success = $response->executed ? true : false;
                    $response->message = $response->executed ? "successfully updated list" : "something went wrong";
                    http_response_code(200);
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
            case 'PATCH':

                //IMPORTANT FOR PATCH, DOESNT WORK WITH POSTMAN SO ADD ANOTHER FORMDATA RULE WITH METHOD = PATCH TO 
                //GET IN THIS CASE

                // Check if the ID is set and is a number
                if (!isset($args["id"]) || !is_numeric($args["id"])) {
                    $response->status = "failed";
                    $response->message = "Invalid ID provided";
                    break;
                }

                // Check if the required fields are set in the request body
                $required_fields = array("name", "important", "color", "photo");
                foreach ($required_fields as $field) {
                    if (!isset($_REQUEST[$field])) {
                        $response->status = "failed";
                        $response->message = "Missing required field: $field";
                        http_response_code(400);

                        break 2;
                    }
                }

                // Update the list with the provided data

                $data = array(
                    "name" => $_REQUEST["name"],
                    "important" => $_REQUEST["important"],
                    "color" => $_REQUEST["color"],
                    "photo" => $_REQUEST["photo"]
                );

                $response->executed = $lists->updateList($args["id"], $data);
                $response->status = $response->executed ? "success" : "failed";
                $response->success = $response->executed ? true : false;
                $response->message = $response->executed ? "successfully updated list" : "something went wrong";
                http_response_code(200);
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



        case 'check':
            if ($method === "PATCH" && isset($args["id"]) && is_numeric($args["id"])) {
                $success = $lists->checkTodo($args["id"]);
                $response->success = $success;
                if ($success) {
                    $response->message = "successfully checked";
                    $response->status = "success";
                    http_response_code(200);
                } else {
                    $response->error = "item is already checked or does not exist";
                    $response->status = "failed";
                    http_response_code(404);
                }
            }
            break;
        case 'uncheck':
            if ($method === "PATCH" && isset($args["id"]) && is_numeric($args["id"])) {
                $id = $args["id"];
                $success = $lists->uncheckTodo($args["id"]);
                $response->success = $success;
                if ($success) {
                    $response->message = "successfully unchecked";
                    $response->status = "success";
                    http_response_code(200);
                } else {
                    $response->error = "item is already unchecked or does not exist";
                    $response->status = "failed";
                    http_response_code(404);
                }
            }
            break;

        case 'todo':
            switch ($method) {
                case 'POST':
                    if (!isset($args["id"])) {
                        // needed data:  `name`, `checked`, `list_id`

                        $response->success = false;
                        $response->status = "failed";
                        if (!isset($args["name"])) {
                            $response->error = "name is not set";
                            break;
                        }
                        if (!isset($args["list_id"])) {
                            $response->error = "list_id is not set";
                            break;
                        }

                        $id = $lists->addTodo($_POST);
                        if ($id) {
                            $response->status = "success";
                            $response->success = true;
                            $response->message = "todo with id ${id} successfully posted";
                        } else {
                            $response->error = "something went wrong";
                            $response->success = false;
                            $response->status = "failed";
                        }
                        break 2;
                    }
                    break;
                case 'DELETE':
                    // todo/id => checks and unchecks
                    if (isset($args["id"]) && is_numeric($args["id"])) {
                        $id = $args["id"];
                        $success =  $lists->deleteTodo($id);

                        $response->status = "failed";
                        $response->success = $success;
                        if ($success) {
                            $response->message = "todo with id ${id} successfully deleted";
                            $response->status = "success";
                        } else {
                            $response->message = "something went wrong";
                        }
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


        case "list/": {
                $response->error = "Please add a valid ID after the /";
                $response->status = "Failed";
                http_response_code(404);

                break;
            }

        default:
            $resource = $args["resource"];
            $response->error = "${resource} is an invalid endpoint";
            $response->status = 404;
            http_response_code(404);
    }
}


header('Content-Type: application/json; charset=utf-8');
print json_encode($response);
exit;
