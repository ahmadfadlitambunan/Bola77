<?php
require 'vendor/autoload.php';

\EasyRdf\RdfNamespace::set('foaf', 'http://xmlns.com/foaf/0.1/');
\EasyRdf\RdfNamespace::set('dbp', 'http://dbpedia.org/property/');
\EasyRdf\RdfNamespace::set('dbo', 'http://dbpedia.org/ontology/');
\EasyRdf\RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
\EasyRdf\RdfNamespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
\EasyRdf\RdfNamespace::set('owl', 'http://www.w3.org/2002/07/owl#');
\EasyRdf\RdfNamespace::set('fb', 'https://example.org/schema/football/');
\EasyRdf\RdfNamespace::setDefault('og');

$jena_endpoint = new \EasyRdf\Sparql\Client('http://localhost:3030/football/sparql');

$sparql_query = '
    SELECT DISTINCT ?p ?id ?name ?pos ?club ?cover
    WHERE {
        ?p  a  fb:player;
            fb:id ?id;
            rdfs:label ?name;
            fb:position ?pos;
            fb:currentClub ?club;
            fb:banner ?cover;
            foaf:homepage ?link.
    } ORDER BY ?id LIMIT 3';      

$result = $jena_endpoint->query($sparql_query);
?>

<!-- header -->
<?php include 'header.php'; ?>
<!-- navbar -->
<?php include 'navbar.php'; ?>
<!-- carousel section-->
<div class="container py-3">
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <?php foreach($result as $row) :
            ?>
            <div class="carousel-item <?php if($row->id == 'p1') echo ' active'; ?>">
                <img class="d-block w-100" src="<?= $row->cover ?>" alt="First slide">
                <div class="carousel-caption d-none d-md-block" >
                    <a class="text-light" href="player.php?id=<?= $row->id?>"">
                        <div class="text-center text-light py-1" style="background-color : rgba(0, 0, 0, 0.7)">
                            <h5><?= $row->name ?></h5>
                            <p><?= $row->club; ?></p>
                        </div>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>

        <!-- Related items section-->
        <section class="py-3 bg-light">
            <div class="container px-4 px-lg-5 mt-5">
                <h2 class="fw-bolder mb-4">Pemain Sepakbola</h2>
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                    <?php 
                        $sparql_query2 = '
                        SELECT DISTINCT ?p ?id ?name ?pos ?club ?cover ?link
                        WHERE {
                            ?p  a  fb:player;
                                fb:id ?id;
                                rdfs:label ?name;
                                fb:position ?pos;
                                fb:currentClub ?club;
                                fb:banner ?cover;
                                foaf:homepage ?link.
                        } ORDER BY ?id OFFSET 3';

                        $result2 = $jena_endpoint->query($sparql_query2);
                        foreach($result2 as $res) :
                            $OG = \EasyRdf\Graph::newAndLoad($res->link);
                    ?>

                    <div class="col mb-5">
                        <div class="card h-100">
                            <!-- Product image-->
                            <a href="player.php?id=<?=$res->id?>">
                                <img class="card-img-top" src="<?= $OG->image ?>" alt="..." />
                            </a>
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder"><?= $res->name ?></h5>
                                    <!-- Product price-->
                                    <p><?= $res->pos ?></p>
                                    <p><?= $res->club ?></p>
                                </div>
                            </div>
                            <!-- Product actions-->
                            <div class="card-footer pt-0 border-top-0 bg-transparent">
                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="player.php?id=<?= $res->id?>">Details</a></div>
                            </div>
                        </div>
                    </div> 
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

<?php include 'header.php' ?>