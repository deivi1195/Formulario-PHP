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
    
    // Si hay errores, guardarlos en la sesión
    if (!empty($errores)) {
        $_SESSION['error_login'] = implode('<br>', $errores);
        header('Location: inicio.php');
        exit();
    }
} else {
    // Si se accede directamente al archivo sin enviar el formulario
    header('Location: inicio.php');
    exit();
}
?>
<link rel="stylesheet" href="styles.css">
