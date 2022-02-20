<?php

require_once 'includes/db.php';

$articlesRow = new Article($connection);
$allArticles = $articlesRow->allArticles(0, 2);
$countArticles = $articlesRow->countArticles();

$count = ceil($countArticles[0] / 2);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style/style.css?v=<?=rand(1,1000)?>" />

    <title><?php echo $config['title']; ?></title>
</head>
<body>

<?php

include "pages/header.php";

if (!isset($_GET['category']) && !isset($_GET['q'])) {
    ?>
    <div class="last-articles"><h3>Last publications</h3></div>
    <div class="showMore" id="showMore">
    <?php if (count($allArticles) <= 0) { ?>
        <div class="container">
            <div class="post-header">
                <h1>Нет статей</h1>
            </div>
        </div>
    <?php } else {
        foreach ($allArticles as $row): ?>
            <article class="article" id="article">
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="/images/<?php echo $row['img'] ?>" class="img-fluid rounded-start" alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <?php
                                $artCat = false;
                                foreach ($allArticleCategory as $cat) {
                                    if ($cat['id'] == $row['category_id']) {
                                        $artCat = $cat;
                                        break;
                                    }
                                }
                                ?>
                                <h1 class="card-title"><?php echo $row['title'] ?></h1>
                                <small>
                                    <a href="/index.php?category=<?php echo $artCat['id'] ?>"><?php echo $artCat['title'] ?></a>
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
        endforeach;
        if ($countArticles[0] > 2): ?>
            <div class="afterPosts" style="text-align: center">
                <a id="loadMore" type="button" name="loadMore" class="btn btn-outline-secondary loadMore" data-page="1"
                   data-max="<?php echo $count; ?>">
                    Load more
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span class="visually-hidden">Loading...</span>
                </a>
            </div>
            </div>
        <?php
        endif;
    }
} elseif (!empty($_GET['category'])) {
    $categoryId = $_GET['category'];
    $articlesOnCategoryRow = new Article($connection);
    $articlesOnCategory = $articlesOnCategoryRow->articleOnCategory($categoryId, 0, 2);

    $categoryTitle = '';

    foreach ($allArticleCategory as $cat) {
        if ($cat['id'] == $categoryId) {
            $categoryTitle = $cat['title'];
        }
    } ?>
    <div class="last-articles"><h3><?php echo $categoryTitle ?></h3></div>
    <div class="showMore" id="showMore">
    <?php if (count($articlesOnCategory) <= 0) { ?>
        <div class="container">
            <div class="post-header">
                <h1>В данной категории нет статей</h1>
            </div>
        </div>
        <?php
    } else {
        foreach ($articlesOnCategory as $row): ?>
            <article class="article">
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="/images/<?php echo $row['img'] ?>" class="img-fluid rounded-start" alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <?php
                                $artCat = false;
                                foreach ($allArticleCategory as $cat) {
                                    if ($cat['id'] == $row['category_id']) {
                                        $artCat = $cat;
                                        break;
                                    }
                                }
                                ?>
                                <h1 class="card-title"><?php echo $row['title'] ?></h1>
                                <small>
                                    <a href="/index.php?category=<?php echo $categoryId ?>"><?php echo $categoryTitle ?></a>
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
        <?php endforeach;
        if ($countArticles[0] > 2): ?>
        <div class="afterPosts" style="text-align: center">
            <a id="loadMore" type="button" name="loadMore" class="btn btn-outline-secondary loadMore" data-cat="<?php echo $categoryId; ?>" data-page="1" data-max="<?php echo $count; ?>">
                Load more
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                <span class="visually-hidden">Loading...</span>
            </a>
        </div>
    </div>
    <?php
    endif;
    }
} elseif ($_GET['q']) {
    $searchQuery = $_GET['q'];
    $searchOnArt = $articlesRow->serchOnArticle($searchQuery);
    ?>
    <div class="last-articles"><h3>Поиск</h3></div>
    <?php
    if (count($searchOnArt) <= 0):
        ?>
        <div class="container">
            <div class="post">
                <div class="post-header">
                    <h1>Статья не найдена</h1>
                    <div class="post-meta">
                        <time datetime=""></time>
                        <span class="author"></span>
                        <span class="category"></span>
                    </div>
                </div>

                <div class="post-content">
                    <p class="card-text">Данная статья, не найдена.</p>
                </div>
            </div>
        </div>
    <?php
    else:
        foreach ($searchOnArt as $row):
            ?>
            <article class="article">
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="/images/<?php echo $row['img'] ?>" class="img-fluid rounded-start"
                                 alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <?php
                                $artCat = false;
                                foreach ($allArticleCategory as $cat) {
                                    if ($cat['id'] == $row['category_id']) {
                                        $artCat = $cat;
                                        break;
                                    }
                                }
                                ?>
                                <h1 class="card-title"><?php echo $row['title'] ?></h1>
                                <h5 class=""><a
                                            href="/pages/article.php?category=<?php echo $artCat['id'] ?>"><?php echo $artCat['title'] ?></a>
                                </h5>
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
                            <a href="/pages/article.php?id=<?php echo $row['id'] ?>" class="read-btn">Читать
                                далее</a>
                        </div>
                    </div>
                </div>
            </article>
        <?php
        endforeach;
    endif;
} ?>

<script src="jquery-3.6.0.min.js"></script>
<script src="/scripts/ajax_pagination.js"></script>
<script src="/scripts/ajax_validation.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>