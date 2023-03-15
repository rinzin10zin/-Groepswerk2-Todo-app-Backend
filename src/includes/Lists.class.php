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
}