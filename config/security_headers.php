<?php
/**
 * ============================================
 * HEADERS DE SEGURIDAD HTTP
 * ============================================
 * 
 * Este archivo establece headers de seguridad
 * para proteger contra varios tipos de ataques.
 * 
 * Incluir este archivo al inicio de cada página PHP
 * (antes de cualquier output)
 * 
 * ============================================
 */

// Prevenir que el navegador detecte el tipo MIME incorrectamente
header('X-Content-Type-Options: nosniff');

// Prevenir clickjacking (no permitir que la página se cargue en iframes)
header('X-Frame-Options: DENY');

// Habilitar protección XSS del navegador
header('X-XSS-Protection: 1; mode=block');

// Política de referrer (controlar qué información se envía en el header Referer)
header('Referrer-Policy: strict-origin-when-cross-origin');

// Política de permisos (limitar acceso a APIs del navegador)
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');

// Content Security Policy básica (ajustar según necesidades)
// Esta es una política básica, puede necesitar ajustes según los recursos que uses
$csp = "default-src 'self'; " .
       "script-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
       "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
       "font-src 'self' https://fonts.gstatic.com; " .
       "img-src 'self' data:; " .
       "connect-src 'self';";
header("Content-Security-Policy: $csp");

// Solo en producción con HTTPS, descomentar:
// header('Strict-Transport-Security: max-age=31536000; includeSubDomains');

