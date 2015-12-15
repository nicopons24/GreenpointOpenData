<?php

class calles {

    const URL = "http://mapas.valencia.es/lanzadera/opendata/vias/csv";
    const FINAL_FILA = "\n";
    const SEPARACION = ";";

    const CODTIPOVIA = "0";
    const CODVIA = "1";
    const NOMOFICIAL = "3";

    public static function get($parametros)
    {
        $calles = self::obtenerCalles();
        $listacalles = self::tipoCalles($calles);
    }

    private function obtenerCalles() {
        $csv = file_get_contents(self::URL);
        $filas = explode(self::FINAL_FILA, $csv);
        $calles = array();
        for ($i = 1; $i < count($filas); $i++) {
            $calle = explode(self::SEPARACION, $filas[$i]);
            array_push($calles, $calle);
        }
        return $calles;
    }

    private function tipoCalles($calles) {
        for ($i = 1; $i < count($calles); $i++) {
            $calle = $calles[$i];

            $codtipo = $calle[self::CODTIPOVIA];
            $idCalle = $calle[self::CODVIA];
            $nombre = self::compruebaTipo($codtipo) . " " . $calle[self::NOMOFICIAL];
            $c = new Calle($idCalle, $nombre);
            print_r($c);
        }
    }

    private function compruebaTipo($codigo){
        $cod = array('C','PL','AV','C.V.','ENTRD','CTRA','CMNO', 'SENDA', 'PJE',
            'LUG', 'PSO', 'C.N.', 'TRV', 'BARRO', 'CJON', 'GRUP', 'G.V.', 'C.H.');
        $tipo = array('Calle', 'Plaza', 'Avenida', 'Camino viejo', 'Entrada', 'Carretera',
            'Camino', 'Senda', 'Pasaje', '', 'Paseo', 'Camino nuevo', 'Travesia',
            'Barrio', 'Callejón', 'Urbanización', 'Gran vía', 'Camino hondo');
        for ($i = 0; $i < count($cod); $i++) {
            if ($cod[$i] == $codigo) {
                return $tipo[$i];
            }
        }
    }
}

class Calle {

    var $idCalle;
    var $nombre;

    /**
     * Calle constructor.
     * @param $idCalle
     * @param $nombre
     */
    public function __construct($idCalle, $nombre)
    {
        $this->idCalle = $idCalle;
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getIdCalle()
    {
        return $this->idCalle;
    }

    /**
     * @param mixed $idCalle
     */
    public function setIdCalle($idCalle)
    {
        $this->idCalle = $idCalle;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function __toString()
    {
        return "Id calle: " . $this->idCalle . "\n" .
                "Nombre: " . $this->nombre . "\n";
    }
}