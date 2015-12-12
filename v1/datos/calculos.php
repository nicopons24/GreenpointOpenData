<?php

class Calculos
{
    private static $instance = null;

    final private function __construct()
    {

    }

    public static function obtenerCalculos()
    {
        if (self::$instance == null) {
            self::$instance = new Calculos();
        }

        return self::$instance;
    }

    public function getJSONFromUrl($url) {
        $fuente = file_get_contents($url);
        $body = json_decode($fuente);

        return $body->features;
    }

    public function getDistance($latContenedor, $longCont1, $latUser, $longUser)
    {
        $earth_radius = 6371;

        $dLat = deg2rad($latUser - $latContenedor);
        $dLon = deg2rad($longUser - $longCont1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($latContenedor)) * cos(deg2rad($latUser)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;

        return $d * 1000;
    }
}