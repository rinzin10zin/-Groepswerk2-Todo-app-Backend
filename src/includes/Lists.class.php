<?php

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
        return $this->db->executeOneQuery($sql, ["id" => $id]);
    }
    public function addList($data)
    {
        // needed data: $name, $type_id, $category_id, $important, $color, $photo
        $keys = array_keys($data);
        $cols = implode(', ', $keys);


        $values = array_map(function ($key) {
            return ':' . $key;
        }, $keys);
        $values = implode(', ', $values);
        $sql = "INSERT INTO `list` ($cols) VALUES ($values);";
        var_dump($sql);
        return $this->db->executeOneQuery($sql, $data);
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
        return $this->db->executeOneQuery($sql, ["id" => $id]);
    }
    public function uncheckTodo($id)
    {
        $sql = "UPDATE `list_item` SET `checked` = '0' WHERE `list_item`.`id` = :id";
        return $this->db->executeOneQuery($sql, ["id" => $id]);
    }
    public function addTodo($data)
    {
        // needed data:  `name`, `checked`, `list_id`
        $keys = array_keys($data);
        $cols = implode(', ', $keys);


        $values = array_map(function ($key) {
            return ':' . $key;
        }, $keys);
        $values = implode(', ', $values);
        $sql = "INSERT INTO `list-item` ($cols) VALUES ($values);";
        return $this->db->executeOneQuery($sql, $data);
    }
    public function deleteList($id)
    {
        $sql = "DELETE FROM `list` WHERE `list`.`id` = :id";
        return $this->db->executeOneQuery($sql, ["id" => $id]);
    }
    public function deleteTodo($id)
    {
        $sql = "DELETE FROM `list-item` WHERE `list-item`.`id` = :id";
        return $this->db->executeOneQuery($sql, ["id" => $id]);
    }
    public function getAllTodoByList($id)
    {
        $sql = "SELECT * FROM `list_item` WHERE `list_id` = :id";
        return $this->db->executeQuery($sql, ["id" => $id]);
    }
}
