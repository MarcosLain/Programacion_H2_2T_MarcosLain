<?php
session_start(); // Inicia la sesión para acceder a las variables de sesión

// Si el usuario no está logueado, lo redirige al login
if (!isset($_SESSION['correo'])) {
    header('Location: login.php'); 
    exit(); 
}

include 'conexion.php'; // Incluye el archivo de conexión a la base de datos
$correo = $_SESSION['correo']; // Obtiene el correo del usuario desde la sesión

// Verifica si se ha enviado el formulario para agregar una nueva tarea
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nueva_tarea'])) {
    // Limpia los datos de la nueva tarea antes de insertarlos en la base de datos
    $tarea = trim($_POST['tarea']);
    $descripcion = trim($_POST['descripcion']);
    $fecha = $_POST['fecha'];

    // Si todos los campos están completos, inserta la tarea en la base de datos
    if ($tarea && $descripcion && $fecha) {
        // Prepara y ejecuta la consulta SQL para insertar la tarea
        $stmt = $conexionsql->prepare('INSERT INTO tareas (correo, tarea, descripcion, fecha, estado) VALUES (:correo, :tarea, :descripcion, :fecha, "Pendiente")');
        $stmt->execute(['correo' => $correo, 'tarea' => $tarea, 'descripcion' => $descripcion, 'fecha' => $fecha]);
    }
}

// Verifica si se ha recibido una acción para completar o eliminar una tarea
if (isset($_GET['accion'], $_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id']; // Obtiene el ID de la tarea desde la URL

    // Si la acción es "completar", cambia el estado de la tarea a "Completado"
    if ($_GET['accion'] === 'completar') {
        $stmt = $conexionsql->prepare('UPDATE tareas SET estado="Completado" WHERE id=:id AND correo=:correo');
        $stmt->execute(['id' => $id, 'correo' => $correo]);
    }
    // Si la acción es "eliminar", elimina la tarea de la base de datos
    elseif ($_GET['accion'] === 'eliminar') {
        $stmt = $conexionsql->prepare('DELETE FROM tareas WHERE id=:id AND correo=:correo');
        $stmt->execute(['id' => $id, 'correo' => $correo]);
    }
}

// Obtiene todas las tareas del usuario desde la base de datos
$stmt = $conexionsql->prepare('SELECT * FROM tareas WHERE correo=:correo ORDER BY fecha ASC');
$stmt->execute(['correo' => $correo]); // Ejecuta la consulta con el correo del usuario
$tareas = $stmt->fetchAll(); // Obtiene todas las tareas asociadas al usuario
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Gestión de Tareas</title> 
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <h2>Gestión de Tareas</h2> 
    <nav>
        <button onclick="window.location.href='index.php'">Inicio</button> 
        <button onclick="window.location.href='logout.php'">Cerrar Sesión</button> 
    </nav>
    <form method="POST" action="tareas.php">
        
        <input type="hidden" name="nueva_tarea" value="1"> 
        <label>Tarea:</label><input type="text" name="tarea" required><br> 
        <label>Descripción:</label><textarea name="descripcion" required></textarea><br> 
        <label>Fecha:</label><input type="date" name="fecha" required><br> 
        <button type="submit">Agregar Tarea</button> 
    </form>
    <h3>Mis Tareas</h3> 
    <ul>
    <?php foreach ($tareas as $t) { ?>
        <li>
            <?= htmlspecialchars($t['tarea']) ?> - <?= htmlspecialchars($t['descripcion']) ?> - <?= $t['fecha'] ?> - <?= $t['estado'] ?>
            <?php if ($t['estado'] === 'Pendiente') { ?>
                <!-- Si la tarea está pendiente, muestra un enlace para marcarla como completada -->
                <a href="tareas.php?accion=completar&id=<?= $t['id'] ?>">Completar</a>
            <?php } ?>
            <!-- Enlace para eliminar la tarea, con confirmación previa -->
            <a href="tareas.php?accion=eliminar&id=<?= $t['id'] ?>" onclick="return confirm('¿Eliminar esta tarea?')">Eliminar</a>
        </li>
    <?php } ?>
    </ul>
</body>
</html>
