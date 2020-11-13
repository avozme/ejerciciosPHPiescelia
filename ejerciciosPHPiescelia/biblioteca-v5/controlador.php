<?php
include_once("vista.php");
include_once("modelos/usuario.php");
include_once("modelos/libro.php");
include_once("modelos/persona.php");
include_once("modelos/seguridad.php");

class Controlador
{

	private $vista, $usuario, $libro, $persona;

	/**
	 * Constructor. Crea las variables de los modelos y la vista
	 */
	public function __construct()
	{
		$this->vista = new Vista();
		$this->usuario = new Usuario();
		$this->libro = new Libro();
		$this->persona = new Persona();
		$this->seguridad = new Seguridad();
	}

	/**
	 * Muestra el formulario de login
	 */
	public function mostrarFormularioLogin()
	{
		$this->vista->mostrar("usuario/formularioLogin");
	}

	/**
	 * Procesa el formulario de login e inicia la sesión
	 */
	public function procesarLogin()
	{
		$usr = $_REQUEST["usr"];
		$pass = $_REQUEST["pass"];

		$usuario = $this->usuario->buscarUsuario($usr, $pass);
		
		if (count($usuario) == 1) {
			$this->seguridad->abrirSesion($usuario[0]);
			$this->mostrarListaLibros();
		} else {
			// Error al iniciar la sesión
			$data['msjError'] = "Nombre de usuario o contraseña incorrectos";
			$this->vista->mostrar("usuario/formularioLogin", $data);
		}
	}

	/**
	 * Cierra la sesión
	 */
	public function cerrarSesion()
	{
		$this->seguridad->cerrarSesion();
		$data['msjInfo'] = "Sesión cerrada correctamente";
		$this->vista->mostrar("usuario/formularioLogin", $data);
	}

	/**
	 * Muestra una lista con todos los libros
	 */
	public function mostrarListaLibros()
	{
		$data['listaLibros'] = $this->libro->getAll();
		$this->vista->mostrar("libro/listaLibros", $data);
	}

	/**
	 * Muestra el formulario de alta de libros
	 */
	public function formularioInsertarLibros()
	{
		if ($this->seguridad->haySesionIniciada()) {
			// Primero, accedemos al modelo de personas para obtener la lista de autores
			$data['listaAutores'] = $this->persona->getAll();
			$this->vista->mostrar('libro/formularioInsertarLibro', $data);
		} else {
			$this->seguridad->errorAccesoNoPermitido();
		}
	}

	/**
	 * Inserta un libro en la base de datos
	 */
	public function insertarLibro()
	{

		if ($this->seguridad->haySesionIniciada()) {
			// Vamos a procesar el formulario de alta de libros
			// Primero, recuperamos todos los datos del formulario
			// Ahora insertamos el libro en la BD
			$result = $this->libro->insert($titulo, $genero, $pais, $ano, $numPaginas);

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
			// Terminamos mostrando la lista de libros actualizada
			$data['listaLibros'] = $this->libro->getAll();
			$this->vista->mostrar("libro/listaLibros", $data);
		} else {
			$this->seguridad->errorAccesoNoPermitido();
		}
	}

	/**
	 * Elimina un libro de la base de datos
	 */
	public function borrarLibro()
	{
		if ($this->seguridad->haySesionIniciada()) {
			// Recuperamos el id del libro
			$idLibro = $_REQUEST["idLibro"];
			// Eliminamos el libro de la BD
			$result = $this->libro->delete($idLibro);
			if ($result == 0) {
				$data['msjError'] = "Ha ocurrido un error al borrar el libro. Por favor, inténtelo de nuevo";
			} else {
				$data['msjInfo'] = "Libro borrado con éxito";
			}
			// Mostramos la lista de libros actualizada
			$data['listaLibros'] = $this->libro->getAll();
			$this->vista->mostrar("libro/listaLibros", $data);
		} else {
			$this->seguridad->errorAccesoNoPermitido();
		}
	}

	/**
	 * Elimina un libro de la base de datos (petición por ajax)
	 */
	public function borrarLibroAjax()
	{
		if ($this->seguridad->haySesionIniciada()) {
			// Recuperamos el id del libro
			$idLibro = $_REQUEST["idLibro"];
			// Eliminamos el libro de la BD
			$result = $this->libro->delete($idLibro);
			if ($result == 0) {
				// Error al borrar. Enviamos el código -1 al JS
				echo "-1";
			}
			else {
				// Borrado con éxito. Enviamos el id del libro a JS
				echo $idLibro;
			}
		} else {
			echo "-1";
		}
	}

	/**
	 * Muestra el formulario de modificación de libro
	 */
	public function formularioModificarLibro()
	{
		if ($this->seguridad->haySesionIniciada()) {
			// Recuperamos el libro con id = $idLibro y lo preparamos para pasárselo a la vista
			$idLibro = $_REQUEST["idLibro"];
			$data['libro'] = $this->libro->get($idLibro);
			// Recuperamos la lista de autores del libro y la preparamos para pasársela a la vista
			$data['listaAutoresLibro'] = $this->libro->getAutores($idLibro);
			// También necesitaremos la lista de todos los autores
			$data['listaTodosLosAutores'] = $this->persona->getAll();
			// Rederizamos la vista con el formulario de modificación de libros
			$this->vista->mostrar('libro/formularioModificarLibro', $data);
		} else {
			$this->seguridad->errorAccesoNoPermitido();
		}
	}

	/**
	 * Modifica un libro en la base de datos
	 */
	public function modificarLibro()
	{
		if ($this->seguridad->haySesionIniciada()) {

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
			$result = $this->libro->update($idLibro, $titulo, $genero, $pais, $ano, $numPaginas);

			if ($result == 1) {
				// Si la modificación del libro ha funcionado, continuamos actualizando la tabla "escriben".
				$resultAutores = $this->libro->updateAutores($idLibro, $autores);
				if ($resultAutores > 0) $data['msjInfo'] = "Libro actualizado con éxito";
				else $data['msjError'] = "Error al actualizar los autores del libro";
			} else {
				// Si la modificación del libro ha fallado, mostramos mensaje de error
				$data['msjError'] = "Ha ocurrido un error al modificar el libro. Por favor, inténtelo más tarde.";
			}
			$data['listaLibros'] = $this->libro->getAll();
			$this->vista->mostrar("libro/listaLibros", $data);
		} else {
			$this->seguridad->errorAccesoNoPermitido();
		}
	}

	/**
	 * Lanza una búsqueda de libros y muestra el resultado
	 */
	public function buscarLibros()
	{
		// Recuperamos el texto de búsqueda de la variable de formulario
		$textoBusqueda = $_REQUEST["textoBusqueda"];
		// Lanzamos la búsqueda y enviamos los resultados a la vista de lista de libros
		$data['listaLibros'] = $this->libro->busquedaAproximada($textoBusqueda);
		$data['msjInfo'] = "Resultados de la búsqueda: \"$textoBusqueda\"";
		$this->vista->mostrar("libro/listaLibros", $data);

	}

	public function comprobarNombreUsuario() {
		$nombreUsuario = $_REQUEST["nombreUsuario"];
		$result = $this->usuario->existeNombre($nombreUsuario);
		echo $result;
	}
}
