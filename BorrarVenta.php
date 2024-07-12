<?php

/*

Alumna: BOGADO, Candela

*/

require_once("Venta.php");

if (
    isset($vars["pedido"])
) {
    $pedido = intval($vars["pedido"]);

    $lista = Venta::ObtenerListaDeVentas();
    $indiceV = Venta::FiltrarPorPedido($lista, $pedido);
    if ($indiceV != -1) {
        Venta::EliminarVenta($lista[$indiceV]);
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
