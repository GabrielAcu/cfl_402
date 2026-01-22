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

    // Intentar leer variables individuales primero
    $servidor = getenv("DB_HOST");
    $nombreBaseDeDatos = getenv("DB_NAME");
    $usuario = getenv("DB_USER");
    $contrasena = getenv("DB_PASS");

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
        // TEMPORAL: Mostrar error detallado para debugging en Railway
        $errorMsg = "<h2>Error de Conexión a Base de Datos</h2>";
        $errorMsg .= "<p><strong>Mensaje de error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        $errorMsg .= "<h3>Variables de entorno:</h3>";
        $errorMsg .= "<pre>";
        $errorMsg .= "MYSQL_URL: " . (getenv("MYSQL_URL") ? "✅ Configurada" : "❌ NO configurada") . "\n";
        $errorMsg .= "DATABASE_URL: " . (getenv("DATABASE_URL") ? "✅ Configurada" : "❌ NO configurada") . "\n";
        $errorMsg .= "DB_HOST: " . (getenv("DB_HOST") ?: "NO configurada") . "\n";
        $errorMsg .= "DB_NAME: " . (getenv("DB_NAME") ?: "NO configurada") . "\n";
        $errorMsg .= "DB_USER: " . (getenv("DB_USER") ?: "NO configurada") . "\n";
        $errorMsg .= "DB_PASS: " . (getenv("DB_PASS") ? "***existe***" : "NO configurada") . "\n";
        $errorMsg .= "\nDSN usado: " . htmlspecialchars($dsn) . "\n";
        $errorMsg .= "Usuario usado: " . htmlspecialchars($usuario) . "\n";
        $errorMsg .= "</pre>";
        $errorMsg .= "<p><em>Este mensaje detallado es temporal para debugging. Será removido después.</em></p>";
        die($errorMsg);
    }
}
?>
