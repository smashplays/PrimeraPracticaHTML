<?php

require_once 'Database.php';

try {
    $database = new DataBase('root', '', '127.0.0.1', '3306', 'monfab');

    $name = "Ejemplo";
    $description = "Ejemplo Descripcion";
    $serial = "0";
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

    $results = $database->createElement($name, $description, $serial, $status, $priority);
    if (!empty($results)) {
        print_r($database->responseJson(
            true,
            "Elemento creado correctamente",
            $results
        ));
    } else {
        print_r($database->responseJson(
            false,
            "Los elementos no se han podido crear, comprueba los datos introducidos",
            $results
        ));
    }
} catch (Exception $e) {
    echo "Ha fallado la conexión por " . $e->getMessage();
}
