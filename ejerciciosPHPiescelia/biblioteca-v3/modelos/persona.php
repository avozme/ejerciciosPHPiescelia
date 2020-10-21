<?php  
    class Persona {
        private $db;
        public function __construct() {
            $this->db = new mysqli("localhost:3386", "root", "bitnami", "biblioteca");
        }

        // Devuelve una persona a partir de su id, o null en caso de error
        public function get($id) {
            if ($result = $this->db->query("SELECT * FROM persona WHERE idPersona = '$id'")) {
                $result = $result->fetch_object();
            } else {
                $result = null;
            }
            return $result;
        }

        // Devuelve todas las personas en un array o null en caso de error
        public function getAll() {
            $arrayResult = array();
            if ($result = $this->db->query("SELECT * FROM personas")) {
                while ($fila = $result->fetch_object()) {
                    $arrayResult[] = $fila;
                }
            } else {
                $arrayResult = null;
            }
            return $arrayResult;
        }

        public function insert() {
        }

        public function update() {
        }

        public function delete() {
        }

        public function escribe($idLibro, $idAutor) {
            $this->db->query("INSERT INTO escriben(idLibro, idPersona) 
                        VALUES('$idLibro', '$idAutor')");
            return $this->db->affected_rows;
        }

    }