<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    // Si no ha iniciado sesión, redirigir al login
    header('Location: login.php');
    exit();
}

$nombre_usuario = $_SESSION['usuario_nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="contenedor-panel">
        <h1>Panel de Control</h1>
        
        <div class="mensaje-bienvenida">
            ¡Hola, <?php echo htmlspecialchars($nombre_usuario); ?>!
        </div>
        
        <div class="info-usuario">
            <h3>Información de tu cuenta:</h3>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($nombre_usuario); ?></p>
            <p>Has iniciado sesión correctamente en el sistema.</p>
        </div>
        
        <a href="logout.php" class="boton-salir">Cerrar Sesión</a>
    </div>
</body>
</html>
