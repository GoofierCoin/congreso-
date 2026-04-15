<?php
// Panel del usuario logueado (participante o ponente).
// Verifica que haya sesión activa antes de mostrar el contenido.
session_start();
require_once 'db.php';

// Si no hay sesión, mandamos al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$tipo       = $_SESSION['tipo'];

// Obtenemos el nombre del usuario desde la BD
$sql  = "SELECT nombre FROM usuarios WHERE id = ?";
$stmt = mysqli_prepare($enlace, $sql);
mysqli_stmt_bind_param($stmt, "i", $usuario_id);
mysqli_stmt_execute($stmt);
$res     = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($res);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Panel - Congreso Internacional</title>
    <link rel="stylesheet" href="../css/interfaz.css">
</head>
<body>
    <div class="dashboard">

        <!-- Barra lateral -->
        <aside class="sidebar">
            <div class="logo-sidebar">
                <h2>Congreso</h2>
                <p class="rol-usuario"><?= ucfirst($tipo) ?></p>
            </div>
            <ul class="menu">
                <li><a href="interfaz.php" class="active">Mi Panel</a></li>
                <li><a href="../html/programa.html">Programa</a></li>
                <li><a href="../html/ponencias.html">Ponencias</a></li>
                <li><a href="../html/memoria.html">Memoria</a></li>
                <?php if ($tipo === 'ponente'): ?>
                    <!-- Solo los ponentes pueden subir archivos -->
                    <li><a href="subirPonencia.php">Subir Ponencia</a></li>
                <?php endif; ?>
                <?php if ($tipo === 'participante'): ?>
                    <!-- Solo los participantes tienen recibo de pago -->
                    <li><a href="recibo.php">Descargar Recibo</a></li>
                <?php endif; ?>
                <li><a href="pagos.php">Pagos</a></li>
                <li><a href="logout.php" class="logout">Cerrar Sesión</a></li>
            </ul>
        </aside>

        <!-- Contenido principal -->
        <main class="content">
            <header class="header">
                <h1>Bienvenido, <?= htmlspecialchars($usuario['nombre']) ?></h1>
                <span><?= ucfirst($tipo) ?></span>
            </header>

            <section class="section">
                <h2>Panel de <?= ucfirst($tipo) ?></h2>
                <?php if ($tipo === 'ponente'): ?>
                    <p>Desde aquí puedes subir tu ponencia o memoria y consultar el programa del congreso.</p>
                    <a href="subirPonencia.php" class="btn-primary">Subir Ponencia / Memoria</a>
                <?php elseif ($tipo === 'participante'): ?>
                    <p>Desde aquí puedes consultar el programa y descargar tu recibo de pago.</p>
                    <a href="recibo.php" class="btn-primary">Descargar Recibo PDF</a>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>
