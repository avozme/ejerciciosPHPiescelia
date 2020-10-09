<!-- ADVERTENCIA: este programa está a medio hacer.
     Utilízalo como base para tu propia versión de la Biblioteca -->


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  </head>
  <body>
    <?php

    // Conectamos con la BD
    $db = new mysqli("localhost:3386","root","bitnami", "biblioteca");

    // Miramos qué acción nos toca hacer a continuación
    if (isset($_REQUEST["action"])) {
    	$action = $_REQUEST["action"];
    } else {
	    $action = "mostrarListaLibros";  // Acción por defecto
    }

    // COMIENZA EL CONTROLADOR PRINCIPAL
    switch($action) {

        case "mostrarListaLibros":
            if ($result = $db->query("SELECT * FROM libros")) {
	            /* Tendrás que añadir los siguientes INNER JOIN a esta consulta cuando empieces
                a trabajar con la tabla "personas":
					INNER JOIN escriben ON libros.idLibro = escriben.idLibro
					INNER JOIN personas ON escriben.idPersona = personas.idPersona */

                if ($result->num_rows != 0) {   
                    // Hemos encontrado resultados: vamos a mostrarlos
                    // Link para añadir nuevos libros         
                    echo "<a href='index.php?action=formularioAltaLibros'>Nuevo</a>";
                    // Formulario de búsqueda
                    echo "<form action='index.php'><input type='hidden' name='action' value='buscarLibros'>
                            <input type='text' name='textoBusqueda'>
                            <input type='submit'>
                            </form>";
                    echo "<table>";
                
                    // Tabla principal de resultados (lista de libros)
                    while ($fila = $result->fetch_object()) {
                        echo "<tr>";
                        echo "<td>".$fila->titulo."</td>";
                        echo "<td>".$fila->genero."</td>";
                        echo "<td>".$fila->pais."</td>";
                        echo "<td>".$fila->numPaginas."</td>";
                        // AÑADIR aquí más adelante el nombre y apellidos del autor
                        echo "<td>Modificar</td>";
                        echo "<td><a href='index.php?action=borrarLibro&idLibro=".$fila->idLibro."'>Borrar</a></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    // No hemos encontrado libros
                    echo "No se encontraron datos";
                }
	        }
   	        break;

        case "formularioAltaLibros":
            echo '<h1>Formulario de alta de libros</h1>
                    <form action = "index.php" method = "get">
                    Título:<input type="text" name="titulo"><br>
                    Género:<input type="text" name="genero"><br>';
                    /* Descomenta y adapta las siguientes líneas cuando vayas a trabajar también con la tabla "escriben"
                        $result = $db->query("SELECT * FROM personas");
                        echo "<select name='autor'>";
                        while ($fila = $result->fetch_object()) {
                            echo "<option value='".$fila->idPersona."'>".$fila->nombre." ".$fila->apellidos."</option>";
		                }
		                echo "</select>";
		            */
	        echo '<input type="hidden" name="action" value="insertarLibro">
                    <input type="submit">
                    </form>';
            break;

        case "insertarLibro":
            // Procesamos el formulario de alta de libros
            // Primero, recuperamos los datos del formulario de alta
            $titulo = $_REQUEST["titulo"];
            $genero = $_REQUEST["genero"];
	        // (Aquí habrá que añadir más campos)
            // Lanzamos el INSERT contra la base de datos
            $db->query("INSERT INTO libros (titulo,genero) VALUES ('$titulo','$genero')");
            // Comprobamos si el INSERT ha funcionado
            if ($db->affected_rows == 1)
		        echo "Libro insertado con éxito";
	        else
                echo "Ha ocurrido un error al insertar el registro";
                echo $db->error;

            /* Descomenta y adapta esto cuando vayas a trabajar también con la tabla "escriben"
            $db->query("INSERT INTO escriben(idLibro, idAutor') VALUES('$idLibro', '$idAutor');
            */
            break;

        case "borrarLibro":
            // Recuperamos el id del libro y lo borramos de la BD
            $idLibro = $_REQUEST["idLibro"];
            $db->query("DELETE FROM libros WHERE idLibro = '$idLibro'");
            /* ¡CUIDADO! Cuando empieces a trabajar con la tabla "escriben" tendrás que borrar también de ella */	
            if ($db->affected_rows == 0) {
               echo "Ha ocurrido un error al borrar el libro. Por favor, inténtelo de nuevo";
            }
            else {
                echo "Libro borrado con éxito";	
            }
            break;

        case "buscarLibros":
            // Impleméntalo tú...
            break;

        case "formularioModificarLibro":
            // Impleméntalo tú...

        case "modificarLibro":
            // Procesar el formulario de modificar libro
            // (Impleméntalo tú...)

        default: echo "Error 404: página no encontrada";
            break;
        } // switch


    ?>

  </body>
</html>