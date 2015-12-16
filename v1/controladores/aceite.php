<?php

class aceite
{
    const URL = "http://mapas.valencia.es/lanzadera/opendata/res_aceite/JSON";
    const TIPO = 5;

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
        foreach ($array as $aceite) {
            $lat = $aceite->geometry->coordinates[1];
            $long = $aceite->geometry->coordinates[0];
            if (($distance = Calculos::obtenerCalculos()->getDistance($lat, $long, $latUser, $longUser)) < $distancia) {
                $id = $aceite->properties->id;
                $direccion = $aceite->properties->direccion;
                $centro = $aceite->properties->centro;
                $c = new Contedor($id, self::TIPO, $direccion, $centro, $lat, $long);
                array_push($contenedores, $c);
            }
        }
        return $contenedores;
    }
}
