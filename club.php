<?php
    
    require 'vendor/autoload.php';
    require 'functions.php';

    $URI = $_GET['idn'];
    \EasyRdf\RdfNamespace::set('foaf', 'http://xmlns.com/foaf/0.1/');
    \EasyRdf\RdfNamespace::set('dbp', 'http://dbpedia.org/property/');
    \EasyRdf\RdfNamespace::set('dbo', 'http://dbpedia.org/ontology/');
    \EasyRdf\RdfNamespace::set('dbr', 'http://dbpedia.org/resource/');
    \EasyRdf\RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
    \EasyRdf\RdfNamespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
    \EasyRdf\RdfNamespace::set('owl', 'http://www.w3.org/2002/07/owl#');
    \EasyRdf\RdfNamespace::set('geo', 'http://www.w3.org/2003/01/geo/wgs84_pos#');
    \EasyRdf\RdfNamespace::set('fb', 'https://example.org/schema/football/');
    \EasyRdf\RdfNamespace::setDefault('og');

    $jena_endpoint = new \EasyRdf\Sparql\Client('http://localhost:3030/football/sparql');
    $dbpedia_endpoint = new \EasyRdf\Sparql\Client('https://dbpedia.org/sparql');



    $queryToDBP = 'SELECT DISTINCT * WHERE {
        dbr:'.$URI.' dbo:abstract ?abstract;
                        dbp:clubname ?name;
                        dbo:ground ?ground;
                        dbo:manager ?mng;
                        dbp:fullname ?fullname;
                        foaf:nick ?nick;
                        foaf:isPrimaryTopicOf ?link.
                   ?mng dbp:name ?manager.
                ?ground foaf:name ?stadium;
                        dbo:location ?geo.
                  ?geo  geo:lat ?ltd;
                        geo:long ?lng.
        FILTER(lang(?abstract) = "en" && ?nick != ""@en).
        OPTIONAL{?ground geo:lat ?lat;
                        geo:long ?long.
        }.
    } LIMIT 1';
    

    // execute the query
    $result = $dbpedia_endpoint->query($queryToDBP);

    // if there's no result retrieve, 404 error
    if($result->numRows() < 1) {
        http_response_code(404);
        include('404.php'); // provide your own HTML for the error page
        die();
    }

    // var_dump($result); exit;
    // fetch the result of the query
    foreach($result as $r) {
        $row = $r;
    }
    
    // Fetch form Open Graph Protocol
    $OG = \EasyRdf\Graph::newAndLoad($row->link);
?>

<?php
    include 'header.php';
    include 'navbar.php';
?>
<!-- Product section-->
<section class="py-3">
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5">
            <div class="col-md-5 p-0">
                <img class="img-fluid" src="<?= $OG->image ?>" alt="..." />
                <h3 class="display-5 fw-bolder pt-3">Details</h3>
                <div class="row">
                    <div class="col-3 lead">Full Name</div>
                    <div class="col-9 lead font-weight-bold">: <?= $row->fullname ?></div>
                </div>
                <div class="row">
                    <div class="col-3 lead">Manager</div>
                    <div class="col-9 lead font-weight-bold">: <?= $row->manager ?></div>
                </div>
                <div class="row">
                    <div class="col-3 lead">Stadium</div>
                    <div class="col-9 lead font-weight-bold">: <?= $row->stadium?></div>
                </div>
                <div class="open-street-map">
                    <h3 class="display-5 fw-bolder pt-3">Location</h3>
                    <div id="map" style="width: 450px; height: 300px;"></div>
                    <script>
                        var map = L.map('map').setView([
                            <?php if(isset($row->lat) && isset($row->long)) {
                                    echo $row->lat.','.$row->long;
                                } else {
                                    echo $row->ltd.','.$row->lng;
                                }
                            ?>
                        ], 13);

                        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);

                        L.marker([
                            <?php if(isset($row->lat) && isset($row->long)) {
                                    echo $row->lat.','.$row->long;
                                } else {
                                    echo $row->ltd.','.$row->lng;
                                }
                            ?>
                            ]).addTo(map)
                            .bindPopup('Home Ground <?= $row->fullname?>')
                            .openPopup();             
                    </script>
                </div>
            </div>
            <div class="col-md-7">
                <h1 class="display-5 fw-bolder">
                    <?php
                        if(isset($row->nameF)) {
                            echo $row->nameF;
                        } elseif (isset($row->name)) {
                            echo $row->name;
                        }
                    ?>
                </h1>
                <div class="fs-5 mb-5">
                    <span class="text-decoration-line-through"><?= $row->nick ?></span>
                </div>
                <p class="lead"><?= $row->abstract ?></p>
            </div>
        </div>
    </div>
</section>

<?php include'footer.php'?>