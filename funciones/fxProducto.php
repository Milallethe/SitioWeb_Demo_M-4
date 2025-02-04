<?php
function fxAgregarProducto ($msDescripcion, $msExistencia)
{	
    $mConexion = fxAbrirConexion();

    $msConsulta = "Select ifnull(max(CODProducto), 0) as Ultimo from ProductoS";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute();
    $Fila = $mDatos->fetch();
    $Numero = intval($Fila["Ultimo"]);
    $Numero += 1;
    $Longitud = strlen($Numero);
    $msCodigo = str_repeat("0", 3 - $Longitud) . trim($Numero);

    $msConsulta = "call INSERTAR_PROD(?, ?, ?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo, $msDescripcion, $msExistencia]);
    return $msCodigo;
}

function fxModificarProducto ($msCodigo, $msDescripcion, $msExistencia)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spModificarProducto(?, ?, ?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo, $msDescripcion, $msExistencia]);
}

function fxBorrarProducto ($msCodigo)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spBorrarProducto(?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo]);
}

function fxObtieneProducto ($msCodigo)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spObtenerProducto(?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo]);
    return $mDatos;
}

function fxObtieneTodosProductos()
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spObtenerProducto(?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute(['']);
    return $mDatos;
}
?>