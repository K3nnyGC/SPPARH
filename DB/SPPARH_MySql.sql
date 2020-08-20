-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 18-08-2020 a las 09:47:23
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `prueba`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificate`
--

DROP TABLE IF EXISTS `certificate`;
CREATE TABLE IF NOT EXISTS `certificate` (
  `id_certificate` int(11) NOT NULL AUTO_INCREMENT,
  `sign_id_sign` int(11) NOT NULL,
  `id_document` int(11) NOT NULL,
  `node_hash` varchar(256) DEFAULT NULL,
  `route_signed_file` text NOT NULL,
  `edited_date` datetime DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_certificate`),
  KEY `Certificate_Document_FK` (`id_document`),
  KEY `Certificate_sign_FK` (`sign_id_sign`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contract`
--

DROP TABLE IF EXISTS `contract`;
CREATE TABLE IF NOT EXISTS `contract` (
  `id_contract` int(11) NOT NULL AUTO_INCREMENT,
  `id_service` int(11) NOT NULL,
  `id_payment` int(11) NOT NULL,
  `num_request` int(11) NOT NULL,
  `init_date` datetime NOT NULL,
  `due_date` datetime NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_contract`),
  KEY `Contract_Payment_FK` (`id_payment`),
  KEY `Contract_Service_FK` (`id_service`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `document`
--

DROP TABLE IF EXISTS `document`;
CREATE TABLE IF NOT EXISTS `document` (
  `id_document` int(11) NOT NULL AUTO_INCREMENT,
  `id_institute` int(11) NOT NULL,
  `id_student` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `notes` text NOT NULL,
  `route_file` text NOT NULL,
  `emited_date` datetime NOT NULL,
  `id_document_before` int(11) DEFAULT NULL,
  `edited_date` datetime DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_document`),
  KEY `Document_Document_FK` (`id_document_before`),
  KEY `Document_Institute_FK` (`id_institute`),
  KEY `Document_Student_FK` (`id_student`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `institute`
--

DROP TABLE IF EXISTS `institute`;
CREATE TABLE IF NOT EXISTS `institute` (
  `id_user` int(11) NOT NULL,
  `RUC` varchar(21) NOT NULL,
  `legal_name` varchar(256) NOT NULL,
  `comercial_name` varchar(256) NOT NULL,
  `address` varchar(256) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `node_user`
--

DROP TABLE IF EXISTS `node_user`;
CREATE TABLE IF NOT EXISTS `node_user` (
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `id_payment` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `due_date` datetime NOT NULL,
  `amount` double NOT NULL,
  `igv` double NOT NULL,
  `total_amount` double NOT NULL,
  `status` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_payment`),
  KEY `Payment_User_FK` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `service`
--

DROP TABLE IF EXISTS `service`;
CREATE TABLE IF NOT EXISTS `service` (
  `id_service` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL,
  `id_user_creator` int(11) NOT NULL,
  `id_user_editor` int(11) DEFAULT NULL,
  `edited_date` datetime DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_service`),
  KEY `Service_User_FK` (`id_user_creator`),
  KEY `Service_User_FKv1` (`id_user_editor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sign`
--

DROP TABLE IF EXISTS `sign`;
CREATE TABLE IF NOT EXISTS `sign` (
  `id_sign` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `route_key_public` text NOT NULL,
  `route_key_private` text NOT NULL,
  `due_date` datetime NOT NULL,
  `edited_date` datetime DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_sign`),
  KEY `sign_Institute_FK` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `id_user` int(11) NOT NULL,
  `birthdate` datetime NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id_contract` int(11) NOT NULL,
  `id_document` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_contract`,`id_document`),
  KEY `Transactions_Document_FK` (`id_document`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `lastname` varchar(256) NOT NULL,
  `dni` varchar(8) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `role` int(11) NOT NULL,
  `edited_date` datetime DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
