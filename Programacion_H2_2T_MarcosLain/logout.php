<?php
session_start(); // Inicia la sesión 
session_destroy(); // Elimina los datos almacenados en la sesión actual, cerrando la sesión del usuario
header('Location: index.php'); // Redirige al usuario al index después de cerrar la sesión
exit(); // Detiene la ejecución del script, asegurando que no se ejecute nada más después de la redirección
?>
