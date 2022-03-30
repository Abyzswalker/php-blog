<?php

use Blog\Classes\Articles;
use Blog\Classes\Categories;
use Blog\Classes\Users;

require_once __DIR__ . '/vendor/autoload.php';
require_once 'db.php';

$articleRow = new Articles($connection);
$usersRow = new Users($connection);
$articleCategoryRow = new Categories($connection);

$allArticleCategory = $articleCategoryRow->allCategory();

if (!empty($_COOKIE['user'])) {
    $userLogin = $_COOKIE['user'];
}
if (!empty($_POST['title'])) {
    $title = trim($_POST['title']);
    $title = htmlspecialchars($title);
}
if (!empty($_POST['text'])) {
    $text = trim($_POST['text']);
    $text = htmlspecialchars($text);
}

if (!empty($_POST['category'])) {
    $categoryId = '';
    $categoryTitle = htmlspecialchars($_POST['category']);

    if ($categoryTitle) {
        foreach ($allArticleCategory as $cat) {
            if ($cat['title'] == $categoryTitle) {
                $categoryId = intval($cat['id']);
            }
        }
    }
}

$user = $usersRow->getUserByName($userLogin);

if ($_FILES) {
    $imgName = $_FILES['img']['name'];
    $tmpname = $_FILES['img']['tmp_name'];
    move_uploaded_file($tmpname, "../images/" . $imgName);
    $img = '../images/' . $imgName;
}

if ($_COOKIE['user']) {
    switch ($_POST['form']) {
        case 'publicate':
            $article = $articleRow->publicate($title, $categoryId, $text, $img, $user['id']);
            $articleId = $connection->insert_id;
            if ($articleId) {
                header("Refresh: 3;http://localhost:8081/pages/article.php?id=" . $articleId);
            }

            echo $article;

            break;
        case 'update':
            $articleId = $_POST['articleId'];
            if (!$imgName) {
                $img = $_POST['articleImg'];
            }
            $article = $articleRow->update($title, $categoryId, $text, $img, $articleId);

            header("Refresh: 3;http://localhost:8081/pages/article.php?id=" . $articleId);

            echo $article;

            break;
        case 'delete':
            $articleId = $_POST['articleId'];
            $article = $articleRow->delete($articleId);

            header("Refresh: 3;http://localhost:8081");

            echo $article;
            break;
    }
}

