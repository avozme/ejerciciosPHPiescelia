<?php
// Pasamos las variables preparadas en el controlador ($data[]) a variables normales 
// para escribirlas con más facilidad
$libro = $data['libro'];
$listaAutoresLibro = $data['listaAutoresLibro'];
$listaTodosLosAutores = $data['listaTodosLosAutores'];

echo "<h1>Modificación de libros</h1>";


// Creamos el formulario con los campos del libro
// y lo rellenamos con los datos que hemos recuperado de la BD
echo "<form action = 'index.php' method = 'get'>
            <input type='hidden' name='idLibro' value='$libro->idLibro'>
            Título:<input type='text' name='titulo' value='$libro->titulo'><br>
            Género:<input type='text' name='genero' value='$libro->genero'><br>
            País:<input type='text' name='pais' value='$libro->pais'><br>
            Año:<input type='text' name='ano' value='$libro->ano'><br>
            Número de páginas:<input type='text' name='numPaginas' value='$libro->numPaginas'><br>";

// Vamos a añadir un selector para el id del autor o autores.
// Para que salgan preseleccionados los autores del libro que estamos modificando, vamos a buscar
// también a esos autores.

// Ya tenemos todos los datos para añadir el selector de autores al formulario
echo "Autores: <select name='autor[]' multiple size='3'>";
foreach ($listaTodosLosAutores as $autor) {
    if (in_array($autor->idPersona, $listaAutoresLibro))
        echo "<option value='$autor->idPersona' selected>$autor->nombre $autor->apellido</option>";
    else
        echo "<option value='$autor->idPersona'>$autor->nombre $autor->apellido</option>";
}
echo "</select>";

// Por último, un enlace para crear un nuevo autor
echo "<a href='index.php?action=formularioInsertarAutores'>Añadir nuevo</a><br>";

// Finalizamos el formulario
echo "  <input type='hidden' name='action' value='modificarLibro'>
            <input type='submit'>
          </form>";
echo "<p><a href='index.php'>Volver</a></p>";
