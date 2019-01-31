-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: seeddms
-- ------------------------------------------------------
-- Server version	5.7.18-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `historialconvocatorias`
--

DROP TABLE IF EXISTS `historialconvocatorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `historialconvocatorias` (
  `idUsuario` int(11) NOT NULL,
  `mesConvocatoria` varchar(10) DEFAULT NULL,
  `yearConvocatoria` varchar(4) DEFAULT NULL,
  KEY `idUsuario` (`idUsuario`),
  CONSTRAINT `historialconvocatorias_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `tblusers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historialconvocatorias`
--

LOCK TABLES `historialconvocatorias` WRITE;
/*!40000 ALTER TABLE `historialconvocatorias` DISABLE KEYS */;
INSERT INTO `historialconvocatorias` VALUES (7,'enero','2018'),(5,'enero','2018'),(8,'enero','2018');
/*!40000 ALTER TABLE `historialconvocatorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `revocacionreservas`
--

DROP TABLE IF EXISTS `revocacionreservas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `revocacionreservas` (
  `idDocumento` int(11) NOT NULL,
  `fechaResolucion` varchar(10) DEFAULT NULL,
  KEY `idDocumento` (`idDocumento`),
  CONSTRAINT `revocacionreservas_ibfk_1` FOREIGN KEY (`idDocumento`) REFERENCES `tbldocuments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `revocacionreservas`
--

LOCK TABLES `revocacionreservas` WRITE;
/*!40000 ALTER TABLE `revocacionreservas` DISABLE KEYS */;
INSERT INTO `revocacionreservas` VALUES (56,'2018-01-12'),(57,'16-10-2017'),(58,'2017-10-31');
/*!40000 ALTER TABLE `revocacionreservas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblacls`
--

DROP TABLE IF EXISTS `tblacls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblacls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `target` int(11) NOT NULL DEFAULT '0',
  `targetType` tinyint(4) NOT NULL DEFAULT '0',
  `userID` int(11) NOT NULL DEFAULT '-1',
  `groupID` int(11) NOT NULL DEFAULT '-1',
  `mode` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblacls`
--

LOCK TABLES `tblacls` WRITE;
/*!40000 ALTER TABLE `tblacls` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblacls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblattributedefinitions`
--

DROP TABLE IF EXISTS `tblattributedefinitions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblattributedefinitions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `objtype` tinyint(4) NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `multiple` tinyint(4) NOT NULL DEFAULT '0',
  `minvalues` int(11) NOT NULL DEFAULT '0',
  `maxvalues` int(11) NOT NULL DEFAULT '0',
  `valueset` text,
  `regex` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblattributedefinitions`
--

LOCK TABLES `tblattributedefinitions` WRITE;
/*!40000 ALTER TABLE `tblattributedefinitions` DISABLE KEYS */;
INSERT INTO `tblattributedefinitions` VALUES (2,'Fecha de clasificación',2,7,0,0,0,'',''),(3,'Fundamento legal (Art. 19 LAIP)',2,3,1,1,8,',a)Planes militares secretos y negociaciones políticas a los que se refiere el art. 168 ordinal 7º  de la constitución.,b)Información que perjudique o ponga en riesgo la defensa nacional o seguridad pública.,c)Información que menoscabe las relaciones internacionales o la conducción de negociaciones diplomáticas del país.,d)Que ponga en peligro evidente la vida; seguridad o salud de cualquier persona.,e)Información que contenga opiniones o recomendaciones que formen parte del proceso deliberativo de los servidores públicos en tanto no sea adoptada la decisión definitiva.,f)Información que causare un serio perjuicio en la prevención; investigación o persecución de actos ilícitos.,g) Información que comprometiere las estrategias y funciones estatales en procedimientos judiciales o administrativos en curso.,h)Información que pueda generar una ventaja indebida a una persona en perjuicio de un tercero.',''),(4,'Tipo de clasificación',2,3,1,0,0,',Total,Parcial',''),(5,'Unidad Administrativa',2,3,0,0,0,'',''),(6,'No. de Declaración de Reserva',2,3,0,0,0,'',''),(7,'Motivo de la reserva',2,3,0,0,0,'',''),(8,'Fecha de emisión del acta de inexistencia',2,7,0,0,0,'',''),(9,'Autoridad que reserva',2,3,0,0,0,'','');
/*!40000 ALTER TABLE `tblattributedefinitions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcategory`
--

DROP TABLE IF EXISTS `tblcategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblcategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcategory`
--

LOCK TABLES `tblcategory` WRITE;
/*!40000 ALTER TABLE `tblcategory` DISABLE KEYS */;
INSERT INTO `tblcategory` VALUES (1,''),(2,'Actas de inexistencia'),(3,'Declaratorias de reserva');
/*!40000 ALTER TABLE `tblcategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbldocumentapprovelog`
--

DROP TABLE IF EXISTS `tbldocumentapprovelog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbldocumentapprovelog` (
  `approveLogID` int(11) NOT NULL AUTO_INCREMENT,
  `approveID` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`approveLogID`),
  KEY `tblDocumentApproveLog_approve` (`approveID`),
  KEY `tblDocumentApproveLog_user` (`userID`),
  CONSTRAINT `tblDocumentApproveLog_approve` FOREIGN KEY (`approveID`) REFERENCES `tbldocumentapprovers` (`approveID`) ON DELETE CASCADE,
  CONSTRAINT `tblDocumentApproveLog_user` FOREIGN KEY (`userID`) REFERENCES `tblusers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbldocumentapprovelog`
--

LOCK TABLES `tbldocumentapprovelog` WRITE;
/*!40000 ALTER TABLE `tbldocumentapprovelog` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbldocumentapprovelog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbldocumentapprovers`
--

DROP TABLE IF EXISTS `tbldocumentapprovers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbldocumentapprovers` (
  `approveID` int(11) NOT NULL AUTO_INCREMENT,
  `documentID` int(11) NOT NULL DEFAULT '0',
  `version` smallint(5) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `required` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`approveID`),
  UNIQUE KEY `documentID` (`documentID`,`version`,`type`,`required`),
  CONSTRAINT `tblDocumentApprovers_document` FOREIGN KEY (`documentID`) REFERENCES `tbldocuments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbldocumentapprovers`
--

LOCK TABLES `tbldocumentapprovers` WRITE;
/*!40000 ALTER TABLE `tbldocumentapprovers` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbldocumentapprovers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbldocumentattributes`
--

DROP TABLE IF EXISTS `tbldocumentattributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbldocumentattributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document` int(11) DEFAULT NULL,
  `attrdef` int(11) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `document` (`document`,`attrdef`),
  KEY `tblDocumentAttributes_attrdef` (`attrdef`),
  CONSTRAINT `tblDocumentAttributes_attrdef` FOREIGN KEY (`attrdef`) REFERENCES `tblattributedefinitions` (`id`),
  CONSTRAINT `tblDocumentAttributes_document` FOREIGN KEY (`document`) REFERENCES `tbldocuments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=259 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbldocumentattributes`
--

LOCK TABLES `tbldocumentattributes` WRITE;
/*!40000 ALTER TABLE `tbldocumentattributes` DISABLE KEYS */;
INSERT INTO `tbldocumentattributes` VALUES (47,19,2,'2017-09-13'),(48,19,3,'<c'),(49,19,7,'das'),(50,19,6,'111'),(51,19,4,'<Total'),(52,19,5,'MOP1'),(107,32,9,'Alcalde'),(108,32,2,'2017-09-28'),(109,32,3,',b,c'),(110,32,7,'El alcalde no lo quiere'),(111,32,6,'SMF1'),(112,32,4,',Total'),(113,32,5,'recursos humanosK'),(121,34,9,'das'),(122,34,2,'2017-09-08'),(123,34,3,',f,g,h'),(124,34,7,'dgfd'),(125,34,6,'hhhjj'),(126,34,4,',Total'),(127,34,5,'kk'),(151,40,9,'Gerente'),(152,40,2,'2017-09-29'),(153,40,3,',b,c,d'),(154,40,7,'Al divulgar esta información se pone en peligro la vida y la seguridad de los empleados y personas involucradas en estos procesos. Asimismo, se pone en peligro el éxito de los procesos relacionados con este tema.'),(155,40,6,'GOF-001/2013'),(156,40,4,',Parcial'),(157,40,5,'Gerencia de Operaciones Financieras'),(180,51,8,'2001-03-01'),(182,53,8,'2017-10-11'),(193,56,3,',b'),(194,56,4,',Total'),(195,56,5,'d'),(196,57,3,',c'),(197,57,4,',Parcial'),(198,58,8,'2017-10-11'),(200,60,9,'Gerente de Administración y Desarrollo'),(201,60,2,'2012-03-06'),(202,60,3,',d,g'),(203,60,7,'Tanto el catálogo de firmas autorizadas para la aprobación de operaciones como el catálogo de usuarios y claves de acceso al Sistema de Recursos Humanos y Clínica Médica son de alto riesgo, ya que el divulgar esta información pone en peligro no solo la seguridad de los funcionarios y empleados, sino también la seguridad financiera del Banco.'),(204,60,6,'GAD-001/2013'),(205,60,4,',Total'),(206,60,5,'Gerencia de Administración y Desarrollo'),(207,61,9,'mario'),(208,61,2,'2017-10-05'),(209,61,3,',a,e,g'),(210,61,7,'sape'),(211,61,6,'777'),(212,61,4,',Parcial'),(219,63,9,'mario'),(220,63,2,'2017-10-19'),(221,63,3,',a,c,g'),(222,63,7,'no quiero'),(223,63,6,'xxx'),(224,63,4,',Parcial'),(225,63,5,'gerencia'),(229,66,8,'2017-10-01'),(230,67,9,'Jefe'),(231,67,2,'2017-02-07'),(232,67,3,',a,b,e,f'),(233,67,7,'Yo quiero'),(234,67,6,'2017-7'),(235,67,4,',Parcial'),(236,67,5,'Gerencia de brandys'),(237,68,8,'2017-12-21'),(238,69,9,'fff'),(239,69,2,'2017-10-14'),(240,69,3,',a)Planes militares secretos y negociaciones políticas a los que se refiere el art. 168 ordinal 7º  de la constitución.'),(241,69,7,'yooooo'),(242,69,4,',Parcial'),(243,70,8,'2017-03-01'),(244,69,5,'Nueva unidad'),(245,71,8,'2017-11-04'),(246,72,8,'2017-10-11'),(251,77,8,'2013-01-30'),(252,78,9,'mario'),(253,78,2,'2017-10-05'),(254,78,3,',c)Información que menoscabe las relaciones internacionales o la conducción de negociaciones diplomáticas del país.'),(255,78,7,'nada q ver'),(256,78,6,'112123'),(257,78,4,',Parcial'),(258,78,5,'GERENCIA');
/*!40000 ALTER TABLE `tbldocumentattributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbldocumentcategory`
--

DROP TABLE IF EXISTS `tbldocumentcategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbldocumentcategory` (
  `categoryID` int(11) NOT NULL DEFAULT '0',
  `documentID` int(11) NOT NULL DEFAULT '0',
  KEY `tblDocumentCategory_category` (`categoryID`),
  KEY `tblDocumentCategory_document` (`documentID`),
  CONSTRAINT `tblDocumentCategory_category` FOREIGN KEY (`categoryID`) REFERENCES `tblcategory` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tblDocumentCategory_document` FOREIGN KEY (`documentID`) REFERENCES `tbldocuments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbldocumentcategory`
--

LOCK TABLES `tbldocumentcategory` WRITE;
/*!40000 ALTER TABLE `tbldocumentcategory` DISABLE KEYS */;
INSERT INTO `tbldocumentcategory` VALUES (3,19),(3,32),(3,34),(3,40),(2,53),(3,56),(3,57),(2,58),(3,60),(3,61),(3,63),(2,66),(3,67),(2,68),(3,69),(2,70),(2,71),(2,72),(2,77),(3,78);
/*!40000 ALTER TABLE `tbldocumentcategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbldocumentcontent`
--

DROP TABLE IF EXISTS `tbldocumentcontent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbldocumentcontent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document` int(11) NOT NULL DEFAULT '0',
  `version` smallint(5) unsigned NOT NULL,
  `comment` text,
  `date` int(12) DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `dir` varchar(255) NOT NULL DEFAULT '',
  `orgFileName` varchar(150) NOT NULL DEFAULT '',
  `fileType` varchar(10) NOT NULL DEFAULT '',
  `mimeType` varchar(100) NOT NULL DEFAULT '',
  `fileSize` bigint(20) DEFAULT NULL,
  `checksum` char(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `document` (`document`,`version`),
  CONSTRAINT `tblDocumentContent_document` FOREIGN KEY (`document`) REFERENCES `tbldocuments` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbldocumentcontent`
--

LOCK TABLES `tbldocumentcontent` WRITE;
/*!40000 ALTER TABLE `tbldocumentcontent` DISABLE KEYS */;
INSERT INTO `tbldocumentcontent` VALUES (19,19,1,'',1506093319,4,'19/','DGCP.xlsx','.xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',15479,'8e670d3a86deeb894a025fc40cd93af9'),(34,32,1,'',1506614807,7,'32/','26726560.jpg','.jpg','image/jpeg',64966,'e382f386c335163d3788b51dcce4f058'),(36,34,1,'',1506635568,7,'34/','Ejercicio3.m','.m','application/octet-stream',2330,'5eb6674a5c0833181948d466461cff50'),(45,40,1,'YA CORREGI EL FALLO',1506704684,6,'40/','a.ini','.ini','application/octet-stream',397824,'d53eeaf63cc69e5ba38205925f74dd2a'),(46,40,2,'ESTA ES LA NUEVA',1506705144,6,'40/','26726560.jpg','.jpg','image/jpeg',64966,'e382f386c335163d3788b51dcce4f058'),(57,51,1,'',1507852309,8,'51/','39 y 40-A-2014 (JC) MH - Karla Diaz de Benitez - Res Def (UM-KMS).pdf','.pdf','application/pdf',225655,'94b76e357c9c0b74f17734ee1e6bf2e4'),(59,53,1,'',1507864864,4,'53/','Lectura_basica_7_rommell.docx','.docx','application/vnd.openxmlformats-officedocument.wordprocessingml.document',60519,'0a686acd85fcac940bb3ca849d0720f9'),(62,56,1,'',1508113887,6,'56/','California-Wallpaper-7.jpg','.jpg','image/jpeg',886955,'8d7d978c7fd0d69bda0067f59235dad2'),(63,57,1,'',1508121910,6,'57/','Captura.PNG','.PNG','image/png',43520,'d1734924e645f6b32887ef8a9337fdb0'),(64,58,1,'',1508169610,6,'58/','3-2013.jpg','.jpg','image/jpeg',716308,'65a7a6997d2b0fbe0cdaf41fab299e39'),(66,60,1,'',1508361424,6,'60/','SancionadorLEG.jpg','.jpg','image/jpeg',508196,'d29315114233fe300795fa6da89abeaf'),(67,61,1,'',1508382528,6,'61/','SancionadorLEG.jpg','.jpg','image/jpeg',508196,'d29315114233fe300795fa6da89abeaf'),(69,63,1,'',1508383030,6,'63/','SancionadorLEG.jpg','.jpg','image/jpeg',508196,'d29315114233fe300795fa6da89abeaf'),(72,66,1,'',1508533471,7,'66/','3-2013.jpg','.jpg','image/jpeg',716308,'65a7a6997d2b0fbe0cdaf41fab299e39'),(73,67,1,'',1508533573,7,'67/','17-2013.png','.png','image/png',150900,'3f2833c1737f100b9da408eed44374b6'),(74,68,1,'',1508533921,7,'68/','agenda.psd','.psd','application/octet-stream',1521133,'3487ba5bc69253969c402a3841682766'),(75,69,1,'ya lo corregi',1509292527,6,'69/','actualizaciónContratoPorFalloFecha.pdf','.pdf','application/pdf',1872845,'5a398294176c240e645ba3d7f88c1954'),(76,63,2,'nueva versión',1509326166,6,'63/','africa-1170055_1920.jpg','.jpg','image/jpeg',241510,'7d63c47989932d8d00839918a1173a8c'),(77,70,1,'ya',1509326811,6,'70/','nuevo2.PNG','.PNG','image/png',132942,'658b79c6959d3630820039dea2e47445'),(78,71,1,'ahora si corregido',1509328643,6,'71/','img012.jpg','.jpg','image/jpeg',61427,'b32089371629a98f1e0287dee9c7c7cf'),(79,72,1,'verison corregida',1509330511,6,'72/','form1.jpg','.jpg','image/jpeg',1579307,'4ab47440a201062d29ffc343caa9c09d'),(84,77,1,'',1509345042,6,'77/','aa6sw0iyyum-dave-lastovskiy.jpg','.jpg','image/jpeg',1362124,'7d3fbc7bc8f34ede1b36c5aaecd7e537'),(85,78,1,'',1509345116,6,'78/','fotoapp.PNG','.PNG','image/png',134122,'01a9dd8c463a5968be76cd57851faf41');
/*!40000 ALTER TABLE `tbldocumentcontent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbldocumentcontentattributes`
--

DROP TABLE IF EXISTS `tbldocumentcontentattributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbldocumentcontentattributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` int(11) DEFAULT NULL,
  `attrdef` int(11) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `content` (`content`,`attrdef`),
  KEY `tblDocumentContentAttributes_attrdef` (`attrdef`),
  CONSTRAINT `tblDocumentContentAttributes_attrdef` FOREIGN KEY (`attrdef`) REFERENCES `tblattributedefinitions` (`id`),
  CONSTRAINT `tblDocumentContentAttributes_document` FOREIGN KEY (`content`) REFERENCES `tbldocumentcontent` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbldocumentcontentattributes`
--

LOCK TABLES `tbldocumentcontentattributes` WRITE;
/*!40000 ALTER TABLE `tbldocumentcontentattributes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbldocumentcontentattributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbldocumentfiles`
--

DROP TABLE IF EXISTS `tbldocumentfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbldocumentfiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document` int(11) NOT NULL DEFAULT '0',
  `userID` int(11) NOT NULL DEFAULT '0',
  `comment` text,
  `name` varchar(150) DEFAULT NULL,
  `date` int(12) DEFAULT NULL,
  `dir` varchar(255) NOT NULL DEFAULT '',
  `orgFileName` varchar(150) NOT NULL DEFAULT '',
  `fileType` varchar(10) NOT NULL DEFAULT '',
  `mimeType` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `tblDocumentFiles_document` (`document`),
  KEY `tblDocumentFiles_user` (`userID`),
  CONSTRAINT `tblDocumentFiles_document` FOREIGN KEY (`document`) REFERENCES `tbldocuments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tblDocumentFiles_user` FOREIGN KEY (`userID`) REFERENCES `tblusers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbldocumentfiles`
--

LOCK TABLES `tbldocumentfiles` WRITE;
/*!40000 ALTER TABLE `tbldocumentfiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbldocumentfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbldocumentlinks`
--

DROP TABLE IF EXISTS `tbldocumentlinks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbldocumentlinks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document` int(11) NOT NULL DEFAULT '0',
  `target` int(11) NOT NULL DEFAULT '0',
  `userID` int(11) NOT NULL DEFAULT '0',
  `public` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tblDocumentLinks_document` (`document`),
  KEY `tblDocumentLinks_target` (`target`),
  KEY `tblDocumentLinks_user` (`userID`),
  CONSTRAINT `tblDocumentLinks_document` FOREIGN KEY (`document`) REFERENCES `tbldocuments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tblDocumentLinks_target` FOREIGN KEY (`target`) REFERENCES `tbldocuments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tblDocumentLinks_user` FOREIGN KEY (`userID`) REFERENCES `tblusers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbldocumentlinks`
--

LOCK TABLES `tbldocumentlinks` WRITE;
/*!40000 ALTER TABLE `tbldocumentlinks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbldocumentlinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbldocumentlocks`
--

DROP TABLE IF EXISTS `tbldocumentlocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbldocumentlocks` (
  `document` int(11) NOT NULL DEFAULT '0',
  `userID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`document`),
  KEY `tblDocumentLocks_user` (`userID`),
  CONSTRAINT `tblDocumentLocks_document` FOREIGN KEY (`document`) REFERENCES `tbldocuments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tblDocumentLocks_user` FOREIGN KEY (`userID`) REFERENCES `tblusers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbldocumentlocks`
--

LOCK TABLES `tbldocumentlocks` WRITE;
/*!40000 ALTER TABLE `tbldocumentlocks` DISABLE KEYS */;
INSERT INTO `tbldocumentlocks` VALUES (32,1);
/*!40000 ALTER TABLE `tbldocumentlocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbldocumentreviewers`
--

DROP TABLE IF EXISTS `tbldocumentreviewers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbldocumentreviewers` (
  `reviewID` int(11) NOT NULL AUTO_INCREMENT,
  `documentID` int(11) NOT NULL DEFAULT '0',
  `version` smallint(5) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `required` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`reviewID`),
  UNIQUE KEY `documentID` (`documentID`,`version`,`type`,`required`),
  CONSTRAINT `tblDocumentReviewers_document` FOREIGN KEY (`documentID`) REFERENCES `tbldocuments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbldocumentreviewers`
--

LOCK TABLES `tbldocumentreviewers` WRITE;
/*!40000 ALTER TABLE `tbldocumentreviewers` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbldocumentreviewers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbldocumentreviewlog`
--

DROP TABLE IF EXISTS `tbldocumentreviewlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbldocumentreviewlog` (
  `reviewLogID` int(11) NOT NULL AUTO_INCREMENT,
  `reviewID` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`reviewLogID`),
  KEY `tblDocumentReviewLog_review` (`reviewID`),
  KEY `tblDocumentReviewLog_user` (`userID`),
  CONSTRAINT `tblDocumentReviewLog_review` FOREIGN KEY (`reviewID`) REFERENCES `tbldocumentreviewers` (`reviewID`) ON DELETE CASCADE,
  CONSTRAINT `tblDocumentReviewLog_user` FOREIGN KEY (`userID`) REFERENCES `tblusers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbldocumentreviewlog`
--

LOCK TABLES `tbldocumentreviewlog` WRITE;
/*!40000 ALTER TABLE `tbldocumentreviewlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbldocumentreviewlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbldocuments`
--

DROP TABLE IF EXISTS `tbldocuments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbldocuments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(600) DEFAULT NULL,
  `comment` text,
  `date` int(12) DEFAULT NULL,
  `expires` int(12) DEFAULT NULL,
  `owner` int(11) DEFAULT NULL,
  `folder` int(11) DEFAULT NULL,
  `folderList` text NOT NULL,
  `inheritAccess` tinyint(1) NOT NULL DEFAULT '1',
  `defaultAccess` tinyint(4) NOT NULL DEFAULT '0',
  `locked` int(11) NOT NULL DEFAULT '-1',
  `keywords` text NOT NULL,
  `sequence` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tblDocuments_folder` (`folder`),
  KEY `tblDocuments_owner` (`owner`),
  CONSTRAINT `tblDocuments_folder` FOREIGN KEY (`folder`) REFERENCES `tblfolders` (`id`),
  CONSTRAINT `tblDocuments_owner` FOREIGN KEY (`owner`) REFERENCES `tblusers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbldocuments`
--

LOCK TABLES `tbldocuments` WRITE;
/*!40000 ALTER TABLE `tbldocuments` DISABLE KEYS */;
INSERT INTO `tbldocuments` VALUES (19,'DECMOP','Documento subido por un Oficial de Información',1506093319,1506117600,4,9,':1:16:9:',1,2,-1,'',1),(32,'Contrataciones de la alcaldía de San Francisco Menendez','Documento subido por un Oficial de Información',1506614807,1570226400,7,32,':1:17:18:32:',1,2,-1,'',1),(34,'aaa','Documento subido por un Oficial de Información',1506635568,1496095200,7,32,':1:17:18:32:',1,2,-1,'',2),(40,'Documentos relativos a la administración, recepción, custodia y suministro de especies monetarias.','Documento subido por un Oficial de Información',1506704684,1506636000,6,13,':1:16:13:',1,2,-1,'',8),(51,'Acta de inexistencia Alcaldía de Ahuachapán','Documento subido por un Oficial de Información',1507852309,0,8,33,':1:17:18:33:',1,2,-1,'',2),(53,'Acta de inexistencia MOP','Documento subido por un Oficial de Información',1507864864,0,4,9,':1:16:9:',1,2,-1,'',5),(56,'reserva revocadita','Documento subido por un Oficial de Información',1508113887,1518130800,6,13,':1:16:13:',1,2,-1,'',9),(57,'nuevo doc','Documento subido por un Oficial de Información',1508121910,1516921200,6,13,':1:16:13:',1,2,-1,'',10),(58,'actadsa','Documento subido por un Oficial de Información',1508169610,0,6,13,':1:16:13:',1,2,-1,'',11),(60,'Catálogo de firmas autorizadas para aprobación de operaciones del BCR y sus respectivas notificaciones tanto permanentes como temporales;  y Catálogo de usuarios y claves de acceso al Sistema de Administración de Recursos Humanos y Sistema de la Clínica Médica.','Documento cuya reserva es válida desde el 2012-03-06 sustentado en los literales d,g del artículo 19 (LAIP)',1508361424,1551826800,6,13,':1:16:13:',1,2,-1,'',12),(61,'toci','Documento cuya reserva es válida desde el 2017-10-05 sustentado en los literales a,e,g del artículo 19 (LAIP)',1508382528,1508536800,6,13,':1:16:13:',1,2,-1,'',13),(63,'TOCI2','Documento cuya reserva es válida desde el 2017-10-19 sustentado en los literales a,c,g del artículo 19 (LAIP)',1508383030,1513897200,6,13,':1:16:13:',1,2,-1,'',15),(66,'Acta inexistencia hola','Esta acta certifica que éste ente obligado no tiene ningún documento bajo condición de reserva',1508533471,0,7,32,':1:17:18:32:',1,2,-1,'',3),(67,'Doc1','Documento cuya reserva es válida desde el 2017-02-07 sustentado en los literales a,b,e,f del artículo 19 (LAIP)',1508533573,1509750000,7,32,':1:17:18:32:',1,2,-1,'',4),(68,'rti','Esta acta certifica que éste ente obligado no tiene ningún documento bajo condición de reserva',1508533921,0,7,32,':1:17:18:32:',1,2,-1,'',5),(69,'dasdas','Documento cuya reserva es válida desde el 2017-10-14 sustentado en los literales a)Planes militares secretos y negociaciones políticas a los que se refiere el art. 168 ordinal 7º  de la constitución. del artículo 19 (LAIP)',1509292527,1509750000,6,13,':1:16:13:',1,2,-1,'',16),(70,'Prueba oct','Esta acta certifica que éste ente obligado no tiene ningún documento bajo condición de reserva',1509326811,0,6,13,':1:16:13:',1,2,-1,'',17),(71,'Prueba nov','Esta acta certifica que éste ente obligado no tiene ningún documento bajo condición de reserva',1509328643,0,6,13,':1:16:13:',1,2,-1,'',18),(72,'Esta es una prueba','Esta acta certifica que éste ente obligado no tiene ningún documento bajo condición de reserva',1509330511,0,6,13,':1:16:13:',1,2,-1,'',19),(77,'va a ser que si 4','Esta acta certifica que éste ente obligado no tiene ningún documento bajo condición de reserva',1509345042,0,6,13,':1:16:13:',1,2,-1,'',24),(78,'holi','Documento cuya reserva es válida desde el 2017-10-05 sustentado en los literales c)Información que menoscabe las relaciones internacionales o la conducción de negociaciones diplomáticas del país. del artículo 19 (LAIP)',1509345116,1671663600,6,13,':1:16:13:',1,2,-1,'',25);
/*!40000 ALTER TABLE `tbldocuments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbldocumentstatus`
--

DROP TABLE IF EXISTS `tbldocumentstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbldocumentstatus` (
  `statusID` int(11) NOT NULL AUTO_INCREMENT,
  `documentID` int(11) NOT NULL DEFAULT '0',
  `version` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`statusID`),
  UNIQUE KEY `documentID` (`documentID`,`version`),
  CONSTRAINT `tblDocumentStatus_document` FOREIGN KEY (`documentID`) REFERENCES `tbldocuments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbldocumentstatus`
--

LOCK TABLES `tbldocumentstatus` WRITE;
/*!40000 ALTER TABLE `tbldocumentstatus` DISABLE KEYS */;
INSERT INTO `tbldocumentstatus` VALUES (19,19,1),(34,32,1),(36,34,1),(45,40,1),(46,40,2),(57,51,1),(59,53,1),(62,56,1),(63,57,1),(64,58,1),(66,60,1),(67,61,1),(69,63,1),(76,63,2),(72,66,1),(73,67,1),(74,68,1),(75,69,1),(77,70,1),(78,71,1),(79,72,1),(84,77,1),(85,78,1);
/*!40000 ALTER TABLE `tbldocumentstatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbldocumentstatuslog`
--

DROP TABLE IF EXISTS `tbldocumentstatuslog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbldocumentstatuslog` (
  `statusLogID` int(11) NOT NULL AUTO_INCREMENT,
  `statusID` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`statusLogID`),
  KEY `statusID` (`statusID`),
  KEY `tblDocumentStatusLog_user` (`userID`),
  CONSTRAINT `tblDocumentStatusLog_status` FOREIGN KEY (`statusID`) REFERENCES `tbldocumentstatus` (`statusID`) ON DELETE CASCADE,
  CONSTRAINT `tblDocumentStatusLog_user` FOREIGN KEY (`userID`) REFERENCES `tblusers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=264 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbldocumentstatuslog`
--

LOCK TABLES `tbldocumentstatuslog` WRITE;
/*!40000 ALTER TABLE `tbldocumentstatuslog` DISABLE KEYS */;
INSERT INTO `tbldocumentstatuslog` VALUES (38,19,3,'Added workflow \'IAIP\'','2017-09-22 09:15:19',4),(39,19,3,'New document content submitted, workflow: IAIP','2017-09-22 09:15:19',4),(42,19,2,'Workflow has ended','2017-09-22 09:19:41',1),(55,19,-3,'','2017-09-23 17:00:57',4),(99,34,3,'Added workflow \'IAIP\'','2017-09-28 10:06:47',7),(100,34,3,'New document content submitted, workflow: IAIP','2017-09-28 10:06:47',7),(104,34,2,'Workflow has ended','2017-09-28 15:50:34',1),(105,36,3,'Added workflow \'IAIP\'','2017-09-28 15:52:48',7),(106,36,3,'New document content submitted, workflow: IAIP','2017-09-28 15:52:48',7),(111,36,2,'Workflow has ended','2017-09-28 16:33:09',1),(136,36,-3,'','2017-09-28 21:51:15',7),(137,45,3,'Added workflow \'IAIP\'','2017-09-29 11:04:44',6),(138,45,3,'New document content submitted, workflow: IAIP','2017-09-29 11:04:44',6),(139,45,2,'Workflow has ended','2017-09-29 11:10:23',1),(140,46,3,'Added workflow \'IAIP\'','2017-09-29 11:12:24',6),(141,46,3,'New document content submitted, workflow: IAIP','2017-09-29 11:12:24',6),(142,46,2,'Workflow has ended','2017-09-29 11:13:29',1),(156,46,-3,'','2017-10-03 10:37:30',6),(169,57,3,'Added workflow \'IAIP\'','2017-10-12 17:51:49',8),(170,57,3,'New document content submitted, workflow: IAIP','2017-10-12 17:51:49',8),(174,59,3,'Added workflow \'IAIP\'','2017-10-12 21:21:04',4),(175,59,3,'New document content submitted, workflow: IAIP','2017-10-12 21:21:04',4),(189,62,3,'Added workflow \'IAIP\'','2017-10-15 18:31:27',6),(190,62,3,'New document content submitted, workflow: IAIP','2017-10-15 18:31:27',6),(191,62,2,'Workflow has ended','2017-10-15 18:31:37',1),(192,62,-2,'hh','2017-10-15 18:32:15',1),(193,63,3,'Added workflow \'IAIP\'','2017-10-15 20:45:10',6),(194,63,3,'New document content submitted, workflow: IAIP','2017-10-15 20:45:10',6),(195,63,2,'Workflow has ended','2017-10-15 20:47:22',1),(196,63,-2,'yo lo revoco','2017-10-15 20:50:30',1),(197,59,2,'Workflow has ended','2017-10-15 20:55:43',1),(199,64,3,'Added workflow \'IAIP\'','2017-10-16 10:00:10',6),(200,64,3,'New document content submitted, workflow: IAIP','2017-10-16 10:00:10',6),(201,64,2,'Workflow has ended','2017-10-16 10:01:19',1),(209,66,3,'Added workflow \'IAIP\'','2017-10-18 15:17:04',6),(210,66,3,'New document content submitted, workflow: IAIP','2017-10-18 15:17:04',6),(211,67,3,'Added workflow \'IAIP\'','2017-10-18 21:08:48',6),(212,67,3,'New document content submitted, workflow: IAIP','2017-10-18 21:08:48',6),(215,69,3,'Added workflow \'IAIP\'','2017-10-18 21:17:10',6),(216,69,3,'New document content submitted, workflow: IAIP','2017-10-18 21:17:10',6),(222,72,3,'Added workflow \'IAIP\'','2017-10-20 15:04:31',7),(223,72,3,'New document content submitted, workflow: IAIP','2017-10-20 15:04:31',7),(224,73,3,'Added workflow \'IAIP\'','2017-10-20 15:06:13',7),(225,73,3,'New document content submitted, workflow: IAIP','2017-10-20 15:06:13',7),(226,74,3,'Added workflow \'IAIP\'','2017-10-20 15:12:01',7),(227,74,3,'New document content submitted, workflow: IAIP','2017-10-20 15:12:01',7),(228,67,-3,'','2017-10-23 14:18:01',6),(229,75,3,'Added workflow \'IAIP\'','2017-10-29 09:55:27',6),(230,75,3,'New document content submitted, workflow: IAIP','2017-10-29 09:55:27',6),(231,64,-2,'lo desclasifica mario','2017-10-29 19:03:01',1),(232,69,2,'Workflow has ended','2017-10-29 19:05:13',1),(233,76,3,'Added workflow \'IAIP\'','2017-10-29 19:16:06',6),(234,76,3,'New document content submitted, workflow: IAIP','2017-10-29 19:16:06',6),(235,76,2,'Workflow has ended','2017-10-29 19:16:35',1),(236,77,3,'Added workflow \'IAIP\'','2017-10-29 19:26:51',6),(237,77,3,'New document content submitted, workflow: IAIP','2017-10-29 19:26:51',6),(238,77,2,'Workflow has ended','2017-10-29 19:29:16',1),(239,78,3,'Added workflow \'IAIP\'','2017-10-29 19:57:23',6),(240,78,3,'New document content submitted, workflow: IAIP','2017-10-29 19:57:23',6),(241,78,2,'Workflow has ended','2017-10-29 20:27:38',1),(242,75,2,'Workflow has ended','2017-10-29 20:27:49',1),(243,79,3,'Added workflow \'IAIP\'','2017-10-29 20:28:31',6),(244,79,3,'New document content submitted, workflow: IAIP','2017-10-29 20:28:31',6),(245,79,2,'Workflow has ended','2017-10-29 20:37:46',1),(249,74,2,'Workflow has ended','2017-10-29 21:13:17',1),(250,57,-3,'','2017-10-29 21:41:44',8),(251,57,3,'','2017-10-29 21:47:53',8),(260,84,3,'Added workflow \'IAIP\'','2017-10-30 00:30:42',6),(261,84,3,'New document content submitted, workflow: IAIP','2017-10-30 00:30:42',6),(262,85,3,'Added workflow \'IAIP\'','2017-10-30 00:31:56',6),(263,85,3,'New document content submitted, workflow: IAIP','2017-10-30 00:31:56',6);
/*!40000 ALTER TABLE `tbldocumentstatuslog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblevents`
--

DROP TABLE IF EXISTS `tblevents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblevents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `comment` text,
  `start` int(12) DEFAULT NULL,
  `stop` int(12) DEFAULT NULL,
  `date` int(12) DEFAULT NULL,
  `userID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblevents`
--

LOCK TABLES `tblevents` WRITE;
/*!40000 ALTER TABLE `tblevents` DISABLE KEYS */;
INSERT INTO `tblevents` VALUES (2,'Abierta convocatoria','Hoal',1508364000,1509577199,1508383138,1);
/*!40000 ALTER TABLE `tblevents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblfolderattributes`
--

DROP TABLE IF EXISTS `tblfolderattributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblfolderattributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folder` int(11) DEFAULT NULL,
  `attrdef` int(11) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `folder` (`folder`,`attrdef`),
  KEY `tblFolderAttributes_attrdef` (`attrdef`),
  CONSTRAINT `tblFolderAttributes_attrdef` FOREIGN KEY (`attrdef`) REFERENCES `tblattributedefinitions` (`id`),
  CONSTRAINT `tblFolderAttributes_folder` FOREIGN KEY (`folder`) REFERENCES `tblfolders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblfolderattributes`
--

LOCK TABLES `tblfolderattributes` WRITE;
/*!40000 ALTER TABLE `tblfolderattributes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblfolderattributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblfolders`
--

DROP TABLE IF EXISTS `tblfolders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblfolders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(70) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `folderList` text NOT NULL,
  `comment` text,
  `date` int(12) DEFAULT NULL,
  `owner` int(11) DEFAULT NULL,
  `inheritAccess` tinyint(1) NOT NULL DEFAULT '1',
  `defaultAccess` tinyint(4) NOT NULL DEFAULT '0',
  `sequence` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  KEY `tblFolders_owner` (`owner`),
  CONSTRAINT `tblFolders_owner` FOREIGN KEY (`owner`) REFERENCES `tblusers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblfolders`
--

LOCK TABLES `tblfolders` WRITE;
/*!40000 ALTER TABLE `tblfolders` DISABLE KEYS */;
INSERT INTO `tblfolders` VALUES (1,'Directorio central',0,'','DMS root',1505246086,1,0,2,0),(5,'Ministerio de Salud (MINSAL)',16,':1:16:','Carpeta del MINSAL',1505922223,1,1,3,4),(6,'Ministerio de Trabajo y Previsión Social (MTPS)',16,':1:16:','Carpeta del Ministerio de Trabajo y Previsión Social (MTPS)',1505922239,1,1,2,5),(7,'Ministerio  de Turismo (MITUR)',16,':1:16:','Carpeta del Ministerio  de Turismo (MITUR)',1505922256,1,1,2,6),(8,'Ministerio de Relaciones Exteriores (RR.EE.)',16,':1:16:','Carpeta del Ministerio de Relaciones Exteriores (RR.EE.)',1505922275,1,0,3,7),(9,'MOP',16,':1:16:','Carpeta del Ministerio de Obras Públicas, Transporte y de Vivienda y Desarrollo Urbano (MOP)',1505922340,1,0,3,8),(13,'Banco Central de Reserva',16,':1:16:','Directorio del BCR',1506026815,1,0,3,9),(16,'Entes obligados no municipales',1,':1:','',1506611832,1,1,3,10),(17,'Alcaldías municipales',1,':1:','Carpeta donde se almacenan los documentos de las alcaldías municipales, por departamento.',1506611862,1,1,2,11),(18,'Ahuachapán',17,':1:17:','En este directorio van las carpetas de los Oficiales de Información del departamento de Ahuachapán',1506612825,1,1,2,1),(19,'Cabañas',17,':1:17:','Entes municipales del municipio de Cabañas',1506612982,1,1,2,2),(20,'Chalatenango',17,':1:17:','Entes obligados municipales del departamento de Chalatenango\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n',1506613009,1,1,2,3),(21,'Cuscatlán',17,':1:17:','Entes obligados municipales del departamento de Cuscatlán\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n',1506613050,1,1,2,4),(22,'La Libertad',17,':1:17:','Entes obligados municipales del departamento de La Libertad\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n',1506613077,1,1,2,5),(23,'La Paz',17,':1:17:','Entes obligados municipales del departamento de La Paz\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n',1506613101,1,1,2,6),(24,'La Unión',17,':1:17:','Entes obligados municipales del departamento de La Unión\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n',1506613128,1,1,2,7),(25,'Morazán',17,':1:17:','Entes obligados municipales del departamento de Morazán\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n',1506613146,1,1,2,8),(26,'San Miguel',17,':1:17:','Entes obligados municipales del departamento de San Miguel\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n',1506613172,1,1,2,9),(27,'San Salvador',17,':1:17:','Entes obligados municipales del departamento de San Salvador\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n',1506613192,1,1,2,10),(28,'San Vicente',17,':1:17:','Entes obligados municipales del departamento de San Vicente\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n',1506613318,1,1,2,11),(29,'Santa Ana',17,':1:17:','Entes obligados municipales del departamento de Santa Ana\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n',1506613337,1,1,2,12),(30,'Sonsonate',17,':1:17:','Entes obligados municipales del departamento de Sonsonate\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n',1506613364,1,1,2,13),(31,'Usulután',17,':1:17:','Entes obligados municipales del departamento de  Usulután',1506613387,1,1,2,14),(32,'Alcaldía Municipal de Villa de San Francisco Menéndez',18,':1:17:18:','Alcaldía Municipal de Villa de San Francisco Menéndez\r\n',1506613704,1,0,3,1),(33,'Alcaldía Municipal de Ahuachapán',18,':1:17:18:','Alcaldía Municipal de Ahuachapán',1506614892,1,0,3,2),(36,'Alcaldía Municipal de Apaneca',18,':1:17:18:','Alcaldia Municipal de Apaneca\r\n',1506878552,1,1,2,3),(37,'Alcaldia Municipal de Atiquizaya',18,':1:17:18:','Alcaldia Municipal de Atiquizaya\r\n',1507050777,1,1,2,4),(38,'CENTA',16,':1:16:','Centro Nacional de Tecnología Agropecuaria y Forestal “Enrique Álvarez Córdova” CENTA',1507845388,1,1,2,10);
/*!40000 ALTER TABLE `tblfolders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblgroupmembers`
--

DROP TABLE IF EXISTS `tblgroupmembers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblgroupmembers` (
  `groupID` int(11) NOT NULL DEFAULT '0',
  `userID` int(11) NOT NULL DEFAULT '0',
  `manager` smallint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `groupID` (`groupID`,`userID`),
  KEY `tblGroupMembers_user` (`userID`),
  CONSTRAINT `tblGroupMembers_group` FOREIGN KEY (`groupID`) REFERENCES `tblgroups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tblGroupMembers_user` FOREIGN KEY (`userID`) REFERENCES `tblusers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblgroupmembers`
--

LOCK TABLES `tblgroupmembers` WRITE;
/*!40000 ALTER TABLE `tblgroupmembers` DISABLE KEYS */;
INSERT INTO `tblgroupmembers` VALUES (5,4,0),(5,5,0),(5,6,0),(5,7,0),(5,8,0);
/*!40000 ALTER TABLE `tblgroupmembers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblgroups`
--

DROP TABLE IF EXISTS `tblgroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblgroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblgroups`
--

LOCK TABLES `tblgroups` WRITE;
/*!40000 ALTER TABLE `tblgroups` DISABLE KEYS */;
INSERT INTO `tblgroups` VALUES (3,'Entes obligados no municipales','Entes obligados no municipales'),(4,'Alcaldías municipales','Categoría de entes obligados que son alcaldías'),(5,'oficiales','Grupo para oficiales de información');
/*!40000 ALTER TABLE `tblgroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblkeywordcategories`
--

DROP TABLE IF EXISTS `tblkeywordcategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblkeywordcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `owner` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblkeywordcategories`
--

LOCK TABLES `tblkeywordcategories` WRITE;
/*!40000 ALTER TABLE `tblkeywordcategories` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblkeywordcategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblkeywords`
--

DROP TABLE IF EXISTS `tblkeywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblkeywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL DEFAULT '0',
  `keywords` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tblKeywords_category` (`category`),
  CONSTRAINT `tblKeywords_category` FOREIGN KEY (`category`) REFERENCES `tblkeywordcategories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblkeywords`
--

LOCK TABLES `tblkeywords` WRITE;
/*!40000 ALTER TABLE `tblkeywords` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblkeywords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblmandatoryapprovers`
--

DROP TABLE IF EXISTS `tblmandatoryapprovers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblmandatoryapprovers` (
  `userID` int(11) NOT NULL DEFAULT '0',
  `approverUserID` int(11) NOT NULL DEFAULT '0',
  `approverGroupID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userID`,`approverUserID`,`approverGroupID`),
  CONSTRAINT `tblMandatoryApprovers_user` FOREIGN KEY (`userID`) REFERENCES `tblusers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblmandatoryapprovers`
--

LOCK TABLES `tblmandatoryapprovers` WRITE;
/*!40000 ALTER TABLE `tblmandatoryapprovers` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblmandatoryapprovers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblmandatoryreviewers`
--

DROP TABLE IF EXISTS `tblmandatoryreviewers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblmandatoryreviewers` (
  `userID` int(11) NOT NULL DEFAULT '0',
  `reviewerUserID` int(11) NOT NULL DEFAULT '0',
  `reviewerGroupID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userID`,`reviewerUserID`,`reviewerGroupID`),
  CONSTRAINT `tblMandatoryReviewers_user` FOREIGN KEY (`userID`) REFERENCES `tblusers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblmandatoryreviewers`
--

LOCK TABLES `tblmandatoryreviewers` WRITE;
/*!40000 ALTER TABLE `tblmandatoryreviewers` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblmandatoryreviewers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblnotify`
--

DROP TABLE IF EXISTS `tblnotify`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblnotify` (
  `target` int(11) NOT NULL DEFAULT '0',
  `targetType` int(11) NOT NULL DEFAULT '0',
  `userID` int(11) NOT NULL DEFAULT '-1',
  `groupID` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`target`,`targetType`,`userID`,`groupID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblnotify`
--

LOCK TABLES `tblnotify` WRITE;
/*!40000 ALTER TABLE `tblnotify` DISABLE KEYS */;
INSERT INTO `tblnotify` VALUES (19,2,1,-1),(32,2,1,-1),(34,2,1,-1),(40,2,1,-1),(51,2,1,-1),(53,2,1,-1),(56,2,1,-1),(57,2,1,-1),(58,2,1,-1),(60,2,1,-1),(61,2,1,-1),(63,2,1,-1),(66,2,1,-1),(67,2,1,-1),(68,2,1,-1),(69,2,1,-1),(70,2,1,-1),(71,2,1,-1),(72,2,1,-1),(77,2,1,-1),(78,2,1,-1);
/*!40000 ALTER TABLE `tblnotify` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblsessions`
--

DROP TABLE IF EXISTS `tblsessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblsessions` (
  `id` varchar(50) NOT NULL DEFAULT '',
  `userID` int(11) NOT NULL DEFAULT '0',
  `lastAccess` int(11) NOT NULL DEFAULT '0',
  `theme` varchar(30) NOT NULL DEFAULT '',
  `language` varchar(30) NOT NULL DEFAULT '',
  `clipboard` text,
  `su` int(11) DEFAULT NULL,
  `splashmsg` text,
  PRIMARY KEY (`id`),
  KEY `tblSessions_user` (`userID`),
  CONSTRAINT `tblSessions_user` FOREIGN KEY (`userID`) REFERENCES `tblusers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblsessions`
--

LOCK TABLES `tblsessions` WRITE;
/*!40000 ALTER TABLE `tblsessions` DISABLE KEYS */;
INSERT INTO `tblsessions` VALUES ('12e5e1bd1f967f35af46d32271a389b2',1,1508886047,'multisis-lte','es_ES',NULL,0,''),('a4cec179e8f839fb5133569392453d15',1,1509374979,'multisis-lte','es_ES',NULL,0,NULL),('a846ad14719028eed1020c5ece5e9337',1,1509292661,'multisis-lte','es_ES',NULL,0,NULL),('b57cb3842eaca7b2ca6b1ca5fd90c175',1,1508801838,'multisis-lte','es_ES',NULL,0,'');
/*!40000 ALTER TABLE `tblsessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbluserimages`
--

DROP TABLE IF EXISTS `tbluserimages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbluserimages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL DEFAULT '0',
  `image` blob NOT NULL,
  `mimeType` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `tblUserImages_user` (`userID`),
  CONSTRAINT `tblUserImages_user` FOREIGN KEY (`userID`) REFERENCES `tblusers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbluserimages`
--

LOCK TABLES `tbluserimages` WRITE;
/*!40000 ALTER TABLE `tbluserimages` DISABLE KEYS */;
INSERT INTO `tbluserimages` VALUES (1,6,'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','image/jpeg'),(2,7,'/9j/4AAQSkZJRgABAQAAAQABAAD//gA+Q1JFQVRPUjogZ2QtanBlZyB2MS4wICh1c2luZyBJSkcgSlBFRyB2OTApLCBkZWZhdWx0IHF1YWxpdHkK/9sAQwAIBgYHBgUIBwcHCQkICgwUDQwLCwwZEhMPFB0aHx4dGhwcICQuJyAiLCMcHCg3KSwwMTQ0NB8nOT04MjwuMzQy/9sAQwEJCQkMCwwYDQ0YMiEcITIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIy/8AAEQgAlgDIAwEiAAIRAQMRAf/EAB8AAAEFAQEBAQEBAAAAAAAAAAABAgMEBQYHCAkKC//EALUQAAIBAwMCBAMFBQQEAAABfQECAwAEEQUSITFBBhNRYQcicRQygZGhCCNCscEVUtHwJDNicoIJChYXGBkaJSYnKCkqNDU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6g4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2drh4uPk5ebn6Onq8fLz9PX29/j5+v/EAB8BAAMBAQEBAQEBAQEAAAAAAAABAgMEBQYHCAkKC//EALURAAIBAgQEAwQHBQQEAAECdwABAgMRBAUhMQYSQVEHYXETIjKBCBRCkaGxwQkjM1LwFWJy0QoWJDThJfEXGBkaJicoKSo1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoKDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uLj5OXm5+jp6vLz9PX29/j5+v/aAAwDAQACEQMRAD8A9UAUjvQBzTe1KBnvXWcrHDI6U8SuKFUDrUqhewpXBJgkrmrKFm6mowOOAKlRitQzSKGlzGTnHX5lGT9CP8P8mGRlEgOzIP41Ykkj4LEbhyDjOD0qMSKqk4UKDluOBnv/AJ96UdAlroL5fmINwK98Upt0K/d5pjtIp+Rfk/zzTkLk/OxX2AqtSdLkf2NieCMfSnpYrE28v3yRgYqUyqAcMc+4qo7M/BPH5VScn1JfLEfMw3/ejVR0wSR/KowQGIznPPFLGhbg9acbVxjbxzmq0WhFm9RSy7QFXnv71GfpQU24BPNKBkU1YTuJipoIPMbkcetM4U8kA+9TB2VeM/hSk+xUVrqTG3iVh8pIPpSG3Vj8oIpkcrdiw+oNKZ5C20hvrkYrPU093sPW2wfv/pTtqLnJ5FV5ZtnLOg/3nxT1dWQOCGHqvNGoadCbI+tL8uOlQI/mH5dw/wB6Nh/OlLOW2+XIP9obcfzpFDyy7cECioZg6LxtIHVmfb/SiiwrmQGHrS7+eKqhmIyXwB19f5Cl813YqAOOwIJ/HBrS6E4l4Sn0FSrIO+Kpq2FIYMPwyf60u3LD5g/cK5A/9lpOwtTQDqBncBUiyArkFSPY1TSINyw2n/YkbH9KeqSg8Spt91JP55qCyxvR+oJ+oIprKrc7Sm3kNnoajkgSUDeQT/ug/wAwakhRYVwGJH0A/kBQBELoRs2ZIduegbOPfgdP5c09LkAPuDhc8YjZh1PII7UksSsjbWkVyDgiRhipIn8oBGUIiKOM5A/HuKAHeWfvH5/YLg/qackYILN5kQH94r/9eho4mxviRwOMlc4qVAqrhQFA7AYpNsaSI1uMuEQwt24m5/lVnzVjIJ3nPopb+VNBX1pQ4B6VLKCWIzYKiMjrh4sn+YqKK3cNjY6f7Xy4/Dkmp/MFOEuRx0ou0DSeo2OKRDguZM/32A/LAprWCFi+5wx7CRiKl8w07PHDfnSu0OyZTltIpF5MZI/vpu/rTY4tg2gpjtsTbj9TVxkV+uAah24NWmQ4kSxMH3ec5H90gY/lT3hV8bi/4OR/I08UuKdxDfLXZtIyvo3P86RI44/9XGi/7qgU/BpCDg0AIaKRcugYYIPII9O1FAHzRDrep6eIobfUJ/s3zeUIZdwVAeeB1wPbP1rovD3iB7Ge4vQ7SxOojYTTjPyj5SM8NkcevH58d/bNnNDEZLhGKLh4pIy28AjcpbjqOQQOxxjNVZdbhAdYLhcKzHf5Ry2Pb34PqOeTmuO8lqjraj1PY08eWzwDfbXEUhUndhWUkc4GDnn6Vt6Lr8WqxFo3V9rMGIyAOTt698Y/yRXj9p4itbi3MRn2zRkYSKLfvPP3VPQD2IxngVpeckFwP3oilMmCTlCc4ywPBY8j5ffFUq8k/eJeHjJXiz2hGULwBzzxQD1wxGffNeXWPifVLHaI52uI9pxDIRJtywAyeG74xntjtXRweOYBvF1YzAoCS0RVhjjBOSMZzW8a8H5GMsPUW2p2IZgBhvxYZp4kO7oMdzmqFlqVnfD/AEadJCFDFQfmA+n9auVto9TB3WjJvMx0Un6U5nUgAhgexA6GoQDUycUmNNsEkCMEdkUBeg4x9Pb+VTjjgE47e1MwGXGPy7Uw7IySY88dQM9PT/CoZZZwfalxmmAE/Q09YwB1P55pFCqD0xThwelIEx/ESfpTs8cHH1FK47DgM07pUeaAWz90fn/9agZMDQFH0NNApwBpCAqvpUc0kNvGZJZFRB1LHGKwtY8aaToV2ba9eVXG0blUEfNjHfPf/PFY2p+OdPubizs4DHNY326OW4YkbB/EB6nB49/0zdWK0uJtHX2F/aapbfabOUSRZI3D2pmo2Ml/B9lEvlwSKRKQMsR2AyMY9favKx40tfCtxdwaZHKtvIRIEkCZVwu0/dyAPlBOec5yamufipeT6hbw6V5UsLADEgAYnjOSOB17D14PFZrExV79AUkj1SWVYvkX7wH3R/niiuP0zxVHc372d0kZv1DZi+0DYOoYDjJP+8B9OtFbqpHuB8wqwAxj0/lS4dfkCMoYYbiokl7kVYluWbYFJI6YrKxrcqWt7Ja3Kyb2BB+8OSPzrsdH8TJIgjlZlKx48wkspAB/h+hz0Pr1riZ4XjPzIw+oxUSO8fKkinKKY4zcT2CDcltFPA+6KQtKrB/lGPc8E9eMA5A9aaWHlxoWUv8AMUSNg+4YY5+U4wOf8815/pPia5snYPI22TAfHII4zkd+M9x9fXrrHXLC+t13rHHKXBkyMhufvHAz3HXuQAeOcJQaZ0QmmrG9Deva+ULedo2jkyAhC4yRk5I4+X0OCD9c9Vp3jnycR6km4AAlo8bwPXA4POPTj1xXFs9qpG24f5ScKrAq2STnHYe2Pb6p51lIiq0qbjgybQV24PTBHPY84xjpxyRnKOxU6cZL3j2bT9TsNTjElncJKuM8cH8jyKvDFeIWty1lE/2e4Ec25TC0eRt9zjG0H6HOecV1WjeObu381NVBuEVgC6KAyDHfHB6Z5x3rojXT+I5ZYdr4T0gGn5DDB6Vztl4u0i+j3wzuWHWNkIcdOcdccj69q1rXUbW73eTJuK43AgqRnpweex/KtOeLdkzKzW5c3bWA6qamXpnPHbNVvMBz/WmrKVkyCSp7U7BcudOtJnnioVuFeQoD91Q34HP+FPDUgJBj/GpABVQ3AFuJlG4HBwD2NPimkZyGgdF7MSpz+RoAsg1Q1SR5YGtYrSWfzB8wRgg255+Y8Z9v5VcI3KQc4IxwcH868p8XalfeD9Yjmt9cQQySSOtq4MjRKR0Gc9ffFZ1Zcsb2B6HnnjWWeLVXsgfIWB/LAZhI2w4IJYAA8ED8O9cwNQ8giNJVJjOQzH8+1TazqT6jqE1zPJveV8ud5OewySSawyoaYiLCsp45/KuWEE1qibXNi6vZZQPMlLlkKttAB2+n59vxqjb3LWsglSbcyAMuGK4NVprgRwCMS73P3mAOP5fWq6sHXjAIPXp+laRgrDSN2xvr29v4ZUf9903uwA655JwPXr1orMilVYpGc5LYA3Hp+dFRKkm9hWBIxkccbqkhj23qDtuFTQhf1FTQRxm4LvJgrggevIrpNSS9wQP92uZRmaRQTkE85roruZTHkMPu+tc7CP3y59aEJF3yUP8ACKYjtDdqIyUwRggnIq1GOeMEVUbm+/4FSQzpNO1u+0xj5HlujABo2XGencc9v0rYt/EEF8iiKJ1uolyYy5Ysc/wjGOe/PrnPJrlhgDdn9aSS3ih2E7myPrUcqZaqNHdx6kkV4I0lUiFlCqxwQSRj5TwvA9eeucVInnWrLPMJY/M3Kjg/K2OgBDcH2HPQ9xXDShQkwPnmWFM4Y/Lg49/cVaZFgtoZrm4ZYnbCdT8wwT0+oqXFItVWdsdTtoX8+S4iDqcjy5Rx1I5zzx7k59c4rZt/FMZz9pnjnQEM7Q3CBlGGwCVOGxk/jg8EivMrS3gZrq5aABYbjbnafl444IPcHrzWstmt7BJNHp00U0YIf7I4h4IyPkAPGM9hye/SspKNwdXo0ey6Z4x0t4rj7RczoFZX82aMgOGHbH0PYdvUV0MOoWU24x3kLbF3thxwvcn2r5cGmXGoYWBXDLHvJeThsjIAz079fzrU0268TaOjRm1NzAQVMcp3rjp2PocV0qryq1zLljJn0DZ+K/Dt95ktvq9sCMKTK3l569N2M9+la1teRXMQmgnjkjJIDowYE5x1HvXzvbXWn3M7JNb3WlTHGQ6eYnTng4I56da6PTvEUvhDWJreyuo720lTjkFXcgfNweDxjGemR16Sq8vtLQhpo9h0mUXWl2UxbdugRgB0GVH61fDjdjNeXaZ43u7aytrQRQqkCCMFgx3BRtGefpVmH4hXj3MkZgt9qDqFPX0+9WvOieZHpe8j6V5p8VPEOippw04pDd6sGBiTAbyh3LZBXJBIAPI3ZGCAayPEvjq/e0fyL7yCrYAtkI64+8272PT1ryu5urm9uWmZmeQvkSFst19/SonPTQd7mTcL5SlshXZiWQDG3moXdDFuz+8JJ6D8q1dTs5Xdpyi7HG4CMj5OemB0HOBWFICZGyMc1EGpK4LUeyqYgQQzd85zSKu1C/IKngnof881GWIGOwoUnBBrSwx0s8kqqrHOOB9PSimkcHbn6UUDNKGXavflhz9Ksjy3Ep2ncAAD6VQ+ZOBgjHTripUlm+4F3BuMY5FFxlzyY26xxjkYynWo2tbc42xLn1HFO3OvDQuH9QOn4VHI8u7vu57YP5Vmr9wuIlrAXIKfkas/2fYkb1Q5PQFjnP8AKq0czs2XwFI445qVZC3OMZP93rzVaiJl06F2VR0Y4HPNbOn6PHqdsZjvDxlgNp6jAxxjqSQPxrKgd2lC4+U44OcE9a7/AMEyxpoM+VXel0+GYA7eFH4ckVE5uMbgldmX4g8MJYeG73UJEK3RdI3w4KlNy4IHY5Arz2/up5ZWgeaRoY3bYjMSF+gr1/x2xn8MGOMMx8wbwEOflyc/pn6VwCeEJ7u4ty8sduboB4/NbG7P60U5xtdsrldzNtr50tZEMsrPM2+T5yN3ufWtfw7OIzNlB8o3NIIw/lgHrj0zx/Okm8JyW1m0zTIlxAWV4TICzqM/OBjp+fQ+4p7WB0/T1t5PKLz/AL1mSRNyFd3yexwc474AHQ1MpRkvdJae5oNcQQyK0Ey7Io0UFRgFgoGefx5x2FQN4vk37Y4vJYEcqcgY9cnJ/wA+vGfZyiGR44n2kEq0soyFyehGDkcDB45NM1S2imnluY3dlZ+WmYeYDjpweQOmcenTNQqUb+/qSua1md7ZWltcFJI4o9jqCd+W2nyp3xxgf8sk/BvxrjLxZV1a9MUDiD7S4X5ThRuP8hWr4W1U28Est5xBDujSOJ9hO5Gj3c9SA49/mq7FqUOn65cW8YD2t1CQpjydwY4xj1GTx09OtTGrH+HbYr2dlzFqzsU1FboRTI9wsjqYVONo3DnOPas92s/NTzLhDOz4YE7VGcEE9OMZNTx6e0tnby/6RJFI/wBolCKTtXCknPbJ/lUS+FhdXiXDTF18vJkMo2A5GARjPTPB9B6YrXmutGVyXjoYniIeTApVGRsjMbc5yM7qw4lldwMIpVct34zj+tbl3dW9tIkQS2vpFJVzI0jqM9QOg6cZGPbsawrE+TcNJK6FRgjkZB9/atbNqzI5bEsd8bB2ZQGcfL8w7fT/AD3qiGa+vciAyPIMFY1yT+ArtU1/w7FFNLL4RtZVVAAZLyQnGQO/Uk/kPxJxvD7Wz68L21gaCJJC2xm3CNCCMZxycn8gepqFBR1Dl7GBLYNbsyzW08ZI4EikGrUGhi6liihlAZoGmLOOOO35j9a9KXULOGQk3ccr581gs+45HXhFx65BNczLfmDxPPKWV0eFkB5Py7+3PPT6c05c1rxYcr6HL/2BdiV1CmQDnzIlLAcZ5x2wevT3orrkvyZI/LURqyYYDOMg7uPTnpjpzjrRWXtZorlkapsZJ5Q94ftDDjdIgk/Rj/nNVF0aEzSyy2VszY+UlWH4gLgA1uCSGQDiVFP8TD/AU8CAY/euVHXIHH61NzdxRy7aZG8LzS20ivG3yssgHGSfu8k+mf0qBrWFPKluYZVjcFdzDhjjoM9812mwoq/PGSwyQQR+pAFElijQhotPuZHboduB+BOAfzqSfZxPPhpunB1Zpim07gSm4H8jVq2tbF3cQyLx84cng4Hp/n8K7E6KLuJVnjbI4VTHnYv4kVXfwhY7H2STRbs48qPGR/n1obfcl030Oc/sxIEk2ICIsMCD2J45GfX6j27W9FvZLWyktpLaTDzGVGLcZOBzj/Pf0q7d+CR5wltdVNtLjkDAyAOMDIwalfw/4ijtYympxXyLjakuV+nTOf8A61Du1ZMlRkiCe/miKyvaRLAVKySSx+aWLDkfOTjqOg56Gs2SdZpWYTRRQqD5SKVIAA2nAyMZzn8DU8umeIJ0MEumJIxO7ekhQg+xbp2NZ02g63LsRbKdAoJwsSluQQQCOcEHGOnWkqbe7FJSZZs4oZ7z55cxpyQxCq2Mn3BBK9MGmaxBKkm+0t3cHAmAVlwPvYIxjHQ5GRxyBxRpNlrIDQQWckOY9szTBQDgDGA2OegBz9OhNdPFa3l0YYpkeKNUGVBU4OMnbt9845xgDpxStKEr7oi0jzaSQpPNKsh37+Gx97k8+3QVu6PPLdawY7uSNS6lSZCCgABx3AxwAMnA4rq7nwxBct5kmRIzFizL0Y4yffkVNF4Z05Lt7iMKkj9QhPOTz1J69xwPat+bmWxfIzlLPw60H2mOSQlpEKjau4ds8j3/AJDFdJo+jS2cAR03TIw2yABVKfLwRtJJypOR7V0aW6ISTgluT2qwu1eeAKiNKTd5MqKcSJIQrKwUAqCFwNuB9B396jubH7ZDMm5hvUgsJSuM9+/8qtq6EF2YKg43H19PrSC6hYBRImB2yOtaqKjsVqeY3vhW7063kuZL3TAsasxZCVkPtjpnFZraffXFvGRpdyylSyyJAzAjnk4DY6dMdPrmvXnkTYWBB/Gqn2Oyuolaazt5cj/lpGGP61XMwTtseWW2iXV5pK3STWzwSEr5aEmThv7uMnp+QzTtMiGn290Du6kA4x/AG6dRXotz4d06WRJLaFLG5XG2eIYXg9CgIHP9PrnH1CCbS2VdQskaDODeQAMPYkfw/nz2yKynKXTUWq1RxWivuuBnIL71z+H/ANc1oSwwm0WRXTzhtzyMgHef6CtyOPSZ9syS2pbGQfusKzIlgk1DUH8tnQiMpgdcA5xms1WvfQcW2ndGbMpVEDYUk5B9qK1b8RxOWtwsyhBlXIBAzznOec0VSk2rpD5juZbOSEYe2Xnsvy5/Ks+ex3lcQFWJxkNkflTtO8R3DSEXwtms1Tl9hV89s5JHPTH61HN4ka63Lo1jNKFwGZoy4B/4CeKz5H0NOZdRbfTljVQt5MkpfksF2Y7AcduOtaQS4aIKNTX5TxtjHI/PkfhVG3vL42hkuSiZ6OsJcD6kEAHpUTag+9AksmOd2IkUsc+vNNcxLaL4a7gDNJeQkbsAtH1PXAxgmp47mORXWWe1fZnjzMbSD0Oef896wpp5Lj5ZJJSmehfpjp0xTEUKAqR5A9KpRfUVzae/sEdfLiaQE8lFK4/xpH1OAjZFEQx7k/yrKU84CgY68dKsIpyGJ4q1FCuyz/aE/liMNtGPofz600ytISzMxI4yaiHHI6UoljDgMV3MOB3NUBMo5+9n1xUySEccgA1V2kcBsc8HFOBkA5GeeWB5/WgC9vPY8+uaVSAScDJ5Jx1qqjCPK73OAAWfqf6VIJA3APPYA8mlsOxM5UgZY/gSKqXGmzvGj3FxmNuViaNSSPr2HvWkAlod0oDS4+Vey/Wqp+ZyzMWYnvU8z6DsluYFxoE8khcTofQbcBR6Dmqcmh6irbVjDgd1YY/Wusyd2cFiB2pwfj5gVAPc1aqNEuKOLk0TUVQk25OPRgT+hqg1ndDIMMnHH3TxXorZxgdzVVo8TcHgkr+PUf1qlUYuU88kjniODuU9wcjFLDqeoWwxFdThc8KJDgfhXo+wuOeo/KoRCqkkKfoBn+lL2nkPkPM5IY7+RvOcWspyRIkfyk89QOn4DHPbFRTWN1aTMj6gHA+6CSQ2eeOor0ObTtOnkLvDGz45GSP0qo2haWblJEd4JFbcvlyYIPYjvn6U1OLFyM88nS8cndGze5bNFegy+GYtRvJJGnaP+NmXnPXryST0ycZPUk5oqrroTyvqRebaGDyfs3nDO7dcnLg+xULx7dOKkbUrt4UiDlY4+FVFCgY9h9KrKY9wABJHYHtUhbbghAvtis9CiXzHnk3yysz9y2Tn8TUiR7uRIn0pikAghcnPOfWnF8EnaAT6A/zpDJGVhyWGB06UfKBgKMU0MQuXbn3oyTgg5+tAEyFQAeDkelKHzwARiq5Zj1OB9OtSJnOAR9TTsFyfOQOcUKQBySST1zTFk2sM8kHpT925vMbA4HIGOKAHB8Agc8UpkYFRk+/Tgf5zTQ25gobjPUCrdlYSTkvlVjXrI3Yf/qqZSSWo0rkdoj3cpSMN1xjBGPz/AJ1oEx6euI8STfxPjIX2WmzXsUSeVacKfvSDhn5qk84X7px71CvLcu1tiyGydzKST1J65phl3cA4bOOKpeYWOMknpTxMTFsAxj7xJwaYiwjZwWYhSe9SFkU/KOOvFU0lVYiuV2k88dRTllLE8KBn0piLxkBA7881Qefy5pOMEBZAAfwP6VKVCyAAHGScdATVGZ1W/RQcKUIpoDSE3STJ2N7053QMDkZ7jvVGBvNtgrICV4yOcdf6VLBIq/IWBcZ69/ek0NFzfG4GQufcHBqJoUduVj49BUZZu4JDdKjLu5VQ209iev0pWGPkDRqBgFM5wV4JopjuqoS06l+cA8UUDOfLFUzxxwMCpQm7aedx96KK0MAUEHOR14wMU8B2ydxJAz1oopDJoopJpkhVxvdgoLdBnigswB6ADr3oopiJETO334qQoqs20EAHABNFFIaEJJ2qAOgOaBE0m75+ABkevXv+FFFAzR0vTjcy+c7fu1PTPLd8fSn3d61wxt4P3cSHbt6Z/LtRRXPe9R36Gq+EoZYDIxnHXvVM3WX2kHPQ+9FFbEEzLti3YznAPPT/ADmmxHEvlNyOm7qeaKKYCsMxs5UHgnpUyMQTtAwG4GfQ0UUgQhmYXGVJGR69vrVWY5uoWIBySP0ooqkJli3c/a5UYkgYNJKWjmRh8vzbMA9jRRS6j6Ftvv7SW+X3pruVzuAzgdBx6/1oopFMzriYhhnn0yKKKKohn//Z','image/jpeg'),(3,8,'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','image/jpeg');
/*!40000 ALTER TABLE `tbluserimages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbluserpasswordhistory`
--

DROP TABLE IF EXISTS `tbluserpasswordhistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbluserpasswordhistory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL DEFAULT '0',
  `pwd` varchar(50) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `tblUserPasswordHistory_user` (`userID`),
  CONSTRAINT `tblUserPasswordHistory_user` FOREIGN KEY (`userID`) REFERENCES `tblusers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbluserpasswordhistory`
--

LOCK TABLES `tbluserpasswordhistory` WRITE;
/*!40000 ALTER TABLE `tbluserpasswordhistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbluserpasswordhistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbluserpasswordrequest`
--

DROP TABLE IF EXISTS `tbluserpasswordrequest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbluserpasswordrequest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL DEFAULT '0',
  `hash` varchar(50) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `tblUserPasswordRequest_user` (`userID`),
  CONSTRAINT `tblUserPasswordRequest_user` FOREIGN KEY (`userID`) REFERENCES `tblusers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbluserpasswordrequest`
--

LOCK TABLES `tbluserpasswordrequest` WRITE;
/*!40000 ALTER TABLE `tbluserpasswordrequest` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbluserpasswordrequest` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblusers`
--

DROP TABLE IF EXISTS `tblusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblusers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) DEFAULT NULL,
  `pwd` varchar(50) DEFAULT NULL,
  `fullName` varchar(100) DEFAULT NULL,
  `email` varchar(70) DEFAULT NULL,
  `language` varchar(32) NOT NULL,
  `theme` varchar(32) NOT NULL,
  `comment` text NOT NULL,
  `role` smallint(1) NOT NULL DEFAULT '0',
  `hidden` smallint(1) NOT NULL DEFAULT '0',
  `pwdExpiration` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `loginfailures` tinyint(4) NOT NULL DEFAULT '0',
  `disabled` smallint(1) NOT NULL DEFAULT '0',
  `quota` bigint(20) DEFAULT NULL,
  `homefolder` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  KEY `tblUsers_homefolder` (`homefolder`),
  CONSTRAINT `tblUsers_homefolder` FOREIGN KEY (`homefolder`) REFERENCES `tblfolders` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblusers`
--

LOCK TABLES `tblusers` WRITE;
/*!40000 ALTER TABLE `tblusers` DISABLE KEYS */;
INSERT INTO `tblusers` VALUES (1,'admin','21232f297a57a5a743894a0e4a801fc3','José Mario López Leiva','marioleiva2011@gmail.com','es_ES','multisis-lte','Usuario administrador',1,0,'0000-00-00 00:00:00',0,0,0,NULL),(2,'guest',NULL,'Guest User',NULL,'en_GB','multisis-lte','',2,0,'0000-00-00 00:00:00',0,0,0,NULL),(3,'marito','091808fcfe95ad6473dec65aedd05e90','José Mario López Leiva','marioleiva2011@gmail.com','en_GB','multisis-lte','Úsuario de prueba no administrador',0,0,'0000-00-00 00:00:00',0,0,0,1),(4,'OI_MOP','091808fcfe95ad6473dec65aedd05e90','Oficial de Información del MOP','oip@mop.gob.sv','es_ES','multisis-lte','Nombre del oficial: ',0,0,'0000-00-00 00:00:00',0,0,0,9),(5,'OI_RREE','091808fcfe95ad6473dec65aedd05e90','OI del Ministerio de Relaciones Exteriores','rree@iaip.gob.sv','en_GB','multisis-lte','Oficial de información del Ministerio de Relaciones Exteriores.',0,0,'0000-00-00 00:00:00',0,0,0,8),(6,'OI_BCR','091808fcfe95ad6473dec65aedd05e90','Flor Idania Romero de Fernández','marioleiva2011@gmail.com','es_ES','multisis-lte','Oficial de información del Banco Central de Reserva',0,0,'0000-00-00 00:00:00',0,0,0,13),(7,'OI_SAN_FRAN_MENENDEZ','091808fcfe95ad6473dec65aedd05e90','Manual Arturo Ojeda Rivera','uaci3055@hotmail.com','es_ES','multisis-lte','Teléfonos 2429-7200, 2429-7204; 2429-7203\r\n',0,0,'0000-00-00 00:00:00',0,0,0,32),(8,'OI_ALCAL_AHUAC','091808fcfe95ad6473dec65aedd05e90','Marcel Ernesto Contraras Árevalo','jmlopez94@outlook.com','es_ES','multisis-lte','Teléfonos: 2487-4835\r\n2486-3103\r\n',0,0,'0000-00-00 00:00:00',0,0,0,33);
/*!40000 ALTER TABLE `tblusers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblversion`
--

DROP TABLE IF EXISTS `tblversion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblversion` (
  `date` datetime DEFAULT NULL,
  `major` smallint(6) DEFAULT NULL,
  `minor` smallint(6) DEFAULT NULL,
  `subminor` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblversion`
--

LOCK TABLES `tblversion` WRITE;
/*!40000 ALTER TABLE `tblversion` DISABLE KEYS */;
INSERT INTO `tblversion` VALUES ('2017-09-12 13:54:46',5,0,0);
/*!40000 ALTER TABLE `tblversion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblworkflowactions`
--

DROP TABLE IF EXISTS `tblworkflowactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblworkflowactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblworkflowactions`
--

LOCK TABLES `tblworkflowactions` WRITE;
/*!40000 ALTER TABLE `tblworkflowactions` DISABLE KEYS */;
INSERT INTO `tblworkflowactions` VALUES (1,'Aprobar (agregar al índice de reserva)'),(2,'Rechazar'),(3,'Desclasificar documento'),(4,'Solicitar corrección'),(5,'Corregir');
/*!40000 ALTER TABLE `tblworkflowactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblworkflowdocumentcontent`
--

DROP TABLE IF EXISTS `tblworkflowdocumentcontent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblworkflowdocumentcontent` (
  `parentworkflow` int(11) DEFAULT '0',
  `workflow` int(11) DEFAULT NULL,
  `document` int(11) DEFAULT NULL,
  `version` smallint(5) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `tblWorkflowDocument_document` (`document`),
  KEY `tblWorkflowDocument_workflow` (`workflow`),
  KEY `tblWorkflowDocument_state` (`state`),
  CONSTRAINT `tblWorkflowDocument_document` FOREIGN KEY (`document`) REFERENCES `tbldocuments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tblWorkflowDocument_state` FOREIGN KEY (`state`) REFERENCES `tblworkflowstates` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tblWorkflowDocument_workflow` FOREIGN KEY (`workflow`) REFERENCES `tblworkflows` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblworkflowdocumentcontent`
--

LOCK TABLES `tblworkflowdocumentcontent` WRITE;
/*!40000 ALTER TABLE `tblworkflowdocumentcontent` DISABLE KEYS */;
INSERT INTO `tblworkflowdocumentcontent` VALUES (0,1,51,1,1,'2017-10-12 17:51:49'),(0,1,60,1,1,'2017-10-18 15:17:04'),(0,1,61,1,1,'2017-10-18 21:08:48'),(0,1,66,1,1,'2017-10-20 15:04:31'),(0,1,67,1,1,'2017-10-20 15:06:13'),(0,1,77,1,1,'2017-10-30 00:30:42'),(0,1,78,1,1,'2017-10-30 00:31:56');
/*!40000 ALTER TABLE `tblworkflowdocumentcontent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblworkflowlog`
--

DROP TABLE IF EXISTS `tblworkflowlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblworkflowlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document` int(11) DEFAULT NULL,
  `version` smallint(5) DEFAULT NULL,
  `workflow` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `transition` int(11) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment` text,
  PRIMARY KEY (`id`),
  KEY `tblWorkflowLog_document` (`document`),
  KEY `tblWorkflowLog_workflow` (`workflow`),
  KEY `tblWorkflowLog_userid` (`userid`),
  KEY `tblWorkflowLog_transition` (`transition`),
  CONSTRAINT `tblWorkflowLog_document` FOREIGN KEY (`document`) REFERENCES `tbldocuments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tblWorkflowLog_transition` FOREIGN KEY (`transition`) REFERENCES `tblworkflowtransitions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tblWorkflowLog_userid` FOREIGN KEY (`userid`) REFERENCES `tblusers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tblWorkflowLog_workflow` FOREIGN KEY (`workflow`) REFERENCES `tblworkflows` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblworkflowlog`
--

LOCK TABLES `tblworkflowlog` WRITE;
/*!40000 ALTER TABLE `tblworkflowlog` DISABLE KEYS */;
INSERT INTO `tblworkflowlog` VALUES (4,19,1,1,1,1,'2017-09-22 09:19:41','aprobado'),(16,32,1,1,1,1,'2017-09-28 15:50:34','dd'),(18,34,1,1,1,1,'2017-09-28 16:33:09','uu'),(29,40,1,1,1,12,'2017-09-29 11:08:09','por favor el documento tiene que ir firmado'),(30,40,1,1,1,14,'2017-09-29 11:10:23','HOY SI ESTA BIEN'),(31,40,2,1,1,1,'2017-09-29 11:13:29','OK'),(38,56,1,1,1,1,'2017-10-15 18:31:37','dads'),(39,57,1,1,1,1,'2017-10-15 20:47:22','bien hecho'),(40,53,1,1,1,12,'2017-10-15 20:55:16','por favor, corrijalo'),(41,53,1,1,1,14,'2017-10-15 20:55:43','ahora sio'),(45,58,1,1,1,1,'2017-10-16 10:01:19','está bien esta acta'),(46,63,1,1,1,1,'2017-10-29 19:05:13','oki'),(47,63,2,1,1,1,'2017-10-29 19:16:35','oki'),(48,70,1,1,1,12,'2017-10-29 19:27:20','añada eesto'),(49,70,1,1,1,14,'2017-10-29 19:29:16','oki'),(51,69,1,1,1,12,'2017-10-29 19:43:42','POr favor corregir'),(52,71,1,1,1,12,'2017-10-29 19:58:26','Necesita actualizar el archivi.'),(57,71,1,1,1,1,'2017-10-29 20:27:38','ok'),(58,69,1,1,1,1,'2017-10-29 20:27:49','ok'),(59,72,1,1,1,12,'2017-10-29 20:30:02','corrijalo'),(60,72,1,1,1,14,'2017-10-29 20:37:46','al fin ok'),(65,68,1,1,1,1,'2017-10-29 21:13:17','iui');
/*!40000 ALTER TABLE `tblworkflowlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblworkflowmandatoryworkflow`
--

DROP TABLE IF EXISTS `tblworkflowmandatoryworkflow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblworkflowmandatoryworkflow` (
  `userid` int(11) DEFAULT NULL,
  `workflow` int(11) DEFAULT NULL,
  UNIQUE KEY `userid` (`userid`,`workflow`),
  KEY `tblWorkflowMandatoryWorkflow_workflow` (`workflow`),
  CONSTRAINT `tblWorkflowMandatoryWorkflow_userid` FOREIGN KEY (`userid`) REFERENCES `tblusers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tblWorkflowMandatoryWorkflow_workflow` FOREIGN KEY (`workflow`) REFERENCES `tblworkflows` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblworkflowmandatoryworkflow`
--

LOCK TABLES `tblworkflowmandatoryworkflow` WRITE;
/*!40000 ALTER TABLE `tblworkflowmandatoryworkflow` DISABLE KEYS */;
INSERT INTO `tblworkflowmandatoryworkflow` VALUES (1,1),(4,1),(5,1),(6,1),(7,1),(8,1);
/*!40000 ALTER TABLE `tblworkflowmandatoryworkflow` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblworkflows`
--

DROP TABLE IF EXISTS `tblworkflows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblworkflows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `initstate` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tblWorkflow_initstate` (`initstate`),
  CONSTRAINT `tblWorkflow_initstate` FOREIGN KEY (`initstate`) REFERENCES `tblworkflowstates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblworkflows`
--

LOCK TABLES `tblworkflows` WRITE;
/*!40000 ALTER TABLE `tblworkflows` DISABLE KEYS */;
INSERT INTO `tblworkflows` VALUES (1,'IAIP',1);
/*!40000 ALTER TABLE `tblworkflows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblworkflowstates`
--

DROP TABLE IF EXISTS `tblworkflowstates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblworkflowstates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `visibility` smallint(5) DEFAULT '0',
  `maxtime` int(11) DEFAULT '0',
  `precondfunc` text,
  `documentstatus` smallint(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblworkflowstates`
--

LOCK TABLES `tblworkflowstates` WRITE;
/*!40000 ALTER TABLE `tblworkflowstates` DISABLE KEYS */;
INSERT INTO `tblworkflowstates` VALUES (1,'En aprobación',0,0,NULL,0),(2,'Desclasificado (fuera del índice)',0,0,NULL,-1),(3,'Aprobado',0,0,NULL,2),(5,'En corrección',0,0,NULL,0);
/*!40000 ALTER TABLE `tblworkflowstates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblworkflowtransitiongroups`
--

DROP TABLE IF EXISTS `tblworkflowtransitiongroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblworkflowtransitiongroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transition` int(11) DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  `minusers` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tblWorkflowTransitionGroups_transition` (`transition`),
  KEY `tblWorkflowTransitionGroups_groupid` (`groupid`),
  CONSTRAINT `tblWorkflowTransitionGroups_groupid` FOREIGN KEY (`groupid`) REFERENCES `tblgroups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tblWorkflowTransitionGroups_transition` FOREIGN KEY (`transition`) REFERENCES `tblworkflowtransitions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblworkflowtransitiongroups`
--

LOCK TABLES `tblworkflowtransitiongroups` WRITE;
/*!40000 ALTER TABLE `tblworkflowtransitiongroups` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblworkflowtransitiongroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblworkflowtransitions`
--

DROP TABLE IF EXISTS `tblworkflowtransitions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblworkflowtransitions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workflow` int(11) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `action` int(11) DEFAULT NULL,
  `nextstate` int(11) DEFAULT NULL,
  `maxtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tblWorkflowTransitions_workflow` (`workflow`),
  KEY `tblWorkflowTransitions_state` (`state`),
  KEY `tblWorkflowTransitions_action` (`action`),
  KEY `tblWorkflowTransitions_nextstate` (`nextstate`),
  CONSTRAINT `tblWorkflowTransitions_action` FOREIGN KEY (`action`) REFERENCES `tblworkflowactions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tblWorkflowTransitions_nextstate` FOREIGN KEY (`nextstate`) REFERENCES `tblworkflowstates` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tblWorkflowTransitions_state` FOREIGN KEY (`state`) REFERENCES `tblworkflowstates` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tblWorkflowTransitions_workflow` FOREIGN KEY (`workflow`) REFERENCES `tblworkflows` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblworkflowtransitions`
--

LOCK TABLES `tblworkflowtransitions` WRITE;
/*!40000 ALTER TABLE `tblworkflowtransitions` DISABLE KEYS */;
INSERT INTO `tblworkflowtransitions` VALUES (1,1,1,1,3,0),(12,1,1,4,5,0),(13,1,3,3,2,0),(14,1,5,1,3,0),(15,1,5,4,1,0);
/*!40000 ALTER TABLE `tblworkflowtransitions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblworkflowtransitionusers`
--

DROP TABLE IF EXISTS `tblworkflowtransitionusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblworkflowtransitionusers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transition` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tblWorkflowTransitionUsers_transition` (`transition`),
  KEY `tblWorkflowTransitionUsers_userid` (`userid`),
  CONSTRAINT `tblWorkflowTransitionUsers_transition` FOREIGN KEY (`transition`) REFERENCES `tblworkflowtransitions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tblWorkflowTransitionUsers_userid` FOREIGN KEY (`userid`) REFERENCES `tblusers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblworkflowtransitionusers`
--

LOCK TABLES `tblworkflowtransitionusers` WRITE;
/*!40000 ALTER TABLE `tblworkflowtransitionusers` DISABLE KEYS */;
INSERT INTO `tblworkflowtransitionusers` VALUES (1,1,1),(12,12,1),(13,13,1),(14,14,1),(15,15,1);
/*!40000 ALTER TABLE `tblworkflowtransitionusers` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-10-30  9:11:28
