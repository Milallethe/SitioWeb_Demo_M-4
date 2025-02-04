<?php
session_start();
if (!isset($_SESSION["gnVerifica"]) or $_SESSION["gnVerifica"] != 1)
{
    echo('<meta http-equiv="Refresh" content="0;url=index.php"/>');
 exit('');
}
	include ("masterWeb.php");
	require_once ("funciones/fxGeneral.php");
	require_once ("funciones/fxDepartamentos.php");

	if (isset($_POST["txtDepartamentoId"]))
	{
		$msCodigo = $_POST["txtDepartamentoId"];
		$msNombre = $_POST["txtNombre"];

		if ($msCodigo == ""){
			$msCodigo = fxAgregarDepartamento ($msNombre);
			fxBitacora ($_SESSION["gsUsuario"], $msCodigo, "DEPARTAMENTOS", "Agregar");
		}
		else{
			fxModificarDepartamento ($msCodigo, $msNombre);
			fxBitacora ($_SESSION["gsUsuario"], $msCodigo, "DEPARTAMENTOS", "Modificar");
		}
							
		?><meta http-equiv="Refresh" content="0;url=gridDepartamentos.php"/><?php
	}
	else
	{
		if (isset($_POST["UCN"]))
			$msCodigo = $_POST["UCN"];
		else
			$msCodigo = "";
				
		if ($msCodigo != "")
		{
			$mDatos = fxObtieneDepartamento($msCodigo);
			$mFila = $mDatos->fetch();
			$msNombre = $mFila["NOMBRE"];
		}
		else
		{
			$msNombre = "";
		}
	}
	?>
    <div class="contenedor text-left">
    	<div class="divContenido">
			<div class = "row">
				<div class="col-xs-12 col-md-11">
					<div class="degradado"><strong>Departamentos</strong></div>
				</div>
			</div>
			
			<div class = "row">
                <div class="col-xs-12 col-xs-offset-none col-md-12 col-md-offset-2">
				<form id="catDepartamentos" name="catDepartamentos" action="catDepartamentos.php" onsubmit="return verificarFormulario()" method="post">
                	<div class = "form-group row">
                        <label for="txtDepartamentoId" class="col-sm-12 col-md-2 col-form-label">Departamento</label>
                        <div class="col-sm-12 col-md-2">
                        <?php
                            echo('<input type="text" class="form-control" id="txtDepartamentoId" name="txtDepartamentoId" value="' . $msCodigo . '" readonly />'); 
                        ?>
                        </div>
                    </div>
                    
                    <div class = "form-group row">
						<label for="txtNombre" class="col-sm-12 col-md-2 col-form-label">Nombre</label>
                        <div class="col-sm-12 col-md-5">
						<?php echo('<input type="text" class="form-control" id="txtNombre" name="txtNombre" value="' . $msNombre . '" />'); ?>
                        </div>
					</div>
					
					<div class = "row">
                    	<div class="col-auto col-xs-offset-none col-md-12 col-md-offset-2">
							<input type="submit" id="Guardar" name="Guardar" value="Guardar" class="btn btn-primary" />
                            <input type="button" id="Cancelar" name="Cancelar" value="Cancelar" class="btn btn-primary" onclick="location.href='gridClientes.php';"/>
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
		if(document.getElementById('txtNombre').value=="")
		{
			$.messager.alert('Conexión PHP-MySQL','Falta el Nombre.','warning');
			return false;
		}

		return true;
	}
</script>