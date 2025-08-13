CREATE DATABASE  IF NOT EXISTS `tqfood` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `tqfood`;
-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: tqfood
-- ------------------------------------------------------
-- Server version	8.0.42

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `khachhang`
--

DROP TABLE IF EXISTS `khachhang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `khachhang` (
  `Makh` varchar(20) NOT NULL,
  `Tenkh` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Matkhau` varchar(100) NOT NULL,
  `SDT` varchar(20) DEFAULT NULL,
  `Diachi` varchar(255) DEFAULT NULL,
  `Ngaydangky` date NOT NULL DEFAULT (curdate()),
  `Vaitro` int DEFAULT '0',
  PRIMARY KEY (`Makh`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `khachhang`
--

LOCK TABLES `khachhang` WRITE;
/*!40000 ALTER TABLE `khachhang` DISABLE KEYS */;
INSERT INTO `khachhang` VALUES ('KH02062025_001','Nguyen Van E','vanE@example.com','123456',NULL,NULL,'2025-06-02',0),('KH02062025_003','Ngoc Quynh','nguyenngocquynh250925@gmail.com','$2y$10$4ppCOZ/08GroBHi7mmiDZeSvJ0pfjXwYEvaoAGBOEsYasjyNiaj1m',NULL,NULL,'2025-06-02',0),('KH02062025_004','Ngoc Quynh','nguyenngocquynh250922@gmail.com','$2y$10$ryztRGq5vpwdMCbru9tm5.ra1.NVliSe1BZm.zs54QTy9a7cyfSHu',NULL,NULL,'2025-06-02',0),('KH02062025_005','Quynh Ne','nguyenngocquynh250906@gmail.com','$2y$10$UtPR2dayY81AMG7X4YWPDuBXpSp1NEByTnSKYUZRV.DbvTob1gDSG',NULL,NULL,'2025-06-02',0),('KH02062025_006','Ngoc Quynh','nguyenngocquynh250901@gmail.com','$2y$10$bu0yGt8j./n1zWcPQ8pgv.fRroKdmJaBZ4wA9NXVu6mt9Iq4o62TS',NULL,NULL,'2025-06-02',0),('KH02062025_007','Quynh Ne','nguyenngocquynh250902@gmail.com','$2y$10$PBc3GH2D6DqAl.JNnNkUtuI/W7odGrjdRN8PXyYrP5R4VTCpFHBvG',NULL,NULL,'2025-06-02',0),('KH02062025_008','Quynh Ne','nguyenngocquynh250900@gmail.com','$2y$10$HRsrBvqUiQ8N.Fe71Z6uWOW9hmQvGCV9Ip4GDEvk.VO5rAG1Q//Xq',NULL,NULL,'2025-06-02',0),('KH02062025_009','Ngoc Quynh','nguyenngocquynh2509000@gmail.com','$2y$10$qZ5.ZaUBZM4xZ8kd877aYed9VqZB.2wQDp3qpNFtqtzYvfc305lA.',NULL,NULL,'2025-06-02',0),('KH02062025_010','Quynh Ne','nguyenngocquynh2509011@gmail.com','$2y$10$bRNODklHT3aEiOvE967b4.bH6HZY3U32aV/WVtr042FXVcNUKH8X6',NULL,NULL,'2025-06-02',0),('KH02062025_011','met qua','nguyenngocquynh2509@gmail.com','$2y$10$3AaTx2/9m5DH3KW9nHyyu.kPT4LaH8c8XSBStP1ndoswlJXK881le',NULL,NULL,'2025-06-02',0),('KH02062025_012','met lan 2','nguyenngocquynh250@gmail.com','$2y$10$Xc9yFpr/LY0YGA5HI64hqeJTQYvIgiM/tp3ZYYgphIQ81KYZ5QYgm',NULL,NULL,'2025-06-02',0),('KH02062025_013','met lan 3','nguyenngocquynh2@gmail.com','$2y$10$6z9JLCXb3Iee4KL9zXnTIujS3AjRDQHKAc73.jbMzQS5oD/nRY5gW',NULL,NULL,'2025-06-02',0),('KH02062025_014','met lan 4','nguyenngocquynh22@gmail.com','$2y$10$hASp1LclCm.eFO8pFLavge0i8MlrzeB/fJsnFXhl8eVJchSfuvx12',NULL,NULL,'2025-06-02',0),('KH02062025_015','ngocquynhne','nguyenngocquynh25090000@gmail.com','$2y$10$XZFg2G9gW/DP3pSuF96vqejbyyJZDcHUHBingrMsCVcRGYLVNOxjO',NULL,NULL,'2025-06-02',0),('KH02062025_016','Nguyen Ngoc Quynh','nguyenngocquynh250904@gmail.com','$2y$10$pLG4VdttTEGuS3XHliHVaehyS.D2eiC.tWEVxT1dMM3arEvcR29JW',NULL,NULL,'2025-06-02',1),('KH05062025_001','Quynh','nguyensair@gmail.com','1234',NULL,NULL,'2025-06-05',0),('KH05062025_002','jh','nguyenngocquynh25090114@gmail.com','$2y$10$Qw89MN1C3EZO0Pd68YcuPe2YE1jOlB2tUxxAp1Gi17CnfRAu.fGAK',NULL,NULL,'2025-06-05',0),('KH05062025_003','Trà My','tramyn@gmail.com','$2y$10$1y5tok182lV305XbisFC..1Ke36npg5IT775dZQ1wye0fIJSlMvoO',NULL,NULL,'2025-06-05',0),('KH06062025_001','Đỗ Nguyễn Minh Hương','huong0122@gmail.com','$2y$10$ixAh6KmWjH28PvoAL2zHtufn/s4umf4AUFT8AqPT5pDFPAFa2HEI6',NULL,NULL,'2025-06-06',0),('KH15062025_001','metghe','nguyenngocquynh1212@gmail.com','$2y$10$Y1ijj.m8FkihHXht/P6WeOYyrzYu.Sz7rQr5bNiOa0ZaRaBCTQ0Q.',NULL,NULL,'2025-06-15',0),('KH15062025_002','sd','nguyenngocquynh2222@gmail.com','$2y$10$3f1f3Ve/oFl9KIsjNYDO3e9.CUY.Rm.xjIj6eb6rJPeggj4FWmZFG',NULL,NULL,'2025-06-15',0),('KH17062025_001','1706','nguyenngoc@gmail.com','$2y$10$ka306JQODQrR3899I7DhO.KKs5NiANzZPv7uVFh9c2560LVtzgGe6',NULL,NULL,'2025-06-17',0),('KH31052025_001','Nguyen Van A','vana@example.com','123456','0123456789','Hanoi','2025-05-31',0),('KH31052025_002','Nguyen Van B','vanB@example.com','123456','0123456789','Hanoi','2025-05-31',0),('KH31052025_003','Nguyen Van C','vanC@example.com','123456','0123456789','Hanoi','2025-05-31',0);
/*!40000 ALTER TABLE `khachhang` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `before_insert_khachhang` BEFORE INSERT ON `khachhang` FOR EACH ROW BEGIN
  DECLARE max_number INT DEFAULT 0;
  DECLARE new_makh VARCHAR(20);
  DECLARE today_prefix VARCHAR(20);

  -- Tạo tiền tố theo ngày hôm nay, dạng KHddmmyyyy_
  SET today_prefix = CONCAT('KH', DATE_FORMAT(CURDATE(), '%d%m%Y'), '_');

  -- Tìm số thứ tự lớn nhất đã tồn tại trong cùng ngày
  SELECT IFNULL(MAX(CAST(SUBSTRING(Makh, 12) AS UNSIGNED)), 0)
  INTO max_number
  FROM khachhang
  WHERE Makh LIKE CONCAT(today_prefix, '%');

  -- Ghép mã mới
  SET new_makh = CONCAT(today_prefix, LPAD(max_number + 1, 3, '0'));

  -- Gán mã khách hàng cho bản ghi sắp thêm
  SET NEW.Makh = new_makh;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `khachhang_suckhoe`
--

DROP TABLE IF EXISTS `khachhang_suckhoe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `khachhang_suckhoe` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Makh` varchar(20) NOT NULL,
  `weight` float DEFAULT NULL,
  `height` float DEFAULT NULL,
  `age` int DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `activity_level` varchar(20) DEFAULT NULL,
  `goal` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `Makh` (`Makh`),
  CONSTRAINT `khachhang_suckhoe_ibfk_1` FOREIGN KEY (`Makh`) REFERENCES `khachhang` (`Makh`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `khachhang_suckhoe`
--

LOCK TABLES `khachhang_suckhoe` WRITE;
/*!40000 ALTER TABLE `khachhang_suckhoe` DISABLE KEYS */;
INSERT INTO `khachhang_suckhoe` VALUES (15,'KH02062025_016',55,155,21,'Nu','1.2','loseweight','2025-06-16 15:26:30'),(16,'KH02062025_016',55,155,21,'Nu','1.2','loseweight','2025-06-16 19:59:33'),(17,'KH02062025_016',55,155,21,'Nu','1.2','loseweight','2025-06-16 20:14:03'),(18,'KH02062025_016',55,155,21,'Nu','1.375','loseweight','2025-06-17 19:15:33'),(19,'KH02062025_016',55,55,21,'Nu','1.2','gainweight','2025-06-17 19:22:53'),(20,'KH02062025_016',55,155,21,'Nu','1.2','loseweight','2025-06-17 19:59:32'),(21,'KH02062025_016',55,155,21,'Nu','1.2','loseweight','2025-06-17 20:01:42'),(22,'KH02062025_016',11,11,11,'Nu','1.725','loseweight','2025-06-17 20:03:29'),(23,'KH02062025_016',55,155,21,'Nu','1.2','loseweight','2025-06-17 20:04:09'),(24,'KH02062025_016',55,155,21,'Nu','1.2','gainweight','2025-06-18 08:26:02'),(25,'KH02062025_004',75,160,24,'Nam','1.375','maintain','2025-06-19 07:36:03');
/*!40000 ALTER TABLE `khachhang_suckhoe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `khachhangdiung`
--

DROP TABLE IF EXISTS `khachhangdiung`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `khachhangdiung` (
  `Makh` varchar(20) NOT NULL,
  `Thanhphandiungid` int NOT NULL,
  PRIMARY KEY (`Makh`,`Thanhphandiungid`),
  KEY `Thanhphandiungid` (`Thanhphandiungid`),
  CONSTRAINT `khachhangdiung_ibfk_1` FOREIGN KEY (`Makh`) REFERENCES `khachhang` (`Makh`) ON DELETE CASCADE,
  CONSTRAINT `khachhangdiung_ibfk_2` FOREIGN KEY (`Thanhphandiungid`) REFERENCES `thanhphan` (`Idthanhphan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `khachhangdiung`
--

LOCK TABLES `khachhangdiung` WRITE;
/*!40000 ALTER TABLE `khachhangdiung` DISABLE KEYS */;
INSERT INTO `khachhangdiung` VALUES ('KH02062025_016',1),('KH02062025_016',2),('KH02062025_016',32),('KH02062025_004',64),('KH02062025_016',64),('KH02062025_016',65),('KH02062025_016',66);
/*!40000 ALTER TABLE `khachhangdiung` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loai`
--

DROP TABLE IF EXISTS `loai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loai` (
  `Maloai` int NOT NULL AUTO_INCREMENT,
  `Tenloai` varchar(255) NOT NULL,
  PRIMARY KEY (`Maloai`),
  UNIQUE KEY `Tenloai` (`Tenloai`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loai`
--

LOCK TABLES `loai` WRITE;
/*!40000 ALTER TABLE `loai` DISABLE KEYS */;
INSERT INTO `loai` VALUES (1,'balance'),(2,'calorie'),(3,'diabetic'),(4,'gluten'),(5,'heart'),(6,'keto'),(7,'protein'),(8,'vegan');
/*!40000 ALTER TABLE `loai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sanpham`
--

DROP TABLE IF EXISTS `sanpham`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sanpham` (
  `Masp` int NOT NULL AUTO_INCREMENT,
  `Tensp` varchar(255) NOT NULL,
  `Mota` text NOT NULL,
  `Hinhanh` varchar(255) DEFAULT NULL,
  `Gianguyenlieu` int NOT NULL,
  `Giaban` int NOT NULL,
  `Calories` int DEFAULT NULL,
  `Protein` int DEFAULT NULL,
  `Fat` int DEFAULT NULL,
  `Carbs` int DEFAULT NULL,
  `Sugar` int DEFAULT NULL,
  `Fiber` int DEFAULT NULL,
  `Ngaytao` date DEFAULT (curdate()),
  `Maloai` int DEFAULT NULL,
  `Trangthai` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Masp`),
  KEY `Maloai` (`Maloai`),
  CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`Maloai`) REFERENCES `loai` (`Maloai`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sanpham`
--

LOCK TABLES `sanpham` WRITE;
/*!40000 ALTER TABLE `sanpham` DISABLE KEYS */;
INSERT INTO `sanpham` VALUES (1,'Thịt nướng sốt mật ong','Món thịt mềm mại của chúng tôi được làm từ thịt bò nạc, quinoa, cà rốt và ớt xanh, được tẩm một lớp sốt mật ong bourbon ngọt ngào và đi kèm với đậu xanh nguyên hạt bên cạnh cho bữa tối thoải mái tuyệt vời nhất!','/CoSo/assets/img/mon_an/Meatloaf_with_Honey.webp',200000,250000,340,28,12,29,12,6,'2025-06-06',1,1),(2,'Gà nướng kiểu Ý thơ mộng','Một miếng ức gà nướng được phủ lên bởi sốt cà chua khô kem chuẩn bị với phô mai parmesan, tỏi và cỏ xạ hương. Được ăn kèm với cơm, cà rốt baby và bông cải xanh.','/CoSo/assets/img/mon_an/Tuscan-InspiredChicken_large.webp',280000,360000,360,39,11,26,4,3,'2025-06-06',1,1),(4,'Gà vườn sốt cà thơ mộng','Kết hợp ba loại phô mai và thịt gà xay tẩm gia vị, đắm mình trong sốt marinara làm từ rau với cà rốt, bí xanh và bí ngòi, đi kèm với bông cải.','/CoSo/assets/img/mon_an/ChickenLasagnawithGardenMarinara_1_large.webp',200000,250000,350,28,13,30,4,5,'2025-06-06',1,1),(5,'Gà vườn sốt ớt đỏ và khoai tây','Một miếng ức gà mềm mại được phủ lớp sốt lấy cảm hứng từ Tây Ban Nha với ớt đỏ nướng, bột paprika hun khói, cà chua, hạt thông và tỏi, đi kèm với khoai tây nướng gia vị và bông cải xanh.','/CoSo/assets/img/mon_an/ChickenwithRomescoSauce_Potatoes_large.webp',190000,230000,390,39,15,24,6,5,'2025-06-06',1,1),(8,'Bò hầm mì Ý sốt vang đỏ đậm đà','Thịt bò mềm trong nước sốt demi-glace vang đỏ, phục vụ trên mì trứng xoắn với nấm và cà rốt. Kèm theo đó là bông cải xanh và hành củ.','/CoSo/assets/img/mon_an/Beef_NoodleswithRedWineDemi-Glace_large.webp',120000,160000,300,29,10,25,4,4,'2025-06-07',1,1),(9,'Gà chiên thảo mộc mê ly','Món ức gà mềm này được tẩm gia vị với các loại thảo mộc cổ điển và được phủ một lớp sốt marinara rau củ cùng với một ít phô mai Parmesan và mì.','/CoSo/assets/img/mon_an/HerbedChickenParmesan_2_large.webp',100000,130000,360,41,8,30,3,4,'2025-06-07',1,1),(10,'Gà bơ kiểu Ấn Độ','Gà xắt nhỏ trong một món cà ri chính hiệu được chế biến với cà chua, kem, bơ và gia vị, phục vụ cùng cơm basmati hương thì là và đậu xanh thơm ngon.','/CoSo/assets/img/mon_an/Indian-InspiredButterChicken_2_large.webp',90000,130000,410,33,20,25,11,4,'2025-06-11',1,1),(11,'Heo nướng sốt cam Caribe','Thịt heo kéo ướp gia vị vừa đủ cay, trộn với ớt chuông và hành tây. Dùng kèm với đậu đen và gạo cùng với salad bắp cải xanh.','/CoSo/assets/img/mon_an/MojoPorkwithBlackBeans_Rice_large.webp',100000,130000,330,23,11,34,7,7,'2025-06-11',1,1),(12,'Heo hầm ớt xanh sốt đậm đà','Chỉ cần cái tên thôi đã đủ làm bạn thèm chảy nước miếng. Món ăn này gồm ớt xanh với thịt heo xông khói xé nhỏ, được bổ sung bởi một loại salsa ngô nướng  và đậu đen với những hương vị của ngò rí, chanh và cây thì là.','/CoSo/assets/img/mon_an/GreenChilePorkwithSouthwestSalsa_large.webp',80000,110000,310,24,8,28,5,5,'2025-06-11',1,1),(15,'Trứng, khoai tây và thịt nguội mềm tan','Một quả trứng với khoai tây đỏ nướng, thịt xông khói và phô mai cheddar, kèm theo một phần táo có vị quế.','/CoSo/assets/img/mon_an/BaconandPotatoEggScramble_large.webp',80000,110000,250,21,8,23,9,3,'2025-06-11',1,1),(17,'Bò nướng than BBQ khoai tây vàng','Thịt bò nướng mềm được phủ bởi một loại sốt BBQ tự làm có hương vị mật ong và đường nâu. Thịt bò BBQ của chúng tôi được phục vụ kèm với khoai tây vàng và đậu xanh nguyên vỏ được nêm tỏi.','/CoSo/assets/img/mon_an/HickorySmokedBBQBeefwithYukonGoldPotatoes_large.webp',160000,190000,370,36,9,35,15,5,'2025-06-14',4,1),(18,'Gà nướng mật ong và hạt bổ dưỡng ','Ức gà thái hạt lựu ngâm trong nước sốt mật ong ngọt ngào kết hợp với phô mai cheddar và gouda cùng đậu xanh.','/CoSo/assets/img/mon_an/HoneyGlazedChickenwithCheesyQuinoa_2_large.webp',90000,120000,370,40,10,30,15,5,'2025-06-14',4,1),(19,'Gà nướng mù tạt mật ong','Thịt ức gà mềm ướp với mật ong và mù tạt, phục vụ cùng khoai tây parmesan nướng thảo mộc và ớt đỏ.','/CoSo/assets/img/mon_an/HoneyMustardChickenwithHerb-roastedPotatoes_large.webp',80000,110000,270,30,7,22,8,4,'2025-06-14',4,1),(20,'Heo nướng BBQ & súp lơ xanh','Thịt heo được xông khói và sau đó được phủ trong nước sốt BBQ tự làm với hương vị của mật ong và đường nâu. Nó được đi kèm với món salad bắp cải tím và táo chua cùng với những bông cải xanh bên cạnh.','/CoSo/assets/img/mon_an/HickorySmokedBBQPorkwithBroccoli_large.webp',60000,100000,320,34,7,30,18,6,'2025-06-14',4,1),(21,'Cá hồi nướng sốt kem húng quế','Cá hồi tươi được phục vụ kèm với một phần rau củ phong phú có cà tím, bí ngòi và bí vàng cùng với súp lơ nướng thảo mộc. Cá hồi chứa axit béo omega-3 có thể giúp giảm nguy cơ mắc bệnh tim, trầm cảm và viêm khớp.','/CoSo/assets/img/mon_an/GrilledSalmonwithCreamyPesto_1_large.webp',160000,200000,380,33,21,14,7,4,'2025-06-14',4,1),(22,'Bò rừng nướng sốt cà chua','Bison là loại thịt bò mới! Protein nạc nâng cao món ăn châu Âu cổ điển này lên một tầm cao mới với phong cách Tây Nam mà chúng tôi thêm vào. Bên cạnh đậu đỏ kiểu cao bồi và một ít salad xoài, món ăn này là một bữa tiệc hương vị được kết hợp một cách tinh tế.','/CoSo/assets/img/mon_an/SouthwestBisonMeatloaf_large.webp',190000,220000,350,27,12,34,12,9,'2025-06-14',4,1),(23,'Bánh mì bò nướng & phô mai tan chảy','2 miếng bánh kẹp bò nạc không vỏ bánh, rưới sốt BBQ Sriracha đặc biệt, hành caramel ngọt và phô mai trắng Cheddar.\r\nKhông dùng bánh mì, thay vào đó là khoai mì chiên giòn – món ăn kèm được yêu thích nhất!','/CoSo/assets/img/mon_an/BBQBeef_CheddarSliders_large.webp',100000,120000,380,28,14,36,5,3,'2025-06-14',4,1),(24,'Gà cuộn cơm rau chiên healthy','Bánh cuốn trứng được làm từ thịt gà xay, trứng bác, bắp cải và cà rốt xào trong nước sốt Sriracha cay, trang trí với hành lá. Dùng kèm với cơm chiên súp lơ.','/CoSo/assets/img/mon_an/ChickenEggRollBowlwithCauliflowerFriedRice_large.webp',50000,70000,230,23,6,21,10,6,'2025-06-14',4,1),(25,'Gà nướng thấm sốt cay nồng Buffalo','Gà buffalo và phô mai xanh, thịt xông khói thơm lừng và hành lá vào món khoai tây nghiền của bạn. Món ăn này được kết hợp với bông cải xanh bên cạnh để tạo thêm sự hoàn hảo.','/CoSo/assets/img/mon_an/GrilledChickenwithBuffaloSauce_large.webp',60000,90000,320,35,11,21,3,4,'2025-06-14',4,1),(26,'Gà nấu nước cốt dừa kiểu Thái','Món ăn này kết hợp gà thái hạt lựu trong nước sốt cà ri dừa cảm hứng Thái Lan béo ngậy, được phục vụ với cơm và một món rau củ bao gồm đậu xanh, cà rốt và cà tím.','/CoSo/assets/img/mon_an/Thai-styleCoconutChicken_2_large.webp',70000,120000,320,30,11,26,6,4,'2025-06-14',4,1),(27,'Bát pizza gà thập cẩm','Một chiếc pizza thượng hạng không vỏ với gà xé, được phủ với ớt chuông xanh, hành tây, nấm, xúc xích gà và được trang trí với lớp phô mai ngon lành. Kèm theo một bên bông cải xanh xào tỏi.','/CoSo/assets/img/mon_an/ChickenSupremePizzaBowl_2_large.webp',100000,130000,320,33,11,22,10,6,'2025-06-14',4,1),(28,'Viên Gà Sốt Việt Quất','Thịt viên gà tây được chế biến với thơm và quả nam việt quất khô, sau đó được phủ nước sốt nâu, cà rốt và hành tây thái nhỏ. Dùng kèm với khoai lang nướng và bông cải bên cạnh.','/CoSo/assets/img/mon_an/CranberryHarvestTurkeyMeatballs_large.webp',70000,100000,330,22,14,31,10,6,'2025-06-17',5,1),(29,'Mì Gà Viên Sốt Kiểu Ý','Thịt viên gà làm tại nhà được phục vụ trên mì spaghetti và được phủ một lớp sốt marinara kèm theo bên cạnh là đậu xanh cắt kiểu Ý được nêm tỏi và húng quế.','/Coso/assets/img/mon_an/ChickenMeatballswithMarinara_Spaghetti_large.webp',80000,110000,290,27,7,28,6,4,'2025-06-17',5,1),(30,'Thịt Heo Xông Khói Sốt Xanh','Món ăn này gồm thịt heoxông khói và mềm mại được phủ salsa verde, kèm theo hỗn hợp ngô và rau mùi nướng trên lửa. Bữa ăn này được hoàn thiện một cách hoàn hảo khi thêm cạnh những quả ớt piquillo nhồi đã được nêm gia vị.','/CoSo/assets/img/mon_an/SmokedChipotlePorkwithSalsaVerde_large.webp',90000,140000,300,30,6,30,5,5,'2025-06-17',5,1),(42,'Bánh Phô Mai Mâm Xôi Đỏ Ngọt Ngào','Bánh phô mai này là sự kết hợp giữa bánh phô mai kiểu Mỹ và Ý làm ​​từ phô mai kem và phô mai ricotta để tăng thêm protein. Lớp vỏ hạnh nhân và yến mạch xay giúp tăng hàm lượng dinh dưỡng mà không làm mất đi hương vị hấp dẫn.','/Coso/assets/img/mon_an/Large_f36cd78a-b2c8-4a45-b7c4-3c8d23162019_large.webp',30000,45000,180,9,9,17,8,2,'2025-06-18',1,1),(43,'Sô Cô La Đậu Phộng Hảo Hạng','Sự ngon lành của bơ đậu phộng và sô cô la mang đến sự thỏa mãn cho giờ ăn nhẹ. Thanh sô cô la phủ sô cô la này chứa 15g protein từ váng sữa ăn cỏ và chỉ 9g đường. Chứa đầy mật ong ngọt tự nhiên, bơ đậu phộng, vụn sô cô la và hạt siêu thực phẩm giúp bạn no và thỏa mãn.','/Coso/assets/img/mon_an/Large_592556dd-916a-4134-ac14-9ff91be7c533_large.webp',20000,25000,200,15,8,22,9,3,'2025-06-18',1,1),(44,'Bánh Quy Sô Cô La Giòn Rụm','Thưởng thức chiếc bánh quy giàu protein được làm từ những nguyên liệu đơn giản bao gồm yến mạch không chứa gluten, protein whey từ bò ăn cỏ, bơ hạnh nhân và vụn sô cô la.','/Coso/assets/img/mon_an/cccookie_large.webp',15000,20000,200,15,8,18,8,2,'2025-06-18',1,1),(45,'Bánh Phô Mai Vani','Đậu vani hảo hạng mang đến cho chiếc bánh phô mai này hương vị vani đậm đà, tuyệt vời! Nhân bánh là hỗn hợp phô mai kem và ricotta để tăng thêm protein và kết cấu kem nhẹ. Vỏ bánh hạnh nhân và yến mạch là sự kết hợp sáng tạo gợi nhớ đến vỏ bánh quy graham truyền thống, và vị giác của bạn sẽ không nhận ra sự khác biệt!','/Coso/assets/img/mon_an/Large_4f831fb0-06d8-44a4-b892-099763b75917_large.webp',25000,30000,180,9,9,17,9,2,'2025-06-18',1,1),(46,'Bánh Phô Mai Dâu Tây Kem Tươi','Được làm từ dâu tây tươi và nhân bánh nhẹ, ngon ngọt, chiếc bánh phô mai này thực sự là một giấc mơ! Sự kết hợp giữa phô mai kem và ricotta mang đến cho chiếc bánh này kết cấu nhẹ hơn, xốp hơn nhưng vẫn có vị mịn và béo ngậy.','/Coso/assets/img/mon_an/Large_e9e76de1-7873-473c-a624-663b0cad02a4_large.webp',25000,30000,180,9,9,17,8,3,'2025-06-18',1,1),(47,'Sô Cô La Hạt Giòn Tan','Hãy thỏa mãn cơn thèm sô cô la của bạn với hỗn hợp hạt này! Sự kết hợp của hạnh nhân, đậu phộng và hạt điều này được bổ sung không chỉ một mà là hai loại sôcôla, sôcôla đen và sôcôla sữa. Hỗn hợp này cũng đi kèm với một phần thưởng bổ sung là giọt bơ đậu phộng và nho khô.','/Coso/assets/img/mon_an/Large_7955a69b-3404-45e3-9e4f-f004d6f64eaf_large.webp',20000,25000,198,6,16,15,10,3,'2025-06-18',1,1),(48,'Bánh Việt Quất Tím Mộng Mơ','Thưởng thức quả việt quất ngọt giàu chất chống oxy hóa và hạt lanh giàu chất xơ trong một thanh yến mạch lành mạnh phủ sữa chua Hy Lạp giàu protein. Thanh Blueberry Parfait tốt cho sức khỏe này được làm từ protein whey từ bò ăn cỏ là một lựa chọn đồ ăn nhẹ thông minh, mang đến niềm hạnh phúc thuần khiết và ngon miệng.','/Coso/assets/img/mon_an/Large_f978355d-59f0-4188-aacd-c83ab08152fa_large.jpg',15000,20000,200,15,7,22,6,3,'2025-06-18',1,1),(49,'Bánh Sô Cô La Espresso Đậm Đà','Espresso làm giảm độ béo ngậy của kem phô mai và nhân ricotta và khuếch đại hương vị của ca cao sô cô la đen cho một món tráng miệng xa hoa.','/Coso/assets/img/mon_an/Large_0a26b0b3-c09d-4c7f-babb-442cc022b5de_large.webp',20000,25000,190,9,9,18,9,3,'2025-06-18',1,1),(50,'Gà Sấy Vị Salsa Đậu Đen Độc Đáo','Thịt gà khô sốt đậu đen Salsa của chúng tôi là sự kết hợp đậm đà của ớt chuông, đậu đen, cà chua và một chút khói.','/Coso/assets/img/mon_an/Large_8724b6bf-51e8-499d-b9bc-55954ebb546e_large.webp',20000,25000,60,11,1,1,1,0,'2025-06-18',1,1),(51,'Sô Cô La Đen & Dừa','Dừa là ngôi sao của thanh protein này! Dừa là nguồn chất béo độc đáo vì nó dễ tiêu hóa và được sử dụng để tạo năng lượng ngay lập tức.','/Coso/assets/img/mon_an/Large_569e9969-103b-4fe6-8174-c71e6d802e01_large.webp',20000,25000,200,15,9,22,9,3,'2025-06-18',1,1),(52,'Waffle Nóng Giòn Cùng Trứng','Bánh quế không chứa gluten theo phong cách gia đình, được phục vụ với vani, quế, xi-rô cây phong kèm theo trứng rán và xúc xích gà tây.','/Coso/assets/img/mon_an/HomestyleWaffleswithScrambledEggs_large.webp',30000,35000,300,15,12,48,4,1,'2025-06-18',4,1),(53,'Thịt Heo BBQ Hàn Quốc ','Chảo rán trứng chế biến từ thịt lợn xé nhỏ trộn với nước sốt BBQ lấy cảm hứng từ Hàn Quốc, ăn kèm với salad kim chi nhẹ.','/Coso/assets/img/mon_an/Korean-InspiredBBQPork_EggSkillet_large.webp',35000,40000,270,21,10,23,16,4,'2025-06-18',1,1),(54,'Trứng Nướng & Bí Đỏ Thơm Lừng','Một món frittata tự làm với xúc xích gà tây nạc vụn, phô mai Thụy Sĩ và phô mai cheddar ăn kèm với bí ngô nướng agave.','/Coso/assets/img/mon_an/TurkeySausageFrittatawithButternutSquash_1_large.webp',35000,40000,260,17,13,18,8,2,'2025-06-18',1,1),(55,'Trứng Ốp La Kiểu Ý Đậm Đà','Lấy cảm hứng từ hương vị của Ý, món trứng tráng kiểu Ý của chúng tôi được nhồi đầy sốt pesto marinara, phô mai mozzarella và xúc xích gà tây xay. Bữa sáng hấp dẫn này được hoàn thiện với khoai tây vỏ đỏ nướng để giúp bạn no bụng suốt buổi sáng.','/Coso/assets/img/mon_an/ItalianStyleOmelet_large.webp',25000,30000,230,22,7,20,4,3,'2025-06-18',1,1),(56,'Yến Mạch Táo Quế ','Yến mạch nguyên hạt cán mỏng với quế và ngọt với xi-rô cây phong đen, táo và nam việt quất khô. Ăn kèm với trứng rán và xúc xích gà tây','/Coso/assets/img/mon_an/AppleCinnamonOatmealwithScrambledEggs_large.webp',25000,30000,210,13,6,25,7,3,'2025-06-18',1,1),(57,'Ức Gà Sốt Táo Đỏ Mọng','Với nhân thảo mộc truyền thống mềm mịn, đậu xanh phủ thịt xông khói hun khói & sốt táo nam việt quất ngọt ngào, bữa tối gà tây này sẽ không làm bạn thất vọng!','/Coso/assets/img/mon_an/TurkeyBreastwithCranberryAppleChutney_large.webp',150000,200000,310,36,5,30,13,5,'2025-06-18',6,1),(58,'Thịt Bò Sấy Vị Quả Mọng','Bạn đang tìm một món ăn nhẹ mang theo? Đây là lựa chọn của bạn! Hãy thưởng thức thịt bò khô ăn cỏ của chúng tôi được làm ngọt bằng nam việt quất và việt quất và chứa 8 gram protein bổ dưỡng.','/Coso/assets/img/mon_an/Large_0cd2f6ba-7350-4cf7-bafe-a8df383af9cd_large.webp',50000,60000,80,8,3,4,3,0,'2025-06-18',6,1),(59,'Kem Bột Cookie Socola','Hãy thưởng thức thanh bột bánh quy sô cô la chip ít carb, giàu protein của chúng tôi được làm từ protein collagen, hạnh nhân, vụn sô cô la đen với một chút vani và muối biển.','/Coso/assets/img/mon_an/chocolatechipcookiedoughbarnew_large.webp',45000,55000,190,13,15,12,3,8,'2025-06-18',6,1),(60,'Bánh Quế Mật Ngọt','Thanh protein Glazed Cinnamon Bun của chúng tôi đã sẵn sàng cung cấp năng lượng cho bạn trong suốt cả ngày.','/Coso/assets/img/mon_an/Large_4a1f1bdf-a219-4374-92dd-b55013ec6bb5_large.webp',35000,45000,180,15,7,22,6,3,'2025-06-18',7,1),(61,'Khoai Tây Đậu Kiểu Chay','Bánh nướng Shepherd được chế biến theo phong cách thuần chay với đậu lăng và rau củ hỗn hợp phủ khoai tây nghiền nướng. Ăn kèm với cà rốt nướng cùng hành tây và rau mùi tây.','/Coso/assets/img/mon_an/VeganLentilShepherd_sPie_large.webp',15000,170000,270,10,6,45,7,8,'2025-06-18',8,1),(62,'Bánh Taco thuần chay','Một chiếc bánh taco thuần chay đầy hương vị được chế biến từ đậu lăng, hành tây, ớt chuông đỏ và trứng rán thuần chay trong nước sốt ranchero cay. Ăn kèm với bánh ngô tortilla và phủ phô mai cheddar thuần chay.','/Coso/assets/img/mon_an/VeganBreakfastTacoBowl_large.webp',140000,150000,270,14,12,27,3,6,'2025-06-18',8,1),(63,'Mì Ống Đậu Hũ ','Đậu phụ ướp Togarashi, một loại gia vị hỗn hợp của Nhật Bản gồm ớt bột, rong biển và hạt vừng, được phục vụ kèm với mì ống và phô mai thuần chay cùng với hỗn hợp rau củ theo phong cách Nhật Bản.','/Coso/assets/img/mon_an/TogarashiTofuwithVeganMac_Cheez_large.webp',145000,155000,320,16,12,35,4,5,'2025-06-18',8,1),(64,'Súp Chili Chay Đậm Đà','Một món ớt thực vật thịnh soạn được chế biến từ hỗn hợp đậu đen và đậu pinto ninh trong nước sốt cà chua và phủ phô mai cheddar thuần chay cùng kem chua thuần chay.','/Coso/assets/img/mon_an/VegetarianChili_large.webp',120000,130000,220,8,8,30,6,8,'2025-06-18',8,1),(65,'Cá Chẽm Sốt Dứa Dừa Ngọt','Cá thịt trắng mọng nước được ngâm trong nước sốt sữa dừa dứa ngọt ngào, ăn kèm với cơm hoa nhài tơi xốp và đậu xanh nguyên hạt trộn với dừa nướng. Barramundi, cá mú có nguồn gốc bền vững của chúng tôi, ít calo với hương vị bơ nhẹ nhàng dễ chịu và nhiều axit béo omega-3 tốt cho tim.','/Coso/assets/img/mon_an/BarramundiSeabasswithSweetPineappleCoconutSauce_1_large.webp',210000,230000,330,35,9,26,7,4,'2025-06-18',5,1),(66,'Sinh tố chuối ngọt ngào','Một ly sinh tố vui nhộn và đầy năng lượng, mang hương vị đặc trưng của chuối chín mọng, vị béo ngậy của bơ đậu phộng và những mảnh socola giòn tan. \"Sinh Tố Khỉ Con\" không chỉ là thức uống, mà còn là một bữa tiệc vị giác đầy bất ngờ, ngọt ngào và bổ dưỡng. Hoàn hảo để khởi đầu ngày mới hoặc nạp năng lượng giữa buổi!','/Coso/assets/img/mon_an/Chunky-Monkey-Smoothie-72.jpg',50000,60000,70,13,18,53,16,8,'2025-06-18',7,1),(67,'Salad Việt Quất Hạnh Nhân','Một sự kết hợp đầy tinh tế và tươi mới! Gỏi Việt Quất Hạnh Nhân là bản giao hưởng của vị chua thanh của những trái việt quất mọng nước, độ giòn bùi của hạnh nhân lát, cùng với rau xanh tươi mát và nước xốt đặc biệt. Món gỏi này không chỉ đẹp mắt mà còn là lựa chọn hoàn hảo cho những ai tìm kiếm một món ăn nhẹ nhàng, bổ dưỡng nhưng vẫn ngập tràn hương vị bất ngờ.','/Coso/assets/img/mon_an/Blueberry-Amandine-Salad-vegan-72.jpg',70000,100000,210,18,20,7,11,11,'2025-06-18',8,1),(68,'Cơm Thố Panang Xào','Với món Panang Stir-Fry Bowl, mình xin đề xuất \"Việt hóa\" và mô tả như sau:\r\n\r\nCơm Thố Panang Xào\r\nMột hành trình vị giác đến Thái Lan ngay tại bàn ăn! Cơm Thố Panang Xào là sự kết hợp hoàn hảo giữa những lát thịt hoặc rau củ tươi ngon được xào nhanh cùng sốt cà ri Panang Thái Lan đậm đà, béo ngậy mùi dừa và thơm nồng các loại thảo mộc, tất cả đặt trên nền cơm trắng dẻo thơm. Món ăn này không chỉ đủ đầy dinh dưỡng mà còn bùng nổ hương vị, mang đến trải nghiệm ẩm thực ấm áp và khó quên.','/Coso/assets/img/mon_an/Panang-Peanut-Stir-FryBowl-vegan-72.jpg',80000,100000,210,19,5,12,6,5,'2025-06-18',5,1),(69,'Bánh Mousse Bơ Đậu Phộng','Một tuyệt phẩm tan chảy ngay từ miếng đầu tiên! Bánh Mousse Bơ Đậu Phộng là sự kết hợp hoàn hảo giữa lớp mousse bơ đậu phộng mềm mịn, béo ngậy, tan chảy trong miệng cùng với đế bánh quy giòn tan hoặc lớp socola đậm đà. Chiếc bánh nhỏ xinh này là lựa chọn lý tưởng cho những tín đồ của bơ đậu phộng, mang đến trải nghiệm vị giác ngọt ngào, thơm lừng khó cưỡng.','/Coso/assets/img/mon_an/Peanut-Butter-Mousse-Cup-72.jpg',70000,100000,195,10,24,20,13,5,'2025-06-18',7,1),(70,'Đậu Gà Cay','Khám phá hương vị Trung Đông giao thoa cùng nét phóng khoáng Mỹ Latin! Sốt Hummus Chipotle Khói là món khai vị độc đáo, hòa quyện giữa sự béo ngậy, mịn màng của đậu gà nghiền, chút chua nhẹ từ chanh tươi, và đặc biệt là nốt cay nồng, thơm lừng mùi khói từ ớt Chipotle. Đây là lựa chọn hoàn hảo để chấm cùng bánh mì pita, rau củ tươi hoặc làm nền cho các món ăn khác, mang đến trải nghiệm vị giác ấm áp và đầy mê hoặc.','/Coso/assets/img/mon_an/Smoky-Chipotle-Hummus-72.jpg',80000,100000,240,12,25,34,9,3,'2025-06-18',2,1),(71,'Ngũ Cốc Kem Vani Vị Cam Chanh','Mở đầu ngày mới đầy hứng khởi với Ngũ Cốc Kem Vani Vị Cam Chanh! Đây là sự kết hợp hoàn hảo giữa những hạt ngũ cốc giòn thơm, được ngâm trong kem vani béo ngậy, mịn màng, cùng với hương thơm tươi mát và vị chua nhẹ đánh thức vị giác từ các loại trái cây họ cam chanh. Món ăn này không chỉ bổ dưỡng mà còn mang lại cảm giác sảng khoái, ngọt ngào, là bữa sáng lý tưởng hoặc món ăn nhẹ đầy năng lượng cho bạn.','/Coso/assets/img/mon_an/Citrus-Vanilla-Cream-Muesli-72.jpg',80000,120000,220,14,16,34,6,10,'2025-06-18',3,1),(72,'Miến Trộn Hàn Quốc','Một món ăn đầy màu sắc và hương vị từ xứ sở Kim Chi! Miến Trộn Hàn Quốc là sự kết hợp hài hòa của những sợi miến khoai lang dai ngon, được xào cùng vô vàn loại rau củ tươi giòn (như cà rốt, cải bó xôi, nấm...), cùng chút thịt hoặc đậu phụ mềm mại. Món ăn được nêm nếm gia vị đậm đà với dầu mè thơm lừng và nước tương đặc trưng, tạo nên một bản giao hưởng vị giác khó quên, vừa thanh đạm lại vừa đủ đầy dinh dưỡng.','/Coso/assets/img/mon_an/Japchae-Salad-vegan-72.jpg',70000,100000,260,17,8,23,12,10,'2025-06-18',8,1),(73,'Thanh Ngũ Cốc','Mở đầu ngày mới hoặc nạp năng lượng tức thì với Thanh Ngũ Cốc Ashwagandha! Đây là sự kết hợp độc đáo giữa các loại hạt dinh dưỡng (hạt bí, hạt hướng dương, hạt chia...) giòn bùi cùng thành phần Ashwagandha, một loại thảo dược quý giúp tăng cường sức khỏe và giảm căng thẳng.','/Coso/assets/img/mon_an/Superfood-Ashwagandha-72.jpg',50000,60000,120,11,12,24,11,13,'2025-06-18',7,1),(74,'Bánh Cà Rốt Vàng','Đánh thức mọi giác quan với Bánh Muffin Cà Rốt Vàng! Chiếc bánh thơm lừng này là sự hòa quyện hoàn hảo giữa vị ngọt tự nhiên của cà rốt tươi bào sợi, chút bùi béo của các loại hạt giòn thơm và hương thơm nồng ấm của quế. ','/Coso/assets/img/mon_an/24-Carrot-Gold-Muffin-72.jpg',50000,60000,180,7,1,37,20,7,'2025-06-18',7,1),(75,'Chè Gạo Lứt','Một món tráng miệng thanh mát và đầy dinh dưỡng! Chè Gạo Lứt Nảy Mầm Vị Thảo Quả là sự kết hợp độc đáo giữa những hạt gạo lứt nảy mầm mềm dẻo, ngọt bùi, được nấu sánh mịn trong sữa dừa hoặc sữa thực vật, và điểm xuyết hương thơm ấm nồng, quyến rũ đặc trưng từ thảo quả.','/Coso/assets/img/mon_an/Sprouted-Brown-Rice-Pudding-72.jpg',60000,70000,110,3,8,8,2,1,'2025-06-18',2,1),(76,'Bát Mì Miso Chanh','Thức tỉnh vị giác với Bát Mì Miso Chanh! Món ăn thanh đạm nhưng đầy hương vị này là sự kết hợp tinh tế giữa sợi mì dai ngon, chan trong nước súp Miso ấm nóng, đậm đà, được nêm nếm thêm chút chanh tươi sảng khoái và các loại rau củ thanh mát.','/Coso/assets/img/mon_an/Lemon-Miso-Bowl-vegan-72.jpg',100000,120000,240,21,21,24,5,9,'2025-06-18',8,1),(77,'Bát Cầu Vồng Kỳ Lân','Một bữa sáng hay bữa nhẹ trong mơ, đẹp như bước ra từ cổ tích! Bát Cầu Vồng Kỳ Lân là sự kết hợp diệu kỳ của sinh tố trái cây xay sánh mịn, được tạo màu tự nhiên từ các loại quả mọng và rau củ tươi ngon, xếp tầng rực rỡ như chiếc cầu vồng.','/Coso/assets/img/mon_an/Unicorn-Bowl-vegan-72.jpg',75000,90000,250,19,17,35,9,9,'2025-06-18',5,1),(78,'Kẹo Mềm Hạnh Nhân Anh Đào','Đắm chìm trong hương vị ngọt ngào và dưỡng chất với Kẹo Mềm Hạnh Nhân Anh Đào Moringa! Món kẹo fudge này là sự kết hợp tinh tế giữa vị béo bùi của hạnh nhân, chút chua thanh tự nhiên từ anh đào khô mọng nước, và đặc biệt là bột Moringa (chùm ngây) bổ dưỡng, mang đến một màu xanh dịu mát. Mỗi viên kẹo tan chảy trong miệng, ngọt ngào, thơm lừng và là một cách tuyệt vời để bổ sung năng lượng mà vẫn giữ được sự lành mạnh.','/Coso/assets/img/mon_an/Almond-Cherry-Moringa-Fudge-72.jpg',65000,75000,195,9,31,25,11,6,'2025-06-18',7,1),(79,'Viên Bột Bánh Quy Nấm Linh Chi Socola','Khám phá một món ăn vặt vừa ngon miệng vừa bổ dưỡng! Viên Bột Bánh Quy Nấm Linh Chi Socola là sự kết hợp độc đáo giữa vị ngọt ngào, béo ngậy của bột bánh quy mềm mịn, những miếng socola chip đậm đà tan chảy, và đặc biệt là chiết xuất từ nấm Linh Chi tự nhiên, giúp tăng cường sức khỏe.','/Coso/assets/img/mon_an/Reishi-Chocolate-Chunk-Cookie-Dough-Balls-72.jpg',85000,95000,190,10,25,23,15,4,'2025-06-18',3,1),(80,'Sinh Tố Thanh Mát Ngày Hè','Một ly sinh tố hoàn hảo để bạn tận hưởng những phút giây thư thái như đang trong kỳ nghỉ! Sinh Tố Thanh Mát Ngày Hè là sự pha trộn diệu kỳ của các loại trái cây tươi nhiệt đới như xoài, dứa, cùng chút nước cốt dừa béo ngậy và một chút bạc hà the mát. Mỗi ngụm sinh tố là một làn gió sảng khoái, ngọt ngào, giúp bạn xua tan mọi mệt mỏi và cảm giác như đang nằm dài trên bãi biển.','/Coso/assets/img/mon_an/Out-Of-Office-Smoothie-72.jpg',45000,55000,110,5,6,21,17,7,'2025-06-18',2,1),(81,'Gỏi Xốt Ranch','Một món gỏi tươi mát, giòn ngon chuẩn vị! Gỏi Vườn Xốt Ranch là sự kết hợp hoàn hảo của các loại rau xanh tươi non như xà lách, dưa chuột, cà chua bi, cùng những miếng gà nướng hoặc trứng luộc thái lát, tất cả được phủ đều bởi lớp xốt Ranch béo ngậy, thơm lừng mùi tỏi và rau thơm. ','/Coso/assets/img/mon_an/Garden-Ranch-Salad-vegan-72.jpg',90000,120000,360,17,19,23,9,10,'2025-06-18',3,1),(82,'Mì Trộn Cải Xoăn','Một biến tấu đầy bất ngờ và hấp dẫn từ món gỏi Caesar kinh điển! Mì Trộn Cải Xoăn Caesar là sự kết hợp hoàn hảo giữa những sợi mì Ý dai ngon, lá cải xoăn xanh mướt giòn rụm, được trộn đều với xốt Caesar béo ngậy, đậm đà vị phô mai và tỏi. Thêm vào đó là chút giòn tan của bánh mì nướng và vị mặn mà của phô mai Parmesan bào sợi, tạo nên một món ăn vừa đủ đầy, vừa thanh mát và vô cùng lôi cuốn.','/Coso/assets/img/mon_an/Kale-Caesar-Pasta-vegan-72.jpg',85000,100000,310,21,12,23,8,11,'2025-06-18',3,1),(83,'Bánh Mousse Thanh Long','Đắm chìm trong sắc hồng rực rỡ và hương vị thanh mát! Bánh Mousse Thanh Long Phô Mai Sống là món tráng miệng độc đáo, kết hợp sự mềm mịn, béo ngậy của lớp mousse phô mai thuần chay, tan chảy trong miệng, cùng vị ngọt dịu và hương thơm đặc trưng của thanh long tươi. Đây là lựa chọn hoàn hảo cho những ai yêu thích ẩm thực lành mạnh, không qua nấu nướng, mang đến cảm giác sảng khoái và một bữa tiệc thị giác đầy màu sắc.','/Coso/assets/img/mon_an/Raw-Dragon-Fruit-Cheesecake-Mousse-72.jpg',35000,45000,110,9,23,25,18,7,'2025-06-18',3,1),(84,'Thanh Yến Mạch Xoài Dừa','Một món ăn vặt tiện lợi và đầy năng lượng, mang đậm hương vị nhiệt đới! Thanh Yến Mạch Xoài Dừa là sự kết hợp hoàn hảo giữa những hạt yến mạch giòn bùi, vị ngọt thơm của xoài chín mọng và sự béo ngậy, quyến rũ từ dừa. Mỗi thanh bánh là một tổng hòa của các hương vị tươi mới và kết cấu hấp dẫn, là lựa chọn lý tưởng cho bữa sáng nhanh gọn, bữa ăn nhẹ giữa buổi hoặc khi bạn cần nạp năng lượng tức thì.','/Coso/assets/img/mon_an/Mango-Coconut-Oat-Bar-72.jpg',55000,65000,150,10,24,21,18,6,'2025-06-18',6,1),(85,'Trứng Bông Rau Xanh','Bắt đầu ngày mới đầy năng lượng với Trứng Cuộn Protein Chipotle! Món ăn hấp dẫn này là sự kết hợp hoàn hảo giữa những quả trứng tươi được đánh bông và cuộn mềm mại, cùng với hương vị cay nồng, khói nhẹ đặc trưng từ ớt Chipotle. ','/Coso/assets/img/mon_an/Chipotle-Protein-Scramble-72.jpg',55000,65000,210,20,9,22,13,12,'2025-06-18',7,1),(86,'Gỏi Gà Đậu Hũ Giòn Kiểu Hy Lạp','Một món gỏi thanh mát, giòn ngon chuẩn vị Địa Trung Hải! Gỏi Gà Đậu Hũ Giòn Kiểu Hy Lạp là sự kết hợp hoàn hảo giữa những hạt đậu gà được tẩm ướp gia vị và nướng/chiên giòn rụm, cùng với các loại rau tươi như xà lách, dưa chuột, cà chua bi, và phô mai feta mặn mà. ','/Coso/assets/img/mon_an/Crispy-Chickpea-Greek-Salad-vegan-72.jpg',90000,130000,310,18,11,32,4,10,'2025-06-18',5,1),(87,'Món Hầm Ngũ Vị','Mang hương vị Bắc Phi đầy mê hoặc đến bàn ăn của bạn với Món Hầm Đất Nung Tagine! Đây là món ăn truyền thống được chế biến chậm rãi trong chiếc nồi đất nung đặc trưng, giúp giữ trọn vẹn hương vị và dưỡng chất. ','/Coso/assets/img/mon_an/Fire-Roasted-Tomato-Tajine-vegan-72.jpg',100000,120000,420,21,30,34,12,15,'2025-06-18',5,1),(88,'Bánh Mousse Bạc Hà Socola','Đắm chìm trong sự kết hợp tươi mát và ngọt ngào! Bánh Mousse Bạc Hà Socola là món tráng miệng độc đáo, với lớp mousse bạc hà xanh mát, mềm mịn, tan chảy trong miệng, phía dưới là lớp bánh quy socola giòn tan hoặc vụn bánh cacao đậm đà.','/Coso/assets/img/mon_an/Grasshopper-Mousse-Cup-72.jpg',65000,75000,170,10,16,34,15,6,'2025-06-18',6,1),(89,'Phô Mai Cà Chua Khô','Một lựa chọn khai vị sáng tạo và đầy hấp dẫn! Đĩa \"Phô Mai\" Cà Chua Khô mang đến trải nghiệm thưởng thức tinh tế, với những lát \"phô mai\" làm từ hạt điều hoặc các nguyên liệu thực vật khác, được tẩm ướp đậm đà và có độ béo ngậy tương tự phô mai truyền thống. ','/Coso/assets/img/mon_an/Sun-Dried-Snack-Pack-72.jpg',75000,85000,210,9,21,21,6,6,'2025-06-18',6,1),(90,'Khoai Lang Chiên Thập Cẩm','Một món ăn sáng hoặc ăn kèm đầy màu sắc và dinh dưỡng! Khoai Lang Chiên Thập Cẩm là sự kết hợp tuyệt vời giữa những miếng khoai lang thái hạt lựu được chiên vàng giòn rụm, cùng với các loại rau củ tươi ngon như hành tây, ớt chuông, và thêm chút thịt xông khói hoặc xúc xích thái hạt lựu. Món ăn này không chỉ bắt mắt mà còn mang đến hương vị ngọt bùi của khoai lang hòa quyện cùng vị đậm đà của các nguyên liệu khác, là lựa chọn hoàn hảo để bắt đầu ngày mới tràn đầy năng lượng.','/Coso/assets/img/mon_an/Sweet-Potato-Hash-72.jpg',90000,120000,380,12,1,12,4,1,'2025-06-18',4,1),(91,'Cơm Thố Ngọc Ruby','Đánh thức mọi giác quan với Cơm Thố Ngọc Ruby! Món ăn này là một bản giao hưởng sắc màu và hương vị, nổi bật với nền cơm hạt đỏ óng ả như ngọc ruby, kết hợp cùng các loại rau củ tươi thái hạt lựu đầy màu sắc như ớt chuông đỏ, bắp cải tím, và cà rốt. Thêm vào đó là những miếng thịt nướng hoặc đậu phụ áp chảo mềm mại, tất cả được rưới đều bằng loại nước xốt đặc biệt, tạo nên một bữa ăn không chỉ đẹp mắt mà còn bổ dưỡng và tràn đầy năng lượng.','/Coso/assets/img/mon_an/Ruby-Rice-Bowl-vegan-72.jpg',120000,140000,350,22,21,12,6,12,'2025-06-18',8,1),(92,'Cơm Cuộn Đút Lò','Một món ăn Mexico ấm áp và đầy hương vị cho cả gia đình! Cơm Cuộn Enchilada Đút Lò là sự kết hợp hấp dẫn giữa những miếng bánh Tortilla mềm mại, được cuộn chặt với nhân thịt bò/gà băm hoặc đậu đậm đà, sau đó phủ ngập trong xốt Enchilada đỏ sánh mịn và rắc lớp phô mai béo ngậy. Món ăn này sau khi được đút lò sẽ có màu vàng óng, thơm lừng, mang đến trải nghiệm ẩm thực nồng nàn, ấm cúng và vô cùng khó quên.','/Coso/assets/img/mon_an/Enchilada-Bake-vegan-72.jpg',190000,210000,360,19,12,23,8,12,'2025-06-18',7,1),(93,'Thanh Bánh Khoai Mỡ Tím','Một món ăn vặt vừa lạ miệng vừa bổ dưỡng từ vùng đất Okinawa! Thanh Bánh Khoai Mỡ Tím là sự kết hợp độc đáo giữa vị ngọt tự nhiên và màu tím quyến rũ của khoai mỡ tím, được nghiền mịn và kết hợp cùng các loại hạt giòn bùi cùng chút yến mạch thơm ngon.','/Coso/assets/img/mon_an/Okinawan-Sweet-Potato-Bar-72.jpg',55000,70000,195,21,20,12,10,3,'2025-06-18',5,1);
/*!40000 ALTER TABLE `sanpham` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sanphamthanhphan`
--

DROP TABLE IF EXISTS `sanphamthanhphan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sanphamthanhphan` (
  `Masp` int NOT NULL,
  `Thanhphanid` int NOT NULL,
  PRIMARY KEY (`Masp`,`Thanhphanid`),
  KEY `Thanhphanid` (`Thanhphanid`),
  CONSTRAINT `sanphamthanhphan_ibfk_1` FOREIGN KEY (`Masp`) REFERENCES `sanpham` (`Masp`) ON DELETE CASCADE,
  CONSTRAINT `sanphamthanhphan_ibfk_2` FOREIGN KEY (`Thanhphanid`) REFERENCES `thanhphan` (`Idthanhphan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sanphamthanhphan`
--

LOCK TABLES `sanphamthanhphan` WRITE;
/*!40000 ALTER TABLE `sanphamthanhphan` DISABLE KEYS */;
INSERT INTO `sanphamthanhphan` VALUES (15,1),(24,1),(52,1),(53,1),(55,1),(56,1),(62,1),(81,1),(85,1),(48,2),(23,3),(66,3),(69,3),(17,5),(20,5),(27,6),(52,6),(54,6),(4,7),(28,7),(29,7),(50,7),(54,7),(57,7),(68,7),(1,8),(8,8),(17,8),(22,8),(58,8),(1,9),(17,9),(18,9),(19,9),(20,9),(43,9),(73,9),(93,9),(1,10),(10,10),(17,10),(18,10),(26,10),(29,10),(57,10),(65,10),(91,10),(1,11),(4,11),(8,11),(24,11),(26,11),(28,11),(61,11),(74,11),(89,11),(1,12),(1,13),(2,13),(4,13),(9,13),(15,13),(18,13),(25,13),(27,13),(42,13),(45,13),(46,13),(47,13),(49,13),(54,13),(63,13),(64,13),(82,13),(86,13),(89,13),(92,13),(1,14),(54,14),(2,15),(9,15),(10,15),(18,15),(19,15),(24,15),(25,15),(26,15),(27,15),(2,16),(5,16),(10,16),(50,16),(64,16),(81,16),(89,16),(2,17),(17,17),(27,17),(29,17),(2,18),(2,19),(2,20),(5,20),(20,20),(25,20),(11,21),(12,21),(15,21),(20,21),(30,21),(53,21),(8,22),(27,22),(5,23),(15,23),(17,23),(19,23),(25,23),(55,23),(61,23),(4,24),(21,24),(4,25),(21,25),(4,26),(8,26),(27,26),(28,26),(5,27),(5,28),(19,28),(5,29),(8,30),(8,31),(82,31),(8,32),(28,32),(9,33),(76,33),(10,34),(26,34),(68,34),(10,35),(26,35),(65,35),(68,35),(87,35),(91,35),(11,36),(90,36),(11,37),(27,37),(61,37),(62,37),(90,37),(11,38),(12,38),(50,38),(64,38),(11,39),(11,40),(12,41),(30,41),(50,41),(62,41),(63,41),(70,41),(85,41),(12,42),(30,42),(12,43),(71,43),(91,45),(19,46),(20,47),(20,48),(21,49),(21,50),(24,50),(22,51),(80,51),(84,51),(22,52),(23,53),(23,54),(24,55),(24,56),(25,56),(26,57),(65,64),(43,65),(47,65),(66,65),(69,65),(92,65),(28,67),(28,68),(48,68),(57,68),(58,68),(67,68),(28,69),(72,69),(90,69),(29,70),(30,71),(61,71),(42,72),(45,72),(42,73),(44,73),(45,73),(46,73),(47,73),(59,73),(60,73),(78,73),(42,74),(44,74),(48,74),(56,74),(60,74),(71,74),(73,74),(84,74),(93,74),(43,75),(44,75),(47,75),(51,75),(59,75),(69,75),(79,75),(88,75),(43,76),(45,77),(52,77),(59,77),(71,77),(46,78),(47,79),(49,80),(49,81),(51,82),(68,82),(80,82),(84,82),(52,83),(56,83),(74,83),(53,84),(56,85),(60,86),(62,87),(63,88),(72,88),(63,89),(63,90),(65,91),(75,91),(66,92),(67,93),(70,94),(70,95),(76,95),(70,96),(86,96),(87,96),(71,97),(72,98),(72,99),(92,99),(72,100),(72,101),(73,102),(74,102),(77,102),(93,102),(74,103),(75,104),(75,105),(76,106),(76,107),(85,107),(87,107),(91,107),(77,108),(77,109),(77,110),(77,111),(78,112),(78,113),(78,114),(79,115),(79,116),(79,117),(80,118),(80,119),(81,120),(86,120),(81,121),(82,122),(82,123),(83,124),(83,125),(83,126),(83,127),(84,128),(86,129),(87,130),(88,131),(88,132),(88,133),(89,134),(90,135),(92,136),(93,137);
/*!40000 ALTER TABLE `sanphamthanhphan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suckhoe_loai`
--

DROP TABLE IF EXISTS `suckhoe_loai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suckhoe_loai` (
  `Mathongtin` int NOT NULL,
  `Maloai` int NOT NULL,
  PRIMARY KEY (`Mathongtin`,`Maloai`),
  KEY `Maloai` (`Maloai`),
  CONSTRAINT `suckhoe_loai_ibfk_1` FOREIGN KEY (`Mathongtin`) REFERENCES `khachhang_suckhoe` (`id`),
  CONSTRAINT `suckhoe_loai_ibfk_2` FOREIGN KEY (`Maloai`) REFERENCES `loai` (`Maloai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suckhoe_loai`
--

LOCK TABLES `suckhoe_loai` WRITE;
/*!40000 ALTER TABLE `suckhoe_loai` DISABLE KEYS */;
INSERT INTO `suckhoe_loai` VALUES (16,1),(17,1),(18,1),(20,1),(21,1),(24,1),(25,1),(15,2),(16,4),(18,4),(20,4),(21,4),(24,4),(15,5),(24,5),(22,6),(19,7),(23,7),(22,8),(23,8),(25,8);
/*!40000 ALTER TABLE `suckhoe_loai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `thanhphan`
--

DROP TABLE IF EXISTS `thanhphan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `thanhphan` (
  `Idthanhphan` int NOT NULL AUTO_INCREMENT,
  `Tenthanhphan` varchar(255) NOT NULL,
  `MaThanhPhan` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Idthanhphan`),
  UNIQUE KEY `Tenthanhphan` (`Tenthanhphan`),
  UNIQUE KEY `MaThanhPhan` (`MaThanhPhan`)
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `thanhphan`
--

LOCK TABLES `thanhphan` WRITE;
/*!40000 ALTER TABLE `thanhphan` DISABLE KEYS */;
INSERT INTO `thanhphan` VALUES (1,'trứng','trung'),(2,'sữa','sua'),(3,'bơ',NULL),(4,'bột cacao',NULL),(5,'Đường',NULL),(6,'Xúc xích',NULL),(7,'Thịt gà',NULL),(8,'Thịt bò',NULL),(9,'mật ong',NULL),(10,'đậu xanh',NULL),(11,'cà rốt',NULL),(12,'ớt xanh',NULL),(13,'phomai',NULL),(14,'bí ngô',NULL),(15,'Gà',NULL),(16,'cà chua',NULL),(17,'tỏi',NULL),(18,'cỏ xạ hương',NULL),(19,'cà sốt',NULL),(20,'bông cải xanh',NULL),(21,'Thịt heo',NULL),(22,'nấm',NULL),(23,'khoai tây',NULL),(24,'bí xanh',NULL),(25,'bí ngòi',NULL),(26,'bông cải',NULL),(27,'Ức gà',NULL),(28,'ớt đỏ',NULL),(29,'hạt thông',NULL),(30,'vang đỏ',NULL),(31,'mì ý',NULL),(32,'hành','hanh'),(33,'mì',NULL),(34,'cà ri',NULL),(35,'cơm',NULL),(36,'ớt chuông',NULL),(37,'hành tây',NULL),(38,'đậu đen',NULL),(39,'gạo',NULL),(40,'bắp cải xanh',NULL),(41,'ớt',NULL),(42,'ngô',NULL),(43,'chanh',NULL),(44,'rau xào',NULL),(45,'táo',NULL),(46,'mùi tạt',NULL),(47,'bắp cải tím',NULL),(48,'táo chua',NULL),(49,'Cá hồi',NULL),(50,'súp lơ',NULL),(51,'xoài',NULL),(52,'đậu đỏ',NULL),(53,'Bánh mì',NULL),(54,'khoai mì',NULL),(55,'bắp cải',NULL),(56,'hành lá',NULL),(57,'cà tím',NULL),(64,'Hải sản','haisan'),(65,'Đậu phộng','dauphong'),(66,'Các loại hạt','hat'),(67,'thơm',NULL),(68,'việt quất',NULL),(69,'khoai lang',NULL),(70,'húng quế',NULL),(71,'rau mùi',NULL),(72,'kem',NULL),(73,'hạnh nhân',NULL),(74,'yến mạch',NULL),(75,'Socola',NULL),(76,'váng có',NULL),(77,'vani',NULL),(78,'dâu tây',NULL),(79,'nho',NULL),(80,'ca cao',NULL),(81,'espresso',NULL),(82,'Dừa',NULL),(83,'Quế',NULL),(84,'kim chi',NULL),(85,'xúc xích gà',NULL),(86,'Váng sữa',NULL),(87,'bánh ngô',NULL),(88,'Đậu phụ',NULL),(89,'rong biển',NULL),(90,'mì ống',NULL),(91,'sữa dừa',NULL),(92,'Chuối',NULL),(93,'hạnh nhân. rau xanh',NULL),(94,'Đậu gà',NULL),(95,'chanh tươi',NULL),(96,'dầu ô liu',NULL),(97,'Ngũ cốc',NULL),(98,'Miến',NULL),(99,'rau',NULL),(100,'dầu mè',NULL),(101,'nước tương',NULL),(102,'Hạt dinh dưỡng',NULL),(103,'bột mì',NULL),(104,'Gạo lứt',NULL),(105,'chất tạo ngọt tự nhiên',NULL),(106,'súp Miso',NULL),(107,'rau củ',NULL),(108,'Sinh tố trái cây',NULL),(109,'quả mọng',NULL),(110,'rau củ tạo màu',NULL),(111,'trái cây tươi',NULL),(112,'anh đào khô',NULL),(113,'bột Moringa',NULL),(114,'bơ ca cao',NULL),(115,'Bột bánh quy',NULL),(116,'nấm Linh Chi',NULL),(117,'bơ thực vật',NULL),(118,'nước cốt dừa',NULL),(119,'bạc hà',NULL),(120,'Rau xanh',NULL),(121,'xốt Ranch',NULL),(122,'cải xoăn',NULL),(123,'xốt Caesar',NULL),(124,'Thanh long',NULL),(125,'hạt điều',NULL),(126,'chà là',NULL),(127,'nước cốt chanh',NULL),(128,'mật ong/chất tạo ngọt tự nhiên',NULL),(129,'Đậu gà chiên',NULL),(130,'gia vị Bắc Phi',NULL),(131,'Mousse bạc hà',NULL),(132,'kem tươi',NULL),(133,'bánh quy cacao',NULL),(134,'mạch nha',NULL),(135,'thịt xông khói.',NULL),(136,'Bánh Tortilla',NULL),(137,'Khoai mỡ tím',NULL);
/*!40000 ALTER TABLE `thanhphan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'tqfood'
--

--
-- Dumping routines for database 'tqfood'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-19  8:05:12
