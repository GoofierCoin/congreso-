<?php
// Página de pagos para usuarios logueados.
// Muestra el precio según el tipo de usuario y simula el pago con PayPal.
session_start();
require_once 'db.php';

// Verificamos que haya sesión activa
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$tipo   = $_SESSION['tipo'];
// Precio diferente según si es ponente o participante
$precio = ($tipo === 'ponente') ? 800 : 500;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagos - Congreso Internacional</title>
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
                <li><a href="pagos.php" class="active">Pagos</a></li>
                <li><a href="logout.php" class="logout">Cerrar Sesión</a></li>
            </ul>
        </aside>

        <main class="content">
            <header class="header">
                <h1>Pago de Inscripción</h1>
            </header>

            <section class="section">
                <h2>Resumen de Pago</h2>
                <p>Tipo de usuario: <strong><?= ucfirst($tipo) ?></strong></p>
                <p>Monto a pagar: <strong>$<?= $precio ?> MXN</strong></p>

                <!-- Imagen de PayPal simulada (no funciona, solo visual) -->
                <div style="margin: 20px 0;">
                    <img src="../img/paypal.png" alt="Pago con PayPal (simulado)" style="width:200px; display:block; margin-bottom:10px;">
                    <!-- Este botón simula la confirmación de pago -->
                    <a href="pago.php" class="btn-primary">Confirmar Pago (Simulado)</a>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
