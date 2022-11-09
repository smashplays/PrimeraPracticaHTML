<?php

// require_once '../interfaces/IToJson.php';
// require_once '../Database.php'; 

class Element //implements IToJson
{
    private $name;
    private $description;
    private $serial;
    private $status;
    private $priority;

    public function __construct($name = null, $description = null, $serial = null, $status = null, $priority = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->serial = $serial;
        $this->status = $status;
        $this->priority = $priority;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getSerial()
    {
        return $this->serial;
    }

    public function setSerial($serial)
    {
        $this->serial = $serial;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    // public function toJson($arr)
    // {
    //     $json = json_encode($arr, JSON_PRETTY_PRINT);
    //     return $json;
    // }

    // public function toTxt($json)
    // {
    //     $file = 'element.txt';
    //     file_put_contents($file, $json, FILE_APPEND);
    // }

    // CRUD API Functions

    private static function connectDb()
    {
        try {
            $db = new DataBase('root', '', '127.0.0.1', '3306', 'monfab');
            return $db;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function getElement($id = null)
    {
        try {
            $database = self::connectDb();
            if ($id !== null) {
                $results = self::prepareAndExecuteGet('SELECT * from elementos WHERE id = :id', $id, $database);
            } else {
                $results = self::query('SELECT * from elementos', $database);
            }

            if (!empty($results)) {
                $response = self::responseJson(true, "Elementos obtenidos correctamente", $results);
            } else {
                $response = self::responseJson(false, "Elementos no encontrados", null);
            }

            return $response;
        } catch (Exception $e) {
            return "Ha fallado la conexión por " . $e->getMessage();
        } finally {
            $database->closePdo();
        }
    }

    public static function deleteElement($id = null)
    {
        try {
            $database = self::connectDb();

            $results = self::prepareAndExecuteGet('SELECT * from elementos WHERE id = :id', $id, $database);

            if ($id !== null && !empty($results)) {
                self::prepareAndExecuteGet('DELETE from elementos WHERE id = :id', $id, $database);
                $response = self::responseJson(true, "Elementos eliminados correctamente", $results);
            } else {
                $response = self::responseJson(false, "El elemento que desea eliminar no se encuentra en la base de datos o no se ha especificado", null);
            }

            return $response;
        } catch (Exception $e) {
            return "Ha fallado la conexión por " . $e->getMessage();
        } finally {
            $database->closePdo();
        }
    }

    public function save($id = null)
    {
        if ($id === null) {
            return $this->createElement();
        } else {
            return $this->modifyElement($id);
        }
    }

    private function createElement()
    {
        try {
            $database = self::connectDb();

            $name = $this->getName() ?? "Ejemplo";
            $description = $this->getDescription() ?? "Ejemplo Descripcion";
            $serial = $this->getSerial() ?? "0";
            $status = $this->getStatus() ?? 'Inactivo';
            $priority = $this->getPriority() ?? 'low';

            if (empty($name)) {
                $name = "Ejemplo";
            }

            if (empty($description)) {
                $description = "Ejemplo Descripcion";
            }

            if (empty($serial)) {
                $serial = "0";
            }

            if (!empty($status) && $status === 'active') {
                $status = 'Activo';
            } else {
                $status = 'Inactivo';
            }

            switch ($priority) {
                case "Baja":
                    $priority = "Baja";
                    break;
                case "Media":
                    $priority = "Media";
                    break;
                case "Alta":
                    $priority = "Alta";
                    break;
                default:
                    $priority = "Baja";
                    break;
            }

            $results = self::createQuery($name, $description, $serial, $status, $priority, $database);

            if (!empty($results)) {
                $response = self::responseJson(true, "Elemento creado correctamente", $results);
            } else {
                $response = self::responseJson(false, "Los elementos no se han podido crear, comprueba los datos introducidos", null);
            }

            return $response;
        } catch (Exception $e) {
            return "Ha fallado la conexión por " . $e->getMessage();
        } finally {
            $database->closePdo();
        }
    }

    private function modifyElement($id)
    {
        try {
            $database = self::connectDb();

            if (empty($_POST)) {
                $response = self::responseJson(false, "No has editado ningún elemento de la id $id", null);
                return $response;
            }

            $name = $this->getName() ?? self::getQueryResult('SELECT nombre FROM elementos WHERE id=:id', $id, $database);
            $description = $this->getDescription() ?? self::getQueryResult('SELECT descripcion FROM elementos WHERE id=:id', $id, $database);
            $serial = $this->getSerial() ?? self::getQueryResult('SELECT nserie FROM elementos WHERE id=:id', $id, $database);
            $status = $this->getStatus() ?? self::getQueryResult('SELECT estado FROM elementos WHERE id=:id', $id, $database);
            $priority = $this->getPriority() ?? self::getQueryResult('SELECT prioridad FROM elementos WHERE id=:id', $id, $database);

            if ($status === 'Activo') {
                $status = 'Activo';
            } else {
                $status = 'Inactivo';
            }

            switch ($priority) {
                case "Baja":
                    $priority = "Baja";
                    break;
                case "Media":
                    $priority = "Media";
                    break;
                case "Alta":
                    $priority = "Alta";
                    break;
                default:
                    $priority = "Baja";
                    break;
            }


            $results = self::modifyQuery($name, $description, $serial, $status, $priority, $id, $database);

            if ($id !== null && !empty($results)) {
                $response = self::responseJson(true, "Elemento modificado correctamente", $results);
            } else {
                $response = self::responseJson(false, "Los elementos no se han podido modificar, comprueba los datos introducidos", null);
            }

            return $response;
        } catch (Exception $e) {
            return "Ha fallado la conexión por " . $e->getMessage();
        } finally {
            $database->closePdo();
        }
    }


    // DATABASE QUERY FUNCTIONS
    private static function query($query, $database)
    {
        try {
            $consulta = $database->getPdo()->query($query);
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    private static function prepareAndExecuteGet($query, $id, $database)
    {
        try {
            $consulta = $database->getPdo()->prepare($query);
            $consulta->bindParam(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    private static function createQuery($name, $description, $serial, $status, $priority, $database)
    {
        try {
            $consulta = $database->getPdo()->prepare('INSERT INTO elementos(nombre, descripcion, nserie, estado, prioridad)
            VALUES (:name, :description, :serial, :status, :priority)');

            $consulta->bindParam(':name', $name, PDO::PARAM_STR);
            $consulta->bindParam(':description', $description, PDO::PARAM_STR);
            $consulta->bindParam(':serial', $serial, PDO::PARAM_STR);
            $consulta->bindParam(':status', $status, PDO::PARAM_STR);
            $consulta->bindParam(':priority', $priority, PDO::PARAM_STR);
            $consulta->execute();
            return self::prepareAndExecuteGet('SELECT * FROM elementos WHERE id = :id', $database->getPdo()->lastInsertId(), $database);
        } catch (PDOException $e) {
            return null;
        }
    }

    private static function modifyQuery($name, $description, $serial, $status, $priority, $id, $database)
    {
        try {
            $consulta = $database->getPdo()->prepare('UPDATE elementos SET 
            nombre = :name, 
            descripcion = :description, 
            nserie = :serial, 
            estado = :status, 
            prioridad = :priority
            WHERE id = :id;');

            $consulta->bindParam(':id', $id, PDO::PARAM_INT);
            $consulta->bindParam(':name', $name, PDO::PARAM_STR);
            $consulta->bindParam(':description', $description, PDO::PARAM_STR);
            $consulta->bindParam(':serial', $serial, PDO::PARAM_STR);
            $consulta->bindParam(':status', $status, PDO::PARAM_STR);
            $consulta->bindParam(':priority', $priority, PDO::PARAM_STR);
            $consulta->execute();
            return self::prepareAndExecuteGet('SELECT * FROM elementos WHERE id = :id', $id, $database);
        } catch (PDOException $e) {
            return null;
        }
    }

    private static function getQueryResult($query, $id, $database)
    {
        try {
            $consulta = $database->getPdo()->prepare($query);
            $consulta->bindParam(':id', $id, PDO::PARAM_INT);
            $consulta->execute();
            return $consulta->fetchColumn();
        } catch (PDOException $e) {
            return null;
        }
    }

    // RESPONSE JSON METHOD
    private static function responseJson($success, $message, $data)
    {
        $results = [];
        $results["success"] = $success;
        $results["message"] = $message;
        $results["data"] = $data;

        $response = json_encode($results, JSON_PRETTY_PRINT);
        return $response;
    }
}
