<?php
// Script de diagnóstico para verificar la conexión a Railway MySQL
// Este archivo debe ejecutarse SOLO en Railway, no en local

echo "<h1>Diagnóstico de Conexión a Base de Datos - Railway</h1>";

echo "<h2>1. Variables de Entorno</h2>";
echo "<pre>";

// Verificar si existe MYSQL_URL
$mysql_url = getenv("MYSQL_URL");
$database_url = getenv("DATABASE_URL");

if ($mysql_url) {
    echo "✅ MYSQL_URL está configurada\n";
    // Mostrar solo los primeros caracteres por seguridad
    echo "   Valor: " . substr($mysql_url, 0, 20) . "...\n\n";
} else {
    echo "❌ MYSQL_URL NO está configurada\n\n";
}

if ($database_url) {
    echo "✅ DATABASE_URL está configurada\n";
    echo "   Valor: " . substr($database_url, 0, 20) . "...\n\n";
} else {
    echo "❌ DATABASE_URL NO está configurada\n\n";
}

// Verificar variables individuales
echo "Variables individuales:\n";
echo "DB_HOST: " . (getenv("DB_HOST") ?: "NO configurada") . "\n";
echo "DB_NAME: " . (getenv("DB_NAME") ?: "NO configurada") . "\n";
echo "DB_USER: " . (getenv("DB_USER") ?: "NO configurada") . "\n";
echo "DB_PASS: " . (getenv("DB_PASS") ? "***configurada***" : "NO configurada") . "\n";

echo "</pre>";

echo "<h2>2. Intentando Parsear MYSQL_URL</h2>";
echo "<pre>";

$url = $mysql_url ?: $database_url;

if ($url) {
    $parsed = parse_url($url);
    
    echo "Host: " . ($parsed['host'] ?? 'NO ENCONTRADO') . "\n";
    echo "Port: " . ($parsed['port'] ?? 'NO ENCONTRADO') . "\n";
    echo "User: " . ($parsed['user'] ?? 'NO ENCONTRADO') . "\n";
    echo "Pass: " . (isset($parsed['pass']) ? '***existe***' : 'NO ENCONTRADO') . "\n";
    echo "Path (DB): " . ($parsed['path'] ?? 'NO ENCONTRADO') . "\n";
    
    $dbname = isset($parsed['path']) ? ltrim($parsed['path'], '/') : '';
    echo "Nombre de BD extraído: " . ($dbname ?: 'NO ENCONTRADO') . "\n";
} else {
    echo "❌ No hay URL de conexión disponible\n";
}

echo "</pre>";

echo "<h2>3. Intentando Conectar a la Base de Datos</h2>";
echo "<pre>";

if ($url) {
    try {
        $parsed = parse_url($url);
        $host = $parsed['host'];
        if (isset($parsed['port'])) {
            $host .= ":" . $parsed['port'];
        }
        $dbname = ltrim($parsed['path'], '/');
        $user = $parsed['user'];
        $pass = $parsed['pass'];
        
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
        
        echo "Intentando conectar con DSN: mysql:host=$host;dbname=$dbname\n\n";
        
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        
        echo "✅ ¡CONEXIÓN EXITOSA!\n\n";
        
        // Verificar si hay tablas
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($tables) > 0) {
            echo "✅ Base de datos tiene " . count($tables) . " tablas:\n";
            foreach ($tables as $table) {
                echo "   - $table\n";
            }
        } else {
            echo "⚠️ Base de datos está VACÍA (sin tablas)\n";
            echo "   Necesitas importar database_structure.sql\n";
        }
        
    } catch (PDOException $e) {
        echo "❌ ERROR DE CONEXIÓN:\n";
        echo "   " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ No se puede intentar conexión sin URL\n";
}

echo "</pre>";

echo "<h2>4. Información del Sistema</h2>";
echo "<pre>";
echo "PHP Version: " . phpversion() . "\n";
echo "PDO MySQL disponible: " . (extension_loaded('pdo_mysql') ? 'SÍ' : 'NO') . "\n";
echo "</pre>";

echo "<hr>";
echo "<p><strong>IMPORTANTE:</strong> Elimina este archivo después de usarlo por seguridad.</p>";
?>
