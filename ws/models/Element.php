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
            return null;
        }
    }

    public static function getElement($id = null)
    {
        $database = self::connectDb();

        if ($database === null) {
            return self::connectionFailed();
        }

        if ($id !== null) {
            $results = self::prepareAndExecuteGet(
                'SELECT * FROM elementos WHERE id = :id',
                $id,
                $database
            );
        } else {
            $results = self::query(
                'SELECT * FROM elementos',
                $database
            );
        }

        if (!empty($results)) {
            return self::responseJson(
                true,
                "Elementos obtenidos correctamente",
                $results
            );
        } else {
            return self::responseJson(
                false,
                "ERROR: Ha fallado la consulta para obtener los elementos",
                $results
            );
        }
    }

    public static function deleteElement($id)
    {
        $database = self::connectDb();

        if ($database === null) {
            return self::connectionFailed();
        }

        $results = self::prepareAndExecuteGet(
            'SELECT nombre, descripcion, nserie, estado, prioridad FROM elementos WHERE id = :id',
            $id,
            $database
        );

        if ($results === null) {
            return self::responseJson(
                false,
                "ERROR: La consulta para obtener el elemento eliminado ha fallado",
                $results
            );
        }

        if ($id !== null && !empty($results)) {
            $delete = self::prepareAndExecuteGet(
                'DELETE FROM elementos WHERE id = :id',
                $id,
                $database
            );
            if ($delete === null) {
                return self::responseJson(
                    false,
                    "ERROR: La consulta para eliminar el elemento ha fallado",
                    $delete
                );
            } else {
                return self::responseJson(
                    true,
                    "Elemento eliminado correctamente",
                    $results
                );
            }
        } else {
            return self::responseJson(
                false,
                "ERROR: El elemento que desea eliminar no se encuentra en la base de datos o no se ha especificado",
                null
            );
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
        $database = self::connectDb();

        if ($database === null) {
            return self::connectionFailed();
        }

        $name = trim($this->getName()) ?? null;
        $description = trim($this->getDescription()) ?? null;
        $serial = trim($this->getSerial()) ?? null;
        $status = trim($this->getStatus()) ?? null;
        $priority = trim($this->getPriority()) ?? null;

        if (empty($name)) {
            $name = "Nombre indefinido";
        }

        if (empty($description)) {
            $description = "Descripción indefinida";
        }

        if (empty($serial)) {
            $serial = "0";
        }

        if (!empty($status) && strtolower($status) === 'activo') {
            $status = 'Activo';
        } else {
            $status = 'Inactivo';
        }

        switch (strtolower($priority)) {
            case "baja":
                $priority = "Baja";
                break;
            case "media":
                $priority = "Media";
                break;
            case "alta":
                $priority = "Alta";
                break;
            default:
                $priority = "Baja";
                break;
        }

        $results = self::createQuery($name, $description, $serial, $status, $priority, $database);

        if (!empty($results)) {
            return self::responseJson(
                true,
                "Elemento creado correctamente",
                $results
            );
        } else if ($results === false) {
            return self::responseJson(
                false,
                "ERROR: La consulta para crear un elemento ha fallado",
                null
            );
        } else {
            return self::responseJson(
                false,
                "ERROR: La consulta para obtener el elemento creado ha fallado",
                null
            );
        }
    }

    private function modifyElement($id)
    {
        $database = self::connectDb();

        if ($database === null) {
            return self::connectionFailed();
        }

        $name = trim($this->getName()) ?? null;
        $description = trim($this->getDescription()) ?? null;
        $serial = trim($this->getSerial()) ?? null;
        $status = trim($this->getStatus()) ?? null;
        $priority = trim($this->getPriority()) ?? null;

        if (empty($name) && empty($description) && empty($serial) && empty($status) && empty($priority)) {
            return self::responseJson(
                false,
                "ERROR: No has introducido ningún valor para editar",
                null
            );
        }

        if (empty($name)) {
            $name = self::getDbValue(
                'SELECT nombre FROM elementos WHERE id = :id',
                $id,
                $database
            );
        }

        if (empty($description)) {
            $description = self::getDbValue(
                'SELECT descripcion FROM elementos WHERE id = :id',
                $id,
                $database
            );
        }

        if (empty($serial)) {
            $serial = self::getDbValue(
                'SELECT nserie FROM elementos WHERE id = :id',
                $id,
                $database
            );
        }

        if ($status === null) {
            $status = self::getDbValue(
                'SELECT estado FROM elementos WHERE id = :id',
                $id,
                $database
            );
        } else if (strtolower($status) === 'activo') {
            $status = 'Activo';
        } else {
            $status = 'Inactivo';
        }

        switch (strtolower($priority)) {
            case "baja":
                $priority = "Baja";
                break;
            case "media":
                $priority = "Media";
                break;
            case "alta":
                $priority = "Alta";
                break;
            case "":
                $priority = self::getDbValue(
                    'SELECT prioridad FROM elementos WHERE id = :id',
                    $id,
                    $database
                );
                break;
            default:
                $priority = "Baja";
                break;
        }

        if ($name === null || $description === null || $serial === null || $status === null || $priority === null) {
            return self::responseJson(
                false,
                "Ha habido un error con los datos recogidos de la base de datos",
                null
            );
        }


        $results = self::modifyQuery($name, $description, $serial, $status, $priority, $id, $database);

        if ($id !== null && !empty($results)) {
            return self::responseJson(
                true,
                "Elemento modificado correctamente",
                $results
            );
        } else if ($results === false) {
            return self::responseJson(
                false,
                "ERROR: No se ha podido modificar el elemento, comprueba los datos introducidos",
                null
            );
        } else {
            return self::responseJson(
                false,
                "ERROR: La consulta para obtener el elemento modificado ha fallado",
                null
            );
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
            $consulta = $database->getPdo()->prepare(
                'INSERT INTO 
            elementos(nombre, descripcion, nserie, estado, prioridad) 
            VALUES (:name, :description, :serial, :status, :priority)'
            );

            $consulta->bindParam(':name', $name, PDO::PARAM_STR);
            $consulta->bindParam(':description', $description, PDO::PARAM_STR);
            $consulta->bindParam(':serial', $serial, PDO::PARAM_STR);
            $consulta->bindParam(':status', $status, PDO::PARAM_STR);
            $consulta->bindParam(':priority', $priority, PDO::PARAM_STR);
            $consulta->execute();

            return self::prepareAndExecuteGet(
                'SELECT nombre, descripcion, nserie, estado, prioridad FROM elementos WHERE id = :id',
                $database->getPdo()->lastInsertId(),
                $database
            );
        } catch (PDOException $e) {
            return false;
        }
    }

    private static function modifyQuery($name, $description, $serial, $status, $priority, $id, $database)
    {
        try {
            $consulta = $database->getPdo()->prepare(
                'UPDATE elementos SET 
            nombre = :name, 
            descripcion = :description, 
            nserie = :serial, 
            estado = :status, 
            prioridad = :priority
            WHERE id = :id;'
            );

            $consulta->bindParam(':id', $id, PDO::PARAM_INT);
            $consulta->bindParam(':name', $name, PDO::PARAM_STR);
            $consulta->bindParam(':description', $description, PDO::PARAM_STR);
            $consulta->bindParam(':serial', $serial, PDO::PARAM_STR);
            $consulta->bindParam(':status', $status, PDO::PARAM_STR);
            $consulta->bindParam(':priority', $priority, PDO::PARAM_STR);
            $consulta->execute();

            return self::prepareAndExecuteGet(
                'SELECT nombre, descripcion, nserie, estado, prioridad FROM elementos WHERE id = :id',
                $id,
                $database
            );
        } catch (PDOException $e) {
            return false;
        }
    }

    private static function getDbValue($query, $id, $database)
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

        return json_encode($results, JSON_PRETTY_PRINT);
    }

    private static function connectionFailed()
    {
        return self::responseJson(
            false,
            "ERROR: Ha fallado la conexión a la base de datos",
            null
        );
    }
}
