<?php

use Blog\Classes\Database;

require_once __DIR__ . '/vendor/autoload.php';
require_once 'config.php';

$db = new Database(
    $config['db']['server'],
    $config['db']['username'],
    $config['db']['password'],
    $config['db']['name']
);

$connection = $db->dbConnect();