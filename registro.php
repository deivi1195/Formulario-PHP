<?php
session_start();

// Si el usuario ya está autenticado, redirigir al panel
if (isset($_SESSION['usuario_id'])) {
    header('Location: panel.php');
    exit();
}

// Obtener datos del formulario si existen en la sesión
$datos_formulario = $_SESSION['datos_formulario'] ?? [
    'nombre' => '',
    'apellido' => '',
    'cedula' => '',
    'fecha_nacimiento' => '',
    'correo' => ''
];

// Obtener errores si existen
$errores = $_SESSION['errores'] ?? [];
unset($_SESSION['errores']);
unset($_SESSION['datos_formulario']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario - Sistema de Usuarios</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="contenedor">
        <div class="contenedor-formulario">
            <h1>Registro de Usuario</h1>
            <p>Por favor completa el siguiente formulario para registrarte.</p>
            
            <?php if (!empty($errores)): ?>
                <div class="error">
                    <ul>
                        <?php foreach ($errores as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form action="validaciones.php" method="POST" id="formularioRegistro">
                <div class="grupo-formulario">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required 
                           value="<?php echo htmlspecialchars($datos_formulario['nombre']); ?>">
                </div>
                
                <div class="grupo-formulario">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" required
                           value="<?php echo htmlspecialchars($datos_formulario['apellido']); ?>">
                </div>
                
                <div class="grupo-formulario">
                    <label for="cedula">Cédula:</label>
                    <input type="text" id="cedula" name="cedula" required maxlength="8"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                           onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                           value="<?php echo htmlspecialchars($datos_formulario['cedula']); ?>">
                    <div class="requisitos">Solo números, sin puntos ni guiones.</div>
                </div>
                
                <div class="grupo-formulario">
                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required
                           value="<?php echo htmlspecialchars($datos_formulario['fecha_nacimiento']); ?>">
                </div>
                
                <div class="grupo-formulario">
                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" required
                           value="<?php echo htmlspecialchars($datos_formulario['correo']); ?>">
                </div>
                
                <div class="grupo-formulario">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" required>
                    <div class="requisitos">
                        La contraseña debe tener al menos 8 caracteres, incluyendo:<br>
                        - Una letra mayúscula<br>
                        - Un número<br>
                        - Un carácter especial (!@#$%^&*)
                    </div>
                </div>
                
                <div class="grupo-formulario">
                    <label for="confirmar_contrasena">Confirmar Contraseña:</label>
                    <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required>
                </div>
                
                <div class="grupo-formulario">
                    <button type="submit">Registrarse</button>
                </div>
            </form>
            
            <p class="texto-centrado">¿Ya tienes una cuenta? <a href="inicio.php">Inicia sesión aquí</a></p>
        </div>
    </div>

    <script>
        // Validación del lado del cliente
        document.getElementById('formularioRegistro').addEventListener('submit', function(e) {
            const contrasena = document.getElementById('contrasena').value;
            const confirmarContrasena = document.getElementById('confirmar_contrasena').value;
            const regexContrasena = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d!@#$%^&*()_+]{8,}$/;
            
            if (contrasena !== confirmarContrasena) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                return false;
            }
            
            if (!regexContrasena.test(contrasena)) {
                e.preventDefault();
                alert('La contraseña no cumple con los requisitos mínimos');
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>
