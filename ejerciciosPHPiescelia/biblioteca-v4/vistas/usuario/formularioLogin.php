<script>

function ejecutar_ajax() {
	peticion_http = new XMLHttpRequest();
	peticion_http.onreadystatechange = procesa_respuesta;
	nombreUsuario = document.getElementById("nombreUsuario").value;
	peticion_http.open('GET', 'http://localhost/ejerciciosPHPiescelia/ejerciciosPHPiescelia/biblioteca-v4/index.php?action=comprobarNombreUsuario&nombreUsuario=' + nombreUsuario, true);
	peticion_http.send(null);
}	

function procesa_respuesta() {
	if(peticion_http.readyState == 4) {
		if(peticion_http.status == 200) {
			if (peticion_http.responseText == "0")
				document.getElementById('mensajeUsuario').innerHTML = "Error, ese usuario no existe";
			if (peticion_http.responseText == "1")
				document.getElementById('mensajeUsuario').innerHTML = "Usuario OK";
		}
	}
}	
</script>



<h1>Iniciar sesión</h1>

<?php
	if (isset($data['msjError'])) {
		echo "<p style='color:red'>".$data['msjError']."</p>";
	}
	if (isset($data['msjInfo'])) {
		echo "<p style='color:blue'>".$data['msjInfo']."</p>";
	}
?>

<form action='index.php'>
	Usuario:<input type='text' name='usr' id='nombreUsuario' onBlur='ejecutar_ajax()'>
	<span id='mensajeUsuario'></span><br>
	Contraseña:<input type='password' name='pass'><br>
	<input type='hidden' name='action' value='procesarLogin'>
	<input type='submit'>
</form>

