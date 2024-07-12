<?php

/*

Alumna: BOGADO, Candela

*/

require_once("Producto.php");
require_once("Conjunto.php");

if (

    isset($_POST["smartphone_nombre"]) &&
    isset($_POST["smartphone_marca"]) &&
    isset($_POST["tablet_nombre"]) &&
    isset($_POST["tablet_marca"]) &&
    isset($_FILES["imagen"])
) {
    //Ingresar un smartphone (nombre, marca) y una tablet (nombre, marca) y una imagen.
    $smartphone_nombre = strtolower($_POST["smartphone_nombre"]);
    $smartphone_marca = strtolower($_POST["smartphone_marca"]);
    $tablet_nombre = strtolower($_POST["tablet_nombre"]);
    $tablet_marca = strtolower($_POST["tablet_marca"]);
    //Data del archivo subido
    $nombre_archivo = $_FILES['imagen']['name'];
    $tipo_archivo = $_FILES['imagen']['type'];
    $tamano_archivo = $_FILES['imagen']['size'];

    //Obtener listado de productos desde archivo
    $lista = Producto::ObtenerListaDeProductos();
    //Crear nuevo objeto con los parámetros recibidos
    $smartphone = new Producto($smartphone_nombre, 0, "smartphone", $smartphone_marca, 0);
    $tablet = new Producto($tablet_nombre, 0, "tablet", $tablet_marca, 0);
    //Ambos productos deben existir en el archivo tienda.json.
    //Chequea si el producto ya existe en la lista
    $indSmartphone = Producto::VerificarSiExiste($lista, $smartphone);
    $indTablet = Producto::VerificarSiExiste($lista, $tablet);

    if ($indSmartphone != -1) {
        if ($indTablet != -1) {
            //Si los productos existen, combinarlos para formar un objeto conjunto: 
            $precio_total = Producto::CalcularPrecioConjunto($lista[$indSmartphone], $lista[$indTablet]);
            $conjunto = new Conjunto("conjunto", $smartphone_nombre, $smartphone_marca, $tablet_nombre, $tablet_marca, $precio_total);
            array_push($lista, $conjunto);
            //Almacenarlo como un único registro en el archivo tienda.json. 
                //Actualizar listado
                if (Producto::GuardarListaDeProductosJSON($lista)) {
                    echo "Lista actualizada con éxito.\n\n";
                } else {
                    echo "La lista no fue guardada correctamente.\n\n";
                }

            //Completar el alta con imagen del conjunto, guardando la imagen con nombre del smartphone + nombre de la tablet como identificación en la carpeta /ImagenesDeConjuntos/2024.
                //Guardar Imagen
                if ((strpos($tipo_archivo, "png") || strpos($tipo_archivo, "jpeg")) && ($tamano_archivo < 500000)) {
                    $extension = substr($tipo_archivo, strpos($tipo_archivo, '/') + 1);
                    $cargaFoto = Conjunto::GuardarFoto($_FILES['imagen'], $smartphone_nombre, $tablet_nombre, $extension);
                    if ($cargaFoto) {
                        echo "La imagen fue guardada exitosamente.\n\n";
                    } else {
                        echo "La foto no pudo ser guardada.\n\n";
                    }
                } else {
                    echo "La extensión o el tamaño de los archivos pueden no ser los correctos.\nSe permiten archivos .png o .jpg.\nSe permiten archivos de 100 Kb máximo.\n\n";
                }
        } else {
            echo "La tablet del conjunto no existe en la tienda.\n\n";
        }
    } else {
        echo "El smartphone del conjunto no existe en la tienda.\n\n";
    }
} else {
    echo "Parametros incorrectos\n\n";
}
