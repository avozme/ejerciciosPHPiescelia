<?php
	/* BIBLIOTECA VERSIÓN 4

	Características de esta versión:
	- Código con arquitectura MVC EN 5 CAPAS
	- Con seguridad
	- Con sesiones y control de acceso
	- Sin reutilización de código
	*/
	session_start();

	// Instanciamos el objeto controlador
	include_once("controlador.php");
	$controlador = new Controlador();
	
	// Recuperamos la acción de la URL. Si no hay, asignamos una por defecto
	if (isset($_REQUEST["action"])) {
		$action = $_REQUEST["action"];
	} else {
		$action = "mostrarListaLibros";  // Acción por defecto
	}

	// Ejecutamos el método llamado como la acción del controlador
	$controlador->$action();
