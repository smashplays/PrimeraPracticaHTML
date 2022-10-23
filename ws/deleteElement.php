<?php

require_once 'Database.php';

try {
    $database = new DataBase('root', '', '127.0.0.1', '3306', 'monfab');
    $id = $_GET['id'] ?? null;

    $results = $database->prepareAndExecuteGet('SELECT * from elementos WHERE id = :id', $id);

    if ($id !== null && !empty($results)) {
        print_r($database->responseJson(
            true,
            "Elementos eliminados correctamente",
            $results
        ));
        $database->prepareAndExecuteGet('DELETE from elementos WHERE id = :id', $id);
    } else {
        print_r($database->responseJson(
            false,
            "El elemento que desea eliminar no se encuentra en la base de datos o no se ha especificado",
            null
        ));
    }
} catch (Exception $e) {
    echo "Ha fallado la conexión por " . $e->getMessage();
}
