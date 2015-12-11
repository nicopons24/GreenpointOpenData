<?php
    //http://gobiernoabierto.valencia.es/wp-content/themes/viavansi-ogov/proxyFile.php?url=http://mapas.valencia.es/lanzadera/opendata/RES_PILAS/JSON
    $fuente=file_get_contents("http://gobiernoabierto.valencia.es/wp-content/themes/viavansi-ogov/proxyFile.php?url=http://mapas.valencia.es/lanzadera/opendata/RES_PILAS/JSON");

//print($fuente);


    //$latitud=$_POST["latitud"];
    //$longitud=$_POST["longitud"];

    $resultadoFiltrado=json_encode(json_encode($fuente));
    print($fuente);