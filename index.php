<?php
require 'vendor/autoload.php';

\EasyRdf\RdfNamespace::set('foaf', 'http://xmlns.com/foaf/0.1/');
\EasyRdf\RdfNamespace::set('dbp', 'http://dbpedia.org/property/');
\EasyRdf\RdfNamespace::set('dbo', 'http://dbpedia.org/ontology/');
\EasyRdf\RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
\EasyRdf\RdfNamespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
\EasyRdf\RdfNamespace::set('owl', 'http://www.w3.org/2002/07/owl#');
\EasyRdf\RdfNamespace::set('football', 'https://example.org/schema/football/');
\EasyRdf\RdfNamespace::setDefault('og');

$jena_endpoint = new \EasyRdf\Sparql\Client('http://localhost:3030/player/sparql');

$sparql_query = '
    SELECT ?name ?num ?link
    WHERE {
        ?p  a   football:player;
            football:name ?name;
            football:number ?num;
            foaf:homepage ?link.
    }';      

$result = $jena_endpoint->query($sparql_query);

var_dump($result);

?>
