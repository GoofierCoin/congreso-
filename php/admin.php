<?php
// Panel de administración.
// Solo accesible si el usuario tiene tipo 'admin' en sesión.
session_start();
require_once 'db.php';

// Protegemos la página: solo el admin puede entrar
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Contamos totales para las tarjetas de resumen
$totalParticipantes = mysqli_num_rows(mysqli_query($enlace, "SELECT id FROM participantes"));
$totalPonentes      = mysqli_num_rows(mysqli_query($enlace, "SELECT id FROM ponentes"));
$totalUsuarios      = mysqli_num_rows(mysqli_query($enlace, "SELECT id FROM usuarios WHERE tipo != 'admin'"));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración - Congreso Internacional</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="dashboard">

        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo-sidebar">
                <h2>Congreso</h2>
                <p class="rol-usuario">Administrador</p>
            </div>
            <ul class="menu">
                <li><a href="admin.php" class="active">Dashboard</a></li>
                <li><a href="#participantes">Participantes</a></li>
                <li><a href="#ponentes">Ponentes</a></li>
                <li><a href="logout.php" class="logout">Cerrar Sesión</a></li>
            </ul>
        </aside>

        <!-- Contenido principal -->
        <main class="content">
            <header class="header">
                <h1>Panel de Administración</h1>
            </header>

            <!-- Tarjetas de resumen -->
            <section class="section">
                <h2>Resumen General</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">👥</div>
                        <div class="stat-info">
                            <h3>Participantes</h3>
                            <p class="stat-number"><?= $totalParticipantes ?></p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">🎤</div>
                        <div class="stat-info">
                            <h3>Ponentes</h3>
                            <p class="stat-number"><?= $totalPonentes ?></p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📊</div>
                        <div class="stat-info">
                            <h3>Total Usuarios</h3>
                            <p class="stat-number"><?= $totalUsuarios ?></p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Tabla de participantes con sus datos completos -->
            <section id="participantes" class="section">
                <h2>Participantes</h2>
                <table class="tabla-datos">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Institución</th>
                            <th>Asistencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Unimos usuarios con participantes para ver todos los datos
                        $res = mysqli_query($enlace,
                            "SELECT u.nombre, u.correo, u.telefono, p.institucion, p.asistencia
                             FROM participantes p
                             JOIN usuarios u ON p.usuario_id = u.id");
                        while ($fila = mysqli_fetch_assoc($res)):
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                            <td><?= htmlspecialchars($fila['correo']) ?></td>
                            <td><?= htmlspecialchars($fila['telefono']) ?></td>
                            <td><?= htmlspecialchars($fila['institucion']) ?></td>
                            <td><?= ucfirst($fila['asistencia']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>

            <!-- Tabla de ponentes con sus datos y archivo -->
            <section id="ponentes" class="section">
                <h2>Ponentes</h2>
                <table class="tabla-datos">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Título</th>
                            <th>Área</th>
                            <th>Tipo</th>
                            <th>Archivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = mysqli_query($enlace,
                            "SELECT u.nombre, u.correo, p.titulo, p.area, p.tipo, p.archivo
                             FROM ponentes p
                             JOIN usuarios u ON p.usuario_id = u.id");
                        while ($fila = mysqli_fetch_assoc($res)):
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                            <td><?= htmlspecialchars($fila['correo']) ?></td>
                            <td><?= htmlspecialchars($fila['titulo']) ?></td>
                            <td><?= htmlspecialchars($fila['area']) ?></td>
                            <td><?= ucfirst($fila['tipo']) ?></td>
                            <td>
                                <?php if ($fila['archivo']): ?>
                                    <a href="../uploads/<?= $fila['archivo'] ?>" target="_blank">Ver</a>
                                <?php else: ?>
                                    Sin archivo
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>

        </main>
    </div>
</body>
</html>
