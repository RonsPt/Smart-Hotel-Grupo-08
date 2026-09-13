-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 22-07-2026 a las 12:26:56
-- Versión del servidor: 11.4.12-MariaDB-cll-lve
-- Versión de PHP: 8.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `soluciones_hotelero`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accessory`
--

CREATE TABLE `accessory` (
  `id_accessory` int(11) NOT NULL,
  `sku_accessory` varchar(20) NOT NULL,
  `accessory_description` varchar(250) NOT NULL,
  `accessory_price` decimal(10,2) NOT NULL,
  `accessory_stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `accessory`
--

INSERT INTO `accessory` (`id_accessory`, `sku_accessory`, `accessory_description`, `accessory_price`, `accessory_stock`) VALUES
(1, '', 'Funda para teléfono', 5.00, 20),
(2, '', 'Correa para reloj', 3.00, 10),
(3, '', 'Llavero', 2.00, 15),
(4, '', 'Monedero', 7.00, 12),
(5, '', 'Gafas de sol', 10.00, 14),
(6, '', 'Mochila', 20.00, 10),
(7, '', 'Papael Higienico', 2.00, 20),
(8, '', 'Papael Higienico', 30.00, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `billingpersale`
--

CREATE TABLE `billingpersale` (
  `id` bigint(20) NOT NULL,
  `operation_type` varchar(30) DEFAULT NULL,
  `campus_id` bigint(20) DEFAULT NULL,
  `clients_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `voucher_type` int(11) DEFAULT NULL,
  `series` varchar(5) DEFAULT NULL,
  `correlative` varchar(8) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `issue_time` time DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `currency` varchar(10) DEFAULT NULL,
  `payment_method` int(11) DEFAULT NULL,
  `installments` int(11) DEFAULT NULL,
  `installment_amount` decimal(10,2) DEFAULT NULL,
  `payment_medium` varchar(10) DEFAULT NULL,
  `taxable_operations` decimal(10,2) DEFAULT NULL,
  `free_operations` decimal(10,2) DEFAULT NULL,
  `exempt_operations` decimal(10,2) DEFAULT NULL,
  `unaffected_operations` decimal(10,2) DEFAULT NULL,
  `igv` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `leyend` varchar(255) DEFAULT NULL,
  `retention` decimal(10,2) DEFAULT NULL,
  `retention_percentage` decimal(5,2) DEFAULT NULL,
  `retention_amount` decimal(10,2) DEFAULT NULL,
  `detraction` decimal(10,2) DEFAULT NULL,
  `detraction_percentage` decimal(5,2) DEFAULT NULL,
  `detraction_amount` decimal(10,2) DEFAULT NULL,
  `net_amount_pending_payment` decimal(10,2) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `response` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `billingpersale`
--

INSERT INTO `billingpersale` (`id`, `operation_type`, `campus_id`, `clients_id`, `user_id`, `voucher_type`, `series`, `correlative`, `issue_date`, `issue_time`, `due_date`, `currency`, `payment_method`, `installments`, `installment_amount`, `payment_medium`, `taxable_operations`, `free_operations`, `exempt_operations`, `unaffected_operations`, `igv`, `total_amount`, `leyend`, `retention`, `retention_percentage`, `retention_amount`, `detraction`, `detraction_percentage`, `detraction_amount`, `net_amount_pending_payment`, `status`, `response`) VALUES
(1, '0101', NULL, 1, 2, 2, 'B001', '00000001', '2024-10-21', '10:07:24', '2024-10-21', 'PEN', 1, NULL, NULL, '1', 305.08, 0.00, 0.00, 0.00, 54.92, 360.00, 'TRESCIENTOS SESENTA Y 00/100 SOLES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `billingpersale_detail`
--

CREATE TABLE `billingpersale_detail` (
  `id` bigint(20) NOT NULL,
  `sale_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `item` int(11) DEFAULT NULL,
  `unit_type` varchar(10) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `serie` varchar(100) DEFAULT NULL,
  `tax_percentage` decimal(5,2) DEFAULT NULL,
  `Type_taxation` varchar(10) DEFAULT NULL,
  `tax_amount` decimal(10,2) DEFAULT NULL,
  `tax_affectation_type` varchar(10) DEFAULT NULL,
  `unit_value` decimal(10,2) DEFAULT NULL,
  `free_unit_value` decimal(10,2) DEFAULT NULL,
  `item_unit_price` decimal(10,2) DEFAULT NULL,
  `sale_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `billingpersale_detail`
--

INSERT INTO `billingpersale_detail` (`id`, `sale_id`, `product_id`, `quantity`, `item`, `unit_type`, `code`, `description`, `serie`, `tax_percentage`, `Type_taxation`, `tax_amount`, `tax_affectation_type`, `unit_value`, `free_unit_value`, `item_unit_price`, `sale_date`) VALUES
(1, 1, 14, 3, 1, 'NIU', '01', 'Mouse Redragon 011', '978123456789712345', 18.00, 'IGV', 54.92, '10', 101.69, 0.00, 305.08, '2024-10-21'),
(2, 2, 14, 1, 1, 'NIU', '01', 'Mouse Redragon 011', '', 0.00, 'EXO', 0.00, '20', 101.69, 0.00, 101.69, '2024-10-21'),
(3, 2, 14, 1, 2, 'NIU', '01', 'Mouse Redragon 011', '', 0.00, 'INA', 0.00, '30', 101.69, 0.00, 101.69, '2024-10-21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE `caja` (
  `id` int(11) NOT NULL,
  `fecha_hora_apertura` datetime NOT NULL,
  `id_user` int(11) NOT NULL,
  `monto_inicial` decimal(10,2) NOT NULL DEFAULT 0.00,
  `ingresos` decimal(10,2) NOT NULL DEFAULT 0.00,
  `egresos` decimal(10,2) NOT NULL DEFAULT 0.00,
  `observaciones` text DEFAULT NULL,
  `comprobante` varchar(255) DEFAULT NULL,
  `estado` enum('Abierta','Cerrada') NOT NULL DEFAULT 'Abierta',
  `fecha_hora_cierre` datetime DEFAULT NULL,
  `monto_final` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `caja`
--

INSERT INTO `caja` (`id`, `fecha_hora_apertura`, `id_user`, `monto_inicial`, `ingresos`, `egresos`, `observaciones`, `comprobante`, `estado`, `fecha_hora_cierre`, `monto_final`) VALUES
(4, '2025-05-19 19:05:00', 1, 100.00, 500.00, 150.00, 'Sencillo', NULL, 'Cerrada', '2025-05-20 20:09:25', 450.00),
(5, '2025-05-22 21:31:00', 1, 100.00, 150.00, 30.00, 'notes', NULL, 'Cerrada', '2025-05-22 23:41:49', 120.00),
(7, '2025-05-22 00:07:00', 15, 500.00, 118.00, 30.00, 'asfafsa', NULL, 'Cerrada', '2025-05-23 01:19:39', 588.00),
(8, '2025-05-23 01:21:00', 13, 200.00, 298.00, 98.00, 'hf', NULL, 'Cerrada', '2025-05-23 01:23:24', 400.00),
(10, '2026-04-10 11:48:00', 1, 50.00, 0.00, 0.00, 'uy', NULL, 'Cerrada', '2026-04-10 11:50:59', 50.00),
(11, '2026-07-04 18:44:00', 1, 50.00, 0.00, 0.00, NULL, NULL, 'Abierta', NULL, 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campus`
--

CREATE TABLE `campus` (
  `id` int(11) NOT NULL,
  `description` varchar(45) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `campus`
--

INSERT INTO `campus` (`id`, `description`, `status`) VALUES
(1, 'CHANCAY', 1),
(2, 'HUARAL', 1),
(3, 'LIMA', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrier`
--

CREATE TABLE `carrier` (
  `id` int(11) NOT NULL,
  `id_document_type` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `document_number` varchar(20) NOT NULL,
  `address` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `business_name` varchar(256) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `carrier`
--

INSERT INTO `carrier` (`id`, `id_document_type`, `name`, `document_number`, `address`, `phone`, `business_name`, `email`, `status`) VALUES
(1, 2, 'ALEXANDER ANGEL', '75232411451', 'Asoc Santa Rosa MZ E3 Lote 9', '933430561', 'ANGEL ASOC 5050', 'alexanderdiaz78@gmail.com', 1),
(2, 1, 'ROSANGELA ', '75418596', 'Asoc Santa Rosa Lote 15 A4', '974852142', 'ROSANGELA ASOC 7890', 'rosangelahuanilo74@gmail.com', 1),
(3, 1, 'ALEXANDER ANGEL DIAZ GRANADOS', '78456252', 'Chancay 748', '526415748', 'ALEXANDER', 'Angel_1574858@hotmail.com', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `description` varchar(120) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `categories`
--

INSERT INTO `categories` (`id`, `description`, `status`) VALUES
(2, 'SNACKS', 1),
(3, 'GASEOSAS', 1),
(4, 'BEBIDAS', 1),
(5, 'AGUA', 1),
(6, 'SHAMPOO', 1),
(7, 'LICORES', 1),
(8, 'ACCESORIOS', 1),
(9, 'COMIDA', 1),
(11, 'DESAYUNO', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coin`
--

CREATE TABLE `coin` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `description` varchar(50) NOT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `coin`
--

INSERT INTO `coin` (`id`, `code`, `description`, `status`) VALUES
(1, 'PEN', 'Nuevo Sol', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `business_name` varchar(200) DEFAULT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `ruc` varchar(45) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `district` varchar(45) DEFAULT NULL,
  `province` varchar(45) DEFAULT NULL,
  `department` varchar(45) DEFAULT NULL,
  `postal_code` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `web` varchar(80) DEFAULT NULL,
  `logo` varchar(256) DEFAULT NULL,
  `country` varchar(45) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `address2` varchar(200) DEFAULT NULL,
  `industry` varchar(500) DEFAULT NULL,
  `ubigeo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `company`
--

INSERT INTO `company` (`id`, `business_name`, `company_name`, `ruc`, `address`, `district`, `province`, `department`, `postal_code`, `phone`, `email`, `web`, `logo`, `country`, `start_date`, `address2`, `industry`, `ubigeo`) VALUES
(1, 'Wilder Florentino Julca Broncano', 'Soluciones Integrales JB SAC', '10410697551', 'Calle Lopez de Zuñiga Nº 547 Piso 2', 'Chancay', 'Huaral', 'Lima', '15131', '996 720 630', 'ventas@solucionesintegralesjb.com', 'www.solucionesintegralesjb.com', 'https://solucionesintegralesjb.com/demo/facturacion/public/app-assets/images/logo/1_673cb3f1dc653.png', 'Perú', '2020-02-15', 'Calle Lopez de Zuñiga Nº 547 - Chancay', 'Ejecución, integración y desarrollo de proyectos. Instalación y mantenimiento de cámaras y equipos de tecnologías en seguridad. Instalación y mantenimiento eléctrico. Soporte técnico en general. sublimación en general. Venta de equipos informáticos, redes, accesorios y materiales eléctricos.', '150605');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `company_corporate`
--

CREATE TABLE `company_corporate` (
  `id` int(11) NOT NULL,
  `company_name` varchar(150) NOT NULL,
  `ruc` char(11) NOT NULL,
  `business_name` varchar(150) NOT NULL DEFAULT '',
  `contact_name` varchar(100) NOT NULL DEFAULT '',
  `contact_email` varchar(50) NOT NULL DEFAULT '',
  `contact_phone` varchar(45) NOT NULL DEFAULT '',
  `commercial_conditions` text DEFAULT NULL,
  `corporate_tariff` decimal(10,2) NOT NULL DEFAULT 0.00,
  `credit_limit` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `company_corporate`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `company_guest`
--

CREATE TABLE `company_guest` (
  `id_company` int(11) NOT NULL,
  `id_person` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `company_guest`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `content_headers`
--

CREATE TABLE `content_headers` (
  `id` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `id_header` int(11) NOT NULL,
  `content` varchar(250) DEFAULT NULL,
  `position` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `content_headers`
--

INSERT INTO `content_headers` (`id`, `id_product`, `id_header`, `content`, `position`) VALUES
(5, 14, 1, 'Caracteristicas', 1),
(6, 14, 2, 'Caracteristicas', 1),
(7, 14, 1, '1', 2),
(8, 14, 2, '222', 2),
(9, 14, 1, '2', 3),
(10, 14, 2, '222', 3),
(11, 14, 1, '3', 4),
(12, 14, 2, '22', 4),
(13, 14, 1, '4', 5),
(14, 14, 2, '22', 5),
(15, 15, 1, NULL, NULL),
(16, 15, 2, NULL, NULL),
(17, 16, 1, NULL, NULL),
(18, 16, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `creditnote`
--

CREATE TABLE `creditnote` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL DEFAULT 0,
  `id_products` int(11) NOT NULL DEFAULT 0,
  `id_sale` int(11) NOT NULL DEFAULT 0,
  `amount` int(11) NOT NULL DEFAULT 0,
  `price_sale` decimal(11,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(11,2) NOT NULL DEFAULT 0.00,
  `correction_description` varchar(50) NOT NULL DEFAULT '0',
  `series` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detail_income`
--

CREATE TABLE `detail_income` (
  `id` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `id_income` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `purchase_price` decimal(11,2) NOT NULL,
  `sale_price` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `development_process`
--

CREATE TABLE `development_process` (
  `id_development_process` int(11) NOT NULL,
  `development_id` int(11) DEFAULT NULL,
  `AN_start_date` date DEFAULT NULL,
  `AN_end_date` date DEFAULT NULL,
  `AN_status` varchar(20) DEFAULT NULL,
  `AN_comment` varchar(200) DEFAULT NULL,
  `DI_start_date` date DEFAULT NULL,
  `DI_end_date` date DEFAULT NULL,
  `DI_status` varchar(20) DEFAULT NULL,
  `DI_comment` varchar(200) DEFAULT NULL,
  `DE_start_date` date DEFAULT NULL,
  `DE_end_date` date DEFAULT NULL,
  `DE_status` varchar(20) DEFAULT NULL,
  `DE_comment` varchar(200) DEFAULT NULL,
  `IM_start_date` date DEFAULT NULL,
  `IM_end_date` date DEFAULT NULL,
  `IM_status` varchar(20) DEFAULT NULL,
  `IM_comment` varchar(200) DEFAULT NULL,
  `MAN_start_date` date DEFAULT NULL,
  `MAN_end_date` date DEFAULT NULL,
  `MAN_status` varchar(20) DEFAULT NULL,
  `MAN_comment` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `document_type`
--

CREATE TABLE `document_type` (
  `id` int(11) NOT NULL,
  `description` varchar(45) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `document_type`
--

INSERT INTO `document_type` (`id`, `description`, `status`) VALUES
(1, 'DNI', 1),
(2, 'RUC', 1),
(3, 'CE', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `flats`
--

CREATE TABLE `flats` (
  `id` int(11) NOT NULL,
  `description` varchar(120) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `flats`
--

INSERT INTO `flats` (`id`, `description`, `status`) VALUES
(2, 'PISO 2', 1),
(3, 'PISO 3', 1),
(4, 'PISO 4', 1),
(9, 'PISO 5', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guest`
--

CREATE TABLE `guest` (
  `id_guest` int(11) NOT NULL,
  `document_type` varchar(50) NOT NULL,
  `document_number` varchar(50) NOT NULL,
  `first_names` varchar(50) NOT NULL,
  `last_names` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `guest`
--

INSERT INTO `guest` (`id_guest`, `document_type`, `document_number`, `first_names`, `last_names`, `address`, `company_name`) VALUES
(1, 'DNI', '12345678', 'Juan', 'Perez', 'Calle 123', ''),
(2, 'DNI', '77707469', 'ANDRES ALEJANDRO', 'HUAMANI VILLAGOMEZ', 'Jiron Tablao', ''),
(3, 'DNI', '76389289', 'VERONICA', 'YANQUE CAHUANTICO', 'Avenida Bolivar', ''),
(4, 'RUC', '20100047218', '', '', 'CAL. CENTENARIO NRO. 156 URB. LAS LADERAS DE MELGAREJO LIMA LIMA LA MOLINA', 'BANCO DE CREDITO DEL PERU'),
(5, 'DNI', '76535554', 'ALEX CHRISTOPHER', 'CORREA GONZALES', 'MIRAFLORES', ''),
(6, 'RUC', '20100053455', '', '', 'AV. CARLOS VILLARAN NRO. 140 URB. SANTA CATALINA LIMA LIMA LA VICTORIA', 'BANCO INTERNACIONAL DEL PERU-INTERBANK'),
(7, 'DNI', '33562458', 'TADEO', 'REQUEJO CARRERO', 'LIMA', ''),
(8, 'RUC', '20263322496', '', '', 'CAL. LUIS GALVANI NRO. 493 URB. LOTIZACION INDUSTRIAL SAN LIMA LIMA ATE', 'NESTLE PERU S A'),
(9, 'DNI', 'TG7390535762', 'Alejo', 'Telegram', 'Sin dirección', ''),
(10, 'DNI', 'TG7390535762', 'Alejo', 'Telegram', 'Sin dirección', ''),
(11, 'DNI', '73589630', 'Franko', 'Villanueva Amasifuen', 'AV SURCO', ''),
(12, 'DNI', '77777780', 'TONIO', 'CALDERON VILLA', 'AV SURCO 322', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `headers`
--

CREATE TABLE `headers` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `headers`
--

INSERT INTO `headers` (`id`, `name`) VALUES
(1, 'Descripción'),
(2, 'Especificación'),
(3, 'Caracteristicas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `igv`
--

CREATE TABLE `igv` (
  `id` int(11) NOT NULL,
  `value` decimal(5,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `igv`
--

INSERT INTO `igv` (`id`, `value`, `status`) VALUES
(1, 18.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `income`
--

CREATE TABLE `income` (
  `id` int(11) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `proof_date` datetime NOT NULL,
  `due_date` datetime NOT NULL,
  `id_voucher_type` int(11) NOT NULL,
  `id_payment_type` int(11) NOT NULL,
  `proof_series` varchar(7) DEFAULT NULL,
  `voucher_series` varchar(10) NOT NULL,
  `igv` decimal(4,2) NOT NULL,
  `full_purchase` decimal(11,2) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `income`
--

INSERT INTO `income` (`id`, `id_supplier`, `id_user`, `proof_date`, `due_date`, `id_voucher_type`, `id_payment_type`, `proof_series`, `voucher_series`, `igv`, `full_purchase`, `status`) VALUES
(1, 1, 13, '2023-03-30 21:43:58', '2023-04-30 21:43:58', 1, 1, 'B001', '00000001', 0.18, 100.50, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `income_accessory`
--

CREATE TABLE `income_accessory` (
  `id` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_voucher_type` int(11) NOT NULL,
  `id_payment_type` int(11) NOT NULL,
  `proof_series` varchar(50) NOT NULL,
  `voucher_series` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `igv` decimal(5,2) NOT NULL,
  `number_installment` int(11) DEFAULT NULL,
  `value_installment` decimal(11,2) DEFAULT NULL,
  `full_purchase` decimal(11,2) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `income_accessory`
--

INSERT INTO `income_accessory` (`id`, `id_client`, `id_user`, `id_voucher_type`, `id_payment_type`, `proof_series`, `voucher_series`, `date`, `igv`, `number_installment`, `value_installment`, `full_purchase`, `status`) VALUES
(1, 1, 13, 1, 1, 'B001', '00000001', '2025-01-10', 0.00, NULL, NULL, 100.50, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `income_accessory_details`
--

CREATE TABLE `income_accessory_details` (
  `id` int(11) NOT NULL,
  `id_income_accessory` int(11) NOT NULL,
  `id_accessory` int(11) NOT NULL,
  `serie` varchar(50) NOT NULL,
  `stock` int(11) NOT NULL,
  `purchase_price` decimal(11,2) NOT NULL,
  `sale_price` decimal(11,2) NOT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `income_detail`
--

CREATE TABLE `income_detail` (
  `id` int(11) NOT NULL,
  `id_products` int(11) NOT NULL,
  `id_income` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `purchase_price` decimal(11,2) NOT NULL,
  `sale_price` decimal(11,2) NOT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `income_products`
--

CREATE TABLE `income_products` (
  `id` int(11) NOT NULL,
  `id_person` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_campus` int(11) NOT NULL,
  `id_voucher_type` int(11) NOT NULL,
  `voucher_series` varchar(50) DEFAULT NULL,
  `number_serial` varchar(50) DEFAULT NULL,
  `data_time` datetime NOT NULL DEFAULT current_timestamp(),
  `tax` decimal(10,0) NOT NULL,
  `id_payment_type` int(11) NOT NULL,
  `id_payment_shape` int(11) NOT NULL,
  `purchase_total` decimal(11,2) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `income_products`
--

INSERT INTO `income_products` (`id`, `id_person`, `id_user`, `id_campus`, `id_voucher_type`, `voucher_series`, `number_serial`, `data_time`, `tax`, `id_payment_type`, `id_payment_shape`, `purchase_total`, `status`) VALUES
(1, 23, 1, 1, 8, 'VOO', '055', '2026-05-07 00:00:00', 0, 3, 2, 525.00, 1),
(3, 9, 1, 1, 2, 'LOL', '0085', '2026-05-27 00:00:00', 0, 1, 1, 15.00, 1),
(4, 3, 1, 1, 8, 'PCD', '007', '2026-06-09 00:00:00', 0, 2, 2, 8.64, 1),
(7, 3, 1, 1, 2, 'VOO', '0066', '2026-06-09 00:00:00', 0, 5, 2, 40.80, 1),
(8, 9, 1, 1, 8, 'LOL', '00654', '2026-06-10 00:00:00', 0, 4, 2, 126.50, 1),
(9, 28, 1, 1, 1, 'FOO1', '00002', '2026-06-15 00:00:00', 0, 1, 1, 43.56, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `income_products_details`
--

CREATE TABLE `income_products_details` (
  `id` int(11) NOT NULL,
  `id_income_products` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `full_purchase` decimal(11,2) NOT NULL,
  `expiration_date` date DEFAULT NULL,
  `selling_price` decimal(11,2) DEFAULT NULL,
  `subtotal` decimal(11,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `income_products_details`
--

INSERT INTO `income_products_details` (`id`, `id_income_products`, `id_product`, `quantity`, `full_purchase`, `expiration_date`, `selling_price`, `subtotal`) VALUES
(1, 1, 3, 150, 4.00, '2026-10-10', 5.50, 525.00),
(3, 3, 1, 15, 1.00, '2026-11-26', 2.00, 15.00),
(4, 4, 1, 12, 1.00, '2026-09-30', 1.50, 8.64),
(7, 7, 1, 15, 1.00, '2026-07-11', 1.50, 12.75),
(8, 7, 3, 17, 2.00, '2026-07-11', 2.50, 28.05),
(9, 8, 1, 20, 1.00, '2026-09-05', 2.00, 20.00),
(10, 8, 3, 21, 4.00, '2026-09-05', 5.00, 73.50),
(11, 8, 4, 22, 2.00, '2026-09-05', 2.00, 33.00),
(12, 9, 1, 15, 0.76, NULL, 2.00, 11.40),
(13, 9, 3, 12, 2.68, NULL, 4.00, 32.16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intent`
--

CREATE TABLE `intent` (
  `id` int(11) NOT NULL,
  `token` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `intent`
--

INSERT INTO `intent` (`id`, `token`) VALUES
(12, 'gjYSL8sm4porYSQSPo436rnlxTIqTpgfW9jgjnwtfze3caCPGAAZIHGF1n7mlWNvaA863E4TYam55/Pm+LwjiBGPnvSoTQ7QD88mYd5pM4cUpWQgJThJKHGRZL1EsNtsdpBAmg=='),
(13, 'gjYSL8sm4porYSQSPo436rnlxTIqTpgfW9jgjnwtfze3caCPGAAZIHGF1n7mlWNvaA863E4TYam55/Pm+LwjiBGPnvSoTQ7QD88mYd5pM4cUpWQgJThJKHGRZL1EsNtsdpBAmg=='),
(14, 'gjYSL8sm4porYSQSPo436rnlxTIqTpgfW9jgjnwtfze3caCPGAAZIHGF1n7mlWNvaA863E4TYam55/Pm+LwjiBGPnvSoTQ7QD88mYd5pM4cUpWQgJThJKHGRZL1EsNtsdpBAmg=='),
(15, 'gjYSL8sm4porYSQSPo436rnlxTIqTpgfW9jgjnwtfze3caCPGAAZIHGF1n7mlWNvaA863E4TYam55/Pm+LwjiBGPnvSoTQ7QD88mYd5pM4cUpWQgJThJKHGRZL1EsNtsdpBAmg=='),
(16, 'EFj4ncJXv2k7BoTw6rZUJ7Qto8w/U2stpqCNZY0boNeX8Q7/noplT8/at/4a55wyFySCmYyf5cN0rDX3c+p9u28OyYSJeeJsyvg7fgbo+3IihvmAWidiivGDJGYoJMywbhIZdA=='),
(17, 'RXy0/jSAd5JczrdApFzVgMPPoN9ZJ7RR1JdvuG5bKZ3443zRi8vjrEqYRwkikqQ2fU7BKe3H3A3IACnGLt97aVeRUDl9VL/3hqUx7HSR+EtlAj6HGCZ6TKjLL6bm3GiNuI8MIQ=='),
(18, 'RXy0/jSAd5JczrdApFzVgMPPoN9ZJ7RR1JdvuG5bKZ3443zRi8vjrEqYRwkikqQ2fU7BKe3H3A3IACnGLt97aVeRUDl9VL/3hqUx7HSR+EtlAj6HGCZ6TKjLL6bm3GiNuI8MIQ=='),
(19, 'VYtGUGue1OCp8/5QBhcIi3ShJbk85/YmVbk3iENr8rIseReWUKYmyZ9BPSmQJflxXaZlVIg62LFTcneW9aJBMVZT6srkr+wXoTGA0pzbPKXKZOPIF+U2dwTS6JX3RDysc12VjA=='),
(20, '3ckmMmMWPQfjL1f5lUk3P+kf38KcpJca/H8FExPCtPDZ6qvN/JaaZAMP/yevdj6Kglp/jhDZhTnnjOs88mh6FM8au67U+FLaEFtG5Jktwhs9e0rjGrfCbbLWnhojZWb53P1/Jg=='),
(21, 'ao4rLnLR32VGiEXTRJnRDvUa0/YeDi30TSIcPdbYLAdF8SS54edHQXF3yx6rCs3XBfuHr4C04kmqU9XJd5Ya5YlMZSdQDgCZTykvcHIrHGC+QrXzHtu8YLeshLb3W5pmQW5avw=='),
(22, '4M2cNr72yhLkmPpw+xXJt82moY7QeBgsAWNznGMkjnIbP3LrxA8OFdi3itOI9y38HC0rsQrgxKnE43AKUpVTTnRM/yUME4sFTUVKX/iWYvsYdkqcfh8P662f+Apoj0/chlz3Og=='),
(23, '4LDRDYaAaQu9fMJdJdpk43GN4uDk4tNzN6RZEhYdSJqSXlCSuDNPZA+wqVY5RVTw+qNyOVA+YbNjZrkXDMINumRg1st8sftzpcQvvp57tbDD3077aHoHnOP2CJ+78V8795lA1g=='),
(24, 'qQ82xrb5o3w/NUv8+4xU3QLIFSXYmLoFuXE4B8CQGn5vlKZRYBJaVRLyM6go8SAdHb0bSD6w/gARnwrZINKjOwYHjqpb5gTRDYxSsV1gxnzTknpZP2DT7G139Qbvi0uNpXY+6Q=='),
(25, 'wwDgSp8/w8HlzFp4ixnFeGaa3QjTF8WqFCDzMjLIMDyDinMVMTjDcmBK7WLGJ1fBtsBsQh4MFZs8YWD1w9IwpWYck99EKXOeHzyZaOqvWzaAvoNO6IiO/Exl5evaBVFqZr2uFw=='),
(26, 'vmPuFTwgdHAcR54wS9/Goo8pse63dXIBiJsW1EYaKuK2FBHGrV6HZG4pyOPZpooyuhwExlF256fpgQA9O0jWvfazf04VrRTk+//IfXO4v7sk0wqxhdl9hmQBQYo095JMCRyUXA=='),
(27, 'JdJdygjLOIbVjFg93+AaR4DdFoOgOzp1WI7UI0fECYCcxee+3vC+rHpQki4hgHhAGQC7BU/3EhYuMkPNoBbuynjNIs4vWQT+vu83fNPl+za0xP+4qHPRhQfa4+HG8KLyX4rhDQ=='),
(28, 'JdJdygjLOIbVjFg93+AaR4DdFoOgOzp1WI7UI0fECYCcxee+3vC+rHpQki4hgHhAGQC7BU/3EhYuMkPNoBbuynjNIs4vWQT+vu83fNPl+za0xP+4qHPRhQfa4+HG8KLyX4rhDQ=='),
(29, 'JdJdygjLOIbVjFg93+AaR4DdFoOgOzp1WI7UI0fECYCcxee+3vC+rHpQki4hgHhAGQC7BU/3EhYuMkPNoBbuynjNIs4vWQT+vu83fNPl+za0xP+4qHPRhQfa4+HG8KLyX4rhDQ=='),
(30, 'dny3DYhuE4cX88faDKbj/zD5GpT6ckZgs1zIhRhNObiRFaQaWLywK7n94lp48J8aDoKxvbhP2h5S3lvFtg6oVAYs2969pxivipecdEgE+/Dwa74ZYW7sWRMZtwJ3t9N2E8PhZw=='),
(31, 'brasEAQFOXbEYDh1VB98yhujiqk+XYiFCMJQXKarLW30UnE7yG2EMGAzFeFMxsC/mpDroNMx/L/B86Jv40GzgK/7a0m/xR9O60AcSWy4j/ba0U9qqknsexBBPsg2+29G4M4mfQ=='),
(32, 'L2kllk6Q0VL0QTBZDQL2PmGmZIWpAdkrvPESQudzKytCTKMdDeaXh3dCnxa9U3Evi5NeI6k/lWK3QuakCNo2uvW67oL4yiuUKb4d3BtY1nLDeK7ctpwSHRKTFXheCtGKU2EScA=='),
(33, 'CMuIs0DXfHTOmVt07WOg2SmVesqlt6TB10eQiY6QORXdp5eOIj9O05rfVRjrLqYfUOLM4n4iIOXmPElpfvQfGmMZLSTbKSQzP5ms70Zm+xKYAGuIzzO1Dt1pAnu3DvPMmF3hPw=='),
(34, 'CMuIs0DXfHTOmVt07WOg2SmVesqlt6TB10eQiY6QORXdp5eOIj9O05rfVRjrLqYfUOLM4n4iIOXmPElpfvQfGmMZLSTbKSQzP5ms70Zm+xKYAGuIzzO1Dt1pAnu3DvPMmF3hPw=='),
(35, 'yTJYC0YLWYYPgXyPI1oWliNEbOS/ngEp/iaqpTdiOBYtHoOzHqHvnjlZBYPLHRX2sxk4hSRTnvozwY3wLIfHXXmvoT3TXryGo3tqyUZoFOCP1HMTjcmkR9YMtMqagYNj+E2wtg=='),
(36, '8/I+RafHkUtOZd5zLst9jXUwIrTeRcTXZq/oi8gPN8ULJ+8k7P4gN3DvD8Ov+s6zRr7lsDkB7epwBu65BDvu870wnP4Dsco5DVgqN2x9WzEhDpLagd6QHFDXXyOPn0EudP9Qlw=='),
(37, 'qAtNqKFIkKUEZvdyphz7yiVUgS739UsxIdfYGtRIGimf77hJytInC4BRcgOuU6wXl7W7RGvHN3sK3nuAgezuYks8P2gwFcxLLXOzpURgGbTKnSckf7COSKeaGCP9iCtKHNQfoA=='),
(38, 'qAtNqKFIkKUEZvdyphz7yiVUgS739UsxIdfYGtRIGimf77hJytInC4BRcgOuU6wXl7W7RGvHN3sK3nuAgezuYks8P2gwFcxLLXOzpURgGbTKnSckf7COSKeaGCP9iCtKHNQfoA=='),
(39, 'S+IpMWva4SxhY02Q/Ji1nPI/5U4CUa0C2Ed2VZyvqiGENtiZ5R8RpI3QTP7oOXuy6WHTspEgBXeZ+UJje3ztmVCZNJSx/R5T2MbWX4JG2vzRM3psV7oEJy6kPueC6LZHUxVJgA=='),
(40, 'F4RYFozjjXXKkOcYeoxqJqzPCXP7FTe1hb0rOqJXLKqZFx/RkaDoeat9+H/MiPsgmWFFyn1zn4fLCmydtdwC3WHNF5cCb5s8W0HT/ZGgJMhXQli8723mQG/josKKY3pimIdQ6Q=='),
(41, 'V64jBc2nzgGGt7RgJiHGnRm0Bnb6bPIPjbmpzP0PicNadtNCW9qLzqK0rbKcEaRfpm7Q0sX6nX62PeGgje+klo1PW6AHUH+X2B7L2pwynU2HHWYAkIdwNMK4MQHYni/QSO3StA=='),
(42, '7ADZuCtpU+H/k8hsdhbKL85gDxEqBfR3UOAbNNnPiLxxiztv6SfEvzqIQdeyfXepQ58kLbGzInJmeiMSklfIbRNRWrE++NNUlUqMod0eXQ11l6b+5ZPoNxH05uTNmRd4/UMHFg=='),
(43, '5CtL6mAy4XYFXA9zQaxQalDN0N8uY7LHHsu85KgP0yyYijRFY2lOKgQj6Xs/rkt2n9DKORxfVT3HihbCTfJfPX52BreCrY0KxyGcOtq+L2i+tkrqqd1o6DyqhT05v4miSEe/5Q=='),
(44, 'OXgcQwUk+CF8AtEzf1HTRah6PlsaQkJNVkQSYG4p1BSCCpFDbVVI+/FNxHE4GTL+5eypGh1OFsH+mjxKa0grJLHc0VDq33aKNV9Pm62nkv+qYXlniV+/Jz9gplWyiBSDcUb87Q=='),
(45, 'tHsO9uxhE+guz/lZXWvGHi91a9LyWcP0uZiM37mf60zgKOfWZ/RwUMBI65CvXsDJ/pyqocpnNg41FrzQaYRegWAf51Mnmm+xK2ZQaPzajcekx/B8Mi3KVK5BkQKkh0uSapK9yA=='),
(46, 'tHsO9uxhE+guz/lZXWvGHi91a9LyWcP0uZiM37mf60zgKOfWZ/RwUMBI65CvXsDJ/pyqocpnNg41FrzQaYRegWAf51Mnmm+xK2ZQaPzajcekx/B8Mi3KVK5BkQKkh0uSapK9yA=='),
(47, 'gFHvVGlyItQCQ2otTKs7IieLpJzXhRJY+zEWNTJp86eTf9qTRVG4nd6NOzCtXoKN1hjq+6mJHiceoUybJqED4zdPRUBQn445Y/nBTUXuR1zZw4IsE8EJlC8nROEiB0WZov3EqA==');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kardex`
--

CREATE TABLE `kardex` (
  `id_kardex` int(11) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `product` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `entry` int(11) DEFAULT NULL,
  `exit` int(11) DEFAULT NULL,
  `balance` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `kardex`
--

INSERT INTO `kardex` (`id_kardex`, `code`, `product`, `category`, `entry`, `exit`, `balance`) VALUES
(1, '12345678', 'AGUA CIELO', 'BEBIDA', 12, 3, 9),
(2, '214335354', 'GALLETA RITZ', 'COMIDA', 30, 21, 9),
(3, '124578965', 'CEREAL CRUNCH', 'DESAYUNO', 50, 15, 35),
(4, '789654321', 'AGUA MINERAL', 'BEBIDA', 40, 10, 30),
(5, '654321987', 'CHOCOLATE AMARGO', 'DULCES', 25, 8, 17),
(6, '987654321', 'YOGUR NATURAL', 'LÁCTEOS', 20, 5, 15),
(7, '333333333', 'PASTA INTEGRAL', 'CEREALES', 15, 7, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kardex_accessory`
--

CREATE TABLE `kardex_accessory` (
  `id_kardex` int(11) NOT NULL,
  `id_accessory` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `product` varchar(150) NOT NULL,
  `category` varchar(100) NOT NULL,
  `entry_stock` int(11) DEFAULT 0,
  `exit_stock` int(11) DEFAULT 0,
  `balance` int(11) DEFAULT 0,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `labels`
--

CREATE TABLE `labels` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `labels`
--

INSERT INTO `labels` (`id`, `name`, `color`) VALUES
(1, 'Mouse', '#f530ab'),
(2, 'ASUS', '#05287a');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `meal`
--

CREATE TABLE `meal` (
  `id_meal` int(11) NOT NULL,
  `meal_sku` varchar(250) NOT NULL,
  `meal_name` varchar(100) NOT NULL,
  `meal_description` varchar(250) NOT NULL,
  `id_category` int(11) NOT NULL,
  `meal_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `meal`
--

INSERT INTO `meal` (`id_meal`, `meal_sku`, `meal_name`, `meal_description`, `id_category`, `meal_price`) VALUES
(1, 'ZG011AQA', 'Lomo saltado', 'Tiene papa,cebolla,tomate', 3, 10.00),
(2, 'TH045AKH', 'Cau Cau', 'tiene papa,zanahoria', 3, 8.00),
(3, 'RGM344GD', 'Ceviche', 'tiene pescado,limon,cebolla', 3, 20.00),
(4, 'KI343JFG', 'Chaufa', 'tiene arroz,sillao', 3, 7.00),
(5, 'C-0002', 'LOMO SALTADO ESPECIAL', 'PAPAS FRITAS  CON HUEVO', 8, 20.00),
(7, 'C-0003', 'ARROZ CON POLLO', '', 9, 20.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `measuring_unit`
--

CREATE TABLE `measuring_unit` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `description` varchar(50) NOT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `measuring_unit`
--

INSERT INTO `measuring_unit` (`id`, `code`, `description`, `status`) VALUES
(1, 'NIU', 'Unidades(BIENES)', 1),
(2, 'ZZ', 'Unidades(SERVICIOS)', 1),
(3, 'KGM', 'Kilogramos', 1),
(4, 'LBR', 'Libras', 1),
(5, 'GRM', 'Gramos', 1),
(6, 'LTR', 'Litros', 1),
(7, 'MMQ', 'Metros Cubicos', 1),
(8, 'MTR', 'Metros', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `description` varchar(80) NOT NULL,
  `icon` varchar(45) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id`, `description`, `icon`, `order`) VALUES
(1, 'Habitaciones', 'home', 3),
(2, 'Compras', 'shopping-bag', 2),
(3, 'Productos', 'archive', 9),
(4, 'Ventas', 'shopping-cart', 4),
(5, 'Almacen', 'package', 1),
(6, 'Caja', 'archive', 5),
(7, 'Reportes', 'clipboard', 6),
(8, 'Administración', 'sliders', 7),
(9, 'Configuraciones', 'settings', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motive_document`
--

CREATE TABLE `motive_document` (
  `id` int(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `motive_document`
--

INSERT INTO `motive_document` (`id`, `description`, `status`) VALUES
(1, 'Anulación de la operación', 1),
(2, 'Anulación por error en el RUC', 1),
(3, 'Correción por error en la descripción', 1),
(4, 'Descuento globlal', 1),
(5, 'Descuento por Item', 1),
(6, 'Devolución total', 1),
(7, 'Devolución parcial', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motive_transfer`
--

CREATE TABLE `motive_transfer` (
  `id` int(11) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `motive_transfer`
--

INSERT INTO `motive_transfer` (`id`, `description`, `status`) VALUES
(1, 'Compra', 1),
(2, 'Consignación', 1),
(3, 'Devolución', 1),
(4, 'Traslado entre almacenes', 1),
(5, 'Venta', 1),
(6, 'Venta con entrega a terceros', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notification`
--

CREATE TABLE `notification` (
  `id_notification` int(11) NOT NULL,
  `date_notification` date NOT NULL,
  `time_notification` time NOT NULL,
  `type` varchar(255) NOT NULL,
  `id_reservation` int(11) NOT NULL,
  `status_notification` varchar(50) NOT NULL,
  `sku_notification` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `notification`
--

INSERT INTO `notification` (`id_notification`, `date_notification`, `time_notification`, `type`, `id_reservation`, `status_notification`, `sku_notification`) VALUES
(5, '2025-05-26', '18:36:00', 'Finalizando', 54, 'Seen', '54Finalizando/2025-05-26/18:51:00'),
(6, '2026-03-21', '11:52:00', 'Finalizando', 64, 'Seen', '64Finalizando/2026-03-21/11:52:00'),
(7, '2026-03-21', '11:56:00', 'Finalizando', 62, 'Seen', '62Finalizando/2026-03-21/12:12:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment`
--

CREATE TABLE `payment` (
  `id_payment` int(11) NOT NULL,
  `id_reservation` int(11) NOT NULL,
  `payment_date` datetime NOT NULL DEFAULT current_timestamp(),
  `payment_room` decimal(10,2) DEFAULT NULL,
  `payment_sales` decimal(10,2) DEFAULT NULL,
  `payment_extra` decimal(10,2) DEFAULT NULL,
  `payment_discount` decimal(10,2) DEFAULT NULL,
  `pre_payment` decimal(10,2) DEFAULT NULL,
  `payment_total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `payment`
--

INSERT INTO `payment` (`id_payment`, `id_reservation`, `payment_date`, `payment_room`, `payment_sales`, `payment_extra`, `payment_discount`, `pre_payment`, `payment_total`) VALUES
(46, 49, '2025-05-19 22:39:06', 240.00, 8.00, NULL, NULL, 150.00, NULL),
(48, 51, '2025-05-19 22:39:06', 120.00, 9.50, NULL, NULL, 60.00, NULL),
(51, 54, '2025-05-19 22:39:06', 400.00, NULL, NULL, NULL, 100.00, NULL),
(52, 55, '2025-05-30 22:39:06', 240.00, 18.00, NULL, NULL, 50.00, NULL),
(56, 59, '2026-03-20 14:50:01', 80.00, NULL, NULL, NULL, 0.00, NULL),
(57, 60, '2026-03-20 15:00:12', 100.00, NULL, NULL, NULL, 0.00, NULL),
(58, 61, '2026-03-21 11:42:09', 90.00, NULL, NULL, NULL, 0.00, NULL),
(59, 62, '2026-03-21 11:44:16', 40.00, NULL, NULL, NULL, 0.00, NULL),
(60, 63, '2026-03-21 11:47:23', 50.00, NULL, NULL, NULL, 0.00, NULL),
(61, 64, '2026-03-21 11:52:09', 40.00, NULL, NULL, NULL, 0.00, NULL),
(62, 65, '2026-03-28 14:40:48', 100.00, 0.00, NULL, NULL, 0.00, NULL),
(63, 66, '2026-05-22 00:39:46', 60.00, NULL, NULL, NULL, 60.00, NULL),
(64, 67, '2026-05-22 18:47:33', 100.00, NULL, NULL, NULL, 100.00, NULL),
(66, 69, '2026-06-01 18:21:24', 50.00, NULL, NULL, NULL, 50.00, NULL),
(67, 70, '2026-06-01 18:22:33', 90.00, NULL, NULL, NULL, 80.00, NULL),
(68, 71, '2026-06-08 17:49:03', 60.00, NULL, NULL, NULL, 60.00, NULL),
(69, 72, '2026-06-08 19:19:27', 30.00, NULL, NULL, NULL, 30.00, NULL),
(70, 73, '2026-06-24 23:57:46', 60.00, 0.00, 0.00, 0.00, 0.00, 60.00),
(71, 74, '2026-06-25 00:19:50', 80.00, 0.00, 0.00, 0.00, 0.00, 80.00),
(72, 75, '2026-07-04 19:36:19', NULL, NULL, NULL, NULL, NULL, NULL),
(73, 76, '2026-07-04 19:38:27', 90.00, NULL, NULL, NULL, 50.00, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_extra`
--

CREATE TABLE `payment_extra` (
  `id_extra` int(11) NOT NULL,
  `extra_time` time NOT NULL,
  `price_extra` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `payment_extra`
--

INSERT INTO `payment_extra` (`id_extra`, `extra_time`, `price_extra`) VALUES
(1, '00:20:00', 10.00),
(2, '01:00:00', 20.00),
(3, '02:00:00', 30.00),
(4, '03:00:00', 50.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_sales_details`
--

CREATE TABLE `payment_sales_details` (
  `id` int(11) NOT NULL,
  `id_payment` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `product_sku` varchar(250) NOT NULL,
  `id_reservation` int(11) NOT NULL,
  `fecha_venta` date NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `payment_sales_details`
--

INSERT INTO `payment_sales_details` (`id`, `id_payment`, `id_product`, `product_sku`, `id_reservation`, `fecha_venta`, `product_name`, `cantidad`, `product_price`, `total_price`) VALUES
(1, 45, 1, 'ZG011AQA', 48, '2025-04-27', 'Rellenita', 1, 1.00, 0.00),
(2, 45, 2, 'TH045AKH', 48, '2025-04-27', 'Agua cielo', 1, 1.50, 0.00),
(3, 45, 3, 'RGM344GD', 48, '2025-04-27', 'Coca cola', 1, 3.50, 0.00),
(4, 45, 1, 'ZG011AQA', 48, '2025-04-27', 'Rellenita', 1, 1.00, 0.00),
(5, 45, 2, 'TH045AKH', 48, '2025-04-27', 'Agua cielo', 1, 1.50, 0.00),
(6, 45, 6, '123345', 48, '2025-04-27', 'Yougurt', 1, 4.00, 0.00),
(7, 45, 5, 'ER194RGJ', 48, '2025-04-27', 'Inka cola', 1, 3.50, 0.00),
(8, 45, 1, 'ZG011AQA', 48, '2025-04-27', 'Rellenita', 1, 1.00, 0.00),
(9, 46, 1, 'ZG011AQA', 49, '2025-04-27', 'Rellenita', 1, 1.00, 0.00),
(10, 46, 1, 'ZG011AQA', 49, '2025-05-02', 'Rellenita', 1, 1.00, 0.00),
(11, 46, 2, 'TH045AKH', 49, '2025-05-02', 'Agua cielo', 1, 1.50, 0.00),
(12, 46, 3, 'RGM344GD', 49, '2025-05-02', 'Coca cola', 1, 3.50, 0.00),
(13, 46, 1, 'ZG011AQA', 49, '2025-05-02', 'Rellenita', 1, 1.00, 0.00),
(14, 47, 1, 'ZG011AQA', 50, '2025-05-02', 'Rellenita', 1, 1.00, 0.00),
(15, 47, 2, 'TH045AKH', 50, '2025-05-02', 'Agua cielo', 1, 1.50, 0.00),
(16, 47, 3, 'RGM344GD', 50, '2025-05-02', 'Coca cola', 1, 3.50, 0.00),
(17, 47, 4, 'KI343JFG', 50, '2025-05-02', 'Ritz', 1, 1.50, 0.00),
(18, 47, 5, 'ER194RGJ', 50, '2025-05-02', 'Inka cola', 1, 3.50, 0.00),
(19, 47, 6, '123345', 50, '2025-05-02', 'Yougurt', 1, 4.00, 0.00),
(20, 47, 1, 'ZG011AQA', 50, '2025-05-02', 'Rellenita', 1, 1.00, 0.00),
(21, 48, 1, 'ZG011AQA', 51, '2025-05-11', 'Rellenita', 1, 1.00, 0.00),
(22, 48, 2, 'TH045AKH', 51, '2025-05-11', 'Agua cielo', 1, 1.50, 0.00),
(23, 48, 1, 'ZG011AQA', 51, '2025-05-11', 'Rellenita', 2, 1.00, 0.00),
(24, 48, 2, 'TH045AKH', 51, '2025-05-11', 'Agua cielo', 1, 1.50, 0.00),
(25, 48, 1, 'ZG011AQA', 51, '2025-05-19', 'Rellenita', 2, 1.00, 0.00),
(26, 48, 2, 'TH045AKH', 51, '2025-05-19', 'Agua cielo', 1, 1.50, 0.00),
(27, 52, 2, 'TH045AKH', 55, '2025-05-23', 'Agua cielo', 1, 1.50, 0.00),
(28, 52, 3, 'RGM344GD', 55, '2025-05-23', 'Coca cola', 1, 3.50, 0.00),
(29, 52, 4, 'KI343JFG', 55, '2025-05-23', 'Ritz', 1, 1.50, 0.00),
(30, 52, 5, 'ER194RGJ', 55, '2025-05-23', 'Inka cola', 1, 3.50, 0.00),
(31, 52, 6, '123345', 55, '2025-05-23', 'Yougurt', 1, 4.00, 0.00),
(32, 52, 1, 'ZG011AQA', 55, '2025-05-23', 'Rellenita', 1, 1.00, 0.00),
(33, 52, 1, 'ZG011AQA', 55, '2025-05-23', 'Rellenita', 3, 1.00, 0.00),
(34, 53, 1, 'ZG011AQA', 56, '2025-05-23', 'Rellenita', 1, 1.00, 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_shape`
--

CREATE TABLE `payment_shape` (
  `id` int(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `payment_shape`
--

INSERT INTO `payment_shape` (`id`, `description`, `status`) VALUES
(1, 'Contado', 1),
(2, 'Crédito', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_type`
--

CREATE TABLE `payment_type` (
  `id` int(11) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `payment_type`
--

INSERT INTO `payment_type` (`id`, `description`, `status`) VALUES
(1, 'Efectivo', 1),
(2, 'Depósito en cuenta', 1),
(3, 'Giro', 1),
(4, 'Transferencia', 1),
(5, 'Orden de pago', 1),
(6, 'Tarjeta de debito', 1),
(7, 'Tarjeta de crédito', 1),
(8, 'Yape', 1),
(9, 'Plin', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission`
--

CREATE TABLE `permission` (
  `id` int(11) NOT NULL,
  `id_role` int(11) NOT NULL,
  `id_sub_menu` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `permission`
--

INSERT INTO `permission` (`id`, `id_role`, `id_sub_menu`, `status`) VALUES
(1, 1, 1, 1),
(4, 1, 8, 1),
(5, 1, 9, 1),
(6, 1, 10, 1),
(7, 1, 11, 1),
(15, 1, 12, 1),
(16, 1, 13, 1),
(17, 1, 14, 1),
(18, 1, 15, 1),
(19, 1, 16, 1),
(20, 1, 17, 1),
(21, 1, 18, 1),
(22, 1, 2, 1),
(23, 1, 3, 1),
(24, 1, 4, 1),
(26, 1, 19, 1),
(27, 1, 20, 1),
(28, 1, 21, 1),
(29, 1, 22, 1),
(30, 1, 23, 1),
(31, 1, 24, 1),
(32, 1, 25, 1),
(33, 1, 26, 1),
(34, 1, 27, 1),
(35, 1, 28, 1),
(36, 1, 29, 1),
(37, 1, 30, 1),
(40, 1, 39, 1),
(41, 1, 37, 1),
(42, 1, 33, 1),
(43, 1, 35, 1),
(44, 5, 12, 1),
(45, 5, 13, 1),
(46, 5, 14, 1),
(47, 5, 15, 1),
(48, 5, 11, 1),
(49, 5, 37, 1),
(50, 5, 3, 1),
(51, 5, 4, 1),
(52, 5, 33, 1),
(53, 5, 1, 1),
(54, 5, 2, 1),
(55, 5, 8, 1),
(56, 5, 9, 1),
(57, 5, 10, 1),
(58, 5, 35, 1),
(59, 5, 16, 1),
(60, 5, 17, 1),
(61, 5, 18, 1),
(62, 5, 19, 1),
(63, 5, 20, 1),
(64, 5, 21, 1),
(65, 5, 22, 1),
(66, 5, 23, 1),
(67, 5, 24, 1),
(68, 5, 39, 1),
(69, 5, 25, 1),
(70, 5, 27, 1),
(71, 5, 28, 1),
(72, 5, 29, 1),
(73, 5, 30, 1),
(74, 5, 26, 1),
(44, 1, 40, 1),
(45, 5, 40, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `person`
--

CREATE TABLE `person` (
  `id` int(11) NOT NULL,
  `id_document_type` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `document_number` varchar(45) NOT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `birth_place` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `business_name` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `person`
--

INSERT INTO `person` (`id`, `id_document_type`, `name`, `document_number`, `nationality`, `birth_date`, `birth_place`, `address`, `phone`, `email`, `business_name`, `status`) VALUES
(2, 1, 'JOSE JEANPIERRE OLAZABAL SANCHEZ', '72326596', 'Peruana', '2004-03-18', NULL, 'Chancay', '992923544', 'olazabalsanchez5@gmail.com', 'ABC', 0),
(3, 1, 'JUAN ANTHONI OTINIANO IMBOMA', '75832762', 'xd', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(17, 1, 'RUBEN CORNELIO JULCA BRONCANO', '33343012', 'PERUANO', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(18, 1, 'LESLYE ABIGAIL DELGADO ROSAS', '75122832', NULL, NULL, NULL, '', NULL, NULL, '', 0),
(24, 1, 'GABRIEL EFRAIN HUAMANI CARHUAPOMA', '40085203', NULL, NULL, NULL, '', NULL, NULL, '', 0),
(28, 2, 'EMAPA CHANCAY S.A.C.', '20172299581', NULL, NULL, NULL, 'CAL. TENIENTE PRINGLES NRO 150', '996720630', 'wilderjulca@solucionesintegralesjb.com', 'EMAPA CHANCAY S.A.C.', 1),
(29, 1, 'STEFANY ISABEL MONTES BARBOZA', '74155120', NULL, NULL, NULL, '', NULL, NULL, '', 0),
(34, 1, 'ANGEL GABRIEL ONTON HUAMAN', '76655929', NULL, NULL, NULL, '', NULL, NULL, '', 1),
(35, 2, 'CYRYEL E.I.R.L.', '20613868853', NULL, NULL, NULL, '', NULL, NULL, 'CYRYEL E.I.R.L.', 1),
(36, 1, 'DANFER JAMIR GOMEZ VIDAL', '76463123', NULL, NULL, NULL, '', NULL, NULL, '', 1),
(37, 1, 'jose manuel ramirez', '56423897', 'español', NULL, NULL, NULL, '987456123', NULL, NULL, 1),
(38, 2, 'Soluciones Integrales JB SAC', '10410697551', NULL, NULL, NULL, 'Lopez de Zuñiga N°  547', '996720630', 'wilderjulca@solucionesintegralesjb.com', 'Soluciones Integrales JB SAC', 1),
(39, 1, 'WILDER FLORENTINO JULCA BRONCANO', '41069755', 'PERUANO', NULL, NULL, '', '996720630', 'wilderjulca@solucionesintegralesjb.com', NULL, 1),
(40, 1, '', '40085203', NULL, NULL, NULL, '', '', '', 'GABRIEL EFRAIN HUAMANI CARHUAPOMA', 0),
(41, 2, '', '20613868853', NULL, NULL, NULL, 'CAL. BELEN MZA. N LOTE 9 URB. RESIDENCIAL CHANCAY', '', '', 'CYRYEL E.I.R.L.', 1),
(42, 1, '', '40085203', NULL, NULL, NULL, '', '', '', 'GABRIEL EFRAIN HUAMANI CARHUAPOMA', 0),
(44, 1, 'ERICK SEBASTIAN TAFUR DIAZ', '77270132', NULL, NULL, NULL, '', NULL, NULL, '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `practicantes`
--

CREATE TABLE `practicantes` (
  `idpracticante` int(11) NOT NULL,
  `nombres_apellidos` varchar(100) NOT NULL,
  `dni` varchar(8) NOT NULL,
  `institucion` varchar(30) NOT NULL,
  `sede` varchar(30) NOT NULL,
  `especialidad` varchar(50) NOT NULL,
  `modalidad` varchar(15) NOT NULL,
  `correo` varchar(40) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_termino` date NOT NULL,
  `estado` varchar(20) NOT NULL,
  `grupo` varchar(50) NOT NULL,
  `tarea` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `practicantes`
--

INSERT INTO `practicantes` (`idpracticante`, `nombres_apellidos`, `dni`, `institucion`, `sede`, `especialidad`, `modalidad`, `correo`, `numero`, `fecha_inicio`, `fecha_termino`, `estado`, `grupo`, `tarea`) VALUES
(152, 'Hilden Carlos Luna Pérez', '71127213', 'SENATI', 'Huaura', 'Administración', 'Presencial', 'hildencarloslunaperez08@gmail.com', '936082350', '2024-07-15', '2024-11-23', 'Activo', '', 'ADMINISTRACION'),
(153, 'David Aarón Geronimo Guillén', '70861894', 'SENATI', 'Huaura', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'davidgeronimoguillen@gmail.com', '906191070', '2024-07-17', '2024-11-16', 'Activo', 'GRUPO 1', 'Desarrollo módulo almacén sistema GLIESE'),
(154, 'Victor Enrique Valdez Pacheco', '72757455', 'SENATI', 'Huaura', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'valdezv231@gmail.com', '940168728', '2024-07-15', '2024-11-23', 'Activo', 'GRUPO 1', 'Desarrollo módulo almacén sistema GLIESE'),
(155, 'Manuel Enrique Pantoja Carlos', '72450712', 'SENATI', 'Huaura', 'Soporte Técnico y Tecnología en Seguridad, Redes &', 'Remoto', 'ec2915000@gmail.com', '900697589', '2024-07-15', '2024-11-07', 'Activo', 'GRUPO 5', 'Adaptar el sistema a modo móvil'),
(156, 'Marcos Aban Villanueva Galván', '72709284', 'SENATI', 'Huaura', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', '0666231039@unjfsc.edu.pe', '974192002', '2024-07-15', '2024-11-23', 'Activo', 'GRUPO 5', 'Adaptar el sistema a modo móvil'),
(157, 'Maycohol David Carmin Liberato', '74999944', 'SENATI', 'Huaura', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'maycoholdcarminliberato@gmail.com', '932335392', '2024-07-15', '2024-11-23', 'Activo', 'GRUPO 5', 'Adaptar el sistema a modo móvil'),
(158, 'Marcus Aurelius Herrera Quispe', '73120393', 'SENATI', 'Huacho', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'lokgg125@gmail.com', '916325144', '2024-07-22', '2024-11-29', 'Activo', 'GRUPO 2', 'Notificación de servicio: Debe enviar una alerta de notificación de la renovación o próximo servicio.'),
(160, 'Leonel Antonio Torres Malpartida', '75553676', 'SENATI', 'Huaura', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'leoneltorresmalpartida89@gmail.com', '985905267', '2024-07-15', '2024-11-23', 'Activo', 'GRUPO 1', 'Desarrollo módulo almacén sistema GLIESE'),
(161, 'Dany Jean Pierre Huancas Mio', '72179860', 'SENATI', 'Huaura', 'Administración', 'Presencial', 'huancasmiodany105@gmail.com', '933489183', '2024-07-15', '2024-11-23', 'Activo', '', 'ADMINISTRACION'),
(162, 'Richard Paul Reyes Salas', '75542636', 'SENATI', 'Huaura', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'reyessalasrichardp@gmail.com', '904797586', '2024-07-18', '2024-11-11', 'Activo', 'GRUPO 2', 'Notificación de servicio: Debe enviar una alerta de notificación de la renovación o próximo servicio.'),
(163, 'Nicole Jasmin Castromonte Nicho', '76846646', 'SENATI', 'Huaura', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'nicolejasminc@gmail.com', '904349092', '2024-07-15', '2024-11-23', 'Activo', 'GRUPO 14', 'Implementar la seguridad de Login'),
(165, 'Pierre Rodolfo Martinez Sosa', '72362302', 'SENATI', 'Huaura', 'Diseño Gráfico', 'Remoto', 'pierremartinezsosa@gmail.com', '957767546', '2024-07-16', '2024-11-23', 'Activo', 'GRUPO 10', 'Diseño de imagenes'),
(166, 'Holises Yonel Cespedes Martin', '74387718', 'SENATI', 'CFP-HUAURA', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'holisesces15@gmail.com', '940056497', '2024-07-15', '2024-11-23', 'Activo', 'GRUPO 3', 'Pruebas y mantenimiento general - Sistema SISPRO'),
(167, 'Jhesler Yoshiyuki Cochachin Cacha', '60745975', 'SENATI', 'CFP Huaura', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'jheslercochachincacha@gmail.com', '947773365', '2024-07-15', '2024-11-23', 'Activo', 'GRUPO 3', 'Pruebas y mantenimiento general - Sistema SISPRO'),
(168, 'Ricardo Arian Vera Solis', '72384452', 'SENATI', 'CFP Huaura', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'verasolis.19@gmail.com', '934049843', '2024-07-15', '2024-11-23', 'Activo', 'GRUPO 4', 'Desarrollar un scrip para construir y descargar XML y PDF'),
(169, 'Luis Alonso Nicho de los Santos', '70668954', 'SENATI', 'Huaura', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'alogameryt500@gmail.com', '973874928', '2024-07-18', '2024-11-20', 'Activo', 'GRUPO 2', 'Notificación de servicio: Debe enviar una alerta de notificación de la renovación o próximo servicio.'),
(170, 'Pedro Alexis Díaz Domínguez', '76319106', 'SENATI', 'Huaura', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'pedro.add.96@gmail.com', '940251013', '2024-07-15', '2024-11-23', 'Activo', 'GRUPO 4', 'Desarrollar un scrip para construir y descargar XML y PDF'),
(171, 'Fabricio Paolo Buitron Zuñiga', '73594060', 'SENATI', 'CFP Huara', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'fapabuzu@gmail.com', '912087309', '2024-07-17', '2024-11-23', 'Activo', 'GRUPO 4', 'Desarrollar un scrip para construir y descargar XML y PDF'),
(172, 'Diego Alexander Castillo Tocto', '75106918', 'SENATI', 'CFP Huaura', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'hiro.zero.two1234@gmail.com', '991861654', '2024-07-15', '2024-11-23', 'Activo', 'GRUPO 4', 'Desarrollar un scrip para construir y descargar XML y PDF'),
(173, 'Elvis Joel Palacios Riquelme', '76551438', 'SENATI', 'Huaura', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'elvis_20_yars@hotmail.com', '971141383', '2024-07-15', '2024-11-23', 'Activo', 'GRUPO 3', 'Pruebas y mantenimiento general - Sistema SISPRO'),
(174, 'Smith Lennon Cipiriano Claros', '73605244', 'SENATI', 'Huaura', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'cipirianosmith3@gmail.com', '967037790', '2024-07-22', '2024-11-23', 'Activo', 'GRUPO 2', 'Notificación de servicio: Debe enviar una alerta de notificación de la renovación o proximo servicio.'),
(175, 'Luis Giovanny Gonzaga Cáceres', '70303797', 'SENATI', 'CFP Huaura', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'giovannigonzagacaceres@hotmail.com', '939014980', '2024-07-15', '2024-11-23', 'Activo', 'GRUPO 3', 'Pruebas y mantenimiento general - Sistema SISPRO'),
(176, 'Deyvi Andres Trujillo Borja', '74624733', 'SENATI', 'Huaura', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'deyviandrestrujillo@gmail.com', '963055492', '2024-07-15', '2024-11-23', 'Activo', 'GRUPO 1', 'Desarrollo módulo almacén sistema GLIESE'),
(177, 'Rafael Jordan Herrera Lucero', '74398942', 'SENATI', 'Independencia', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'jordanherreralucero15@gmail.com', '74398942', '2024-07-15', '2024-11-23', 'Activo', 'GRUPO 5', 'Adaptar el sistema a modo móvil'),
(178, 'Enzo Joel Rueda Gallardo', '72942306', 'SENATI', 'Independencia (Central)', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'enzojrg0502@gmail.com', '912878556', '2024-08-19', '2024-12-06', 'Activo', 'GRUPO 8', 'Sistema Hotelero - Back-end'),
(179, 'Yhordin Armando Viera Garay', '73094851', 'SENATI', 'Independencia', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'yhordiviera@gmail.com', '918908944', '2024-08-19', '2024-12-06', 'Activo', 'GRUPO 8', 'Sistema Hotelero - Back-end'),
(181, 'CHRISTIAN YHON CAMPOS QUISPE', '76666315', 'SENATI', 'HUANCAYO', 'Soporte Técnico y Tecnología en Seguridad, Redes &', 'Remoto', 'camposq215@gmail.com', '910047318', '2024-07-15', '2024-11-23', 'Activo', 'GRUPO 7', 'Integración de Login'),
(182, 'Carlos Gabriel Suazo Vilca', '77178044', 'SENATI', 'Huancayo', 'Soporte Técnico y Tecnología en Seguridad, Redes &', 'Remoto', 'suazocarlos87@gmail.com', '933337961', '2024-07-15', '2024-11-23', 'Activo', 'GRUPO 7', 'Integración de Login'),
(183, 'Lincol Fabrisio Victorio Atanacio', '72839408', 'SENATI', 'HUAURA', 'Desarrollo de software', 'Remoto', 'victorioatanaciolincol@gmail.com', '953 091 46', '2024-08-08', '2024-11-29', 'Activo', 'GRUPO 6', 'Actualización de página del Hostal Paraíso'),
(184, 'Bilha Eunice Nacion Broncano', '75267455', 'SENATI', 'HUAURA', 'Desarrollo de software', 'Remoto', 'nacionbilha@gmail.com', '901781787', '2024-08-08', '2024-11-29', 'Activo', 'GRUPO 6', 'Actualización de página del Hostal Paraíso'),
(185, 'Miker Armando Melgarejo Cabanillas', '72723528', 'SENATI', 'HUAURA', 'Desarrollo de software', 'Remoto', 'armandomiker99@gmail.com', '924814700', '2024-08-08', '2024-11-29', 'Activo', 'GRUPO 6', 'Actualización de página del Hostal Paraíso'),
(186, 'José Alberto Bravo Espinoza', '60769894', 'SENATI', 'Central los olivos', 'Desarrollo de software', 'Remoto', 'josebravoespinoza1@gmail.com', '982848321', '2024-08-14', '2024-12-08', 'Activo', 'GRUPO 8', 'Sistema Hotelero - Back-end'),
(189, 'Juan Anthoni Otiniano Imboma', '75832762', 'SENATI', 'Independencia sede principal', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'otinianoantoni02@gmail.com', '921812289', '2024-08-21', '2024-12-14', 'Activo', 'GRUPO 8', 'Sistema Hotelero - Back-end'),
(190, 'Carlos Felipe Barreto Flores', '72427681', 'SENATI', 'Huaura', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'carlosfelipebarretoflores@gmail.com', '971131334', '2024-07-18', '2024-11-22', 'Activo', 'GRUPO 2', 'Notificación de servicio: Debe enviar una alerta de notificación de la renovación o proximo servicio.'),
(191, 'Eber Jose Suyuri Hinostroza', '75119417', 'SENATI', 'Lima Callao', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'eberjoseesuyurihinostroza@gmail.com', '965284751', '2024-08-19', '2024-12-08', 'Activo', 'GRUPO 9', 'Mejora del diseño - Sistema Hotelero - Front end'),
(192, 'Almir Wilfredo Preciado Juarez', '74805877', 'SENATI', 'INDEPENDENCIA', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'almirpreciadojuarez@gmail.com', '902893467', '2024-08-21', '2024-12-08', 'Activo', 'GRUPO 9', 'Mejora del diseño - Sistema Hotelero - Front end'),
(193, 'Joaquín Alejandro Gonzales Juarez', '72016594', 'SENATI', 'Lima-indepdencia', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'joaquin161003@gmail.com', '916031264', '2024-08-22', '2024-12-06', 'Activo', 'GRUPO 9', 'Mejora del diseño - Sistema Hotelero - Front end'),
(194, 'Jianpierre Giovanni Moron Neciosup', '75210034', 'SENATI', 'Independencia', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'jeanpierremoron0711@gmail.com', '913503284', '2024-08-21', '2024-12-08', 'Activo', 'GRUPO 9', 'Mejora del diseño - Sistema Hotelero - Front end'),
(195, 'Jaren Steven Risco Condor', '72562174', 'SENATI', 'Independencia', 'Diseño Gráfico', 'Remoto', 'riscocondorjarensteven@gmail.com', '967231565', '2024-08-19', '2024-12-06', 'Activo', 'GRUPO 10 (C)', 'Modelamiento 3D'),
(196, 'Mathias Raphael Tenemas Diaz', '72775638', 'SENATI', 'Independencia', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'mathiastenemas@gmail.com', '939266007', '2024-08-26', '2024-12-09', 'Activo', 'GRUPO 13', 'Implementar reservación en línea - Sistema del Hotel'),
(197, 'Adrian Franco Moreno Castro', '72354759', 'SENATI', 'Independencia', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'adrianmorenocastro123@gmail.com', '953238950', '2024-08-19', '2024-12-27', 'Activo', 'GRUPO 11', 'Mantenimiento de página web - Soluciones Integrales JB'),
(199, 'Chris Julio Alberto Huarisueca Sulca', '60791391', 'SENATI', 'Independencia', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'huarisuecasulca@gmail.com', '958075362', '2024-08-19', '2024-12-06', 'Activo', 'GRUPO 11', 'Mantenimiento de página web - Soluciones Integrales JB'),
(200, 'Enrique Calderon Balarezo', '73019478', 'SENATI', 'indepencia', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'topincre@gmail.com', '963637441', '2024-08-22', '2024-10-01', 'Activo', 'GRUPO 12', 'Mantenimiento - Sistema Hotelero'),
(201, 'Adixon Julca Ramírez', '71262010', 'SENATI', 'Independencia', 'Ingenieria de software con inteligencia artificial', 'Remoto', 'julcaadixon25@gmail.com', '971866040', '2024-08-22', '2024-12-05', 'Activo', 'GRUPO 11', 'Mantenimiento de página web - Soluciones Integrales JB'),
(202, 'Luis Alessandro Obispo Puchoc', '76855497', 'SENATI', 'Independencia', 'Diseño Gráfico', 'Remoto', 'opluis.09o@gmail.com', '934308471', '2024-08-19', '2024-12-28', 'Activo', 'GRUPO 10 (A)', 'Diseño de imagenes'),
(203, 'Brando Akiro Alor Baldeón', '72896121', 'SENATI', 'Independencia', 'Diseño Gráfico', 'Remoto', 'pollitocarioca@gmail.com', '940886679', '2024-08-19', '2024-12-28', 'Activo', 'GRUPO 10', 'Diseño de imagenes'),
(204, 'Emerson Leonardo Cotera Davila', '72741769', 'SENATI', 'INDEPENDENCIA', 'Diseño Gráfico', 'Remoto', 'emerson.tbvr@gmail.com', '983220063', '2024-08-22', '2024-12-28', 'Activo', 'GRUPO 10 (C)', 'Modelamiento 3D'),
(205, 'JAIR ALONSO SANTA CRUZ MIO', '73060411', 'SENATI', 'INDEPENDENCIA', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'fcbarcelona07080@gmail.com', '969890430', '2024-08-23', '2024-12-27', 'Activo', 'GRUPO 11', 'Mantenimiento de página web - Soluciones Integrales JB'),
(206, 'Alec Fuentes Renteria', '74904669', 'SENATI', 'Independencia', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'fuentesrenteria.a22@gmail.com', '953100792', '2024-08-19', '2024-12-27', 'Activo', 'GRUPO 12', 'Mantenimiento - Sistema Hotelero'),
(207, 'Ashly Jhandet Chahuayo Vasquez', '72754025', 'SENATI', 'Independencia', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'ashlychahuayovasquez@gmail.com', '902200238', '2024-08-23', '2024-12-08', 'Activo', 'GRUPO 12', 'Mantenimiento - Sistema Hotelero'),
(208, 'Elver Ronaldo Rivera Ventura', '71930265', 'SENATI', 'Independencia', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'elverriveraventura99@gmail.com', '966369525', '2024-08-23', '2024-12-08', 'Activo', 'GRUPO 12', 'Mantenimiento - Sistema Hotelero'),
(210, 'Fabrizio Alejandro del Piero Loa Colan', '75411365', 'SENATI', 'ETI', 'Diseño Gráfico', 'Remoto', 'loacolan@gmail.com', '960126885', '2024-08-19', '2024-12-06', 'Activo', 'GRUPO 10', 'Diseño de imagenes'),
(211, 'Carlos Daniel González Chilcon', '72459575', 'SENATI', 'Independencia', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'carloschilcon19@gmail.com', '962064821', '2024-08-26', '2024-12-07', 'Activo', 'GRUPO 12', 'Mantenimiento - Sistema Hotelero'),
(212, 'Gabriel Adrián Garcia Martínez', '70425499', 'SENATI', 'Independencia', 'Diseño Gráfico', 'Remoto', 'adriangarciamartinez3@gmail.com', '946099601', '2024-08-19', '2024-12-06', 'Activo', 'GRUPO 10', 'Diseño de imagenes'),
(213, 'Fabrizzio Manuel Flores Bustamante', '76360772', 'SENATI', 'Independecia', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'fabrizzio1418@gmail.com', '990251700', '2024-08-26', '2024-12-06', 'Activo', 'GRUPO 13', 'Implementar reservación en línea - Sistema del Hotel'),
(215, 'Miguel angel López de la cruz', '72184799', 'SENATI', 'Independencia', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', '1520051@senati.pe', '930238631', '2024-08-19', '2024-12-06', 'Activo', 'GRUPO 13', 'Implementar reservación en línea - Sistema del Hotel'),
(216, 'Claudio Farid Diaz Calle', '73612096', 'SENATI', 'Independencia', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'xibolofarid@gmail.com', '948308212', '2024-08-26', '2024-12-06', 'Activo', 'GRUPO 13', 'Implementar reservación en línea - Sistema del Hotel'),
(218, 'Elvis Junior Chuquipiondo Ventura', '60750457', 'SENATI', 'Independencia - Escuela Superi', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', '5rd4rkk1llx16th@gmail.com', '912770003', '2024-08-26', '2024-12-31', 'Activo', 'GRUPO 12', 'Mantenimiento - Sistema Hotelero'),
(220, 'Pascual Arafy Carrillo Nuñez', '77469664', 'SENATI', 'Independencia', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', '1421770@senati.pe', '990307850', '2024-08-19', '2024-12-27', 'Activo', 'GRUPO 14', 'Implementar la seguridad de Login'),
(221, 'Leonella Keyla Pinto Jara', '76841363', 'SENATI', 'Independencia', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', '1424088@senati.pe', '972040780', '2024-08-26', '2024-12-27', 'Activo', 'GRUPO 12', 'Mantenimiento - Sistema Hotelero'),
(222, 'Rosalinda Rumaldo Torres', '71267783', 'SENATI', 'Independiente', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'rosalindarumaldotorres@gmail.com', '942788341', '2024-08-19', '2024-12-27', 'Activo', 'GRUPO 12', 'Mantenimiento - Sistema Hotelero'),
(224, 'Daniel Victor Cebreros Bravo', '77122318', 'SENATI', 'Independencia', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'cebrerosdaniel488@gmail.com', '957668774', '2024-08-27', '2024-12-13', 'Activo', 'GRUPO 12', 'Mantenimiento - Sistema Hotelero'),
(226, 'Mateo Daniel Choy yin Silva', '75768678', 'SENATI', '28 DE JULIO', 'Diseño Gráfico', 'Remoto', 'choyyinmateo@gmail.com', '902740614', '2024-09-02', '2024-12-27', 'Activo', 'GRUPO 10 (C)', 'MO'),
(227, 'Cristhian Enrique Peñafiel Cachay', '72459230', 'SENATI', 'Independica', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'cristhianpenafiel952@gmail.com', '935428083', '2024-08-19', '2024-12-27', 'Activo', 'GRUPO 14', 'Implementar la seguridad de Login'),
(228, 'Marc Anthonny Raúl Guerra Leon', '7293768', 'SENATI', 'Luis Cáceres Graziani ', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'marcosguerra15987@gmail.com', '924329445', '2024-09-02', '2024-11-23', 'Activo', '', ''),
(229, 'Rodrigo Arturo Rodríguez Laura', '72941555', 'SENATI', 'Huaura', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'rodrigorodriguezlaura403@gmail.com', '900628644', '2024-07-15', '2024-11-09', 'Activo', 'GRUPO 15', 'Desarrollo del sistema hotelero'),
(230, 'Joaquin Jesus Alexander Caceres Vargas', '72005544', 'SENATI', 'CFP LUIS CACERES GRAZIANI', 'Diseño Gráfico', 'Remoto', '1520426@senati.pe', '981100916', '2024-09-02', '2024-12-27', 'Activo', 'GRUPO 10 (C)', 'Modelamiento 3D'),
(231, 'Andro Paolo Quispe Torres', '76095337', 'SENATI', '28 de julio', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', '1399699@senati.pe', '916412581', '2024-09-02', '2024-12-27', 'Activo', 'GRUPO 14', 'Implementar la seguridad de Login'),
(232, 'Renzo Amadeus Rengifo Briceño', '74838812', 'SENATI', 'Independencia', 'Diseño Gráfico', 'Remoto', 'koni.no.ongaku12@gmail.com', '910341900', '2024-08-19', '2024-12-27', 'Activo', 'GRUPO 10 (A)', 'Diseño de imagenes'),
(233, 'Anthony Jesus Zapata Nuñez', '62851996', 'SENATI', 'CFP LUIS CACERES GRAZIANI', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'jesus.nunez4105@gmail.com', '992904968', '2024-09-02', '2024-11-23', 'Activo', 'GRUPO 16', ''),
(234, 'Gianluca Emanuel Revilla Albarran', '77920105', 'SENATI', 'CFP Luis Cáceres Graziani', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', '1491081@senati.pe', '906586270', '2024-09-02', '2024-11-23', 'Activo', 'GRUPO 16', ''),
(235, 'Edison Joaquín Mallqui Quispe', '77020122', 'SENATI', 'Independencia', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'mallquiedison@gmail.com', '919504649', '2024-09-05', '2024-12-06', 'Activo', 'GRUPO 15', 'Desarrollo del sistema hotelero'),
(236, 'Michael Jackson Sejekam Mashian', '63377246', 'SENATI', 'Cercado de Lima', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'maicolscam19@gmail.com', '935250977', '2024-09-02', '2024-12-27', 'Activo', 'GRUPO 15', 'Desarrollo del sistema hotelero'),
(237, 'Omar Alexis Carlos Manrique', '77076770', 'SENATI', 'Senati Centro de Lima', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', '1443083@senati.pe', '902191670', '2024-09-02', '2024-12-27', 'Activo', 'GRUPO 15', 'Desarrollo del sistema hotelero'),
(238, 'Tony Cristhian Estrada Jaimes', '75212272', 'SENATI', 'Independencia', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'estradajaimestony@gmail.com', '981457492', '2024-09-05', '2024-12-08', 'Activo', 'GRUPO 12 (B)', 'Modulo Compra'),
(240, 'Lwiggie Jr Manuel Reategui Arimuya', '72643046', 'Senati', 'Huaura', 'Desarrollo de Software y Facturación Electrónica', 'Remoto', 'reateguiarimuyaj.1505@gmail.com', '947608246', '2024-09-16', '2024-11-23', 'Activo', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product`
--

CREATE TABLE `product` (
  `id_product` int(11) NOT NULL,
  `product_sku` varchar(250) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_description` varchar(250) NOT NULL,
  `id_category` int(11) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_stock` int(11) NOT NULL,
  `expiration_date` date NOT NULL,
  `status_expiration_date` int(11) NOT NULL,
  `unit_type` varchar(45) DEFAULT NULL,
  `stock_in` int(11) NOT NULL,
  `stock_out` varchar(11) NOT NULL,
  `price_sell` decimal(10,2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `product`
--

INSERT INTO `product` (`id_product`, `product_sku`, `product_name`, `product_description`, `id_category`, `product_price`, `product_stock`, `expiration_date`, `status_expiration_date`, `unit_type`, `stock_in`, `stock_out`, `price_sell`, `status`) VALUES
(1, 'BE-0001', 'AGUA DE MESA CIELO 625ML', '', 4, 0.00, 0, '2026-05-30', 1, NULL, 0, '', 0.00, 1),
(3, 'BE-0002', 'GASEOSA COCA COLA 600ML', '', 4, 3.50, 0, '2023-10-27', 1, NULL, 0, '', 0.00, 1),
(4, 'BE-0003', 'GASEOSA INKA KOLA 600ML', '', 4, 1.50, 0, '2023-10-27', 1, NULL, 0, '', 0.00, 1),
(5, 'BE-0004', 'GATORADE 500ML', ' ', 4, 3.50, 0, '2023-10-27', 1, NULL, 0, '', 0.00, 1),
(6, 'BE-0005', 'SPORRADE 500ML', ' ', 4, 4.00, 0, '2023-10-27', 1, NULL, 0, '', 0.00, 1),
(8, 'LI-0001', 'CERVEZA PILSEN LATA 473ML', '', 7, 1.00, 0, '2023-10-27', 1, NULL, 0, '', 0.00, 1),
(16, 'LI-0002', 'CERVEZA CUSQUEÑA TRIGO LATA 473ML', '', 7, 0.00, 0, '2026-06-30', 1, NULL, 0, '', 0.00, 1),
(19, 'BE-00010', 'AGUA DE MESA CIELO 120ML', '', 4, 0.00, 0, '2026-06-30', 1, NULL, 0, '', 0.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `image_url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `product_images`
--

INSERT INTO `product_images` (`id`, `id_product`, `image_url`) VALUES
(35, 14, 'http://localhost/gliese/public/app-assets/images/product/14/14_6716804738cd7.jpg'),
(36, 15, 'http://localhost/gliese/public/app-assets/images/product/15/15_671bb8d2d693e.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_inventories`
--

CREATE TABLE `product_inventories` (
  `id` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `id_section` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `id_subcategory` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `product_inventories`
--

INSERT INTO `product_inventories` (`id`, `id_product`, `id_section`, `id_category`, `id_subcategory`) VALUES
(7, 14, 1, 5, 1),
(8, 15, 1, 5, 1),
(9, 16, 1, 5, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_stock`
--

CREATE TABLE `product_stock` (
  `id` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `id_campus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `product_stock`
--

INSERT INTO `product_stock` (`id`, `id_product`, `stock`, `id_campus`) VALUES
(25, 14, 5, 4),
(26, 15, 33, 4),
(27, 16, 100, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_type_sale`
--

CREATE TABLE `product_type_sale` (
  `id` int(11) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proforma`
--

CREATE TABLE `proforma` (
  `id` int(11) NOT NULL,
  `id_clients` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_voucher_type` int(11) NOT NULL,
  `igv` decimal(5,2) NOT NULL DEFAULT 0.00,
  `igv_total` decimal(5,2) NOT NULL DEFAULT 0.00,
  `date_issue` date NOT NULL,
  `correlative` varchar(50) NOT NULL DEFAULT '',
  `reference` varchar(50) NOT NULL,
  `total_sale` decimal(5,2) NOT NULL DEFAULT 0.00,
  `delivery_time` varchar(50) NOT NULL DEFAULT '',
  `offer_validity` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `proforma`
--

INSERT INTO `proforma` (`id`, `id_clients`, `id_user`, `id_voucher_type`, `igv`, `igv_total`, `date_issue`, `correlative`, `reference`, `total_sale`, `delivery_time`, `offer_validity`, `status`) VALUES
(1, 1, 1, 9, 18.00, 5.50, '2013-11-13', '-00000001', 'Referencia', 100.00, '1 dia', 'Oferta', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proforma_detail`
--

CREATE TABLE `proforma_detail` (
  `id` int(11) NOT NULL,
  `id_products` int(11) NOT NULL,
  `id_proforma` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `series` varchar(50) NOT NULL,
  `price_sale` decimal(5,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `referralguide`
--

CREATE TABLE `referralguide` (
  `id` int(11) NOT NULL,
  `id_clients` int(11) NOT NULL,
  `id_sale` int(11) NOT NULL,
  `id_carrier` int(11) NOT NULL,
  `id_reason_transfer` int(11) NOT NULL,
  `date_issue` date NOT NULL,
  `date_transfer` date NOT NULL,
  `modality_transport` varchar(50) NOT NULL,
  `transfer_type` varchar(50) NOT NULL DEFAULT '',
  `gross_weight` int(11) NOT NULL,
  `serie_correlative` varchar(50) NOT NULL,
  `serie_correlative_guide` varchar(50) NOT NULL,
  `address_start` varchar(200) NOT NULL,
  `address_arrival` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `referralguide_detail`
--

CREATE TABLE `referralguide_detail` (
  `id` int(11) NOT NULL,
  `id_products` int(11) NOT NULL,
  `id_referralguide` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `series` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservation`
--

CREATE TABLE `reservation` (
  `id_reservation` int(11) NOT NULL,
  `checkin_date` date NOT NULL,
  `checkin_time` time NOT NULL,
  `checkout_date` date DEFAULT NULL,
  `checkout_time` time DEFAULT NULL,
  `departure_date` date DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  `id_room` int(11) NOT NULL,
  `id_person` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `down_payment` decimal(10,2) DEFAULT NULL,
  `balance_due` decimal(10,2) DEFAULT NULL,
  `total_paid` decimal(10,2) NOT NULL,
  `payment_status` varchar(100) NOT NULL,
  `status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `reservation`
--

INSERT INTO `reservation` (`id_reservation`, `checkin_date`, `checkin_time`, `checkout_date`, `checkout_time`, `departure_date`, `departure_time`, `id_room`, `id_person`, `total_amount`, `down_payment`, `balance_due`, `total_paid`, `payment_status`, `status`) VALUES
(69, '2026-06-01', '17:20:00', '2026-06-02', '17:21:00', NULL, NULL, 26, 36, 0.00, NULL, NULL, 0.00, '0', 'Ocupado'),
(70, '2026-06-02', '17:22:00', '2026-06-03', '20:22:00', NULL, NULL, 28, 34, 90.00, 50.00, 50.00, 50.00, '0', 'Reservado'),
(71, '2026-06-10', '18:48:00', '2026-06-11', '17:48:00', NULL, NULL, 27, 33, 0.00, NULL, NULL, 0.00, '', 'Pendiente'),
(72, '2026-06-10', '19:19:00', '2026-06-10', '21:19:00', NULL, NULL, 27, 39, 0.00, NULL, NULL, 0.00, '', 'Reservado'),
(73, '2026-06-24', '06:00:00', '2026-06-25', '08:30:00', NULL, NULL, 28, 11, 0.00, NULL, NULL, 0.00, '', 'Reservado'),
(74, '2026-06-25', '03:24:00', '2026-06-26', '17:23:00', NULL, NULL, 49, 12, 0.00, NULL, NULL, 0.00, '', 'Reservado'),
(75, '2026-07-11', '18:35:00', NULL, NULL, NULL, NULL, 27, 44, 0.00, NULL, NULL, 0.00, '', 'Pendiente'),
(76, '2026-07-11', '18:37:00', '2026-07-12', '19:37:00', NULL, NULL, 27, 44, 0.00, NULL, NULL, 0.00, '', 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `description` varchar(45) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `role`
--

INSERT INTO `role` (`id`, `description`, `status`) VALUES
(1, 'ADMINISTRADOR', 1),
(5, 'RECEPCIONISTA', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `room`
--

CREATE TABLE `room` (
  `id_room` int(11) NOT NULL,
  `room_number` varchar(50) DEFAULT NULL,
  `room_status` varchar(50) DEFAULT NULL,
  `id_type` int(11) DEFAULT NULL,
  `id_flat` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `room`
--

INSERT INTO `room` (`id_room`, `room_number`, `room_status`, `id_type`, `id_flat`) VALUES
(26, '302', 'Disponible', 2, 3),
(27, '201', 'Reservado', 1, 2),
(28, '202', 'Disponible', 1, 2),
(29, '205', 'Disponible', 2, 2),
(30, '204', 'Disponible', 1, 2),
(31, '301', 'Disponible', 1, 3),
(44, '203', 'Disponible', 3, 2),
(46, '303', 'Disponible', 1, 3),
(48, '304', 'Disponible', 1, 3),
(49, '305', 'Disponible', 4, 3),
(50, '401', 'Disponible', 1, 4),
(53, '402', 'Disponible', 2, 4),
(54, '403', 'Disponible', 1, 4),
(55, '404', 'Disponible', 1, 4),
(56, '405', 'Disponible', 1, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `room_type`
--

CREATE TABLE `room_type` (
  `id_type` int(11) NOT NULL,
  `type_name` varchar(50) NOT NULL,
  `person_limit` int(11) NOT NULL,
  `price_temporary` decimal(10,2) NOT NULL,
  `price_half` decimal(10,2) NOT NULL,
  `price_day` decimal(10,2) NOT NULL,
  `price_holiday` decimal(10,2) NOT NULL,
  `bed_type` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `room_type`
--

INSERT INTO `room_type` (`id_type`, `type_name`, `person_limit`, `price_temporary`, `price_half`, `price_day`, `price_holiday`, `bed_type`) VALUES
(1, 'Simple', 2, 30.00, 50.00, 60.00, 80.00, '1 cama de 2 plazas'),
(2, 'Doble', 4, 60.00, 80.00, 100.00, 120.00, '2 camas de 2 plazas'),
(3, 'Triple', 6, 80.00, 120.00, 150.00, 180.00, '2 cama 2 plazas, 1 cama plaza y media'),
(4, 'Familiar', 3, 50.00, 60.00, 80.00, 100.00, '1 cama 2 plazas, 1 cama plaza y media'),
(8, 'Matrimonial', 2, 80.00, 100.00, 120.00, 150.00, '1 cama kin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sale`
--

CREATE TABLE `sale` (
  `id` int(11) NOT NULL,
  `id_clients` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_voucher_type` int(11) NOT NULL,
  `id_coins` int(11) NOT NULL,
  `id_document_reason` int(11) NOT NULL,
  `id_payment_type` int(11) NOT NULL,
  `doc_related` int(11) NOT NULL,
  `series` int(11) DEFAULT NULL,
  `correlative` int(11) DEFAULT NULL,
  `date_issue` date DEFAULT NULL,
  `date_expiration` date DEFAULT NULL,
  `date_transfer` date DEFAULT NULL,
  `igv` decimal(5,2) NOT NULL DEFAULT 0.00,
  `igv_total` decimal(5,2) NOT NULL DEFAULT 0.00,
  `op_taxed` decimal(5,2) DEFAULT NULL,
  `op_unaffected` decimal(5,2) DEFAULT NULL,
  `op_exonerated` decimal(5,2) DEFAULT NULL,
  `op_free` decimal(5,2) DEFAULT NULL,
  `isc` decimal(5,2) DEFAULT NULL,
  `total_discount` decimal(5,2) DEFAULT NULL,
  `total_sale` decimal(5,2) NOT NULL DEFAULT 0.00,
  `legend` varchar(50) NOT NULL,
  `sustent` varchar(50) NOT NULL,
  `reference` varchar(50) NOT NULL,
  `validity` varchar(50) NOT NULL,
  `time_delivery` varchar(50) NOT NULL,
  `modality_transport` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sales_accessory`
--

CREATE TABLE `sales_accessory` (
  `id_venta` int(11) NOT NULL,
  `id_accessory` int(11) NOT NULL,
  `amount_ac` int(11) NOT NULL,
  `price_sales_ac` decimal(10,2) DEFAULT NULL,
  `id_reservation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sales_meal`
--

CREATE TABLE `sales_meal` (
  `id_venta` int(11) NOT NULL,
  `id_meal` int(11) NOT NULL,
  `amount_me` int(11) NOT NULL,
  `price_sales_me` decimal(10,2) DEFAULT NULL,
  `id_reservation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sales_product`
--

CREATE TABLE `sales_product` (
  `id_venta` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `amount_pr` int(11) NOT NULL,
  `price_sales_pr` decimal(10,2) DEFAULT NULL,
  `id_reservation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sale_detail`
--

CREATE TABLE `sale_detail` (
  `id` int(11) NOT NULL,
  `id_sale` int(11) NOT NULL,
  `id_products` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `price_sale` decimal(5,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(5,2) NOT NULL DEFAULT 0.00,
  `bestselling_date` date NOT NULL,
  `item` int(11) NOT NULL,
  `series` varchar(50) NOT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sub_menu`
--

CREATE TABLE `sub_menu` (
  `id` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `description` varchar(45) NOT NULL,
  `icon` varchar(45) DEFAULT NULL,
  `url` varchar(80) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `sub_menu`
--

INSERT INTO `sub_menu` (`id`, `id_menu`, `description`, `icon`, `url`, `order`) VALUES
(1, 1, 'Reservas', 'circle', 'Reservation', 1),
(2, 1, 'Recepción', 'circle', 'Reception', 2),
(3, 2, 'Accesorios', 'circle', 'Income_Accessory', 1),
(4, 2, 'Productos', 'circle', 'Income_Products', 2),
(8, 4, 'Registro', 'circle', 'Sales', 1),
(9, 4, 'Lista de ventas', 'circle', 'Saleslist', 2),
(10, 4, 'Facturación', 'circle', 'Billingpersale', 3),
(11, 5, 'Kardex accesorio', 'circle', 'Kardex', 5),
(12, 5, 'Accesorios', 'circle', 'Accessories', 1),
(13, 5, 'Producto', 'circle', 'Product', 2),
(14, 5, 'Comidas', 'circle', 'Meal', 3),
(15, 5, 'Categoria', 'circle', 'Categories', 4),
(16, 6, 'Apertura inicial', 'circle', 'Initial_open', 1),
(17, 6, 'Cierre de caja', 'circle', 'Closingcash', 2),
(18, 6, 'Lista de caja', 'circle', 'Cashlist', 3),
(19, 7, 'Reporte de cliente', 'circle', 'Customer_report', 1),
(20, 7, 'Reporte mensual', 'circle', 'report_monthly', 2),
(21, 7, 'Reporte de facturas', 'circle', 'report_billing', 3),
(22, 7, 'Reporte de caja', 'circle', 'report_cash', 4),
(23, 8, 'Tipo habitación', 'circle', 'RoomType', 1),
(24, 8, 'Habitaciones', 'circle', 'Rooms', 2),
(25, 8, 'Personas', 'circle', 'Clients', 4),
(26, 9, 'Datos de la Empresa', 'circle', 'Company', 5),
(27, 9, 'Usuarios', 'circle', 'Users', 1),
(28, 9, 'Roles', 'circle', 'Roles', 2),
(29, 9, 'Sedes', 'circle', 'Campus', 3),
(30, 9, 'Personalizar', 'circle', 'Personalization', 4),
(33, 2, 'Proveedores', 'circle', 'Suppliers', 3),
(35, 4, 'Nota Crédito', 'circle', '', 4),
(37, 5, 'Kardex producto', 'circle', '', 6),
(39, 8, 'Piso', 'circle', 'Flats', 3),
(40, 8, 'Empresas', 'circle', 'Companies', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sunat`
--

CREATE TABLE `sunat` (
  `id` int(11) NOT NULL,
  `sunat_endpoint` varchar(100) DEFAULT NULL,
  `cert_password` varchar(100) DEFAULT NULL,
  `certificate` varchar(100) DEFAULT NULL,
  `user` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sunat`
--

INSERT INTO `sunat` (`id`, `sunat_endpoint`, `cert_password`, `certificate`, `user`, `password`) VALUES
(1, 'FE_BETA', 's01uci0n3sInt3gr1es', 'cert_66fd827091050.p12', 'admin', 'admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `id_document_type` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `document_number` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `address` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `business_name` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `supplier`
--

INSERT INTO `supplier` (`id`, `id_document_type`, `name`, `document_number`, `address`, `phone`, `business_name`, `email`, `status`) VALUES
(1, 1, 'ALEXANDER', '71695889', 'lopez de zuñiga', '915959584', 'R&amp;J ACTION', 'generateindollars@gmail.com', 1),
(3, 1, 'JEREMI', '72003664', 'Av 1 de mayo', '936672334', 'J&amp;amp;D ACTION', 'jeregr.21042002@gmail.com12', 1),
(4, 1, 'RUBEN DARIO', '721368235', 'Chancay Lopez 04', '987975591', 'R&amp;R ACTION', 'rubendario7tu@gmail.com', 1),
(5, 1, 'JEREMI ARMANDO GONZALES RUEDA', '7', 'Av 1 de mayo', '8', 'J&amp;D ACTION ', 'jeregr.21042002@gmail.com', 1),
(6, 2, 'JUAN PEREZ', '20100047218', 'CAL. CENTENARIO NRO. 156 URB. LAS LADERAS DE MELGAREJO LIMA LIMA LA MOLINA', '8732544744216543', 'INTERNET SAC', 'a@asacgmail.com', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `task_control`
--

CREATE TABLE `task_control` (
  `id` int(11) NOT NULL,
  `entry_date` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `leader` varchar(255) DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `service_status` varchar(50) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL,
  `outstanding_balance` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `delivery_status` varchar(50) DEFAULT NULL,
  `document_number` varchar(50) DEFAULT NULL,
  `institution` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `group_members` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `task_control`
--

INSERT INTO `task_control` (`id`, `entry_date`, `start_date`, `end_date`, `leader`, `project_name`, `service_status`, `payment_status`, `outstanding_balance`, `status`, `delivery_status`, `document_number`, `institution`, `phone`, `group_members`) VALUES
(1, '2023-10-26', '2023-10-27', '2023-11-10', 'Ana Pérez', 'Desarrollo Web', 'En progreso', 'Parcial', 500.00, 'Activo', 'Pendiente', 'DOC-123', 'Empresa A', '555-1234', 'Pedro, 123, Sofía'),
(2, '2023-10-27', '2023-10-30', '2023-11-15', 'Juan Gómez', 'Diseño Gráfico', 'Completado', 'Pagado', 0.00, 'Cerrado', 'Entregado', 'DOC-456', 'Empresa B', '555-5678', 'Luis, 456, Marta'),
(3, '2023-10-28', '2023-11-01', '2023-11-20', 'María López', 'Marketing Digital', 'En espera', 'No pagado', 1000.00, 'Pendiente', 'No aplica', 'DOC-789', 'Universidad C', '555-9012', 'Elena, 789, Miguel'),
(4, '2023-10-29', '2023-11-02', '2023-11-25', 'Carlos Rodríguez', 'Soporte Técnico', 'En progreso', 'Parcial', 250.00, 'Activo', 'Pendiente', 'DOC-101', 'Hospital D', '555-3456', 'Paula, 321, Andrés'),
(5, '2023-10-30', '2023-11-03', '2023-11-30', 'Laura Martínez', 'Consultoría', 'Completado', 'Pagado', 0.00, 'Cerrado', 'Entregado', 'DOC-112', 'Gobierno E', '555-7890', 'Diego, 654, Carmen'),
(6, '2024-03-01', '2024-03-02', '2024-03-10', 'Daniel Pérez', 'Nuevo Proyecto', 'En progreso', 'Parcial', 300.00, 'Activo', 'Pendiente', 'DOC-202', 'Empresa Z', '555-2222', 'Carlos, Andrea, 789'),
(7, '2025-03-12', NULL, NULL, 'David Aarón Geronimo Guillén', 'ABC', 'En Proceso', 'Pendiente', 123.00, 'Activo', 'Pendiente', '70861894', 'Huaura', '906191070', ''),
(8, '2025-03-12', NULL, NULL, 'Victor Enrique Valdez Pacheco', 'ABCD', 'Pendiente', 'Pendiente', 1234.00, 'Activo', 'Pendiente', '72757455', 'Huaura', '940168728', 'Juan Anthoni Otiniano Imboma (75832762), Alec Fuentes Renteria (74904669)');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `token`
--

CREATE TABLE `token` (
  `id` int(11) NOT NULL,
  `token` varchar(150) DEFAULT NULL,
  `host` varchar(80) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(150) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `token`
--

INSERT INTO `token` (`id`, `token`, `host`, `email`, `password`) VALUES
(1, 'apis-token-10307.jNJ6K5RZsRvE9MKBg9ZvfHFmEg7v8nLZ', 'mail.solucionesintegralesjb.com', 'facturacion@solucionesintegralesjb.com', 'N!6zW&amp;skzDy,');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `id_role` int(11) NOT NULL,
  `id_document_type` int(11) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `document_number` varchar(45) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `telephone` varchar(45) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `user` varchar(45) NOT NULL,
  `password` text NOT NULL,
  `image_url` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `id_role`, `id_document_type`, `first_name`, `last_name`, `document_number`, `address`, `telephone`, `email`, `user`, `password`, `image_url`, `status`, `active`) VALUES
(1, 1, 2, 'Wilder', 'Julca Broncano', '41069755', 'Chancay', '913085587', 'grjere698@gmail.com', 'julca', '4f544d77595467344f54413559574d324e32566d4d7a41325957526d5a6a677a4f544530597a49774e7a673d', NULL, 1, 1),
(2, 1, 1, 'Soluciones', 'Integrales JB', '10410697551', 'Av. 1 de mayo 1031', '913085589', 'grjere698@gmail.com', 'admin', '5a6d4d35597a41334e6a4a6a5a4459784d7a51355a6a457a596d593159324d7a597a566d4d5445784e7a633d', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_campus`
--

CREATE TABLE `user_campus` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_campus` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `user_campus`
--

INSERT INTO `user_campus` (`id`, `id_user`, `id_campus`, `status`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(16, 1, 3, 1),
(17, 2, 1, 1),
(18, 2, 2, 1),
(19, 2, 3, 1),
(20, 15, 1, 1),
(21, 15, 2, 1),
(22, 15, 3, 1),
(23, 18, 1, 1),
(24, 18, 2, 1),
(25, 18, 3, 1),
(26, 19, 2, 1),
(27, 19, 1, 1),
(28, 19, 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `voucher_type`
--

CREATE TABLE `voucher_type` (
  `id` int(11) NOT NULL,
  `code` varchar(4) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `voucher_type`
--

INSERT INTO `voucher_type` (`id`, `code`, `description`, `status`) VALUES
(1, '01', 'Factura', 1),
(2, '03', 'Boleta de Venta', 1),
(3, '07', 'Nota de Credito', 1),
(4, '08', 'Nota de Debito', 1),
(5, '09', 'Guia de Remisión Remitente', 1),
(6, NULL, 'Cotización', 1),
(7, NULL, 'Orden de Pagos', 1),
(8, '12', 'Ticket', 1),
(9, NULL, 'Prestamo', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accessory`
--
ALTER TABLE `accessory`
  ADD PRIMARY KEY (`id_accessory`);

--
-- Indices de la tabla `billingpersale`
--
ALTER TABLE `billingpersale`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `billingpersale_detail`
--
ALTER TABLE `billingpersale_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`);

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `campus`
--
ALTER TABLE `campus`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `carrier`
--
ALTER TABLE `carrier`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `document_number` (`document_number`) USING BTREE,
  ADD KEY `id_document_type` (`id_document_type`) USING BTREE;

--
-- Indices de la tabla `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `coin`
--
ALTER TABLE `coin`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `company_corporate`
--
ALTER TABLE `company_corporate`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ruc_UNIQUE` (`ruc`);

--
-- Indices de la tabla `company_guest`
--
ALTER TABLE `company_guest`
  ADD PRIMARY KEY (`id_company`,`id_person`),
  ADD KEY `FK_COMPANY_GUEST_PERSON` (`id_person`);

--
-- Indices de la tabla `content_headers`
--
ALTER TABLE `content_headers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_product` (`id_product`),
  ADD KEY `id_header` (`id_header`);

--
-- Indices de la tabla `creditnote`
--
ALTER TABLE `creditnote`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_products` (`id_products`),
  ADD KEY `id_venta` (`id_sale`);

--
-- Indices de la tabla `detail_income`
--
ALTER TABLE `detail_income`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_product` (`id_product`);

--
-- Indices de la tabla `development_process`
--
ALTER TABLE `development_process`
  ADD PRIMARY KEY (`id_development_process`),
  ADD KEY `development_id` (`development_id`);

--
-- Indices de la tabla `document_type`
--
ALTER TABLE `document_type`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `flats`
--
ALTER TABLE `flats`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `guest`
--
ALTER TABLE `guest`
  ADD PRIMARY KEY (`id_guest`);

--
-- Indices de la tabla `headers`
--
ALTER TABLE `headers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `igv`
--
ALTER TABLE `igv`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `income`
--
ALTER TABLE `income`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_supplier` (`id_supplier`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_voucher_type` (`id_voucher_type`),
  ADD KEY `id_payment_type` (`id_payment_type`);

--
-- Indices de la tabla `income_accessory`
--
ALTER TABLE `income_accessory`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `income_accessory_details`
--
ALTER TABLE `income_accessory_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_income_accessory` (`id_income_accessory`);

--
-- Indices de la tabla `income_detail`
--
ALTER TABLE `income_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_income` (`id_income`),
  ADD KEY `id_product` (`id_products`) USING BTREE;

--
-- Indices de la tabla `income_products`
--
ALTER TABLE `income_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_SALES_CLIENT` (`id_person`),
  ADD KEY `FK_SALES_VOUCHER_TYPE` (`id_voucher_type`),
  ADD KEY `FK_SALES_PAYMENT_TYPE` (`id_payment_type`),
  ADD KEY `FK_SALES_PAYMENT_SHAPE` (`id_payment_shape`);

--
-- Indices de la tabla `income_products_details`
--
ALTER TABLE `income_products_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_INCOME_PRODUCT_FOREIGN` (`id_income_products`),
  ADD KEY `FK_PRODUCT_FOREIGN` (`id_product`);

--
-- Indices de la tabla `intent`
--
ALTER TABLE `intent`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `kardex`
--
ALTER TABLE `kardex`
  ADD PRIMARY KEY (`id_kardex`);

--
-- Indices de la tabla `kardex_accessory`
--
ALTER TABLE `kardex_accessory`
  ADD PRIMARY KEY (`id_kardex`),
  ADD KEY `fk_kardex_accessory` (`id_accessory`);

--
-- Indices de la tabla `labels`
--
ALTER TABLE `labels`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `meal`
--
ALTER TABLE `meal`
  ADD PRIMARY KEY (`id_meal`),
  ADD UNIQUE KEY `meal_sku` (`meal_sku`),
  ADD KEY `id_meal` (`id_category`);

--
-- Indices de la tabla `measuring_unit`
--
ALTER TABLE `measuring_unit`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `motive_document`
--
ALTER TABLE `motive_document`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `motive_transfer`
--
ALTER TABLE `motive_transfer`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id_notification`),
  ADD KEY `id_reservation` (`id_reservation`);

--
-- Indices de la tabla `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id_payment`),
  ADD KEY `id_reservation` (`id_reservation`);

--
-- Indices de la tabla `payment_extra`
--
ALTER TABLE `payment_extra`
  ADD PRIMARY KEY (`id_extra`);

--
-- Indices de la tabla `payment_sales_details`
--
ALTER TABLE `payment_sales_details`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `payment_shape`
--
ALTER TABLE `payment_shape`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `payment_type`
--
ALTER TABLE `payment_type`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_PERMISSION_ROLE` (`id_role`),
  ADD KEY `FK_PERMISSION_SUB_MENU` (`id_sub_menu`);

--
-- Indices de la tabla `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_type` (`id_document_type`) USING BTREE;

--
-- Indices de la tabla `practicantes`
--
ALTER TABLE `practicantes`
  ADD PRIMARY KEY (`idpracticante`);

--
-- Indices de la tabla `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id_product`),
  ADD UNIQUE KEY `product_sku` (`product_sku`),
  ADD KEY `id_category` (`id_category`);

--
-- Indices de la tabla `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_product` (`id_product`);

--
-- Indices de la tabla `product_inventories`
--
ALTER TABLE `product_inventories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_product` (`id_product`),
  ADD KEY `id_section` (`id_section`),
  ADD KEY `id_category` (`id_category`),
  ADD KEY `id_subcategory` (`id_subcategory`);

--
-- Indices de la tabla `product_stock`
--
ALTER TABLE `product_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_product` (`id_product`),
  ADD KEY `id_campus` (`id_campus`);

--
-- Indices de la tabla `product_type_sale`
--
ALTER TABLE `product_type_sale`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proforma`
--
ALTER TABLE `proforma`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_clients` (`id_clients`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_voucher_type` (`id_voucher_type`);

--
-- Indices de la tabla `proforma_detail`
--
ALTER TABLE `proforma_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_products` (`id_products`),
  ADD KEY `id_proforma` (`id_proforma`);

--
-- Indices de la tabla `referralguide`
--
ALTER TABLE `referralguide`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_clients` (`id_clients`),
  ADD KEY `id_carrier` (`id_carrier`),
  ADD KEY `id_sale` (`id_sale`),
  ADD KEY `id_transfer_type` (`id_reason_transfer`) USING BTREE;

--
-- Indices de la tabla `referralguide_detail`
--
ALTER TABLE `referralguide_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_products` (`id_products`),
  ADD KEY `id_referralguide` (`id_referralguide`);

--
-- Indices de la tabla `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `id_room` (`id_room`),
  ADD KEY `fk_reservation_person` (`id_person`);

--
-- Indices de la tabla `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id_room`),
  ADD UNIQUE KEY `room_number` (`room_number`),
  ADD KEY `id_type` (`id_type`),
  ADD KEY `fk_room_flat` (`id_flat`);

--
-- Indices de la tabla `room_type`
--
ALTER TABLE `room_type`
  ADD PRIMARY KEY (`id_type`);

--
-- Indices de la tabla `sales_accessory`
--
ALTER TABLE `sales_accessory`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_accessory` (`id_accessory`),
  ADD KEY `id_reservation` (`id_reservation`);

--
-- Indices de la tabla `sales_meal`
--
ALTER TABLE `sales_meal`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_meal` (`id_meal`),
  ADD KEY `id_reservation` (`id_reservation`);

--
-- Indices de la tabla `sales_product`
--
ALTER TABLE `sales_product`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_product` (`id_product`),
  ADD KEY `id_reservation` (`id_reservation`);

--
-- Indices de la tabla `sale_detail`
--
ALTER TABLE `sale_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_sale` (`id_sale`),
  ADD KEY `id_products` (`id_products`);

--
-- Indices de la tabla `sub_menu`
--
ALTER TABLE `sub_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_SUB_MENU_MENU` (`id_menu`);

--
-- Indices de la tabla `sunat`
--
ALTER TABLE `sunat`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `document_number` (`document_number`),
  ADD KEY `id_document_type` (`id_document_type`);

--
-- Indices de la tabla `task_control`
--
ALTER TABLE `task_control`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `token`
--
ALTER TABLE `token`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_UNIQUE` (`user`),
  ADD UNIQUE KEY `document_number_UNIQUE` (`document_number`),
  ADD KEY `FK_USER_ROLE` (`id_role`),
  ADD KEY `FK_USER_DOCUMENT_TYPE` (`id_document_type`);

--
-- Indices de la tabla `user_campus`
--
ALTER TABLE `user_campus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_USER_CAMPUS_CAMPUS` (`id_campus`),
  ADD KEY `FK_USER_CAMPUS_USER` (`id_user`);

--
-- Indices de la tabla `voucher_type`
--
ALTER TABLE `voucher_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accessory`
--
ALTER TABLE `accessory`
  MODIFY `id_accessory` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `billingpersale`
--
ALTER TABLE `billingpersale`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `billingpersale_detail`
--
ALTER TABLE `billingpersale_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `campus`
--
ALTER TABLE `campus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `carrier`
--
ALTER TABLE `carrier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `coin`
--
ALTER TABLE `coin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `company_corporate`
--
ALTER TABLE `company_corporate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `content_headers`
--
ALTER TABLE `content_headers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `detail_income`
--
ALTER TABLE `detail_income`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `development_process`
--
ALTER TABLE `development_process`
  MODIFY `id_development_process` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `document_type`
--
ALTER TABLE `document_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `flats`
--
ALTER TABLE `flats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `guest`
--
ALTER TABLE `guest`
  MODIFY `id_guest` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `headers`
--
ALTER TABLE `headers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `igv`
--
ALTER TABLE `igv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `income`
--
ALTER TABLE `income`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `income_accessory`
--
ALTER TABLE `income_accessory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `income_accessory_details`
--
ALTER TABLE `income_accessory_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `income_products`
--
ALTER TABLE `income_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `income_products_details`
--
ALTER TABLE `income_products_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `intent`
--
ALTER TABLE `intent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `kardex`
--
ALTER TABLE `kardex`
  MODIFY `id_kardex` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `kardex_accessory`
--
ALTER TABLE `kardex_accessory`
  MODIFY `id_kardex` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `labels`
--
ALTER TABLE `labels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `meal`
--
ALTER TABLE `meal`
  MODIFY `id_meal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `measuring_unit`
--
ALTER TABLE `measuring_unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `motive_document`
--
ALTER TABLE `motive_document`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `notification`
--
ALTER TABLE `notification`
  MODIFY `id_notification` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `payment`
--
ALTER TABLE `payment`
  MODIFY `id_payment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT de la tabla `payment_extra`
--
ALTER TABLE `payment_extra`
  MODIFY `id_extra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `payment_sales_details`
--
ALTER TABLE `payment_sales_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT de la tabla `person`
--
ALTER TABLE `person`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `product`
--
ALTER TABLE `product`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `product_inventories`
--
ALTER TABLE `product_inventories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `product_stock`
--
ALTER TABLE `product_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `product_type_sale`
--
ALTER TABLE `product_type_sale`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proforma`
--
ALTER TABLE `proforma`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `referralguide`
--
ALTER TABLE `referralguide`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id_reservation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT de la tabla `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `room`
--
ALTER TABLE `room`
  MODIFY `id_room` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT de la tabla `room_type`
--
ALTER TABLE `room_type`
  MODIFY `id_type` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `sales_accessory`
--
ALTER TABLE `sales_accessory`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sales_meal`
--
ALTER TABLE `sales_meal`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sales_product`
--
ALTER TABLE `sales_product`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sub_menu`
--
ALTER TABLE `sub_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `sunat`
--
ALTER TABLE `sunat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `task_control`
--
ALTER TABLE `task_control`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `token`
--
ALTER TABLE `token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `user_campus`
--
ALTER TABLE `user_campus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrier`
--
ALTER TABLE `carrier`
  ADD CONSTRAINT `FK_CARRIER_DOCUMENT_TYPE` FOREIGN KEY (`id_document_type`) REFERENCES `document_type` (`id`);

--
-- Filtros para la tabla `company_guest`
--
ALTER TABLE `company_guest`
  ADD CONSTRAINT `FK_COMPANY_GUEST_COMPANY` FOREIGN KEY (`id_company`) REFERENCES `company_corporate` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_COMPANY_GUEST_PERSON` FOREIGN KEY (`id_person`) REFERENCES `person` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
