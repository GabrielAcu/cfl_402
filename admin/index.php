<?php
// 1. Configuración de rutas
require_once __DIR__ . '/../config/path.php';

// 2. Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

// 3. Autenticación general
requireLogin();

// Si no es admin ni superadmin, afuera del panel
if (!isAdmin() && !isSuperAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}

// 4. Conexión BD
$conn = conectar();

// 5. Clase del rol para ajustar el layout
$panelClass = isSuperAdmin() ? 'superadmin' : (isAdmin() ? 'admin' : 'instructor');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración - CFL402</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/panel.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/header.css">
</head>

<body class="hide-crud-links light">
<h1>Panel de Administración</h1>

<main>
    <!-- 👇 ACÁ SE APLICA EL LAYOUT SEGÚN EL ROL -->
    <section class="panel-container <?= $panelClass ?>">

        <!-- 👨‍🎓 Alumnos (lo ven admin + superadmin) -->
        <?php if (isAdmin() || isSuperAdmin()) : ?>
            <a href="../admin/crud/alumnos/index.php" class="card">
                <h2>👨‍🎓 Alumnos</h2>
                <p>Gestión completa de los alumnos registrados.</p>
            </a>
        <?php endif; ?>

        <!-- 👩‍🏫 Instructores (solo superadmin) -->
        <?php if (isAdmin() || isSuperAdmin()) : ?>
            <a href="../admin/crud/instructores/index.php" class="card">
                <h2>👩‍🏫 Instructores</h2>
                <p>Administrar docentes e instructores.</p>
            </a>
        <?php endif; ?>

        <!-- 📘 Cursos (admin + superadmin + instructor) -->
        <a href="../admin/crud/cursos/index.php" class="card">
            <h2>📘 Cursos</h2>
            <p>Alta, baja y modificación de cursos disponibles.</p>
        </a>

        <!-- 🔐 Usuarios (solo superadmin) -->
        <?php if (isSuperAdmin()) : ?>
            <a href="../admin/crud/usuarios/index.php" class="card">
                <h2>🔐 Usuarios</h2>
                <p>Gestión de accesos al sistema.</p>
            </a>
        <?php endif; ?>

    </section>
</main>

<footer>
    <p>© 2025 CFL402 - Sistema Educativo</p>
</footer>

<script src="<?= BASE_URL ?>/assets/js/header.js" defer></script>

</body>
</html>
