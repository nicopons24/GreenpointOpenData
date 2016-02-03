<?php

class aceite
{

    const URL_aceite = "C:/xampp/htdocs/GreenpointOpenData/v1/jsonaceite.json";
    const TIPO = 5;

    public static function get($parametros)
    {
        $latUser = $parametros[0];
        $longUser = $parametros[1];
        $distancia = $parametros[2];

        $contenedores = Calculos::obtenerCalculos()->getJSONFromUrl(self::URL_aceite);
        return [
            "contenedores" => self::obtenerInformacionContenedores($contenedores, $latUser, $longUser, $distancia)
        ];
    }

    function obtenerInformacionContenedores($array, $latUser, $longUser, $distancia)
    {
        $contenedores = array();
        for ($i = 0; $i < count($array); $i++) {
            $aceite = $array[$i];
            $lat = $aceite->lat;
            $long = $aceite->long;
            if (($distance = Calculos::obtenerCalculos()->getDistance($lat, $long, $latUser, $longUser)) < $distancia) {
                $id = $i + 1;
                $direccion = $aceite->direccion;
                $c = new Contedor($id, self::TIPO, $direccion, $lat, $long);
                array_push($contenedores, $c);
            }
        }
        return $contenedores;
    }
}
