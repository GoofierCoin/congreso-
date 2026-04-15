<?php
// Confirmación simulada de pago.
// Marca el pago como confirmado en la sesión y muestra mensaje al usuario.
session_start();
require_once 'db.php';

// Verificamos sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$tipo       = $_SESSION['tipo'];

// Marcamos el pago como confirmado en la BD según el tipo
if ($tipo === 'participante') {
    $sql  = "UPDATE participantes SET pago_pdf = 'confirmado' WHERE usuario_id = ?";
    $stmt = mysqli_prepare($enlace, $sql);
    mysqli_stmt_bind_param($stmt, "i", $usuario_id);
    mysqli_stmt_execute($stmt);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pago Confirmado - Congreso Internacional</title>
    <link rel="stylesheet" href="../css/interfaz.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo-sidebar">
                <h2>Congreso</h2>
                <p class="rol-usuario"><?= ucfirst($tipo) ?></p>
            </div>
            <ul class="menu">
                <li><a href="interfaz.php">Mi Panel</a></li>
                <li><a href="logout.php" class="logout">Cerrar Sesión</a></li>
            </ul>
        </aside>

        <main class="content">
            <header class="header">
                <h1>Pago Confirmado</h1>
            </header>
            <section class="section">
                <p>Tu pago ha sido registrado correctamente.</p>
                <?php if ($tipo === 'participante'): ?>
                    <p>Ahora puedes generar tu recibo de pago en PDF.</p>
                    <a href="recibo.php" class="btn-primary">Generar Recibo PDF</a>
                <?php else: ?>
                    <a href="interfaz.php" class="btn-primary">Volver al Panel</a>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>
