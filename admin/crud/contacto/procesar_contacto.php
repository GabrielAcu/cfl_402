<?php
// 1. Configuración y Auth
require_once dirname(__DIR__, 2) . '/../config/path.php';
require_once BASE_PATH . '/auth/check.php';
requireLogin();

$conn = conectar();
// if (!isAdmin()) {
//     header('Location: /cfl_402/index.php');
//     exit();
// }

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar que entramos por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../index.php');
    exit();
}

// 2. Recepción de datos (con limpieza y valores por defecto para evitar warnings)
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
$id_contacto   = $_POST['id_contacto'] ?? null; // Puede ser null en Insert

// 3. Validación de campos obligatorios
// Nota: Usamos || (OR lógico), no | (Bitwise)
if (empty($nombre) || empty($apellido) || empty($dni) || empty($telefono) || empty($entidad_id) || empty($tipo)) {
    $_SESSION['mensaje'] = "Error: Faltan llenar campos obligatorios.";
    
    // Volver a la lista
    $_SESSION['id_entidad_temp'] = $entidad_id;
    $_SESSION['tipo_temp'] = $tipo;
    header('Location: listar_contactos.php');
    exit();
}

try {
    // 4. Decidir si es Modificar o Insertar
    if (isset($_POST['action']) && $_POST['action'] == 'modificar') {
        
        // --- MODIFICAR ---
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
        
        // --- INSERTAR ---
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
        
        $_SESSION['mensaje'] = "Contacto creado correctamente.";
    }

    // 5. Redirección Éxitosa
    // Guardamos datos en sesión para que listar_contactos sepa qué mostrar
    $_SESSION['id_entidad_temp'] = $entidad_id;
    $_SESSION['tipo_temp']       = $tipo;
    header('Location: listar_contactos.php');
    exit();

} catch(PDOException $e) {
    
    // 6. Manejo de Errores (Ej: DNI Duplicado)
    if ($e->getCode() == 23000) { 
        $_SESSION['mensaje'] = "Error: El DNI $dni ya existe en la base de datos.";
    } else {
        $_SESSION['mensaje'] = "Error de base de datos: " . $e->getMessage();
    }

    // Redirigir de vuelta a la lista mostrando el error
    $_SESSION['id_entidad_temp'] = $entidad_id;
    $_SESSION['tipo_temp']       = $tipo;
    header('Location: listar_contactos.php');
    exit();
}
?>