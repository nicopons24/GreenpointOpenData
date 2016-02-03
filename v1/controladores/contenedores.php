<?php

class contenedores
{


    const URL_contenedores = "C:/xampp/htdocs/GreenpointOpenData/v1/jsoncontenedores.json";
    const ORGANICO = 1;
    const CARTON = 2;
    const PLASTICO = 3;
    const VIDRIO = 4;
    const ORGANICOT = 7;

    public static function get($parametros)
    {
        $latUser = $parametros[0];
        $longUser = $parametros[1];
        $distancia = $parametros[2];
        $tipo = $parametros[3];

        $contenedores = Calculos::obtenerCalculos()->getJSONFromUrl(self::URL_contenedores);
        return [
            "contenedores" => self::obtenerInformacionContenedores($contenedores, $tipo, $latUser, $longUser, $distancia)
        ];
    }

    private function obtenerInformacionContenedores($array, $tipo, $latUser, $longUser, $distancia)
    {
        $contenedores = array();
        for ($i = 0; $i < count($array); $i++) {
            $contenedor = $array[$i];
            $idTipo = $contenedor->tipo;
            if ($tipo == $idTipo) {
                $lat = $contenedor->lat;
                $long = $contenedor->log;
                if (($distance = Calculos::obtenerCalculos()->getDistance($lat, $long, $latUser, $longUser)) < $distancia) {
                    $idContenedor = $i;
                    $calle = $contenedor->direccion;
                    $c = new Contedor($idContenedor, $idTipo, $calle, $lat, $long);
                    array_push($contenedores, $c);
                }
            }
        }
        return $contenedores;
    }

    public static
    function obtenerTipo($tipo)
    {
        $tipos = array('RESIDUOS URBANOS', 'PAPEL CARTON', 'ENVASES LIGEROS', 'VIDRIO');
        $valor = array(self::ORGANICO, self::CARTON, self::PLASTICO, self::VIDRIO);
        for ($i = 0; $i < count($tipos); $i++) {
            if($tipo=='RESIDUOS URBANOS T'){
                return 1;
            }
            if ($tipo == $tipos[$i]) {
                return $valor[$i];
            }
        }
    }

    public static
    function obtenerDireccion($tipovia, $nomvia, $numportal)
    {
        $codigos = array('C.N.', 'C', 'AV', 'PLZ', 'PL', 'C.V.', 'PSO', 'G.V.', 'LUG',
            'BAR', 'SEN', 'SENDA', '', 'CTRA', 'CMNO', 'TRV', 'CRA', 'ENTRD',
            'ENTD', 'PJE', 'CON', 'GRUP');
        $tipos = array('CAMINO', 'CALLE', 'AVENIDA', 'PLAZA', 'PLAZA', 'CAMINO', 'PASEO',
            'GRAN VIA', '', 'BAR', 'SENDA', 'SENDA', '', 'CARRETERA', 'CAMINO',
            'TRAVESIA', 'CARRERA', 'ENTRADA', 'ENTRADA', 'PASAJE', 'CONSTRUCCUION', 'CALLE GRUPO');
        for ($i = 0; $i < count($codigos); $i++) {
            if ($tipovia == $codigos[$i])
                return $tipos[$i] . " " . $nomvia . ", " . $numportal;
        }
    }

}