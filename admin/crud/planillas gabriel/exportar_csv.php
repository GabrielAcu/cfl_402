<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';

// Seguridad
requireLogin();

// Conexión (usando tu función conectar())
$conn = conectar();

// Consulta de alumnos
$sql = "SELECT * FROM alumnos ORDER BY apellido, nombre";
$result = $conn->query($sql);

// Estas 2 lines deberia servirme para preparar las descargas sin usar las librerias
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=alumnos.csv');

// Aca voy a abrir salida como archivo CSV
$salida = fopen('php://output', 'w');

// Esta fila me configura el formato utf-8 para que excel lea las ñ y los acentos
fprintf($salida, chr(0xEF).chr(0xBB).chr(0xBF)); 

// Estas deberian ser las columnas
fputcsv($salida, [
    "ID",
    "Apellido",
    "Nombre",
    "DNI",
    "Fecha Nacimiento",
    "Teléfono",
    "Correo",
    "Dirección",
    "Localidad",
    "CP",
    "Activo",
    "Vehículo",
    "Patente",
    "Observaciones"
], ';');

// Estas son las filas, aca pido los datos a la bd
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($salida, [
        $row['id_alumno'],
        $row['apellido'],
        $row['nombre'],
        $row['dni'],
        $row['fecha_nacimiento'],
        $row['telefono'],
        $row['correo'],
        $row['direccion'],
        $row['localidad'],
        $row['cp'],
        $row['activo'],
        $row['vehiculo'],
        $row['patente'],
        $row['observaciones']
    ], ';');
}

exit;
