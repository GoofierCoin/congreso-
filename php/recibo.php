<?php
// Genera y descarga el recibo de pago en PDF para participantes.
// Usa la librería FPDF incluida en la carpeta /fpdf.
session_start();
require_once 'db.php';
require_once '../fpdf/fpdf.php';

// Solo participantes pueden generar recibo
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'participante') {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Consultamos los datos del participante uniendo las dos tablas
$sql  = "SELECT u.nombre, u.correo, u.telefono, p.institucion, p.asistencia
         FROM usuarios u
         JOIN participantes p ON u.id = p.usuario_id
         WHERE u.id = ?";
$stmt = mysqli_prepare($enlace, $sql);
mysqli_stmt_bind_param($stmt, "i", $usuario_id);
mysqli_stmt_execute($stmt);
$res   = mysqli_stmt_get_result($stmt);
$datos = mysqli_fetch_assoc($res);

// Si se presiona el botón, generamos el PDF
if (isset($_POST['generar'])) {
    $precio = 500; // precio fijo para participantes

    $pdf = new FPDF();
    $pdf->AddPage();

    // Encabezado del recibo
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Recibo de Pago - Congreso Internacional 2026', 0, 1, 'C');
    $pdf->Ln(8);

    // Línea separadora
    $pdf->SetDrawColor(0, 64, 128);
    $pdf->SetLineWidth(0.5);
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln(8);

    // Datos del participante
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(50, 8, 'Nombre:', 0, 0);
    $pdf->Cell(0,  8, $datos['nombre'], 0, 1);

    $pdf->Cell(50, 8, 'Correo:', 0, 0);
    $pdf->Cell(0,  8, $datos['correo'], 0, 1);

    $pdf->Cell(50, 8, 'Telefono:', 0, 0);
    $pdf->Cell(0,  8, $datos['telefono'], 0, 1);

    $pdf->Cell(50, 8, 'Institucion:', 0, 0);
    $pdf->Cell(0,  8, $datos['institucion'], 0, 1);

    $pdf->Cell(50, 8, 'Asistencia:', 0, 0);
    $pdf->Cell(0,  8, ucfirst($datos['asistencia']), 0, 1);

    $pdf->Ln(8);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(50, 8, 'Monto pagado:', 0, 0);
    $pdf->Cell(0,  8, '$' . $precio . ' MXN', 0, 1);

    // Guardamos el PDF en la carpeta recibos/
    $nombreArchivo = "recibo_" . $usuario_id . ".pdf";
    $ruta          = "../recibos/" . $nombreArchivo;
    $pdf->Output('F', $ruta);

    // Actualizamos la ruta del recibo en la BD
    $sql_up  = "UPDATE participantes SET pago_pdf = ? WHERE usuario_id = ?";
    $stmt_up = mysqli_prepare($enlace, $sql_up);
    mysqli_stmt_bind_param($stmt_up, "si", $nombreArchivo, $usuario_id);
    mysqli_stmt_execute($stmt_up);

    // Enviamos el PDF al navegador para descarga
    $pdf->Output('D', "Recibo_" . $datos['nombre'] . ".pdf");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Pago - Congreso Internacional</title>
    <link rel="stylesheet" href="../css/interfaz.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo-sidebar">
                <h2>Congreso</h2>
                <p class="rol-usuario">Participante</p>
            </div>
            <ul class="menu">
                <li><a href="interfaz.php">Mi Panel</a></li>
                <li><a href="recibo.php" class="active">Recibo</a></li>
                <li><a href="logout.php" class="logout">Cerrar Sesión</a></li>
            </ul>
        </aside>

        <main class="content">
            <header class="header">
                <h1>Recibo de Pago</h1>
            </header>
            <section class="section">
                <p>Hola <strong><?= htmlspecialchars($datos['nombre']) ?></strong>, aquí puedes descargar tu recibo de pago en PDF.</p>
                <form method="post">
                    <button type="submit" name="generar" class="btn-primary">Descargar Recibo PDF</button>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
