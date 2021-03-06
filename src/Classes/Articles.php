<?php

namespace Blog\Classes;

use mysqli;

class Articles
{
    private $connection;
    private $query;
    private $message;
    private $error;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function allArticles($start = 0, $limit = 999)
    {
        $stmt = $this->connection->prepare("SELECT * FROM articles WHERE `id` > 0 ORDER BY update_date DESC LIMIT ?, ?");
        $stmt->execute(["$start", "$limit"]);
        $this->query = $stmt->get_result();

        if($this->query && $this->query->num_rows > 0){
            return $this->query->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public function articleOnCategory($categoryId, $start = 0, $limit = 999)
    {
        $stmt = $this->connection->prepare("SELECT * FROM articles WHERE `category_id` = ? ORDER BY update_date DESC LIMIT ?, ?");
        $stmt->execute(["$categoryId","$start", "$limit"]);
        $this->query = $stmt->get_result();

        return $this->query->fetch_all(MYSQLI_ASSOC);
    }

    public function countArticles($id = null)
    {
        if ($id === null) {
            $stmt = $this->connection->prepare("SELECT count(id) FROM articles WHERE `id` > 0");
            $stmt->execute();
        } else {
            $stmt = $this->connection->prepare("SELECT count(id) FROM articles WHERE `category_id` = ?");
            $stmt->execute(["$id"]);
        }
        $this->query = $stmt->get_result();

        return $this->query->fetch_row();
    }

    public function serchOnArticle($searchQuery = '')
    {
        $stmt = $this->connection->prepare("SELECT * FROM articles WHERE `title` LIKE ?");
        $stmt->execute(["%$searchQuery%"]);
        $this->query = $stmt->get_result();

        return $this->query->fetch_all(MYSQLI_ASSOC);
    }

    public function getArticle($articleId)
    {
        $stmt = $this->connection->prepare("SELECT * FROM articles WHERE `id` = ?");
        $stmt->execute(["$articleId"]);
        $this->query = $stmt->get_result();

        return $this->query->fetch_assoc();
    }

    public function publicate($title, $categoryId, $text, $img, $user)
    {
        date_default_timezone_set('Europe/Kiev');
        $today = date("Y-m-d H:i:s");
        $views = 0;

        $stmt = $this->connection->prepare("
             INSERT INTO articles (title, category_id, text, img, author_id, update_date, views)
             VALUES(?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param('sissisi', $title, $categoryId, $text, $img, $user, $today, $views);

        if ($stmt->execute() == true) {
            $this->message = "???????????? ?????????????? ??????????????????";
            return $this->message;
        } else {
            $this->error = $this->connection->error;
            return $this->error;
        }
    }
    public function update($title, $categoryId, $text, $img, $articleId)
    {
        date_default_timezone_set('Europe/Kiev');
        $today = date("Y-m-d H:i:s");

        $stmt = $this->connection->prepare("
             UPDATE articles 
             SET title = ?, category_id = ?, text = ?, img = ?, update_date = ?
             WHERE id = ?");

        $stmt->bind_param('sisssi', $title, $categoryId, $text, $img, $today, $articleId);

        if ($stmt->execute() == true) {
            $this->message = "???????????? ?????????????? ??????????????????";
            return $this->message;
        } else {
            $this->error = $this->connection->error;
            return $this->error;
        }
    }

    public function delete($articleId)
    {
        $stmt = $this->connection->prepare("
            DELETE FROM articles WHERE id = ?;
            ");

        if ($stmt->execute(["$articleId"])) {
            $this->message = "???????????? ?????????????? ??????????????";
            return $this->message;
        } else {
            $this->error = $this->connection->error;
            return $this->error;
        }
    }
}