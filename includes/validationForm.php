<?php

require_once 'db.php';

if ($_POST['data']['login'] && $_POST['data']['password']) {
    $login = filter_var(trim($_POST['data']['login']), FILTER_SANITIZE_STRING);
    $pass = filter_var(trim($_POST['data']['password']), FILTER_SANITIZE_STRING);
    $pass = md5($pass."test12345");
}
//$error = "username/password incorrect";
$msg = [];


$user = $usersQuery->checkUser($login, $pass);


switch ($_POST['key']) {
    case 'up':
        if ($user) {
            $msg['msg'] = 'error';
            echo json_encode($msg);
        } elseif (!$user) {
            $signUp = $usersQuery->addUser($login, $pass);

            $msg['msg'] = 'signUp';
            echo json_encode($msg);
        }
        break;
    case 'in':
        if ($user) {
            //3600
            setcookie('user', $user['login'], time() + 36, '/');
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
        var_dump(111);
        //var_dump('kuki', $_COOKIE['user']);
        unset($_COOKIE['user']);
        setcookie('user', null, -1, '/');
    //header("Location: http://blog/index.php");
        break;
}
