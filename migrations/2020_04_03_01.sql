CREATE TABLE `inventarios_clasificacion` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Clasificacion` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Estado` int(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `pedidos_items` (
  `ID` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `pedido_id` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `product_id` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Observaciones` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `Cantidad` double NOT NULL,
  `ValorUnitario` double NOT NULL,
  `Total` double NOT NULL,
  `Estado` int(11) NOT NULL,
  `Created` datetime NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `pedido_id` (`pedido_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `productos_servicios` (
  `ID` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Referencia` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `Nombre` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `DescripcionCorta` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `DescripcionLarga` text COLLATE utf8_spanish_ci NOT NULL,
  `PrecioVenta` double NOT NULL,
  `idClasificacion` int(11) NOT NULL,
  `Orden` int(11) NOT NULL,
  `Estado` int(1) NOT NULL,
  `idUser` int(11) NOT NULL,
  `Created` datetime NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Referencia` (`Referencia`),
  KEY `idClasificacion` (`idClasificacion`),
  KEY `Orden` (`Orden`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `productos_servicios_imagenes` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `idProducto` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Ruta` varchar(90) COLLATE utf8_spanish_ci NOT NULL,
  `NombreArchivo` text COLLATE utf8_spanish_ci NOT NULL,
  `Extension` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `Tamano` bigint(20) NOT NULL,
  `idUser` int(11) NOT NULL,
  `Created` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `idProducto` (`idProducto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;