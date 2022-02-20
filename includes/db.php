<?php

require 'config.php';

spl_autoload_register(function ($class_name) {
    include 'Classes/' . $class_name . '.php';
});

$database = new Database(
    $config['db']['server'],
    $config['db']['username'],
    $config['db']['password'],
    $config['db']['name']
);

$connection = $database->dbConnect();

$articleCategoryRow = new Category($connection);
$allArticleCategory = $articleCategoryRow->allCategory();

$usersQuery = new User($connection);
$users = $usersQuery->allUsers();
