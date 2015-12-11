<?php
        require_once("../data/contenedor.php");

        $fuente = file_get_contents("http://mapas.valencia.es/lanzadera/opendata/RES_PILAS/JSON");

        $fuente_decode=json_decode($fuente);

        $contenedores_pilas=obtenerPilas($fuente_decode);

        //print_r($contenedores_pilas);

        $arrayPilas=array();
        print_r(count($contenedores_pilas));

        foreach($contenedores_pilas as $pila){
            $id=$pila->properties->id;
            $tipo="pilas";
            $direccion=$pila->properties->direccion;
            $centro=$pila->properties->centro;
            $lat=$pila->geometry->coordinates[1];
            $log=$pila->geometry->coordinates[0];

            $contentPilas=new Contedor($id,$tipo,$direccion,$centro,$lat,$log);
            $arrayPilas[] = $contentPilas;
            array_push($arrayPilas,$contentPilas);
        }
        $newjson=json_encode($arrayPilas);
        print $newjson;
        //print_r($arrayPilas);

    function obtenerPilas($contenedores_pilas){
        $pilas=$contenedores_pilas->features;
        return $pilas;
    }
