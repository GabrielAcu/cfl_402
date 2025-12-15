<!-- // ==========================
//   MOSTRAR TABLA
// ========================== -->
<?php
if ($consulta->rowCount() > 0) {

    echo "
    <main class='main_alumnos'>
    <table class='info_table'>
        <thead>
            <tr class='table_header'>
                <th>Ver</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>DNI</th>
                <th>Fecha Nac.</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Correo</th>
                <th>Datos Extra</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
    ";

    while ($registro = $consulta->fetch()) : ?>
    <tr>
        <td> 
            <button class="btnVerCurso" data-id="<?= $registro['id_alumno'] ?>">
                <img class="svg_lite" src="/cfl_402/assets/svg/blue_search.svg" title="Modificar">
            </button>
        </td>
        <td><?= $registro['nombre'] ?></td>
        <td><?= $registro['apellido'] ?></td>
        <td><?= $registro['dni'] ?></td>
        <td><?= $registro['fecha_nacimiento'] ?></td>
        <td><?= $registro['telefono'] ?></td>
        <td><?= $registro['direccion'] ?></td>
        <td><?= $registro['correo'] ?></td>

        <td class="td_actions">
            <div class="acciones_wrapper">

                <form action="../contacto/listar_contactos.php" method="POST" class="enlinea">
                    <input type="hidden" name="id_entidad" value="<?= $registro['id_alumno'] ?>">
                    <input type="hidden" name="tipo" value="alumno">
                    <button type="submit" class="submit-button">
                        <img class="svg_lite" src="/cfl_402/assets/svg/contact.svg" title="Contactos">
                    </button>
                </form>

                <form action="/cfl_402/admin/crud/cursos/index.php" method="POST" class="enlinea">
                    <input type="hidden" name="id_alumno" value="<?= $registro['id_alumno'] ?>">
                    <button type="submit" class="submit-button">
                        <img class="svg_lite" src="/cfl_402/assets/svg/book.svg" title="Cursos">
                    </button>
                </form>
            </div>
        </td>

        <td class="td_actions2">

            <button class="btnModificarAlumno" data-id="<?= $registro['id_alumno'] ?>">
                <img class="svg_lite" src="/cfl_402/assets/svg/pencil.svg" title="Modificar">
            </button>

            <form action="../alumnos/bajar.php" method="POST" class="enlinea confirm-delete">
                <?= getCSRFTokenField() ?>
                <input type="hidden" name="id_alumno" value="<?= $registro['id_alumno'] ?>">
                <button type="submit" class="submit-button">
                    <img class="svg_lite" src="/cfl_402/assets/svg/trash.svg" title="Eliminar">
                </button>
            </form>

            <form action="../inscripciones/index.php" method="POST" class="enlinea">
                <input type="hidden" name="tipo" value="alumno">
                <input type="hidden" name="id_alumno" value="<?= $registro['id_alumno'] ?>">
                <input type="hidden" name="volver" value="alumnos">
                <button type="submit" class="submit-button">
                    <img class="svg_lite" src="/cfl_402/assets/svg/plus.svg" title="Inscribir a un curso">
                </button>
            </form>
        </td>
    </tr>
    
    
<?php endwhile; 
echo"
        </tbody>
    </table>
    </main>";
}