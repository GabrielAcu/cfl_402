<?php
function conectar(){
    $servidor="localhost";
    $nombreBaseDatos="cfl402_2025";
    $usuario="root";
    $contrasena="";
    try {
        $dsn="mysql:host=$servidor;dbname=$nombreBaseDatos;charset=utf8mb4";
        $opciones=[
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        $conexion= new PDO($dsn,$usuario, $contrasena, $opciones);
        return $conexion;
    } catch (PDOException $e) {
        echo "Error en la conexión: ".$e->getMessage();
        exit;
    }
}
?>