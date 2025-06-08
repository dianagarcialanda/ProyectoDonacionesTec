-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 03-06-2025 a las 07:30:30
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
-- Base de datos: `donacionestec`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `donaciones`
--

CREATE TABLE `donaciones` (
  `idDonacion` int(11) NOT NULL,
  `idUsuario` int(15) NOT NULL COMMENT 'Usuario que dona',
  `tipoDonacion` varchar(20) NOT NULL COMMENT '''monetaria'',''especie'',''propuesta''',
  `fecha` date NOT NULL COMMENT 'Fecha y hora de la donación',
  `estado` varchar(20) NOT NULL COMMENT '''pendiente'',''aprobada'',''rechazada'''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `donaciones`
--

INSERT INTO `donaciones` (`idDonacion`, `idUsuario`, `tipoDonacion`, `fecha`, `estado`) VALUES
(1, 3, 'monetaria', '2025-05-28', 'pendiente'),
(2, 1, 'propuesta', '2025-06-02', 'Pendiente'),
(4, 2, 'especie', '2025-06-03', 'pendiente'),
(5, 2, 'especie', '2025-06-03', 'pendiente'),
(6, 2, 'especie', '2025-06-03', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `donacionesespecie`
--

CREATE TABLE `donacionesespecie` (
  `idDonacion` int(15) NOT NULL COMMENT 'Relación con donaciones',
  `descripcion` varchar(50) NOT NULL COMMENT 'Descripción del bien donado',
  `cantidad` int(11) NOT NULL COMMENT 'Número de bienes',
  `estado` varchar(20) NOT NULL COMMENT '''nuevo'',''seminuevo'',''usado''',
  `foto` blob NOT NULL COMMENT 'Imagen del bien',
  `comprobante` blob NOT NULL COMMENT 'Lo generaría como evidencia falta actualizar estado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `donacionesespecie`
--

INSERT INTO `donacionesespecie` (`idDonacion`, `descripcion`, `cantidad`, `estado`, `foto`, `comprobante`) VALUES
(5, 'botes de basura', 20, 'nuevo', 0x666f746f5f313734383931363434345f313830362e706e67, ''),
(6, 'mas botes de basura para el ito', 10, 'nuevo', 0x666f746f5f313734383931363834385f363431352e706e67, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `donacionesmonetarias`
--

CREATE TABLE `donacionesmonetarias` (
  `idDonacion` int(15) NOT NULL COMMENT 'Relación con donaciones',
  `monto` decimal(10,2) NOT NULL COMMENT 'Monto donado',
  `metodoPago` int(15) NOT NULL COMMENT 'Método de pago',
  `comprobante` blob NOT NULL COMMENT 'Lo generaría como evidencia falta actualizar estado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `donacionespropuesta`
--

CREATE TABLE `donacionespropuesta` (
  `idDonacion` int(15) NOT NULL COMMENT 'Relación con donaciones',
  `descripcion` varchar(200) NOT NULL COMMENT 'Descripción del apoyo ofrecido',
  `archivo` blob NOT NULL COMMENT 'Evidencia (contrato)',
  `comprobante` blob NOT NULL COMMENT 'Lo generaría como evidencia falta actualizar estado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `donacionespropuesta`
--

INSERT INTO `donacionespropuesta` (`idDonacion`, `descripcion`, `archivo`, `comprobante`) VALUES
(2, 'Propuesta para instalar mas paneles solares en el ', 0x6172636869766f732f70616e656c65735f736f6c617265732e706466, 0x636f6d70726f62616e7465732f70616e656c65735f636f6d70726f62616e74652e706466);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `contrasena` varchar(20) NOT NULL,
  `telefono` int(10) NOT NULL,
  `direccion` varchar(50) NOT NULL,
  `fechaNac` date NOT NULL,
  `fotoPerfil` blob NOT NULL,
  `rol` varchar(20) NOT NULL,
  `idDonacion` int(15) NOT NULL COMMENT 'Relación con donación',
  `fechaRegistro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `nombre`, `correo`, `contrasena`, `telefono`, `direccion`, `fechaNac`, `fotoPerfil`, `rol`, `idDonacion`, `fechaRegistro`) VALUES
(1, 'Administrador', 'admin@donaciones.com', 'admin123', 1234567890, 'Oficina central', '1990-01-01', '', 'admin', 0, '2025-06-01 12:10:01'),
(2, 'Juan', 'juancarlos@gmail.com', 'juan123', 2147483647, 'Av. Cri Cri 742', '2000-01-01', '', 'usuario', 0, '2025-06-01 12:10:01'),
(3, 'Luis Angel', 'luisangel@gmail.com', 'luis1234', 2147483647, 'Calle Privada 127', '1990-05-12', '', 'usuario', 0, '2025-06-01 12:10:01');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `donaciones`
--
ALTER TABLE `donaciones`
  ADD PRIMARY KEY (`idDonacion`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `donaciones`
--
ALTER TABLE `donaciones`
  MODIFY `idDonacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
