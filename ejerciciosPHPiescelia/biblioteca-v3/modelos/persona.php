<?php
class Persona
{
    private $db;
    /**
     * Constructor. Establece la conexión con la BD y la almacena en una variable de clase
     */
    public function __construct()
    {
        $this->db = new mysqli("localhost:3386", "root", "bitnami", "biblioteca");
    }

    /**
     * Busca a una persona a partir de su $id
     * @param id El id de la persona a buscar
     * @return Un objeto con todos los datos de la persona extraídos de la BD, o null en caso de error
     */
    public function get($id)
    {
        if ($result = $this->db->query("SELECT * FROM persona WHERE idPersona = '$id'")) {
            $result = $result->fetch_object();
        } else {
            $result = null;
        }
        return $result;
    }

    /**
     * Busca a todas las personas de la BD
     * @return Un array de objetos con todos los datos extraídos de la BD, o null en caso de error
     */
    public function getAll()
    {
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

    public function insert()
    {
    }

    public function update()
    {
    }

    public function delete()
    {
    }

    /**
     * Inserta un registro en la relación "escribe"
     * @param idLibro El id del libro que se va a insertar
     * @param idAutor El id del autor que se va a insertar
     * @return 1 en caso de éxito, 0 en caso de fallo
     */
    public function escribe($idLibro, $idAutor)
    {
        $this->db->query("INSERT INTO escriben(idLibro, idPersona) 
                        VALUES('$idLibro', '$idAutor')");
        return $this->db->affected_rows;
    }
}
