<?php

/**
 * Created by PhpStorm.
 * User: pablo
 * Date: 12/01/2016
 * Time: 2:50
 */
class cercanos
{
    const URL_aceite = "http://mapas.valencia.es/lanzadera/opendata/res_aceite/JSON";
    const ACEITE = 5;

    const URL_contenedores = "http://mapas.valencia.es/lanzadera/opendata/res_contenedor/JSON";

    const ORGANICO = 1;
    const CARTON = 2;
    const PLASTICO = 3;
    const VIDRIO = 4;

    const URL_papeleras = "http://mapas.valencia.es/lanzadera/opendata/Res_papeleras/JSON";
    const PAPELERAS = 0;

    const URL_pilas = "http://mapas.valencia.es/lanzadera/opendata/res_pilas/JSON";
    const PILAS = 6;

    public static function get($parametros)
    {
        $latUser = $parametros[0];
        $longUser = $parametros[1];
        $distancia = $parametros[2];

        $contenedorescercanos = array();

        //contenedor mas cercano de residuos urbanos , carton , envase , vidrio
        $contenedores = Calculos::obtenerCalculos()->getJSONFromUrl(self::URL_contenedores);
        $containers = self::obtenerInformacionContenedores($contenedores, $latUser, $longUser, $distancia);
        array_push($contenedorescercanos, $containers);

        //contenedor mas dercano de aceite
        $contenedores = Calculos::obtenerCalculos()->getJSONFromUrl(self::URL_aceite);
        $containers = self::obtenerInformacionaceite($contenedores, $latUser, $longUser, $distancia);
        array_push($contenedorescercanos, $containers);

        //papelera más cercana
        $contenedores = calculos::obtenerCalculos()->getJSONFromUrl(self::URL_papeleras);
        $containers = self::obtenerInformacionPapeleras($contenedores, $latUser, $longUser, $distancia);
        array_push($contenedorescercanos, $containers);

        //contenedor pilas más cercano
        $contenedores = Calculos::obtenerCalculos()->getJSONFromUrl(self::URL_pilas);
        $containers = self::obtenerInformacionapilas($contenedores, $latUser, $longUser, $distancia);
        array_push($contenedorescercanos, $containers);

        return [
            //"contenedores" => self::obtenerInformacionContenedores($contenedores, $latUser, $longUser, $distancia)
            "contenedores" => $contenedorescercanos
        ];

    }

    private function obtenerInformacionContenedores($array, $latUser, $longUser, $distancia)
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
            $contenedor = $array[$i];
            $idTipo = contenedores::obtenerTipo($contenedor->properties->tipo);
            $lat = $contenedor->geometry->coordinates[1];
            $long = $contenedor->geometry->coordinates[0];
            $latlon=Calculos::coordenadas($lat,$long,30);

            if (($distance = Calculos::obtenerCalculos()->getDistance($latlon['lat'], $latlon['lon'], $latUser, $longUser)) < $distancia) {
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
                $idContenedor = $i + 1;
                //contenedor tipo 1
                if ($distance <= $u1 && $idTipo==self::ORGANICO) {
                    $u1 = $distance;

                    $calle = contenedores::obtenerDireccion($contenedor->properties->tipovia, $contenedor->properties->calleempre, $contenedor->properties->numportal);
                    $c1 = new Contedor($idContenedor, self::ORGANICO, $calle,$latlon['lat'], $latlon['lon']);

                }
                //contenedor tipo 2
                if ($distance <= $u2 && $idTipo==self::CARTON) {
                    $u2 = $distance;

                    $calle = contenedores::obtenerDireccion($contenedor->properties->tipovia, $contenedor->properties->calleempre, $contenedor->properties->numportal);
                    $c2 = new Contedor($idContenedor, self::CARTON, $calle, $latlon['lat'], $latlon['lon']);

                }
                //contenedor tipo 3
                if ($distance <= $u3 && $idTipo==self::PLASTICO) {
                    $u3 = $distance;

                    $calle = contenedores::obtenerDireccion($contenedor->properties->tipovia, $contenedor->properties->calleempre, $contenedor->properties->numportal);
                    $c3 = new Contedor($idContenedor, self::PLASTICO, $calle, $latlon['lat'], $latlon['lon']);

                }
                //contenedor tipo 4
                if ($distance <= $u4 && $idTipo==self::VIDRIO) {
                    $u4 = $distance;

                    $calle = contenedores::obtenerDireccion($contenedor->properties->tipovia, $contenedor->properties->calleempre, $contenedor->properties->numportal);
                    $c4 = new Contedor($idContenedor, self::VIDRIO, $calle, $latlon['lat'], $latlon['lon']);

                }

            }
        }
        array_push($contenedores, $c1);
        array_push($contenedores, $c2);
        array_push($contenedores, $c3);
        array_push($contenedores, $c4);
        return $contenedores;
    }

    function obtenerInformacionaceite($array, $latUser, $longUser, $distancia)
    {
        $u5 = null;
        $p5=null;
        $contenedores = array();
        foreach ($array as $aceite) {
            $lat = $aceite->geometry->coordinates[1];
            $long = $aceite->geometry->coordinates[0];
            $latlon=Calculos::coordenadas($lat,$long,30);
            if (($distance = Calculos::obtenerCalculos()->getDistance($latlon['lat'], $latlon['lon'], $latUser, $longUser)) < $distancia) {
                if ($u5 == null) {
                    $u5 = $distance;
                }
                $direccion = $aceite->properties->direccion;
                $id = $aceite->properties->codigo;
                if ($distance < $u5) {
                    $p5 = new Contedor($id, self::ACEITE,$direccion, $latlon['lat'], $latlon['lon']);
                }
            }
        }
        array_push($contenedores, $p5);
        return $contenedores;
    }

    function obtenerInformacionPapeleras($array, $latUser, $longUser, $distancia)
    {
        $u0 = null;
        $p0=null;
        $contenedores = array();
        foreach ($array as $papeleras) {
            $lat = $papeleras->geometry->coordinates[1];
            $long = $papeleras->geometry->coordinates[0];
            $latlon = Calculos::coordenadas($lat, $long, 30);
            if (($distance = Calculos::obtenerCalculos()->getDistance($latlon['lat'], $latlon['lon'], $latUser, $longUser)) < $distancia) {
                if ($u0 == null) {
                    $u0 = $distance;
                }
                $id = $papeleras->properties->codigo;
                if ($distance < $u0) {
                    $p0 = new Papelera($id, self::PAPELERAS, $latlon['lat'], $latlon['lon']);
                }
            }
        }
        array_push($contenedores, $p0);
        return $contenedores;
    }

    function obtenerInformacionPilas($array, $latUser, $longUser, $distancia)
    {
        $u6 = null;
        $p6=null;
        $contenedores = array();
        foreach ($array as $pilas) {
            $lat = $pilas->geometry->coordinates[1];
            $long = $pilas->geometry->coordinates[0];
            $latlon = Calculos::coordenadas($lat, $long, 30);
            if (($distance = Calculos::obtenerCalculos()->getDistance($latlon['lat'], $latlon['lon'], $latUser, $longUser)) < $distancia) {
                if($u6=null){$u6=$distance;}
                $id = $pilas->properties->id;
                $direccion = $pilas->properties->direccion;
                if (distance < $u6) {
                    $p6 = new Contedor($id, self::PILAS, $direccion, $latlon['lat'], $latlon['lon']);
                }
            }
        }
        array_push($contenedores, $p6);
        return $contenedores;
    }
}