<?php
class contenedores {

    const URL = "http://mapas.valencia.es/lanzadera/opendata/fjiejfiejfefefefeff/JSON";

    public static function get($parametros) {
        $latUser = $parametros[0];
        $longUser = $parametros[1];
        $distancia = $parametros[2];
        $tipo = $parametros[3];
        $contenedores = Calculos::obtenerCalculos()->getJSONFromUrl(self::URL);

        return [
            "contenedores" => self::obtenerInformacionContenedores($contenedores, $tipo, $latUser, $longUser, $distancia)
        ];
    }

    private function obenerInformacionContenedores($array, $tipo, $latUser, $longUser, $distancia) {
        foreach ($array as $item) {

        }
    }

}