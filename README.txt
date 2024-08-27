Título del proyecto: Generador de facturas | Aplicación Web

Descripción del proyecto: Este proyecto es una aplicación web desarrollada para gestionar la compra de computadores. El sistema permite registrar compradores, seleccionar un producto disponible, generar facturas, y gestionar el stock de los productos en la base de datos. La aplicación está desarrollada en PHP, HTML, y CSS, y utiliza MySQL como sistema de gestión de bases de datos.

Requisitos del sistema
- Servidor local (XAMPP)
- PHP 7.0 o superior
- MySQL 5.7 o superior
- Navegador web moderno

Instrucciones de instalación
- Clonar el repositorio en tu máquina local.
- Importa el script SQL proporcionado en la carpeta database a tu servidor MySQL.
- Configura la conexión a la base de datos en los archivos PHP según los parámetros de tu servidor.
- Inicia el servidor Apache y MySQL desde XAMPP.
- Accede al proyecto desde tu navegador a través de http://localhost/nombre_proyecto.

Uso del sistema
- Registra un nuevo comprador ingresando los datos solicitados, incluyendo el número de cédula.
- Selecciona un comprador de la lista para realizar una compra.
- Navega por la lista de productos disponibles y realiza la compra.
- Visualiza la factura generada automáticamente después de la compra.

Creación de la base de datos por código SQL:

-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS TiendaComputadores;
USE TiendaComputadores;

-- Crear la tabla Comprador con id_comprador como número de cédula
CREATE TABLE Comprador (
    id_comprador INT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(15)
);

CREATE TABLE Computador (
    id_computador INT AUTO_INCREMENT PRIMARY KEY,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL
);
CREATE TABLE Factura (
    id_factura INT AUTO_INCREMENT PRIMARY KEY,
    id_comprador INT,
    fecha_compra DATE NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_comprador) REFERENCES Comprador(id_comprador)
);
CREATE TABLE Detalle_Factura (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_factura INT,
    id_computador INT,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_factura) REFERENCES Factura(id_factura),
    FOREIGN KEY (id_computador) REFERENCES Computador(id_computador)
);



-- Insertar productos en la tabla Computador
INSERT INTO Computador (marca, modelo, precio, stock) VALUES
('Dell', 'Inspiron 15', 3299900.00, 10),
('HP', 'Pavilion x360', 3900000.00, 8),
('Lenovo', 'ThinkPad X1', 4835900.00, 5),
('Apple', 'MacBook Pro 13"', 6100000.00, 7),
('Acer', 'Aspire TC', 2899900.00, 12),
('Asus', 'ROG Strix G10', 5699900.00, 4),
('HP', 'All-in-One 24', 4500000.00, 6);



-- Insertar compradores en la tabla Comprador
INSERT INTO Comprador (id_comprador, nombre, apellido, email, telefono) VALUES
(123456789, 'Ana', 'García', 'ana.garcia@example.com', '555123456'),
(234567890, 'Luis', 'Martínez', 'luis.martinez@example.com', '555123457'),
(345678901, 'Carlos', 'Lopez', 'carlos.lopez@example.com', '555123458'),
(456789012, 'María', 'Fernández', 'maria.fernandez@example.com', '555123459'),
(567890123, 'Isabel', 'Gómez', 'isabel.gomez@example.com', '555123460'),
(678901234, 'Fernando', 'Hernández', 'fernando.hernandez@example.com', '555123461'),
(789012345, 'Laura', 'Pérez', 'laura.perez@example.com', '555123462'),
(890123456, 'Javier', 'Ramírez', 'javier.ramirez@example.com', '555123463'),
(901234567, 'Sara', 'Martín', 'sara.martin@example.com', '555123464'),
(102345678, 'David', 'Jiménez', 'david.jimenez@example.com', '555123465');
