<?php
			// Comprobamos si hay una sesión iniciada o no
				echo "<h1>Alta de libros</h1>";

				// Creamos el formulario con los campos del libro
				echo "<form action = 'index.php' method = 'get'>
						Título:<input type='text' name='titulo'><br>
						Género:<input type='text' name='genero'><br>
						País:<input type='text' name='pais'><br>
						Año:<input type='text' name='ano'><br>
						Número de páginas:<input type='text' name='numPaginas'><br>";

				// Añadimos un selector para el id del autor o autores
				echo "Autores: <select name='autor[]' multiple size='3'>";
				foreach ($data['listaAutores'] as $autor) {
					echo "<option value='" . $autor->idPersona . "'>" . $autor->nombre . " " . $autor->apellido . "</option>";
				}
				echo "</select>";
				echo "<a href='index.php?action=formularioInsertarAutores'>Añadir nuevo</a><br>";

				// Finalizamos el formulario
				echo "  <input type='hidden' name='action' value='insertarLibro'>
						<input type='submit'>
					</form>";
				echo "<p><a href='index.php'>Volver</a></p>";
