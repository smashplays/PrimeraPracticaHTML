<?php

require_once 'Database.php';

try {
    $database = new DataBase('root', '', '127.0.0.1', '3306', 'monfab');
    $id = $_GET['id'] ?? null;
    $results = [];
    $results["success"] = null;
    $results["message"] = null;
    $results["data"] = $database->prepareAndExecuteGet('SELECT * from elementos WHERE id = :id', $id);


    if ($id !== null && !empty($results["data"])) {
        $results["success"] = true;
        $results["message"] = "Elementos eliminados correctamente";
        $database->prepareAndExecuteGet('DELETE from elementos WHERE id = :id', $id);
    } else {
        $results["success"] = false;
        $results["message"] = "El elemento que desea eliminar no se encuentra en la base de datos o no se ha especificado";
        $results["data"] = null;
    }
    $response = json_encode($results, JSON_PRETTY_PRINT);
    print_r($response);
} catch (Exception $e) {
    echo "Ha fallado la conexión por " . $e->getMessage();
}
