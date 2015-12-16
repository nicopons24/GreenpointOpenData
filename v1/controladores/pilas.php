<?php

class pilas
{
    const URL= "http://mapas.valencia.es/lanzadera/opendata/res_pilas/JSON";
    const TIPO = 6;

    public static function get($parametros)
    {
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
        foreach ($array as $pilas) {
            $lat = $pilas->geometry->coordinates[1];
            $long = $pilas->geometry->coordinates[0];
            if (($distance = Calculos::obtenerCalculos()->getDistance($lat, $long, $latUser, $longUser)) < $distancia) {
                $id = $pilas->properties->id;
                $direccion = $pilas->properties->direccion;
                $c = new Contedor($id, self::TIPO, $direccion, $lat, $long);
                array_push($contenedores, $c);
            }
        }
        return $contenedores;
    }
}
