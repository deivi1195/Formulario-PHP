<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    // Si no ha iniciado sesión, redirigir al login
    header('Location: index.php');
    exit();
}

$nombre_usuario = $_SESSION['usuario_nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .dashboard-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .welcome-message {
            font-size: 24px;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .user-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: left;
        }
        .logout-btn {
            display: inline-block;
            background: #e74c3c;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            margin-top: 20px;
            transition: background 0.3s;
        }
        .logout-btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Bienvenido al Dashboard</h1>
        
        <div class="welcome-message">
            ¡Hola, <?php echo htmlspecialchars($nombre_usuario); ?>!
        </div>
        
        <div class="user-info">
            <h3>Información de tu cuenta:</h3>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($nombre_usuario); ?></p>
            <p>Has iniciado sesión correctamente en el sistema.</p>
        </div>
        
        <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
    </div>
</body>
</html>
