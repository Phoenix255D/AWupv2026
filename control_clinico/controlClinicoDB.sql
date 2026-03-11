-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 23-02-2026 a las 09:16:49
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
-- Base de datos: `clinica`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora_acceso`
--

CREATE TABLE `bitacora_acceso` (
  `idBitacora` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `fechaAcceso` datetime NOT NULL DEFAULT current_timestamp(),
  `accionRealizada` varchar(250) NOT NULL,
  `modulo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_agenda`
--

CREATE TABLE `control_agenda` (
  `idCita` int(11) NOT NULL,
  `idPaciente` int(11) NOT NULL,
  `idMedico` int(11) NOT NULL,
  `fechaCita` date NOT NULL,
  `motivoConsulta` varchar(250) NOT NULL,
  `EstadoCita` enum('programada','cancelada','atendida','otro') NOT NULL,
  `observaciones` varchar(250) NOT NULL,
  `fechaRegistro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_medico`
--

CREATE TABLE `control_medico` (
  `idMedico` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidoPaterno` varchar(50) NOT NULL,
  `apellidoMaterno` varchar(50) NOT NULL,
  `cedulaProfesional` varchar(50) NOT NULL,
  `especialidad` int(11) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `correoElectronico` varchar(100) NOT NULL,
  `horarioAtencion` varchar(100) NOT NULL,
  `fechaIngreso` datetime NOT NULL DEFAULT current_timestamp(),
  `estatus` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_pacientes`
--

CREATE TABLE `control_pacientes` (
  `idPaciente` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidoPaterno` varchar(50) NOT NULL,
  `apellidoMaterno` varchar(50) NOT NULL,
  `CURP` varchar(18) NOT NULL,
  `fechaNacimiento` date NOT NULL,
  `sexo` char(1) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `correoElectronico` varchar(100) NOT NULL,
  `numeroDir` int(11) NOT NULL,
  `numeroInteriorDir` int(11) DEFAULT NULL,
  `calle` varchar(30) NOT NULL,
  `fracc/col` varchar(40) NOT NULL,
  `codigoPostal` int(11) NOT NULL,
  `ciudad` varchar(40) NOT NULL,
  `estado` varchar(40) NOT NULL,
  `contactoEmergencia` varchar(150) NOT NULL,
  `telefonoEmergencia` varchar(20) NOT NULL,
  `alergias` varchar(250) NOT NULL,
  `antecendentesMedicos` longtext NOT NULL,
  `fechaRegistro` datetime NOT NULL DEFAULT current_timestamp(),
  `estatus` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `control_pacientes`
--

INSERT INTO `control_pacientes` (`idPaciente`, `nombre`, `apellidoPaterno`, `apellidoMaterno`, `CURP`, `fechaNacimiento`, `sexo`, `telefono`, `correoElectronico`, `numeroDir`, `numeroInteriorDir`, `calle`, `fracc/col`, `codigoPostal`, `ciudad`, `estado`, `contactoEmergencia`, `telefonoEmergencia`, `alergias`, `antecendentesMedicos`, `fechaRegistro`, `estatus`) VALUES
(2, 'nombre', 'apellidoP', 'apellidoN', 'NONONONONONONONOAP', '2002-02-20', 'M', '8345551234', 'correo@correo.com', 1234, NULL, 'calle', 'fraccionamiento', 12345, 'ciudad', 'estado', 'Queeseso', '8345554321', 'ninguna', 'todos', '2002-02-19 00:00:00', b'1'),
(3, '[value-1]', '[value-2]', '[value-3]', '[value-4]', '2000-01-01', 'F', '[value-7]', '[value-8]', 1234, 124, 'value-11', 'value12', 12345, '[value-14]', 'value15', '[value-16]', '[value-17]', '[value-18]', '[value-19]', '1999-12-31 00:00:00', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `idEspecialidad` int(11) NOT NULL,
  `nombreEspecialidad` varchar(100) NOT NULL,
  `descripcion` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expediente_clinico`
--

CREATE TABLE `expediente_clinico` (
  `idExpediente` int(11) NOT NULL,
  `idPaciente` int(11) NOT NULL,
  `idMedico` int(11) NOT NULL,
  `fechaConsulta` datetime NOT NULL,
  `sintomas` longtext NOT NULL,
  `diagnostico` longtext NOT NULL,
  `tratamiento` longtext NOT NULL,
  `recetaMedica` longtext NOT NULL,
  `notasAdicionales` longtext NOT NULL,
  `proximaCita` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gestor_pagos`
--

CREATE TABLE `gestor_pagos` (
  `idPago` int(11) NOT NULL,
  `idCita` int(11) NOT NULL,
  `idPaciente` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodoPago` enum('Efectivo','Tarjeta','Transferencia','') NOT NULL,
  `fechaPago` datetime NOT NULL DEFAULT current_timestamp(),
  `referencia` varchar(100) NOT NULL,
  `estatusPago` enum('Pagado','Pendiente','Cancelado','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gestor_tarifas`
--

CREATE TABLE `gestor_tarifas` (
  `idTarifa` int(11) NOT NULL,
  `descripcionServicio` varchar(150) NOT NULL,
  `costoBase` decimal(10,2) NOT NULL,
  `idEspecialidad` int(11) DEFAULT NULL,
  `estatus` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `ifReporte` int(11) NOT NULL,
  `tipoReporte` varchar(50) NOT NULL,
  `idPaciente` int(11) DEFAULT NULL,
  `idMedico` int(11) DEFAULT NULL,
  `fechaGeneracion` datetime NOT NULL DEFAULT current_timestamp(),
  `rutaArchivo` varchar(250) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `generadoPor` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuario` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contraseña` varchar(200) NOT NULL,
  `rol` enum('Admin','Medico','Recepcionista','') NOT NULL,
  `idMedico` int(11) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL,
  `ultimoAcceso` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `usuario`, `contraseña`, `rol`, `idMedico`, `activo`, `ultimoAcceso`) VALUES
(1, 'Diego', '$2y$10$wCojNW14i7d.uO0wQLMFteOnOaQIfLi0RHia6W6Lxl53qojJB6wIy', 'Admin', NULL, 1, '2026-02-22 00:00:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bitacora_acceso`
--
ALTER TABLE `bitacora_acceso`
  ADD PRIMARY KEY (`idBitacora`);

--
-- Indices de la tabla `control_agenda`
--
ALTER TABLE `control_agenda`
  ADD PRIMARY KEY (`idCita`),
  ADD KEY `idPaciente` (`idPaciente`,`idMedico`),
  ADD KEY `cAgenda-cMedico` (`idMedico`);

--
-- Indices de la tabla `control_medico`
--
ALTER TABLE `control_medico`
  ADD PRIMARY KEY (`idMedico`),
  ADD KEY `medico-especialidad` (`especialidad`);

--
-- Indices de la tabla `control_pacientes`
--
ALTER TABLE `control_pacientes`
  ADD PRIMARY KEY (`idPaciente`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`idEspecialidad`);

--
-- Indices de la tabla `expediente_clinico`
--
ALTER TABLE `expediente_clinico`
  ADD PRIMARY KEY (`idExpediente`),
  ADD KEY `idPaciente` (`idPaciente`,`idMedico`),
  ADD KEY `expediente-cMedico` (`idMedico`);

--
-- Indices de la tabla `gestor_pagos`
--
ALTER TABLE `gestor_pagos`
  ADD PRIMARY KEY (`idPago`),
  ADD KEY `idCita` (`idCita`,`idPaciente`);

--
-- Indices de la tabla `gestor_tarifas`
--
ALTER TABLE `gestor_tarifas`
  ADD PRIMARY KEY (`idTarifa`),
  ADD KEY `tarifa-especialidad` (`idEspecialidad`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`ifReporte`),
  ADD KEY `idPaciente` (`idPaciente`,`idMedico`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`),
  ADD KEY `idMedico` (`idMedico`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bitacora_acceso`
--
ALTER TABLE `bitacora_acceso`
  MODIFY `idBitacora` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `control_agenda`
--
ALTER TABLE `control_agenda`
  MODIFY `idCita` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `control_medico`
--
ALTER TABLE `control_medico`
  MODIFY `idMedico` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `control_pacientes`
--
ALTER TABLE `control_pacientes`
  MODIFY `idPaciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `idEspecialidad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `expediente_clinico`
--
ALTER TABLE `expediente_clinico`
  MODIFY `idExpediente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gestor_pagos`
--
ALTER TABLE `gestor_pagos`
  MODIFY `idPago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gestor_tarifas`
--
ALTER TABLE `gestor_tarifas`
  MODIFY `idTarifa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reportes`
--
ALTER TABLE `reportes`
  MODIFY `ifReporte` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `control_agenda`
--
ALTER TABLE `control_agenda`
  ADD CONSTRAINT `cAgenda-cMedico` FOREIGN KEY (`idMedico`) REFERENCES `control_medico` (`idMedico`),
  ADD CONSTRAINT `cAgenda-cPaciente` FOREIGN KEY (`idPaciente`) REFERENCES `control_pacientes` (`idPaciente`);

--
-- Filtros para la tabla `control_medico`
--
ALTER TABLE `control_medico`
  ADD CONSTRAINT `medico-especialidad` FOREIGN KEY (`especialidad`) REFERENCES `especialidades` (`idEspecialidad`);

--
-- Filtros para la tabla `expediente_clinico`
--
ALTER TABLE `expediente_clinico`
  ADD CONSTRAINT `expediente-cMedico` FOREIGN KEY (`idMedico`) REFERENCES `control_medico` (`idMedico`),
  ADD CONSTRAINT `expediente-cPaciente` FOREIGN KEY (`idPaciente`) REFERENCES `control_pacientes` (`idPaciente`);

--
-- Filtros para la tabla `gestor_tarifas`
--
ALTER TABLE `gestor_tarifas`
  ADD CONSTRAINT `tarifa-especialidad` FOREIGN KEY (`idEspecialidad`) REFERENCES `especialidades` (`idEspecialidad`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuario-medico` FOREIGN KEY (`idMedico`) REFERENCES `control_medico` (`idMedico`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
