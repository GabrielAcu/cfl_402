<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/check.php';

echo "LOGIN: Entró a login.php<br>";

$userName = $_POST['usuario'] ?? null;
$pass = $_POST['password'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($userName) || empty($pass)) {
        $_SESSION['mensaje'] = 'Faltan Campos';
        header('Location: ../index.php');
        exit;
    }

    try {
        $conn = conectar();

        $sql_login = "SELECT * FROM usuarios WHERE nombre = :nombre AND contrasenia = :contrasenia";
        $stm = $conn->prepare($sql_login);
        $stm->execute([
            ':nombre' => $userName,
            ':contrasenia' => $pass
        ]);

        $rst = $stm->fetch();

        if ($rst) {
            $_SESSION['user'] = [
                'user_id' => $rst['id'],
                'rol'     => $rst['rol'],
                'name'    => $rst['nombre']
            ];

            // redirige según rol
            idAdminOrInstructor();
            exit;
        } else {
            $_SESSION['mensaje'] = 'Usuario o contraseña incorrectos';
            header('Location: ../index.php');
            exit;
        }

    } catch (Throwable $th) {
        echo $th->getMessage();
        exit;
    }

} else {
    $_SESSION['mensaje'] = 'Acceso inválido';
    header('Location: ../index.php');
    exit;
}
