<?php

require_once 'db.php';

if ($_POST['data']['login'] && $_POST['data']['password']) {
    $login = filter_var(trim($_POST['data']['login']), FILTER_SANITIZE_STRING);
    $pass = filter_var(trim($_POST['data']['password']), FILTER_SANITIZE_STRING);
    $pass = md5($pass."test12345");
}

$msg = [];


$user = $usersQuery->checkUser($login);

switch ($_POST['key']) {
    case 'up':
        if ($user) {
            $msg['msg'] = 'User Error';
            echo json_encode($msg);
        } elseif (!$user) {
            $usersQuery->addUser($login, $pass);
            $msg['msg'] = 'signUp';
            echo json_encode($msg);
        }
        break;
    case 'in':
        if ($user) {
            //3600
            setcookie('user', $user['login'], time() + 3600, '/');
            $msg['msg'] = 'signIn';
            echo json_encode($msg);
        } elseif (!$user) {
            if (!$user) {
                $msg['msg'] = 'error';
                echo json_encode($msg);
            }
        }
        break;
    case 'logout':
        //var_dump('kuki', $_COOKIE['user']);
        unset($_COOKIE['user']);
        setcookie('user', null, -1, '/');
    //header("Location: http://blog/index.php");
        break;
}
