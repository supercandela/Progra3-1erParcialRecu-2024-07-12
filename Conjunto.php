<?php

class Conjunto
{
    public $_id;
    public $_tipo;
    public $_smartphone_nombre;
    public $_smartphone_marca;
    public $_tablet_nombre;
    public $_tablet_marca;
    public $_precio_total;

    /**
     * Constructor de clase.
     * Crea un ID autoincremental (emulado, random de 1 a 1000), si no posee uno.
     */
    public function __construct($tipo, $smartphone_nombre, $smartphone_marca, $tablet_nombre, $tablet_marca, $precio_total, $id = 0)
    {
        $this->_tipo = $tipo;
        $this->_smartphone_nombre = $smartphone_nombre;
        $this->_smartphone_marca = $smartphone_marca;
        $this->_tablet_nombre = $tablet_nombre;
        $this->_tablet_marca = $tablet_marca;
        $this->_precio_total = $precio_total;
        if ($id == 0) {
            $this->_id = rand(1, 1000);
        } else {
            $this->_id = $id;
        }
    }

    /**
     * Convierte los atributos privados a públicos para su guardado en el archivo json
     */
    public static function convertirAtributosAPublico($elemento)
    {
        $conjuntoPublico = new stdClass();
        $conjuntoPublico->tipo = $elemento->_tipo;
        $conjuntoPublico->smartphone_nombre = $elemento->_smartphone_nombre;
        $conjuntoPublico->smartphone_marca = $elemento->_smartphone_marca;
        $conjuntoPublico->tablet_nombre = $elemento->_tablet_nombre;
        $conjuntoPublico->tablet_marca = $elemento->_tablet_marca;
        $conjuntoPublico->precio_total = $elemento->_precio_total;
        $conjuntoPublico->id = $elemento->_id;
        return $conjuntoPublico;
    }

    /**
     * Guarda la imagen en el servidor en la carpeta /ImagenesDeProductos/2024
     */
    public static function GuardarFoto($foto, $smartphone_nombre, $tablet_nombre, $tipo_archivo)
    {
        //Carpeta donde voy a guardar los archivos
        $carpeta_archivos = 'ImagenesDeConjuntos/2024/';
        // Ruta final, carpeta + nombre del archivo
        $destino = $carpeta_archivos . $smartphone_nombre . "-" . $tablet_nombre . "." . $tipo_archivo;

        if (move_uploaded_file($foto['tmp_name'], $destino)) {
            return true;
        } else {
            return false;
        }
    }
}