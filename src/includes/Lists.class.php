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
        return $this->db->executeGetQuery($sql);
    }
    public function getAllLists($limit = 500)
    {
        $sql = "SELECT list.id, list.name, list.color, list.photo, list.important, list.createdAt, category.name AS category_name, type.name AS type_name
        FROM list
        LEFT JOIN category ON list.category_id = category.id
        LEFT JOIN type ON list.type_id = type.id;
        ";
        return $this->db->executeGetQuery($sql);
    }
    public function getListById($id)
    {
        $sql = "SELECT list.id, list.name, list.color, list.photo, list.important, list.createdAt, category.name AS category_name, type.name AS type_name
        FROM list
        LEFT JOIN category ON list.category_id = category.id
        LEFT JOIN type ON list.type_id = type.id
        WHERE list.id = :id;
        ";
        return $this->db->executeGetOneQuery($sql, ["id" => $id]);
    }
    public function addList($name, $type_id, $category_id, $important, $color, $photo)
    {
        $sql = "INSERT INTO `list` (`id`, `name`, `createdAt`, `type_id`, `category_id`, `important`, `color`, `photo`) VALUES (NULL, :name, CURRENT_TIMESTAMP, :type_id, :category_id, :important, :color, :photo);";
        return $this->db->executeGetOneQuery($sql, ["name" => $name, "type_id" => $type_id, "category_id" => $category_id, "important" => $important, "color" => $color, "photo" => $photo]);
    }
    public function addTodo($name, $list_id, $checked = "0")
    {
        $sql = "INSERT INTO `list-item` (`id`, `name`, `checked`, `list_id`) VALUES (NULL, :name, :checked, :list_id);";
        return $this->db->executeGetOneQuery($sql, ["name" => $name, "checked" => $checked, "list_id" => $list_id]);
    }
}
