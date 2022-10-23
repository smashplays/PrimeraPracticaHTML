<?php

require_once 'Database.php';

try {
    $database = new DataBase('root', '', '127.0.0.1', '3306', 'monfab');
    $id = $_GET['id'] ?? null;

    if ($id !== null) {
        $results = $database->prepareAndExecuteGet('SELECT * from elementos WHERE id = :id', $id);
    } else {
        $results = $database->query('SELECT * from elementos');
    }

    if (!empty($results)) {
        print_r($database->responseJson(
            true,
            "Elementos obtenidos correctamente",
            $results
        ));
    } else {
        print_r($database->responseJson(
            false,
            "Elementos no encontrados",
            null
        ));
    }
} catch (Exception $e) {
    echo "Ha fallado la conexión por " . $e->getMessage();
}
