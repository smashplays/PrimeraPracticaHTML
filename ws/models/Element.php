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

    public function __construct($name, $description, $serial, $status, $priority)
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
    public static function getElement()
    {
        try {
            $database = new DataBase('root', '', '127.0.0.1', '3306', 'monfab');
            $id = $_GET['id'] ?? null;
            if ($id !== null) {
                $results = self::prepareAndExecuteGet('SELECT * from elementos WHERE id = :id', $id, $database);
            } else {
                $results = self::query('SELECT * from elementos', $database);
            }

            return $results;
        } catch (Exception $e) {
            return "Ha fallado la conexión por " . $e->getMessage();
        } finally {
            $database->closePdo();
        }
    }

    public static function deleteElement()
    {
        try {
            $database = new DataBase('root', '', '127.0.0.1', '3306', 'monfab');
            $id = $_GET['id'] ?? null;

            $results = self::prepareAndExecuteGet('SELECT * from elementos WHERE id = :id', $id, $database);

            if ($id !== null && !empty($results)) {
                self::prepareAndExecuteGet('DELETE from elementos WHERE id = :id', $id, $database);
                return $results;
            } else {
                return null;
            }
        } catch (Exception $e) {
            echo "Ha fallado la conexión por " . $e->getMessage();
        } finally {
            $database->closePdo();
        }
    }

    public static function createElement()
    {
        try {
            $database = new DataBase('root', '', '127.0.0.1', '3306', 'monfab');

            $status = $_POST["status"] ?? 'Inactivo';
            $priority = $_POST["priority"] ?? 'low';

            if (!empty($_POST["name"])) {
                $name = $_POST["name"];
            } else {
                $name = "Ejemplo";
            }

            if (!empty($_POST["description"])) {
                $description = $_POST["description"];
            } else {
                $description = "Ejemplo Descripcion";
            }

            if (!empty($_POST["serial"])) {
                $serial = $_POST["serial"];
            } else {
                $serial = "0";
            }

            if (!empty($status) && $status === 'active') {
                $status = 'Activo';
            } else {
                $status = 'Inactivo';
            }

            switch ($priority) {
                case "low":
                    $priority = "Baja";
                    break;
                case "medium":
                    $priority = "Media";
                    break;
                case "high":
                    $priority = "Alta";
                    break;
                default:
                    $priority = "Baja";
                    break;
            }

            $results = self::createQuery($name, $description, $serial, $status, $priority, $database);

            if (!empty($results)) {
                return $results;
            } else {
                return null;
            }
        } catch (Exception $e) {
            echo "Ha fallado la conexión por " . $e->getMessage();
        } finally {
            $database->closePdo();
        }
    }

    public static function modifyElement()
    {
        try {
            $database = new DataBase('root', '', '127.0.0.1', '3306', 'monfab');
            $id = $_GET['id'] ?? null;

            $name = $_POST['name'] ?? self::getQueryResult('SELECT nombre FROM elementos WHERE id=:id', $id, $database);
            $description = $_POST['description'] ?? self::getQueryResult('SELECT descripcion FROM elementos WHERE id=:id', $id, $database);
            $serial = $_POST['serial'] ?? self::getQueryResult('SELECT nserie FROM elementos WHERE id=:id', $id, $database);
            $status = $_POST["status"] ?? self::getQueryResult('SELECT estado FROM elementos WHERE id=:id', $id, $database);
            $priority = $_POST["priority"] ?? self::getQueryResult('SELECT prioridad FROM elementos WHERE id=:id', $id, $database);

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
                return $results;
            } else {
                return null;
            }
        } catch (Exception $e) {
            echo "Ha fallado la conexión por " . $e->getMessage();
        } finally {
            $database->closePdo();
        }
    }


    // DATABASE QUERY FUNCTIONS
    public static function query($query, $database)
    {
        try {
            $consulta = $database->getPdo()->query($query);
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public static function prepareAndExecuteGet($query, $id, $database)
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

    public static function createQuery($name, $description, $serial, $status, $priority, $database)
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

    public static function modifyQuery($name, $description, $serial, $status, $priority, $id, $database)
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

    public static function getQueryResult($query, $id, $database)
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
    public static function responseJson($success, $message, $data)
    {
        $results = [];
        $results["success"] = $success;
        $results["message"] = $message;
        $results["data"] = $data;

        $response = json_encode($results, JSON_PRETTY_PRINT);
        return $response;
    }
}
