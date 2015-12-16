<?php

class calles {

    const URL = "http://mapas.valencia.es/lanzadera/opendata/vias/csv";
    const FINAL_FILA = "\n";
    const SEPARACION = ";";

    const CODTIPOVIA = 0;
    const CODVIA = 1;
    const NOMOFICIAL = 3;

    const NOMBRE_TABLA = "calles";
    const IDCALLE = "id_calle";
    const NOMCALLE = "nombre";

    public static function get($parametros)
    {
        $listaCalles = self::obtenerCalles();
        return self::insertaDB($listaCalles);
    }

    private function obtenerCalles() {
        $csv = file_get_contents(self::URL);
        $filas = explode(self::FINAL_FILA, $csv);
        $calles = array();
        for ($i = 1; $i < count($filas); $i++) {
            $calle = explode(self::SEPARACION, $filas[$i]);

            $codtipo = $calle[self::CODTIPOVIA];
            $idCalle = $calle[self::CODVIA];
            $nombre = self::compruebaTipo($codtipo) . " " . strtolower($calle[self::NOMOFICIAL]);
            $c = [
                "idCalle"=>$idCalle,
                "nombre"=>$nombre
            ];
            array_push($calles, $c);
        }
        return $calles;
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

    private function insertaDB($listacalles) {
        $comando = "INSERT INTO " . self::NOMBRE_TABLA . " (" .
            self::IDCALLE . ", " .
            self::NOMCALLE . ")" .
            " VALUES (?,?)";
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sentencia = $pdo->prepare($comando);
        ini_set('max_execution_time', 300);
        for ($i = 0; $i < count($listacalles) -1; $i++) {
            $calle = $listacalles[$i];

            $id = $calle["idCalle"];
            $nombre = $calle["nombre"];

            $sentencia->bindParam(1, $id, PDO::PARAM_INT);
            $sentencia->bindParam(2, $nombre);

            $resultado = $sentencia->execute();
        }
        return $resultado;
    }
}