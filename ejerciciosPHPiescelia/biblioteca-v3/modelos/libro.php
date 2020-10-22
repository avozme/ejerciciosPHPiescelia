<?php

/**
 * Clase libro. Es el modelo de libro
 */
class Libro
{
    private $db;
	/**
	 * Constructor. Crea la conexión con la base de datos
     * y la guarda en una variable de la clase
	 */
    public function __construct()
    {
        $this->db = new mysqli("localhost:3386", "root", "bitnami", "biblioteca");
    }

	/**
	 * Busca un libro con idLibro = $id en la base de datos.
     * @param id El id del libro que se quiere buscar.
     * @return Un objeto con el libro de la BD, o null si no lo encuentra.
	 */
    public function get($id)
    {
        if ($result = $this->db->query("SELECT * FROM libros
                                            WHERE libros.idLibro = '$id'")) {
            $result = $result->fetch_object();
        } else {
            $result = null;
        }
        return $result;
    }

	/**
	 * Busca todos los autores de un libro.
     * @param idLibro El id del libro que se quiere buscar.
     * @return Un array con los ids de los autores de un libro
	 */
    public function getAutores($idLibro)
    {
        $autoresLibro = $this->db->query("SELECT personas.idPersona FROM libros
						INNER JOIN escriben ON libros.idLibro = escriben.idLibro
						INNER JOIN personas ON escriben.idPersona = personas.idPersona
						WHERE libros.idLibro = '$idLibro'");             // Obtener solo los autores del libro que estamos buscando
        // Vamos a convertir esa lista de autores del libro en un array de ids de personas
        $listaAutoresLibro = array();
        while ($autor = $autoresLibro->fetch_object()) {
            $listaAutoresLibro[] = $autor->idPersona;
        }
        return $listaAutoresLibro;
    }

    /**
     * Busca todos los libros de la BD
     * @return Todos los libros como objetos de un array o null en caso de error
     */
    public function getAll()
    {
        $arrayResult = array();
        if ($result = $this->db->query("SELECT * FROM libros
					                        INNER JOIN escriben ON libros.idLibro = escriben.idLibro
					                        INNER JOIN personas ON escriben.idPersona = personas.idPersona
                                            ORDER BY libros.titulo")) {
            while ($fila = $result->fetch_object()) {
                $arrayResult[] = $fila;
            }
        } else {
            $arrayResult = null;
        }
        return $arrayResult;
    }

    /**
     * Inserta un libro en la BD
     * @param titulo El título del libro
     * @param genero El género del libro
     * @param pais El país del libro
     * @param ano El año de publicación del libro
     * @param numPaginas El número de páginas del libro
     * @return 1 si la inserción tiene éxito, 0 en otro caso
     */
    public function insert($titulo, $genero, $pais, $ano, $numPaginas)
    {
        $this->db->query("INSERT INTO libros (titulo,genero,pais,ano,numPaginas) 
                        VALUES ('$titulo','$genero', '$pais', '$ano', '$numPaginas')");
        return $this->db->affected_rows;
    }

    /**
     * Actualiza un libro de la BD
     * @param idLibro El id del libro que se va a actualizar
     * @param titulo El título del libro
     * @param genero El género del libro
     * @param pais El país del libro
     * @param ano El año de publicación del libro
     * @param numPaginas El número de páginas del libro
     * @return 1 si la actualización tiene éxito, 0 en otro caso
     */
    public function update($idLibro, $titulo, $genero, $pais, $ano, $numPaginas)
    {
        $this->db->query("UPDATE libros SET
								titulo = '$titulo',
								genero = '$genero',
								pais = '$pais',
								ano = '$ano',
								numPaginas = '$numPaginas'
                                WHERE idLibro = '$idLibro'");
        return $this->db->affected_rows;
    }

    /**
     * Actualiza los autores de un libro en la BD
     * @param idLibro El id del libro que se va a actualizar
     * @param autores La lista (array) de autores de ese libro
     * @return 0 en otro caso o el número de autores insertados en caso de éxito
     */
    public function updateAutores($idLibro, $autores)
    {
        $cantidadDeAutores = count($autores);

        // Primero borraremos todos los registros del libro actual y luego los insertaremos de nuevo
        $this->db->query("DELETE FROM escriben WHERE idLibro = '$idLibro'");
        
        // Ya podemos insertar todos los autores junto con el libro en "escriben"
        $insertados = 0;
        foreach ($autores as $idAutor) {
            $this->db->query("INSERT INTO escriben(idLibro, idPersona) VALUES('$idLibro', '$idAutor')");
            if ($this->db->affected_rows == 1) $insertados++;
        }

        // Si el número de autores insertados en "escriben" es igual al número de elementos del array $autores, todo ha ido bien
        if ($cantidadDeAutores == $insertados) return 1;
        else return 0; 
    }

    /** 
     * Elimina un libro de la BD
     * @param id El id del libro que se va a actualizar
     * @return 1 si el borrado tiene éxito, 0 en caso contrario
     */
    public function delete($id)
    {
        $this->db->query("DELETE FROM libros WHERE idLibro = '$id'");
        return $this->db->affected_rows;
    }

    /** 
     * Obtiene el último ID asignado a un libro en la BD
     * @return El último ID asignado
     */
    public function getLastId()
    {
        $result = $this->db->query("SELECT MAX(idLibro) AS ultimoIdLibro FROM libros");
        $idLibro = $result->fetch_object()->ultimoIdLibro;
        return $idLibro;
    }

    /** 
     * Realiza una búsqueda por titulo o género (del libro) y nombre o apellido (del autor)
     * @param textoBusqueda El texto de búsqueda
     * @return Un array de objetos con los datos de los libros encontrados
     */
    public function busquedaAproximada($textoBusqueda)
    {
        $arrayResult = array();
        // Buscamos los libros de la biblioteca que coincidan con el texto de búsqueda
		if ($result = $this->db->query("SELECT * FROM libros
					INNER JOIN escriben ON libros.idLibro = escriben.idLibro
					INNER JOIN personas ON escriben.idPersona = personas.idPersona
					WHERE libros.titulo LIKE '%$textoBusqueda%'
					OR libros.genero LIKE '%$textoBusqueda%'
					OR personas.nombre LIKE '%$textoBusqueda%'
					OR personas.apellido LIKE '%$textoBusqueda%'
					ORDER BY libros.titulo")) {
            while ($fila = $result->fetch_object()) {
                $arrayResult[] = $fila;
            }
        } else {
            $arrayResult = null;
        }
        return $arrayResult;

    }
}
