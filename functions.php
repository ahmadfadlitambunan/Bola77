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
?>