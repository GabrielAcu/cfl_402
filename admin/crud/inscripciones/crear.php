<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';

requireLogin();
$conn = conectar();

// VALIDAR TIPO
$tipo = $_POST["tipo"] ?? null;
if (!$tipo) {
    die("Falta tipo en crear.php");
}

// Mapear parámetros
$map = [
    "curso"      => ["id_param" => "id_curso",      "redir_input" => "id_curso"],
    "alumno"     => ["id_param" => "id_alumno",     "redir_input" => "id_alumno"],
    "instructor" => ["id_param" => "id_instructor", "redir_input" => "id_instructor"],
];

if (!isset($map[$tipo])) {
    die("Tipo inválido");
}

// Recibir IDs
$id_param = $map[$tipo]["id_param"];
$id = $_POST[$id_param] ?? null;
if (!$id) {
    die("Falta $id_param en POST.");
}
$id = intval($id);

// PROCESAR SEGÚN TIPO
try {
    if ($tipo === "curso" || $tipo === "alumno") {
        // ambos insertan en inscripciones
        // determinar quién es alumno y quién curso
        if ($tipo === "curso") {
            $id_curso = $id;
            $id_alumno = intval($_POST["id_alumno"] ?? 0);
        } else { // alumno
            $id_alumno = $id;
            $id_curso = intval($_POST["id_curso"] ?? 0);
        }
        if (!$id_curso || !$id_alumno) {
            die("Datos incompletos: falta id_curso o id_alumno.");
        }
        $stmt = $conn->prepare("INSERT INTO inscripciones (id_curso, id_alumno, fecha_inscripcion) VALUES (?, ?, NOW())");
        $stmt->execute([$id_curso, $id_alumno]);

        // Valores para el formulario de retorno
        $redir_tipo = $tipo;
        $redir_values = [
            "tipo" => $tipo,
            // usaremos input con el nombre exacto que index.php espera
            $map[$tipo]["redir_input"] => $id
        ];

    } elseif ($tipo === "instructor") {
        $id_instructor = $id;
        $id_curso = intval($_POST["id_curso"] ?? 0);
        if (!$id_instructor || !$id_curso) {
            die("Datos incompletos: falta id_instructor o id_curso.");
        }

        // Aquí asumimos que querés insertar en la tabla pivot. Si usas UPDATE en cursos cambialo.
        $stmt = $conn->prepare("INSERT INTO instructores_cursos (id_instructor, id_curso) VALUES (?, ?)");
        $stmt->execute([$id_instructor, $id_curso]);

        $redir_tipo = $tipo;
        $redir_values = [
            "tipo" => $tipo,
            $map[$tipo]["redir_input"] => $id
        ];
    } else {
        die("Tipo no soportado");
    }
} catch (Exception $e) {
    // por si hay error en la BD
    die("Error al insertar: " . $e->getMessage());
}

// Si llegamos acá, la inserción fue OK.
// Generamos un pequeño HTML con formulario POST que reenvía al index.php
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Redirigiendo...</title>
</head>
<body>
<p>Redirigiendo, espere...</p>

<form id="redirForm" action="index.php" method="POST" style="display:none;">
    <?php foreach ($redir_values as $k => $v): ?>
        <input type="hidden" name="<?= htmlspecialchars($k, ENT_QUOTES) ?>" value="<?= htmlspecialchars($v, ENT_QUOTES) ?>">
    <?php endforeach; ?>
</form>

<noscript>
    <p>Si no se redirige automáticamente, hacé clic en el botón:</p>
    <form action="index.php" method="POST">
        <?php foreach ($redir_values as $k => $v): ?>
            <input type="hidden" name="<?= htmlspecialchars($k, ENT_QUOTES) ?>" value="<?= htmlspecialchars($v, ENT_QUOTES) ?>">
        <?php endforeach; ?>
        <button type="submit">Volver al listado</button>
    </form>
</noscript>

<script>
    // Intentamos enviar el formulario automáticamente
    try {
        document.getElementById('redirForm').submit();
    } catch (e) {
        // En caso de fallo mostramos un debug pequeño
        console.error('No se pudo enviar el formulario automáticamente', e);
        document.body.innerHTML += '<p>Error en el auto-submit, por favor presione el botón de abajo.</p>';
        // mostrar un botón manual si algo falla
        var b = document.createElement('button');
        b.textContent = 'Volver al listado';
        b.onclick = function() {
            var f = document.createElement('form');
            f.method = 'POST';
            f.action = 'index.php';
            <?php foreach ($redir_values as $k => $v): ?>
            var i = document.createElement('input'); i.type='hidden'; i.name='<?= htmlspecialchars($k, ENT_QUOTES) ?>'; i.value='<?= htmlspecialchars($v, ENT_QUOTES) ?>'; f.appendChild(i);
            <?php endforeach; ?>
            document.body.appendChild(f);
            f.submit();
        };
        document.body.appendChild(b);
    }
</script>

</body>
</html>
