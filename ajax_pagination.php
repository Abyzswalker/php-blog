<?php

use Blog\Classes\Articles;
use Blog\Classes\Categories;
use Blog\Classes\Users;

require_once __DIR__ . '/vendor/autoload.php';
require_once 'db.php';

$articlesRow = new Articles($connection);
$usersRow = new Users($connection);
$articleCategoryRow = new Categories($connection);

$limit = 2;
$page = intval(@$_GET['page']);
$page = (empty($page)) ? 1 : $page;
$start = ($page != 1) ? $page * $limit - $limit : 0;

$allArticleCategory = $articleCategoryRow->allCategory();
$users = $usersRow->allUsers();

if (!empty($_GET['catId'])) {
    $nextPageArticle = $articlesRow->articleOnCategory($_GET['catId'], $start, $limit);
} else {
    $nextPageArticle = $articlesRow->allArticles($start, $limit);
}

foreach ($nextPageArticle as $row) {
    ?>
    <article class="article">
        <div class="card mb-3">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="/images/<?php echo $row['img'] ?>" class="img-fluid rounded-start" alt="...">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <?php
                        $art_cat = false;
                        foreach ($allArticleCategory as $cat) {
                            if ($cat['id'] == $row['category_id']) {
                                $art_cat = $cat;
                                break;
                            }
                        }
                        ?>
                        <h1 class="card-title"><?php echo $row['title'] ?></h1>
                        <small>
                            <a href="/index.php?category=<?php echo $art_cat['id'] ?>"><?php echo $art_cat['title'] ?></a>
                        </small>
                        <p class="card-text"><?php echo mb_substr(strip_tags($row['text']), 0, 100, 'utf-8') . ' . . .' ?></p>
                        <div class="post-info">
                            <div>
                                <?php
                                foreach ($users as $user) {
                                    if ($row['author_id'] == $user['id']) {
                                        echo $user['login'];
                                        break;
                                    }
                                }
                                ?>
                            </div>
                            <div><?php echo $row['views'] ?> просмотров</div>
                            <div class="text-muted"><?php echo 'last update ' . $row['update_date'] ?></div>
                        </div>
                    </div>
                    <a href="/pages/article.php?id=<?php echo $row['id'] ?>" class="read-btn">Читать далее</a>
                </div>
            </div>
        </div>
    </article>
    <?php
}