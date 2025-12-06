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

    <table>
        <thead>
            <tr>
                <th>Ver</th>
                <th>Código</th>
                <th>Nombre Curso</th>
                <th>Turno</th>
                <th>Cupo</th>
                <th colspan='2'>Instructor</th>
                <th>Acciones</th>
                <th>Datos Extras</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($registro = $consulta->fetch()): ?>
                <tr class="fila_curso">

                    <td class="td_action">
                            <button class="btnVerCurso" <?php echo "data-id=$registro[id_curso]"?> >
                                <img class="svg_lite" src="/cfl_402/assets/svg/blue_search.svg" alt="">
                            </button> 
                            
                    </td> 
                        
                    
                    <td>  <?php echo $registro['codigo']; ?></td>
                    <td><?php echo $registro['nombre_curso']; ?></td>
                    <td><?php echo $registro['descripcion']; ?></td>
                    <td><?php echo $registro['cupo']; ?></td>
                    <td><?php echo $registro['apellido']; ?></td>
                    <td><?php echo $registro['nombre']; ?></td>

                    <td>
                        <form action='modificar_curso.php' method='POST' class='enlinea'>
                            <input type='hidden' name='id_curso' value='<?php echo $registro['id_curso']; ?>'>
                            <input type='submit' value='✏️ Modificar'>
                        </form>

                        <form action='eliminar_curso.php' method='POST' class='enlinea' onsubmit='return confirm("Está seguro que desea eliminar el curso?")'>
                            <input type='hidden' name='id_curso' value='<?php echo $registro['id_curso']; ?>'>
                            <input type='submit' value='❌ Eliminar'>
                        </form>
                    </td>

                    <td>
                        <form action='../horarios/index.php' method='POST' class='enlinea'>
                            <input type='hidden' name='id_curso' value='<?php echo $registro['id_curso']; ?>'>
                            <input type='submit' value='👨‍👩‍👧‍👦 Horarios'>
                        </form>

                        <form action='../inscripciones/index.php' method='POST' class='enlinea'>
                            <input type='hidden' name='tipo' value='curso'>
                            <input type='hidden' name='id_curso' value='<?php echo $registro['id_curso']; ?>'>
                            <input type='hidden' name='volver' value='cursos'>
                            <input type='submit' value='📖 Inscripciones'>
                        </form>
                        <form action='../planillas/planillas.php' method='POST' class='enlinea'>
                            <input type='hidden' name='id_curso' value='$registro[id_curso]'>
                            <input type='submit' value='📄 Planilla'>
                        </form>  
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php else: ?>
    <p>Aún no existen cursos</p>
<?php endif; ?>

