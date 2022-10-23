<?php

require_once 'Database.php';

try {
    $database = new DataBase('root', '', '127.0.0.1', '3306', 'monfab');
    $id = $_GET['id'] ?? null;
    $results = [];

    if ($id !== null) {
        $query = $database->prepareAndExecuteGet('SELECT * from elementos WHERE id = :id', $id);
    } else {
        $query = $database->query('SELECT * from elementos');
    }

    if (empty($query)) {
        $results["success"] = false;
        $results["message"] = "Elementos no encontrados";
        $results["data"] = null;
    } else {
        $results["success"] = true;
        $results["message"] = "Elementos obtenidos correctamente";
        $results["data"] = $query;
    }
    $response = json_encode($results, JSON_PRETTY_PRINT);
    print_r($response);
} catch (Exception $e) {
    echo "Ha fallado la conexión por " . $e->getMessage();
}
