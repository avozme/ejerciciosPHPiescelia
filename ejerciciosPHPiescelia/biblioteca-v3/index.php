<!-- BIBLIOTECA VERSIÓN 3

     Características de esta versión:
       - Código con arquitectura MVC
       - Con seguridad
       - Con sesiones y control de acceso
       - Sin reutilización de código
-->

<?php

	// Creamos los objetos vista y modelos
	include_once("controlador.php");
	$controlador = new Controlador();
	
	if (isset($_REQUEST["action"])) {
		$action = $_REQUEST["action"];
	} else {
		$action = "mostrarListaLibros";  // Acción por defecto
	}

	$controlador->$action();
