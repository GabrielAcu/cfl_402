<?php
// ==========================================
// ARCHIVO: tabla_cursos.php
// ==========================================
// "Esto es como recortar SOLO la parte de la mesa donde están los cursos.
//  No cambiamos nada, no tocamos nada, solo lo sacamos del Frankenstein."

// IMPORTANTE:
// Este archivo espera que YA existan:
//   - $consulta  → resultado de la consulta a la DB
//   - paginacion($pagina_actual, $total_paginas)
//   - $pagina_actual, $total_paginas
// No crea nada, no calcula nada. Solo IMPRIME.
?>

<?php if ($consulta->rowCount() > 0): ?>
    <?php echo paginacion($pagina_actual, $total_paginas); ?>

    <table class="info_table">
        <thead>
            <tr>
                <th>Ver</th>
                <th>Código</th>
                <th>Curso</th>
                <th>Turno</th>
                <th>Cupo</th>
                <th>Instructor</th>
                <th>Datos Extra</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($registro = $consulta->fetch()): ?>
                <tr class="fila_curso">

                    <td>
                        <button class="btnVerCurso" data-id="<?= $registro['id_curso'] ?>">
                            <img class="svg_lite" src="<?= BASE_URL ?>/assets/svg/eye.svg" title="Ver Detalles">
                        </button> 
                    </td> 
                    
                    <td><?= htmlspecialchars($registro['codigo']) ?></td>
                    <td class="text-left"><strong><?= htmlspecialchars($registro['nombre_curso']) ?></strong></td>
                    <td><?= htmlspecialchars($registro['descripcion']) ?></td> <!-- Turno -->
                    <td><?= htmlspecialchars($registro['cupo']) ?></td>
                    <td>
                        <?php if($registro['apellido']): ?>
                            <?= htmlspecialchars($registro['apellido'] . ', ' . $registro['nombre']) ?>
                        <?php else: ?>
                            <span style="opacity:0.5; font-style:italic;">Sin asignar</span>
                        <?php endif; ?>
                    </td>

                    <td class="td_actions">
                        <div class="acciones_wrapper">
                            <form action='../horarios/index.php' method='POST' class='enlinea'>
                                <input type='hidden' name='id_curso' value='<?= $registro['id_curso'] ?>'>
                                <button type="submit" class="submit-button">
                                    <img class="svg_lite" src="<?= BASE_URL ?>/assets/svg/clock.svg" title="Horarios">
                                </button>
                            </form>

                            <form action='../inscripciones/index.php' method='POST' class='enlinea'>
                                <input type='hidden' name='tipo' value='curso'>
                                <input type='hidden' name='id_curso' value='<?= $registro['id_curso'] ?>'>
                                <input type='hidden' name='volver' value='cursos'>
                                <button type="submit" class="submit-button">
                                    <img class="svg_lite" src="<?= BASE_URL ?>/assets/svg/user-plus.svg" title="Inscripciones">
                                </button>
                            </form>
                            
                            <form action='../planillas/index.php' method='GET' class='enlinea'>
                                <input type='hidden' name='id_curso' value='<?= $registro['id_curso'] ?>'>
                                <button type="submit" class="submit-button">
                                    <img class="svg_lite" src="<?= BASE_URL ?>/assets/svg/file-text.svg" title="Planilla">
                                </button>
                            </form>  
                        </div>
                    </td>

                    <td class="td_actions2">
                        <div class="acciones_wrapper">
                            <form action='modificar_curso.php' method='POST' class='enlinea'>
                                <?php
                                require_once dirname(__DIR__, 3) . '/config/path.php';
                                require_once BASE_PATH . '/config/csrf.php';
                                echo getCSRFTokenField();
                                ?>
                                <input type='hidden' name='id_curso' value='<?= $registro['id_curso'] ?>'>
                                <button type="submit" class="btnModificarCurso">
                                    <img class="svg_lite" src="<?= BASE_URL ?>/assets/svg/edit-pencil.svg" title="Modificar">
                                </button>
                            </form>

                            <form action='eliminar_curso.php' method='POST' class='enlinea' onsubmit='return confirm("Está seguro que desea eliminar el curso?")'>
                                <?php
                                require_once dirname(__DIR__, 3) . '/config/path.php';
                                require_once BASE_PATH . '/config/csrf.php';
                                echo getCSRFTokenField();
                                ?>
                                <input type='hidden' name='id_curso' value='<?= $registro['id_curso'] ?>'>
                                <button type="submit" class="submit-button">
                                    <img class="svg_lite" src="<?= BASE_URL ?>/assets/svg/trash-can.svg" title="Eliminar">
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php else: ?>
    <p>Aún no existen cursos</p>
<?php endif; ?>

