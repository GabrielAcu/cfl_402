<?php 
define('BASE_PATH', __DIR__); // Define la ruta absoluta del archivo actual

session_start();
require_once BASE_PATH . "/auth/check.php";

if (isLogin()) {
    idAdminOrInstructor();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Sistema CFL 402</title>
  <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
  <div class="login-container">
    <h1>Sistema de Gestión CFL 402</h1>
    <form method="POST" action="auth/login.php">
      <div class="form-group">
        <label for="usuario">Usuario</label>
        <input type="text" id="usuario" name="usuario" placeholder="Ingrese su usuario" >
      </div>

      <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" >
      </div>

      <div class="form-group">
        <button type="submit">Ingresar</button>
      </div>

      <?php if (isset($_SESSION['user'])): ?>
        <?php var_dump($_SESSION['user']['name']); ?>
      <?php endif; ?>


      <?php if (isset($_SESSION['mensaje'])): ?>
        <p class="error"><?= htmlspecialchars($_SESSION['mensaje']) ?></p>
        <?php 
          unset($_SESSION['mensaje']);
        ?>
      <?php endif; ?>
    </form>
  </div>
</body>
</html>
