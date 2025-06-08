<?php
/*
Archivo:  login.php
Objetivo: verifica correo y contraseña contra repositorio a través de clases
*/
include_once("modelo/Usuario.php");
session_start();

$sErr = "";
$oUsu = new Usuario();

if (isset($_POST["txtCve"], $_POST["txtPwd"])) {
	$oUsu->setCorreo($_POST["txtCve"]);
	$oUsu->setContrasena($_POST["txtPwd"]);
	
	try {
		if ($oUsu->buscarCorreoYContrasena()) { // nombre actualizado
			$_SESSION["usuario"] = serialize($oUsu);
			header("Location: inicio.php");
			exit;
		} else {
			header("Location: logeo.php");
		}
	} catch (Exception $e) {
		die("Error en la base de datos: " . $e->getMessage());
	}
} else {
	$sErr = "Faltan datos.";
}

/*header("Location: error.php?sError=" . urlencode($sErr));*/
?>
