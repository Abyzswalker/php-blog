<?php

require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/Article.php';
require_once '../includes/User.php';

//spl_autoload_register(function ($class_name) {
//    include '../includes/' . $class_name . '.php';
//});

$articleRow = new Article($connection);
$usersRow = new User($connection);
$articleCategoryRow = new Category($connection);

if (!empty($_GET['id'])) {
    $article = $articleRow->getArticle($_GET['id']);
}

$categoryTitle = '';
$allArticleCategory = $articleCategoryRow->allCategory();

if ($article) {
    $user = $usersRow->getUserById($article['author_id']);

    foreach ($allArticleCategory as $cat) {
        if ($cat['id'] == $article['category_id']){
            $categoryTitle = $cat['title'];
        }
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="../style/style.article.css?v=<?=rand(1,1000)?>" />

<?php
if (!$article) {
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
} else
{
    $connection->query("UPDATE `articles` SET `views` = `views` + 1 WHERE `id` = " . (int) $article['id']);
?>
<div class="container">
    <div class="post">
        <div class="post-header">
            <h1><?php echo $article['title'] ?></h1>
            <div class="post-meta">
                <time datetime="2019-04-01"><?php echo $article['update_date'] ?></time>
                <span class="author"><?php echo $user['login'] ?></span>
                <span class="category"><?php echo $categoryTitle ?></span>
            </div>
        </div>
        <div class="post-image">
            <img src="../images/<?php echo $article['img'] ?>">
        </div>
        <?php
        if ((!empty($_COOKIE['user'])) && $_COOKIE['user'] == $user['login'])
        {
            ?>
            <form id="formArticle" class="formArticle" action="../includes/validationPublicate.php" method="post" enctype="multipart/form-data">
                <div class="btn-group">
                    <button type="button" class="btn first" data-bs-toggle="modal" data-bs-target="#ModalUpdate">Update</button>
                    <button type="button" class="btn second" data-bs-toggle="modal" data-bs-target="#ModalDelete">Delete</button>
                </div>
            </form>
            <?php
        }
        ?>
        <div class="post-content">
            <p class="card-text"><?php echo $article['text'] ?>.</p>
        </div>
    </div>
</div>
<?php
} ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


<!-- ModalUpdate -->
<div class="modal fade" id="ModalUpdate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formUpdate" class="formUpdate" action="../includes/validationPublicate.php" method="post" enctype="multipart/form-data">
                    <input type="text" class="form-control" name="title" id="articleTitle" placeholder="Title" value="<?php echo $article['title'] ?>">
                    <textarea type="textarea" class="form-control" name="text" id="articleText" placeholder="Text" style="margin-top: 10px">
                        <?php echo trim($article['text']) ?>
                    </textarea>
                    <select type="select" class="select-category" name="category" id="select-category" style="margin-top: 10px">
                        <option value = "3" selected = "selected"><?php echo $categoryTitle ?></ option>
                        <?php
                        foreach ($allArticleCategory as $allCat)
                        {
                            ?>
                            <option><?php echo $allCat['title']?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <label>IMG</label>
                    <input class="form-control" type="file" name="img" id="loadArticleImg">
                    <input class="form-control" type="hidden" name="form" value="update">
                    <input class="form-control" type="hidden" name="articleImg" value="<?php echo $article['img'] ?>">
                    <input class="form-control" type="hidden" name="articleId" value="<?php echo (int) $article['id'] ?>">
                    <div class="modal-footer">
                        <button form="formPublicate" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button id="buttonPublicate" type="submit" name="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ModalDelete -->
<div class="modal fade" id="ModalDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formDelete" class="formDelete" action="../includes/validationPublicate.php" method="post" enctype="multipart/form-data">
                    <label>Удалить статью?</label>
                    <input class="form-control" type="hidden" name="form" value="delete">
                    <input class="form-control" type="hidden" name="articleId" value="<?php echo (int) $article['id'] ?>">
                    <div class="modal-footer">
                        <button form="formPublicate" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button id="buttonPublicate" type="submit" name="submit" class="btn btn-primary">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

