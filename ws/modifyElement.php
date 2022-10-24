<?php

require_once 'Database.php';

try {
    $database = new DataBase('root', '', '127.0.0.1', '3306', 'monfab');
    $id = $_GET['id'] ?? null;
    $name = $_POST['name'] ?? $database->getQueryResult('SELECT nombre FROM elementos WHERE id=:id', $id);
    $description = $_POST['description'] ?? $database->getQueryResult('SELECT descripcion FROM elementos WHERE id=:id', $id);
    $serial = $_POST['serial'] ?? $database->getQueryResult('SELECT nserie FROM elementos WHERE id=:id', $id);
    $status = $_POST["status"] ?? $database->getQueryResult('SELECT estado FROM elementos WHERE id=:id', $id);
    $priority = $_POST["priority"] ?? $database->getQueryResult('SELECT prioridad FROM elementos WHERE id=:id', $id);

    $results = $database->modifyElement($name, $description, $serial, $status, $priority, $id);

    if ($id !== null && !empty($results)) {
        print_r($database->responseJson(
            true,
            "Elemento modificado correctamente",
            $results
        ));
    } else {
        print_r($database->responseJson(
            false,
            "Los elementos no se han podido modificar, comprueba los datos introducidos",
            null
        ));
    }
} catch (Exception $e) {
    echo "Ha fallado la conexión por " . $e->getMessage();
}
