<?php

/*

Alumna: BOGADO, Candela

*/

require_once("Venta.php");

if (isset($_GET["fecha"])) {
    $fecha = $_GET["fecha"];
    if ($fecha == "") {
        // Crear instancia de DateTime para la fecha actual
        $fecha = new DateTime();
        // Modificar la fecha para que sea la fecha de ayer
        $fecha = $fecha->modify('-1 day');
    } else {
        $fecha = new DateTime($fecha);
    }
    $lista = Venta::ObtenerListaDeVentas();
    $nuevaLista = Venta::FiltrarListaPorFechaExacta($lista, $fecha);
    $cantidadVendida = Venta::SumarCantidades($nuevaLista);
    echo "La cantidad de productos vendidos en la fecha " . $fecha->format('Y-m-d') . " es: " . $cantidadVendida;
} else if (isset($_GET["usuario"])) {
    $usuario = $_GET["usuario"];
    $lista = Venta::ObtenerListaDeVentas();
    $nuevaLista = Venta::FiltrarListaPorUsuario($lista, $usuario);
    foreach ($nuevaLista as $venta) {
        $venta->MostrarVenta();
    }
} else if (isset($_GET["tipo"])) {
    $tipo = $_GET["tipo"];
    $lista = Venta::ObtenerListaDeVentas();
    $nuevaLista = Venta::FiltrarListaPorTipo($lista, $tipo);
    foreach ($nuevaLista as $venta) {
        $venta->MostrarVenta();
    }
} else if (isset($_GET["precioMin"]) && isset($_GET["precioMax"])) {
    $precioMin = floatval($_GET["precioMin"]);
    $precioMax = floatval($_GET["precioMax"]);
    $lista = Venta::ObtenerListaDeVentas();
    $nuevaLista = Venta::FiltrarListaPorRangoDePrecios($lista, $precioMin, $precioMax);
    foreach ($nuevaLista as $venta) {
        $venta->MostrarVenta();
    }
} else if (isset($_GET["gananciasEnFecha"])) {
    $fecha = $_GET["gananciasEnFecha"];
    $lista = Venta::ObtenerListaDeVentas();
    if ($fecha == "") {
        $nuevaLista = $lista;
    } else {
        $fecha = new DateTime($fecha);
        $nuevaLista = Venta::FiltrarListaPorFechaExacta($lista, $fecha);
    }
    $ganancias = Venta::SumarGanancias($nuevaLista);

    if ($fecha == "") {
        echo "La cantidad de ganancias total es: " . $ganancias;
    } else {
        echo "La cantidad de ganancias de ventas en la fecha " . $fecha->format('Y-m-d') . " es: " . $ganancias;
    }
} else if (isset($_GET["productoMasVendido"])) {
    $masVendido = Venta::ObtenerProductoMasVendido();
    echo "El producto más vendido es " . $masVendido[0] . " y la cantidad de unidades fueron: " . $masVendido[1] . ".";

} else {
    echo "Parametros incorrectos\n\n";
}
