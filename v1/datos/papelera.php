<?php

class Papelera
{

    var $id;
    var $tipo;
    var $lat;
    var $log;

    /**
     * Papelera constructor.
     * @param $id
     * @param $tipo
     * @param $lat
     * @param $log
     */
    public function __construct($id, $tipo, $lat, $log)
    {
        $this->id = $id;
        $this->tipo = $tipo;
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