<?php

/*

Alumna: BOGADO, Candela

*/

echo "Tienda de Electrónica\n\n";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];

        switch ($accion) {
            case 'tiendaAlta':
                include 'TiendaAlta.php';
                break;

            case 'consultarProducto':
                include 'ProductoConsultar.php';
                break;

            case 'altaVenta':
                include 'AltaVenta.php';
                break;

            case 'altaConjunto':
                include 'AltaConjunto.php';
                break;

            default:
                echo "Acción no válida.\n\n";
                break;
        }
    } else {
        echo "No se especificó ninguna acción.\n\n";
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['accion'])) {
        
        $accion = $_GET['accion'];

        switch ($accion) {
            case 'consultaVentas':
                include 'ConsultasVentas.php';
                break;

            default:
                echo "Acción no válida.\n\n";
                break;
        }
    } else {
        echo "No se especificó ninguna acción.\n\n";
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $vars);

    if (isset($vars['accion'])) {
        $accion = $vars['accion'];

        switch ($accion) {
            case 'modificarVenta':
                include 'ModificarVenta.php';
                break;

            default:
                echo "Acción no válida.\n\n";
                break;
        }
    } else {
        echo "No se especificó ninguna acción.\n\n";
    }
}  else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $vars);

    if (isset($vars['accion'])) {
        $accion = $vars['accion'];

        switch ($accion) {
            case 'borrarVenta':
                include 'BorrarVenta.php';
                break;

            default:
                echo "Acción no válida.\n\n";
                break;
        }
    } else {
        echo "No se especificó ninguna acción.\n\n";
    }
} else {
    echo "Método no soportado.\n\n";
}
