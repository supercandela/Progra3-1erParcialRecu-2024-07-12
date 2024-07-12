<?php

require_once "./Conjunto.php";

class Producto
{
    private $_id;
    private $_nombre;
    private $_precio;
    private $_tipo;
    private $_marca;
    private $_stock;

    /**
     * Constructor de clase.
     * Crea un ID autoincremental (emulado, random de 1 a 1000), si no posee uno.
     */
    public function __construct($nombre, $precio, $tipo, $marca, $stock, $id = 0)
    {
        $this->_nombre = $nombre;
        $this->_precio = $precio;
        $this->_tipo = $tipo;
        $this->_marca = $marca;
        $this->_stock = $stock;
        if ($id == 0) {
            $this->_id = rand(1, 1000);
        } else {
            $this->_id = $id;
        }
    }

    /**
     * Obtener array de productos desde archivo json
     * Retorna un array de objetos
     */
    public static function ObtenerListaDeProductos()
    {
        $array = array();
        // Verificar si el archivo existe
        if (file_exists("./tienda.json")) {
            $valores = file_get_contents("./tienda.json");
            $data = json_decode($valores, true);
            if ($data != null) {
                foreach ($data as $elemento) {
                    if ($elemento["tipo"] == "conjunto") {
                        $item = new Conjunto($elemento["tipo"], $elemento["smartphone_nombre"], $elemento["smartphone_marca"], $elemento["tablet_nombre"], $elemento["tablet_marca"], $elemento["precio_total"], $elemento["id"]);

                    } else {
                        $item = new Producto($elemento["nombre"], $elemento["precio"], $elemento["tipo"], $elemento["marca"], $elemento["stock"], $elemento["id"]);
                    }
                    array_push($array, $item);
                }
            }
        }
        return $array;
    }

    /**
     * Verifica si producto es igual en nombre y tipo a algún producto del listado
     * Retorna el índice del producto si el producto coincide
     * Retorna -1 si no coincide.
     */
    public static function VerificarSiExiste($arrayDeProductos, $producto)
    {
        if (count($arrayDeProductos) === []) {
            echo "Lista de productos vacía. \n\n";
            return -1;
        }
        $productoRegistrado = -1;

        for ($i = 0; $i < count($arrayDeProductos); $i++) {
            if ($arrayDeProductos[$i]->_tipo != "conjunto") {
                if ($arrayDeProductos[$i]->_nombre == $producto->_nombre && $arrayDeProductos[$i]->_tipo == $producto->_tipo) {
                    $productoRegistrado = $i;
                    break;
                }
            }
        }
        return $productoRegistrado;
    }

    /**
     * Recibe un array y sobreescribe el archivo json con el nuevo contenido
     * Retorna false si no pudo completar el guardado completo
     * Retorna true si guardó todos los elementos en el archivo
     */
    public static function GuardarListaDeProductosJSON($lista)
    {
        if (count($lista) > 0) {
            $valoresPublicos = array();
            foreach ($lista as $item) {
                if ($item->_tipo == "conjunto") {
                    $itemPublico = Conjunto::convertirAtributosAPublico($item);
                } else {
                    $itemPublico = Producto::convertirAtributosAPublico($item);
                }
                array_push($valoresPublicos, $itemPublico);
            }
            $json = json_encode($valoresPublicos, JSON_PRETTY_PRINT);
            // Escribir el JSON en el archivo
            return file_put_contents("./tienda.json", $json);
        }
        return false;
    }

    /**
     * Convierte los atributos privados a públicos para su guardado en el archivo json
     */
    private static function convertirAtributosAPublico($elemento)
    {
        $productoPublico = new stdClass();
        $productoPublico->nombre = $elemento->_nombre;
        $productoPublico->precio = $elemento->_precio;
        $productoPublico->tipo = $elemento->_tipo;
        $productoPublico->marca = $elemento->_marca;
        $productoPublico->stock = $elemento->_stock;
        $productoPublico->id = $elemento->_id;
        return $productoPublico;
    }



    /**
     * Actualiza el precio del elemento
     */
    public static function ActualizarPrecio($producto, $arrayDeProductos, $i)
    {
        if ($arrayDeProductos[$i]->_tipo == "conjunto") {
            echo "No es un producto válido. \n\n";
            return false;
        }
        if (!(isset($producto->_nombre) && isset($producto->_tipo)
            && isset($producto->_precio)) && count($arrayDeProductos) >= $i) {
            echo "Datos erróneos. \n\n";
            return false;
        }
        $productoModificado = false;
        if (
            $arrayDeProductos[$i]->_nombre == $producto->_nombre &&
            $arrayDeProductos[$i]->_tipo == $producto->_tipo
        ) {
            $arrayDeProductos[$i]->_precio = $producto->_precio;
            $productoModificado = true;
        }
        return $productoModificado;
    }

    /**
     * Suma el stock al producto existente
     */
    public static function SumarStock($producto, $arrayDeProductos, $i)
    {
        if ($arrayDeProductos[$i]->_tipo == "conjunto") {
            echo "No es un producto válido. \n\n";
            return false;
        }
        if (!(isset($producto->_nombre) && isset($producto->_tipo)
            && isset($producto->_stock)) && count($arrayDeProductos) >= $i) {
            echo "Datos erróneos. \n\n";
            return false;
        }
        $productoModificado = false;
        if (
            $arrayDeProductos[$i]->_nombre == $producto->_nombre &&
            $arrayDeProductos[$i]->_tipo == $producto->_tipo
        ) {
            $arrayDeProductos[$i]->_stock = $arrayDeProductos[$i]->_stock + $producto->_stock;
            $productoModificado = true;
        }
        return $productoModificado;
    }

    /**
     * Guarda la imagen en el servidor en la carpeta /ImagenesDeProductos/2024
     */
    public static function GuardarFoto($foto, $nombre, $tipo, $tipo_archivo)
    {
        //Carpeta donde voy a guardar los archivos
        $carpeta_archivos = 'ImagenesDeProductos/2024/';
        // Ruta final, carpeta + nombre del archivo
        $destino = $carpeta_archivos . $nombre . "-" . $tipo . "." . $tipo_archivo;

        if (move_uploaded_file($foto['tmp_name'], $destino)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Verifica si el nombre del producto existe en el listado
     * Retorna el índice del producto si el producto coincide
     * Retorna -1 si no coincide.
     */
    public static function VerificarNombre($arrayDeProductos, $producto)
    {
        if (count($arrayDeProductos) === []) {
            echo "Lista de productos vacía. \n\n";
            return -1;
        }
        $productoRegistrado = -1;

        for ($i = 0; $i < count($arrayDeProductos); $i++) {
            if ($arrayDeProductos[$i]->_tipo != "conjunto") {
                if ($arrayDeProductos[$i]->_nombre == $producto->_nombre) {
                    $productoRegistrado = $i;
                    break;
                }
            }
        }
        return $productoRegistrado;
    }

    /**
     * Verifica si el tipo existe en el listado
     * Retorna el índice del producto si el producto coincide
     * Retorna -1 si no coincide.
     */
    public static function VerificarTipo($arrayDeProductos, $producto)
    {
        if (count($arrayDeProductos) === []) {
            echo "Lista de productos vacía. \n\n";
            return -1;
        }
        $productoRegistrado = -1;

        for ($i = 0; $i < count($arrayDeProductos); $i++) {
            if ($arrayDeProductos[$i]->_tipo != "conjunto" && $arrayDeProductos[$i]->_tipo == $producto->_tipo) {
                $productoRegistrado = $i;
                break;
            }
        }
        return $productoRegistrado;
    }

    /**
     * Verifica si el stock alcanza
     * Retorna true si alcanza
     * Retorna false si no alcanza
     */
    public static function VerificarStock($producto, $stock)
    {
        return $producto->_stock >= $stock;
    }

    /**
     * Calcular precio del producto
     */
    public function CalcularPrecio($stock)
    {
        return $this->_precio * $stock;
    }

    /**
     * Resta el stock al producto existente
     */
    public static function RestarStock($producto, $arrayDeProductos, $i)
    {
        if ($arrayDeProductos[$i]->_tipo == "conjunto") {
            echo "No es un producto válido. \n\n";
            return false;
        }
        if (!(isset($producto->_nombre) && isset($producto->_tipo)
            && isset($producto->_stock)) && count($arrayDeProductos) >= $i) {
            echo "Datos erróneos. \n\n";
            return false;
        }
        $productoModificado = false;
        if (
            $arrayDeProductos[$i]->_nombre == $producto->_nombre &&
            $arrayDeProductos[$i]->_tipo == $producto->_tipo
        ) {
            $arrayDeProductos[$i]->_stock = $arrayDeProductos[$i]->_stock - $producto->_stock;
            $productoModificado = true;
        }
        return $productoModificado;
    }

    /**
     * Calcula el precio del conjunto. Recibe los dos productos a sumar
     */
    public static function CalcularPrecioConjunto ($smartphone, $tablet)
    {
        return $smartphone->_precio + $tablet->_precio;
    }
}
