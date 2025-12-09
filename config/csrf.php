<?php
/**
 * ============================================
 * PROTECCIÓN CSRF (Cross-Site Request Forgery)
 * ============================================
 * 
 * Genera y valida tokens CSRF para proteger
 * formularios contra ataques CSRF
 * 
 * ============================================
 */

function generateCSRFToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Generar token si no existe
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

function getCSRFTokenField() {
    $token = generateCSRFToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

function validateCSRFToken($token = null) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Obtener token del POST si no se proporciona
    if ($token === null) {
        $token = $_POST['csrf_token'] ?? null;
    }
    
    // Verificar que existe token en sesión
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    
    // Verificar que el token recibido no sea null
    if ($token === null || !is_string($token)) {
        return false;
    }
    
    // Comparar tokens (comparación segura)
    return hash_equals($_SESSION['csrf_token'], $token);
}

function requireCSRFToken() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!validateCSRFToken()) {
            http_response_code(403);
            die('Error: Token CSRF inválido. Por favor, recargue la página e intente nuevamente.');
        }
    }
}

// Regenerar token periódicamente para mayor seguridad
function regenerateCSRFToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    unset($_SESSION['csrf_token']);
    return generateCSRFToken();
}

