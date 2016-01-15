<?php

require_once 'datos/calculos.php';
require 'controladores/contenedores.php';
require 'controladores/aceite.php';
require 'controladores/pilas.php';
require 'controladores/calles.php';
require 'controladores/papeleras.php';
require 'controladores/cercanos.php';
require 'datos/contenedor.php';
require 'vistas/VistaJson.php';
require 'vistas/VistaXML.php';
require 'utilidades/ExcepcionApi.php';

const ESTADO_URL_INCORRECTA = 2;
const ESTADO_EXISTENCIA_RECURSO = 3;
const ESTADO_METODO_NO_PERMITIDO = 4;

$formato = isset($_GET['formato']) ? $_GET['formato'] : 'json';

switch ($formato) {
    case 'xml':
        $vista = new VistaXML();
        break;
    case 'json':
    default:
        $vista = new VistaJson();
}

// manejo de excepciones
set_exception_handler(function ($exception) use ($vista) {
    $cuerpo = array(
        "estado" => $exception->estado,
        "mensaje" => $exception->getMessage()
    );
    if ($exception->getCode()) {
        $vista->estado = $exception->getCode();
    } else {
        $vista->estado = 500;
    }

    $vista->imprimir($cuerpo);
}
);

// Extraer segmento de la url
if (isset($_GET['PATH_INFO'])) {
    $peticion = explode('/', $_GET['PATH_INFO']);
} else
    throw new ExcepcionApi(ESTADO_URL_INCORRECTA, utf8_encode("No se reconoce la petición"));
// Obtener recurso
$recurso = array_shift($peticion);
$recursos_existentes = array('aceite', 'pilas', 'papeleras', 'contenedores','cercanos');

// Comprobar si existe el recurso
if (!in_array($recurso, $recursos_existentes)) {
    throw new ExcepcionApi(ESTADO_EXISTENCIA_RECURSO, utf8_encode("El recurso al que intentas acceder no existe"));
} else {
        // obtenemos el parametro latitud
        if (isset($_GET['lat']))
            $lat = $_GET['lat'];
        else
            throw new ExcepcionApi(ESTADO_URL_INCORRECTA, utf8_encode("falta el parametro lat"));
// obtenemos el parametro longitud
        if (isset($_GET['long']))
            $long = $_GET['long'];
        else
            throw new ExcepcionApi(ESTADO_URL_INCORRECTA, utf8_encode("falta el parametro long"));
// obtenemos la distancia del usuario si no por defecto 200m
        if (isset($_GET['dist']))
            $dist = $_GET['dist'];
        else
            $dist = 200;

        array_push($peticion, $lat);
        array_push($peticion, $long);
        array_push($peticion, $dist);
        if ($recurso == $recursos_existentes[count($recursos_existentes) - 2]) {
            if (isset($_GET['tipo'])) {
                $tipo = $_GET['tipo'];
                array_push($peticion, $tipo);
            } else
                throw new ExcepcionApi(ESTADO_URL_INCORRECTA, utf8_encode("falta el parametro tipo"));
        }
}

$metodo = strtolower($_SERVER['REQUEST_METHOD']);

switch ($metodo) {
    case 'get':
        if (method_exists($recurso, $metodo)) {
            $respuesta = call_user_func(array($recurso, $metodo), $peticion);
            $vista->imprimir($respuesta);
            break;
        }
    default:
        // Método no aceptado
        $vista->estado = 405;
        $cuerpo = [
            "estado" => ESTADO_METODO_NO_PERMITIDO,
            "mensaje" => utf8_encode("Metodo no permitido")
        ];
        $vista->imprimir($cuerpo);
}