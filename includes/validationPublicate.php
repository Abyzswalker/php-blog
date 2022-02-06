<?php

require_once 'db.php';

if ($_COOKIE['user']) {
    switch ($_POST['form']) {
        case 'publicate':
            if (!empty($_POST)) {
                $userLogin = $_COOKIE['user'];
                $today = date("Y-m-d H:i:s");
                $title = trim($_POST['title']);
                $text = trim($_POST['text']);
                $categoryTitle = $_POST['category'];
            }

            $articleRow = new Article($connection);
            $user = $usersQuery->getUserByName($userLogin);

            $categoryId = '';
            if ($categoryTitle) {
                foreach ($allArticleCategory as $cat) {
                    if ($cat['title'] == $categoryTitle) {
                        $categoryId = $cat['id'];
                    }
                }
            }

            if ($_FILES) {
                $imgName = $_FILES['img']['name'];
                $tmpname = $_FILES['img']['tmp_name'];
                move_uploaded_file($tmpname, "../images/" . $imgName);
                $img = '../images/' . $imgName;
            }

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
            $articles = "
             UPDATE articles
             SET title = '$title', category_id = '$categoryId', text = '$text', img = '$img', update_date = '$today'
             WHERE id = '$articleId'";

            if ($connection->query($articles)) {
                echo "Статья успешно обновлена";
                header("Refresh: 3;http://blog/pages/article.php?id=" . $articleId);
            } else {
                print_r($connection->error);
            }
            break;
        case 'delete':
            $articleId = $_POST['articleId'];
            $articles = "
            DELETE FROM articles WHERE id = '$articleId';
            ";

            if ($connection->query($articles)) {
                echo "Статья успешно удалена";
                //header("Refresh: 3;http://blog");
            } else {
                print_r($connection->error);
            }
    }
}

