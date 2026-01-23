-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-12-2025 a las 03:49:44
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
-- Base de datos: `cfl402_2025_nuevo`
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
(1, 'Lucas', 'Martinez', 40111222, '2001-03-15', '1155550001', 'lucas.martinez@email.com', 'Av. San Martin 123', 'Capital', '1001', 1, NULL, NULL, 'Principiante'),
(2, 'Sofia', 'Fernandez', 41222333, '2002-07-20', '1155550002', 'sofia.fer@email.com', 'Calle Falsa 123', 'Lanús', '1824', 1, 'Fiat Palio', 'AA123BB', 'Tiene licencia vencida'),
(3, 'Mateo', 'Lopez', 42333444, '2003-11-05', '1155550003', 'mateo.lop@email.com', 'Rivadavia 4500', 'Caballito', '1405', 1, NULL, NULL, 'Solo puede por la tarde'),
(4, 'Valentina', 'Diaz', 39444555, '1999-01-30', '1155550004', 'valen.diaz@email.com', 'Corrientes 2000', 'Centro', '1040', 1, 'Ford Ka', 'AC456DD', ''),
(5, 'Julian', 'Romero', 43555666, '2004-09-12', '1155550005', 'juli.romero@email.com', 'Mitre 500', 'Avellaneda', '1870', 1, NULL, NULL, 'Ansioso'),
(6, 'Camila', 'Sosa', 38666777, '1998-05-22', '1155550006', 'cami.sosa@email.com', 'Belgrano 300', 'Morón', '1708', 1, 'VW Gol', 'AD789EE', 'Clases de refuerzo'),
(7, 'Tomas', 'Ruiz', 44777888, '2005-02-14', '1155550007', 'tomas.ruiz@email.com', 'Alberdi 100', 'Flores', '1406', 1, NULL, NULL, 'Menor con permiso'),
(8, 'Lucia', 'Torres', 40888999, '2000-12-01', '1155550008', 'lucia.torres@email.com', 'Moreno 800', 'Quilmes', '1878', 1, NULL, NULL, ''),
(9, 'Nicolas', 'Flores', 37999000, '1995-08-18', '1155550009', 'nico.flores@email.com', 'Lavalle 450', 'Microcentro', '1047', 1, 'Peugeot 208', 'AF159GG', 'Renovación'),
(10, 'Martina', 'Gomez', 41000111, '2002-04-10', '1155550010', 'martu.gomez@email.com', 'Sarmiento 1200', 'Ramos Mejia', '1704', 1, NULL, NULL, '');

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
  `tipo` enum('alumno','instructor') NOT NULL COMMENT 'Distingue si el contacto es de alumno o instructor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contactos`
--

INSERT INTO `contactos` (`id_contacto_alumno`, `nombre`, `apellido`, `dni`, `telefono`, `correo`, `direccion`, `localidad`, `cp`, `activo`, `entidad_id`, `parentesco`, `observaciones`, `tipo`) VALUES
(1, 'Mario', 'Martinez', 18000111, '1144440001', 'mario.padre@email.com', 'Av. San Martin 123', 'Capital', '1001', 1, 1, 'Padre', 'Llamar si hay emergencia', 'alumno'),
(2, 'Juana', 'Gomez', 19000222, '1144440002', 'juana.madre@email.com', 'Av. San Martin 123', 'Capital', '1001', 1, 1, 'Madre', '', 'alumno'),
(3, 'Pablo', 'Fernandez', 35000333, '1144440003', 'pablo.hno@email.com', 'Calle Falsa 123', 'Lanús', '1824', 1, 2, 'Hermano', 'Responsable de pago', 'alumno'),
(4, 'Lucia', 'Mendez', 43000444, '1144440004', 'lucia.novia@email.com', 'Mitre 500', 'Avellaneda', '1870', 1, 5, 'Pareja', '', 'alumno'),
(5, 'Margarita', 'Pols', 20555666, '1144440005', 'marga.esposa@email.com', 'Libertador 1000', 'Nuñez', '1429', 1, 1, 'Esposa', 'Contacto de emergencia del instructor', 'instructor'),
(6, 'Kevin', 'Perez', 45000777, '1144440006', 'kevin.hijo@email.com', 'Santa Fe 3000', 'Palermo', '1425', 1, 3, 'Hijo', '', 'instructor');

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
  `id_instructor` int(11) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id_curso`, `codigo`, `nombre_curso`, `descripcion`, `activo`, `id_turno`, `cupo`, `id_instructor`, `fecha_inicio`, `fecha_fin`) VALUES
(1, 789, 'panaderia', 'hacemos pan', 1, 1, 20, 1, '2025-01-01', '2026-01-01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id_horario` int(11) NOT NULL COMMENT 'Identificador del horario',
  `id_curso` int(11) NOT NULL COMMENT 'FK a cursos',
  `dia_semana` enum('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado') NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `id_inscripcion` int(11) NOT NULL,
  `id_alumno` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `fecha_inscripcion` date DEFAULT NULL,
  `observaciones` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'Carlos', 'Bianchi', 20111222, '1166660001', '1975-06-15', 'carlos.b@autoescuela.com', 'Libertador 1000', 'Nuñez', '1429', 1, 'Toyota Etios', 'AE111AA', 'Jefe de instructores'),
(2, 'Marta', 'Rodriguez', 22333444, '1166660002', '1980-03-22', 'marta.r@autoescuela.com', 'Cabildo 2500', 'Belgrano', '1428', 1, 'Fiat Cronos', 'AF222BB', 'Especialista en estacionamiento'),
(3, 'Jorge', 'Perez', 25444555, '1166660003', '1985-09-10', 'jorge.p@autoescuela.com', 'Santa Fe 3000', 'Palermo', '1425', 1, 'VW Gol Trend', 'AD333CC', 'Turno tarde'),
(4, 'Silvia', 'Luna', 28555666, '1166660004', '1990-12-05', 'silvia.l@autoescuela.com', 'Callao 500', 'Recoleta', '1022', 1, 'Chevrolet Onix', 'AG444DD', 'Turno mañana'),
(5, 'Ricardo', 'Darin', 18666777, '1166660005', '1970-01-20', 'ricardo.d@autoescuela.com', 'San Juan 1500', 'Constitución', '1148', 1, 'Renault Logan', 'AC555EE', ''),
(6, 'Ana', 'Morales', 30777888, '1166660006', '1992-07-14', 'ana.m@autoescuela.com', 'Directorio 200', 'Caballito', '1424', 1, 'Ford Ka', 'AE666FF', ''),
(7, 'Pedro', 'Alvarez', 24888999, '1166660007', '1982-11-30', 'pedro.a@autoescuela.com', 'Gaona 1100', 'Flores', '1416', 1, 'Fiat Mobi', 'AF777GG', 'Instructor de manejo defensivo'),
(8, 'Elena', 'Vazquez', 27999000, '1166660008', '1988-05-05', 'elena.v@autoescuela.com', 'Nazca 500', 'Villa del Parque', '1417', 1, 'Nissan March', 'AD888HH', ''),
(9, 'Gustavo', 'Cerati', 21000111, '1166660009', '1978-08-11', 'gustavo.c@autoescuela.com', 'Beiro 3000', 'Devoto', '1419', 1, 'Peugeot 208', 'AG999II', 'Turno noche'),
(10, 'Laura', 'Fidalgo', 29111222, '1166660010', '1991-02-28', 'laura.f@autoescuela.com', 'Cordoba 4500', 'Villa Crespo', '1414', 1, 'Citroen C3', 'AH000JJ', '');

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
  `contrasenia` varchar(255) NOT NULL,
  `rol` tinyint(3) UNSIGNED NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `contrasenia`, `rol`, `activo`) VALUES
(1, 'superAdmin', '$2y$10$j9fEhuJof7D9pV7wBpN6N.TCPBM5kR3CnyQ0IGqV3cEaEvnOiXBle', 0, 1),
(2, 'admin', '$2y$10$jRC2Q3INg69X763l3dduJ.eNlXfqNyG1wwYfG9Gkkwf./r5mll8ri', 1, 1),
(3, 'instructor', '$2y$10$DGW0dpQQ1rwqQWWR6MK.9.XRMH2C.8WH/SVvXGq0rNTJUId7Bs6Vm', 2, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id_alumno`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD INDEX `idx_nombre_apellido` (`nombre`, `apellido`);

--
-- Indices de la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD PRIMARY KEY (`id_contacto_alumno`),
  ADD UNIQUE KEY `unico_por_alumno` (`dni`,`entidad_id`,`tipo`),
  ADD KEY `fk_contacto_entidad` (`entidad_id`),
  ADD INDEX `idx_nombre_apellido` (`nombre`, `apellido`);

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
  ADD UNIQUE KEY `dni` (`dni`),
  ADD INDEX `idx_nombre_apellido` (`nombre`, `apellido`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`id_turno`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD INDEX `idx_nombre` (`nombre`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id_alumno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id_contacto_alumno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del horario';

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `instructores`
--
ALTER TABLE `instructores`
  MODIFY `id_instructor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id_turno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

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