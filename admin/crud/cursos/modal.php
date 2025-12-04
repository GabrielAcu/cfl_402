<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modal</title>
    <link rel="stylesheet" href="modal.css">
</head>
<body>
    
</body>
</html>

<?php
// ==========================
//   MODAL (SIN CAMBIAR NADA)
//   SOLO SEPARADO DEL INDEX
// ==========================

// Acá simplemente pegá EXACTAMENTE el modal que tenías en el index.
// No voy a inventar nada porque quiero que tu código siga funcionando
// exactamente igual que antes.


?>
<div id="modalCurso" class="modal">
    <div class="modal-content">
        <span class="cerrar">&times;</span>
        <h2>Nuevo Curso</h2>

        <form class='new-form' action="crear_curso.php" method="POST" id="formCurso">
            <div>
                <label for="codigo">Código</label>
                <input class='input-modify' type="text" name="codigo" id="codigo" required>
            </div>

            <div>
                <label for="nombre_curso">Nombre del Curso</label>
                <input type="text" name="nombre_curso" id="nombre_curso" required>
            </div>

            <div>
                <label for="descripcion">Descripción</label>
                <input type="text" name="descripcion" id="descripcion" required>
            </div>

            <div>
                <label for="cupo">Cupo</label>
                <input class='input-modify' type="text" name="cupo" id="cupo" required>
            </div>

            <!-- SELECT INSTRUCTORES -->
            <?php
                $instructores=$conn->query("SELECT nombre, apellido, id_instructor FROM instructores");
                echo "<div>
                        <label for='instructor'>Instructor</label>
                        <select name='instructor' id='instructor'>";
                while ($i=$instructores->fetch()){
                    echo "<option value='$i[id_instructor]'>$i[apellido], $i[nombre]</option>";
                }
                echo "</select></div>";
            ?>

            <!-- SELECT TURNOS -->
            <?php
                $turnos=$conn->query("SELECT * FROM turnos");
                echo "<div>
                        <label for='turno'>Turno</label>
                        <select name='turno' id='turno'>";
                while ($t=$turnos->fetch()){
                    echo "<option value='$t[id_turno]'>$t[descripcion]</option>";
                }
                echo "</select></div>";
            ?>

            <input type="submit" value="Guardar" class="btn-submit">
        </form>
    </div>
    

        
</div>