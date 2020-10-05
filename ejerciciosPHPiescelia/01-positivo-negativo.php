<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title></title>
  </head>
  <body>
	<?php
		if (!isset($_REQUEST["numero"])) {
			echo "<form action='01-positivo-negativo.php' method='get'>
				Introduce un número:
				<input type='text' name='numero'>
				<input type='submit'>
				</form>";
		}
		else {
			$n = $_REQUEST["numero"];
			if ($n > 0) {
				echo "El número $n es POSITIVO";
			}
			else if ($n < 0) {
				echo "El número $n es NEGATIVO";
			}
			else {
				echo "El número $n es CERO";
			}
		}

	?>


  </body>
</html>