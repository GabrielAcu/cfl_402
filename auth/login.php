<?php
session_start();

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/check.php';
require_once __DIR__ . '/../config/rate_limit.php';
require_once __DIR__ . '/../config/logger.php';

$userName = $_POST['usuario'] ?? null;
$pass = $_POST['password'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verificar rate limiting
    $rateLimit = checkRateLimit(5, 300); // 5 intentos en 5 minutos
    
    if (!$rateLimit['allowed']) {
        $_SESSION['mensaje'] = $rateLimit['message'];
        header('Location: ../index.php');
        exit;
    }

    if (empty($userName) || empty($pass)) {
        $_SESSION['mensaje'] = 'Faltan Campos';
        header('Location: ../index.php');
        exit;
    }

    try {
        $conn = conectar();

        // Buscar usuario por nombre (sin comparar contraseña en SQL)
        $sql_login = "SELECT * FROM usuarios WHERE nombre = :nombre AND activo = 1";
        $stm = $conn->prepare($sql_login);
        $stm->execute([
            ':nombre' => $userName
        ]);

        $rst = $stm->fetch();

        // Verificar contraseña usando password_verify()
        if ($rst && password_verify($pass, $rst['contrasenia'])) {
            // Login exitoso - limpiar rate limit y registrar
            clearRateLimit();
            logLoginAttempt($userName, true);
            
            $_SESSION['user'] = [
                'user_id' => $rst['id'],
                'rol'     => $rst['rol'],
                'name'    => $rst['nombre']
            ];

            // redirige según rol
            idAdminOrInstructor();
            exit;
        } else {
            // Login fallido - registrar intento
            logLoginAttempt($userName, false, 'Credenciales inválidas');
            
            // No revelar si el usuario existe o no (seguridad)
            $_SESSION['mensaje'] = 'Usuario o contraseña incorrectos';
            header('Location: ../index.php');
            exit;
        }

    } catch (Throwable $th) {
        // Registrar error en logs
        logError('Error en login', $th, ['username' => $userName]);
        
        // No exponer detalles del error en producción
        $_SESSION['mensaje'] = 'Error al iniciar sesión. Intente nuevamente.';
        header('Location: ../index.php');
        exit;
    }

} else {
    $_SESSION['mensaje'] = 'Acceso inválido';
    header('Location: ../index.php');
    exit;
}
