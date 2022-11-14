<?php

class DataBase
{
    private $user;
    private $password;
    private $host;
    private $port;
    private $name;
    private $pdo;

    public function __construct($user, $password, $host, $port, $name)
    {
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->port = $port;
        $this->name = $name;

        $dsn = 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $name;
        $this->pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $this->pdo->exec('set names utf8');
    }

    public function getPdo()
    {
        return $this->pdo;
    }
}
