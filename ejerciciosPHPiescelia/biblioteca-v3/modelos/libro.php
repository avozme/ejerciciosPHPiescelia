<?php  
    class Libro {
        private $db;
        public function __construct() {
            $this->db = new mysqli("localhost:3386", "root", "bitnami", "biblioteca");
        }

        // Devuelve un libro a partir de su id, o null en caso de error
        public function get($id) {
            $arrayResult = array();
            if ($result = $this->db->query("SELECT * FROM libros
					                        INNER JOIN escriben ON libros.idLibro = escriben.idLibro
					                        INNER JOIN personas ON escriben.idPersona = personas.idPersona
                                            WHERE libros.idLibro = '$id'")) {
                $arrayResult[] = $result->fetch_object();
            } else {
                $arrayResult = null;
            }
            return $arrayResult;
        }

        // Devuelve todos los libros en un array o null en caso de error
        public function getAll() {
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

        public function insert($titulo, $genero, $pais, $ano, $numPaginas) {
            $this->db->query("INSERT INTO libros (titulo,genero,pais,ano,numPaginas) 
                        VALUES ('$titulo','$genero', '$pais', '$ano', '$numPaginas')");        
            return $this->db->affected_rows;
        }

        public function update() {
        }

        public function delete($id) {
            $this->db->query("DELETE FROM libros WHERE idLibro = '$id'");
            return $this->db->affected_rows;
        }

        public function getLastId() {
            $result = $this->db->query("SELECT MAX(idLibro) AS ultimoIdLibro FROM libros");
            $idLibro = $result->fetch_object()->ultimoIdLibro;
            return $idLibro;
        }

    }