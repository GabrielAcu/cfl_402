<?php
session_start();

include_once __DIR__ . '/../config/conexion.php';
require_once 'check.php';

$userName = $_POST['usuario'] ?? null;
$pass = $_POST['password'] ?? null;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(empty($userName) || empty($pass)){
        $_SESSION['mensaje'] = 'Faltan Campos';
        header('Location: ../index.php');
    } else {
        try {
            $conn = conectar();
            $sql_login = "SELECT * FROM usuarios WHERE nombre = :nombre AND contrasenia = :contrasenia";
            $rst = $conn->prepare($sql_login);
            $rst->execute([':nombre' => $userName, ':contrasenia' => $pass]);
            $rst = $rst->fetch();

            if($rst && $rst['contrasenia'] == $pass) {
                $_SESSION['user'] = [
                    'user_id' => $rst['id'],
                    'rol' => $rst['rol'],
                    'name' => $rst['nombre']
                ];
                idAdminOrInstructor();
                exit();  
            }
            // var_dump($rst->fetch());
            // exit();
        } catch (\Throwable $th) {
            echo $th;
            header('Location: ../index.php');
        }
    }
} else{
    $_SESSION['mensaje'] = 'Faltan Campos';
    header('Location: ../index.php');
}