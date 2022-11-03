<?php

require_once 'models/Element.php';

$results = Element::createElement();

if (!empty($results)) {
    print_r(Element::responseJson(true, "Elemento creado correctamente", $results));
} else {
    print_r(Element::responseJson(false, "Los elementos no se han podido crear, comprueba los datos introducidos", null));
}
