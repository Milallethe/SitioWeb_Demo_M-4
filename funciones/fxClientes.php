<?php
function fxAgregarCliente ($msMunicipio, $msNombres, $msApellidos)
{	
    $mConexion = fxAbrirConexion();

    $msConsulta = "Select ifnull(max(CODCLIENTE), 0) as Ultimo from CLIENTES";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute();
    $Fila = $mDatos->fetch();
    $Numero = intval($Fila["Ultimo"]);
    $Numero += 1;
    $Longitud = strlen($Numero);
    $msCodigo = str_repeat("0", 3 - $Longitud) . trim($Numero);

    $msConsulta = "call spInsertarCliente(?, ?, ?, ?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo, $msMunicipio, $msNombres, $msApellidos]);
    return $msCodigo;
}

function fxModificarCliente ($msCodigo, $msMunicipio, $msNombres, $msApellidos)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spModificarCliente(?, ?, ?, ?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo, $msMunicipio, $msNombres, $msApellidos]);
}

function fxBorrarCliente ($msCodigo)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spBorrarCliente(?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo]);
}

function fxObtieneCliente ($msCodigo)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spObtenerCliente(?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo]);
    return $mDatos;
}
function fxObtieneTodosClientes()
{
    $mConexion = fxAbrirConexion();

    // Llamando al procedimiento correcto
    $msConsulta = "call spObtenerTodosClientes()";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute();  // No es necesario pasar parámetros
    return $mDatos;
}

?>