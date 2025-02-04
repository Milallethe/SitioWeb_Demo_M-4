<?php
function fxAgregarUsuarios($msUsuarios, $msClave)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "Select ifnull(max(USUARIOID), 0) as Ultimo from Usuarios";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute();
    $Fila = $mDatos->fetch();
    $Numero = intval($Fila["Ultimo"]);
    $Numero += 1;
    $Longitud = strlen($Numero);
    $msCodigo = str_repeat("0", 3 - $Longitud) . trim($Numero);

    // Pasar tres parámetros: código, nombre, clave
    $msConsulta = "call INSERT_USUARIO(?, ?, ?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo, $msUsuarios, $msClave]);

    return $msCodigo;
}

function fxModificarUsuario ($msCodigo, $msUsuarios, $msClave)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spModificarUsuario(?, ? ,?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo, $msUsuarios, $msClave]);
}

function fxBorrarUsuarios ($msCodigo)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spBorrarUsuario(?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo]);
}

function fxObtieneUsuarios ($msCodigo)
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spObtenerUsuario(?)";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute([$msCodigo]);
    return $mDatos;
}

function fxObtieneTodosUsuarios()
{
    $mConexion = fxAbrirConexion();

    $msConsulta = "call spObtenerTodosLosUsuarios()";
    $mDatos = $mConexion->prepare($msConsulta);
    $mDatos->execute(); 
    return $mDatos;
}

?>