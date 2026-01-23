<?php
// Ruta absoluta del proyecto
define("BASE_PATH", realpath(__DIR__ . "/.."));

// Detectar el protocolo de forma segura (soporte para proxies/Railway)
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
             (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) 
            ? "https" : "http";

// Host actual
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// URL base del proyecto (útil para enlaces)
// URL base del proyecto
// Si estamos en Railway (o producción en raíz), no usamos subcarpeta
if (strpos($host, 'railway.app') !== false || getenv('RAILWAY_ENVIRONMENT')) {
    define("BASE_URL", $protocol . "://" . $host);
} else {
    // En local (XAMPP), usamos la subcarpeta del proyecto
    define("BASE_URL", $protocol . "://" . $host . "/cfl_402");
}

// Incluir headers de seguridad globales
require_once __DIR__ . '/security_headers.php';