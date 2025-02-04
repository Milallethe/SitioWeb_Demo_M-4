<?php
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
?>
<!DOCTYPE html>
<html lang="ES-NI">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Sitio web demostrativo."/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<link rel="icon" href="imagenes/logo.png" />
<link rel="stylesheet" href="css/bootstrap.css" />
<link rel="stylesheet" href="css/estilos.css" />
<link rel="stylesheet" href="css/easyui.css" />
<link rel="stylesheet" href="css/icon.css" />
<link rel="stylesheet" href="bootstrap/css/jquery.bootgrid.css" />

<script src="js/jquery-3.7.1.js"></script>
<script src="js/moderniz.2.8.1.js"></script>
<script src="js/jquery.easyui.min.js"></script>
<script src="bootstrap/js/bootstrap.bundle.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="bootstrap/lib/jquery-1.11.1.js"></script>
<title>Conexión PHP-MySQL</title>
</head>

<body>
<div class="divMenu">
    <div class="divTitulo">
        <img src="imagenes/logo.png" style="width:40%; align-self: center; margin-top: 10%; margin-bottom: 10%" alt="">
    </div>
    
    <ul>
        <li class="nav-item">
            <a href="gridClientes.php">Clientes</a>
        </li>
        <li class="nav-item">
            <a href="gridProveedores.php">Proveedores</a>
        </li>
        <li class="nav-item">
            <a href="gridProducto.php">Productos</a>
        </li>
        <li class="nav-item">
            <a href="gridCompras.php">Compras</a>
        </li>
        <li class="nav-item">
            <a href="gridVentas.php">Ventas</a>
        </li>
        <li class="nav-item">
            <a href="gridUsuarios.php">Usuarios</a>
        </li>
        <li class="nav-item">
            <a href="gridDepartamentos.php">Departamentos</a>
        </li>
        <li class="nav-item">
            <a href="gridMunicipios.php">Municipios</a>
        </li>
    </ul>
</div>