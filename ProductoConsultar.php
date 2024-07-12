<?php

/*

Alumna: BOGADO, Candela

*/

require_once("Producto.php");

if (
    isset($_POST["nombre"]) &&
    isset($_POST["tipo"]) &&
    isset($_POST["marca"])
) {
    //Producto
    $nombre = strtolower($_POST["nombre"]);
    $tipo = strtolower($_POST["tipo"]);
    $marca = strtolower($_POST["marca"]);

    //Obtener listado de productos desde archivo
    $lista = Producto::ObtenerListaDeProductos();
    //Crear nuevo objeto con los parámetros recibidos
    $producto = new Producto($nombre, 0, $tipo, $marca, 0);
    //Chequea si el producto ya existe en la lista
    $indiceP = Producto::VerificarSiExiste($lista, $producto);

    if ($indiceP != -1) {
        echo "El producto existe en el registro.\n\n";
    } else {
        if (Producto::VerificarNombre($lista, $producto) != -1) {
            echo "El nombre existe, pero no coincide el tipo.\n\n";
        } else if (Producto::VerificarTipo($lista, $producto) != -1) {
            echo "El tipo existe, pero no hay nombres que coincidan.\n\n";
        } else {
            echo "No existe ni ese nombre ni ese tipo.\n\n";
        }
    }
} else {
    echo "Parametros incorrectos\n\n";
}
