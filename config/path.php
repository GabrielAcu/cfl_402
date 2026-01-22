<?php
// Ruta absoluta del proyecto
define("BASE_PATH", realpath(__DIR__ . "/.."));

// Detectar el protocolo de forma segura
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";

// Host actual
$host = $_SERVER['HTTP_HOST'];

// URL base del proyecto (útil para enlaces)
define("BASE_URL", $protocol . "://" . $host . "/cfl_402");

// Incluir headers de seguridad globales
require_once __DIR__ . '/security_headers.php';