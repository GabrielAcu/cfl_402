<?php
/**
 * ============================================
 * SISTEMA DE RATE LIMITING
 * ============================================
 * 
 * Previene ataques de fuerza bruta limitando
 * el número de intentos de login por IP
 * 
 * ============================================
 */

function checkRateLimit($maxAttempts = 5, $timeWindow = 300) {
    // Obtener IP del cliente
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    
    // Si hay proxy, intentar obtener IP real
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($ips[0]);
    }
    
    // Archivo de log para rate limiting (en directorio temporal o logs)
    $logDir = __DIR__ . '/../logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logFile = $logDir . '/rate_limit_' . md5($ip) . '.json';
    
    // Leer intentos previos
    $attempts = [];
    if (file_exists($logFile)) {
        $content = file_get_contents($logFile);
        $attempts = json_decode($content, true) ?: [];
    }
    
    // Limpiar intentos antiguos (fuera de la ventana de tiempo)
    $currentTime = time();
    $attempts = array_filter($attempts, function($timestamp) use ($currentTime, $timeWindow) {
        return ($currentTime - $timestamp) < $timeWindow;
    });
    
    // Contar intentos en la ventana de tiempo
    $attemptCount = count($attempts);
    
    // Si excede el límite, bloquear
    if ($attemptCount >= $maxAttempts) {
        $remainingTime = $timeWindow - ($currentTime - min($attempts));
        return [
            'allowed' => false,
            'remaining_time' => max(0, $remainingTime),
            'message' => "Demasiados intentos. Por favor, espere " . ceil($remainingTime / 60) . " minutos."
        ];
    }
    
    // Registrar nuevo intento
    $attempts[] = $currentTime;
    file_put_contents($logFile, json_encode($attempts), LOCK_EX);
    
    return [
        'allowed' => true,
        'attempts' => $attemptCount + 1,
        'remaining' => $maxAttempts - ($attemptCount + 1)
    ];
}

function clearRateLimit($ip = null) {
    // Limpiar rate limit después de login exitoso
    if ($ip === null) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = trim($ips[0]);
        }
    }
    
    $logDir = __DIR__ . '/../logs';
    $logFile = $logDir . '/rate_limit_' . md5($ip) . '.json';
    
    if (file_exists($logFile)) {
        unlink($logFile);
    }
}

