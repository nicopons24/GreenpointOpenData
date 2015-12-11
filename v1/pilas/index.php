<?php
    $latitud=$_GET["latitud"];
    $longitud=$_GET["longitud"];


    $fuente=file_get_contents("http://mapas.valencia.es/lanzadera/opendata/RES_PILAS/JSON");

    print($fuente);




    $resultadoFiltrado=json_encode(json_encode($fuente));
    print($fuente);