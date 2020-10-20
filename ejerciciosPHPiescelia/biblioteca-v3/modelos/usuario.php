<?php
    class Usuario {
        private $db;
        public function __construct() {
            $this->db = new mysqli("localhost:3386", "root", "bitnami", "biblioteca");
        }

        public function buscarUsuario($usuario,$password) {

            if ($result = $this->db->query("SELECT idUsuario, nombre, fotografia FROM usuarios WHERE nombre = '$usuario' AND password = '$password'")) {
                if ($result->num_rows == 1) {
                    $usuario = $result->fetch_object();
                    // Iniciamos la sesión
                    $_SESSION["idUsuario"] = $usuario->idUsuario;
                    $_SESSION["nombreUsuario"] = $usuario->nombre;
                    $_SESSION["fotografiaUsuario"] = $usuario->fotografia;
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }

        }

        public function get($id) {
        }

        public function getAll() {
        }

        public function insert() {
        }

        public function update() {
        }

        public function delete() {
        }


    }