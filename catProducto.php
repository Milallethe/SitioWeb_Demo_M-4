<?php
	session_start();
	if (!isset($_SESSION["gnVerifica"]) or $_SESSION["gnVerifica"] != 1)
	{
		echo('<meta http-equiv="Refresh" content="0;url=index.php"/>');
		exit('');
	}
	
	include ("masterWeb.php");
	require_once ("funciones/fxGeneral.php");
	require_once ("funciones/fxProducto.php");

	if (isset($_POST["txtProductoId"]))
	{
		$msCodigo = $_POST["txtProductoId"];
		$msDESCRIPCION = $_POST["txtDESCRIPCION"];
		$msEXISTENCIA = $_POST["txtEXISTENCIA"];

		if ($msCodigo == ""){
			$msCodigo = fxAgregarProducto ($msDESCRIPCION, $msEXISTENCIA);
			fxBitacora ($_SESSION["gsUsuario"], $msCodigo, "Producto", "Agregar");
		}
		else{
			fxModificarProducto($msCodigo, $msDESCRIPCION, $msEXISTENCIA);
			fxBitacora ($_SESSION["gsUsuario"], $msCodigo, "Producto", "Modificar");
		}
							
		?><meta http-equiv="Refresh" content="0;url=gridProducto.php"/><?php
	}
	else
	{
		if (isset($_POST["UCN"]))
			$msCodigo = $_POST["UCN"];
		else
			$msCodigo = "";
				
		if ($msCodigo != "")
		{
			$mDatos = fxObtieneProducto($msCodigo);
			$mFila = $mDatos->fetch();
			$msDESCRIPCION = $mFila["DESCRIPCION"];
			$msEXISTENCIA = $mFila["EXISTENCIA"];
		}
		else
		{
			$msDESCRIPCION = "";
			$msEXISTENCIA = "";
		}
	}
	?>
    <div class="contenedor text-left">
    	<div class="divContenido">
			<div class = "row">
				<div class="col-xs-12 col-md-11">
					<div class="degradado"><strong>Producto</strong></div>
				</div>
			</div>
			
			<div class = "row">
                <div class="col-xs-12 col-xs-offset-none col-md-12 col-md-offset-2">
				<form id="catProducto" name="catProducto" action="catProducto.php" onsubmit="return verificarFormulario()" method="post">
                	<div class = "form-group row">
                        <label for="txtProductoId" class="col-sm-12 col-md-1 col-form-label">Producto</label>
                        <div class="col-sm-12 col-md-2">
                        <?php
                            echo('<input type="text" class="form-control" id="txtProductoId" name="txtProductoId" value="' . $msCodigo . '" readonly />'); 
                        ?>
                        </div>
                    </div>
                    
                    <div class = "form-group row">
						<label for="txtDESCRIPCION" class="col-sm-12 col-md-1 col-form-label">DESCRIPCION</label>
                        <div class="col-sm-12 col-md-5">
						<?php echo('<input type="text" class="form-control" id="txtDESCRIPCION" name="txtDESCRIPCION" value="' . $msDESCRIPCION . '" />'); ?>
                        </div>
					</div>
					
					<div class = "form-group row">
						<label for="txtEXISTENCIA" class="col-sm-12 col-md-1 col-form-label">EXISTENCIA</label>
                        <div class="col-sm-12 col-md-3">
						<?php echo('<input type="number" class="form-control" id="txtEXISTENCIA" name="txtEXISTENCIA" value="' . $msEXISTENCIA . '" />'); ?>
                        </div>
                    </div>

					<div class = "row">
                    	<div class="col-auto col-xs-offset-none col-md-12 col-md-offset-1">
							<input type="submit" id="Guardar" name="Guardar" value="Guardar" class="btn btn-primary" />
                            <input type="button" id="Cancelar" name="Cancelar" value="Cancelar" class="btn btn-primary" onclick="location.href='gridProducto.php';"/>
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
		if(document.getElementById('txtDESCRIPCION').value=="")
		{
			$.messager.alert('Conexión PHP-MySQL','Falta el DESCRIPCION.','warning');
			return false;
		}

		if(document.getElementById('txtEXISTENCIA').value=="")
		{
			$.messager.alert('Conexión PHP-MySQL','Falta el EXISTENCIA.','warning');
			return false;
		}
		
		return true;
	}
</script>