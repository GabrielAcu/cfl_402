<?php
require_once __DIR__ . '/../../config/conexion.php';

if (!isSuperAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}

/**
 * Verifica usuario y contraseña en la base de datos.
 * Retorna:
 * - 1 → administrador
 * - 2 → instructor
 * - false → credenciales inválidas
 */
function verificarUsuario(string $usuario, string $password): int|false
{
    // Obtenemos la conexión desde la función conectar()
    $pdo = conectar();

    // Ajuste de nombres de columnas según tu base de datos
    $sql = "SELECT * FROM usuarios WHERE nombre = :nombre AND activo = 1 LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $usuario, PDO::PARAM_STR);
    $stmt->execute();
    $usuarioDB = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificamos credenciales (de momento sin hash)
    if ($usuarioDB && $usuarioDB['contrasenia'] === $password) {
        return (int)$usuarioDB['rol'];
    }

    return false;
}
