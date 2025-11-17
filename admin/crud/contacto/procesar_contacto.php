<?php
// Cargar path.php
require_once dirname(__DIR__, 2) . '/../config/path.php';

// Dependencias
require_once BASE_PATH . '/config/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $conexion = conectar();
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $dni = trim($_POST['dni']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $direccion = trim($_POST['direccion']);
    $localidad = trim($_POST['localidad']);
    $cp = trim($_POST['cp']);
    $parentesco = trim($_POST['parentesco']);
    $observaciones = trim($_POST['observaciones']);
    $entidad_id = trim($_POST['id_entidad']);
    $id_contacto = trim($_POST['id_contacto']);
    $tipo = trim($_POST['tipo']);

    if (
        empty($nombre) || 
        empty($apellido) | 
        empty($dni) || 
        empty($telefono) || 
        empty($correo) || 
        empty($direccion) || 
        empty($localidad) || 
        empty($cp) || 
        empty($parentesco) || 
        empty($observaciones) || 
        empty($entidad_id) || 
        empty($tipo) ) 
        {

        $_SESSION['mensaje_de_session'] = "Faltan llenar campos correctamente";
        $_SESSION['entidad_id'] = $entidad_id;
        $_SESSION['tipo'] = $tipo;
        header('Location: ../../index.php');
        exit();
    }

    try{
        if(isset($_POST['action']) && $_POST['action'] == 'modificar'){
            //modifica
            $sentencia = "UPDATE contactos SET nombre = ?, apellido = ?, dni = ?, telefono = ?, correo = ?, direccion = ?, localidad = ?, cp = ?, parentesco = ?, observaciones = ? WHERE id_contacto = ?";
            $modificar = $conexion->prepare($sentencia); 
            $modificar->execute([$nombre, $apellido, $dni, $telefono, $correo, $direccion, $localidad, $cp, $parentesco, $observaciones, $id_contacto]);
           
        } else {
            //inserta
            $insertar = "
                INSERT INTO contactos (
                nombre, apellido, dni, telefono, correo, direccion, localidad, cp,
                parentesco, observaciones, entidad_id, tipo)
                
                VALUES (
                :nombre, :apellido, :dni, :telefono, :correo, :direccion, :localidad, :cp,
                :parentesco, :observaciones, :entidad_id, :tipo)
            ";
            $contacto = $conexion->prepare($insertar);
            $contacto->execute([
                ':nombre'=>$nombre, 
                ':apellido'=>$apellido, 
                ':dni'=>$dni, 
                ':telefono'=>$telefono, 
                ':correo'=>$correo, 
                ':direccion'=>$direccion, 
                ':localidad'=>$localidad, 
                ':cp'=>$cp,
                ':parentesco'=>$parentesco, 
                ':observaciones'=>$observaciones, 
                ':entidad_id'=>$entidad_id, 
                ':tipo'=>$tipo]);
            // $contacto->bindParam(':entidad_id',$entidad_id); // vincular el parámetro
                
        }

        if ($tipo == 'instructor') {
            unset($_SESSION['entidad_id']);
            unset($_SESSION['tipo']);
            header('Location: ../instructores/index.php');
        } else {
            unset($_SESSION['entidad_id']);
            unset($_SESSION['tipo']);
            header('Location: ../alumnos/index.php');
        }
        exit();

    } catch(PDOExeption $e){
        echo "Ocurrio un error de tipo: ".$e;
        echo "<a href:'../index.php'>Regresar a Instructore</a>";
    }
} else {
  header('Location: ../../index.php');  
}

?>