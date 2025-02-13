<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Document</title> 
</head>
<body>

<?php
// Conexión a la base de datos
try {
    // Crea una nueva conexión PDO a la base de datos 'users' en localhost
    $conexionsql = new PDO('mysql:host=localhost;dbname=users', 'root', 'campusfp');
    $conexionsql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexionsql->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Si ocurre un error al conectar a la base de datos, muestra un mensaje de error y detiene la ejecución
    die("ERROR: No se pudo conectar. " . $e->getMessage());
}
?>
</body>
</html>
