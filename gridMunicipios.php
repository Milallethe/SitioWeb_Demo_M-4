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

	if (isset($_POST["UCN"])) {
		fxBorrarMunicipio ($_POST["UCN"]);
		fxBitacora ($_SESSION["gsUsuario"], $_POST["UCN"], "MUNICIPIOS", "Borrar");
	}
?>

<div class="contenedor">
   	<div class="divContenido">
	   	<div id="lateral">
			<label id="agregar" data-toggle="tooltip" data-placement="top" title="Agregar"><img src="imagenes/btnLateralAgregar.png" height="80%" style="cursor:pointer" /></label>
			<label id="modificar" data-toggle="tooltip" data-placement="top" title="Editar"><img src="imagenes/btnLateralEditar.png" height="80%" style="cursor:pointer" /></label>
			<label id="borrar" data-toggle="tooltip" data-placement="top" title="Borrar"><img src="imagenes/btnLateralBorrar.png" height="80%" style="cursor:pointer" /></label>
		</div>

       	<div class="row">
           	<div class="col-md-12">
				<button id="append" type="button" class="btn btn-primary">Agregar</button>
				<button id="edit" type="button" class="btn btn-primary">Editar</button>
				<button id="remove" type="button" class="btn btn-primary">Borrar</button>
                    
            	<table id="grid" class="table table-condensed table-hover table-striped" data-selection="true" data-multi-select="false" data-row-select="true" data-keep-selection="true">
                   	<thead>
                    	<tr>
                            <th data-column-id="CODMUNICIPIO" data-identifier="true" data-align="left">Municipio</th>
                            <th data-column-id="DEPARTAMENTO" data-align="left">Departamento</th>
							<th data-column-id="NOMBRE" data-align="left">Nombre</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
							$mDatos = fxObtieneTodosMunicipios();
							
                            while ($mFila = $mDatos->fetch())
							{
								echo ("<tr>");
								echo ("<td>" . $mFila["CODMUNICIPIO"] . "</td>");
								echo ("<td>" . $mFila["DEPARTAMENTO"] . "</td>");
								echo ("<td>" . $mFila["NOMBRE"] . "</td>");
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
		$(window).scroll(function() {
			var scroll = $(window).scrollTop();
			if (scroll >= 100) {
			$("#lateral").addClass("entra");
			} else {
			$("#lateral").removeClass("entra");
			}
		});
	});

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
			$.redirect("catMunicipios.php", "POST");
		});

		$("#agregar").on("click", function() {
			$.redirect("catMunicipios.php", "POST");
		});

		$("#remove").on("click", function() {
			if ($.trim($("#grid").bootgrid("getSelectedRows")) != "")
			{
				var codMunicipio = $.trim($("#grid").bootgrid("getSelectedRows"));
				$.redirect("gridMunicipios.php", {UCN: codMunicipio}, "POST");
			}
		});

		$("#borrar").on("click", function() {
			if ($.trim($("#grid").bootgrid("getSelectedRows")) != "")
			{
				var codMunicipio = $.trim($("#grid").bootgrid("getSelectedRows"));
				$.redirect("gridMunicipios.php", {UCN: codMunicipio}, "POST");
			}
		});
			
		$("#edit").on("click", function() {
			if ($.trim($("#grid").bootgrid("getSelectedRows")) != "")
			{
				var codMunicipio = $.trim($("#grid").bootgrid("getSelectedRows"));
				$.redirect("catMunicipios.php", {UCN: codMunicipio}, "POST");
			}
		});

		$("#modificar").on("click", function() {
			if ($.trim($("#grid").bootgrid("getSelectedRows")) != "")
			{
				var codMunicipio = $.trim($("#grid").bootgrid("getSelectedRows"));
				$.redirect("catMunicipios.php", {UCN: codMunicipio}, "POST");
			}
		});
	});
</script>
</body>
</html>