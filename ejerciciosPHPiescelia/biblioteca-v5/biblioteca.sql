-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: mariadb:3306
-- Tiempo de generación: 13-11-2020 a las 12:41:40
-- Versión del servidor: 10.3.24-MariaDB
-- Versión de PHP: 7.3.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `biblioteca`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `escriben`
--

CREATE TABLE `escriben` (
  `idLibro` int(11) NOT NULL,
  `idPersona` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `escriben`
--

INSERT INTO `escriben` (`idLibro`, `idPersona`) VALUES
(1, 2),
(2, 4),
(3, 3),
(4, 1),
(15, 2),
(15, 3),
(16, 1),
(16, 2),
(16, 3),
(16, 4),
(17, 3),
(17, 2),
(18, 5),
(19, 5),
(20, 1),
(22, 1),
(23, 1),
(24, 1),
(24, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `idLibro` int(11) NOT NULL,
  `titulo` varchar(1000) CHARACTER SET utf8 NOT NULL,
  `genero` varchar(200) CHARACTER SET utf8 NOT NULL,
  `pais` varchar(200) CHARACTER SET utf8 NOT NULL,
  `ano` int(11) NOT NULL,
  `numPaginas` int(11) NOT NULL,
  `portada` varchar(500) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`idLibro`, `titulo`, `genero`, `pais`, `ano`, `numPaginas`, `portada`) VALUES
(1, 'El señor de los anillos', 'Fantástico', 'Reino Unido', 1954, 1020, 'el-senor-de-los-anillos.jpg'),
(2, 'Fundación y Tierra', 'Ciencia ficción', 'Estados Unidos', 1981, 484, 'fundacion-y-tierra.jpg'),
(3, 'Harry Potter y la piedra filosofal', 'Aventuras', 'Reino Unido', 1997, 331, 'harry-potter-y-la-piedra-filosofal.jpg'),
(4, 'El valle de los lobos', 'Fantástico', 'España', 2001, 352, 'el-valle-de-los-lobos.jpg'),
(21, 'asljads', 'lkjsdfl', 'sdflksj', 1234, 123, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `idPersona` int(11) NOT NULL,
  `nombre` varchar(200) CHARACTER SET utf8 NOT NULL,
  `apellido` varchar(200) CHARACTER SET utf8 NOT NULL,
  `fotografia` varchar(500) CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`idPersona`, `nombre`, `apellido`, `fotografia`) VALUES
(1, 'Laura', 'Gallego', 'laura-gallego.jpg'),
(2, 'J.R.R.', 'Tolkien', 'jrr-tolkien.jpg'),
(3, 'J. K.', 'Rowling', 'jk-rowling'),
(4, 'Isaac', 'Asimov', 'isaac-asimov.jpg'),
(5, 'Miguel', 'De Cervantes', 'miguel-de-cervantes.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuario` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(500) COLLATE utf8_spanish_ci NOT NULL,
  `fotografia` varchar(500) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `nombre`, `password`, `fotografia`) VALUES
(1, 'alfredo', '12345', 'foto-perfil.jpg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`idLibro`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`idPersona`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `idLibro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `idPersona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
