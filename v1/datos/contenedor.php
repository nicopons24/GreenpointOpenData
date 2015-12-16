<?php

class Contedor {

    var $id;
    var $tipo;
    var $direccion;
    var $lat;
    var $log;

    /**
     * Contedor constructor.
     * @param $id
     * @param $tipo
     * @param $direccion
     * @param $centro
     * @param $lat
     * @param $log
     */
    public function __construct($id, $tipo, $direccion, $lat, $log)
    {
        $this->id = $id;
        $this->tipo = $tipo;
        $this->direccion = $direccion;
        $this->lat = $lat;
        $this->log = $log;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param mixed $direccion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }

    /**
     * @return mixed
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param mixed $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * @return mixed
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * @param mixed $log
     */
    public function setLog($log)
    {
        $this->log = $log;
    }

}