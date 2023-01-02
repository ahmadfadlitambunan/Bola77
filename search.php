<?php
    require 'vendor/autoload.php';
    require 'functions.php';
 
 $key = $_GET['key'];
 \EasyRdf\RdfNamespace::set('foaf', 'http://xmlns.com/foaf/0.1/');
 \EasyRdf\RdfNamespace::set('dbp', 'http://dbpedia.org/property/');
 \EasyRdf\RdfNamespace::set('dbo', 'http://dbpedia.org/ontology/');
 \EasyRdf\RdfNamespace::set('dbr', 'http://dbpedia.org/resource/');
 \EasyRdf\RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
 \EasyRdf\RdfNamespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
 \EasyRdf\RdfNamespace::set('owl', 'http://www.w3.org/2002/07/owl#');
 \EasyRdf\RdfNamespace::set('fb', 'https://example.org/schema/football/');
 \EasyRdf\RdfNamespace::setDefault('og');
 
 $jena_endpoint = new \EasyRdf\Sparql\Client('http://localhost:3030/football/sparql');

 // Query searching dari RDF yang dibuat
 $sparql_query = 'SELECT DISTINCT * WHERE {
     {  ?p 	a fb:player;
            fb:id ?id;
            rdfs:label ?name;
            fb:fullName ?fullName;
            fb:currentClub ?clubN;
            fb:position ?pos;
            foaf:homepage ?link.
        FILTER REGEX (?fullName, "'.$key.'", "i").
     } UNION {
         ?p a fb:player;
            fb:id ?id;
            rdfs:label ?name;
            fb:currentClub ?clubN;
            fb:position ?pos;
            foaf:homepage ?link.
     FILTER REGEX (?clubN, "'.$key.'", "i").
    }
}';      
 
$result = $jena_endpoint->query($sparql_query);

?>

<?php 
    include 'header.php';
    include 'navbar.php';
?>

<section class="py-3">
    <div class="container px-4 px-lg-5 my-3">
        <div class="row gx-4 gx-lg-5 align-items-center mb-2">
            <div class="col-10">
                <ul class="list-unstyled">
                <?php 
                    if($result->numRows() < 1) {
                        echo "<div class='text-center font-weight-bold lead'>Sorry, There's no result for ".$key."</div>";
                    } else {
                        echo "<div class='text-center font-weight-bold lead'>Here's the result for '".$key."'</div>";
                        foreach($result as $row) :
                            $OG = \EasyRdf\Graph::newAndLoad($row->link);                
                ?>
                    <li class="media m-3 bg-dark text-light rounded">
                        <a href="player.php?id=<?= $row->id ?>">
                            <img class="img-fluid rounded m-2" src="<?= $OG->image ?>" alt="" width='100px'>
                        </a>
                        <div class="media-body p-3">
                        <h5 class=""><?= $row->name ?></h5>
                        <p><?= $row->pos ?></p>
                        <p><?= $row->clubN ?></p>
                        </div>
                    </li>
                    <?php 
                        endforeach;
                        }
                    ?> 
                </ul>
            </div>
            
        </div>
    </div>
</section>


<?php include'footer.php'; ?>