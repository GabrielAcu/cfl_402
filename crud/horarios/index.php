<?php
include_once __DIR__ . '/../../config/conexion.php';
$conn = conectar();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Horarios — Listado</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="horarios.css">
</head>
<body>
  <div class="container">
    <header>
      <h1>Horarios</h1>
      <div class="controls">
        <input id="search" type="search" placeholder="Buscar por curso o día...">
        <a class="btn new" href="agregar.php">+ Nuevo horario</a>
      </div>
    </header>

    <main>
      <table id="tabla-horarios">
        <thead>
          <tr>
            <th>ID</th>
            <th>Curso</th>
            <th>Día</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="resultado-body">
          <!-- filas cargadas vía AJAX -->
        </tbody>
      </table>
      <div id="no-results" class="hidden">No se encontraron horarios.</div>
    </main>
  </div>

  <script src="horarios.js"></script>
  <script>
    // Inicializa carga y búsqueda
    document.addEventListener('DOMContentLoaded', () => {
      initHorarios(); // función en horarios.js
    });
  </script>
</body>
</html>
