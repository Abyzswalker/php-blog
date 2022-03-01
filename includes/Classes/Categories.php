<?php

require_once 'Database.php';

class Categories
{
    private $connection;
    public $error;
    public $query;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function addCategory($title)
    {
        $stmt = $this->connection->prepare("SELECT * FROM articles_categories WHERE `title` = ?");
        $stmt->execute(["$title"]);

        $this->query = $stmt->get_result();

        if ($this->query->fetch_row() !== null) {
            $this->error['error'] = 'Данная категория уже существует';
            return $this->error;
        } else {
            $this->query = "
             INSERT INTO articles_categories (title)
             VALUES('$title')";

            if ($this->connection->query($this->query)) {
                return $this->connection->insert_id;
            } else {
                $this->error = $this->connection->error;
                return $this->error;
            }
        }
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