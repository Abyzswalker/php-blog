<?php

namespace Blog\Classes;

use mysqli;

class Categories
{
    private $connection;
    private $query;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function allCategory($categoryId = null)
    {
        if (isset($categoryId)){
            $stmt = $this->connection->prepare("SELECT * FROM articles_categories WHERE `id` = ?");
            $stmt->execute(["$categoryId"]);
        } else {
            $stmt = $this->connection->prepare("SELECT * FROM articles_categories WHERE `id` > 0");
            $stmt->execute();
        }
        $this->query = $stmt->get_result();

        return $this->query->fetch_all(MYSQLI_ASSOC);
    }
}