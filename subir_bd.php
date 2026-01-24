<?php
// Script de importación de BD para CLI (Terminal)

echo "\n=========================================\n";
echo "   SUBIDA DE BASE DE DATOS (VIA PHP)     \n";
echo "=========================================\n\n";

// Función para pedir input
function prompt($mensaje) {
    echo $mensaje . ": ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    fclose($handle);
    return trim($line);
}

// 1. Pedir Credenciales
$host = prompt("Host (ej: nozomi.proxy.rlwy.net)");
$port = prompt("Puerto (ej: 52241)");
$user = prompt("Usuario (ej: root)");
$pass = prompt("Contraseña");
$dbname = prompt("Nombre BD (ej: railway)");

echo "\nConectando...\n";

try {
    // Usamos PDO que suele tener mejor soporte de drivers
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "¡Conexión Exitosa!\n\n";

    echo "Leyendo database_structure.sql...\n";
    $sqlFile = __DIR__ . '/database_structure.sql';
    
    if (!file_exists($sqlFile)) {
        die("Error: No encuentro el archivo database_structure.sql\n");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Limpieza básica de comentariosSQL que a veces molestan en ejecución masiva
    // Pero PDO no soporta multi-query real, hay que separar por ;
    // Esta es una separación rudimentaria pero suele funcionar para dumps simples
    
    echo "Ejecutando consultas...\n";
    
    // Ejecutar todo el bloque. 
    // Nota: mysql:host permite Multi-statements si se configura, pero PDO::exec a veces falla con bloques grandes.
    // Intentemos ejecutarlo completo primero.
    
    // Ajuste para permitir múltiples sentencias si el driver lo soporta
    // Forzamos emulación para splits
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true); 
    
    $pdo->exec($sql);
    
    echo "\n✅ ¡IMPORTACIÓN COMPLETADA CON ÉXITO!\n";
    echo "Las tablas deberían estar creadas en Railway.\n";

} catch (PDOException $e) {
    echo "\n❌ ERROR DE CONEXIÓN O SQL:\n";
    echo $e->getMessage() . "\n";
}

echo "\nPresiona Enter para salir...";
fgets(fopen("php://stdin", "r"));
?>
