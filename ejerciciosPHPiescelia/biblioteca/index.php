<!-- ADVERTENCIA: este programa está a medio hacer.
     Utilízalo como base para tu propia versión de la Biblioteca -->


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  </head>
  <body>
    <?php
    ini_set('display_errors', '1');
    error_reporting(E_ALL);

    $db = new mysqli("URL","user","password", "database");

    if (isset($_REQUEST["action"])) {
    	$action = $_REQUEST["action"];
    } else {
	    $action = "mostrarListaLibros";  // Acción por defecto
    }

    switch($action) {

        case "montrarListaLibros":
            if ($result = $db->query("SELECT * FROM libros")) {

                if ($result->num_rows != 0) {            
                    echo "<a href='index.php?action=formularioAltaLibros'>Nuevo</a>";
                    echo "<form action='index.php'><input type='hidden' name='action' value='buscarLibros'>
                            <input type='text' name='textoBusqueda'>
                            </form>";
                    echo "<table>";
                
                    while ($fila = $result->fetch_object()) {
                        echo "<tr>";
                        echo "<td>".$fila->titulo."</td>";
                        echo "<td>".$fila->genero."</td>";
                        echo "<td>".$fila->numpaginas."</td>";
                        echo "<td>Modificar</td>";
                        echo "<td><a href='index.php?action=borrarLibro&idLibro=".$fila->id."'>Borrar</a></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No se encontraron datos";
                }
	        }
   	        break;

    case "formularioAltaLibros":
            echo '<h1>Formulario de alta de libros</h1>
                <form action = "index.php" method = "get">
                    Título:<input type="text" name="titulo"><br>
                    Género:<input type="text" name="genero"><br>
                    Autor:<select name="autor">
                        <option value='1'>Autor 1</option>
                        <option value='2'>Autor 2</option>
                        <option value='3'>Autor 3</option>
                    </select>
                    <input type="hidden" name="action" value="insertarLibro">
                    <input type="submit">
                    </form>';
            break;

        case "insertarLibro":
            $titulo = $_REQUEST["titulo"];
            $genero = $_REQUEST["genero"];
	        $idAutor = $_REQUEST["autor"];
            // Conectamos con el servidor y abrimos la BD.
            $conexdb->query("INSERT INTO libros (titulo,genero)
            VALUES ('$titulo','$genero')");
	        //$conexdb->query("INSERT INTO escriben(idLibro, idAutor') VALUES('$idLibro', '$idAutor');
            break;

        case "borrarLibro":
            $idLibro = $_REQUEST["idLibro"];
            $db->query("DELETE FROM libros WHERE idLibro = '$idLibro'");	
            if ($db->affected_rows == 0) {
               echo "Ha ocurrido un error al borrar el libro. Por favor, inténtelo de nuevo";
            }
            else {
                echo "Libro borrado con éxito";	
            }
            break;

        case "buscarLibros":
            break;

        default: echo "Error 404: página no encontrada";
            break;
        } // switch

        
    } // else

    ?>

  </body>
</html>