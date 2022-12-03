<?php
    require 'vendor/autoload.php';
    require 'functions.php';

    \EasyRdf\RdfNamespace::set('foaf', 'http://xmlns.com/foaf/0.1/');
    \EasyRdf\RdfNamespace::set('dbp', 'http://dbpedia.org/property/');
    \EasyRdf\RdfNamespace::set('dbo', 'http://dbpedia.org/ontology/');
    \EasyRdf\RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
    \EasyRdf\RdfNamespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
    \EasyRdf\RdfNamespace::set('owl', 'http://www.w3.org/2002/07/owl#');
    \EasyRdf\RdfNamespace::set('fb', 'https://example.org/schema/football/');
    \EasyRdf\RdfNamespace::setDefault('og');

    $jena_endpoint = new \EasyRdf\Sparql\Client('http://localhost:3030/football/sparql');
    $dbpedia_endpoint = new \EasyRdf\Sparql\Client('https://dbpedia.org/sparql');

?>

<?php
    include 'header.php';
    include 'navbar.php';
?>
<section class="py-3">
    <div class="container px-4 px-lg-5 my-5">
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
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6">
                                Tempat Lahir :
                            </div>
                            <div class="col-6 text-bold">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6">
                                Tanggal Lahir (umur) :
                            </div>
                            <div class="col-6 text-bold">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6">
                                Tinggi  :
                            </div>
                            <div class="col-6 text-bold">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6">
                                Kaki Terkuat  :
                            </div>
                            <div class="col-6 text-bold">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6">
                                Posisi  :
                            </div>
                            <div class="col-6 text-bold">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6">
                                Club Sekarang  :
                            </div>
                            <div class="col-6 text-bold">
                                
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6">
                                Harga Pasar  :
                            </div>
                            <div class="col-6 text-bold">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 ">
                                <h3 class="font-weight-bold">Clubs :</h3>
                                <div class="list-group list-group-flush">
                                    <a href="club.php?idn=?" class="list-group-item list-group-item-action">fsa</a>
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

<?php include'footer.php'?>