<?php
function fxAgregarVenta ($msCliente, $msProducto, $mdFecha, $mnCantidad)
{	
    $mConexion = fxAbrirConexion();

    $msConsulta = "Select ifnull(mid(max(CODVENTA), 2), 0) as Ultimo from VENTAS";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute();
    $Fila = $mDatos->fetch();
    $Numero = intval($Fila["Ultimo"]);
    $Numero += 1;
    $Longitud = strlen($Numero);
    $msCodigo = "V" . str_repeat("0", 4 - $Longitud) . trim($Numero);

    $msConsulta = "call spInsertarVenta(?, ?, ?, ?, ?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo, $msCliente, $msProducto, $mdFecha, $mnCantidad]);
    return $msCodigo;
}

function fxModificarVenta ($msCodigo, $msCliente, $msProducto, $mdFecha, $mnCantidad)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spModificarVenta(?, ?, ?, ?, ?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo, $msCliente, $msProducto, $mdFecha, $mnCantidad]);
}

function fxBorrarVenta ($msCodigo)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spBorrarVenta(?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo]);
}

function fxObtieneVenta ($msCodigo)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spObtenerVenta(?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo]);
    return $mDatos;
}
function fxObtieneTodosVentas()
{
    $mConexion = fxAbrirConexion();
    $msConsulta = "call spObtenerVenta(?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute(['']);  
    return $mDatos;
}

?>