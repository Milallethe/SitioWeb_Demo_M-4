<?php 
session_start();
$_SESSION["gnVerifica"] = 0;

require_once("funciones/fxGeneral.php");
?>
// Esta parte del demo queda con las modificaciones del encriptado...
<!DOCTYPE html>
<html lang="es-NI" class="no-js">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="Sitio web demostrativo."/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="icon" href="imagenes/logo.png" />
    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="stylesheet" href="css/estilos.css" />
    <link rel="stylesheet" href="css/easyui.css" />
    <link rel="stylesheet" href="css/icon.css" />

    <script src="js/jquery-3.7.1.js"></script>
    <script src="js/jquery.easyui.min.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <title>Conexión PHP-MySQL</title>
    <style>
        body {
            background-image: url(imagenes/fondo.png);
            background-repeat: no-repeat;
            background-size: cover;
        }
        label {
            font-size: xx-large;
            font-weight: bolder;
        }
    </style>
</head>
<body>
<?php
if (isset($_POST['txtUsuario'])) {
    try {
        $mConexion = fxAbrirConexion();
        $msUsuario = trim($_POST['txtUsuario']);
        $msClave = $_POST['txtClave'];

        // Consulta para obtener la contraseña del usuario
        $msConsulta = "SELECT CLAVE FROM USUARIOS WHERE USUARIO = ?";
        $mDatos = $mConexion->prepare($msConsulta);
        $mDatos->execute([$msUsuario]);
        $Fila = $mDatos->fetch();

        if ($Fila && isset($Fila["CLAVE"])) {
            $msClaveBD = $Fila["CLAVE"];

            // Compara la contraseña ingresada con la encriptada
            if (password_verify($msClave, $msClaveBD)) {
                $_SESSION["gsUsuario"] = $msUsuario;
                $_SESSION["gnVerifica"] = 1;
                echo('<meta http-equiv="Refresh" content="0;url=gridClientes.php">');
            } else {
                echo "<script>
                    $.messager.alert('Reportes', 'Contraseña incorrecta.', 'warning');
                    </script>";
            }
        } else {
            echo "<script>
                $.messager.alert('Reportes', 'Usuario no encontrado.', 'warning');
                </script>";
        }
    } catch (PDOException $e) {
        echo "<script>
            $.messager.alert('Error', 'Error en la conexión o consulta: " . addslashes($e->getMessage()) . "', 'error');
            </script>";
    }
}
?>
<form action="index.php" method="post" onsubmit="return verificarFormulario()">
    <div class="divLateral">
        <div id="divLogIn">
            <input class="form-control" style="margin-bottom:2%; width:90%;" type="text" placeholder="Usuario" name="txtUsuario" id="txtUsuario">
            <input class="form-control" style="margin-bottom:2%; width:90%;" type="password" placeholder="Contraseña" name="txtClave" id="txtClave">
            <input class="btn btn-primary" type="submit" value="Entrar">
        </div>

        <div class="divTitulo">
            <img style="align-self: center" src="imagenes/logo.png" style="width: 30%" alt="Logo">
            <label style="align-self: center">Conexión PHP-MySQL</label>
        </div>
    </div>
</form>
<script>
function verificarFormulario() {
    if (document.getElementById("txtUsuario").value.trim() === '') {
        $.messager.alert('Conexión PHP-MySQL', 'Falta el Usuario.', 'warning');
        return false;
    }

    if (document.getElementById("txtClave").value.trim() === '') {
        $.messager.alert('Conexión PHP-MySQL', 'Falta la Contraseña.', 'warning');
        return false;
    }
    

    return true;
}
</script>
</body>
</html>
