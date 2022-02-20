<?php

require_once 'Database.php';

class User
{
    private $connection;
    public $user;


    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function addUser($login, $pass)
    {
        $this->user = $this->connection->query("
             INSERT INTO `users` (`login`, `pass`)
             VALUES('$login', '$pass')");
    }

    public function checkUser($login)
    {
        $this->user = $this->connection->query("SELECT * FROM `users` WHERE `login` = '$login'");

        return $this->user->fetch_assoc();
    }

    public function allUsers()
    {
        $this->user = $this->connection->query("SELECT * FROM `users`");

        return $this->user->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserById($id)
    {
        $this->user = $this->connection->query("SELECT id, login FROM `users` WHERE id ='" . $id . "'");

        return $this->user->fetch_assoc();
    }

    public function getUserByName($name)
    {
        $this->user = $this->connection->query("SELECT id, login FROM `users` WHERE login ='" . $name . "'");

        return $this->user->fetch_assoc();
    }
}