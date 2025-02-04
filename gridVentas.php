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

	if (isset($_POST["UCN"])) {
		fxBorrarVenta ($_POST["UCN"]);
		fxBitacora ($_SESSION["gsUsuario"], $_POST["UCN"], "VENTAS", "Borrar");
	}
?>

<div class="contenedor">
   	<div class="divContenido">
       	<div class="row">
           	<div class="col-md-12">
				<button id="append" type="button" class="btn btn-primary">Agregar</button>
				<button id="edit" type="button" class="btn btn-primary">Editar</button>
				<button id="remove" type="button" class="btn btn-primary">Borrar</button>
                    
            	<table id="grid" class="table table-condensed table-hover table-striped" data-selection="true" data-multi-select="false" data-row-select="true" data-keep-selection="true">
                   	<thead>
                    	<tr>
                            <th data-column-id="CODVENTA" data-identifier="true" data-align="left">Venta</th>
                            <th data-column-id="FECHA" data-align="left">Fecha</th>
							<th data-column-id="CLIENTE" data-align="left">Cliente</th>
							<th data-column-id="PRODUCTO" data-align="left">Producto</th>
							<th data-column-id="CANTIDAD" data-align="left">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
							$mDatos = fxObtieneTodosVentas();
							
                            while ($mFila = $mDatos->fetch())
							{
								echo ("<tr>");
								echo ("<td>" . $mFila["CODVENTA"] . "</td>");
								$mdFecha = date_create_from_format('Y-m-d', $mFila["FECHA"]);
								echo ("<td>" . date_format($mdFecha, 'd-m-Y') . "</td>");
								echo ("<td>" . $mFila["CLIENTE"] . "</td>");
								echo ("<td>" . $mFila["PRODUCTO"] . "</td>");
								echo ("<td>" . $mFila["CANTIDAD"] . "</td>");
								echo ("</tr>");
							}
						?>
                    </tbody>
                </table>
        	</div>
        </div>
    </div>
</div>

<script src="bootstrap/lib/jquery-1.11.1.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="bootstrap/dist/jquery.bootgrid.js"></script>
<script src="js/jquery.redirect.js"></script>
<script>
    $(function() {
		function init() {
			$("#grid").bootgrid({
				formatters: {
					"link": function(column, row) {
						return "<a href=\"#\">" + column.id + ": " + row.id + "</a>";
					}
				},
				rowCount: [-1, 10, 50, 75]
			});
		}

        init();

		$("#append").on("click", function() {
			$.redirect("procVentas.php", "POST");
		});

		$("#remove").on("click", function() {
			if ($.trim($("#grid").bootgrid("getSelectedRows")) != "")
			{
				var codVenta = $.trim($("#grid").bootgrid("getSelectedRows"));
				$.redirect("gridVentas.php", {UCN: codVenta}, "POST");
			}
		});
			
		$("#edit").on("click", function() {
			if ($.trim($("#grid").bootgrid("getSelectedRows")) != "")
			{
				var codVenta = $.trim($("#grid").bootgrid("getSelectedRows"));
				$.redirect("procVentas.php", {UCN: codVenta}, "POST");
			}
		});
	});
</script>
</body>
</html>