<?php
// header.php — Header general del sistema CFL_402
// Se incluye en todos los index de los CRUD y admin
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

      <!-- Menú principal -->
      <nav class="main-nav" id="main-nav">
        <ul>
          <li class="crud-link"><a href="<?= BASE_URL ?>/admin/crud/index.php">Inicio</a></li>
          <li class="crud-link"><a href="<?= BASE_URL ?>/crud/alumnos/index.php">Alumnos</a></li>
          <!-- <li class="crud-link"><a href="<?= BASE_URL ?>/crud/cursos/index.php">Cursos</a></li> -->
          <!-- <li class="crud-link"><a href="<?= BASE_URL ?>/crud/horarios/index.php">Horarios</a></li> -->
          <!-- <li class="crud-link"><a href="<?= BASE_URL ?>/crud/inscripciones/index.php">Inscripciones</a></li> -->
          <li class="crud-link"><a href="<?= BASE_URL ?>/crud/instructores/index.php">Instructores</a></li>
          <li class="crud-link"><a href="<?= BASE_URL ?>/crud/usuarios/index.php">Usuarios</a></li>
          <li><a href="<?= BASE_URL ?>/auth/logout.php" class="btn-logout">Cerrar sesión</a></li>
        </ul>
      </nav>

      <!-- Bienvenida -->
    </div>

  </div>
</header>





<!-- Enlaces CSS y JS -->
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/header.css">
<script src="<?= BASE_URL ?>/assets/js/header.js" defer></script>
