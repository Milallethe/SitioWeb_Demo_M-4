<?php
session_start();
if (!isset($_SESSION["gnVerifica"]) or $_SESSION["gnVerifica"] != 1)
{
    echo('<meta http-equiv="Refresh" content="0;url=index.php"/>');
 exit('');
}
	include ("masterWeb.php");
	require_once ("funciones/fxGeneral.php");
	require_once ("funciones/fxClientes.php");
	$mConexion = fxAbrirConexion();

	if (isset($_POST["txtClienteId"]))
	{
		$msCodigo = $_POST["txtClienteId"];
		$msMunicipio = $_POST["cboMunicipio"];
		$msNombre = $_POST["txtNombre"];
		$msApellido = $_POST["txtApellido"];

		if ($msCodigo == ""){
			$msCodigo = fxAgregarCliente ($msMunicipio, $msNombre, $msApellido);
			fxBitacora ($_SESSION["gsUsuario"], $msCodigo, "CLIENTES", "Agregar");
		}
		else{
			fxModificarCliente($msCodigo, $msMunicipio, $msNombre, $msApellido);
			fxBitacora ($_SESSION["gsUsuario"], $msCodigo, "CLIENTES", "Modificar");
		}
							
		?><meta http-equiv="Refresh" content="0;url=gridClientes.php"/><?php
	}
	else
	{
		if (isset($_POST["UCN"]))
			$msCodigo = $_POST["UCN"];
		else
			$msCodigo = "";
				
		if ($msCodigo != "")
		{
			$mDatos = fxObtieneCliente($msCodigo);
			$mFila = $mDatos->fetch();
			$msMunicipio = $mFila["CODMUNICIPIO"];
			$msNombre = $mFila["NOMBRE"];
			$msApellido = $mFila["APELLIDO"];
		}
		else
		{
			$msMunicipio = "";
			$msNombre = "";
			$msApellido = "";
		}
	}
	?>
    <div class="contenedor text-left">
    	<div class="divContenido">
			<div class = "row">
				<div class="col-xs-12 col-md-11">
					<div class="degradado"><strong>Clientes</strong></div>
				</div>
			</div>
			
			<div class = "row">
                <div class="col-xs-12 col-xs-offset-none col-md-12 col-md-offset-2">
				<form id="catClientes" name="catClientes" action="catClientes.php" onsubmit="return verificarFormulario()" method="post">
                	<div class = "form-group row">
                        <label for="txtClienteId" class="col-sm-12 col-md-2 col-form-label">Cliente</label>
                        <div class="col-sm-12 col-md-2">
                        <?php
                            echo('<input type="text" class="form-control" id="txtClienteId" name="txtClienteId" value="' . $msCodigo . '" readonly />'); 
                        ?>
                        </div>
                    </div>
                    
					<div class = "form-group row">
						<label for="cboDepartamento" class="col-sm-12 col-md-2 col-form-label">Departamento</label>
                        <div class="col-sm-12 col-md-5">
						<?php 
							echo('<select class="form-control" id="cboDepartamento" name="cboDepartamento" onchange="llenaMunicipios(this.value)">');

							if ($msMunicipio != "")
							{
								$msConsulta = "select CODDEPARTAMENTO from MUNICIPIOS where CODMUNICIPIO = ?";
								$mDatos = $mConexion->prepare($msConsulta);
								$mDatos->execute([$msMunicipio]);
								$mFila = $mDatos->fetch();
								$msDepartamento = $mFila["CODDEPARTAMENTO"];
							}
							else
								$msDepartamento = "";

							$msConsulta = "select CODDEPARTAMENTO, NOMBRE from DEPARTAMENTOS order by NOMBRE";
							$mDatos = $mConexion->prepare($msConsulta);
							$mDatos->execute();

							while ($mFila = $mDatos->fetch())
							{
								$msCodDepartamento = $mFila["CODDEPARTAMENTO"];
								$msNomDepartamento = $mFila["NOMBRE"];

								if ($msDepartamento == ""){
									echo("<option value='" . $msCodDepartamento . "' selected>" . $msNomDepartamento . "</option>");
									$msDepartamento = $msCodDepartamento;
								}
								else{
									if ($msDepartamento == $msCodDepartamento)
										echo("<option value='" . $msCodDepartamento . "' selected>" . $msNomDepartamento . "</option>");
									else
										echo("<option value='" . $msCodDepartamento . "'>" . $msNomDepartamento . "</option>");
								}
							}

							echo('</select>');
						?>
                        </div>
					</div>

					<div class = "form-group row">
						<label for="cboMunicipio" class="col-sm-12 col-md-2 col-form-label">Municipio</label>
                        <div class="col-sm-12 col-md-5">
						<?php 
							echo('<select class="form-control" id="cboMunicipio" name="cboMunicipio">');

							$msConsulta = "select CODMUNICIPIO, NOMBRE from MUNICIPIOS where CODDEPARTAMENTO = ? order by NOMBRE";
							$mDatos = $mConexion->prepare($msConsulta);
							$mDatos->execute([$msDepartamento]);

							while ($mFila = $mDatos->fetch())
							{
								$msCodMunicipio = $mFila["CODMUNICIPIO"];
								$msNomMunicipio = $mFila["NOMBRE"];

								if ($msMunicipio == ""){
									echo("<option value='" . $msCodMunicipio . "' selected>" . $msNomMunicipio . "</option>");
									$msMunicipio = $msCodMunicipio;
								}
								else{
									if ($msMunicipio == $msCodMunicipio)
										echo("<option value='" . $msCodMunicipio . "' selected>" . $msNomMunicipio . "</option>");
									else
										echo("<option value='" . $msCodMunicipio . "'>" . $msNomMunicipio . "</option>");
								}
							}

							echo('</select>');
						?>
                        </div>
					</div>

                    <div class = "form-group row">
						<label for="txtNombre" class="col-sm-12 col-md-2 col-form-label">Nombre</label>
                        <div class="col-sm-12 col-md-5">
						<?php echo('<input type="text" class="form-control" id="txtNombre" name="txtNombre" value="' . $msNombre . '" />'); ?>
                        </div>
					</div>
					
					<div class = "form-group row">
						<label for="txtApellido" class="col-sm-12 col-md-2 col-form-label">Apellido</label>
                        <div class="col-sm-12 col-md-5">
						<?php echo('<input type="text" class="form-control" id="txtApellido" name="txtApellido" value="' . $msApellido . '" />'); ?>
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

		if(document.getElementById('txtApellido').value=="")
		{
			$.messager.alert('Conexión PHP-MySQL','Falta el Apellido.','warning');
			return false;
		}
		
		return true;
	}

	function llenaMunicipios(departamento)
	{
		var datos = new FormData();
		datos.append('departamento', departamento);

		$.ajax({
			url: 'funciones/fxDatosClientes.php',
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