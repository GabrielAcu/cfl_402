<<<<<<< HEAD
=======
<!-- <!DOCTYPE html>
>>>>>>> origin/toby-nueva-rama
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modal</title>
    <link rel="stylesheet" href="modal.css">
</head>
<body>
    
</body>
</html> -->

<?php
// ===============================================================
//                    MODAL DE CREACIÓN DE CURSO
// ===============================================================
?>

<div id="modalCurso" class="modal">
    <div class="modal-content">
        <span class="cerrar">&times;</span>
        <h2>Nuevo Curso</h2>

        <!-- ======================================================
                        FORMULARIO DE CREACIÓN DE CURSO          
             ====================================================== -->
        <form class="new-form" action="crear_curso.php" method="POST" id="formCurso">

            <!-- Código del curso -->
            <div>
                <label for="codigo">Código</label>
                <input class="input-modify" type="text" name="codigo" id="codigo" required>
            </div>

            <!-- Nombre del curso -->
            <div>
                <label for="nombre_curso">Nombre del Curso</label>
                <input type="text" name="nombre_curso" id="nombre_curso" required>
            </div>

            <!-- Descripción -->
            <div>
                <label for="descripcion">Descripción</label>
                <input type="text" name="descripcion" id="descripcion" required>
            </div>

            <!-- Cupo -->
            <div>
                <label for="cupo">Cupo</label>
                <input class="input-modify" type="text" name="cupo" id="cupo" required>
            </div>

            <!-- Fecha de Inicio del curso -->
            <div>
                <label for="fecha_inicio">Fecha de Inicio</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" required>
            </div>

            <!-- Fecha de Fin del curso -->
            <div>
                <label for="fecha_fin">Fecha de Fin</label>
                <input type="date" name="fecha_fin" id="fecha_fin" required>
            </div>

            <!-- ======================================================
                 SELECT: Instructores (desde la BD)
            ====================================================== -->
            <?php
                $instructores = $conn->query("SELECT nombre, apellido, id_instructor FROM instructores");
                echo "<div>
                        <label for='instructor'>Instructor</label>
                        <select name='instructor' id='instructor'>";
                while ($i = $instructores->fetch()) {
                    echo "<option value='{$i['id_instructor']}'>{$i['apellido']}, {$i['nombre']}</option>";
                }
                echo "</select></div>";
            ?>

            <!-- ======================================================
                 SELECT: Turnos (desde la BD)
            ====================================================== -->
            <?php
                $turnos = $conn->query("SELECT * FROM turnos");
                echo "<div>
                        <label for='turno'>Turno</label>
                        <select name='turno' id='turno'>";
                while ($t = $turnos->fetch()) {
                    echo "<option value='{$t['id_turno']}'>{$t['descripcion']}</option>";
                }
                echo "</select></div>";
            ?>

            <!-- Botón de Guardar -->
            <input type="submit" value="Guardar" class="btn-submit">
        </form>
    </div>
</div>
