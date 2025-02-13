<?php session_start(); ?> <!-- Inicia la sesión del usuario-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Inicio - Gestor de Tareas</title> 
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <h1>GESTOR DE TAREAS</h1> 

    <?php if (isset($_SESSION['correo'])): ?> 
        <!-- Si el usuario está logeado -->
        <button onclick="window.location.href='tareas.php'">Ir a mis Tareas</button> <!-- Botón para redirigir a la página de tareas del usuario -->
        <button onclick="window.location.href='logout.php'">Cerrar Sesión</button> <!-- Botón para cerrar sesión y redirigir al usuario a la página de logout -->
    <?php else: ?>
        <!-- Si no está logeado -->
        <button onclick="window.location.href='login.php'">Iniciar Sesión</button> <!-- Botón para redirigir al formulario de inicio de sesión -->
        <button onclick="window.location.href='registro.php'">Registrarse</button> <!-- Botón para redirigir al formulario de registro -->
    <?php endif; ?>

</body>
</html>
