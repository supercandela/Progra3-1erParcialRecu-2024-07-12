<?php

/*

Alumna: BOGADO, Candela

*/

require_once("Producto.php");

if (
    isset($_POST["nombre"]) &&
    isset($_POST["precio"]) &&
    isset($_POST["tipo"]) &&
    isset($_POST["marca"]) &&
    isset($_POST["stock"]) &&
    isset($_FILES["imagen"])
) {
    //Producto
    $nombre = strtolower($_POST["nombre"]);
    $precio = floatval($_POST["precio"]);
    $tipo = strtolower($_POST["tipo"]);
    $marca = strtolower($_POST["marca"]);
    $stock = intval($_POST["stock"]);
    //Data del archivo subido
    $nombre_archivo = $_FILES['imagen']['name'];
    $tipo_archivo = $_FILES['imagen']['type'];
    $tamano_archivo = $_FILES['imagen']['size'];

    //Obtener listado de productos desde archivo
    $lista = Producto::ObtenerListaDeProductos();
    //Crear nuevo objeto con los parámetros recibidos
    $producto = new Producto($nombre, $precio, $tipo, $marca, $stock);
    //Chequea si el producto ya existe en la lista
    $indiceP = Producto::VerificarSiExiste($lista, $producto);

    if ($indiceP != -1) {
        //Actualiza precio
        if (Producto::ActualizarPrecio($producto, $lista, $indiceP)) {
            echo "Precio actualizado con éxito.\n\n";
        } else {
            echo "Precio no actualizado.\n\n";
        }
        //Suma Stock
        if (Producto::SumarStock($producto, $lista, $indiceP)) {
            echo "Stock actualizado con éxito.\n\n";
        } else {
            echo "Stock no actualizado.\n\n";
        }
        //Actualizar listado
        if (Producto::GuardarListaDeProductosJSON($lista)) {
            echo "Lista actualizada con éxito.\n\n";
        } else {
            echo "La lista no fue guardada correctamente.\n\n";
        }
    } else {
        //Guarda el producto nuevo en la lista
        array_push($lista, $producto);
        if (Producto::GuardarListaDeProductosJSON($lista)) {
            echo "Producto registrado con éxito.\n\n";
        } else {
            echo "El producto no fue guardado correctamente.\n\n";
        }

        //Guardar Imagen
        if ((strpos($tipo_archivo, "png") || strpos($tipo_archivo, "jpeg")) && ($tamano_archivo < 500000)) {
            $extension = substr($tipo_archivo, strpos($tipo_archivo, '/') + 1);
            $cargaFoto = Producto::GuardarFoto($_FILES['imagen'], $nombre, $tipo, $extension);
            if ($cargaFoto) {
                echo "La imagen fue guardada exitosamente.\n\n";
            } else {
                echo "La foto no pudo ser guardada.\n\n";
            }
        } else {
            echo "La extensión o el tamaño de los archivos pueden no ser los correctos.\nSe permiten archivos .png o .jpg.\nSe permiten archivos de 100 Kb máximo.\n\n";
        }
    }
} else {
    echo "Parametros incorrectos\n\n";
}
