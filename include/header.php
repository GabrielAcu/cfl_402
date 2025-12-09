<?php
// header.php — Header general del sistema CFL_402
// Se incluye en todos los index de los CRUD y admin

// Asegurar que los headers de seguridad estén establecidos
if (!headers_sent()) {
    require_once __DIR__ . '/../config/security_headers.php';
}
?>

<header class="main-header">
  <div class="header-container">

    <!-- Logo -->
    <div class="logo">
      <a href="<?= BASE_URL ?>/admin/index.php">CFL 402</a>
    </div>

    <!-- Contenedor derecho -->
    <div class="right-section">
      <!-- Botón menú hamburguesa -->
      <button class="menu-toggle" id="menu-toggle" aria-label="Abrir menú">☰</button>
      <button id="toggleTheme" class="theme-btn">🌙</button>


      <!-- Menú principal -->
      <nav class="main-nav" id="main-nav">
        <ul>
          <li class="crud-link"><a href="<?= BASE_URL ?>/admin/index.php">Inicio</a></li>
          <li class="crud-link"><a href="<?= BASE_URL ?>/admin/crud/alumnos/index.php">Alumnos</a></li>
          <li class="crud-link"><a href="<?= BASE_URL ?>/admin/crud/instructores/index.php">Instructores</a></li>
          <li class="crud-link"><a href="<?= BASE_URL ?>/admin/crud/cursos/index.php">Cursos</a></li>
          <li class="crud-link"><a href="<?= BASE_URL ?>/admin/crud/usuarios/index.php">Usuarios</a></li>
          <li><a href="<?= BASE_URL ?>/auth/logout.php" class="btn-logout">Cerrar sesión</a></li>
          <!-- <li class="crud-link"><a href="<?= BASE_URL ?>/crud/horarios/index.php">Horarios</a></li> -->
          <!-- <li class="crud-link"><a href="<?= BASE_URL ?>/crud/inscripciones/index.php">Inscripciones</a></li> -->
        </ul>
      </nav>

      <!-- Bienvenida -->
    </div>

  </div>
</header>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css">

<script src="<?= BASE_URL ?>/assets/js/tema.js" defer></script>



<!-- Enlaces CSS y JS -->
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/header.css">
<script src="<?= BASE_URL ?>/assets/js/header.js" defer></script>
