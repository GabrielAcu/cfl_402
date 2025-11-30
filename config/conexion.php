<?php
require_once 'env.php';
function conectar(){
    $servidor = getenv("DB_HOST");
    $nombreBaseDeDatos = getenv("DB_NAME");
    $usuario = getenv("DB_USER");
    $contrasena = getenv("DB_PASS");

    try {
        $dsn = "mysql:host=$servidor;dbname=$nombreBaseDeDatos;charset=utf8";
        $opciones = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $conexion = new PDO($dsn, $usuario, $contrasena, $opciones);
        return $conexion;
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
        exit;
    }
}
?>
