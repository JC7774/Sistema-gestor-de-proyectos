-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-07-2025 a las 19:55:00
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gestor_proyectos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos_proyecto`
--

CREATE TABLE `documentos_proyecto` (
  `id` int(11) NOT NULL,
  `id_proyecto` int(10) UNSIGNED NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `documentos_proyecto`
--

INSERT INTO `documentos_proyecto` (`id`, `id_proyecto`, `nombre_archivo`, `ruta_archivo`) VALUES
(9, 56, '2. Solicitud de Inscripción.pdf', 'documentos/56/686a190b880cd_2. Solicitud de Inscripción.pdf'),
(10, 50, '2. Solicitud de Inscripción.pdf', 'documentos/50/686a1a13bf2e7_2. Solicitud de Inscripción.pdf'),
(12, 54, '3. Carta de aceptación-Dependencia IDS.pdf', 'documentos/54/6877ea2cc2d4c_3. Carta de aceptación-Dependencia IDS.pdf'),
(13, 59, 'certificado (1).pdf', 'documentos/59/6887911da2787_certificado (1).pdf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `estatus` varchar(20) NOT NULL,
  `asignado_a` int(10) UNSIGNED DEFAULT NULL,
  `comentarios` text DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `complejidad` varchar(20) DEFAULT 'medio'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proyectos`
--

INSERT INTO `proyectos` (`id`, `nombre`, `descripcion`, `estatus`, `asignado_a`, `comentarios`, `fecha_inicio`, `fecha_fin`, `fecha_creacion`, `complejidad`) VALUES
(45, 'prueba 1', 'prueba 1', 'finalizado', 4, 'prueba 1', '2025-07-02', '2025-07-11', '2025-07-05 00:00:00', 'medio'),
(46, 'prueba 2', 'prueba 2', 'activo', 5, 'prueba 2 para saber si es funcional el actualizar comentarios desde el panel del programador', '2025-07-01', '2025-07-05', '2025-07-05 00:00:00', 'medio'),
(47, 'prueba 3', 'prueba 3', 'activo', 3, 'prueba 3', '2025-07-01', '2025-07-05', '2025-07-05 00:00:00', 'medio'),
(48, 'prueba 5', 'prueba 5', 'finalizado', 3, 'prueba 5', '2025-07-01', '2025-07-06', '2025-07-05 00:00:00', 'especial'),
(49, 'prueba 6', 'prueba 6', 'pausado', 4, 'prueba 6', '2025-07-01', '2025-07-05', '2025-07-05 00:00:00', 'complejo'),
(50, 'prueba 7', 'prueba 7', 'activo', 6, 'error en arquitectura', '2025-07-01', '2025-07-05', '2025-07-05 00:00:00', 'especial'),
(51, 'prueba 8 ', 'prueba 8', 'activo', 5, 'prueba 8', '2025-07-01', '2025-07-05', '2025-07-05 00:00:00', 'complejo'),
(52, 'prueba 9', 'prueba 9', 'pausado', 5, 'prueba 9', '2025-06-30', '2025-07-05', '2025-07-05 00:00:00', 'complejo'),
(53, 'prueba 10', 'prueba 10', 'pausado', 5, 'prueba 10', '2025-06-29', '2025-07-03', '2025-07-05 00:00:00', 'complejo'),
(54, 'prueba 11', 'prueba 11', 'finalizado', 6, 'prueba 10', '2025-06-30', '2025-07-05', '2025-07-05 00:00:00', 'complejo'),
(55, 'prueba 12', 'prueba 12', 'activo', 6, 'prueba 12', '2025-07-02', '2025-07-04', '2025-07-05 00:00:00', 'complejo'),
(56, 'prueba 13', 'prueba 13', 'pausado', 6, 'prueba 13', '2025-07-01', '2025-07-05', '2025-07-05 00:00:00', 'complejo'),
(57, 'prueba 14', 'prueba 14', 'finalizado', 6, 'prueba 14', '2025-07-01', '2025-07-05', '2025-07-05 00:00:00', 'complejo'),
(58, 'prueba 15', 'prueba 15', 'activo', 5, 'prueba 15', '2025-07-01', '2025-07-05', '2025-07-05 00:00:00', 'medio'),
(59, 'Prueba 16', 'Prueba 16', 'activo', 4, 'Prueba 16', '2025-07-01', '2025-07-04', '2025-07-06 00:00:00', 'complejo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `empleado` varchar(8) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido_paterno` varchar(100) NOT NULL,
  `apellido_materno` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `empleado`, `nombre`, `apellido_paterno`, `apellido_materno`, `correo`, `password`, `rol`) VALUES
(1, '90100048', 'juan', 'carlos', 'zazueta', 'juanzazueta@coppel.com', 'Aa123456789$$', 'administrador'),
(2, '90100049', 'gerson', 'bueno', 'amarillas', 'gerson@coppel.com', 'Aa123456789$$', 'arquitecto'),
(3, '90100050', 'america', 'arellano', 'lopez', 'america@coppel.com', 'Aa123456789$$', 'programador'),
(4, '90100051', 'thania', 'figueroa', 'quevedo', 't@outlook.com', 'Aa123456789$$', 'programador'),
(5, '90100052', 'sergio', 'lopez', 'quiñones', 's@outloo.com', 'Aa123456789$$', 'programador'),
(6, '90100053', 'Manuel ', 'Landeros', 'lopez', 'M@coppel.com', 'Aa123456789$$', 'programador'),
(12, '90100070', 'Montserrat', 'Urias', 'Castañeda', 'MC@hotmail.com', 'Aa123456789$$', 'programador');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `documentos_proyecto`
--
ALTER TABLE `documentos_proyecto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_proyecto` (`id_proyecto`);

--
-- Indices de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `documentos_proyecto`
--
ALTER TABLE `documentos_proyecto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `documentos_proyecto`
--
ALTER TABLE `documentos_proyecto`
  ADD CONSTRAINT `documentos_proyecto_ibfk_1` FOREIGN KEY (`id_proyecto`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
