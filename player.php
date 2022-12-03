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

//Query To DBPedia
$queryCLubs = '
    SELECT DISTINCT ?club ?clubN ?clubName ?cClubName WHERE {
        <'.$row->p.'> dbp:clubs ?club;
                    dbp:currentclub ?clubN.
        ?club dbp:clubname ?clubName.
        ?clubN dbp:clubname ?cClubName.
    }';

$res = $dbpedia_endpoint->query($queryCLubs);
?>

<?php 
    include 'header.php';
    include 'navbar.php';
?>

<!-- Detalis section-->
<section class="py-3">
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5 align-items-center mb-2">
            <div class="card bg-dark text-white">
                <img class="card-img" src="<?= $row->banner ?>" alt="Card image">
                <div class="card-img-overlay">
                    <h1 class="card-title"><?= $row->name ?></h1>
                </div>
            </div>
        </div>
        <div class="row gx-4 gx-lg-5">
            <div class="col-md-4 pl-0"><img class="img-fluid rounded" src="<?= $og->image ?>" alt="..." /></div>
            <div class="col-md-8">
                <h1 class="display-5 fw-bolder">Data Pemain</h1>
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-6">
                                Nama Lengkap :
                            </div>
                            <div class="col-6 text-bold">
                                <?= $row->fullName ?>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6">
                                Tempat Lahir :
                            </div>
                            <div class="col-6 text-bold">
                                <?= $row->bPlace ?>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6">
                                Tanggal Lahir (umur) :
                            </div>
                            <div class="col-6 text-bold">
                                <?= $row->bDay ?>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6">
                                Tinggi  :
                            </div>
                            <div class="col-6 text-bold">
                                <?= $row->height ?>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6">
                                Kaki Terkuat  :
                            </div>
                            <div class="col-6 text-bold">
                                <?= $row->foot ?>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6">
                                Posisi  :
                            </div>
                            <div class="col-6 text-bold">
                                <?= $row->pos ?>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6">
                                Club Sekarang  :
                            </div>
                            <div class="col-6 text-bold">
                                <?php 
                                foreach($res as $r) :
                                    $club = $r->cClubName;
                                endforeach;
                                    echo $club;
                                ?>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6">
                                Harga Pasar  :
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
                                    <a href="club.php?idn=?<?= extractURI($r->club)?>" class="list-group-item list-group-item-action"><?= $r->clubName ?></a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <!-- <div class="col-6 text-bold">
                                <h3 class="font-weight-bold">National Teams :</h3>
                                <div class="list-group list-group-flush">
                                    <a href="#" class="list-group-item list-group-item-action">Dapibus ac facilisis in</a>
                                    <a href="#" class="list-group-item list-group-item-action">Morbi leo risus</a>
                                    <a href="#" class="list-group-item list-group-item-action">Porta ac consectetur ac</a>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php' ?>