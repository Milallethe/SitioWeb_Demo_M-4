<?php
function fxAgregarDepartamento ($msNombre)
{	
    $mConexion = fxAbrirConexion();

    $msConsulta = "Select ifnull(mid(max(CODDEPARTAMENTO), 3), 0) as Ultimo from DEPARTAMENTOS";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute();
    $Fila = $mDatos->fetch();
    $Numero = intval($Fila["Ultimo"]);
    $Numero += 1;
    $Longitud = strlen($Numero);
    $msCodigo = "DP" . str_repeat("0", 3 - $Longitud) . trim($Numero);

    $msConsulta = "call spInsertarDepartamento(?, ?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo, $msNombre]);
    return $msCodigo;
}

function fxModificarDepartamento ($msCodigo, $msNombre)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spModificarDepartamento(?, ?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo, $msNombre]);
}

function fxBorrarDepartamento ($msCodigo)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spBorrarDepartamento(?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo]);
}

function fxObtieneDepartamento ($msCodigo)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spObtenerDepartamento(?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo]);
    return $mDatos;
}

function fxObtieneTodosDepartamentos()
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spObtenerDepartamento(?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute(['']);
    return $mDatos;
}
?>