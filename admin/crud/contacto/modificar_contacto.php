<?php
// Cargar path.php
require_once dirname(__DIR__, 2) . '/../config/path.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';
// Dependencias
require_once BASE_PATH . '/config/conexion.php';
// 3. Autenticación
requireLogin();

if (!isAdmin() && !isSuperAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}

$id_contacto = $_POST['id_contacto'] ?? null;

if ($id_contacto == null) {    
    header("Location: ../../index.php");
    exit();
}

$conexion = conectar();
// Fix: Use prepared statement
$stmt = $conexion->prepare("SELECT * FROM contactos WHERE id_contacto_alumno = ?");
$stmt->execute([$id_contacto]);
$registro = $stmt->fetch();

if (!$registro) {
    echo "Contacto no encontrado";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Contacto - CFL 402</title>
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

    <h1>Modificar Contacto</h1>

    <div class="form-card">
        <form action='procesar_contacto.php' method='post'>
            <?= getCSRFTokenField() ?>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre</label>
                    <input type='text' name='nombre' value="<?= htmlspecialchars($registro['nombre']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Apellido</label>
                    <input type='text' name='apellido' value="<?= htmlspecialchars($registro['apellido']) ?>" required>
                </div>

                <div class="form-group">
                    <label>DNI</label>
                    <input type='number' name='dni' value="<?= htmlspecialchars($registro['dni']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type='tel' name='telefono' value="<?= htmlspecialchars($registro['telefono']) ?>" required>
                </div>

                <div class="form-group full-width">
                    <label>Correo Electrónico</label>
                    <input type='email' name='correo' value="<?= htmlspecialchars($registro['correo']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Dirección</label>
                    <input type='text' name='direccion' value="<?= htmlspecialchars($registro['direccion']) ?>" required>
                </div>
                 <div class="form-group">
                    <label>Parentesco / Relación</label>
                    <input type='text' name='parentesco' value="<?= htmlspecialchars($registro['parentesco']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Localidad</label>
                    <input type='text' name='localidad' value="<?= htmlspecialchars($registro['localidad']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Código Postal</label>
                    <input type='text' name='cp' value="<?= htmlspecialchars($registro['cp']) ?>" required>
                </div>

                <div class="form-group full-width">
                    <label>Observaciones</label>
                    <textarea name='observaciones' rows="3"><?= htmlspecialchars($registro['observaciones']) ?></textarea>
                </div>
            </div>

            <input type='hidden' name='id_entidad' value='<?= $registro['entidad_id'] ?>'>
            <input type='hidden' name='tipo' value='<?= $registro['tipo'] ?>'>
            <input type='hidden' name='id_contacto' value='<?= $registro['id_contacto_alumno'] ?>'>
            <input type='hidden' name='action' value='modificar'>

            <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center;">
                <button type="button" class="btn-primary" style="background: transparent; border: 1px solid var(--border); color: var(--text);" onclick="document.getElementById('formVolver').submit()">
                    Cancelar
                </button>
                <button type="submit" class="btn-primary">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    <!-- Formulario invisible para volver -->
    <form id="formVolver" action='listar_contactos.php' method='post' style="display:none;">
        <input type='hidden' name='id_entidad' value='<?= $registro['entidad_id'] ?>'>
        <input type='hidden' name='tipo' value='<?= $registro['tipo'] ?>'>
    </form>

</body>
</html>