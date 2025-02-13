<?php
session_start();   // Inicia la sesión 
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Verifica que la petición sea un POST (cuando se envía el formulario)
    include 'conexion.php'; // Incluye el archivo de conexión a la base de datos
    $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL); 
    $contraseña = $_POST['contraseña']; 

    // Prepara la consulta SQL para buscar al usuario en la base de datos.
    $stmt = $conexionsql->prepare('SELECT * FROM usuarios WHERE correo = :correo');
    $stmt->execute(['correo' => $correo]); // Ejecuta la consulta pasando el correo como parámetro
    $usuario = $stmt->fetch(); // Obtiene el resultado de la consulta (el usuario encontrado)

    // Verifica si el usuario existe y si la contraseña es válida usando password_verify
    if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {
        $_SESSION['correo'] = $usuario['correo']; // Si el login es exitoso, guarda el correo en la sesión
        header('Location: tareas.php'); // Redirige a la página de tareas
        exit(); // Termina el script 
    } else {
        $error = "ERROR. CORREO O CONTRASEÑA INCORRECTOS."; // Si la contraseña o el correo fallan, sale error
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Iniciar Sesión</title> 
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <h2>Iniciar Sesión</h2> 
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?> 
    <form method="POST" action="login.php"> 
        <label>Correo:</label><input type="email" name="correo" required><br> 
        <label>Contraseña:</label><input type="password" name="contraseña" required><br> 
        <button type="submit">Iniciar Sesión</button> 
        <button type="button" onclick="window.location.href='index.php'">Volver a Inicio</button> 
    </form>
</body>
</html>
