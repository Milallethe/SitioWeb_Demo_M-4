<?php
function fxAgregarCompras ( $msCliente, $msProducto, $mdFecha, $mnCantidad)
{	
    $mConexion = fxAbrirConexion();

    $msConsulta = "Select ifnull(max(CODCOMPRA), 0) as Ultimo from Compras";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute();
    $Fila = $mDatos->fetch();
    $Numero = intval($Fila["Ultimo"]);
    $Numero += 1;
    $Longitud = strlen($Numero);
    $msCodigo = str_repeat("0", 3 - $Longitud) . trim($Numero);

    $msConsulta = "call INSERT_Compras(?, ?, ?,?, ?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo, $msCliente, $msProducto,  $mdFecha, $mnCantidad]);
    return $msCodigo;
}

function fxModificarCompras ($msCodigo, $msCliente, $msProducto, $mdFecha, $mnCantidad)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spModificarCompras(?, ?, ?,?, ?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo, $msCliente, $msProducto, $mdFecha, $mnCantidad]);
}

function fxBorrarCompras ($msCodigo)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spBorrarCompras(?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo]);
}

function fxObtieneCompras ($msCodigo)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spObtenerCompra(?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo]);
    return $mDatos;
}

function fxObtieneTodosCompras()
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spObtenerCompra(?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute(['']);
    return $mDatos;
}
?>