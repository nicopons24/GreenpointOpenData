<?php

class pilas
{
    const URL= "http://mapas.valencia.es/lanzadera/opendata/res_pilas/JSON";
    const TIPO = "pilas";

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
            $id = $pilas->properties->id;
            $direccion = $pilas->properties->direccion;
            $centro = $pilas->properties->centro;
            $lat = $pilas->geometry->coordinates[1];
            $long = $pilas->geometry->coordinates[0];
            $c = new Contedor($id, self::TIPO, $direccion, $centro, $lat, $long);
            if (($distance = Calculos::obtenerCalculos()->getDistance($lat, $long, $latUser, $longUser)) < $distancia) {
                array_push($contenedores, $c);
            }
        }
        return $contenedores;
    }
}
