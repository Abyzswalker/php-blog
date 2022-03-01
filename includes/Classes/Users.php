<?php

require_once 'Database.php';

class Users
{
    private $connection;
    public $user;


    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function addUser($login, $pass)
    {
        $this->user = $this->connection->prepare("
             INSERT INTO users (`login`, `pass`)
             VALUES(?, ?)
        ");

        $this->user->bind_param('ss', $login, $pass);

        $this->user->execute();
    }

    public function checkUser($login)
    {
        $stmt = $this->connection->prepare("SELECT id, login FROM users WHERE `login` = ?");
        $stmt->execute(["$login"]);
        $this->user = $stmt->get_result();

        return $this->user->fetch_assoc();
    }

    public function allUsers()
    {
        $stmt = $this->connection->prepare("SELECT id, login FROM users");
        $stmt->execute();
        $this->user = $stmt->get_result();

        return $this->user->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserById($id)
    {
        $stmt = $this->connection->prepare("SELECT id, login FROM users WHERE `id` = ?");
        $stmt->execute(["$id"]);
        $this->user = $stmt->get_result();

        return $this->user->fetch_assoc();
    }

    public function getUserByName($login)
    {
        $stmt = $this->connection->prepare("SELECT id, login FROM users WHERE `login` = ?");
        $stmt->execute(["$login"]);
        $this->user = $stmt->get_result();

        return $this->user->fetch_assoc();
    }
}