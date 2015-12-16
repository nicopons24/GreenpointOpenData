<?php

require '/../datos/papelera.php';

class papeleras {

    const URL = "http://mapas.valencia.es/lanzadera/opendata/Res_papeleras/JSON";
    const TIPO = 0;

    public static function get($parametros){
        $latUser = $parametros[0];
        $longUser = $parametros[1];
        $distancia = $parametros[2];
        $contenedores = Calculos::obtenerCalculos()->getJSONFromUrl(self::URL);
        return [
            "contenedores" => self::obtenerInformacionContenedores($contenedores, $latUser, $longUser, $distancia)
        ];
    }

    function obtenerInformacionContenedores($array, $latUser, $longUser, $distancia)
    {
        $contenedores = array();
        foreach ($array as $papeleras) {
            $lat = $papeleras->geometry->coordinates[1];
            $long = $papeleras->geometry->coordinates[0];
            if (($distance = Calculos::obtenerCalculos()->getDistance($lat, $long, $latUser, $longUser)) < $distancia) {
                $id = $papeleras->properties->codigo;
                $p = new Papelera($id,self::TIPO, $lat, $long);
                array_push($contenedores, $p);
            }
        }
        return $contenedores;
    }
}
