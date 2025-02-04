<?php
session_start();
if (!isset($_SESSION["gnVerifica"]) or $_SESSION["gnVerifica"] != 1)
{
    echo('<meta http-equiv="Refresh" content="0;url=index.php"/>');
 exit('');
}
	include ("masterWeb.php");
	require_once ("funciones/fxGeneral.php");
	require_once ("funciones/fxcompras.php");

	if (isset($_POST["UCN"])) {
		fxBorrarcompras($_POST["UCN"]);
		fxBitacora ($_SESSION["gsUsuario"], $_POST["UCN"], "compras", "Borrar");
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
                            <th data-column-id="CODCOMPRA" data-identifier="true" data-align="left">compras</th>
                            <th data-column-id="CODPROVEEDOR" data-align="left">CODPROVEEDOR</th>
                            <th data-column-id="CODPRODUCTO" data-align="left">CODPRODUCTO</th>

                            <th data-column-id="FECHA" data-align="left">FECHA</th>
                            <th data-column-id="CANTIDAD" data-align="left">CANTIDAD</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
							$mDatos = fxObtieneTodoscompras();
							
                            while ($mFila = $mDatos->fetch())
							{
								echo ("<tr>");
								echo ("<td>" . $mFila["CODCOMPRA"] . "</td>");
								echo ("<td>" . $mFila["CODPROVEEDOR"] . "</td>");
                                echo ("<td>" . $mFila["CODPRODUCTO"] . "</td>");

                                echo ("<td>" . $mFila["FECHA"] . "</td>");
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
			$.redirect("catcompras.php", "POST");
		});

		$("#remove").on("click", function() {
			if ($.trim($("#grid").bootgrid("getSelectedRows")) != "")
			{
				var CODCOMPRA = $.trim($("#grid").bootgrid("getSelectedRows"));
				$.redirect("gridcompras.php", {UCN: CODCOMPRA}, "POST");
			}
		});
			
		$("#edit").on("click", function() {
			if ($.trim($("#grid").bootgrid("getSelectedRows")) != "")
			{
				var CODCOMPRA = $.trim($("#grid").bootgrid("getSelectedRows"));
				$.redirect("catcompras.php", {UCN: CODCOMPRA}, "POST");
			}
		});
	});
</script>
</body>
</html>