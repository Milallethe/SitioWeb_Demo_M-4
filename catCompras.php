<?php
	session_start();
	if (!isset($_SESSION["gnVerifica"]) or $_SESSION["gnVerifica"] != 1)
	{
		echo('<meta http-equiv="Refresh" content="0;url=index.php"/>');
		exit('');
	}
	
	include ("masterWeb.php");
	require_once ("funciones/fxGeneral.php");
	require_once ("funciones/fxCompras.php");
	$mConexion = fxAbrirConexion();

	if (isset($_POST["txtVentaId"]))
	{
		$msCodigo = $_POST["txtVentaId"];
		$msProveedor = $_POST["cboProveedor"];
		$msProducto = $_POST["cboProducto"];
		$mdFecha = $_POST["dtpFecha"];
		$mnCantidad = $_POST["txnCantidad"];

		if ($msCodigo == ""){
			$msCodigo = fxAgregarCompras ($msProveedor, $msProducto, $mdFecha, $mnCantidad);
			fxBitacora ($_SESSION["gsUsuario"], $msCodigo, "Compras", "Agregar");
		}
		else{
			fxModificarCompras ($msCodigo, $msProveedor, $msProducto, $mdFecha, $mnCantidad);
			fxBitacora ($_SESSION["gsUsuario"], $msCodigo, "Compras", "Modificar");
		}
							
		?><meta http-equiv="Refresh" content="0;url=gridCompras.php"/><?php
	}
	else
	{
		if (isset($_POST["UCN"]))
			$msCodigo = $_POST["UCN"];
		else
			$msCodigo = "";
				
		if ($msCodigo != "")
		{
			$mDatos = fxObtieneCompras($msCodigo);
			$mFila = $mDatos->fetch();
			$msProveedor = $mFila["PROVEEDORES"];
			$msProducto = $mFila["CODPRODUCTO"];
			$mdFecha = $mFila["FECHA"];
			$mnCantidad = $mFila["CANTIDAD"];
		}
		else
		{
			$msProveedor = "";
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
					<div class="degradado"><strong>Compras</strong></div>
				</div>
			</div>
			
			<div class = "row">
                <div class="col-xs-12 col-xs-offset-none col-md-12 col-md-offset-2">
				<form id="catCompras" name="catCompras" action="catCompras.php" onsubmit="return verificarFormulario()" method="post">
                	<div class = "form-group row">
                        <label for="txtVentaId" class="col-sm-12 col-md-1 col-form-label">Compra</label>
                        <div class="col-sm-12 col-md-2">
                        <?php
                            echo('<input type="text" class="form-control" id="txtVentaId" name="txtVentaId" value="' . $msCodigo . '" readonly />'); 
                        ?>
                        </div>
                    </div>
                    
					
					<div class="form-group row">
    <label for="cboProveedor" class="col-sm-12 col-md-1 col-form-label">Proveedor</label>
    <div class="col-sm-12 col-md-5">
        <?php 
            echo('<select class="form-control" id="cboProveedor" name="cboProveedor">');

            // Inicialización de la variable msProveedor
            $msProveedor = $msProveedor ?? "";  // Si $msProveedor no está definida, se inicializa a ""

            // Consulta para obtener los proveedores
            $msConsulta = "SELECT CODPROVEEDOR, NOMBRE FROM PROVEEDORES ORDER BY CODPROVEEDOR";
            $mDatos = $mConexion->prepare($msConsulta);
            $mDatos->execute();

            // Recorrer los resultados de la consulta
            while ($mFila = $mDatos->fetch())
            {
                $msCodProveedor = $mFila["CODPROVEEDOR"];  // Variable corregida para el código del proveedor
                $msNomProveedor = $mFila["NOMBRE"];

                // Si no se ha seleccionado un proveedor aún, se selecciona el primero por defecto
                if ($msProveedor == "") {
                    echo("<option value='" . $msCodProveedor . "' selected>" . $msNomProveedor . "</option>");
                    $msProveedor = $msCodProveedor;
                } else {
                    if ($msProveedor == $msCodProveedor)
                        echo("<option value='" . $msCodProveedor . "' selected>" . $msNomProveedor . "</option>");
                    else
                        echo("<option value='" . $msCodProveedor . "'>" . $msNomProveedor . "</option>");
                }
            }

            echo('</select>');
        ?>
    </div>
</div>


					
					<div class = "form-group row">
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

								if ($msProducto == ""){
									echo("<option value='" . $msCodProducto . "' selected>" . $msNomProducto . "</option>");
									$msProducto = $msCodProducto;
								}
								else{
									if ($msProducto == $msCodProducto)
										echo("<option value='" . $msCodProducto . "' selected>" . $msNomProducto . "</option>");
									else
										echo("<option value='" . $msCodProducto . "'>" . $msNomProducto . "</option>");
								}
							}

							echo('</select>');
						?>
                        </div>
					</div>
					<div class = "form-group row">
						<label for="dtpFecha" class="col-sm-12 col-md-1 col-form-label">Fecha</label>
                        <div class="col-sm-12 col-md-2">
						<?php echo('<input type="date" class="form-control" id="dtpFecha" name="dtpFecha" value="' . $mdFecha . '" />'); ?>
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
                            <input type="button" id="Cancelar" name="Cancelar" value="Cancelar" class="btn btn-primary" onclick="location.href='gridCompras.php';"/>
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