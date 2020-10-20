<?php
	echo "<h1>Biblioteca</h1>";
	// Mostramos info del usuario logueado (si hay alguno)
	if (isset($_SESSION['idUsuario'])) {
		echo "<p>Hola, ".$_SESSION['nombreUsuario']."</p>";
		echo "<p align='right'><img width='50' src='".$_SESSION['fotografiaUsuario']."'></p>";
	}
	// Mostramos mensaje de error o de información (si hay alguno)
	if (isset($data['msjError'])) {
		echo "<p style='color:red'>".$data['msjError']."</p>";
	}
	if (isset($data['msjInfo'])) {
		echo "<p style='color:blue'>".$data['msjInfo']."</p>";
	}

	// Enlace a "Iniciar sesión" o "Cerrar sesión"
	if (isset($_SESSION["idUsuario"])) {
		echo "<p><a href='index.php?action=cerrarSesion'>Cerrar sesión</a></p>";
	}
	else {
		echo "<p><a href='index.php?action=mostrarFormularioLogin'>Iniciar sesión</a></p>";
	}
	
	// Primero, el formulario de búsqueda
	echo "<form action='index.php'>
			<input type='hidden' name='action' value='buscarLibros'>
           	<input type='text' name='textoBusqueda'>
			<input type='submit' value='Buscar'>
            </form><br>";
	
	if (count($data['listaLibros']) > 0) {

	// Ahora, la tabla con los datos de los libros
	echo "<table border ='1'>";
	foreach($data['listaLibros'] as $libro) {
			echo "<tr>";
			echo "<td>" . $libro->titulo . "</td>";
			echo "<td>" . $libro->genero . "</td>";
			echo "<td>" . $libro->numPaginas . "</td>";
			echo "<td>" . $libro->nombre . "</td>";
			echo "<td>" . $libro->apellido . "</td>";
			// Los botones "Modificar" y "Borrar" solo se muestran si hay una sesión iniciada
			if (isset($_SESSION["idUsuario"])) {
				echo "<td><a href='index.php?action=formularioModificarLibro&idLibro=" . $libro->idLibro . "'>Modificar</a></td>";
				echo "<td><a href='index.php?action=borrarLibro&idLibro=" . $libro->idLibro . "'>Borrar</a></td>";
			}
			echo "</tr>";
	}
	echo "</table>";
	} 
	else {
		// La consulta no contiene registros
		echo "No se encontraron datos";
	}
	// El botón "Nuevo libro" solo se muestra si hay una sesión iniciada
	if (isset($_SESSION["idUsuario"])) {
		echo "<p><a href='index.php?action=formularioInsertarLibros'>Nuevo</a></p>";
	}
