<script>
	// **** Petición y respuesta AJAX con JS tradicional ****

	peticionAjax = new XMLHttpRequest();

	function borrarPorAjax(idLibro) {
		if (confirm("¿Está seguro de que desea borrar el libro?")) {
			idLibroGlobal = idLibro;
			peticionAjax.onreadystatechange = borradoLibroCompletado;
			peticionAjax.open("GET", "index.php?action=borrarLibroAjax&idLibro=" + idLibro, true);
			peticionAjax.send(null);
		}
	}

	function borradoLibroCompletado() {
		if (peticionAjax.readyState == 4) {
			if (peticionAjax.status == 200) {
				idLibro = peticionAjax.responseText;
				if (idLibro == -1) {
					document.getElementById('msjError').innerHTML = "Ha ocurrido un error al borrar el libro";
				} else {
					document.getElementById('msjInfo').innerHTML = "Libro borrado con éxito";
					document.getElementById('libro' + idLibro).remove();
				}
			}
		}
	}

	// **** Petición y respuesta AJAX con jQuery ****

	$(document).ready(function() {
		$(".btnBorrar").click(function() {
			if (confirm("¿Está seguro de que desea borrar el libro?")) {
				$.get("index.php?action=borrarLibroAjax&idLibro=" + this.id, null, function(idLibroBorrado) {
					if (idLibroBorrado == -1) {
						$('#msjError').html("Ha ocurrido un error al borrar el libro");
					} else {
						$('#msjInfo').html("Libro borrado con éxito");
						$('#libro' + idLibroBorrado).remove();
					}
				});
			}
		});
	});
</script>



<?php
echo "<h1>Biblioteca</h1>";
// Mostramos info del usuario logueado (si hay alguno)
if ($this->seguridad->haySesionIniciada()) {
	echo "<p>Hola, " . $this->seguridad->get("nombreUsuario") . "</p>";
	echo "<p align='right'><img width='50' src='" . $this->seguridad->get("fotografiaUsuario") . "'></p>";
}
// Mostramos mensaje de error o de información (si hay alguno)
if (isset($data['msjError'])) {
	echo "<p style='color:red' id='msjError'>" . $data['msjError'] . "</p>";
} else {
	echo "<p style='color:red' id='msjError'></p>";
}
if (isset($data['msjInfo'])) {
	echo "<p style='color:blue' id='msjInfo'>" . $data['msjInfo'] . "</p>";
} else {
	echo "<p style='color:blue' id='msjInfo'></p>";
}


// Enlace a "Iniciar sesión" o "Cerrar sesión"
if (isset($_SESSION["idUsuario"])) {
	echo "<p><a href='index.php?action=cerrarSesion'>Cerrar sesión</a></p>";
} else {
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
	foreach ($data['listaLibros'] as $libro) {
		echo "<tr id='libro" . $libro->idLibro . "'>";
		echo "<td>" . $libro->titulo . "</td>";
		echo "<td>" . $libro->genero . "</td>";
		echo "<td>" . $libro->numPaginas . "</td>";
		echo "<td>" . $libro->nombre . "</td>";
		echo "<td>" . $libro->apellido . "</td>";
		// Los botones "Modificar" y "Borrar" solo se muestran si hay una sesión iniciada
		if ($this->seguridad->haySesionIniciada()) {
			echo "<td><a href='index.php?action=formularioModificarLibro&idLibro=" . $libro->idLibro . "'>Modificar</a></td>";
			echo "<td><a href='index.php?action=borrarLibro&idLibro=" . $libro->idLibro . "'>Borrar mediante enlace</a></td>";
			echo "<td><a href='#' onclick='borrarPorAjax(" . $libro->idLibro . ")'>Borrar por Ajax/JS</a></td>";
			echo "<td><a href='#' class='btnBorrar' id='" . $libro->idLibro . "'>Borrar por Ajax/jQuery</a></td>";
		}
		echo "</tr>";
	}
	echo "</table>";
} else {
	// La consulta no contiene registros
	echo "No se encontraron datos";
}
// El botón "Nuevo libro" solo se muestra si hay una sesión iniciada
if (isset($_SESSION["idUsuario"])) {
	echo "<p><a href='index.php?action=formularioInsertarLibros'>Nuevo</a></p>";
}
