<?php
	date_default_timezone_set("America/Managua");

	function fxAbrirConexion()
	{
		$msUsuario = "usuarioClases";
		$msClave = "Pass123";
		$msBase = "demo";
		$conexion = new PDO('mysql:host=localhost;dbname='.$msBase, $msUsuario, $msClave);
		$conexion->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
		$conexion->exec("set names utf8");
		return $conexion;
	}

	function fxBitacora($msUsuario, $msRegistro, $msTabla, $msOperacion)
	{
		$mConexion = fxAbrirConexion();
		$mdFechaHoy = date('Y-m-d H:i:s');
		$msConsulta = "insert into BITACORA (USUARIO, FECHAHORA, REGISTRO, TABLA, OPERACION) values (?, ?, ?, ?, ?)";
		$mResultado = $mConexion->prepare($msConsulta);
		$mResultado->execute([$msUsuario, $mdFechaHoy, $msRegistro, $msTabla, $msOperacion]);
	}
?>