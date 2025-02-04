<?php
session_start();
if (!isset($_SESSION["gnVerifica"]) or $_SESSION["gnVerifica"] != 1)
{
    echo('<meta http-equiv="Refresh" content="0;url=index.php"/>');
 exit('');
}
	include ("masterWeb.php");
	require_once ("funciones/fxGeneral.php");
	require_once ("funciones/fxMunicipios.php");
	$mConexion = fxAbrirConexion();

	if (isset($_POST["txtMunicipioId"]))
	{
		$msCodigo = $_POST["txtMunicipioId"];
		$msDepartamento = $_POST["cboDepartamento"];
		$msNombre = $_POST["txtNombre"];

		if ($msCodigo == ""){
			$msCodigo = fxAgregarMunicipio ($msDepartamento, $msNombre);
			fxBitacora ($_SESSION["gsUsuario"], $msCodigo, "MUNICIPIOS", "Agregar");
		}
		else{
			fxModificarVenta ($msCodigo, $msDepartamento, $msNombre);
			fxBitacora ($_SESSION["gsUsuario"], $msCodigo, "MUNICIPIOS", "Modificar");
		}
							
		?><meta http-equiv="Refresh" content="0;url=gridMunicipios.php"/><?php
	}
	else
	{
		if (isset($_POST["UCN"]))
			$msCodigo = $_POST["UCN"];
		else
			$msCodigo = "";
				
		if ($msCodigo != "")
		{
			$mDatos = fxObtieneMunicipio($msCodigo);
			$mFila = $mDatos->fetch();
			$msDepartamento = $mFila["CODDEPARTAMENTO"];
			$msNombre = $mFila["NOMBRE"];
		}
		else
		{
			$msDepartamento = "";
			$msNombre = "";
		}
	}
	?>
    <div class="contenedor text-left">
    	<div class="divContenido">
			<div class = "row">
				<div class="col-xs-12 col-md-11">
					<div class="degradado"><strong>Municipios</strong></div>
				</div>
			</div>
			
			<div class = "row">
                <div class="col-xs-12 col-xs-offset-none col-md-12 col-md-offset-2">
				<form id="catMunicipios" name="catMunicipios" action="catMunicipios.php" onsubmit="return verificarFormulario()" method="post">
                	<div class = "form-group row">
                        <label for="txtMunicipioId" class="col-sm-12 col-md-2 col-form-label">Municipio</label>
                        <div class="col-sm-12 col-md-2">
                        <?php
                            echo('<input type="text" class="form-control" id="txtMunicipioId" name="txtMunicipioId" value="' . $msCodigo . '" readonly />'); 
                        ?>
                        </div>
                    </div>
                    
					<div class = "form-group row">
						<label for="cboDepartamento" class="col-sm-12 col-md-2 col-form-label">Departamento</label>
                        <div class="col-sm-12 col-md-5">
						<?php 
							echo('<select class="form-control" id="cboDepartamento" name="cboDepartamento">');

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
                        <label for="txtNombre" class="col-sm-12 col-md-2 col-form-label">Nombre</label>
                        <div class="col-sm-12 col-md-5">
                        <?php
                            echo('<input type="text" class="form-control" id="txtNombre" name="txtNombre" value="' . $msNombre . '" />'); 
                        ?>
                        </div>
                    </div>

					<div class = "row">
                    	<div class="col-auto col-xs-offset-none col-md-12 col-md-offset-2">
							<input type="submit" id="Guardar" name="Guardar" value="Guardar" class="btn btn-primary" />
                            <input type="button" id="Cancelar" name="Cancelar" value="Cancelar" class="btn btn-primary" onclick="location.href='gridMunicipios.php';"/>
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