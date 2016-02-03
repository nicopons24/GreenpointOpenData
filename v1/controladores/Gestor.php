<?php

/**
 * Created by PhpStorm.
 * User: media
 * Date: 02/02/2016
 * Time: 21:34
 */
class Gestor
{
    const URL_contenedores = "C:/xampp/htdocs/GreenpointOpenData/v1/res_contenedor.json";
    const URL_aceite = "C:/xampp/htdocs/GreenpointOpenData/v1/res_aceite.json";
    const URL_pilas = "C:/xampp/htdocs/GreenpointOpenData/v1/res_pilas.json";
    const URL_papeleras = "C:/xampp/htdocs/GreenpointOpenData/v1/res_papeleras.json";

    const ACEITE = 5;
    const ORGANICO = 1;
    const CARTON = 2;
    const PLASTICO = 3;
    const VIDRIO = 4;
    const PAPELERAS = 0;
    const PILAS = 6;

    public static function get()
    {
        $contenedores = Calculos::obtenerCalculos()->getJSONFromUrlGestor(self::URL_contenedores);
        $cuerpo = self::obtenerInformacionContenedores($contenedores);
        cercanos::escribir("jsoncontenedores", $cuerpo);

        $contenedores = Calculos::obtenerCalculos()->getJSONFromUrlGestor(self::URL_aceite);
        $cuerpo = self::obtenerInformacionContenedoresAceite($contenedores);
        cercanos::escribir("jsonaceite", $cuerpo);

        $contenedores = Calculos::obtenerCalculos()->getJSONFromUrlGestor(self::URL_pilas);
        $cuerpo = self::obtenerInformacionContenedoresPilas($contenedores);
        cercanos::escribir("jsonpilas", $cuerpo);

        $contenedores = Calculos::obtenerCalculos()->getJSONFromUrlGestor(self::URL_papeleras);
        $cuerpo = self::obtenerInformacionContenedoresPapeleras($contenedores);
        cercanos::escribir("jsonpapeleras", $cuerpo);
        return[
            "contenedores"=>"Done, ya tienes tus contenedores guardaicos en fichericos :)"
        ];
    }
    ////////////////////////////////////////
    /////CONTENEDORES
    ////////////////////////////////////////
    private function obtenerInformacionContenedores($array)
    {
        $contenedores = array();
        Calculos::obtenerCalculos()->resetCoordenatesContenedores();
        for ($i = 0; $i < count($array); $i++) {
            $contenedor = $array[$i];
            $idTipo = self::obtenerTipo($contenedor->properties->tipo);
            $lat = $contenedor->geometry->coordinates[1];
            $long = $contenedor->geometry->coordinates[0];
            $latlon = Calculos::obtenerCalculos()->coordenadas($lat, $long, 30);
            if (Calculos::obtenerCalculos()->comparaCoordenates(array($lat, $long, $idTipo))) {
                $idContenedor = $i;
                $calle = self::obtenerDireccion($contenedor->properties->tipovia, $contenedor->properties->calleempre, $contenedor->properties->numportal);
                $c = new Contedor($idContenedor, $idTipo, $calle, $latlon['lat'], $latlon['lon']);
                array_push($contenedores, $c);
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
            if ($tipo == $tipos[$i]){
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
    //////////////////////////////////////////////////////////////////////////////
    /////ACEITE
    //////////////////////////////////////////////////////////////////////////////
    function obtenerInformacionContenedoresAceite($array)
    {
        $contenedores = array();
        for ($i = 0; $i < count($array); $i++) {
            $aceite = $array[$i];
            $lat = $aceite->geometry->coordinates[1];
            $long = $aceite->geometry->coordinates[0];
            $latlon = Calculos::obtenerCalculos()->coordenadas($lat, $long, 30);
            $id = $i + 1;
            $direccion = $aceite->properties->direccion;
            $c = new Contedor($id, self::ACEITE, $direccion, $latlon['lat'], $latlon['lon']);
            array_push($contenedores, $c);

        }
        return $contenedores;
    }
    /////////////////////////////////////////////////////////////////////////////
    /////PILAS
    /////////////////////////////////////////////////////////////////////////////
    function obtenerInformacionContenedoresPilas($array)
    {
        $contenedores = array();
        for ($i = 0; $i < count($array); $i++) {
            $pilas = $array[$i];
            $lat = $pilas->geometry->coordinates[1];
            $long = $pilas->geometry->coordinates[0];
            $latlon = Calculos::obtenerCalculos()->coordenadas($lat, $long, 30);
            $id = $i + 1;
            $direccion = $pilas->properties->direccion;
            $c = new Contedor($id, self::PILAS, $direccion, $latlon['lat'], $latlon['lon']);
            array_push($contenedores, $c);
        }
        return $contenedores;
    }
    ////////////////////////////////////////////////////////////////////////////////
    //////PAPELERAS
    ///////////////////////////////////////////////////////////////////////////////
    function obtenerInformacionContenedoresPapeleras($array)
    {
        $contenedores = array();
        foreach ($array as $papeleras) {
            $lat = $papeleras->geometry->coordinates[1];
            $long = $papeleras->geometry->coordinates[0];
            $latlon = Calculos::obtenerCalculos()->coordenadas($lat, $long, 30);
            $id = (int)$papeleras->properties->codigo;
            $p = new Contedor($id, self::PAPELERAS, "", $latlon['lat'], $latlon['lon']);
            array_push($contenedores, $p);
        }
        return $contenedores;
    }
}