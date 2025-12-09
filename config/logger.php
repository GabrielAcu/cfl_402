<?php
/**
 * ============================================
 * SISTEMA DE LOGGING BÁSICO
 * ============================================
 * 
 * Registra eventos importantes del sistema:
 * - Intentos de login (exitosos y fallidos)
 * - Errores críticos
 * - Actividades de usuarios (opcional)
 * 
 * ============================================
 */

function logEvent($message, $level = 'INFO', $context = []) {
    $logDir = __DIR__ . '/../logs';
    
    // Crear directorio de logs si no existe
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    // Nombre del archivo de log (un archivo por día)
    $logFile = $logDir . '/app_' . date('Y-m-d') . '.log';
    
    // Obtener IP del cliente
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($ips[0]);
    }
    
    // Obtener usuario si está en sesión
    $user = 'anonymous';
    if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['user'])) {
        $user = $_SESSION['user']['name'] ?? 'unknown';
    }
    
    // Formato del log: [Fecha Hora] [Nivel] [IP] [Usuario] Mensaje [Contexto]
    $timestamp = date('Y-m-d H:i:s');
    $contextStr = !empty($context) ? ' ' . json_encode($context) : '';
    $logEntry = "[$timestamp] [$level] [$ip] [$user] $message$contextStr" . PHP_EOL;
    
    // Escribir en el archivo de log
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

function logError($message, $exception = null, $context = []) {
    $context['exception'] = $exception ? $exception->getMessage() : null;
    logEvent($message, 'ERROR', $context);
}

function logWarning($message, $context = []) {
    logEvent($message, 'WARNING', $context);
}

function logInfo($message, $context = []) {
    logEvent($message, 'INFO', $context);
}

function logLoginAttempt($username, $success, $reason = null) {
    $context = [
        'username' => $username,
        'success' => $success,
        'reason' => $reason
    ];
    $level = $success ? 'INFO' : 'WARNING';
    $message = $success ? "Login exitoso" : "Intento de login fallido";
    logEvent($message, $level, $context);
}

function logUserAction($action, $details = []) {
    $context = array_merge(['action' => $action], $details);
    logEvent("Acción de usuario: $action", 'INFO', $context);
}

