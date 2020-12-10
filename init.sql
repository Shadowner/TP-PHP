CREATE DATABASE `eCommerce`;
USE `eCommerce`;
CREATE TABLE `EC_PRODUCTS` (
  `ID_PRODUCT` int(11) NOT NULL AUTO_INCREMENT,
  `NAME_PRODUCT` varchar(255) CHARACTER SET latin1 NOT NULL,
  `DESCRIPTION_PRODUCT` text CHARACTER SET latin1 NOT NULL,
  `PRICE_PRODUCT` float(8,2) NOT NULL,
  `STOCK_PRODUCT` int(11) NOT NULL,
  PRIMARY KEY (`ID_PRODUCT`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
LOCK TABLES `EC_PRODUCTS` WRITE;
INSERT INTO `EC_PRODUCTS` VALUES (1,'Figurinne POP Tardis','Magnifique figurine pop du tardis. L eparfait cadeau de noÃ«l pour n&#39;importe quel fan de la sÃ©rie Dr Who.',20.99,20),(2,'Oculus Quest','Sans doute l&#39;un des meilleur casque VR du marchÃ©. Portatif, transportez le partout.',399.99,5),(3,'SSD Samsung 512GB','L&#39;un des meilleur ssd sata disponible',55.00,200),(4,'Network Switch','Un switch 1GB/s. 5 port.',15.35,40),(5,'Ryzen 9 5900X','Le meilleur processeur gaming au monde',835.00,1),(6,'Aorus B450 Pro','Carte mÃ¨re pour les processeur Ryzen  de toutes generation.',126.86,20),(7,'Perceuse-visseuse sans-fil AEG','Visseuse Ã  percussion compacte et puissante 18 V.',228.36,21);
UNLOCK TABLES;
CREATE TABLE `EC_IMAGES` (
  `ID_IMAGE` int(11) NOT NULL AUTO_INCREMENT,
  `EXTENSION_IMAGE` varchar(4) NOT NULL,
  `ID_PRODUCT` int(11) NOT NULL,
  PRIMARY KEY (`ID_IMAGE`),
  KEY `FK_EC_IMAGE_EC_ILLUST_EC_PRODU` (`ID_PRODUCT`),
  CONSTRAINT `FK_EC_IMAGE_EC_ILLUST_EC_PRODU` FOREIGN KEY (`ID_PRODUCT`) REFERENCES `EC_PRODUCTS` (`ID_PRODUCT`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
LOCK TABLES `EC_IMAGES` WRITE;
INSERT INTO `EC_IMAGES` VALUES (1,'jpg',1),(2,'jpg',1),(3,'jpg',1),(4,'jpg',1),(5,'jpg',1),(6,'jpg',2),(7,'jpg',2),(8,'jpg',2),(9,'jpg',2),(10,'jpg',3),(11,'jpg',3),(12,'jpg',3),(13,'jpg',3),(14,'jpg',4),(15,'jpg',4),(16,'jpg',4),(17,'jpg',4),(18,'jpg',4),(19,'jpg',4),(20,'jpg',4),(21,'jpg',5),(22,'jpg',5),(23,'jpg',5),(24,'jpg',6),(25,'jpg',6),(26,'jpg',6),(27,'jpg',6),(28,'jpg',7),(29,'jpg',7),(30,'jpg',7),(31,'jpg',7);
UNLOCK TABLES;