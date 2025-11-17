<?php
// Cargar path.php desde crud/alumnos (2 niveles arriba)
require_once dirname(__DIR__, 2) . '/config/path.php';

// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

// Seguridad
requireLogin();

// Conexión
$conn = conectar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Cursos</title>
  
  <link rel="stylesheet" href="cursos.css">
</head>
<body>
  <h2>Gestión de Cursos</h2>

  <div class="acciones-superiores">
    <a href="agregar.php" class="btn-agregar">+ Nuevo curso</a>
  </div>

  <!-- Buscador dinámico -->
  <div class="form-busqueda">
    <input 
      type="text" 
      id="buscar" 
      placeholder="Buscar por nombre o código..."
      autocomplete="off"
    >
    <p>🔍</p>
  </div>

  <!-- loader -->
  <div id="spinner" aria-hidden="true" style="display:none;">
    <div class="spinner-inner" role="status" aria-live="polite">
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"></div>
      <span class="sr-only">Cargando...</span>
    </div>
  </div>

  <!-- Aquí se cargan los cursos -->
  <div id="resultado">
    <!-- listado inicial se inyecta vía JS -->
  </div>

  <script src="funciones.js"></script>
</body>
</html>
