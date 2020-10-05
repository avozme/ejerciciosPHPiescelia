<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title></title>
  </head>
  <body>

	<?php
		if (!isset($_REQUEST["numero"])) {
			if (!isset($_REQUEST["aleatorio"])) {
				$aleatorio = rand(1,100);
			} else {
				$aleatorio = $_REQUEST["aleatorio"];
			}
			echo "<form action='05-numero-secreto.php' method='get'>
				Adivina mi número:
				<input type='text' name='numero'>
				<input type='hidden' name='aleatorio' value='$aleatorio'>
				<input type='submit'>
				</form>";
		} else {
			$n = $_REQUEST["numero"];
			$aleatorio = $_REQUEST["aleatorio"];
			echo "Tu número es: $n<br>";
			echo "El número aleatorio es: $aleatorio<br>";
			if ($n > $aleatorio) {
				echo "Mi número es MENOR";
			}
			else if ($n < $aleatorio) {
				echo "Mi número es MAYOR";
			}
			else {
				echo "ENHORABUENA, HAS ACERTADO";
			}
			echo "<a href='05-numero-secreto.php?aleatorio=$aleatorio'>Sigue jugando...</a>";
		}

	?>











  </body>
</html>