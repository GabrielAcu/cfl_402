<?php
// Cargar path.php
require_once dirname(__DIR__, 3) . '/config/path.php';
// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';

// Autenticación
requireLogin();

if (!isAdmin() && !isSuperAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}

// Validar CSRF
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    requireCSRFToken();
}

// Conexión
$conn = conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_instructor = filter_var($_POST["id_instructor"] ?? 0, FILTER_VALIDATE_INT);
    
    if (!$id_instructor) {
        header("Location: index.php?error=id_invalido");
        exit;
    }

    // Obtener y validar datos
    $nombre = trim($_POST["nombre"] ?? '');
    $apellido = trim($_POST["apellido"] ?? '');
    $dni = $_POST["dni"] ?? '';
    $telefono = trim($_POST["telefono"] ?? '');
    $correo = trim($_POST["correo"] ?? '');
    $fecha_nacimiento = $_POST["fecha_nacimiento"] ?? '';
    $direccion = trim($_POST["direccion"] ?? '');
    $localidad = trim($_POST["localidad"] ?? '');
    $cp = trim($_POST["cp"] ?? '');
    $vehiculo = trim($_POST["vehiculo"] ?? '');
    $patente = trim($_POST["patente"] ?? '');
    $observaciones = trim($_POST["observaciones"] ?? '');

    // Validaciones básicas
    if (empty($nombre) || empty($apellido) || empty($dni) || empty($telefono) || empty($correo) || 
        empty($fecha_nacimiento) || empty($direccion) || empty($localidad) || empty($cp) || 
        empty($vehiculo) || empty($patente)) {
        header("Location: index.php?error=campos_vacios");
        exit;
    }

    // Validar email
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?error=email_invalido");
        exit;
    }

    // Validar DNI
    $dni = filter_var($dni, FILTER_VALIDATE_INT);
    if (!$dni || $dni <= 0) {
        header("Location: index.php?error=dni_invalido");
        exit;
    }

    try {
        $consulta = $conn->prepare("UPDATE instructores SET 
            nombre = :nombre, 
            apellido = :apellido, 
            dni = :dni, 
            telefono = :telefono, 
            correo = :correo,
            fecha_nacimiento = :fecha_nacimiento,
            direccion = :direccion,
            localidad = :localidad,
            cp = :cp,
            vehiculo = :vehiculo,
            patente = :patente,
            observaciones = :observaciones
            WHERE id_instructor = :id_instructor");
        
        $consulta->execute([
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':dni' => $dni,
            ':telefono' => $telefono,
            ':correo' => $correo,
            ':fecha_nacimiento' => $fecha_nacimiento,
            ':direccion' => $direccion,
            ':localidad' => $localidad,
            ':cp' => $cp,
            ':vehiculo' => $vehiculo,
            ':patente' => $patente,
            ':observaciones' => $observaciones,
            ':id_instructor' => $id_instructor
        ]);

        if ($consulta->rowCount() > 0) {
            header("Location: index.php?ok=modificado");
        } else {
            header("Location: index.php?error=sin_cambios");
        }
        exit;

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            header("Location: index.php?error=dni_duplicado");
        } else {
            header("Location: index.php?error=error_db");
        }
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}