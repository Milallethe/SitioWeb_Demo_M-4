<?php
session_start();
if (!isset($_SESSION["gnVerifica"]) or $_SESSION["gnVerifica"] != 1)
{
    echo('<meta http-equiv="Refresh" content="0;url=index.php"/>');
 exit('');
} 
	
	include ("masterWeb.php");
	require_once ("funciones/fxGeneral.php");
	require_once ("funciones/fxVentas.php");
	$mConexion = fxAbrirConexion();

	if (isset($_POST["txtVentaId"]))
	{
		$msCodigo = $_POST["txtVentaId"];
		$msCliente = $_POST["cboCliente"];
		$msProducto = $_POST["cboProducto"];
		$mdFecha = $_POST["dtpFecha"];
		$mnCantidad = $_POST["txnCantidad"];

		if ($msCodigo == ""){
			$msCodigo = fxAgregarVenta ($msCliente, $msProducto, $mdFecha, $mnCantidad);
			fxBitacora ($_SESSION["gsUsuario"], $msCodigo, "VENTAS", "Agregar");
		}
		else{
			fxModificarVenta ($msCodigo, $msCliente, $msProducto, $mdFecha, $mnCantidad);
			fxBitacora ($_SESSION["gsUsuario"], $msCodigo, "VENTAS", "Modificar");
		}
							
		?><meta http-equiv="Refresh" content="0;url=gridVentas.php"/><?php
	}
	else
	{
		if (isset($_POST["UCN"]))
			$msCodigo = $_POST["UCN"];
		else
			$msCodigo = "";
				
		if ($msCodigo != "")
		{
			$mDatos = fxObtieneVenta($msCodigo);
			$mFila = $mDatos->fetch();
			$msCliente = $mFila["CLIENTE"];
			$msProducto = $mFila["PRODUCTO"];
			$mdFecha = $mFila["FECHA"];
			$mnCantidad = $mFila["CANTIDAD"];
		}
		else
		{
			$msCliente = "";
			$msProducto = "";
			$mdFecha = date('Y-m-d');
			$mnCantidad = 0;
		}
	}
	?>
    <div class="contenedor text-left">
    	<div class="divContenido">
			<div class = "row">
				<div class="col-xs-12 col-md-11">
					<div class="degradado"><strong>Ventas</strong></div>
				</div>
			</div>
			
			<div class = "row">
                <div class="col-xs-12 col-xs-offset-none col-md-12 col-md-offset-2">
				<form id="procVentas" name="procVentas" action="procVentas.php" onsubmit="return verificarFormulario()" method="post">
                	<div class = "form-group row">
                        <label for="txtVentaId" class="col-sm-12 col-md-1 col-form-label">Venta</label>
                        <div class="col-sm-12 col-md-2">
                        <?php
                            echo('<input type="text" class="form-control" id="txtVentaId" name="txtVentaId" value="' . $msCodigo . '" readonly />'); 
                        ?>
                        </div>
                    </div>
                    
                    <div class = "form-group row">
						<label for="dtpFecha" class="col-sm-12 col-md-1 col-form-label">Fecha</label>
                        <div class="col-sm-12 col-md-2">
						<?php echo('<input type="date" class="form-control" id="dtpFecha" name="dtpFecha" value="' . $mdFecha . '" />'); ?>
                        </div>
					</div>
					
					<div class="form-group row">
						<label for="cboCliente" class="col-sm-12 col-md-1 col-form-label">Cliente</label>
						<div class="col-sm-12 col-md-5">
							<?php 
							echo('<select class="form-control" id="cboCliente" name="cboCliente">');
							$msConsulta = "select CODCLIENTE, concat_ws(' ', NOMBRE, APELLIDO) as CLIENTE from CLIENTES order by NOMBRE";
							$mDatos = $mConexion->prepare($msConsulta);
							$mDatos->execute();
							while ($mFila = $mDatos->fetch()) {
								$msCodCliente = $mFila["CODCLIENTE"];
								$msNomCliente = $mFila["CLIENTE"];
								if ($msCliente == $msCodCliente) {
									echo("<option value='" . $msCodCliente . "' selected>" . $msNomCliente . "</option>");
								} else
								{
									echo("<option value='" . $msCodCliente . "'>" . $msNomCliente . "</option>");
								}
							}
							echo('</select>');
							?>
						</div>
					</div>

					<div class="form-group row">
						<label for="cboProducto" class="col-sm-12 col-md-1 col-form-label">Producto</label>
						<div class="col-sm-12 col-md-5">
							<?php 
							echo('<select class="form-control" id="cboProducto" name="cboProducto">');
							$msConsulta = "select CODPRODUCTO, DESCRIPCION from PRODUCTOS order by DESCRIPCION";
							$mDatos = $mConexion->prepare($msConsulta);
							$mDatos->execute();
							while ($mFila = $mDatos->fetch()) 
							{
								$msCodProducto = $mFila["CODPRODUCTO"];
								$msNomProducto = $mFila["DESCRIPCION"];
								if ($msProducto == $msCodProducto) {
									 echo("<option value='" . $msCodProducto . "' selected>" . $msNomProducto . "</option>");
									}
									else
									{
										echo("<option value='" . $msCodProducto . "'>" . $msNomProducto . "</option>");
									}
								}
								echo('</select>');
								?>	
							</div>
						</div>
						
						<div class = "form-group row">
							<label for="txnCantidad" class="col-sm-12 col-md-1 col-form-label">Cantidad</label>
							<div class="col-sm-12 col-md-2">
								<?php
								echo('<input type="number" class="form-control" id="txnCantidad" name="txnCantidad" value="' . $mnCantidad . '" />'); 
								?>
							</div>
						</div>
						
						<div class = "row">
							<div class="col-auto col-xs-offset-none col-md-12 col-md-offset-1">
								<input type="submit" id="Guardar" name="Guardar" value="Guardar" class="btn btn-primary" />
                            	<input type="button" id="Cancelar" name="Cancelar" value="Cancelar" class="btn btn-primary" onclick="location.href='gridVentas.php';"/>
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
		if(document.getElementById('txnCantidad').value<=0)
		{
			$.messager.alert('Conexión PHP-MySQL','Falta la Catidad.','warning');
			return false;
		}

		return true;
	}
</script>