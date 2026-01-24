<?php
// Script de diagnóstico para ver qué headers recibe Railway
header('Content-Type: text/plain');

echo "=== DIAGNÓSTICO DE HEADERS ===\n\n";

echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'NO DEFINIDO') . "\n";
echo "HTTP_X_FORWARDED_HOST: " . ($_SERVER['HTTP_X_FORWARDED_HOST'] ?? 'NO DEFINIDO') . "\n";
echo "HTTP_X_FORWARDED_PROTO: " . ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'NO DEFINIDO') . "\n";
echo "HTTPS: " . ($_SERVER['HTTPS'] ?? 'NO DEFINIDO') . "\n";
echo "SERVER_PORT: " . ($_SERVER['SERVER_PORT'] ?? 'NO DEFINIDO') . "\n";
echo "RAILWAY_ENVIRONMENT: " . (getenv('RAILWAY_ENVIRONMENT') ?: 'NO DEFINIDO') . "\n\n";

// Simular la lógica de path.php
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
             (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) 
            ? "https" : "http";

$host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost';

echo "PROTOCOLO DETECTADO: $protocol\n";
echo "HOST DETECTADO: $host\n";

if (strpos($host, 'railway.app') !== false || getenv('RAILWAY_ENVIRONMENT')) {
    $host_no_port = explode(':', $host)[0];
    $base_url = $protocol . "://" . $host_no_port;
    echo "MODO: Railway\n";
    echo "HOST SIN PUERTO: $host_no_port\n";
} else {
    $base_url = $protocol . "://" . $host . "/cfl_402";
    echo "MODO: Local\n";
}

echo "BASE_URL GENERADA: $base_url\n";
