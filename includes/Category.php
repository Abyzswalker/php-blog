<?php

require_once 'Database.php';

class Category
{
    private $connection;
    public $query;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function allCategory($categoryId = null)
    {
        if (isset($id)){
            $this->query = $this->connection->query("SELECT * FROM `articles_categories` WHERE `id` =" . (int) $categoryId);
        } else {
            $this->query = $this->connection->query("SELECT * FROM `articles_categories` WHERE id > 0");
        }

        return $this->query->fetch_all(MYSQLI_ASSOC);
    }
}