<?php

/**
 * Created by PhpStorm.
 * User: pablo
 * Date: 12/01/2016
 * Time: 2:50
 */
class cercanos
{
    const URL_aceite = "C:/xampp/htdocs/GreenpointOpenData/v1/jsonaceite.json";
    const ACEITE = 5;

    const URL_contenedores = "C:/xampp/htdocs/GreenpointOpenData/v1/jsoncontenedores.json";
    const ORGANICO = 1;
    const CARTON = 2;
    const PLASTICO = 3;
    const VIDRIO = 4;

    const URL_papeleras = "C:/xampp/htdocs/GreenpointOpenData/v1/jsonpapeleras.json";
    const PAPELERAS = 0;

    const URL_pilas = "C:/xampp/htdocs/GreenpointOpenData/v1/jsonpilas.json";
    const PILAS = 6;

    public static function get($parametros)
    {
        $latUser = $parametros[0];
        $longUser = $parametros[1];

        $contenedorescercanos = array();

        //contenedor mas cercano de residuos urbanos , carton , envase , vidrio
        $contenedores = Calculos::obtenerCalculos()->getJSONFromUrl(self::URL_contenedores);
        $containers = self::obtenerInformacionContenedores($contenedores, $latUser, $longUser);
        array_push($contenedorescercanos, $containers[0]);
        array_push($contenedorescercanos, $containers[1]);
        array_push($contenedorescercanos, $containers[2]);
        array_push($contenedorescercanos, $containers[3]);

        //contenedor mas dercano de aceite
        $contenedores = Calculos::obtenerCalculos()->getJSONFromUrl(self::URL_aceite);
        $container = self::obtenerInformacionaceite($contenedores, $latUser, $longUser);
        array_push($contenedorescercanos, $container);

        //papelera más cercana
        $contenedores = calculos::obtenerCalculos()->getJSONFromUrl(self::URL_papeleras);
        $container = self::obtenerInformacionPapeleras($contenedores, $latUser, $longUser);
        array_push($contenedorescercanos, $container);

        //contenedor pilas más cercano
        $contenedores = Calculos::obtenerCalculos()->getJSONFromUrl(self::URL_pilas);
        $container = self::obtenerInformacionPilas($contenedores, $latUser, $longUser);
        array_push($contenedorescercanos, $container);

        return [
            "contenedores" => $contenedorescercanos
        ];

    }

    function error($tipoerror, $numero, $texto)
    {
        $ddf = fopen('error' . $tipoerror . '.log', 'a');
        fwrite($ddf, " $numero: $texto\r\n");
        fclose($ddf);
    }
    function escribir($nombrefichero,$contenido){
        $fichero = fopen($nombrefichero.".json", 'w');
        fwrite($fichero,json_encode($contenido,JSON_PRETTY_PRINT));

        fclose($fichero);
    }

    private function obtenerInformacionContenedores($array, $latUser, $longUser)
    {
        $c1 = null;
        $c2 = null;
        $c3 = null;
        $c4 = null;
        $u1 = null;
        $u2 = null;
        $u3 = null;
        $u4 = null;
        $contenedores = array();
        for ($i = 0; $i < count($array); $i++) {

            //blucle tipo 1
            $contenedor = $array[$i];
            $lat = $contenedor->lat;
            $long = $contenedor->log;
            $distance = Calculos::obtenerCalculos()->getDistance($lat,$long, $latUser, $longUser);
            if ($u1 == null) {
                $u1 = $distance;
            }
            if ($u2 == null) {
                $u2 = $distance;
            }
            if ($u3 == null) {
                $u3 = $distance;
            }
            if ($u4 == null) {
                $u4 = $distance;
            }
            $idTipo = $contenedor->tipo;

            if (Calculos::obtenerCalculos()->comparaCoordenates(array($lat, $long, $idTipo))) {

                if ($idTipo == self::ORGANICO) {
                    if ($distance <= $u1) {
                        $u1 = $distance;
                        $calle = $contenedor->direccion;
                        $c1 = new Contedor($i, self::ORGANICO, $calle, $lat,$long);
                    }
                }


                //blucle tipo 2

                if ($idTipo == self::CARTON) {

                    if ($distance <= $u2) {
                        $u2 = $distance;
                        $calle = $contenedor->direccion;
                        $c2 = new Contedor($i, self::CARTON, $calle,$lat,$long);
                    }

                }

                //blucle tipo 3

                if ($idTipo == self::PLASTICO) {

                    if ($u3 == null) {
                        $u3 = $distance;
                    }

                    if ($distance <= $u3) {
                        $u3 = $distance;
                        $calle = $contenedor->direccion;
                        $c3 = new Contedor($i, self::PLASTICO, $calle, $lat,$long);
                    }
                }

                //blucle tipo 4

                if ($idTipo == self::VIDRIO) {
                    if ($u4 == null) {
                        $u4 = $distance;
                    }
                    if ($distance <= $u4) {
                        $u4 = $distance;
                        $calle = $contenedor->direccion;
                        $c4 = new Contedor($i, self::VIDRIO, $calle, $lat,$long);
                    }
                }
            }
        }


        array_push($contenedores, $c1);
        array_push($contenedores, $c2);
        array_push($contenedores, $c3);
        array_push($contenedores, $c4);
        return $contenedores;
    }

    private
    function obtenerInformacionaceite($array, $latUser, $longUser)
    {
        $u5 = -1;
        $p5 = null;
        for ($i = 0; $i < count($array); $i++) {
            $aceite = $array[$i];
            $lat = $aceite->lat;
            $long = $aceite->log;
            $distance = Calculos::obtenerCalculos()->getDistance($lat,$long, $latUser, $longUser);
            if ($u5 < 0) {
                $u5 = $distance;
            }
            $direccion = $aceite->direccion;
            $id = $i + 1;
            if ($distance <= $u5) {
                $p5 = new Contedor($id, self::ACEITE, $direccion, $lat,$long);
            }
        }
        return $p5;
    }

    function obtenerInformacionPapeleras($array, $latUser, $longUser)
    {
        $u0 = -1;
        $p0 = null;
        foreach ($array as $papeleras) {
            $lat = $papeleras->lat;
            $long = $papeleras->log;
            $distance = Calculos::obtenerCalculos()->getDistance($lat,$long, $latUser, $longUser);
            if ($u0 < 0) {
                $u0 = $distance;
            }
            $id = (int)$papeleras->id;
            if ($distance <= $u0) {
                $u0 = $distance;
                $p0 = new Contedor($id, self::PAPELERAS, "", $lat,$long);
            }
        }
        return $p0;
    }

    function obtenerInformacionPilas($array, $latUser, $longUser)
    {
        $u6 = null;
        $p6 = null;
        for ($i = 0; $i < count($array); $i++) {
            $pilas = $array[$i];
            $lat = $pilas->lat;
            $long = $pilas->log;
            $distance = Calculos::obtenerCalculos()->getDistance($lat,$long, $latUser, $longUser);
            if ($u6 == null) {
                $u6 = $distance;
            }
            $id = $i + 1;
            $direccion = $pilas->direccion;
            if ($distance < $u6) {
                $u6 = $distance;
                $p6 = new Contedor($id, self::PILAS, $direccion, $lat,$long);
            }
        }

        return $p6;
    }
}