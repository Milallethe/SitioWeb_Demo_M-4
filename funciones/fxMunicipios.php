<?php
function fxAgregarMunicipio ($msCodDepartamento, $msNombre)
{	
    $mConexion = fxAbrirConexion();

    $msConsulta = "Select ifnull(mid(max(CODMUNICIPIO), 3), 0) as Ultimo from MUNICIPIOS";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute();
    $Fila = $mDatos->fetch();
    $Numero = intval($Fila["Ultimo"]);
    $Numero += 1;
    $Longitud = strlen($Numero);
    $msCodigo = "MN" . str_repeat("0", 4 - $Longitud) . trim($Numero);

    $msConsulta = "call spInsertarMunicipio(?, ?, ?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo, $msCodDepartamento, $msNombre]);
    return $msCodigo;
}

function fxModificarMunicipio ($msCodigo, $msCodDepartamento, $msNombre)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spModificarMunicipio(?, ?, ?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo, $msCodDepartamento, $msNombre]);
}

function fxBorrarMunicipio ($msCodigo)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spBorrarMunicipio(?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo]);
}

function fxObtieneMunicipio ($msCodigo)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spObtenerMunicipio(?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo]);
    return $mDatos;
}
function fxObtieneTodosMunicipios()
{
    $mConexion = fxAbrirConexion();

    // Llamada correcta al procedimiento almacenado para obtener todos los municipios
    $msConsulta = "CALL spObtenerTodosMunicipios()";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute();
    
    return $mDatos;
}

?>