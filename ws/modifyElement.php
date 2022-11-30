<?php

require_once 'Database.php';
require_once 'models/Element.php';

$id = $_GET['id'] ?? null;

$element = new Element(
    $_POST['name'] ?? null,
    $_POST['description'] ?? null,
    $_POST['serial'] ?? null,
    $_POST['status'] ?? null,
    $_POST['priority'] ?? null
);

echo $element->save($id);
