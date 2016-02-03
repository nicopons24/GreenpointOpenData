<?php

class papeleras {


    const URL_papeleras = "C:/xampp/htdocs/GreenpointOpenData/v1/jsonpapeleras.json";
    const TIPO = 0;

    public static function get($parametros){
        $latUser = $parametros[0];
        $longUser = $parametros[1];
        $distancia = $parametros[2];
        $contenedores = Calculos::obtenerCalculos()->getJSONFromUrl(self::URL_papeleras);
        return [
            "contenedores" => self::obtenerInformacionContenedores($contenedores, $latUser, $longUser, $distancia)
        ];
    }

    function obtenerInformacionContenedores($array, $latUser, $longUser, $distancia)
    {
        $contenedores = array();
        foreach ($array as $papeleras) {
            $lat = $papeleras->lat;
            $long = $papeleras->log;
            if (($distance = Calculos::obtenerCalculos()->getDistance($lat,$long, $latUser, $longUser)) < $distancia) {
                $id = (int) $papeleras->id;
                $p = new Contedor($id,self::TIPO, "", $lat,$long);
                array_push($contenedores, $p);
            }
        }
        return $contenedores;
    }
}
