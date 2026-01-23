<?php
session_start();
// 3. Autenticación
require_once dirname(__DIR__, 2) . '/../config/path.php';
require_once BASE_PATH . '/auth/check.php';
requireLogin();

if (!isAdmin() && !isSuperAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}

$id_entidad = $_POST['id_entidad'] ?? $_SESSION['entidad_id'] ?? null;
$tipo = $_POST['tipo'] ?? $_SESSION['tipo'] ?? null;
$mensaje_session = $_SESSION['mensaje_de_session'] ?? null;

if ($tipo == null || $id_entidad == null) {    
    header("Location: ../../index.php");
    exit();
}

// Limpiar sesión de mensaje si existe
if (isset($mensaje_session)) {
    unset($_SESSION['entidad_id']);
    unset($_SESSION['tipo']);
    unset($_SESSION['mensaje_de_session']);
}

require_once BASE_PATH . '/config/csrf.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Contacto - CFL 402</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css?v=3.2">
    <link rel="stylesheet" href="../alumnos/alumnos2.css?v=3.2">
    <style>
        .form-card {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 16px;
            border: 1px solid var(--border);
            max-width: 800px;
            margin: 20px auto;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .form-group.full-width {
            grid-column: span 2;
        }
        label {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 500;
        }
        input, select, textarea {
            background: var(--bg);
            border: 1px solid var(--border);
            color: var(--text);
            padding: 10px;
            border-radius: 8px;
            font-size: 1rem;
        }
        input:focus, textarea:focus {
            border-color: var(--accent);
            outline: none;
        }
    </style>
</head>
<body class="main_alumnos_body">

    <?php require_once BASE_PATH . '/include/header.php'; ?>

    <h1>Agregar Nuevo Contacto</h1>

    <?php if ($mensaje_session): ?>
        <div style="max-width: 800px; margin: 0 auto 20px; padding: 15px; background: rgba(255,0,0,0.1); color: #f87171; border: 1px solid #f87171; border-radius: 8px;">
            <?= htmlspecialchars($mensaje_session) ?>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form action='procesar_contacto.php' method='post'>
            <?= getCSRFTokenField() ?>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre</label>
                    <input type='text' name='nombre' required>
                </div>
                <div class="form-group">
                    <label>Apellido</label>
                    <input type='text' name='apellido' required>
                </div>

                <div class="form-group">
                    <label>DNI</label>
                    <input type='number' name='dni' required>
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type='tel' name='telefono' required>
                </div>

                <div class="form-group full-width">
                    <label>Correo Electrónico</label>
                    <input type='email' name='correo' required>
                </div>

                <div class="form-group">
                    <label>Dirección</label>
                    <input type='text' name='direccion' required>
                </div>
                 <div class="form-group">
                    <label>Parentesco / Relación</label>
                    <input type='text' name='parentesco' placeholder="Ej: Padre, Madre, Pareja" required>
                </div>

                <div class="form-group">
                    <label>Localidad</label>
                    <input type='text' name='localidad' required>
                </div>
                <div class="form-group">
                    <label>Código Postal</label>
                    <input type='text' name='cp' required>
                </div>

                <div class="form-group full-width">
                    <label>Observaciones</label>
                    <textarea name='observaciones' rows="3"></textarea>
                </div>
            </div>

            <input type='hidden' name='id_entidad' value='<?= $id_entidad ?>'>
            <input type='hidden' name='tipo' value='<?= $tipo ?>'>

            <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center;">
                 <button type="button" class="btn-primary" style="background: transparent; border: 1px solid var(--border); color: var(--text);" onclick="document.getElementById('formVolver').submit()">
                    Cancelar
                </button>
                <button type="submit" class="btn-primary">
                    Guardar Contacto
                </button>
            </div>
        </form>
    </div>

    <!-- Formulario invisible para volver -->
    <form id="formVolver" action='listar_contactos.php' method='post' style="display:none;">
        <input type='hidden' name='id_entidad' value='<?= $id_entidad ?>'>
        <input type='hidden' name='tipo' value='<?= $tipo ?>'>
    </form>

</body>
</html>
