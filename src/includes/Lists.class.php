<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Lists
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll($limit = 500)
    {
        $sql = "SELECT * FROM list";
        return $this->db->executeQuery($sql);
    }
    public function getAllLists($limit = 500)
    {
        $sql = "SELECT list.id, list.name, list.color, list.photo, list.important, list.createdAt, category.name AS category_name, type.name AS type_name
        FROM list
        LEFT JOIN category ON list.category_id = category.id
        LEFT JOIN type ON list.type_id = type.id;
        ";


        return $this->db->executeQuery($sql);
    }
    public function getListById($id)
    {
        $sql = "SELECT list.id, list.name, list.color, list.photo, list.important, list.createdAt, category.name AS category_name, type.name AS type_name
        FROM list
        LEFT JOIN category ON list.category_id = category.id
        LEFT JOIN type ON list.type_id = type.id
        WHERE list.id = :id;
        ";

        $listInfo = $this->db->executeOneQuery($sql, ["id" => $id]);
        if (!$listInfo) {
            return false;
        }

        // if (!$listInfo) {
        //     $listInfo->error = "list with id ${id} not found";
        //     $listInfo->status = "failed";
        //     http_response_code(404);
        //     return $listInfo;
        // }

        $sql = "SELECT * FROM list_item WHERE list_id = :id";
        $data =  $this->db->executeQuery($sql, ["id" => $id]);

        $listInfo->listItems = $data;

        return $listInfo;
    }
    public function addList($body)
    {
        // needed data: $name, $type_id, $category_id, $important, $color, $photo
        $keys = array_keys($body);
        $cols = implode(', ', $keys);


        $values = array_map(function ($key) {
            return ':' . $key;
        }, $keys);
        $values = implode(', ', $values);
        $sql = "INSERT INTO `list` ($cols) VALUES ($values);";

        return $this->db->executeInsertQuery($sql, $body);
    }
    public function getTodo($id)
    {
        $sql = "SELECT * FROM `list_item` WHERE `id` = :id";
        return $this->db->executeOneQuery($sql, ["id" => $id]);
    }
    public function todoIsChecked($id)
    {
        $sql = "SELECT * FROM `list_item` WHERE `list_id` = :id";
        $response = $this->db->executeOneQuery($sql, ["id" => $id]);
        return $response ? true : false;
    }
    public function checkTodo($id)
    {
        $sql = "UPDATE `list_item` SET `checked` = '1' WHERE `list_item`.`id` = :id";

        return $this->db->executePatchQuery($sql, ["id" => $id]);
    }
    public function uncheckTodo($id)
    {
        $sql = "UPDATE `list_item` SET `checked` = '0' WHERE `list_item`.`id` = :id";
        return $this->db->executePatchQuery($sql, ["id" => $id]);
    }
    public function addTodo($body)
    {
        // needed data:  `name`, `checked`, `list_id`
        $keys = array_keys($body);
        $cols = implode(', ', $keys);


        $values = array_map(function ($key) {
            return ':' . $key;
        }, $keys);
        $values = implode(', ', $values);
        $sql = "INSERT INTO `list_item` ($cols) VALUES ($values);";
        return $this->db->executeInsertQuery($sql, $body);
    }
    public function addCat($body)
    {
        // needed data:  category name
        $keys = array_keys($body);
        $cols = implode(', ', $keys);


        $values = array_map(function ($key) {
            return ':' . $key;
        }, $keys);
        $values = implode(', ', $values);
        $sql = "INSERT INTO `category` ($cols) VALUES ($values);";
        return $this->db->executeInsertQuery($sql, $body);
    }
    public function deleteList($id)
    {
        $sql = "DELETE FROM `list` WHERE `list`.`id` = :id";
        $response = $this->db->executeDeleteQuery($sql, ["id" => $id]);
        return $response;
    }
    public function deleteTodo($id)
    {
        $sql = "DELETE FROM `list_item` WHERE `list_item`.`id` = :id";
        $response = $this->db->executeDeleteQuery($sql, ["id" => $id]);
        return $response;
    }
    public function getAllTodoByList($id)
    {
        $sql = "SELECT * FROM `list_item` WHERE `list_id` = :id";
        return $this->db->executeQuery($sql, ["id" => $id]);
    }
    public function getAllCategoryNames()
    {

        $sql = "SELECT name, id From category";
        return $this->db->executeQuery($sql);
    }
    public function updateList($id, $data)
    {
        $sql = "UPDATE `list` SET ";
        $params = array();

        foreach ($data as $field => $value) {
            $sql .= "`$field` = :$field, ";
            $params[$field] = $value;
        }

        $sql = rtrim($sql, ", ");
        $sql .= " WHERE `id` = :id";
        $params["id"] = $id;

        return $this->db->executeUpdateQuery($sql, $params);
    }
}
