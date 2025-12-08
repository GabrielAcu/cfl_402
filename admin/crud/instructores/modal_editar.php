<?php

// Cargar path.php
require_once dirname(__DIR__, 3) . '/config/path.php';


// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

// 3. Autenticación
requireLogin();

// if (!isAdmin()) {
//     header('Location: cfl_402_ciro/cfl_402/index.php');
//     exit();
// }

// Conexión
$conn = conectar();
?>
<link rel="stylesheet" href="modal.css">
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];

    $texto = "SELECT * FROM instructores WHERE id_instructor = :id";
    $consulta = $conn->prepare($texto);
    $consulta->bindParam(':id', $id);
    $consulta->execute();
    $instructor = $consulta->fetch();

    if ($instructor) {
?>
       <div id="modalModificar" class="modal">
            <div class="modal-content">
            <span class="cerrar">&times;</span>
            <h2>Modificar Instructor</h2>

        <?php if ($instructor): ?>
            <form class="new-form" action="procesar_modificacion_instructor.php" method="POST">

                <input type="hidden" name="id_instructor" value="<?= $instructor['id_instructor'] ?>">

                <div>
                    <label>Nombre</label>
                    <input type="text" name="nombre" value="<?= $instructor['nombre'] ?>" required>
                </div>

                <div>
                    <label>Apellido</label>
                    <input type="text" name="apellido" value="<?= $instructor['apellido'] ?>" required>
                </div>

                <div>
                    <label>DNI</label>
                    <input type="number" name="dni" value="<?= $instructor['dni'] ?>" required>
                </div>

                <div>
                    <label>Teléfono</label>
                    <input type="text" name="telefono" value="<?= $instructor['telefono'] ?>" required>
                </div>

                <div>
                    <label>Correo</label>
                    <input type="email" name="correo" value="<?= $instructor['correo'] ?>" required>
                </div>

                <input type="submit" value="Guardar cambios" class="btn-submit">
            </form>
        <?php else: ?>
            <p class="error">El instructor no existe.</p>
        <?php endif; ?>

        <a href="index.php" class="btn-cancel">Volver</a>
    </div>
</div>


<?php
    } else {
        echo "<p class='text-danger'>El instructor no existe.</p>";
        echo "<a href='index.php'>Volver al listado</a>";
    }
} else {
    echo "<h1>Instructor modificado correctamente</h1>";
    echo "<a href='index.php'>Volver al listado de instructores</a>";
}
?>
