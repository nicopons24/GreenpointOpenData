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

    const URL_papeleras = "http://mapas.valencia.es/lanzadera/opendata/res_papeleras/JSON";
    const PAPELERAS = 0;

    const URL_pilas = "http://mapas.valencia.es/lanzadera/opendata/res_pilas/JSON";
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
            //"contenedores" => self::obtenerInformacionContenedores($contenedores, $latUser, $longUser, $distancia)
            "contenedores" => $contenedorescercanos
        ];

    }

    private function obtenerInformacionContenedores($array, $latUser, $longUser)
    {
        $id1 = null;
        $id2 = null;
        $id3 = null;
        $id4 = null;
        $c1 = null;
        $c2 = null;
        $c3 = null;
        $c4 = null;
        $u1 = null;
        $u2 = null;
        $u3 = null;
        $u4 = null;
        $contenedores = array();

        //blucle tipo 1
        for ($i = 0; $i < count($array); $i++) {
            $contenedor = $array[$i];
            $idTipo = contenedores::obtenerTipo($contenedor->properties->tipo);
            if ($idTipo == self::ORGANICO) {
                $lat = $contenedor->geometry->coordinates[1];
                $long = $contenedor->geometry->coordinates[0];
                $latlon = Calculos::obtenerCalculos()->coordenadas($lat, $long, 30);
                $distance = Calculos::obtenerCalculos()->getDistance($latlon['lat'], $latlon['lon'], $latUser, $longUser);
                if ($u1 == null) {
                    $u1 = $distance;
                }
                if ($distance <= $u1) {
                    $u1 = $distance;
                    $id1= $i+1;
                    $calle = Contenedores::obtenerDireccion($contenedor->properties->tipovia, $contenedor->properties->calleempre, $contenedor->properties->numportal);
                    $c1 = new Contedor($id1, self::ORGANICO, $calle, $latlon['lat'], $latlon['lon']);
                }
            }
        }
        //blucle tipo 2
        for ($i = 0; $i < count($array); $i++) {
            $contenedor = $array[$i];
            $idTipo = contenedores::obtenerTipo($contenedor->properties->tipo);
            if ($idTipo == self::CARTON) {
                $lat = $contenedor->geometry->coordinates[1];
                $long = $contenedor->geometry->coordinates[0];
                $latlon = Calculos::obtenerCalculos()->coordenadas($lat, $long, 30);
                $distance = Calculos::obtenerCalculos()->getDistance($latlon['lat'], $latlon['lon'], $latUser, $longUser);
                if ($u2 == null) {
                    $u2 = $distance;
                }
                if ($distance <= $u2) {
                    $u2 = $distance;
                    $id2=$i+2;
                    $calle = Contenedores::obtenerDireccion($contenedor->properties->tipovia, $contenedor->properties->calleempre, $contenedor->properties->numportal);
                    $c2 = new Contedor($id2, self::ORGANICO, $calle, $latlon['lat'], $latlon['lon']);
                }
            }
        }
        //blucle tipo 3
        for ($i = 0; $i < count($array); $i++) {
            $contenedor = $array[$i];
            $idTipo = contenedores::obtenerTipo($contenedor->properties->tipo);
            if ($idTipo == self::PLASTICO) {
                $lat = $contenedor->geometry->coordinates[1];
                $long = $contenedor->geometry->coordinates[0];
                $latlon = Calculos::obtenerCalculos()->coordenadas($lat, $long, 30);
                $distance = Calculos::obtenerCalculos()->getDistance($latlon['lat'], $latlon['lon'], $latUser, $longUser);
                if ($u3 == null) {
                    $u3 = $distance;
                }
                if ($distance <= $u3) {
                    $u3 = $distance;
                    $id3=$i+1;
                    $calle = Contenedores::obtenerDireccion($contenedor->properties->tipovia, $contenedor->properties->calleempre, $contenedor->properties->numportal);
                    $c3 = new Contedor($id3, self::ORGANICO, $calle, $latlon['lat'], $latlon['lon']);
                }
            }
        }
        //blucle tipo 4
        for ($i = 0; $i < count($array); $i++) {
            $contenedor = $array[$i];
            $idTipo = contenedores::obtenerTipo($contenedor->properties->tipo);
            if ($idTipo == self::VIDRIO) {
                $lat = $contenedor->geometry->coordinates[1];
                $long = $contenedor->geometry->coordinates[0];
                $latlon = Calculos::obtenerCalculos()->coordenadas($lat, $long, 30);
                $distance = Calculos::obtenerCalculos()->getDistance($latlon['lat'], $latlon['lon'], $latUser, $longUser);
                if ($u4 == null) {
                    $u4 = $distance;
                }
                if ($distance <= $u4) {
                    $u4 = $distance;
                    $id4=$i+4;
                    $calle = Contenedores::obtenerDireccion($contenedor->properties->tipovia, $contenedor->properties->calleempre, $contenedor->properties->numportal);
                    $c4 = new Contedor($id4, self::ORGANICO, $calle, $latlon['lat'], $latlon['lon']);
                }
            }
        }


        array_push($contenedores, $c1);
        array_push($contenedores, $c2);
        array_push($contenedores, $c3);
        array_push($contenedores, $c4);
        return $contenedores;
    }
    function error($tipoerror,$numero,$texto){
        $ddf = fopen('error'.$tipoerror.'.log','a');
        fwrite($ddf,"Error $numero: $texto\r\n");
        fclose($ddf);
    }
    function obtenerInformacionaceite($array, $latUser, $longUser)
    {
        $u5 = -1;
        $p5 = null;
        for ($i = 0; $i < count($array); $i++) {
            $aceite = $array[$i];
            $lat = $aceite->geometry->coordinates[1];
            $long = $aceite->geometry->coordinates[0];
            $latlon = Calculos::obtenerCalculos()->coordenadas($lat, $long, 30);
            $distance = Calculos::obtenerCalculos()->getDistance($latlon['lat'], $latlon['lon'], $latUser, $longUser);
            //self::error(5,0,$distance);
            //self::error(5,9,$u5);
            if ($u5 < 0) {
                $u5 = $distance;
                //self::error(5,1,"distancia $distance asignada a u5");
            }
            $direccion = $aceite->properties->direccion;
            //$id = $aceite->properties->codigo;
            $id = $i + 1;
            if ($distance <= $u5) {
                $p5 = new Contedor($id, self::ACEITE, $direccion, $latlon['lat'], $latlon['lon']);
                //self::error(5,2,"objeto $id, distancia $u5/$distance asignado con [".$latlon['lat']." - ".$latlon['lon']."]");
            }
        }
        return $p5;
    }

    function obtenerInformacionPapeleras($array, $latUser, $longUser)
    {
        $u0 = -1;
        $p0 = null;
        foreach ($array as $papeleras) {
            $lat = $papeleras->geometry->coordinates[1];
            $long = $papeleras->geometry->coordinates[0];
            $latlon = Calculos::obtenerCalculos()->coordenadas($lat, $long, 30);
            $distance = Calculos::obtenerCalculos()->getDistance($latlon['lat'], $latlon['lon'], $latUser, $longUser);
            //self::error(0,0,$distance);
            //self::error(0,9,$u0);
            if ($u0 < 0) {
                $u0 = $distance;
                //self::error(0,1,"distancia $distance asignada a u0 - $u0");
            }
            $id =(int) $papeleras->properties->codigo;
            if ($distance <= $u0) {
                $u0=$distance;
                $p0 = new Contedor($id, self::PAPELERAS, "", $latlon['lat'], $latlon['lon']);
                //self::error(0,2,"objeto $id, distancia guardada $u0/ distancia nueva $distance asignado con [".$latlon['lat']." - ".$latlon['lon']."]");
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
            $lat = $pilas->geometry->coordinates[1];
            $long = $pilas->geometry->coordinates[0];
            $latlon = Calculos::obtenerCalculos()->coordenadas($lat, $long, 30);
            $distance = Calculos::obtenerCalculos()->getDistance($latlon['lat'], $latlon['lon'], $latUser, $longUser);
            if ($u6 == null) {
                $u6 = $distance;
            }
            $id = $i + 1;
            $direccion = $pilas->properties->direccion;
            if ($distance < $u6) {
                $u6=$distance;
                $p6 = new Contedor($id, self::PILAS, $direccion, $latlon['lat'], $latlon['lon']);
            }
        }

        return $p6;
    }
}