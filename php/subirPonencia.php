<?php
// Permite a los ponentes subir o actualizar su ponencia/memoria.
session_start();
require_once 'db.php';

// Solo ponentes pueden acceder
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'ponente') {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$mensaje    = "";

// Procesamos el formulario de subida
if (isset($_POST['subir'])) {
    $titulo       = $_POST['titulo'];
    $resumen      = $_POST['resumen'];
    $area         = $_POST['area'];
    $tipoPonencia = $_POST['tipoPonencia'];

    // Verificamos que se haya subido un archivo
    if (!empty($_FILES['archivo']['name'])) {
        // Añadimos timestamp al nombre para evitar duplicados
        $archivo = time() . "_" . basename($_FILES['archivo']['name']);
        $ruta    = "../uploads/" . $archivo;

        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta)) {
            // Verificamos si ya tiene una ponencia registrada
            $check = mysqli_prepare($enlace, "SELECT id FROM ponentes WHERE usuario_id = ?");
            mysqli_stmt_bind_param($check, "i", $usuario_id);
            mysqli_stmt_execute($check);
            mysqli_stmt_store_result($check);

            if (mysqli_stmt_num_rows($check) > 0) {
                // Actualizamos la ponencia existente
                $sql  = "UPDATE ponentes SET titulo=?, resumen=?, area=?, tipo=?, archivo=? WHERE usuario_id=?";
                $stmt = mysqli_prepare($enlace, $sql);
                mysqli_stmt_bind_param($stmt, "sssssi", $titulo, $resumen, $area, $tipoPonencia, $archivo, $usuario_id);
            } else {
                // Insertamos nueva ponencia
                $sql  = "INSERT INTO ponentes (usuario_id, titulo, resumen, area, tipo, archivo) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($enlace, $sql);
                mysqli_stmt_bind_param($stmt, "isssss", $usuario_id, $titulo, $resumen, $area, $tipoPonencia, $archivo);
            }
            mysqli_stmt_execute($stmt);
            $mensaje = "ok";
        } else {
            $mensaje = "error";
        }
    } else {
        $mensaje = "sin_archivo";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Ponencia - Congreso Internacional</title>
    <link rel="stylesheet" href="../css/interfaz.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo-sidebar">
                <h2>Congreso</h2>
                <p class="rol-usuario">Ponente</p>
            </div>
            <ul class="menu">
                <li><a href="interfaz.php">Mi Panel</a></li>
                <li><a href="subirPonencia.php" class="active">Subir Ponencia</a></li>
                <li><a href="pagos.php">Pagos</a></li>
                <li><a href="logout.php" class="logout">Cerrar Sesión</a></li>
            </ul>
        </aside>

        <main class="content">
            <header class="header">
                <h1>Subir Ponencia o Memoria</h1>
            </header>

            <section class="section">
                <!-- Mensajes de resultado -->
                <?php if ($mensaje === 'ok'): ?>
                    <p style="color:green;">Ponencia subida correctamente.</p>
                <?php elseif ($mensaje === 'error'): ?>
                    <p style="color:red;">Error al subir el archivo. Intenta de nuevo.</p>
                <?php elseif ($mensaje === 'sin_archivo'): ?>
                    <p style="color:orange;">Debes seleccionar un archivo PDF.</p>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data">
                    <div class="input-group">
                        <label>Título</label>
                        <input type="text" name="titulo" required>
                    </div>
                    <div class="input-group">
                        <label>Resumen</label>
                        <textarea name="resumen" rows="4" required></textarea>
                    </div>
                    <div class="input-group">
                        <label>Área temática</label>
                        <input type="text" name="area" required>
                    </div>
                    <div class="input-group">
                        <label>Tipo</label>
                        <select name="tipoPonencia" required>
                            <option value="ponencia">Ponencia</option>
                            <option value="memoria">Memoria</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label>Archivo (PDF)</label>
                        <input type="file" name="archivo" accept=".pdf" required>
                    </div>
                    <button type="submit" name="subir" class="btn-primary">Subir</button>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
