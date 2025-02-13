<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Registro</title> 
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <form method="POST" action="registro.php"> 
        <label for="nombre">Nombre de usuario:</label>
        <input type="text" id="nombre" name="nombre" required><br>

        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" required><br>

        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contraseña" name="contraseña" required><br>

        <input type="checkbox" id="terminos" name="terminos" required>
        <label for="terminos">Acepto los términos y condiciones</label><br>

        <input type="submit" value="Crear cuenta">
        <input type="button" value="Volver al inicio" onclick="window.location.href='index.php'">
    </form>
</body>
</html>


<?php
include 'conexion.php'; // Incluye el archivo de conexión a la base de datos

// Verifica si se ha enviado el formulario 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Quita los espacios de los datos del formulario
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $contraseña = trim($_POST['contraseña']);

    // Verifica que todos los campos estén completos
    if (empty($nombre) || empty($correo) || empty($contraseña)) {
        die("ERROR: Todos los campos son obligatorios."); // Si falta algún dato muestra un error
    }

    // Valida que el correo tenga un formato correcto
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        die("ERROR: Formato de correo inválido."); // Muestra un error si el correo no es válido
    }

    // Hasheamos la contraseña para guardarla de forma segura
    $contraseña_hasheada = password_hash($contraseña, PASSWORD_DEFAULT);

    try {
        // Verifica si el correo ya está en la base de datos
        $stmt = $conexionsql->prepare("SELECT correo FROM usuarios WHERE correo = :correo");
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR); // Asocia el valor del correo con el parámetro
        $stmt->execute();

        // Si el correo existe, muestra un error
        if ($stmt->fetch()) {
            die('<p style="color: red;">ERROR: NO SE HA CREADO EL USUARIO. EL CORREO YA ESTÁ REGISTRADO.</p>');
        }

        // Inserta el nuevo usuario en la base de datos
        $stmt = $conexionsql->prepare("INSERT INTO usuarios (nombre, correo, contraseña) VALUES (:nombre, :correo, :pass)");
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR); // Asocia el nombre de usuario
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR); // Asocia el correo
        $stmt->bindParam(':pass', $contraseña_hasheada, PDO::PARAM_STR); // Asocia la contraseña hasheada

        // Ejecuta la consulta para insertar los datos del nuevo usuario
        if ($stmt->execute()) {
            echo "Cuenta creada con éxito. <a href='login.php'>Iniciar sesión</a>"; // Muestra un mensaje de éxito
        } else {
            echo "ERROR: No se pudo registrar el usuario."; // Muestra un error si la inserción falla
        }
    } catch (PDOException $e) {
        // Captura cualquier error relacionado con la base de datos
        die("ERROR en la base de datos: " . $e->getMessage());
    }
}
?>
