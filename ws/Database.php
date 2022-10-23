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

    public function query($query)
    {
        try {
            $consulta = $this->pdo->query($query);
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function prepareAndExecuteGet($query, $id)
    {
        try {
            $consulta = $this->pdo->prepare($query);
            $consulta->bindParam(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function createElement($name, $description, $serial, $status, $priority)
    {
        try {
            $consulta = $this->pdo->prepare('INSERT INTO elementos(nombre, descripcion, nserie, estado, prioridad)
            VALUES (:name, :description, :serial, :status, :priority)');

            $consulta->bindParam(':name', $name, PDO::PARAM_STR);
            $consulta->bindParam(':description', $description, PDO::PARAM_STR);
            $consulta->bindParam(':serial', $serial, PDO::PARAM_INT);
            $consulta->bindParam(':status', $status, PDO::PARAM_STR);
            $consulta->bindParam(':priority', $priority, PDO::PARAM_STR);
            $consulta->execute();
            return $this->prepareAndExecuteGet('SELECT * FROM elementos WHERE id = :id', $this->pdo->lastInsertId());
        } catch (PDOException $e) {
            return null;
        }
    }

    public function modifyElement($name, $description, $serial, $status, $priority, $id)
    {
        try {
            $consulta = $this->pdo->prepare('UPDATE elementos SET 
            nombre=:name, 
            descripcion=:description, 
            nserie=:serial, 
            estado=:status, 
            prioridad=:priority
            WHERE id=:id;');

            $consulta->bindParam(':id', $id, PDO::PARAM_INT);
            $consulta->bindParam(':name', $name, PDO::PARAM_STR);
            $consulta->bindParam(':description', $description, PDO::PARAM_STR);
            $consulta->bindParam(':serial', $serial, PDO::PARAM_INT);
            $consulta->bindParam(':status', $status, PDO::PARAM_STR);
            $consulta->bindParam(':priority', $priority, PDO::PARAM_STR);
            $consulta->execute();
            return $this->prepareAndExecuteGet('SELECT * FROM elementos WHERE id = :id', $id);
        } catch (PDOException $e) {
            return null;
        }
    }
}
