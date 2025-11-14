<?php
// header.php — Header general del sistema CFL_402
// Se incluye en todos los index de los CRUD y admin
?>

<header class="main-header">
  <div class="header-container">

    <!-- Logo -->
    <div class="logo">
      <a href="/cfl_402/admin/index.php">CFL 402</a>
    </div>

    <!-- Contenedor derecho -->
    <div class="right-section">
      <!-- Botón menú hamburguesa -->
      <button class="menu-toggle" id="menu-toggle" aria-label="Abrir menú">☰</button>

      <!-- Menú principal -->
      <nav class="main-nav" id="main-nav">
        <ul>
          <li class="crud-link"><a href="/cfl_402/admin/index.php">Inicio</a></li>
          <li class="crud-link"><a href="/cfl_402/crud/alumnos/index.php">Alumnos</a></li>
          <li class="crud-link"><a href="/cfl_402/crud/cursos/index.php">Cursos</a></li>
          <li class="crud-link"><a href="/cfl_402/crud/horarios/index.php">Horarios</a></li>
          <li class="crud-link"><a href="/cfl_402/crud/inscripciones/index.php">Inscripciones</a></li>
          <li class="crud-link"><a href="/cfl_402/crud/instructores/index.php">Instructores</a></li>
          <li class="crud-link"><a href="/cfl_402/crud/usuarios/index.php">Usuarios</a></li>
          <li><a href="../auth/logout.php" class="btn-logout">Cerrar sesión</a></li>
        </ul>
      </nav>

      <!-- Bienvenida -->
    </div>

  </div>
</header>





<!-- Enlaces CSS y JS -->
<link rel="stylesheet" href="/cfl_402/assets/css/header.css">
<script src="/cfl_402/assets/js/header.js" defer></script>
