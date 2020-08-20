-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 10.2.1.123
-- Tiempo de generación: 19-08-2020 a las 23:01:43
-- Versión del servidor: 10.4.13-MariaDB
-- Versión de PHP: 7.2.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u494946663_spparh`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificate`
--

DROP TABLE IF EXISTS `certificate`;
CREATE TABLE `certificate` (
  `id_certificate` int(11) NOT NULL,
  `sign_id_sign` int(11) NOT NULL,
  `id_document` int(11) NOT NULL,
  `node_hash` varchar(256) DEFAULT NULL,
  `route_signed_file` text NOT NULL,
  `edited_date` datetime DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contract`
--

DROP TABLE IF EXISTS `contract`;
CREATE TABLE `contract` (
  `id_contract` int(11) NOT NULL,
  `id_service` int(11) NOT NULL,
  `id_payment` int(11) NOT NULL,
  `num_request` int(11) NOT NULL,
  `init_date` datetime NOT NULL,
  `due_date` datetime NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `document`
--

DROP TABLE IF EXISTS `document`;
CREATE TABLE `document` (
  `id_document` int(11) NOT NULL,
  `id_institute` int(11) NOT NULL,
  `id_student` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `notes` text NOT NULL,
  `route_file` text NOT NULL,
  `emited_date` datetime NOT NULL,
  `id_document_before` int(11) DEFAULT NULL,
  `edited_date` datetime DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `institute`
--

DROP TABLE IF EXISTS `institute`;
CREATE TABLE `institute` (
  `id_user` int(11) NOT NULL,
  `RUC` varchar(21) NOT NULL,
  `legal_name` varchar(256) NOT NULL,
  `comercial_name` varchar(256) NOT NULL,
  `address` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `node_user`
--

DROP TABLE IF EXISTS `node_user`;
CREATE TABLE `node_user` (
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE `payment` (
  `id_payment` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `due_date` datetime NOT NULL,
  `amount` double NOT NULL,
  `igv` double NOT NULL,
  `total_amount` double NOT NULL,
  `status` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `id_name` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_level` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `service`
--

DROP TABLE IF EXISTS `service`;
CREATE TABLE `service` (
  `id_service` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL,
  `id_user_creator` int(11) NOT NULL,
  `id_user_editor` int(11) DEFAULT NULL,
  `edited_date` datetime DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sign`
--

DROP TABLE IF EXISTS `sign`;
CREATE TABLE `sign` (
  `id_sign` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `route_key_public` text NOT NULL,
  `route_key_private` text NOT NULL,
  `due_date` datetime NOT NULL,
  `edited_date` datetime DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE `student` (
  `id_user` int(11) NOT NULL,
  `birthdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id_contract` int(11) NOT NULL,
  `id_document` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `lastname` varchar(256) NOT NULL,
  `dni` varchar(8) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `id_role` int(11) NOT NULL,
  `edited_date` datetime DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `certificate`
--
ALTER TABLE `certificate`
  ADD PRIMARY KEY (`id_certificate`),
  ADD KEY `Certificate_Document_FK` (`id_document`),
  ADD KEY `Certificate_sign_FK` (`sign_id_sign`);

--
-- Indices de la tabla `contract`
--
ALTER TABLE `contract`
  ADD PRIMARY KEY (`id_contract`),
  ADD KEY `Contract_Payment_FK` (`id_payment`),
  ADD KEY `Contract_Service_FK` (`id_service`);

--
-- Indices de la tabla `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id_document`),
  ADD KEY `Document_Document_FK` (`id_document_before`),
  ADD KEY `Document_Institute_FK` (`id_institute`),
  ADD KEY `Document_Student_FK` (`id_student`);

--
-- Indices de la tabla `institute`
--
ALTER TABLE `institute`
  ADD PRIMARY KEY (`id_user`);

--
-- Indices de la tabla `node_user`
--
ALTER TABLE `node_user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indices de la tabla `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id_payment`),
  ADD KEY `Payment_User_FK` (`id_user`);

--
-- Indices de la tabla `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indices de la tabla `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id_service`),
  ADD KEY `Service_User_FK` (`id_user_creator`),
  ADD KEY `Service_User_FKv1` (`id_user_editor`);

--
-- Indices de la tabla `sign`
--
ALTER TABLE `sign`
  ADD PRIMARY KEY (`id_sign`),
  ADD KEY `sign_Institute_FK` (`id_user`);

--
-- Indices de la tabla `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id_user`);

--
-- Indices de la tabla `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id_contract`,`id_document`),
  ADD KEY `Transactions_Document_FK` (`id_document`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `User_Role_FK` (`id_role`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `certificate`
--
ALTER TABLE `certificate`
  MODIFY `id_certificate` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contract`
--
ALTER TABLE `contract`
  MODIFY `id_contract` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `document`
--
ALTER TABLE `document`
  MODIFY `id_document` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `payment`
--
ALTER TABLE `payment`
  MODIFY `id_payment` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `service`
--
ALTER TABLE `service`
  MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sign`
--
ALTER TABLE `sign`
  MODIFY `id_sign` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `certificate`
--
ALTER TABLE `certificate`
  ADD CONSTRAINT `Certificate_Document_FK` FOREIGN KEY (`id_document`) REFERENCES `document` (`id_document`),
  ADD CONSTRAINT `Certificate_sign_FK` FOREIGN KEY (`sign_id_sign`) REFERENCES `sign` (`id_sign`);

--
-- Filtros para la tabla `contract`
--
ALTER TABLE `contract`
  ADD CONSTRAINT `Contract_Payment_FK` FOREIGN KEY (`id_payment`) REFERENCES `payment` (`id_payment`),
  ADD CONSTRAINT `Contract_Service_FK` FOREIGN KEY (`id_service`) REFERENCES `service` (`id_service`);

--
-- Filtros para la tabla `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `Document_Document_FK` FOREIGN KEY (`id_document_before`) REFERENCES `document` (`id_document`),
  ADD CONSTRAINT `Document_Institute_FK` FOREIGN KEY (`id_institute`) REFERENCES `institute` (`id_user`),
  ADD CONSTRAINT `Document_Student_FK` FOREIGN KEY (`id_student`) REFERENCES `student` (`id_user`);

--
-- Filtros para la tabla `institute`
--
ALTER TABLE `institute`
  ADD CONSTRAINT `Institute_User_FK` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Filtros para la tabla `node_user`
--
ALTER TABLE `node_user`
  ADD CONSTRAINT `Node_user_User_FK` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Filtros para la tabla `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `Payment_User_FK` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Filtros para la tabla `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `Service_User_FK` FOREIGN KEY (`id_user_creator`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `Service_User_FKv1` FOREIGN KEY (`id_user_editor`) REFERENCES `user` (`id_user`);

--
-- Filtros para la tabla `sign`
--
ALTER TABLE `sign`
  ADD CONSTRAINT `sign_Institute_FK` FOREIGN KEY (`id_user`) REFERENCES `institute` (`id_user`);

--
-- Filtros para la tabla `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `Student_User_FK` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Filtros para la tabla `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `Transactions_Contract_FK` FOREIGN KEY (`id_contract`) REFERENCES `contract` (`id_contract`),
  ADD CONSTRAINT `Transactions_Document_FK` FOREIGN KEY (`id_document`) REFERENCES `document` (`id_document`);

--
-- Filtros para la tabla `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
