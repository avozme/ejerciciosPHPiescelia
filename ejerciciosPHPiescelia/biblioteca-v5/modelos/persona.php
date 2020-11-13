<?php

include_once("DB.php");

class Persona
{
    private $db;
    /**
     * Constructor. Establece la conexión con la BD y la almacena en una variable de clase
     */
    public function __construct()
    {
        $this->db = new DB();
    }

    /**
     * Busca a una persona a partir de su $id
     * @param id El id de la persona a buscar
     * @return Un objeto con todos los datos de la persona extraídos de la BD, o null en caso de error
     */
    public function get($id)
    {
        $result = $result = $this->db->consulta("SELECT * FROM persona WHERE idPersona = '$id'");
        return $result;
    }

    /**
     * Busca a todas las personas de la BD
     * @return Un array de objetos con todos los datos extraídos de la BD, o null en caso de error
     */
    public function getAll()
    {
       $result = $this->db->query("SELECT * FROM personas");
        return $result;
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
        $result = $this->db->manipulacion("INSERT INTO escriben(idLibro, idPersona) 
                        VALUES('$idLibro', '$idAutor')");
        return $result;
    }
}
