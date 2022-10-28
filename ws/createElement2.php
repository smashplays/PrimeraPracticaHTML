<?php

require_once 'Database.php';

try {
    $database = new DataBase('root', '', '127.0.0.1', '3306', 'monfab');

    $status = $_POST["status"] ?? 'Inactivo';
    $priority = $_POST["priority"] ?? 'low';

    if (!empty($_POST["name"])) {
        $name = $_POST["name"];
    } else{
        $name = "Ejemplo";
    }

    if (!empty($_POST["description"])) {
        $description = $_POST["description"];
    } else{
        $description = "Ejemplo Descripcion";
    }

    if (!empty($_POST["serial"])) {
        $serial = $_POST["serial"];
    } else{
        $serial = "0";
    }

    if (!empty($status) && $status === 'active') {
        $status = 'Activo';
    } else{
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
