<?php
// Repositorio de memorias con filtro por área/categoría.
// Página pública, no requiere sesión.
require_once 'db.php';

// Recibimos los filtros del formulario GET (si existen)
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

// Construimos la consulta base para memorias
$sql = "SELECT p.titulo, p.area, p.archivo, u.nombre
        FROM ponentes p
        JOIN usuarios u ON p.usuario_id = u.id
        WHERE p.tipo = 'memoria'";

// Añadimos filtro de categoría si se seleccionó uno
if (!empty($categoria)) {
    $cat_safe = mysqli_real_escape_string($enlace, $categoria);
    $sql .= " AND p.area = '$cat_safe'";
}

$sql .= " ORDER BY p.id DESC";
$res  = mysqli_query($enlace, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Memoria - Congreso Internacional</title>
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
        <h1>Memoria del Congreso</h1>
    </section>

    <!-- Filtro por categoría -->
    <div style="padding:20px;">
        <form method="get" action="memoria.php" style="display:flex; gap:15px; align-items:center; flex-wrap:wrap;">
            <label>Categoría:</label>
            <select name="categoria">
                <option value="">Todas</option>
                <option value="Medio Ambiente"  <?= $categoria === 'Medio Ambiente'  ? 'selected' : '' ?>>Medio Ambiente</option>
                <option value="Tecnología"      <?= $categoria === 'Tecnología'      ? 'selected' : '' ?>>Tecnología</option>
                <option value="Educación"       <?= $categoria === 'Educación'       ? 'selected' : '' ?>>Educación</option>
                <option value="Salud"           <?= $categoria === 'Salud'           ? 'selected' : '' ?>>Salud</option>
            </select>
            <button type="submit" class="btn-primary">Filtrar</button>
            <?php if ($categoria): ?>
                <a href="memoria.php">Limpiar filtro</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Listado de memorias -->
    <div class="repositorio">
        <?php if (mysqli_num_rows($res) === 0): ?>
            <p style="padding:20px;">No hay memorias registradas<?= $categoria ? " en la categoría '$categoria'" : "" ?>.</p>
        <?php else: ?>
            <?php while ($fila = mysqli_fetch_assoc($res)): ?>
            <div class="pdf-card">
                <h3><?= htmlspecialchars($fila['titulo']) ?></h3>
                <p><strong>Autor:</strong> <?= htmlspecialchars($fila['nombre']) ?></p>
                <p><strong>Área:</strong> <?= htmlspecialchars($fila['area']) ?></p>
                <?php if ($fila['archivo']): ?>
                    <a href="../uploads/<?= $fila['archivo'] ?>" target="_blank" class="btn-primary">Ver PDF</a>
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
