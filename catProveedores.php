<?php
session_start();
if (!isset($_SESSION["gnVerifica"]) or $_SESSION["gnVerifica"] != 1)
{
    echo('<meta http-equiv="Refresh" content="0;url=index.php"/>');
 exit('');
}
	include ("masterWeb.php");
	require_once ("funciones/fxGeneral.php");
	require_once ("funciones/fxproveedores.php");
	$mConexion = fxAbrirConexion();

	if (isset($_POST["txtproveedoresId"]))
	{
		$msCodigo = $_POST["txtproveedoresId"];
		$msDepartamento = $_POST["cboDepartamento"];
		$msMunicipio = $_POST["cboMunicipio"];
		$msNombre = $_POST["txtNombre"];

		if ($msCodigo == ""){
			$msCodigo = fxAgregarproveedores ($msDepartamento, $msMunicipio, $msNombre);
			fxBitacora ($_SESSION["gsUsuario"], $msCodigo, "proveedores", "Agregar");
		}
		else{
			fxModificarproveedores($msCodigo, $msDepartamento, $msMunicipio, $msNombre);
			fxBitacora ($_SESSION["gsUsuario"], $msCodigo, "proveedores", "Modificar");
		}
							
		?><meta http-equiv="Refresh" content="0;url=gridproveedores.php"/><?php
	}
	else
	{
		if (isset($_POST["UCN"]))
			$msCodigo = $_POST["UCN"];
		else
			$msCodigo = "";
				
		if ($msCodigo != "")
		{
			$mDatos = fxObtieneproveedores($msCodigo);
			$mFila = $mDatos->fetch();
			$msDepartamento = $mFila["CODDEPARTAMENTO"];
			$msMunicipio = $mFila["CODMUNICIPIO"];
			$msNombre = $mFila["NOMBRE"];
		}
		else
		{
			$msDepartamento = "";
			$msMunicipio = "";
			$msNombre = "";
		}
	}
?>

<div class="contenedor text-left">
	<div class="divContenido">
		<div class="row">
			<div class="col-xs-12 col-md-11">
				<div class="degradado"><strong>Proveedores</strong></div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-md-12 col-md-offset-2">
				<form id="catproveedores" name="catproveedores" action="catproveedores.php" onsubmit="return verificarFormulario()" method="post">
					<div class="form-group row">
						<label for="txtproveedoresId" class="col-sm-12 col-md-2 col-form-label">Proveedor</label>
						<div class="col-sm-12 col-md-2">
							<?php echo('<input type="text" class="form-control" id="txtproveedoresId" name="txtproveedoresId" value="' . $msCodigo . '" readonly />'); ?>
						</div>
					</div>

					<div class="form-group row">
						<label for="cboDepartamento" class="col-sm-12 col-md-2 col-form-label">Departamento</label>
						<div class="col-sm-12 col-md-5">
							<?php
								echo('<select class="form-control" id="cboDepartamento" name="cboDepartamento" onchange="llenaMunicipios(this.value)">');
								$msConsulta = "SELECT CODDEPARTAMENTO, NOMBRE FROM DEPARTAMENTOS ORDER BY NOMBRE";
								$mDatos = $mConexion->prepare($msConsulta);
								$mDatos->execute();

								while ($mFila = $mDatos->fetch())
								{
									$msCodDepartamento = $mFila["CODDEPARTAMENTO"];
									$msNomDepartamento = $mFila["NOMBRE"];
									if ($msDepartamento == $msCodDepartamento)
										echo("<option value='" . $msCodDepartamento . "' selected>" . $msNomDepartamento . "</option>");
									else
										echo("<option value='" . $msCodDepartamento . "'>" . $msNomDepartamento . "</option>");
								}
								echo('</select>');
							?>
						</div>
					</div>

					<div class="form-group row">
						<label for="cboMunicipio" class="col-sm-12 col-md-2 col-form-label">Municipio</label>
						<div class="col-sm-12 col-md-5">
							<?php
								echo('<select class="form-control" id="cboMunicipio" name="cboMunicipio">');
								$msConsulta = "SELECT CODMUNICIPIO, NOMBRE FROM MUNICIPIOS WHERE CODDEPARTAMENTO = ? ORDER BY NOMBRE";
								$mDatos = $mConexion->prepare($msConsulta);
								$mDatos->execute([$msDepartamento]);

								while ($mFila = $mDatos->fetch())
								{
									$msCodMunicipio = $mFila["CODMUNICIPIO"];
									$msNomMunicipio = $mFila["NOMBRE"];
									if ($msMunicipio == $msCodMunicipio)
										echo("<option value='" . $msCodMunicipio . "' selected>" . $msNomMunicipio . "</option>");
									else
										echo("<option value='" . $msCodMunicipio . "'>" . $msNomMunicipio . "</option>");
								}
								echo('</select>');
							?>
						</div>
					</div>

					<div class="form-group row">
						<label for="txtNombre" class="col-sm-12 col-md-2 col-form-label">Nombre</label>
						<div class="col-sm-12 col-md-5">
							<?php echo('<input type="text" class="form-control" id="txtNombre" name="txtNombre" value="' . $msNombre . '" />'); ?>
						</div>
					</div>

					<div class="row">
						<div class="col-auto col-xs-offset-none col-md-12 col-md-offset-1">
							<input type="submit" id="Guardar" name="Guardar" value="Guardar" class="btn btn-primary" />
							<input type="button" id="Cancelar" name="Cancelar" value="Cancelar" class="btn btn-primary" onclick="location.href='gridproveedores.php';"/>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
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

	function llenaMunicipios(departamento)
	{
		var datos = new FormData();
		datos.append('departamento', departamento);

		$.ajax({
			url: 'funciones/fxDatosProveedores.php',
			type: 'post',
			data: datos,
			contentType: false,
			processData: false,
			success: function(response){
				document.getElementById('cboMunicipio').innerHTML = response;
			}
		})
	}
</script>
