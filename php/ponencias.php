<?php
// Muestra el listado de ponencias registradas en la BD.
// Página pública, no requiere sesión.
require_once 'db.php';

// Consultamos todas las ponencias uniendo con la tabla de usuarios para obtener el nombre del autor
$res = mysqli_query($enlace,
    "SELECT p.titulo, p.resumen, p.area, p.tipo, p.archivo, u.nombre
     FROM ponentes p
     JOIN usuarios u ON p.usuario_id = u.id
     ORDER BY p.id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ponencias - Congreso Internacional</title>
    <link rel="stylesheet" href="../css/Style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <ul>
                <li><a href="../html/home.html">Home</a></li>
                <li><a href="../html/programa.html">Programa</a></li>
                <li><a href="ponencias.php">Ponencias</a></li>
                <li><a href="memoria.php">Memoria</a></li>
                <li><a href="registro.php">Registro</a></li>
                <li><a href="../html/pagos.html">Pagos</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <section class="banner" style="padding:30px;">
        <h1>Ponencias del Congreso</h1>
    </section>

    <div class="agenda">
        <?php
        // Si no hay ponencias registradas mostramos un mensaje
        if (mysqli_num_rows($res) === 0):
        ?>
            <p style="padding:20px;">No hay ponencias registradas aún.</p>
        <?php else: ?>
            <?php while ($fila = mysqli_fetch_assoc($res)): ?>
            <div class="evento">
                <h3><?= htmlspecialchars($fila['titulo']) ?></h3>
                <p><strong>Autor:</strong> <?= htmlspecialchars($fila['nombre']) ?></p>
                <p><strong>Área:</strong> <?= htmlspecialchars($fila['area']) ?></p>
                <p><strong>Tipo:</strong> <?= ucfirst($fila['tipo']) ?></p>
                <p><?= htmlspecialchars($fila['resumen']) ?></p>
                <?php if ($fila['archivo']): ?>
                    <a href="../uploads/<?= $fila['archivo'] ?>" target="_blank" class="btn-primary">Ver Archivo</a>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <footer>
        <p>© 2026 Congreso Internacional</p>
    </footer>
</body>
</html>
