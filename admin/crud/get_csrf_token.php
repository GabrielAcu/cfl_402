<?php
/**
 * Endpoint para obtener token CSRF (para formularios dinámicos en JavaScript)
 */
require_once dirname(__DIR__, 2) . '/config/path.php';
require_once BASE_PATH . '/config/csrf.php';
require_once BASE_PATH . '/auth/check.php';

// Autenticación
requireLogin();

header('Content-Type: application/json');
echo json_encode(['token' => generateCSRFToken()]);

