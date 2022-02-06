<?php

class Database extends \mysqli
{
    public $server;
    public $username;
    public $password;
    public $name;
    public $connection;

    public function __construct ($server, $username, $password, $name)
    {
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->name = $name;
    }

    public function dbConnect ()
    {
        $this->connection = new Mysqli($this->server, $this->username, $this->password, $this->name);
        $this->connection->set_charset("utf8");

        if ($this->connection == false) {
            echo 'Не удается подключится к БД!<br>';
            echo mysqli_connect_error();
            exit();
        } else {
            return $this->connection;
        }
    }
}