<?php

/*

Alumna: BOGADO, Candela

*/
require_once("Producto.php");
require_once("Venta.php");

if (
    isset($_POST["email"]) &&
    isset($_POST["nombre"]) &&
    isset($_POST["tipo"]) &&
    isset($_POST["marca"]) &&
    isset($_POST["cantidad"]) &&
    isset($_FILES["imagen"])
) {
    //Dato Usuario
    $email = $_POST["email"];

    //Data Producto
    $nombre = strtolower($_POST["nombre"]);
    $tipo = strtolower($_POST["tipo"]);
    $marca = strtolower($_POST["marca"]);
    $cantidad = intval($_POST["cantidad"]);

    //Data del archivo subido
    $nombre_archivo = $_FILES['imagen']['name'];
    $tipo_archivo = $_FILES['imagen']['type'];
    $tamano_archivo = $_FILES['imagen']['size'];

    //Obtener listado de productos desde archivo
    $lista = Producto::ObtenerListaDeProductos();
    //Crear nuevo objeto con los parámetros recibidos
    $productoVendido = new Producto($nombre, 0, $tipo, $marca, $cantidad);

    //Chequea si el producto ya existe en la lista
    $indiceP = Producto::VerificarSiExiste($lista, $productoVendido);

    if ($indiceP != -1) {
        if (Producto::VerificarStock($lista[$indiceP], $cantidad)) {

            $precio = $lista[$indiceP]->CalcularPrecio($cantidad);
            //Nueva venta
            $fechaVenta = new DateTime();
            $fechaVenta = $fechaVenta->format("Y-m-d");
            $venta = new Venta($fechaVenta, $precio, $email, $cantidad, $nombre, $tipo, $marca);
            $listaVentas = Venta::ObtenerListaDeVentas();
            array_push($listaVentas, $venta);
            if (Venta::GuardarVentasJSON($listaVentas)) {
                echo "Venta guardada con éxito.\n\n";
            } else {
                echo "La venta no fue guardada.\n\n";
            }

            //Descontar Stock
            if (Producto::RestarStock($productoVendido, $lista, $indiceP)) {
                echo "El stock fue descontado con éxito.\n\n";

                if (Producto::GuardarListaDeProductosJSON($lista)) {
                    echo "Lista de productos guardada.\n\n";
                } else {
                    echo "Error al guardar la lista de productos.\n\n";
                }
            } else {
                echo "Error al descontar stock.\n\n";
            }
            //Guardar Imagen
            if ((strpos($tipo_archivo, "png") || strpos($tipo_archivo, "jpeg")) && ($tamano_archivo < 500000)) {

                $extension = substr($tipo_archivo, strpos($tipo_archivo, '/') + 1);
                $emailFormateado = explode("@", $email);

                $cargaFoto = Venta::GuardarFoto($_FILES['imagen'], $nombre, $tipo, $marca, $emailFormateado[0], $fechaVenta, $extension);
                if ($cargaFoto) {
                    echo "La imagen fue guardada exitosamente.\n\n";
                } else {
                    echo "La foto no pudo ser guardada.\n\n";
                }
            } else {
                echo "La extensión o el tamaño de los archivos pueden no ser los correctos.\nSe permiten archivos .png o .jpg.\nSe permiten archivos de 100 Kb máximo.\n\n";
            }
        } else {
            echo "No hay stock suficiente del producto elegido.\n\n";
        }
    } else {
        echo "No existe el producto elegido.\n\n";
    }
} else {
    echo "Parametros incorrectos\n\n";
}
