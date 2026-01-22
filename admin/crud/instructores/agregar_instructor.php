<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
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

$conn = conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $dni = trim($_POST["dni"]);
    $telefono = trim($_POST["telefono"]);
    $nacimiento = $_POST["nacimiento"];
    $email = trim($_POST["email"]);
    $domicilio = trim($_POST["domicilio"]);
    $localidad = trim($_POST["localidad"]);
    $postal = trim($_POST["postal"]);
    $vehiculo = trim($_POST["vehiculo"]);
    $patente = trim($_POST["patente"]);
    $observaciones = htmlspecialchars(trim($_POST["observaciones"]));
    $activo = "1";

    // Validaciones
    // Note: The original code had a `fallido` function which is not defined.
    // For this replacement, I'll assume it's a placeholder for error handling
    // and will use `header("Location: index.php?error=...")` as per the original file's pattern.
    // Also, the original code used `fecha_nacimiento` and `correo` and `direccion` and `cp`.
    // The new code uses `nacimiento`, `email`, `domicilio`, `postal`.
    // I'm mapping them based on context.

    if (empty($nombre) || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $nombre)) {
        header("Location: index.php?error=nombre_invalido");
        exit;
    } elseif (empty($apellido) || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $apellido)) {
        header("Location: index.php?error=apellido_invalido");
        exit;
    } elseif (!ctype_digit($dni) || strlen($dni) < 7 || strlen($dni) > 8) {
        header("Location: index.php?error=dni_invalido");
        exit;
    } elseif (!preg_match('/^[0-9\-\+\s]+$/', $telefono) || strlen($telefono) < 6) {
        header("Location: index.php?error=telefono_invalido");
        exit;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?error=email_invalido");
        exit;
    } else {

        try {
            // consulta SQL
            $sql = "INSERT INTO instructores (
                        nombre, apellido, dni, telefono,
                        fecha_nacimiento, correo, direccion,
                        localidad, cp, activo,
                        vehiculo, patente, observaciones
                    ) VALUES (
                        :nombre, :apellido, :dni, :telefono,
                        :nacimiento, :email, :domicilio,
                        :localidad, :postal, :activo,
                        :vehiculo, :patente, :observaciones
                    )";

            $consulta = $conn->prepare($sql);

            $consulta->execute([
                ':nombre' => $nombre,
                ':apellido' => $apellido,
                ':dni' => $dni,
                ':telefono' => $telefono,
                ':nacimiento' => $nacimiento,
                ':email' => $email,
                ':domicilio' => $domicilio,
                ':localidad' => $localidad,
                ':postal' => $postal,
                ':activo' => $activo,
                ':vehiculo' => $vehiculo,
                ':patente' => $patente,
                ':observaciones' => $observaciones
            ]);

            header("location: index.php?ok=1");
            exit;

        } catch (PDOException $e) {
            // Log del error real (interno)
            // Assuming logError function exists as in the original code
            logError('Error al agregar instructor', $e, ['dni' => $dni]);

            if ($e->getCode() == 23000) { // Duplicate entry error code
                header("Location: index.php?error=dni_duplicado");
            } else {
                // Mensaje genérico para cualquier otro error
                header("Location: index.php?error=error_sistema");
            }
            exit;
        }
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
