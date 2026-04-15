<?php
// Procesa el formulario de registro.
// Inserta en la tabla usuarios y luego en participantes o ponentes según el tipo.
session_start();
require_once 'db.php';

$error = "";

if (isset($_POST['registro'])) {
    $nombre     = $_POST['nombre'];
    $correo     = $_POST['correo'];
    $telefono   = $_POST['telefono'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // encriptamos la contraseña
    $tipo       = $_POST['tipo'];

    // Verificamos que el correo no esté ya registrado
    $check = mysqli_prepare($enlace, "SELECT id FROM usuarios WHERE correo = ?");
    mysqli_stmt_bind_param($check, "s", $correo);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
        $error = "Ese correo ya está registrado.";
    } else {
        // Insertamos el usuario base
        $sql  = "INSERT INTO usuarios (nombre, correo, telefono, contrasena, tipo) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($enlace, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", $nombre, $correo, $telefono, $contrasena, $tipo);
        mysqli_stmt_execute($stmt);
        $usuario_id = mysqli_insert_id($enlace);

        // Insertamos datos extra según el tipo
        if ($tipo === 'participante') {
            $institucion = $_POST['institucion'];
            $asistencia  = $_POST['asistencia'];
            $sql_part    = "INSERT INTO participantes (usuario_id, institucion, asistencia) VALUES (?, ?, ?)";
            $stmt_part   = mysqli_prepare($enlace, $sql_part);
            mysqli_stmt_bind_param($stmt_part, "iss", $usuario_id, $institucion, $asistencia);
            mysqli_stmt_execute($stmt_part);

        } else { // ponente
            $titulo       = $_POST['titulo'];
            $resumen      = $_POST['resumen'];
            $area         = $_POST['area'];
            $tipoPonencia = $_POST['tipoPonencia'];
            $archivo      = "";

            // Subimos el archivo si se proporcionó
            if (!empty($_FILES['archivo']['name'])) {
                $archivo  = time() . "_" . basename($_FILES['archivo']['name']);
                $ruta     = "../uploads/" . $archivo;
                move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta);
            }

            $sql_pon  = "INSERT INTO ponentes (usuario_id, titulo, resumen, area, tipo, archivo) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_pon = mysqli_prepare($enlace, $sql_pon);
            mysqli_stmt_bind_param($stmt_pon, "isssss", $usuario_id, $titulo, $resumen, $area, $tipoPonencia, $archivo);
            mysqli_stmt_execute($stmt_pon);
        }

        // Iniciamos sesión automáticamente tras el registro
        $_SESSION['usuario_id'] = $usuario_id;
        $_SESSION['tipo']       = $tipo;
        header("Location: interfaz.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Congreso Internacional</title>
    <link rel="stylesheet" href="../css/Style.css">
    <script>
        // Muestra u oculta los campos según el tipo de usuario seleccionado
        function mostrarCampos() {
            var tipo = document.getElementById("tipo").value;
            document.getElementById("campos-participante").style.display = (tipo === "participante") ? "block" : "none";
            document.getElementById("campos-ponente").style.display     = (tipo === "ponente")      ? "block" : "none";
        }
        // Ejecutamos al cargar para mostrar el estado inicial
        window.onload = mostrarCampos;
    </script>
</head>
<body>
    <header>
        <nav class="navbar">
            <ul>
                <li><a href="../html/home.html">Home</a></li>
                <li><a href="../html/programa.html">Programa</a></li>
                <li><a href="../html/ponencias.html">Ponencias</a></li>
                <li><a href="../html/memoria.html">Memoria</a></li>
                <li><a href="registro.php">Registro</a></li>
                <li><a href="../html/pagos.html">Pagos</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <div class="formulario">
        <div class="logo-container">
            <h1>Congreso Internacional</h1>
            <p class="subtitle">Registro de Usuario</p>
        </div>

        <?php if ($error): ?>
            <p class="error-msg"><?= $error ?></p>
        <?php endif; ?>

        <!-- enctype necesario para subir archivos -->
        <form method="post" action="registro.php" enctype="multipart/form-data">
            <h2>Crear Cuenta</h2>

            <div class="input-group">
                <label>Nombre completo</label>
                <input type="text" name="nombre" required>
            </div>
            <div class="input-group">
                <label>Correo electrónico</label>
                <input type="email" name="correo" required>
            </div>
            <div class="input-group">
                <label>Teléfono</label>
                <input type="tel" name="telefono" required>
            </div>
            <div class="input-group">
                <label>Contraseña</label>
                <input type="password" name="contrasena" required>
            </div>

            <div class="input-group">
                <label>Tipo de registro</label>
                <select name="tipo" id="tipo" onchange="mostrarCampos()" required>
                    <option value="participante">Participante</option>
                    <option value="ponente">Ponente</option>
                </select>
            </div>

            <!-- Campos solo para participante -->
            <div id="campos-participante">
                <div class="input-group">
                    <label>Institución</label>
                    <input type="text" name="institucion">
                </div>
                <div class="input-group">
                    <label>Tipo de asistencia</label>
                    <select name="asistencia">
                        <option value="presencial">Presencial</option>
                        <option value="virtual">Virtual</option>
                    </select>
                </div>
            </div>

            <!-- Campos solo para ponente -->
            <div id="campos-ponente" style="display:none;">
                <div class="input-group">
                    <label>Título de ponencia</label>
                    <input type="text" name="titulo">
                </div>
                <div class="input-group">
                    <label>Resumen</label>
                    <textarea name="resumen" rows="3"></textarea>
                </div>
                <div class="input-group">
                    <label>Área temática</label>
                    <input type="text" name="area">
                </div>
                <div class="input-group">
                    <label>Tipo</label>
                    <select name="tipoPonencia">
                        <option value="ponencia">Ponencia</option>
                        <option value="memoria">Memoria</option>
                    </select>
                </div>
                <div class="input-group">
                    <label>Archivo (PDF)</label>
                    <input type="file" name="archivo" accept=".pdf">
                </div>
            </div>

            <button type="submit" name="registro" class="btn-login">Registrar</button>

            <div class="register-link">
                <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
            </div>
        </form>
    </div>
</body>
</html>
