<?php

require_once 'Database.php';

try {
    $database = new DataBase('root', '', '127.0.0.1', '3306', 'monfab');
    $id = $_GET['id'] ?? null;
    $name = "Ejemplo";
    $description = "Ejemplo Descripcion";
    $serial = 0;
    $status = $_POST["status"] ?? 'inactive';
    $priority = $_POST["priority"] ?? 'low';

    if (isset($_POST["name"])) {
        $name = $_POST["name"];
    }

    if (isset($_POST["description"])) {
        $description = $_POST["description"];
    }

    if (isset($_POST["serial"])) {
        $serial = $_POST["serial"];
    }
    switch ($priority) {
        case "low":
            $priority = "low";
            break;
        case "medium":
            $priority = "medium";
            break;
        case "high":
            $priority = "high";
            break;
        default:
            $priority = "low";
            break;
    }

    $results = [];

    $results["success"] = null;
    $results["message"] = null;
    $results["data"] = $database->modifyElement($name, $description, $serial, $status, $priority, $id);

    if ($id !== null && !empty($results["data"])) {
        $results["success"] = true;
        $results["message"] = "Elemento modificado correctamente";
    } else {
        $results["success"] = false;
        $results["message"] = "Los elementos no se han podido modificar, comprueba los datos introducidos";
        $results["data"] = null;
    }

    $response = json_encode($results, JSON_PRETTY_PRINT);
    print_r($response);
} catch (Exception $e) {
    echo "Ha fallado la conexión por " . $e->getMessage();
}
