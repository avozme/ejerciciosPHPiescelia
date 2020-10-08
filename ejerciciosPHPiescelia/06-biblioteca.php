<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title></title>
  </head>
  <body>

	<?php

	if (isset($_REQUEST["action"]))
		$action = $_REQUEST["action"];
	else
		$action = "mostrarListaLibros"; // Acción por defecto

	switch($action) {
	   case "mostrarListaLibros":
		error_reporting(E_ALL);		
		$db = new mysqli("localhost:8443", "root", "bitnami", "biblioteca");		
		if ($mysqli->connect_errno) {
    			echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}

		$consulta = $db->query("SELECT * FROM libros");
		echo "<a href='06-biblioteca.php?action=mostrarFomularioNuevoLibro'>Nuevo</a>";
		echo "<input type='text' name='buscador'>";
		echo "<table border='1'>"
		while ($fila = $consulta->fetch_object()) {
			echo "<tr><td>".$fila->titulo."</td>".
			     "<td>".$fila->genero."</td>".
			     "<td>". $fila->numPaginas."</td>".
			     "<td>Modificar</td>".
			     "<td>Borrar</td></tr>";
		}
		echo "</table>";
   		break;

  	   case "mostrarFomularioNuevoLibro":
		echo "<h1>Nuevo libro</h1>";
		echo "<form action='' method='get'>
			Titulo: <input type='text' name='titulo'><br>
			Género: <input type='text' name='genero'><br>
			<input type='submit'>
		      </form>";
			
		break;

	   case "borrarLibro":
		break;











	?>

  </body>
</html>