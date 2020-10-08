<!-- Versión del número secreto CON VARIABLES DE SESIÓN -->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title></title>
  </head>
  <body>

	<?php
		session_start();

		if (!isset($_REQUEST["numero"])) {
			if (!isset($_REQUEST["aleatorio"])) {
				$_SESSION["intentos"] = 0;
				$_SESSION["aleatorio"] = rand(1,100);
			} else {
				$aleatorio = $_SESSION["aleatorio"];
				$intentos = $_SESSION["intentos"];
			}
			echo "<form action='05-numero-secreto-v2.php' method='get'>
				Adivina mi número:
				<input type='text' name='numero'><br>
				<br>				
				<input type='submit'>
				</form>";
		} else {
			$n = $_REQUEST["numero"];
			$aleatorio = $_SESSION["aleatorio"];
			$intentos = $_SESSION["intentos"];
			$intentos++;
			echo "Tu número es: $n<br>";
			if ($n > $aleatorio) {
				echo "Mi número es MENOR";
				echo "<br><a href='05-numero-secreto-v2.php'>Sigue jugando...</a>";
			}
			else if ($n < $aleatorio) {
				echo "Mi número es MAYOR";
				echo "<br><a href='05-numero-secreto-v2.php'>Sigue jugando...</a>";
			}
			else {
				echo "<p>ENHORABUENA, HAS ACERTADO</p>";
				echo "Has necesitado $intentos intentos";
				$intentos = 0;
				unset($_SESSION["aleatorio"]);
				echo "<br><a href='05-numero-secreto-v2.php'>Jugar de nuevo</a>";
			}
			$_SESSION["intentos"] = $intentos;
		}

	?>











  </body>
</html>