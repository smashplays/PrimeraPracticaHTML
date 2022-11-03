<?php

require_once 'Database.php';
require_once 'models/Element.php';

$results = Element::modifyElement();

if (!empty($results)) {
    print_r(Element::responseJson(true, "Elemento modificado correctamente", $results));
} else {
    print_r(Element::responseJson(false, "Los elementos no se han podido modificar, comprueba los datos introducidos", null));
}
