<?php

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
            $latlon = Calculos::obtenerCalculos()->coordenadas($lat, $long, 30);
            if (($distance = Calculos::obtenerCalculos()->getDistance($latlon['lat'], $latlon['lon'], $latUser, $longUser)) < $distancia) {
                $id = (int) $papeleras->properties->codigo;
                $p = new Contedor($id,self::TIPO, "", $latlon['lat'], $latlon['lon']);
                array_push($contenedores, $p);
            }
        }
        return $contenedores;
    }
}
