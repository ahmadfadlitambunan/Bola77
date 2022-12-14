<?php 
   require 'vendor/autoload.php';
   require 'functions.php';

$id = $_GET['id'];
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
$dbpedia_endpoint = new \EasyRdf\Sparql\Client('https://dbpedia.org/sparql');

// Query ke RDF untuk mengambil detail singkat pemain
$sparql_query = 'SELECT ?p ?id ?name ?fullName ?bDay ?bPlace ?height ?foot ?clubN ?pos ?price ?banner ?link WHERE {
	?p 	a fb:player;
  		fb:id ?id;
  		rdfs:label ?name;
        fb:fullName ?fullName;
     	fb:birthDate ?bDay;
        fb:birthPlace ?bPlace;
        fb:height ?height;
        fb:banner ?banner;
        fb:foot ?foot;
        fb:position ?pos;
        fb:currentClub ?clubN;
        fb:marketValue ?price;
      	foaf:homepage ?link.
    FILTER(?id = "'.$id.'").
    } LIMIT 1';      

$result = $jena_endpoint->query($sparql_query);

// Fetch sparQL results
foreach($result as $r) {
    $row = $r;
}
// Get data from OpenGraph 'transfermarkt.com'
$og = \EasyRdf\Graph::newAndLoad($row->link); 

//Query To DBPedia To get All clubs
$queryCLubs = '
    SELECT DISTINCT ?club ?yClub ?clubName ?yClubName WHERE {
        <'.$row->p.'> dbp:clubs ?club.
        ?club dbp:clubname ?clubName.
    }';




$res = $dbpedia_endpoint->query($queryCLubs);
?>

<?php 
    include 'header.php';
    include 'navbar.php';
?>

<!-- Detalis section-->
<section class="py-3">
    <div class="container px-4 px-lg-5 my-3">
        <div class="text-center font-weight-bold lead mb-3">
            <h1>Profile Pemain</h1>
        </div>
        <div class="row gx-4 gx-lg-5 align-items-center mb-2">
            <div class="card text-white">
                <img class="card-img" src="<?= $row->banner ?>" alt="Card image">
                <div class="card-img-overlay">
                    <h2 class="card-title"><?= $row->name ?></h2>
                </div>
            </div>
        </div>
        <div class="row gx-4 gx-lg-5">
            <div class="col-md-4 pl-0"><img class="img-fluid rounded" src="<?= $og->image ?>" alt="..." width="1200" height="400"/></div>
            <div class="col-md-8 lead">
                <h1 class="display-5 fw-bolder">Player Data</h1>
                <div class="row mb-2">
                    <div class="col-6">
                        Full Name :
                    </div>
                    <div class="col-6 text-bold">
                        <?= $row->fullName ?>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">
                        Birth Place :
                    </div>
                    <div class="col-6 text-bold">
                        <?= $row->bPlace ?>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">
                        Birth Date (Age) :
                    </div>
                    <div class="col-6 text-bold">
                        <?= $row->bDay ?> (<?= getAge($row->bDay) ?>)
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">
                        Height  :
                    </div>
                    <div class="col-6 text-bold">
                        <?= $row->height ?>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">
                        Prefered Foot  :
                    </div>
                    <div class="col-6 text-bold">
                        <?= $row->foot ?>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">
                        Position  :
                    </div>
                    <div class="col-6 text-bold">
                        <?= $row->pos ?>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">
                        Current Club  :
                    </div>
                    <div class="col-6 text-bold">
                        <?= $row->clubN ?>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">
                        Market Value  :
                    </div>
                    <div class="col-6 text-bold">
                        <?= $row->price ?>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6 ">
                        <h3 class="font-weight-bold">Clubs :</h3>
                        <div class="list-group list-group-flush">
                            <?php foreach($res as $r) :?>
                            <a href="club.php?idn=<?= extractURI($r->club)?>" class="list-group-item list-group-item-action"><?= $r->clubName ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php' ?>