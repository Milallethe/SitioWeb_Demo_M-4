<?php
session_start();
if (!isset($_SESSION["gnVerifica"]) or $_SESSION["gnVerifica"] != 1)
{
    echo('<meta http-equiv="Refresh" content="0;url=index.php"/>');
 exit('');
}

include ("masterWeb.php");
require_once ("funciones/fxGeneral.php");
require_once ("funciones/fxUsuarios.php");

if (isset($_POST["txtUsuarioId"]))
{
    $msCodigo = $_POST["txtUsuarioId"];
    $msUsuario = $_POST["txtUsuario"];
    $msClave = $_POST["txtClave"];

    // Encriptar la contraseña antes de guardarla
    $msClaveEncriptada = password_hash($msClave, PASSWORD_DEFAULT); // Encriptar la contraseña

    if ($msCodigo == "") {
        $msCodigo = fxAgregarUsuarios($msUsuario, $msClaveEncriptada);
        fxBitacora ($_SESSION["gsUsuario"], $msCodigo, "USUARIOS", "Agregar");
    }
    else {
        fxModificarUsuario($msCodigo, $msUsuario, $msClaveEncriptada);
        fxBitacora ($_SESSION["gsUsuario"], $msCodigo, "USUARIOS", "Modificar");
    }
                            
    ?><meta http-equiv="Refresh" content="0;url=gridUsuarios.php"/><?php
}
else {
    if (isset($_POST["UCN"]))
        $msCodigo = $_POST["UCN"];
    else
        $msCodigo = "";

    if ($msCodigo != "") {
        $mDatos = fxObtieneUsuarios($msCodigo);
        $mFila = $mDatos->fetch();
        $msUsuario = $mFila["USUARIO"];
        $msClave = $mFila["CLAVE"];
    }
    else {
        $msUsuario = "";
        $msClave = "";
    }
}
?>
<div class="contenedor text-left">
    <div class="divContenido">
        <div class = "row">
            <div class="col-xs-12 col-md-11">
                <div class="degradado"><strong>Usuarios</strong></div>
            </div>
        </div>

        <div class = "row">
            <div class="col-xs-12 col-xs-offset-none col-md-12 col-md-offset-2">
                <form id="catUsuarios" name="catUsuarios" action="catUsuarios.php" onsubmit="return verificarFormulario()" method="post">
                    <div class = "form-group row">
                        <label for="txtUsuarioId" class="col-sm-12 col-md-1 col-form-label">Id Usuario</label>
                        <div class="col-sm-12 col-md-2">
                            <?php
                                echo('<input type="text" class="form-control" id="txtUsuarioId" name="txtUsuarioId" value="' . $msCodigo . '" readonly />'); 
                            ?>
                        </div>
                    </div>

                    <div class = "form-group row">
                        <label for="txtUsuario" class="col-sm-12 col-md-1 col-form-label">Usuario</label>
                        <div class="col-sm-12 col-md-5">
                            <?php echo('<input type="text" class="form-control" id="txtUsuario" name="txtUsuario" value="' . $msUsuario . '" />'); ?>
                        </div>
                    </div>

                    <div class = "form-group row">
                        <label for="txtClave" class="col-sm-12 col-md-1 col-form-label">Clave</label>
                        <div class="col-sm-12 col-md-5">
                            <?php echo('<input type="password" class="form-control" id="txtClave" name="txtClave" value="' . $msClave . '" />'); ?>
                        </div>
                    </div>

                    <div class = "row">
                        <div class="col-auto col-xs-offset-none col-md-12 col-md-offset-1">
                            <input type="submit" id="Guardar" name="Guardar" value="Guardar" class="btn btn-primary" />
                            <input type="button" id="Cancelar" name="Cancelar" value="Cancelar" class="btn btn-primary" onclick="location.href='gridUsuarios.php';"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script type='text/javascript'>
    function verificarFormulario()
    {
        if(document.getElementById('txtUsuario').value=="")
        {
            $.messager.alert('Conexión PHP-MySQL','Falta el Usuario.','warning');
            return false;
        }

        if(document.getElementById('txtClave').value=="")
        {
            $.messager.alert('Conexión PHP-MySQL','Falta la Clave.','warning');
            return false;
        }

        return true;
    }
</script>
