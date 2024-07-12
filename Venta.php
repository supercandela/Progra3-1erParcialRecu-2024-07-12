<?php

class Venta
{
    private $_id;
    private $_pedido;
    private $_fecha;
    private $_precio;
    private $_usuario;
    private $_cantidad;
    private $_nombre;
    private $_tipo;
    private $_marca;
    private $_borrada;


    public function __construct($fecha, $precio, $usuario, $cantidad, $nombre, $tipo, $marca, $id = 0, $pedido = 0, $borrada = false)
    {
        $this->_fecha = $fecha;
        $this->_precio = $precio;
        $this->_usuario = $usuario;
        $this->_cantidad = $cantidad;
        $this->_nombre = $nombre;
        $this->_tipo = $tipo;
        $this->_marca = $marca;
        if ($id == 0) {
            $this->_id = rand(1, 100);
        } else {
            $this->_id = $id;
        }
        if ($pedido == 0) {
            $this->_pedido = rand(100000, 999999);
        } else {
            $this->_pedido = $pedido;
        }
        $this->_borrada = $borrada;
    }

    /**
     * Obtener array de ventas desde ventas.json
     * Retorna un array de ventas
     */
    public static function ObtenerListaDeVentas()
    {
        $array = array();
        // Verificar si el archivo existe
        if (file_exists("./ventas.json")) {
            $valores = file_get_contents("./ventas.json");
            $data = json_decode($valores, true);
            if ($data != null) {
                foreach ($data as $elemento) {
                    $venta = new Venta($elemento["fecha"], $elemento["precio"], $elemento["usuario"], $elemento["cantidad"], $elemento["nombre"], $elemento["tipo"], $elemento["marca"], $elemento["id"], $elemento["pedido"], $elemento["borrada"]);
                    array_push($array, $venta);
                }
            }
        }
        return $array;
    }

    /**
     * Recibe un array de ventas y sobreescribe el archivo ventas.json con el nuevo contenido
     * Retorna false si no pudo completar el guardado completo
     * Retorna true si guardó todos los elementos en el archivo
     */
    public static function GuardarVentasJSON($ventas)
    {
        if (count($ventas) > 0) {
            $ventasValoresPublicos = array();
            foreach ($ventas as $venta) {
                $nuevaV = Venta::convertirAtributosAPublico($venta);
                array_push($ventasValoresPublicos, $nuevaV);
            }
            $json = json_encode($ventasValoresPublicos, JSON_PRETTY_PRINT);
            // Escribir el JSON en el archivo
            return file_put_contents("./ventas.json", $json);
        }
        return false;
    }

    public static function convertirAtributosAPublico($elemento)
    {
        $ventaPublica = new stdClass();
        $ventaPublica->fecha = $elemento->_fecha;
        $ventaPublica->precio = $elemento->_precio;
        $ventaPublica->usuario = $elemento->_usuario;
        $ventaPublica->cantidad = $elemento->_cantidad;
        $ventaPublica->nombre = $elemento->_nombre;
        $ventaPublica->tipo = $elemento->_tipo;
        $ventaPublica->marca = $elemento->_marca;
        $ventaPublica->id = $elemento->_id;
        $ventaPublica->pedido = $elemento->_pedido;
        $ventaPublica->borrada = $elemento->_borrada;
        return $ventaPublica;
    }

    /**
     * sube la imagen al servidor en la carpeta /ImagenesDeLaVenta/2024.
     */
    public static function GuardarFoto($foto, $nombre, $tipo, $marca, $email, $fecha, $tipo_archivo)
    {
        //Carpeta donde voy a guardar los archivos
        $carpeta_archivos = 'ImagenesDeVenta/2024/';
        // Ruta final, carpeta + nombre del archivo
        $destino = $carpeta_archivos . $nombre . "-" . $tipo . "-" . $marca . "-" . $email . "-" . $fecha . "." . $tipo_archivo;

        if (move_uploaded_file($foto['tmp_name'], $destino)) {
            return true;
        } else {
            return false;
        }
    }

    public static function FiltrarListaPorFechaExacta($lista, Datetime $fecha)
    {
        $listaConFiltro = array();
        if (count($lista) > 0) {
            for ($i = 0; $i < count($lista); $i++) {
                $fechaVenta = new DateTime($lista[$i]->_fecha);
                if (!$lista[$i]->_borrada && $fechaVenta->format("Y-m-d") == $fecha->format('Y-m-d')) {
                    array_push($listaConFiltro, $lista[$i]);
                }
            }
        }
        return $listaConFiltro;
    }

    public static function SumarCantidades($lista)
    {
        $cantidadTotal = 0;
        if (count($lista) > 0) {
            for ($i = 0; $i < count($lista); $i++) {
                if (!$lista[$i]->_borrada) {
                    $cantidadTotal += $lista[$i]->_cantidad;
                }
            }
        }
        return $cantidadTotal;
    }

    public static function FiltrarListaPorUsuario($lista, $usuario)
    {
        $listaConFiltro = array();
        if (count($lista) > 0) {
            for ($i = 0; $i < count($lista); $i++) {
                if (!$lista[$i]->_borrada && $lista[$i]->_usuario == $usuario) {
                    array_push($listaConFiltro, $lista[$i]);
                }
            }
        }
        return $listaConFiltro;
    }

    public function MostrarVenta()
    {
        if (!$this->_borrada) {
            echo "ID Venta: " . $this->_id . "\n";
            echo "ID Pedido: " . $this->_pedido . "\n";
            echo "Usuario: " . $this->_usuario . "\n";
            echo "Fecha: " . $this->_fecha . "\n";
            echo "Cantidad: " . $this->_cantidad . "\n";
            echo "Nombre: " . $this->_nombre . "\n";
            echo "Tipo: " . $this->_tipo . "\n";
            echo "Marca: " . $this->_marca . "\n";
            echo "Precio: $" . $this->_precio . "\n\n";
        }
    }

    public static function FiltrarListaPorTipo($lista, $tipo)
    {
        $listaConFiltro = array();
        if (count($lista) > 0) {
            for ($i = 0; $i < count($lista); $i++) {
                if (!$lista[$i]->_borrada && $lista[$i]->_tipo == $tipo) {
                    array_push($listaConFiltro, $lista[$i]);
                }
            }
        }
        return $listaConFiltro;
    }

    public static function FiltrarListaPorRangoDePrecios($lista, $precioMin, $precioMax)
    {
        $listaConFiltro = array();
        if (count($lista) > 0) {
            for ($i = 0; $i < count($lista); $i++) {
                if (
                    !$lista[$i]->_borrada &&
                    $lista[$i]->_precio >= $precioMin &&
                    $lista[$i]->_precio <= $precioMax
                ) {
                    array_push($listaConFiltro, $lista[$i]);
                }
            }
        }
        return $listaConFiltro;
    }

    public static function SumarGanancias($lista)
    {
        $cantidadTotal = 0;
        if (count($lista) > 0) {
            for ($i = 0; $i < count($lista); $i++) {
                if (!$lista[$i]->_borrada) {
                    $cantidadTotal += $lista[$i]->_precio;
                }
            }
        }
        return $cantidadTotal;
    }

    /**
     * Verifica si el numero de pedido existe en el listado
     * Retorna el índice si coincide
     * Retorna -1 si no coincide.
     */
    public static function FiltrarPorPedido($lista, $pedido)
    {
        if (count($lista) === []) {
            echo "Lista vacía. \n\n";
            return -1;
        }
        $indiceARetornar = -1;

        for ($i = 0; $i < count($lista); $i++) {
            if (!$lista[$i]->_borrada && $lista[$i]->_pedido == $pedido) {
                $indiceARetornar = $i;
                break;
            }
        }
        return $indiceARetornar;
    }

    public static function ModificarVenta($venta, $usuario, $nombre, $tipo, $marca, $cantidad)
    {
        $venta->_usuario = $usuario;
        $venta->_nombre = $nombre;
        $venta->_tipo = $tipo;
        $venta->_marca = $marca;
        $venta->_cantidad = $cantidad;
    }

    public static function ObtenerProductoMasVendido()
    {
        // Inicializo array para contar las ventas por producto
        $contadorVentas = [];

        $lista = Venta::ObtenerListaDeVentas();

        // Contar la cantidad total de cada producto vendido
        foreach ($lista as $venta) {
            if (!$venta->_borrada) {
                $nombreProducto = $venta->_nombre;
                $cantidadVendida = $venta->_cantidad;
                //Seteo el valor del contador en 0 para la primera ocurrencia
                if (!isset($contadorVentas[$nombreProducto])) {
                    $contadorVentas[$nombreProducto] = 0;
                }
                //Actualizo el valor de contador ventas
                $contadorVentas[$nombreProducto] += $cantidadVendida;
            }
        }

        // Encontrar el producto más vendido
        $productoMasVendido = '';
        $maxCantidadVendida = 0;

        foreach ($contadorVentas as $producto => $cantidad) {
            if ($cantidad > $maxCantidadVendida) {
                $maxCantidadVendida = $cantidad;
                $productoMasVendido = $producto;
            }
        }

        return [$productoMasVendido, $maxCantidadVendida];
    }

    public static function EliminarVenta ($venta)
    {
        $venta->_borrada = true;
        Venta::MoverFoto($venta);
    }

    public static function MoverFoto ($venta)
    {
        $usuario = explode("@", $venta->_usuario);
        $usuario = $usuario[0];

        // Extensiones de archivo a buscar
        $extensiones = ['jpg', 'png', 'jpeg'];
        //Carpeta donde estan los archivos de origen
        $carpeta_archivos_origen = 'ImagenesDeVenta/2024/';
        // Ruta completa del archivo de origen
        $ruta_origen_sin_extension = $carpeta_archivos_origen . $venta->_nombre . "-" . $venta->_tipo . "-" . $venta->_marca . "-" . $usuario . "-" . $venta->_fecha;

        // Ruta de destino
        $carpeta_archivos_destino = 'ImagenesBackUpVentas/2024/';
        $ruta_destino_sin_extension = $carpeta_archivos_destino . $venta->_nombre . "-" . $venta->_tipo . "-" . $venta->_marca . "-" . $usuario . "-" . $venta->_fecha;

        $archivo_encontrado = false;

        foreach ($extensiones as $extension) {
            $ruta_origen = $ruta_origen_sin_extension . "." . $extension;
            // Verificar si el archivo existe
            if (file_exists($ruta_origen)) {
                $ruta_destino = $ruta_destino_sin_extension . "." . $extension;
                
                // Mover el archivo
                if (rename($ruta_origen, $ruta_destino)) {
                    echo "El archivo $ruta_origen se ha movido exitosamente a $ruta_destino.";
                    $archivo_encontrado = true;
                    break;
                } else {
                    echo "Hubo un error al mover el archivo $ruta_origen.";
                }
            }
        }
        
        if (!$archivo_encontrado) {
            echo "No se encontraron archivos con las extensiones especificadas.";
        }
    }
}
