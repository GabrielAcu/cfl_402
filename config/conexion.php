<?php
// Cargar variables de entorno locales si existen (Dev)
// En producción (Railway), las variables vienen del servidor, y este archivo no existe (está en .gitignore)
$envFile = __DIR__ . '/env.php';
if (file_exists($envFile)) {
    require_once $envFile;
}
function conectar(){
    $servidor = getenv("DB_HOST");
    $nombreBaseDeDatos = getenv("DB_NAME");
    $usuario = getenv("DB_USER");
    $contrasena = getenv("DB_PASS");

    // Intentar leer variables individuales primero (Estándar o Railway)
    $servidor = getenv("DB_HOST") ?: getenv("MYSQLHOST");
    $nombreBaseDeDatos = getenv("DB_NAME") ?: getenv("MYSQLDATABASE");
    $usuario = getenv("DB_USER") ?: getenv("MYSQLUSER");
    $contrasena = getenv("DB_PASS") ?: getenv("MYSQLPASSWORD");
    $puerto = getenv("DB_PORT") ?: getenv("MYSQLPORT");

    if ($puerto && $servidor) {
        $servidor = "$servidor:$puerto";
    }

    // Si no están, buscar una URL de conexión (Típico de Railway/Heroku)
    // Ejemplo: mysql://usuario:pass@host:port/nombre_db
    if (!$servidor && ($url = getenv("MYSQL_URL") ?: getenv("DATABASE_URL"))) {
        $parsed = parse_url($url);
        $servidor = $parsed['host'];
        if (isset($parsed['port'])) {
            $servidor .= ":" . $parsed['port']; // Añadir puerto si existe
        }
        $nombreBaseDeDatos = ltrim($parsed['path'], '/');
        $usuario = $parsed['user'];
        $contrasena = $parsed['pass'];
    }

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
        // Error de conexión - mostrar mensaje genérico en producción
        die("Error de conexión a la Base de Datos. Por favor contacte al administrador.");
    }
}
?>
