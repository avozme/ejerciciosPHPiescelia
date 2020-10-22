<?php
    class Usuario {
        private $db;
        
        /**
         * Constructor. Establece la conexión con la BD y la guarda
         * en una variable de la clase
         */
        public function __construct() {
            $this->db = new mysqli("localhost:3386", "root", "bitnami", "biblioteca");
        }

       
        /**
         * Busca un usuario por nombre de usuario y password
         * @param usuario El nombre del usuario
         * @param password La contraseña del usuario
         * @return True si existe un usuario con ese nombre y contraseña, false en caso contrario
         */
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