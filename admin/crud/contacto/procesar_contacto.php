<?php

require_once dirname(__DIR__, 3) . '/config/path.php';

// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';
requireLogin();

if (!isAdmin() && !isSuperAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}
if (session_status() === PHP_SESSION_NONE) session_start();
require_once BASE_PATH . '/config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../index.php');
    exit();
}

// Validar CSRF
requireCSRFToken();

$conexion = conectar();

// 2. Recepción de variables
$nombre        = trim($_POST['nombre'] ?? '');
$apellido      = trim($_POST['apellido'] ?? '');
$dni           = trim($_POST['dni'] ?? '');
$telefono      = trim($_POST['telefono'] ?? '');
$correo        = trim($_POST['correo'] ?? '');
$direccion     = trim($_POST['direccion'] ?? '');
$localidad     = trim($_POST['localidad'] ?? '');
$cp            = trim($_POST['cp'] ?? '');
$parentesco    = trim($_POST['parentesco'] ?? '');
$observaciones = trim($_POST['observaciones'] ?? '');

$entidad_id    = trim($_POST['id_entidad'] ?? '');
$tipo          = trim($_POST['tipo'] ?? '');
$id_contacto   = $_POST['id_contacto'] ?? null;


// Bandera para saber si el usuario ya vio la pantalla de confirmación y dio el "Sí"
$confirmar_vincular = isset($_POST['confirmar_vincular']) && $_POST['confirmar_vincular'] == '1';

// Validación básica
if (empty($entidad_id) || empty($tipo)) {
    $_SESSION['mensaje'] = "Error: Faltan datos de la entidad.";
    header('Location: listar_contactos.php');
    exit();
}

try {
    // =======================================================================
    //   LÓGICA DE MODIFICACIÓN (UPDATE)
    // =======================================================================
    if (isset($_POST['action']) && $_POST['action'] == 'modificar') {
        
        $sentencia = "UPDATE contactos SET 
                        nombre = ?, apellido = ?, dni = ?, telefono = ?, correo = ?, 
                        direccion = ?, localidad = ?, cp = ?, parentesco = ?, observaciones = ? 
                      WHERE id_contacto_alumno = ?";
        
        $modificar = $conn->prepare($sentencia); 
        $modificar->execute([
            $nombre, $apellido, $dni, $telefono, $correo, 
            $direccion, $localidad, $cp, $parentesco, $observaciones, $id_contacto
        ]);
        $_SESSION['mensaje'] = "Contacto modificado correctamente.";

    } else {
        // =======================================================================
        //   LÓGICA DE INSERCIÓN / VINCULACIÓN
        // =======================================================================

        // 1. Verificar si YA existe para ESTE alumno específico
        $checkPropio = "SELECT id_contacto_alumno, activo FROM contactos WHERE dni = ? AND entidad_id = ? AND tipo = ?";
        $stmtPropio = $conexion->prepare($checkPropio);
        $stmtPropio->execute([$dni, $entidad_id, $tipo]);
        $existentePropio = $stmtPropio->fetch();

        if ($existentePropio) {
            // Caso A: Ya lo tiene asignado
            if ($existentePropio['activo'] == 1) {
                throw new PDOException("Esta persona ya está asignada a este alumno.", 23000);
            } else {
                // Caso B: Lo tiene pero borrado -> REVIVIR
                $revivir = "UPDATE contactos SET activo = 1, observaciones = ? WHERE id_contacto_alumno = ?";
                $stmtRevivir = $conexion->prepare($revivir);
                $stmtRevivir->execute([$observaciones, $existentePropio['id_contacto_alumno']]);
                $_SESSION['mensaje'] = "El contacto existía (borrado) y fue reactivado.";
                
                // Redirección final
                $_SESSION['id_entidad_temp'] = $entidad_id;
                $_SESSION['tipo_temp'] = $tipo;
                header('Location: listar_contactos.php');
                exit();
            }
        }

        // 2. Verificar si existe GLOBALMENTE (en otros alumnos)
        // Solo buscamos si NO estamos en la fase de confirmación
        if (!$confirmar_vincular) {
            $checkGlobal = "SELECT * FROM contactos WHERE dni = ? LIMIT 1";
            $stmtGlobal = $conexion->prepare($checkGlobal);
            $stmtGlobal->execute([$dni]);
            $personaEncontrada = $stmtGlobal->fetch();

            if ($personaEncontrada) {
                // Interrumpimos el proceso y mostramos la pantalla de confirmación HTML
                mostrarPantallaConfirmacion($personaEncontrada, $_POST);
                exit(); // IMPORTANTE: Detener el script aquí
            }
        }

        // 3. Preparar los datos finales para Insertar
        // Si el usuario confirmó la vinculación, usamos los datos de la BASE DE DATOS para nombre/dirección
        // para mantener la consistencia, pero usamos el parentesco/obs del FORMULARIO.
        if ($confirmar_vincular) {
            // Buscamos los datos "reales" de esa persona nuevamente para asegurar integridad
            $stmtDatos = $conexion->prepare("SELECT * FROM contactos WHERE dni = ? LIMIT 1");
            $stmtDatos->execute([$dni]);
            $datosReales = $stmtDatos->fetch();
            
            if ($datosReales) {
                $nombre    = $datosReales['nombre'];
                $apellido  = $datosReales['apellido'];
                $telefono  = $datosReales['telefono'];
                $correo    = $datosReales['correo'];
                $direccion = $datosReales['direccion'];
                $localidad = $datosReales['localidad'];
                $cp        = $datosReales['cp'];
                // Parentesco y Observaciones se mantienen del $_POST actual (nuevos)
            }
        }

        // 4. Insertar Nuevo Registro (o Copia Vinculada)
        $insertar = "INSERT INTO contactos (
                        nombre, apellido, dni, telefono, correo, direccion, localidad, cp,
                        parentesco, observaciones, entidad_id, tipo, activo
                    ) VALUES (
                        :nombre, :apellido, :dni, :telefono, :correo, :direccion, :localidad, :cp,
                        :parentesco, :observaciones, :entidad_id, :tipo, 1
                    )";
        
        $contacto = $conn->prepare($insertar);
        $contacto->execute([
            ':nombre' => $nombre, 
            ':apellido' => $apellido, 
            ':dni' => $dni, 
            ':telefono' => $telefono, 
            ':correo' => $correo, 
            ':direccion' => $direccion, 
            ':localidad' => $localidad, 
            ':cp' => $cp,
            ':parentesco' => $parentesco, 
            ':observaciones' => $observaciones, 
            ':entidad_id' => $entidad_id, 
            ':tipo' => $tipo
        ]);
        
        $_SESSION['mensaje'] = "Contacto agregado correctamente.";
    }

    // Redirección Final
    $_SESSION['id_entidad_temp'] = $entidad_id;
    $_SESSION['tipo_temp']       = $tipo;
    header('Location: listar_contactos.php');
    exit();

} catch(PDOException $e) {
    if ($e->getCode() == 23000) { 
        $_SESSION['mensaje'] = "Error: " . $e->getMessage();
    } else {
        $_SESSION['mensaje'] = "Error de base de datos: " . $e->getMessage();
    }

    $_SESSION['id_entidad_temp'] = $entidad_id;
    $_SESSION['tipo_temp']       = $tipo;
    header('Location: listar_contactos.php');
    exit();
}


function mostrarPantallaConfirmacion($datosPersona, $datosPost) {

    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Confirmar Vinculación</title>
        <style>
            body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
            .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); max-width: 500px; width: 100%; }
            h2 { color: #d9534f; margin-top: 0; }
            .datos-encontrados { background: #f9f9f9; border-left: 4px solid #5bc0de; padding: 15px; margin: 20px 0; }
            .btn { padding: 10px 20px; border: none; cursor: pointer; border-radius: 4px; font-size: 16px; margin-right: 10px; }
            .btn-si { background: #5cb85c; color: white; }
            .btn-no { background: #d9534f; color: white; text-decoration: none; display: inline-block; }
            .btn:hover { opacity: 0.9; }
        </style>
    </head>
    <body>
        <div class="card">
            <h2>⚠️ Persona Encontrada</h2>
            <p>El DNI <strong><?= htmlspecialchars($datosPersona['dni']) ?></strong> ya existe en nuestra base de datos asociado a otro alumno.</p>
            
            <div class="datos-encontrados">
                <p><strong>Nombre:</strong> <?= htmlspecialchars($datosPersona['nombre']) . " " . htmlspecialchars($datosPersona['apellido']) ?></p>
                <p><strong>Teléfono:</strong> <?= htmlspecialchars($datosPersona['telefono']) ?></p>
                <p><strong>Dirección:</strong> <?= htmlspecialchars($datosPersona['direccion']) ?></p>
            </div>

            <p>¿Deseas agregar a esta persona como contacto usando sus datos existentes?</p>

            <form action="procesar_contacto.php" method="POST">
                <input type="hidden" name="id_entidad" value="<?= htmlspecialchars($datosPost['id_entidad']) ?>">
                <input type="hidden" name="tipo" value="<?= htmlspecialchars($datosPost['tipo']) ?>">
                <input type="hidden" name="nombre" value="<?= htmlspecialchars($datosPost['nombre']) ?>">
                <input type="hidden" name="apellido" value="<?= htmlspecialchars($datosPost['apellido']) ?>">
                <input type="hidden" name="dni" value="<?= htmlspecialchars($datosPost['dni']) ?>">
                
                <input type="hidden" name="parentesco" value="<?= htmlspecialchars($datosPost['parentesco']) ?>">
                <input type="hidden" name="observaciones" value="<?= htmlspecialchars($datosPost['observaciones']) ?>">

                <input type="hidden" name="confirmar_vincular" value="1">

                <button type="submit" class="btn btn-si">✅ Sí, Vincular Persona</button>
                <a href="listar_contactos.php?id_entidad=<?= $datosPost['id_entidad'] ?>&tipo=<?= $datosPost['tipo'] ?>" class="btn btn-no">Cancelar</a>
            </form>
            
            <br>
            <small style="color: #666;">Nota: Al vincular, se usarán el nombre, dirección y teléfono que ya figuran en el sistema.</small>
        </div>
    </body>
    </html>
    <?php
}
?>