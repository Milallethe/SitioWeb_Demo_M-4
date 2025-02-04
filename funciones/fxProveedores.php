<?php
function fxAgregarProveedores ($msMunicipio, $msNombres)
{	
    $mConexion = fxAbrirConexion();

    $msConsulta = "Select ifnull(max(CODPROVEEDOR), 0) as Ultimo from Proveedores";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute();
    $Fila = $mDatos->fetch();
    $Numero = intval($Fila["Ultimo"]);
    $Numero += 1;
    $Longitud = strlen($Numero);
    $msCodigo = str_repeat("0", 3 - $Longitud) . trim($Numero);

    $msConsulta = "call spInsertarProveedor(?, ?, ?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo, $msMunicipio, $msNombres]);
    return $msCodigo;
}

function fxModificarProveedores ($msCodigo,$msMunicipio, $msNombres)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spActualizarProveedor(?, ?,?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo, $msMunicipio, $msNombres]);
}

function fxBorrarProveedores ($msCodigo)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spEliminarProveedor(?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo]);
}


function fxObtieneProveedores ($msCodigo)
{
    $mConexion = fxAbrirConexion();

     $msConsulta = "CALL spObtenerProveedorPorCodigo(?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo]);
    return $mDatos;
}

function fxObtieneTodosProveedores()
{
    $mConexion = fxAbrirConexion();

    // Llamando al procedimiento correcto
    $msConsulta = "CALL spObtenerTodosProveedores()";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute();  // No es necesario pasar parámetros
    return $mDatos;
}
?>