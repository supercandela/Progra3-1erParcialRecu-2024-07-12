<?php

/*

Alumna: BOGADO, Candela

*/

require_once("Venta.php");

if (
    isset($vars["pedido"]) &&
    isset($vars["usuario"]) &&
    isset($vars["nombre"]) &&
    isset($vars["tipo"]) &&
    isset($vars["marca"]) &&
    isset($vars["cantidad"])
) {
    $pedido = intval($vars["pedido"]);
    $usuario = $vars["usuario"];
    $nombre = strtolower($vars["nombre"]);
    $tipo = strtolower($vars["tipo"]);
    $marca = strtolower($vars["marca"]);
    $cantidad = intval($vars["cantidad"]);

    $lista = Venta::ObtenerListaDeVentas();
    $indiceV = Venta::FiltrarPorPedido($lista, $pedido);
    if ($indiceV != -1) {
        Venta::ModificarVenta($lista[$indiceV], $usuario, $nombre, $tipo, $marca, $cantidad);
        if (Venta::GuardarVentasJSON($lista)) {
            echo "Venta actualizada con éxito.\n\n";
        } else {
            echo "La venta no fue actualizada.\n\n";
        }
    } else {
        echo "No existe el número de pedido " . $pedido . ".\n\n";
    }
} else {
    echo "Parametros incorrectos\n\n";
}
