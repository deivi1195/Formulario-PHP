<?php
$host = 'localhost';
$dbname = 'formulario_db';
$username = 'root';
$password = '';

try {
    $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->exec("SET CHARACTER SET utf8");
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
