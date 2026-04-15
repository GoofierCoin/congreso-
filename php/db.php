<?php
// Conexión a la base de datos.
// Se incluye en todos los archivos PHP que necesiten consultar la BD.
$enlace = mysqli_connect("localhost", "root", "", "congreso");

if (!$enlace) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Usamos UTF-8 para que los acentos y caracteres especiales funcionen bien
mysqli_set_charset($enlace, "utf8");
?>
