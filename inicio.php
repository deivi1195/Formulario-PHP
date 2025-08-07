<?php
session_start();

// Si el usuario ya está autenticado, redirigir al panel
if (isset($_SESSION['usuario_id'])) {
    header('Location: panel.php');
    exit();
}

// Mostrar mensaje de cierre de sesión si existe
if (isset($_GET['cierre_sesion'])) {
    $mensaje_exito = 'Has cerrado sesión correctamente.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Usuarios</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="contenedor">
        <div class="contenedor-formulario">
            <h1>Iniciar Sesión</h1>
            
            <?php 
            // Mostrar mensaje de cierre de sesión
            if (isset($mensaje_exito)) {
                echo '<div class="exito">' . htmlspecialchars($mensaje_exito) . '</div>';
            }

            // Mostrar mensajes de error
            if (isset($_SESSION['error_login'])) {
                echo '<div class="error">' . htmlspecialchars($_SESSION['error_login']) . '</div>';
                unset($_SESSION['error_login']);
            }
            
            // Mostrar mensaje de éxito de registro
            if (isset($_SESSION['registro_exitoso'])) {
                echo '<div class="exito">' . htmlspecialchars($_SESSION['registro_exitoso']) . '</div>';
                unset($_SESSION['registro_exitoso']);
            }
            ?>
            
            <form action="login.php" method="post">
                <div class="grupo-formulario">
                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" required>
                </div>
                
                <div class="grupo-formulario">
                    <button type="submit">Iniciar Sesión</button>
                </div>
            </form>
            
            <p class="texto-centrado">¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
        </div>
    </div>
</body>
</html>
