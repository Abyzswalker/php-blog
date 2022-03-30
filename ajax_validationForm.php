<?php

use Blog\Classes\Users;

require_once __DIR__ . '/vendor/autoload.php';
require_once 'db.php';

if ($_POST['data']['login'] && $_POST['data']['password']) {
    $login = filter_var(trim($_POST['data']['login']), FILTER_SANITIZE_STRING);
    $pass = filter_var(trim($_POST['data']['password']), FILTER_SANITIZE_STRING);
    $pass = md5($pass."test12345");
}

$msg = [];

$usersRow = new Users($connection);
$user = $usersRow->checkUser($login);

switch ($_POST['key']) {
    case 'up':
        if ($user) {
            $msg['msg'] = 'User Error';
            echo json_encode($msg);
        } elseif (!$user) {
            $usersRow->addUser($login, $pass);
            setcookie('user', $login, time() + 3600, '/');
            $msg['msg'] = 'signUp';
            echo json_encode($msg);
        }
        break;
    case 'in':
        if (!empty($user)) {
            $validateUser = $usersRow->validateUser($login, $pass);

            if ($validateUser === 'signIn') {
                setcookie('user', $user['login'], time() + 3600, '/');
                $msg['msg'] = $validateUser;
                echo json_encode($msg);
            } elseif ($validateUser === 'error') {
                $msg['msg'] = $validateUser;
                echo json_encode($msg);
            }
        }
        break;
    case 'logout':
        unset($_COOKIE['user']);
        setcookie('user', null, -1, '/');
        break;
}
