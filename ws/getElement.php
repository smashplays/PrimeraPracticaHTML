<?php

require_once 'models/Element.php';

$results = Element::getElement();

if (!empty($results)) {
    print_r(Element::responseJson(true, "Elementos obtenidos correctamente", $results));
} else {
    print_r(Element::responseJson(false, "Elementos no encontrados", null));
}
