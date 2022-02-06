<?php

require_once 'Database.php';

class Article
{
    private $connection;
    public $query;
    public $message;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function allArticles($start = 0, $limit = 999)
    {
        if ($start or $limit) {
            $filter = (int) $start . ',' . (int) $limit;
        }
        $this->query = $this->connection->query("SELECT * FROM `articles` WHERE id > 0 ORDER BY update_date DESC LIMIT " . $filter);

        return $this->query->fetch_all(MYSQLI_ASSOC);
    }

    public function articleOnCategory($categoryId, $start = 0, $limit = 999)
    {
        if ($start or $limit) {
            $filter = (int) $start . ',' . (int) $limit;
        }
        $this->query = $this->connection->query("SELECT * FROM `articles` WHERE `category_id` =" . (int) $categoryId . ' ' . "ORDER BY update_date DESC LIMIT " . $filter);

        return $this->query->fetch_all(MYSQLI_ASSOC);
    }

    public function countArticles()
    {
        $this->query = $this->connection->query("SELECT count(*) FROM `articles` WHERE id > 0");

        return $this->query->fetch_row();
    }

    public function serchOnArticle($searchQuery = '')
    {
        $searchQuery = '%' . $searchQuery . '%';
        $this->query = $this->connection->query("SELECT * FROM `articles` WHERE `title` LIKE" . "'$searchQuery'");

        return $this->query->fetch_all(MYSQLI_ASSOC);
    }

    public function getArticle($articleId)
    {
        $this->query = $this->connection->query("SELECT * FROM articles WHERE id ='" . (int) $articleId . "'");

        return $this->query->fetch_assoc();
    }

    public function publicate($title, $categoryId, $text, $img, $user, $date)
    {
        $articles = "
             INSERT INTO articles (title, category_id, text, img, author_id, update_date, views)
             VALUES('$title', '$categoryId', '$text', '$img', '$user', '$date', '0')";

         if ($this->connection->query($articles)) {
             $this->message = "Статья успешно добавлена";
         } else {
             $this->message = $this->connection->error;
         }
         return $this->message;
    }
}