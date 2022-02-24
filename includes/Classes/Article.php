<?php

require_once 'Database.php';

class Article
{
    private $connection;
    public $query;
    public $message;
    public $error;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function allArticles($start = 0, $limit = 999)
    {
        if ($start || $limit) {
            $filter = (int) $start . ',' . (int) $limit;
        }

        //$query = "SELECT * FROM `articles` WHERE id > 0 ORDER BY update_date DESC LIMIT ";

        $this->query = $this->connection->query("SELECT * FROM `articles` WHERE id > 0 ORDER BY update_date DESC LIMIT " . $filter);

        if($this->query && $this->query->num_rows>0){
            return $this->query->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public function articleOnCategory($categoryId, $start = 0, $limit = 999)
    {
        if ($start || $limit) {
            $filter = (int) $start . ',' . (int) $limit;
        }
        $this->query = $this->connection->query("SELECT * FROM `articles` WHERE `category_id` =" . (int) $categoryId . ' ' . "ORDER BY update_date DESC LIMIT " . $filter);

        return $this->query->fetch_all(MYSQLI_ASSOC);
    }

    public function countArticles()
    {
        $this->query = $this->connection->query("SELECT count(id) FROM `articles` WHERE id > 0");

        return $this->query->fetch_row();
    }

    public function serchOnArticle($searchQuery = '')
    {
        $stmt = $this->connection->prepare("SELECT * FROM articles WHERE `title` LIKE ?");

        $stmt->execute(["%$searchQuery%"]);

        $result = $stmt->get_result();

        return $this->query = $result->fetch_all(MYSQLI_ASSOC);

    }

    public function getArticle($articleId)
    {
        $this->query = $this->connection->query("SELECT * FROM articles WHERE id ='" . (int) $articleId . "'");

        return $this->query->fetch_assoc();
    }

    public function publicate($title, $categoryId, $text, $img, $user, $date)
    {
        $this->query = $this->connection->prepare("
             INSERT INTO articles (title, category_id, text, img, author_id, update_date, views)
             VALUES(?, ?, ?, ?, ?, ?, ?)");

        $views = 0;

        $this->query->bind_param('sissisi', $title, $categoryId, $text, $img, $user, $date, $views);

       if ($this->query->execute() == true) {
           $this->message = "Статья успешно добавлена";
           return $this->message;
       } else {
           $this->error = $this->connection->error;
           return $this->error;
       }
    }
    public function update($title, $categoryId, $text, $img, $date, $articleId)
    {
        $this->query = $this->connection->prepare("
             UPDATE articles 
             SET title = ?, category_id = ?, text = ?, img = ?, update_date = ?
             WHERE id = ?");

        $this->query->bind_param('sisssi', $title, $categoryId, $text, $img, $date, $articleId);

        if ($this->query->execute() == true) {
            $this->message = "Статья успешно обновлена";
            return $this->message;
        } else {
            $this->error = $this->connection->error;
            return $this->error;
        }
    }

    public function delete($articleId)
    {
        $this->query = "
            DELETE FROM articles WHERE id = '$articleId';
            ";

        if ($this->connection->query($this->query)) {
            $this->message = "Статья успешно удалена";
            return $this->message;
        } else {
            $this->error = $this->connection->error;
            return $this->error;
        }
    }
}