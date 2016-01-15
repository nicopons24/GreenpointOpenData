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
        for ($i = 0; $i < count($array); $i++) {
            $aceite = $array[$i];
            $lat = $aceite->geometry->coordinates[1];
            $long = $aceite->geometry->coordinates[0];
            $latlon = Calculos::obtenerCalculos()->coordenadas($lat, $long, 30);
            if (($distance = Calculos::obtenerCalculos()->getDistance($latlon['lat'], $latlon['lon'], $latUser, $longUser)) < $distancia) {
                $id = $i + 1;
                $direccion = $aceite->properties->direccion;
                $c = new Contedor($id, self::TIPO, $direccion, $latlon['lat'], $latlon['lon']);
                array_push($contenedores, $c);
            }
        }
        return $contenedores;
    }
}
