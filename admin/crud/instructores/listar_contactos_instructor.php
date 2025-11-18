<?php

$id_instructor=$_POST['id_instructor'];
$tipo=$_POST['tipo'];

var_dump($id_instructor);
var_dump($tipo);

echo "Aca se listan los contactos del instructor seleccionado.";
echo "<a href='index.php'>Volver al listado de instructores</a>";