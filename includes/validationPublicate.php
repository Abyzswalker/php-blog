<?php

require_once 'db.php';

if (!empty($_COOKIE['user'])) {
    $userLogin = $_COOKIE['user'];
}
if (!empty($_POST['title'])) {
    $title = trim($_POST['title']);
}
if (!empty($_POST['text'])) {
    $text = trim($_POST['text']);
}
if (!empty($_POST['category'])) {
    $categoryTitle = $_POST['category'];

    $categoryId = '';
    if ($categoryTitle) {
        foreach ($allArticleCategory as $cat) {
            if ($cat['title'] == $categoryTitle) {
                $categoryId = $cat['id'];
            }
        }
    }
}

$today = date("Y-m-d H:i:s");

$articleRow = new Article($connection);
$user = $usersQuery->getUserByName($userLogin);


if ($_FILES) {
    $imgName = $_FILES['img']['name'];
    $tmpname = $_FILES['img']['tmp_name'];
    move_uploaded_file($tmpname, "../images/" . $imgName);
    $img = '../images/' . $imgName;
}

if ($_COOKIE['user']) {
    switch ($_POST['form']) {
        case 'publicate':
            $article = $articleRow->publicate($title, $categoryId, $text, $img, $user['id'], $today);
            $articleId = $connection->insert_id;
            if ($articleId) {
                header("Refresh: 3;http://blog/pages/article.php?id=" . $articleId);
            }
            echo $article;

            break;
        case 'update':
            if (!$imgName) {
                $img = $_POST['articleImg'];
            }
            $articleId = $_POST['articleId'];
            $article = $articleRow->update($title, $categoryId, $text, $img, $today, $articleId);

            header("Refresh: 3;http://blog/pages/article.php?id=" . $articleId);

            echo $article;

            break;
        case 'delete':
            $articleId = $_POST['articleId'];
            $article = $articleRow->delete($articleId);

            header("Refresh: 3;http://blog");

            echo $article;
            break;
    }
}

