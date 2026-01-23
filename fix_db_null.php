<?php
require_once __DIR__ . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';

$conn = conectar();

echo "<h2>Migración: Permitir Instructor NULL en Cursos</h2>";

try {
    // 1. Eliminar Foreign Key existente (el nombre suele ser cursos_ibfk_1, pero verificamos)
    // En MariaDB/MySQL a veces es mejor modificar la columna directamente y el motor ajusta, 
    // pero si hay FK estricta puede fallar.
    
    // Intentamos modificar la columna a NULLABLE
    echo "Intentando modificar columna id_instructor a NULL...<br>";
    $sql = "ALTER TABLE cursos MODIFY id_instructor int(11) NULL";
    $conn->exec($sql);
    echo "<strong style='color:green'>EXITO: id_instructor ahora acepta NULL.</strong><br>";

    // Notar: Si falla por FK, habría que dropear FK y recrear. 
    // Pero en XAMPP/MariaDB default suele permitirlo salvo que la FK tenga reglas raras.
    // La FK existente "REFERENCES instructores(id_instructor)" valida que SI hay valor, exista. NULL es ignorado.

} catch (PDOException $e) {
    echo "<strong style='color:red'>ERROR: " . $e->getMessage() . "</strong><br>";
    echo "Probablemente necesitemos eliminar la restricción FK primero.<br>";
}
?>
