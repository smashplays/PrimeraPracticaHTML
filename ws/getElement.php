<?php

require_once 'Database.php';
require_once 'models/Element.php';

$id = $_GET['id'] ?? null;

echo Element::getElement($id);
