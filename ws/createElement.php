<?php

require_once("interfaces/IToJson.php");
require_once("models/Element.php");

$name = $_POST["name"];
$description = $_POST["description"];
$serial = $_POST["serial"];

if (!isset($_POST["status"])) {
    $_POST["status"] = "inactive";  
}

if (!isset($_POST["priority"])) {
    $_POST["priority"] = "low";  
}

$status = $_POST["status"];
$priority = $_POST["priority"];  

$element = new Element($name, $description, $serial, $status, $priority);
$json = $element->toJson($_POST);
echo $json;
$element->toTxt($json);
