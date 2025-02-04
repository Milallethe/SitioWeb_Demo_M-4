<?php
require_once ("fxGeneral.php");

/**********Llenar el combo de los Municipios**********/
if (isset($_POST["departamento"]))
{
	$m_cnx_MySQL = fxAbrirConexion();
	$msCodigo = $_POST["departamento"];
	$msConsulta = "Select CODMUNICIPIO, NOMBRE from MUNICIPIOS where CODDEPARTAMENTO = ? order by NOMBRE";
	$mDatos = $m_cnx_MySQL->prepare($msConsulta);
	$mDatos->execute([$msCodigo]);
	$mnRegistros = $mDatos->rowCount();
	$msResultado = "";

	if ($mnRegistros > 0)
	{
		while ($mFila = $mDatos->fetch())
		{
			$msResultado .= "<option value='" . $mFila["CODMUNICIPIO"] . "'>" . $mFila["NOMBRE"] . "</option>";
		}
	}
	
	echo $msResultado;
}
?>