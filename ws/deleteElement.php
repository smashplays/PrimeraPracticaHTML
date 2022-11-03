<?php

require_once 'models/Element.php';

$results = Element::deleteElement();

if (!empty($results)) {
    print_r(Element::responseJson(true, "Elementos eliminados correctamente", $results));
} else {
    print_r(Element::responseJson(false, "El elemento que desea eliminar no se encuentra en la base de datos o no se ha especificado", null));
}
