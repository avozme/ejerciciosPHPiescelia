<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title></title>
  </head>
  <body>

	<?php
	if (!isset($_REQUEST["numero"])) {
		// Si no tenemos un número pasado por GET, mostramos el formulario
		echo "<form action='03-tabla2.php' method='GET'>
		Introduce un número:
		<input type='text' name='numero'>
		<br>
		<input type='submit'>
		</form>";
	}
	else {
		// Ya tenemos número pasado por GET. Vamos a procesarlo.
		$n = $_REQUEST["numero"];
		echo "<table border='1'>";
		echo "<tr><td colspan='5'>Tabla de multiplicar del número $n</td></tr>";
		echo "<tr>";
		for ($i = 1; $i <= 25; $i++) {
			if (($i-1) % 5 == 0) echo "</tr><tr>";
			echo "<td>$n x $i = " . $n * $i . "</td>";
		}
		echo "</tr>";
		echo "</table>";
	}
	?>

  </body>
</html>