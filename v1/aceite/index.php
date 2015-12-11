<?php
require '/../data/contenedor.php';
require '/../data/calculos.php';

const URL_ACEITE = "http://mapas.valencia.es/lanzadera/opendata/res_aceite/JSON";

$fuente = file_get_contents(URL_ACEITE);
$body = json_decode($fuente);

$features = obtenerArrayContendores($body);
$listaContenedores = obtenerInformacionContenedores($features);
print json_encode($listaContenedores);

function obtenerInformacionContenedores($array) {
    $contenedores = array();
    foreach($array as $aceite) {
        $tipo = "aceite";
        $id = $aceite->properties->id;
        $direccion = $aceite->properties->direccion;
        $centro = $aceite->properties->centro;
        $long = $aceite->geometry->coordinates[0];
        $lat = $aceite->geometry->coordinates[1];
        $c = new Contedor($id, $tipo, $direccion, $centro, $lat, $long);
        if (($distance = Calculos::obtenerCalculos()->getDistance($lat, $long, 39.471210, -0.408766)) < 200) {
            array_push($contenedores, $c);
        }
    }
    return $contenedores;
}

function obtenerArrayContendores($body)
{
    $listaContenedores = $body->features;
    return $listaContenedores;
}

function getDistance(  ) {

}