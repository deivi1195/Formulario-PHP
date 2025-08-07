<?php
session_start();
require_once 'conexion.php';

// Función para validar nombre y apellido (solo letras y espacios)
function validarTexto($texto) {
    return preg_match('/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/', $texto);
}

// Función para validar cédula (8 dígitos)
function validarCedula($cedula) {
    return preg_match('/^\d{8}$/', $cedula);
}

// Función para validar correo electrónico
function validarCorreo($correo) {
    return filter_var($correo, FILTER_VALIDATE_EMAIL);
}

// Función para validar contraseña
function validarContrasena($contrasena) {
    return preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d!@#$%^&*()_+]{8,}$/', $contrasena);
}

// Inicializar array para errores
$errores = [];

// Validar datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y limpiar datos
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $cedula = trim($_POST['cedula'] ?? '');
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
    $correo = trim($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $confirmar_contrasena = $_POST['confirmar_contrasena'] ?? '';
    
    // Validar nombre
    if (empty($nombre) || !validarTexto($nombre)) {
        $errores[] = 'El nombre solo debe contener letras y espacios';
    }
    
    // Validar apellido
    if (empty($apellido) || !validarTexto($apellido)) {
        $errores[] = 'El apellido solo debe contener letras y espacios';
    }
    
    // Validar cédula
    if (empty($cedula) || !validarCedula($cedula)) {
        $errores[] = 'La cédula debe tener exactamente 8 dígitos';
    }
    
    // Validar fecha de nacimiento
    if (empty($fecha_nacimiento)) {
        $errores[] = 'La fecha de nacimiento es obligatoria';
    } else {
        $fecha_nac = new DateTime($fecha_nacimiento);
        $hoy = new DateTime();
        if ($fecha_nac >= $hoy) {
            $errores[] = 'La fecha de nacimiento debe ser anterior a la fecha actual';
        }
    }
    
    // Validar correo electrónico
    if (empty($correo) || !validarCorreo($correo)) {
        $errores[] = 'Por favor ingrese un correo electrónico válido';
    }
    
    // Validar contraseña
    if (empty($contrasena) || !validarContrasena($contrasena)) {
        $errores[] = 'La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial';
    }
    
    // Validar confirmación de contraseña
    if ($contrasena !== $confirmar_contrasena) {
        $errores[] = 'Las contraseñas no coinciden';
    }
    
    // Si no hay errores, proceder con la inserción en la base de datos
    if (empty($errores)) {
        try {
            // Verificar si la cédula ya existe
            $consulta = $conexion->prepare("SELECT id FROM usuarios WHERE cedula = ?");
            $consulta->execute([$cedula]);
            if ($consulta->rowCount() > 0) {
                $errores[] = 'La cédula ya está registrada';
            }
            
            // Verificar si el correo ya existe
            $consulta = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
            $consulta->execute([$correo]);
            if ($consulta->rowCount() > 0) {
                $errores[] = 'El correo electrónico ya está registrado';
            }
            
            // Si no hay errores, insertar en la base de datos
            if (empty($errores)) {
                // Hashear la contraseña
                $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
                
                $consulta = $conexion->prepare("
                    INSERT INTO usuarios (nombre, apellido, cedula, fecha_nacimiento, correo, contrasena) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                
                $consulta->execute([
                    $nombre,
                    $apellido,
                    $cedula,
                    $fecha_nacimiento,
                    $correo,
                    $contrasena_hash
                ]);
                
                // Redirigir con mensaje de éxito
                $_SESSION['registro_exitoso'] = '¡Registro exitoso! Ahora puedes iniciar sesión.';
                header('Location: inicio.php');
                exit();
            }
            
        } catch (PDOException $error) {
            $errores[] = 'Error en la base de datos: ' . $error->getMessage();
        }
    }
    
    // Si hay errores, guardarlos en sesión y redirigir
    if (!empty($errores)) {
        $_SESSION['errores'] = $errores;
        // Guardar los valores del formulario para mostrarlos de nuevo
        $_SESSION['datos_formulario'] = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'cedula' => $cedula,
            'fecha_nacimiento' => $fecha_nacimiento,
            'correo' => $correo
        ];
        header('Location: registro.php');
        exit();
    }
} else {
    // Si se accede directamente al archivo sin enviar el formulario
    header('Location: registro.php');
    exit();
}
?>
