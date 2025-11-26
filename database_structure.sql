-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-11-2025 a las 13:55:02
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cfl402_2025_2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `id_alumno` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `dni` int(11) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `correo` varchar(70) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `localidad` varchar(50) NOT NULL,
  `cp` varchar(8) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `vehiculo` varchar(70) DEFAULT NULL,
  `patente` varchar(10) DEFAULT NULL,
  `observaciones` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id_alumno`, `nombre`, `apellido`, `dni`, `fecha_nacimiento`, `telefono`, `correo`, `direccion`, `localidad`, `cp`, `activo`, `vehiculo`, `patente`, `observaciones`) VALUES
(1, 'Juana', 'González', 38222111, '0000-00-00', '11-5566-7788', '', '', '', '', 1, '', '', ''),
(3, 'Martín', 'Pereyra', 37999888, '0000-00-00', '11-8765-5544', '', '', '', '', 1, '', '', ''),
(5, 'Juan', 'Pérez', 38222999, '0000-00-00', '11-9999-5555', '', '', '', '', 1, '', '', ''),
(10, 'Mirta', 'Legrand', 3450697, '0000-00-00', '', '', '', '', '', 1, '', '', ''),
(12, 'Carlos', 'Avarese', 55444777, '0000-00-00', '221-25-2525', '', '', '', '', 1, '', '', ''),
(13, 'Sandra', 'Capel', 24321456, '0000-00-00', '', '', '', '', '', 1, '', '', ''),
(15, 'Nora', 'Carabajal', 21555777, '0000-00-00', '', '', '', '', '', 1, '', '', ''),
(209, 'Javier', 'De Leon', 55882, '0000-00-00', '', '', '', '', '', 1, '', '', ''),
(211, 'Javier', 'DELETE FROM alumnos', 558821, '0000-00-00', '', '', '', '', '', 1, '', '', ''),
(212, 'Javier', 'DELETE FROM alumnos', 5588226, '0000-00-00', '', '', '', '', '', 1, '', '', ''),
(213, 'Franco', 'Colapinto', 22334, '0000-00-00', '', '', '', '', '', 1, '', '', ''),
(214, 'Gabriel', 'Batistuta', 28012345, '0000-00-00', '1122', '', '', '', '', 1, '', '', ''),
(215, 'Mercedes', 'Sosa', 12345678, '0000-00-00', '45454', '', '', '', '', 1, '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactos`
--

CREATE TABLE `contactos` (
  `id_contacto_alumno` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `dni` int(11) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `correo` varchar(70) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `localidad` varchar(50) NOT NULL,
  `cp` varchar(8) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `entidad_id` int(11) NOT NULL,
  `parentesco` varchar(30) NOT NULL,
  `observaciones` text NOT NULL,
  `tipo` enum('alumno','instructor','','') NOT NULL COMMENT 'Este campo lo usamos para saber si el contacto es de alumnos o de instructores o administrativos.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contactos`
--

INSERT INTO `contactos` (`id_contacto_alumno`, `nombre`, `apellido`, `dni`, `telefono`, `correo`, `direccion`, `localidad`, `cp`, `activo`, `entidad_id`, `parentesco`, `observaciones`, `tipo`) VALUES
(1, 'Elsa', 'Gimenez', 555, '11', 'c@c.com', 'su casa', 'Ezpeleta', '1880', 1, 1, 'Madre', '', 'alumno');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL,
  `codigo` mediumint(8) UNSIGNED NOT NULL,
  `nombre_curso` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `id_turno` int(3) NOT NULL,
  `cupo` tinyint(3) UNSIGNED NOT NULL,
  `id_instructor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id_curso`, `codigo`, `nombre_curso`, `descripcion`, `activo`, `id_turno`, `cupo`, `id_instructor`) VALUES
(1, 540, 'Electricidad domiciliaria', 'Introducción a instalaciones eléctricas residenciales, normas de seguridad, cableado, tableros y mediciones.', 1, 1, 20, 1),
(3, 220, 'Reparación de PC', 'Armado, diagnóstico de hardware, instalación de sistemas operativos, soluciones a problemas frecuentes.', 1, 2, 20, 1),
(11, 321, 'Panadero', 'Curso de panadería', 1, 1, 20, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id_horario` int(11) NOT NULL COMMENT 'Identificador del horario',
  `id_curso` int(11) NOT NULL COMMENT 'FK a cursos.id:curso',
  `dia_semana` enum('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado') NOT NULL COMMENT 'Ej: Lunes, Martes',
  `hora_inicio` time NOT NULL COMMENT 'Ej: 09:00:00',
  `hora_fin` time NOT NULL COMMENT 'Ej 11:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horarios`
--

INSERT INTO `horarios` (`id_horario`, `id_curso`, `dia_semana`, `hora_inicio`, `hora_fin`) VALUES
(1, 1, 'Lunes', '09:00:00', '12:00:00'),
(2, 1, 'Martes', '09:00:00', '12:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `id_inscripcion` int(11) NOT NULL COMMENT 'Identificador único de inscripción',
  `id_alumno` int(11) NOT NULL COMMENT 'FK a alumnos.id_alumno',
  `id_curso` int(11) NOT NULL COMMENT 'FK a cursos.id_curso',
  `fecha_inscripcion` date DEFAULT NULL COMMENT 'Opcional',
  `observaciones` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inscripciones`
--

INSERT INTO `inscripciones` (`id_inscripcion`, `id_alumno`, `id_curso`, `fecha_inscripcion`, `observaciones`) VALUES
(2, 5, 1, NULL, ''),
(3, 5, 3, NULL, ''),
(6, 3, 3, '2025-10-07', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instructores`
--

CREATE TABLE `instructores` (
  `id_instructor` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `dni` int(11) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `correo` varchar(70) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `localidad` varchar(50) NOT NULL,
  `cp` varchar(8) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `vehiculo` varchar(50) NOT NULL,
  `patente` varchar(10) NOT NULL,
  `observaciones` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `instructores`
--

INSERT INTO `instructores` (`id_instructor`, `nombre`, `apellido`, `dni`, `telefono`, `fecha_nacimiento`, `correo`, `direccion`, `localidad`, `cp`, `activo`, `vehiculo`, `patente`, `observaciones`) VALUES
(1, 'Pablo', 'Di Zoccolo', 99999999, '11-9999-5555', '1975-01-12', 'pablo@x.com', 'mi casa', 'quilmes', '1878', 1, '', '', ''),
(2, 'Juan', 'Perez', 40111222, '', '0000-00-00', '', '', '', '', 1, '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE `turnos` (
  `id_turno` int(11) NOT NULL,
  `descripcion` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `turnos`
--

INSERT INTO `turnos` (`id_turno`, `descripcion`) VALUES
(1, 'Mañana'),
(2, 'Tarde'),
(3, 'Noche');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `contrasenia` varchar(50) NOT NULL,
  `rol` tinyint(3) UNSIGNED NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `contrasenia`, `rol`, `activo`) VALUES
(1, 'gabriel', 'gabygabygaby', 0, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id_alumno`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indices de la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD PRIMARY KEY (`id_contacto_alumno`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD KEY `id_alumno` (`entidad_id`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`),
  ADD KEY `id_instructor` (`id_instructor`),
  ADD KEY `id_turno` (`id_turno`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id_horario`),
  ADD KEY `id_curso` (`id_curso`);

--
-- Indices de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`id_inscripcion`),
  ADD KEY `id_alumno` (`id_alumno`,`id_curso`),
  ADD KEY `id_curso` (`id_curso`);

--
-- Indices de la tabla `instructores`
--
ALTER TABLE `instructores`
  ADD PRIMARY KEY (`id_instructor`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`id_turno`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id_alumno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=234;

--
-- AUTO_INCREMENT de la tabla `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id_contacto_alumno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del horario', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de inscripción', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `instructores`
--
ALTER TABLE `instructores`
  MODIFY `id_instructor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id_turno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD CONSTRAINT `contactos_ibfk_1` FOREIGN KEY (`entidad_id`) REFERENCES `alumnos` (`id_alumno`);

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`id_instructor`) REFERENCES `instructores` (`id_instructor`),
  ADD CONSTRAINT `cursos_ibfk_2` FOREIGN KEY (`id_turno`) REFERENCES `turnos` (`id_turno`);

--
-- Filtros para la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD CONSTRAINT `inscripciones_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`),
  ADD CONSTRAINT `inscripciones_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
