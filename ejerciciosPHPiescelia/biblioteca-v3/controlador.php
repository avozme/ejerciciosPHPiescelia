<?php
	include_once("vista.php");
	include_once("modelos/usuario.php");
	include_once("modelos/libro.php");
	include_once("modelos/persona.php");

	// Creamos los objetos vista y modelos
 
 
    class Controlador {
 
        private $vista, $usuario, $libro, $persona;

        public function __construct() {
            $this->vista = new Vista();
            $this->usuario = new Usuario();
            $this->libro = new Libro();
            $this->persona = new Persona();
        }

        public function mostrarFormularioLogin() {
			$this->vista->mostrar("usuario/formularioLogin");
        }
 
        public function procesarLogin() {
			$usr = $_REQUEST["usr"];
			$pass = $_REQUEST["pass"];

			$result = $this->usuario->buscarUsuario($usr, $pass);
			
			if ($result) {
				// De momento, dejamos aquí este echo. Ya lo quitaremos
				echo "<script>location.href = 'index.php'</script>";
			} 
			else {
				// Error al iniciar la sesión
				$data['msjError'] = "Nombre de usuario o contraseña incorrectos";
				$this->vista->mostrar("usuario/formularioLogin", $data);
			}
        }

		public function cerrarSesion() {
			session_destroy();
			$data['msjInfo'] = "Sesión cerrada correctamente";
			$this->vista->mostrar("usuario/formularioLogin", $data);
        }
			
			// --------------------------------- MOSTRAR LISTA DE LIBROS ----------------------------------------

        public function mostrarListaLibros() {
			$data['listaLibros'] = $this->libro->getAll();
			$this->vista->mostrar("libro/mostrarListaLibros", $data);
        }

			// --------------------------------- FORMULARIO ALTA DE LIBROS ----------------------------------------

        public function formularioInsertarLibros() {
			if (isset($_SESSION["idUsuario"])) {
				// Primero, accedemos al modelo de personas para obtener la lista de autores
				$data['listaAutores'] = $this->persona->getAll();
				$this->vista->mostrar('libro/formularioInsertarLibro', $data);
			} else {
				$data['msjError'] = "No tienes permisos para hacer eso";
				$this->vista->mostrar("usuario/formularioLogin", $data);
			}
        }
		

			// --------------------------------- INSERTAR LIBROS ----------------------------------------

        public function insertarLibro() {
			
			if (isset($_SESSION["idUsuario"])) {
				// Vamos a procesar el formulario de alta de libros
				// Primero, recuperamos todos los datos del formulario
				$titulo = $_REQUEST["titulo"];
				$genero = $_REQUEST["genero"];
				$pais = $_REQUEST["pais"];
				$ano = $_REQUEST["ano"];
				$numPaginas = $_REQUEST["numPaginas"];
				$autores = $_REQUEST["autor"];
				$result = $this->libro->insert($titulo, $genero, $pais, $ano, $numPaginas);

				// Lanzamos el INSERT contra la BD.
				if ($result == 1) {
					// Si la inserción del libro ha funcionado, continuamos insertando en la tabla "escriben"
					// Tenemos que averiguar qué idLibro se ha asignado al libro que acabamos de insertar
					$ultimoId = $this->libro->getLastId();
					// Ya podemos insertar todos los autores junto con el libro en "escriben"
					foreach ($autores as $idAutor) {
						$this->persona->escribe($ultimoId, $idAutor);
					}
					$data['msjInfo'] = "Libro insertado con éxito";
				} else {
					// Si la inserción del libro ha fallado, mostramos mensaje de error
					$data['msjError'] = "Ha ocurrido un error al insertar el libro. Por favor, inténtelo más tarde.";
				}
				$data['listaLibros'] = $this->libro->getAll();
				$this->vista->mostrar("libro/mostrarListaLibros", $data);
			} else {
				$data['msjError'] = "No tienes permisos para hacer eso";
				$this->vista->mostrar("usuario/formularioLogin", $data);
			}
				
		}

			// --------------------------------- BORRAR LIBROS ----------------------------------------

        public function borrarLibro() {
			if (isset($_SESSION["idUsuario"])) {
				$idLibro = $_REQUEST["idLibro"];
				$result = $this->libro->delete($idLibro);
				if ($result == 0) {
					$data['msjError'] = "Ha ocurrido un error al borrar el libro. Por favor, inténtelo de nuevo";
				} else {
					$data['msjInfo'] = "Libro borrado con éxito";
				}
				$data['listaLibros'] = $this->libro->getAll();
				$this->vista->mostrar("libro/mostrarListaLibros", $data);
			} else {
				$data['msjError'] = "No tienes permisos para hacer eso";
				$this->vista->mostrar("usuario/formularioLogin", $data);
			}

		}

		// --------------------------------- FORMULARIO MODIFICAR LIBROS ----------------------------------------

		public function formularioModificarLibro() {
			if (isset($_SESSION["idUsuario"])) {
				echo "<h1>Modificación de libros</h1>";

				// Recuperamos el id del libro que vamos a modificar y sacamos el resto de sus datos de la BD
				$idLibro = $_REQUEST["idLibro"];
				$result = $db->query("SELECT * FROM libros WHERE libros.idLibro = '$idLibro'");
				$this->libro = $result->fetch_object();

				// Creamos el formulario con los campos del libro
				// y lo rellenamos con los datos que hemos recuperado de la BD
				echo "<form action = 'index.php' method = 'get'>
						<input type='hidden' name='idLibro' value='$idLibro'>
						Título:<input type='text' name='titulo' value='$this->libro->titulo'><br>
						Género:<input type='text' name='genero' value='$this->libro->genero'><br>
						País:<input type='text' name='pais' value='$this->libro->pais'><br>
						Año:<input type='text' name='ano' value='$this->libro->ano'><br>
						Número de páginas:<input type='text' name='numPaginas' value='$this->libro->numPaginas'><br>";

				// Vamos a añadir un selector para el id del autor o autores.
				// Para que salgan preseleccionados los autores del libro que estamos modificando, vamos a buscar
				// también a esos autores.
				$todosLosAutores = $db->query("SELECT * FROM personas");  // Obtener todos los autores
				$autoresLibro = $db->query("SELECT personas.idPersona FROM libros
						INNER JOIN escriben ON libros.idLibro = escriben.idLibro
						INNER JOIN personas ON escriben.idPersona = personas.idPersona
						WHERE libros.idLibro = '$idLibro'"); 			// Obtener solo los autores del libro que estamos buscando
				// Vamos a convertir esa lista de autores del libro en un array de ids de personas
				$listaAutoresLibro = array();
				while ($autor = $autoresLibro->fetch_object()) {
					$listaAutoresLibro[] = $autor->idPersona;
				}

				// Ya tenemos todos los datos para añadir el selector de autores al formulario
				echo "Autores: <select name='autor[]' multiple size='3'>";
				while ($fila = $todosLosAutores->fetch_object()) {
					if (in_array($fila->idPersona, $listaAutoresLibro))
						echo "<option value='$fila->idPersona' selected>$fila->nombre $fila->apellido</option>";
					else
						echo "<option value='$fila->idPersona'>$fila->nombre $fila->apellido</option>";
				}
				echo "</select>";

				// Por último, un enlace para crear un nuevo autor
				echo "<a href='index.php?action=formularioInsertarAutores'>Añadir nuevo</a><br>";

				// Finalizamos el formulario
				echo "  <input type='hidden' name='action' value='modificarLibro'>
						<input type='submit'>
					  </form>";
				echo "<p><a href='index.php'>Volver</a></p>";
			} else {
				echo "<p>No tienes permisos para eso!!!</p>";
				echo "<p><a href='index.php'>Volver</a></p>";
			}

		}

		// --------------------------------- MODIFICAR LIBROS ----------------------------------------

		public function modificarLibro() {
			if (isset($_SESSION["idUsuario"])) {
				echo "<h1>Modificación de libros</h1>";

				// Vamos a procesar el formulario de modificación de libros
				// Primero, recuperamos todos los datos del formulario
				$idLibro = $_REQUEST["idLibro"];
				$titulo = $_REQUEST["titulo"];
				$genero = $_REQUEST["genero"];
				$pais = $_REQUEST["pais"];
				$ano = $_REQUEST["ano"];
				$numPaginas = $_REQUEST["numPaginas"];
				$autores = $_REQUEST["autor"];

				// Lanzamos el UPDATE contra la base de datos.
				$db->query("UPDATE libros SET
								titulo = '$titulo',
								genero = '$genero',
								pais = '$pais',
								ano = '$ano',
								numPaginas = '$numPaginas'
								WHERE idLibro = '$idLibro'");

				if ($db->affected_rows == 1) {
					// Si la modificación del libro ha funcionado, continuamos actualizando la tabla "escriben".
					// Primero borraremos todos los registros del libro actual y luego los insertaremos de nuevo
					$db->query("DELETE FROM escriben WHERE idLibro = '$idLibro'");
					// Ya podemos insertar todos los autores junto con el libro en "escriben"
					foreach ($autores as $idAutor) {
						$db->query("INSERT INTO escriben(idLibro, idPersona) VALUES('$idLibro', '$idAutor')");
					}
					echo "Libro actualizado con éxito";
				} else {
					// Si la modificación del libro ha fallado, mostramos mensaje de error
					echo "Ha ocurrido un error al modificar el libro. Por favor, inténtelo más tarde.";
				}
				echo "<p><a href='index.php'>Volver</a></p>";
			} else {
				echo "<p>No tienes permisos para eso!!!</p>";
				echo "<p><a href='index.php'>Volver</a></p>";
			}
		}

		// --------------------------------- BUSCAR LIBROS ----------------------------------------

        public function buscarLibros() {
			// Recuperamos el texto de búsqueda de la variable de formulario
			$textoBusqueda = $_REQUEST["textoBusqueda"];
			echo "<h1>Resultados de la búsqueda: \"$textoBusqueda\"</h1>";

			// Buscamos los libros de la biblioteca que coincidan con el texto de búsqueda
			if ($result = $db->query("SELECT * FROM libros
					INNER JOIN escriben ON libros.idLibro = escriben.idLibro
					INNER JOIN personas ON escriben.idPersona = personas.idPersona
					WHERE libros.titulo LIKE '%$textoBusqueda%'
					OR libros.genero LIKE '%$textoBusqueda%'
					OR personas.nombre LIKE '%$textoBusqueda%'
					OR personas.apellido LIKE '%$textoBusqueda%'
					ORDER BY libros.titulo")) {

				// La consulta se ha ejecutado con éxito. Vamos a ver si contiene registros
				if ($result->num_rows != 0) {
					// La consulta ha devuelto registros: vamos a mostrarlos
					// Primero, el formulario de búsqueda
					echo "<form action='index.php'>
								<input type='hidden' name='action' value='buscarLibros'>
                            	<input type='text' name='textoBusqueda'>
								<input type='submit' value='Buscar'>
                          </form><br>";
					// Después, la tabla con los datos
					echo "<table border ='1'>";
					while ($fila = $result->fetch_object()) {
						echo "<tr>";
						echo "<td>" . $fila->titulo . "</td>";
						echo "<td>" . $fila->genero . "</td>";
						echo "<td>" . $fila->numPaginas . "</td>";
						echo "<td>" . $fila->nombre . "</td>";
						echo "<td>" . $fila->apellido . "</td>";
						echo "<td><a href='index.php?action=formularioModificarLibro&idLibro=" . $fila->idLibro . "'>Modificar</a></td>";
						echo "<td><a href='index.php?action=borrarLibro&idLibro=" . $fila->idLibro . "'>Borrar</a></td>";
						echo "</tr>";
					}
					echo "</table>";
				} else {
					// La consulta no contiene registros
					echo "No se encontraron datos";
				}
			} else {
				// La consulta ha fallado
				echo "Error al tratar de recuperar los datos de la base de datos. Por favor, inténtelo más tarde";
			}
			echo "<p><a href='index.php?action=formularioInsertarLibros'>Nuevo</a></p>";
			echo "<p><a href='index.php'>Volver</a></p>";
		}


    }