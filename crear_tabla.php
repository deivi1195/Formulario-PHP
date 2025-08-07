<?php
require_once 'conexion.php';

try {
    // Crear la base de datos si no existe
    $sql = "CREATE DATABASE IF NOT EXISTS formulario_db";
    $conexion->exec($sql);
    
    // Seleccionar la base de datos
    $conexion->exec("USE formulario_db");
    
    // Crear la tabla usuarios
    $sql = "CREATE TABLE IF NOT EXISTS usuarios (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(50) NOT NULL,
        apellido VARCHAR(50) NOT NULL,
        cedula VARCHAR(8) NOT NULL UNIQUE,
        fecha_nacimiento DATE NOT NULL,
        correo VARCHAR(100) NOT NULL UNIQUE,
        contrasena VARCHAR(255) NOT NULL,
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $conexion->exec($sql);
    echo "Base de datos y tabla creadas correctamente";
    
} catch(PDOException $e) {
    die("Error al crear la tabla: " . $e->getMessage());
}
?>
