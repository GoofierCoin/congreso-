<?php
// Procesa el formulario de login.
// Verifica correo y contrasena, inicia sesion y redirige segun el tipo de usuario.
session_start();
require_once 'db.php';

$error = "";

if (isset($_POST['login'])) {
    $correo     = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Buscamos el usuario por correo
    $sql  = "SELECT id, contrasena, tipo FROM usuarios WHERE correo = ?";
    $stmt = mysqli_prepare($enlace, $sql);
    mysqli_stmt_bind_param($stmt, "s", $correo);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($usuario = mysqli_fetch_assoc($resultado)) {
        // Verificamos la contrasena con el hash guardado
        if (password_verify($contrasena, $usuario['contrasena'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['tipo']       = $usuario['tipo'];

            // Redirigimos segun el tipo
            if ($_SESSION['tipo'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: interfaz.php");
            }
            exit;
        } else {
            $error = "Contrasena incorrecta.";
        }
    } else {
        $error = "Correo no registrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Congreso Internacional - Login</title>
    <link rel="stylesheet" href="../css/Style.css">
</head>
<body>
    <div class="formulario">
        <div class="logo-container">
            <h1>Congreso Internacional</h1>
            <p class="subtitle">Portal de Ponentes y Participantes</p>
        </div>

        <?php if ($error): ?>
            <p class="error-msg"><?= $error ?></p>
        <?php endif; ?>

        <form method="post" action="login.php">
            <h2>Inicio de Sesion</h2>

            <div class="input-group">
                <label for="correo">Correo electronico</label>
                <input type="email" id="correo" name="correo" required>
            </div>

            <div class="input-group">
                <label for="contrasena">Contrasena</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>

            <button type="submit" name="login" class="btn-login">Iniciar Sesion</button>

            <div class="register-link">
                <p>No tienes cuenta? <a href="registro.php">Registrate aqui</a></p>
            </div>
        </form>

        <div class="footer-info">
            <p>2026 Congreso Internacional</p>
        </div>
    </div>
</body>
</html>
