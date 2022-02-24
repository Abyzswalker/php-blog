<?php

include_once 'db.php';


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

$categoryId = '';

if (!empty($_POST['category']) && empty($_POST['newCategory'])) {
    $categoryTitle = htmlspecialchars($_POST['category']);

    if ($categoryTitle) {
        foreach ($allArticleCategory as $cat) {
            if ($cat['title'] == $categoryTitle) {
                $categoryId = intval($cat['id']);
            }
        }
    }
} elseif (empty($_POST['category']) && !empty($_POST['newCategory'])) {
    $categoryTitle = htmlspecialchars($_POST['newCategory']);
    $categoryId = $articleCategoryRow->addCategory($_POST['newCategory']);

    if ($categoryId['error']) {
        echo $categoryId['error'];

        header("Refresh: 3;http://localhost:8081/pages/article.php?id=" . $articleId);

        exit();
    }
} elseif (empty($_POST['category']) && empty($_POST['newCategory']) && $_POST['form'] !== 'delete') {
    echo 'Выбрано 2 категории';

    header("Refresh: 3;http://localhost:8081/pages/article.php?id=" . $articleId);
    exit();
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
                header("Refresh: 3;http://localhost:8081/pages/article.php?id=" . $articleId);
            }

            echo $article;

            break;
        case 'update':
            $articleId = $_POST['articleId'];
            if (!$imgName) {
                $img = $_POST['articleImg'];
            }
            $article = $articleRow->update($title, $categoryId, $text, $img, $today, $articleId);

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

