-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-08-2024 a las 22:57:22
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tiendacomputadores`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprador`
--

CREATE TABLE `comprador` (
  `id_comprador` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comprador`
--

INSERT INTO `comprador` (`id_comprador`, `nombre`, `apellido`, `email`, `telefono`) VALUES
(102345678, 'David', 'Jim?nez', 'david.jimenez@example.com', '555123465'),
(123456789, 'Ana', 'Garc?a', 'ana.garcia@example.com', '555123456'),
(234567890, 'Luis', 'Mart?nez', 'luis.martinez@example.com', '555123457'),
(345678901, 'Carlos', 'Lopez', 'carlos.lopez@example.com', '555123458'),
(456789012, 'Mar?a', 'Fern?ndez', 'maria.fernandez@example.com', '555123459'),
(567890123, 'Isabel', 'G?mez', 'isabel.gomez@example.com', '555123460'),
(678901234, 'Fernando', 'Hern?ndez', 'fernando.hernandez@example.com', '555123461'),
(789012345, 'Laura', 'P?rez', 'laura.perez@example.com', '555123462'),
(890123456, 'Javier', 'Ram?rez', 'javier.ramirez@example.com', '555123463'),
(901234567, 'Sara', 'Mart?n', 'sara.martin@example.com', '555123464');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `computador`
--

CREATE TABLE `computador` (
  `id_computador` int(11) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `computador`
--

INSERT INTO `computador` (`id_computador`, `marca`, `modelo`, `precio`, `stock`) VALUES
(1, 'Dell', 'Inspiron 15', 3299900.00, 8),
(2, 'HP', 'Pavilion x360', 3900000.00, 5),
(3, 'Lenovo', 'ThinkPad X1', 4835900.00, 5),
(4, 'Apple', 'MacBook Pro 13\"', 6100000.00, 6),
(5, 'Acer', 'Aspire TC', 2899900.00, 8),
(6, 'Asus', 'ROG Strix G10', 5699900.00, 4),
(7, 'HP', 'All-in-One 24', 4500000.00, 5),
(8, 'HP', 'Victus', 2350000.00, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_factura`
--

CREATE TABLE `detalle_factura` (
  `id_detalle` int(11) NOT NULL,
  `id_factura` int(11) DEFAULT NULL,
  `id_computador` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_factura`
--

INSERT INTO `detalle_factura` (`id_detalle`, `id_factura`, `id_computador`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 1, 1, 2, 3299900.00, 6599800.00),
(2, 1, 4, 1, 6100000.00, 6100000.00),
(3, 1, 5, 1, 2899900.00, 2899900.00),
(4, 2, 2, 1, 3900000.00, 3900000.00),
(5, 2, 5, 1, 2899900.00, 2899900.00),
(6, 2, 8, 3, 2350000.00, 7050000.00),
(7, 3, 7, 1, 4500000.00, 4500000.00),
(8, 4, 2, 2, 3900000.00, 7800000.00),
(9, 4, 5, 2, 2899900.00, 5799800.00),
(10, 5, 8, 2, 2350000.00, 4700000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id_factura` int(11) NOT NULL,
  `id_comprador` int(11) DEFAULT NULL,
  `fecha_compra` date NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`id_factura`, `id_comprador`, `fecha_compra`, `total`) VALUES
(1, 678901234, '2024-08-30', 15599700.00),
(2, 102345678, '2024-08-30', 13849900.00),
(3, 901234567, '2024-08-30', 4500000.00),
(4, 123456789, '2024-08-30', 13599800.00),
(5, 678901234, '2024-08-30', 4700000.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comprador`
--
ALTER TABLE `comprador`
  ADD PRIMARY KEY (`id_comprador`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `computador`
--
ALTER TABLE `computador`
  ADD PRIMARY KEY (`id_computador`);

--
-- Indices de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_factura` (`id_factura`),
  ADD KEY `id_computador` (`id_computador`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id_factura`),
  ADD KEY `id_comprador` (`id_comprador`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `computador`
--
ALTER TABLE `computador`
  MODIFY `id_computador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD CONSTRAINT `detalle_factura_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id_factura`),
  ADD CONSTRAINT `detalle_factura_ibfk_2` FOREIGN KEY (`id_computador`) REFERENCES `computador` (`id_computador`);

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`id_comprador`) REFERENCES `comprador` (`id_comprador`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
