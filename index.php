<?php
session_start();

// Si el usuario ya está logueado, redirigir al dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Iniciar Sesión</h2>
            <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
            
            <?php 
            // Mostrar mensajes de error
            if (isset($_SESSION['error_login'])) {
                echo '<div class="error">' . htmlspecialchars($_SESSION['error_login']) . '</div>';
                unset($_SESSION['error_login']);
            }
            
            // Mostrar mensaje de éxito de registro
            if (isset($_SESSION['registro_exitoso'])) {
                echo '<div class="success">' . htmlspecialchars($_SESSION['registro_exitoso']) . '</div>';
                unset($_SESSION['registro_exitoso']);
            }
            ?>
            
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" required>
                </div>
                
                <div class="form-group">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" required>
                </div>
                
                <div class="form-group">
                    <button type="submit">Iniciar Sesión</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
