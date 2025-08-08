<?php
session_start();

// Si el usuario ya está autenticado, redirigir al panel
if (isset($_SESSION['usuario_id'])) {
    header('Location: panel.php');
    exit();
}

// Incluir archivo de conexión
require_once 'conexion.php';

// Inicializar variables
$correo = '';
$errores = [];

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y limpiar datos
    $correo = trim($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    
    // Validar campos
    if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Por favor ingrese un correo electrónico válido';
    }
    
    if (empty($contrasena)) {
        $errores[] = 'Por favor ingrese su contraseña';
    }
    
    // Si no hay errores, verificar credenciales
    if (empty($errores)) {
        try {
            // Buscar usuario por correo
            $consulta = $conexion->prepare("SELECT id, nombre, correo, contrasena FROM usuarios WHERE correo = ?");
            $consulta->execute([$correo]);
            $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
            
            // Verificar si el usuario existe y la contraseña es correcta
            if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
                // Iniciar sesión
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_correo'] = $usuario['correo'];
                
                // Redirigir al panel
                header('Location: panel.php');
                exit();
            } else {
                $errores[] = 'Correo o contraseña incorrectos';
            }
            
        } catch (PDOException $error) {
            $errores[] = 'Error al iniciar sesión. Por favor, inténtelo de nuevo más tarde.';
            // En un entorno de producción, registrar el error en un archivo de registro
            error_log('Error en login.php: ' . $error->getMessage());
        }
    }
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
    <div class="contenedor">
        <div class="contenedor-formulario">
            <h2>Iniciar Sesión</h2>
            
            <?php 
            // Mostrar mensaje de éxito de registro si existe
            if (isset($_SESSION['registro_exitoso'])) {
                echo '<div class="exito">' . htmlspecialchars($_SESSION['registro_exitoso']) . '</div>';
                unset($_SESSION['registro_exitoso']);
            }
            
            // Mostrar errores si existen
            if (!empty($errores)): ?>
                <div class="error">
                    <ul>
                        <?php foreach ($errores as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form action="login.php" method="POST">
                <div class="grupo-formulario">
                    <label for="correo">Correo electrónico:</label>
                    <input type="email" id="correo" name="correo" required 
                           value="<?php echo htmlspecialchars($correo); ?>">
                </div>
                
                <div class="grupo-formulario">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" required>
                </div>
                
                <div class="grupo-formulario">
                    <button type="submit">Iniciar Sesión</button>
                </div>
            </form>
            
            <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
        </div>
    </div>
</body>
</html>
