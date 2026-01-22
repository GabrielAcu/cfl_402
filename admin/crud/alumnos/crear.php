<?php
// ==========================
//   CONFIGURACIÓN INICIAL
// ==========================
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';
require_once BASE_PATH . '/include/header.php';
require_once 'layouts.php';

// Autenticación
requireLogin();

// Validar CSRF en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCSRFToken();
}

// Conexión
$conn = conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitización
    $nombre      = trim($_POST["nombre"]); 
    $apellido    = trim($_POST["apellido"]);
    $dni         = trim($_POST["dni"]);
    $telefono    = trim($_POST["telefono"]);
    $correo      = trim($_POST["email"]);
    $nacimiento  = $_POST["nacimiento"];
    $domicilio   = trim($_POST["domicilio"]);
    $localidad   = trim($_POST["localidad"]);
    $postal      = trim($_POST["postal"]);
    $autos       = trim($_POST["autos"]);
    $patente     = trim($_POST["patente"]);
    $observaciones = htmlspecialchars(trim($_POST["observaciones"]));
    $activo      = "1";

    if (empty($nombre) || strlen($nombre) > 50 || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $nombre)) {
        fallido("Nombre inválido (solo letras, max 50 caracteres)");
    } elseif (empty($apellido) || strlen($apellido) > 50 || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $apellido)) {
        fallido("Apellido inválido (solo letras, max 50 caracteres)");
    } elseif (!ctype_digit($dni) || strlen($dni) < 7 || strlen($dni) > 8) {
        fallido("DNI inválido (debe tener 7 u 8 números)");
    } elseif (!preg_match('/^[0-9\-\+\s]+$/', $telefono) || strlen($telefono) < 6) {
        fallido("Teléfono inválido");
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        fallido("Email inválido");
    } elseif (empty($nacimiento)) {
        fallido("Sin Fecha de Nacimiento");
    } elseif (empty($domicilio)) {
        fallido("Sin Domicilio");
    } elseif (empty($localidad)) {
        fallido("Sin Localidad");
    } elseif (empty($postal)) {
        fallido("Sin Código Postal");
    } else {

        try {

            // consulta SQL
            $sql = "INSERT INTO alumnos (
                        nombre, apellido, dni, fecha_nacimiento,
                        telefono, correo, direccion, localidad, cp,
                        activo, vehiculo, patente, observaciones
                    ) VALUES (
                        :nombre, :apellido, :dni, :nacimiento,
                        :telefono, :correo, :direccion, :localidad, :cp,
                        :activo, :autos, :patente, :observaciones
                    )";

            $consulta = $conn->prepare($sql);

            $consulta->execute([
                ':nombre'        => $nombre,
                ':apellido'      => $apellido,
                ':dni'           => $dni,
                ':nacimiento'    => $nacimiento,
                ':telefono'      => $telefono,
                ':correo'        => $correo,
                ':direccion'     => $domicilio,
                ':localidad'     => $localidad,
                ':cp'            => $postal,
                ':activo'        => $activo,
                ':autos'         => $autos,
                ':patente'       => $patente,
                ':observaciones' => $observaciones
            ]);

            header("location: index.php ");

        } catch (PDOException $e) {

            if ($e->getCode() == 23000) {
                fallido("El DNI ya existe");
            } elseif ($e->getCode() == '42S22') {
                fallido("El campo 'autos' no existe en tu tabla");
            } else {
                error_log("Error DB: " . $e->getMessage());
                echo "Ocurrió un error al insertar los datos. Por favor contacte al administrador.";
            }
        }
    }

} else { 
    echo "<h1 class='error'>Aha pillín!!!</h1>"; 
    echo "<p>{$_SERVER['REQUEST_METHOD']}</p>";
}
?>