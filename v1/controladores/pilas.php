<?php

class pilas
{

    const URL_pilas = "C:/xampp/htdocs/GreenpointOpenData/v1/jsonpilas.json";
    const TIPO = 6;

    public static function get($parametros)
    {
        $latUser = $parametros[0];
        $longUser = $parametros[1];
        $distancia = $parametros[2];
        $contenedores = Calculos::obtenerCalculos()->getJSONFromUrl(self::URL_pilas);
        return [
            "contenedores" => self::obtenerInformacionContenedores($contenedores, $latUser, $longUser, $distancia)
        ];
    }

    function obtenerInformacionContenedores($array, $latUser, $longUser, $distancia)
    {
        $contenedores = array();
        for ($i = 0; $i < count($array); $i++) {
            $pilas = $array[$i];
            $lat = $pilas->lat;
            $long = $pilas->log;
            if (($distance = Calculos::obtenerCalculos()->getDistance($lat,$long, $latUser, $longUser)) < $distancia) {
                $id = $i + 1;
                $direccion = $pilas->direccion;
                $c = new Contedor($id, self::TIPO, $direccion, $lat,$long);
                array_push($contenedores, $c);
            }
        }
        return $contenedores;
    }
}
