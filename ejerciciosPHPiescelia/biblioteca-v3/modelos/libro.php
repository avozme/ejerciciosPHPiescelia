<?php
class Libro
{
    private $db;
    public function __construct()
    {
        $this->db = new mysqli("localhost:3386", "root", "bitnami", "biblioteca");
    }

    // Devuelve un libro a partir de su id, o null en caso de error
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

    // Devuelve un array con los ids de los autores de un libro
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

    // Devuelve todos los libros en un array o null en caso de error
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

    public function insert($titulo, $genero, $pais, $ano, $numPaginas)
    {
        $this->db->query("INSERT INTO libros (titulo,genero,pais,ano,numPaginas) 
                        VALUES ('$titulo','$genero', '$pais', '$ano', '$numPaginas')");
        return $this->db->affected_rows;
    }

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

    public function delete($id)
    {
        $this->db->query("DELETE FROM libros WHERE idLibro = '$id'");
        return $this->db->affected_rows;
    }

    public function getLastId()
    {
        $result = $this->db->query("SELECT MAX(idLibro) AS ultimoIdLibro FROM libros");
        $idLibro = $result->fetch_object()->ultimoIdLibro;
        return $idLibro;
    }

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
