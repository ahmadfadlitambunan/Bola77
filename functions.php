<?php 

function extractURI($URI) {
    $str = [];
    $str = explode('/resource/', $URI);

    return $str[1];
}

function deextractURI($ident) {
    $str = 'http://dbpedia.org/resource/'.$ident;

    return $str;
}

function getAge($age) {

    $today = date("Y-m-d");
    $diff = date_diff(date_create($age), date_create($today));
    return $diff->format('%y');
}

function getCurrency($number) {
    $fmt = new NumberFormatter( 'de_DE', NumberFormatter::CURRENCY );
    return $fmt->formatCurrency($number, "EUR");
}

?>