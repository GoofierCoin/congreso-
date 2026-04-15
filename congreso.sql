-- Base de datos del congreso
-- Ejecutar este archivo en phpMyAdmin o MySQL

CREATE DATABASE IF NOT EXISTS congreso;
USE congreso;

-- Tabla principal de usuarios (ponentes, participantes y admin)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    contrasena VARCHAR(255) NOT NULL,  -- guardamos el hash de la contraseña
    tipo ENUM('participante','ponente','admin') NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de participantes (datos extra)
CREATE TABLE IF NOT EXISTS participantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    institucion VARCHAR(150),
    asistencia ENUM('presencial','virtual') NOT NULL,
    pago_pdf VARCHAR(255),             -- ruta del recibo PDF generado
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabla de ponentes (datos extra + archivo)
CREATE TABLE IF NOT EXISTS ponentes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(200),
    resumen TEXT,
    area VARCHAR(100),
    tipo ENUM('ponencia','memoria') NOT NULL,
    archivo VARCHAR(255),              -- nombre del archivo subido
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Usuario administrador por defecto (contraseña: admin123)
INSERT INTO usuarios (nombre, correo, telefono, contrasena, tipo)
VALUES ('Administrador', 'admin@congreso.com', '0000000000',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
