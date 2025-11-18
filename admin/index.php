<?php
// 1. Cargar la configuración de rutas
require_once __DIR__ . '/../config/path.php';

// 2. Cargar dependencias usando rutas absolutas
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

// 3. Autenticación
requireLogin();

if (!isAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}

// 4. Conexión a la base de datos
$conn = conectar();
?>



<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - CFL402</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/panel.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/header.css">
  </head>
  <body class="hide-crud-links">
    <h1>Panel de Administración</h1>
    
    <main>
      <section class="panel-container">
        <a href="../admin/crud/alumnos/index.php" class="card">
        <h2>👨‍🎓 Alumnos</h2>
        <p>Gestión completa de los alumnos registrados.</p>
      </a>
      
      <a href="../admin/crud/instructores/index.php" class="card">
        <h2>👩‍🏫 Instructores</h2>
        <p>Administrar docentes e instructores.</p>
      </a>
      
      <a href="../admin/crud/cursos/index.php" class="card">
        <h2>📘 Cursos</h2>
        <p>Alta, baja y modificación de cursos disponibles.</p>
      </a>
      
      <!-- <a href="../crud/inscripciones/index.php" class="card">
        <h2>🧾 Inscripciones</h2>
        <p>Vincular alumnos con cursos.</p>
      </a> -->
      
      <!-- <a href="../crud/horarios/index.php" class="card">
        <h2>⏰ Horarios</h2>
        <p>Definir días y horas de cursado.</p>
      </a> -->
      
      <a href="../admin/crud/usuarios/index.php" class="card">
        <h2>🔐 Usuarios</h2>
        <p>Gestión de accesos al sistema.</p>
      </a>
    </section>
  </main>

  
  <footer>
    <p>© 2025 CFL402 - Sistema Educativo</p>
  </footer>
  <script src="<?= BASE_URL ?>/assets/js/header.js" defer></script>
</body>
</html>
