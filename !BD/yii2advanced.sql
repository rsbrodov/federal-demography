-- MySQL dump 10.10
--
-- Host: 192.168.1.3    Database: yii2advanced
-- ------------------------------------------------------
-- Server version	5.6.47-log

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
-- Table structure for table `age_info`
--

DROP TABLE IF EXISTS `age_info`;
CREATE TABLE `age_info` (
  `id` int(11) auto_increment,
  `name` varchar(255),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `age_info`
--


/*!40000 ALTER TABLE `age_info` DISABLE KEYS */;
LOCK TABLES `age_info` WRITE;
INSERT INTO `age_info` VALUES (2,'2-3 года','2020-05-14 02:48:50'),(5,'с 3 до 7 лет','2020-05-14 02:48:50'),(6,'7-11 лет','2020-05-14 02:48:50'),(7,'11-18 лет','2020-05-14 02:48:50'),(8,'с 1 до 7 лет ','2020-05-14 02:48:50'),(9,'7-18 лет','2020-05-14 02:48:50'),(10,'Персонал','2020-05-14 02:48:50');
UNLOCK TABLES;
/*!40000 ALTER TABLE `age_info` ENABLE KEYS */;

--
-- Table structure for table `anket_children`
--

DROP TABLE IF EXISTS `anket_children`;
CREATE TABLE `anket_children` (
  `id` int(11) auto_increment,
  `federal_district_id` int(11),
  `region_id` int(11),
  `place_residence` int(11),
  `school` varchar(250),
  `class` int(11),
  `child_age` int(11),
  `sex` int(11),
  `body_weight` int(11),
  `body_length` int(11),
  `eat_in_school` int(11),
  `dont_eat` text,
  `where_eat` int(11),
  `what_take` int(11),
  `other` text,
  `tasty_food` int(11),
  `food_warm` int(11),
  `enough_time` int(11),
  `menu_varied` int(11),
  `choice_dishes` int(11),
  `clean_dishes` int(11),
  `enough_space` int(11),
  `eat_class` int(11),
  `tasteless_food` int(11),
  `cold_food` int(11),
  `dont_enough_time` int(11),
  `monotony_menu` int(11),
  `dont_choice_dishes` int(11),
  `dont_clean_dishes` int(11),
  `dont_enough_space` int(11),
  `satisfied` int(11),
  `offers` text,
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `anket_children`
--


/*!40000 ALTER TABLE `anket_children` DISABLE KEYS */;
LOCK TABLES `anket_children` WRITE;
INSERT INTO `anket_children` VALUES (1,5,48,1,'rgdgdr',9,9,0,13,26,3,'',0,1,'',0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,9,'','2020-04-20 04:21:51'),(2,5,48,1,'rgdgdr',9,9,0,13,26,2,'',0,1,'',0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,9,'','2020-04-20 04:21:51'),(3,5,48,1,'rgdgdr',9,9,1,13,26,0,'',3,1,'',1,0,0,1,0,1,1,0,1,0,0,1,0,1,0,5,'','2020-04-20 04:21:51');
UNLOCK TABLES;
/*!40000 ALTER TABLE `anket_children` ENABLE KEYS */;

--
-- Table structure for table `anket_parents_school_children`
--

DROP TABLE IF EXISTS `anket_parents_school_children`;
CREATE TABLE `anket_parents_school_children` (
  `id` int(11) auto_increment,
  `federal_district_id` int(11),
  `region_id` int(11),
  `place_residence` varchar(250) DEFAULT '',
  `school` varchar(250) DEFAULT '',
  `class` int(11),
  `age` int(11),
  `sex` int(11),
  `body_weight` int(11),
  `body_length` int(11),
  `obtain_information` int(11),
  `obtain_information_otther` int(11),
  `delicious_food` int(11),
  `enough_time_eat` int(11),
  `menu_quite_diverse` int(11),
  `choice_dishes` int(11),
  `clean_dishes` int(11),
  `comfort_food_children` int(11),
  `not_delicious_food` int(11),
  `not_enough_time_eat` int(11),
  `not_menu_quite_diverse` int(11),
  `not_choice_dishes` int(11),
  `not_clean_dishes` int(11),
  `not_comfort_food_children` int(11),
  `rate_overall_satisfaction` int(11),
  `offers` text,
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `anket_parents_school_children`
--


/*!40000 ALTER TABLE `anket_parents_school_children` DISABLE KEYS */;
LOCK TABLES `anket_parents_school_children` WRITE;
INSERT INTO `anket_parents_school_children` VALUES (1,1,2,'кпкпкп','кпкпкпк',1,15,0,123,123,0,NULL,1,1,1,1,1,1,1,1,1,1,1,0,8,'птпт','2020-04-21 05:27:20'),(2,1,1,'кпкпкп','кпкпкпк',1,15,0,123,123,1,NULL,1,1,1,1,1,1,1,1,1,1,1,0,8,'','2020-04-21 05:27:20'),(3,1,2,'кпкпкп','кпкпкпк23',1,15,0,123,123,0,NULL,1,1,1,1,1,1,1,1,1,1,1,0,8,'','2020-04-21 05:27:20');
UNLOCK TABLES;
/*!40000 ALTER TABLE `anket_parents_school_children` ENABLE KEYS */;

--
-- Table structure for table `anket_preschoolers`
--

DROP TABLE IF EXISTS `anket_preschoolers`;
CREATE TABLE `anket_preschoolers` (
  `id` int(11) auto_increment,
  `federal_district_id` int(11),
  `region_id` int(11),
  `place_residence` varchar(250) DEFAULT '',
  `kindergarten` varchar(250) DEFAULT '',
  `child_age` int(11),
  `sex` int(11),
  `body_weight` int(11),
  `body_length` int(11),
  `obtain_information` int(11),
  `obtain_information_other` varchar(250),
  `delicious_food` int(11),
  `food_always_warm` int(11),
  `menu_varied` int(11),
  `always_clean_dishes` int(11),
  `food_not_good` int(11),
  `served_cold` int(11),
  `monotonous_menu` int(11),
  `not_always_clean_dishes` int(11),
  `satisfied` int(11),
  `offers` text,
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `anket_preschoolers`
--


/*!40000 ALTER TABLE `anket_preschoolers` DISABLE KEYS */;
LOCK TABLES `anket_preschoolers` WRITE;
INSERT INTO `anket_preschoolers` VALUES (1,1,1,'f','123',0,0,123,123,0,'',1,0,1,0,0,1,1,1,0,'','2020-04-14 10:00:40'),(2,2,14,'fff','123fdsfs',0,1,123,123,2,'dfdfd',0,1,0,0,0,1,1,0,1,'','2020-04-14 10:05:21'),(3,6,57,'34234','123fdsfs34',3,0,1231,12321,0,'',1,0,1,1,1,0,0,0,2,'','2020-04-14 10:07:53'),(4,5,48,'fff33','123fdsfs4353',5,1,1231231,1231231,0,'',1,1,0,0,1,0,1,1,3,'','2020-04-14 10:08:25'),(5,1,1,'f','123',0,0,123,123,2,'',1,0,1,0,0,1,1,1,4,'','2020-04-14 10:00:40'),(6,1,1,'f','123',0,0,123,123,1,'',1,0,1,0,0,1,1,1,5,'','2020-04-14 10:00:40'),(7,1,1,'f','123',0,0,123,123,0,'',1,0,1,0,0,1,1,1,6,'','2020-04-14 10:00:40'),(8,1,2,'f','123',0,0,123,123,0,'',1,0,1,0,0,1,1,1,9,'','2020-04-14 10:00:40'),(10,1,3,'f','123',0,0,123,123,0,'',1,0,1,0,0,1,1,1,9,'','2020-04-14 10:00:40'),(11,1,1,'f','1234',0,0,123,123,0,'',1,0,1,0,0,1,1,1,9,'','2020-04-14 10:00:40'),(13,1,1,'f','123',0,0,123,123,2,'',1,0,1,0,0,1,1,1,9,'','2020-04-14 10:00:40');
UNLOCK TABLES;
/*!40000 ALTER TABLE `anket_preschoolers` ENABLE KEYS */;

--
-- Table structure for table `anket_teacher`
--

DROP TABLE IF EXISTS `anket_teacher`;
CREATE TABLE `anket_teacher` (
  `id` int(11) auto_increment,
  `federal_district_id` int(11),
  `region_id` int(11),
  `place_residence` varchar(250) DEFAULT '',
  `school` varchar(250) DEFAULT '',
  `class` int(11),
  `percentage_children` int(11),
  `delicious_food` int(11),
  `food_always_warm` int(11),
  `time_eat` int(11),
  `menu_varied` int(11),
  `choice_dishes` int(11),
  `always_clean_dishes` int(11),
  `enough_space` int(11),
  `feed_whole_class` int(11),
  `not_delicious_food` int(11),
  `not_food_always_warm` int(11),
  `not_time_eat` int(11),
  `not_menu_varied` int(11),
  `not_choice_dishes` int(11),
  `not_always_clean_dishes` int(11),
  `not_enough_space` int(11),
  `rate_overall_satisfaction` int(11),
  `offers` text,
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `anket_teacher`
--


/*!40000 ALTER TABLE `anket_teacher` DISABLE KEYS */;
LOCK TABLES `anket_teacher` WRITE;
INSERT INTO `anket_teacher` VALUES (1,1,1,'df','fe',1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,'klkl','0000-00-00 00:00:00'),(2,1,4,'df','fe2',2,20,1,1,0,1,1,1,1,1,0,0,0,0,0,0,0,1,'','2020-04-20 08:46:13'),(3,1,1,'df','fe13',1,1,0,0,1,1,1,1,1,1,1,1,1,1,1,1,0,1,'klkl','0000-00-00 00:00:00'),(4,1,1,'df','fe',1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,9,'klkl','0000-00-00 00:00:00');
UNLOCK TABLES;
/*!40000 ALTER TABLE `anket_teacher` ENABLE KEYS */;

--
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) DEFAULT '',
  `user_id` varchar(64) DEFAULT '',
  `created_at` int(11),
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `idx-auth_assignment-user_id` (`user_id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `auth_assignment`
--


/*!40000 ALTER TABLE `auth_assignment` DISABLE KEYS */;
LOCK TABLES `auth_assignment` WRITE;
INSERT INTO `auth_assignment` VALUES ('admin','2',1582129819),('admin','25',1585822780),('admin','3',1585671038),('camp_director','33',1586157788),('foodworker','41',1586227693),('food_director','40',1586225835),('internat_director','36',1586158370),('medic','29',1585903166),('medic','42',1586227772),('medic','43',1586227929),('medic','45',1586228552),('medic','47',1586506065),('school_director','10',1585707781),('school_director','11',1585710416),('school_director','12',1585711656),('school_director','13',1585713011),('school_director','15',1585713782),('school_director','16',1585714238),('school_director','17',1585714642),('school_director','18',1585714783),('school_director','19',1585714873),('school_director','20',1585715200),('school_director','21',1585729458),('school_director','22',1585730630),('school_director','23',1585730873),('school_director','24',1585793009),('school_director','26',1585890067),('school_director','27',1585890077),('school_director','28',1585890091),('school_director','32',1586157133),('school_director','34',1586158117),('school_director','35',1586158297),('school_director','37',1586158486),('school_director','38',1586158612),('school_director','39',1586163174),('school_director','46',1586492214),('teacher','30',1585907979),('teacher','31',1585908984),('teacher','44',1586228134);
UNLOCK TABLES;
/*!40000 ALTER TABLE `auth_assignment` ENABLE KEYS */;

--
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE `auth_item` (
  `name` varchar(64) DEFAULT '',
  `type` smallint(6),
  `description` text,
  `rule_name` varchar(64),
  `data` blob,
  `created_at` int(11),
  `updated_at` int(11),
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `auth_item`
--


/*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;
LOCK TABLES `auth_item` WRITE;
INSERT INTO `auth_item` VALUES ('admin',1,'Админ',NULL,NULL,1581857543,1581857543),('camp_director',1,'Администратор загородных организаций отдыха и оздоровления',NULL,NULL,NULL,NULL),('foodworker',1,'Работник столовой',NULL,NULL,NULL,NULL),('food_director',1,'Организатор питания',NULL,NULL,NULL,NULL),('internat_director',1,'Учреждение для детей с круглосуточным прибыванием',NULL,NULL,NULL,NULL),('kindergarten_director',1,'Директор дошкольного образования',NULL,NULL,NULL,NULL),('medic',1,'Медик',NULL,NULL,NULL,NULL),('school_director',1,'Директор школы',NULL,NULL,1581857545,1581857545),('subject_minobr',1,'Муниципальный орган управления образования',NULL,NULL,NULL,NULL),('teacher',1,'Учитель',NULL,NULL,NULL,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;

--
-- Table structure for table `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) DEFAULT '',
  `child` varchar(64) DEFAULT '',
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `auth_item_child`
--


/*!40000 ALTER TABLE `auth_item_child` DISABLE KEYS */;
LOCK TABLES `auth_item_child` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `auth_item_child` ENABLE KEYS */;

--
-- Table structure for table `auth_rule`
--

DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `name` varchar(64) DEFAULT '',
  `data` blob,
  `created_at` int(11),
  `updated_at` int(11),
  PRIMARY KEY (`name`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `auth_rule`
--


/*!40000 ALTER TABLE `auth_rule` DISABLE KEYS */;
LOCK TABLES `auth_rule` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `auth_rule` ENABLE KEYS */;

--
-- Table structure for table `basic_information`
--

DROP TABLE IF EXISTS `basic_information`;
CREATE TABLE `basic_information` (
  `id` int(10) auto_increment,
  `organization_id` int(10),
  `sklad_stelaji` int(10),
  `sklad_podtovarniki` int(10),
  `sklad_shkafi` int(10),
  `sklad_inoe` text,
  `ovoshi_stoli` int(10),
  `ovoshi_kartofel` int(10),
  `ovoshi_vanni` int(10),
  `ovoshi_rakovini` int(10),
  `ovoshi_inoe` text,
  `ovoshi2_stoli` int(10),
  `ovoshi2_vanna` int(10),
  `ovoshi2_cut_machine` int(10),
  `ovoshi2_holodilnik` int(10),
  `ovoshi2_vesi` int(10),
  `ovoshi2_rakovina` int(10),
  `ovoshi2_inoe` text,
  `holod_stoli` int(10),
  `holod_vesi` int(10),
  `holod_shkafi` int(10),
  `holod_privod_and_machine` int(10),
  `holod_vanni` int(10),
  `holod_rakovina` int(10),
  `holod_chasi` int(10),
  `holod_inoe` text,
  `meat_stoli` int(10),
  `meat_vesi` int(10),
  `meat_skafi` int(10),
  `meat_cut` int(10),
  `meat_coloda` int(10),
  `meat_rakovini` int(10),
  `meat_inoe` text,
  `egg_stoli` int(10),
  `egg_emkosti` int(11),
  `egg_perf_emkosti` int(11),
  `egg_bactery` int(11),
  `egg_obrab_emkosti` int(11),
  `egg_rakovini` int(10),
  `egg_chasi` int(10),
  `egg_inoe` text,
  `muchnoi_stoli` int(10),
  `muchnoi_conditer` int(10),
  `muchnoi_podvod` int(10),
  `muchnoi_vesi` int(10),
  `muchnoi_shkafi` int(10),
  `muchnoi_stelaji` int(10),
  `muchnoi_vanni` int(10),
  `muchnoi_rakovini` int(10),
  `muchnoi_inoe` text,
  `dogotov_stoli` int(10),
  `dogotov_vesi` int(10),
  `dogotov_shkafi` int(10),
  `dogotov_cut` int(10),
  `dogotov_vanni` int(10),
  `dogotov_rakovini` int(10),
  `dogotov_inoe` text,
  `hleb_stoli` int(10),
  `hleb_cut` int(10),
  `hleb_shkafi` int(10),
  `hleb_rakovini` int(10),
  `hleb_inoe` text,
  `hot_stoli` int(10),
  `hot_plita` int(10),
  `hot_skovoroda` int(10),
  `hot_shkafi` int(10),
  `hot_electroprivod` int(10),
  `hot_kotel` int(10),
  `hot_vesi` int(10),
  `hot_rakovini` int(10),
  `hot_chasi` int(10),
  `hot_inoe` text,
  `give_marmiti` int(10),
  `give_holod_prilavok` int(10),
  `give_neitral_prilavok` int(10),
  `give_inoe` text,
  `wash1_stoli` int(10),
  `wash1_machine` int(10),
  `wash1_3vanni` int(10),
  `wash1_2vanni` int(10),
  `wash1_stelaji` int(10),
  `wash1_rakovini` int(10),
  `wash1_inoe` text,
  `wash2_stoli` int(10),
  `wash2_vanni` int(10),
  `wash2_stelaji` int(10),
  `wash2_rakovini` int(10),
  `wash2_inoe` text,
  `wash3_vanni` int(10),
  `wash3_inoe` text,
  `prod_stoli` int(10),
  `prod_electroplita` int(10),
  `prod_shkafi` int(10),
  `prod_give` int(10),
  `prod_marmiti` int(10),
  `prod_rakovini` int(10),
  `prod_machine` int(10),
  `prod_inoe` int(10),
  `dishwash_machine` int(10),
  `dishwash_vanni` int(10),
  `dishwash_inventar` int(10),
  `dishwash_stelaji` int(10),
  `dishwash_rakovini` int(10),
  `dishwash_inoe` text,
  `foodroom_stoli` int(10),
  `foodroom_electroplita` int(10),
  `foodroom_svch` int(10),
  `foodroom_holodilnik` int(10),
  `foodroom_shkafi` int(10),
  `foodroom_vanni` int(10),
  `foodroom_rakovini` int(10),
  `foodroom_inoe` text,
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `basic_information`
--


/*!40000 ALTER TABLE `basic_information` DISABLE KEYS */;
LOCK TABLES `basic_information` WRITE;
INSERT INTO `basic_information` VALUES (2,0,12321313,NULL,NULL,'',NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','2020-04-08 07:50:29'),(3,1,6786,NULL,NULL,'',NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','2020-04-08 07:50:29'),(5,1,12321313,12,NULL,'',NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','2020-04-08 07:50:29'),(6,7,123213131,12,NULL,'',NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,'',NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','2020-04-08 07:50:29');
UNLOCK TABLES;
/*!40000 ALTER TABLE `basic_information` ENABLE KEYS */;

--
-- Table structure for table `class`
--

DROP TABLE IF EXISTS `class`;
CREATE TABLE `class` (
  `id` int(10) auto_increment,
  `number` int(10),
  `letter` varchar(10),
  `menu_id` int(10),
  `teacher_id` int(10),
  `count` int(10),
  `created_at` datetime DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `class`
--


/*!40000 ALTER TABLE `class` DISABLE KEYS */;
LOCK TABLES `class` WRITE;
INSERT INTO `class` VALUES (1,123,'123',0,44,123,'2020-04-07 09:55:34');
UNLOCK TABLES;
/*!40000 ALTER TABLE `class` ENABLE KEYS */;

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE `company` (
  `id` int(11) auto_increment,
  `name` varchar(255),
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `company`
--


/*!40000 ALTER TABLE `company` DISABLE KEYS */;
LOCK TABLES `company` WRITE;
INSERT INTO `company` VALUES (1,'ООО \"Result-s\"');
UNLOCK TABLES;
/*!40000 ALTER TABLE `company` ENABLE KEYS */;

--
-- Table structure for table `configuration_classes`
--

DROP TABLE IF EXISTS `configuration_classes`;
CREATE TABLE `configuration_classes` (
  `id` int(11) auto_increment,
  `id_user` int(11),
  `class_number` int(11),
  `class_letter` varchar(5),
  `list_structure` int(11),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `configuration_classes`
--


/*!40000 ALTER TABLE `configuration_classes` DISABLE KEYS */;
LOCK TABLES `configuration_classes` WRITE;
INSERT INTO `configuration_classes` VALUES (9,25,3,'Ж (7)',9,'2020-05-22 05:01:12'),(10,25,4,'Е (6)',12,'2020-05-22 05:04:34');
UNLOCK TABLES;
/*!40000 ALTER TABLE `configuration_classes` ENABLE KEYS */;

--
-- Table structure for table `culinary_processing`
--

DROP TABLE IF EXISTS `culinary_processing`;
CREATE TABLE `culinary_processing` (
  `id` int(11) auto_increment,
  `name` varchar(255),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `culinary_processing`
--


/*!40000 ALTER TABLE `culinary_processing` DISABLE KEYS */;
LOCK TABLES `culinary_processing` WRITE;
INSERT INTO `culinary_processing` VALUES (1,'Варка','2020-04-13 07:34:34'),(2,'Запекание','2020-04-13 07:34:59'),(3,'Иное (не предусматривает термической обработки)','2020-04-13 08:01:14'),(4,'Тушение','2020-04-13 08:01:28');
UNLOCK TABLES;
/*!40000 ALTER TABLE `culinary_processing` ENABLE KEYS */;

--
-- Table structure for table `daily_informations`
--

DROP TABLE IF EXISTS `daily_informations`;
CREATE TABLE `daily_informations` (
  `id` int(11) auto_increment,
  `id_class` int(11),
  `day` date,
  `children_day` int(11),
  `fed_children` int(11),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `daily_informations`
--


/*!40000 ALTER TABLE `daily_informations` DISABLE KEYS */;
LOCK TABLES `daily_informations` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `daily_informations` ENABLE KEYS */;

--
-- Table structure for table `daily_meals`
--

DROP TABLE IF EXISTS `daily_meals`;
CREATE TABLE `daily_meals` (
  `id` int(11) auto_increment,
  `id_daily_information` int(11),
  `id_menu` int(11),
  `id_meal` int(11),
  `quantity` int(11),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `daily_meals`
--


/*!40000 ALTER TABLE `daily_meals` DISABLE KEYS */;
LOCK TABLES `daily_meals` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `daily_meals` ENABLE KEYS */;

--
-- Table structure for table `days`
--

DROP TABLE IF EXISTS `days`;
CREATE TABLE `days` (
  `id` int(10) auto_increment,
  `name` varchar(255),
  `short_name` varchar(255),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `days`
--


/*!40000 ALTER TABLE `days` DISABLE KEYS */;
LOCK TABLES `days` WRITE;
INSERT INTO `days` VALUES (1,'Понедельник','Пн','2020-05-14 02:46:44'),(2,'Вторник','Вт','2020-05-14 02:46:44'),(3,'Среда','Ср','2020-05-14 02:46:44'),(4,'Четверг','Чт','2020-05-14 02:46:44'),(5,'Пятница','Пт','2020-05-14 02:46:44'),(6,'Суббота','Сб','2020-05-14 02:46:44'),(7,'Воскресенье','Вс','2020-05-14 02:46:44');
UNLOCK TABLES;
/*!40000 ALTER TABLE `days` ENABLE KEYS */;

--
-- Table structure for table `diseases`
--

DROP TABLE IF EXISTS `diseases`;
CREATE TABLE `diseases` (
  `id` int(11) auto_increment,
  `name` varchar(250),
  `status` int(11),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `diseases`
--


/*!40000 ALTER TABLE `diseases` DISABLE KEYS */;
LOCK TABLES `diseases` WRITE;
INSERT INTO `diseases` VALUES (1,'Анемия',0,'2020-04-22 04:39:09'),(2,'Гипертиреоз',0,'2020-04-22 04:39:13'),(3,'Гипотиреоз',0,'2020-04-22 04:39:19'),(4,'Сахарный диабет',0,'2020-04-22 04:39:24'),(5,'Фенилкетонурия',0,'2020-04-22 04:39:28'),(6,'Муковисцидоз',0,'2020-04-22 04:39:31'),(7,'Пищевая аллергия',0,'2020-04-22 04:39:36'),(8,'Органов дыхания',1,'2020-04-22 04:39:46'),(9,'Органов пищеварения',1,'2020-04-22 04:39:57'),(10,'Болезни системы кровообращения',1,'2020-04-22 04:40:04'),(11,'Органов нервной системы',1,'2020-04-22 04:40:10'),(12,'Опорно-двигательного аппарата',1,'2020-04-22 04:40:14');
UNLOCK TABLES;
/*!40000 ALTER TABLE `diseases` ENABLE KEYS */;

--
-- Table structure for table `dishes`
--

DROP TABLE IF EXISTS `dishes`;
CREATE TABLE `dishes` (
  `id` int(11) auto_increment,
  `name` varchar(255),
  `dishes_category_id` int(10),
  `recipes_collection_id` int(10),
  `description` text,
  `culinary_processing_id` int(10),
  `yield` int(10),
  `appearance` varchar(255),
  `consistency` varchar(255),
  `color` varchar(255),
  `taste` varchar(255),
  `smell` varchar(255),
  `techmup_number` varchar(255),
  `number_of_dish` int(10),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `dishes`
--


/*!40000 ALTER TABLE `dishes` DISABLE KEYS */;
LOCK TABLES `dishes` WRITE;
INSERT INTO `dishes` VALUES (1,'Test Dishes1',1,1,'Огурцы промыть, удалить плодоножки, верхушки и нарезать на порции. Подавать к мясным или рыбным блюдам',3,150,'1','1','1','1','1','1',0,'2020-04-14 05:54:26'),(2,'Test Dishes2',4,2,'Подготовленную свеклу отварить в кожуре, охладить, очистить, нарезать соломкой. Перед подачей заправить растительным маслом',1,200,'1','1','1','1','1','12',0,'2020-04-14 05:54:26'),(3,'Test Dishes3',3,3,'Огурцы промыть, удалить плодоножки, верхушки и нарезать на порции. Подавать к мясным или рыбным блюдам',1,180,'1','1','1','1','1','18',0,'2020-04-16 17:00:00'),(4,'Test Dishes4',5,1,'Огурцы промыть, удалить плодоножки, верхушки и нарезать на порции. Подавать к мясным или рыбным блюдам',1,160,'1','1','1','1','1','1',0,'2020-04-16 17:00:00');
UNLOCK TABLES;
/*!40000 ALTER TABLE `dishes` ENABLE KEYS */;

--
-- Table structure for table `dishes_category`
--

DROP TABLE IF EXISTS `dishes_category`;
CREATE TABLE `dishes_category` (
  `id` int(11) auto_increment,
  `name` varchar(255),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `dishes_category`
--


/*!40000 ALTER TABLE `dishes_category` DISABLE KEYS */;
LOCK TABLES `dishes_category` WRITE;
INSERT INTO `dishes_category` VALUES (1,'Блюда из круп – каши','2020-04-13 06:46:59'),(2,'Блюда из мяса','2020-04-13 06:47:18'),(3,'Блюда из рыбы','2020-04-13 06:47:31'),(4,'Блюда из яиц и творога','2020-04-13 06:47:40'),(5,'Гарниры','2020-04-13 06:47:47'),(6,'Горячие напитки','2020-04-13 06:47:56'),(7,'Первые блюда','2020-04-13 06:48:07'),(8,'Соусы','2020-04-13 06:48:17'),(9,'Холодные блюда','2020-04-13 06:48:25'),(10,'Холодные напитки','2020-04-13 06:48:32');
UNLOCK TABLES;
/*!40000 ALTER TABLE `dishes_category` ENABLE KEYS */;

--
-- Table structure for table `dishes_products`
--

DROP TABLE IF EXISTS `dishes_products`;
CREATE TABLE `dishes_products` (
  `id` int(11) auto_increment,
  `dishes_id` int(10),
  `products_id` int(10),
  `net_weight` float,
  `gross_weight` float,
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `dishes_products`
--


/*!40000 ALTER TABLE `dishes_products` DISABLE KEYS */;
LOCK TABLES `dishes_products` WRITE;
INSERT INTO `dishes_products` VALUES (4,1,1,28,35,'2020-04-21 07:56:41'),(5,1,2,28,35,'2020-04-21 07:58:17'),(9,3,4,50,5,'2020-04-21 08:18:00'),(10,3,3,92,26,'2020-04-21 08:18:32'),(11,2,1,5,180,'2020-04-21 10:13:26'),(13,2,2,27,32,'2020-04-22 07:19:49'),(14,4,2,19,32,'2020-04-22 07:20:28'),(15,4,4,29,32,'2020-04-22 07:20:35');
UNLOCK TABLES;
/*!40000 ALTER TABLE `dishes_products` ENABLE KEYS */;

--
-- Table structure for table `fact_day_menus_dishes`
--

DROP TABLE IF EXISTS `fact_day_menus_dishes`;
CREATE TABLE `fact_day_menus_dishes` (
  `id` int(11) auto_increment,
  `date_day` date,
  `menu_id` int(10),
  `cycle` int(10),
  `days_id` int(10),
  `nutrition_id` int(10),
  `dishes_id` int(10),
  `yield` int(10),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `fact_day_menus_dishes`
--


/*!40000 ALTER TABLE `fact_day_menus_dishes` DISABLE KEYS */;
LOCK TABLES `fact_day_menus_dishes` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `fact_day_menus_dishes` ENABLE KEYS */;

--
-- Table structure for table `fact_menus_dishes`
--

DROP TABLE IF EXISTS `fact_menus_dishes`;
CREATE TABLE `fact_menus_dishes` (
  `id` int(11) auto_increment,
  `date_of_day` int(25),
  `menus_dishes_id` int(10),
  `indicator` int(10),
  `menu_id` int(10),
  `cycle` int(10),
  `days_id` int(10),
  `nutrition_id` int(10),
  `dishes_id` int(10),
  `yield` int(10),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `fact_menus_dishes`
--


/*!40000 ALTER TABLE `fact_menus_dishes` DISABLE KEYS */;
LOCK TABLES `fact_menus_dishes` WRITE;
INSERT INTO `fact_menus_dishes` VALUES (2,1586120400,0,2,21,2,1,1,3,666,'2020-05-27 02:49:14'),(3,1586120400,27,1,0,0,0,0,0,0,'2020-05-27 04:09:44'),(5,1586120400,0,2,21,2,1,1,2,666,'2020-05-27 02:49:14');
UNLOCK TABLES;
/*!40000 ALTER TABLE `fact_menus_dishes` ENABLE KEYS */;

--
-- Table structure for table `federal_district`
--

DROP TABLE IF EXISTS `federal_district`;
CREATE TABLE `federal_district` (
  `id` int(10) auto_increment,
  `name` varchar(255),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `federal_district`
--


/*!40000 ALTER TABLE `federal_district` DISABLE KEYS */;
LOCK TABLES `federal_district` WRITE;
INSERT INTO `federal_district` VALUES (1,'Дальневосточный федеральный округ','2020-04-08 07:47:11'),(2,'Приволжский федеральный округ','2020-04-08 07:47:11'),(3,'Северо-Западный федеральный округ\r\n','2020-04-14 09:01:54'),(4,'Северо-Кавказский федеральный округ','2020-04-14 09:02:00'),(5,'Сибирский федеральный округ','2020-04-14 09:02:06'),(6,'Уральский федеральный округ','2020-04-14 09:02:16'),(7,'Центральный федеральный округ\r\n','2020-04-14 09:02:17'),(8,'Южный федеральный округ','2020-04-14 09:02:21');
UNLOCK TABLES;
/*!40000 ALTER TABLE `federal_district` ENABLE KEYS */;

--
-- Table structure for table `feeders_characters`
--

DROP TABLE IF EXISTS `feeders_characters`;
CREATE TABLE `feeders_characters` (
  `id` int(11) auto_increment,
  `name` varchar(255),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `feeders_characters`
--


/*!40000 ALTER TABLE `feeders_characters` DISABLE KEYS */;
LOCK TABLES `feeders_characters` WRITE;
INSERT INTO `feeders_characters` VALUES (2,'тестовая характеристика','2020-04-09 05:50:23'),(3,'Без особенностей','2020-04-14 06:57:49'),(4,'Дети с ОВЗ','2020-04-14 06:57:57'),(5,'Дети с сахарным диабетом','2020-04-14 07:02:23'),(6,'Дети с целиакией','2020-04-14 07:02:29'),(7,'Дети с фенилкетанурией','2020-04-14 07:02:39'),(8,'Дети с муковисцидозом','2020-04-14 07:02:49'),(9,'Дети с метаболическим синдромом','2020-04-14 07:02:56');
UNLOCK TABLES;
/*!40000 ALTER TABLE `feeders_characters` ENABLE KEYS */;

--
-- Table structure for table `kids`
--

DROP TABLE IF EXISTS `kids`;
CREATE TABLE `kids` (
  `id` int(11) auto_increment,
  `unique_number` int(11),
  `lastname` varchar(250),
  `name` varchar(250),
  `year_birth` int(4),
  `month_birth_id` int(11),
  `federal_district_id` int(2),
  `region_id` int(2),
  `class_number` int(2),
  `class_letter` varchar(2),
  `organization_id` int(11),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `kids`
--


/*!40000 ALTER TABLE `kids` DISABLE KEYS */;
LOCK TABLES `kids` WRITE;
INSERT INTO `kids` VALUES (20,149060654,'А','ывавы',2020,1,1,2,1,'в',7,'2020-04-29 07:58:16'),(21,515609435,'Ж','Владислав',2001,1,1,3,5,'а',7,'2020-05-13 06:21:21');
UNLOCK TABLES;
/*!40000 ALTER TABLE `kids` ENABLE KEYS */;

--
-- Table structure for table `meals`
--

DROP TABLE IF EXISTS `meals`;
CREATE TABLE `meals` (
  `id` int(11) auto_increment,
  `intake` varchar(250),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `meals`
--


/*!40000 ALTER TABLE `meals` DISABLE KEYS */;
LOCK TABLES `meals` WRITE;
INSERT INTO `meals` VALUES (1,'Только завтрак','2020-05-14 03:12:36'),(2,'Только обед','2020-05-14 03:21:18'),(3,'Только завтрак и обед','2020-05-14 03:23:21'),(4,'Завтрак, обед и полдник','2020-05-14 03:23:36'),(5,'Завтрак, 2-ой завтрак, обед и полдник','2020-05-14 03:23:46'),(6,'Завтрак, 2-ой завтрак, обед, полдник и ужин','2020-05-14 03:23:55');
UNLOCK TABLES;
/*!40000 ALTER TABLE `meals` ENABLE KEYS */;

--
-- Table structure for table `medical_diseases`
--

DROP TABLE IF EXISTS `medical_diseases`;
CREATE TABLE `medical_diseases` (
  `id` int(11) auto_increment,
  `medicals_id` int(11),
  `diseases_id` int(11),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `medical_diseases`
--


/*!40000 ALTER TABLE `medical_diseases` DISABLE KEYS */;
LOCK TABLES `medical_diseases` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `medical_diseases` ENABLE KEYS */;

--
-- Table structure for table `medicals`
--

DROP TABLE IF EXISTS `medicals`;
CREATE TABLE `medicals` (
  `id` int(11) auto_increment,
  `kids_id` int(11),
  `body_length` int(11),
  `body_weight` int(11),
  `capacity_lungs` int(11),
  `left_hand` int(11),
  `right_hand` int(11),
  `bmi` double,
  `physical_evolution` varchar(250),
  `health_group` int(11),
  `physical_group_id` int(11),
  `flat_feet` text,
  `date` int(11),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `medicals`
--


/*!40000 ALTER TABLE `medicals` DISABLE KEYS */;
LOCK TABLES `medicals` WRITE;
INSERT INTO `medicals` VALUES (60,0,189,150,299,15,15,41.99,'Ожирение III степени',1,1,'0',2020,'2020-04-29 02:45:51'),(61,0,189,150,299,15,15,41.99,'Ожирение III степени',1,1,'0',2020,'2020-04-29 02:45:59'),(62,56,189,150,299,15,15,41.99,'Ожирение III степени',1,1,'0',2020,'2020-04-29 02:47:05'),(63,56,189,150,299,15,15,41.99,'Ожирение III степени',1,1,'0',2020,'2020-04-29 02:48:17');
UNLOCK TABLES;
/*!40000 ALTER TABLE `medicals` ENABLE KEYS */;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int(11) auto_increment,
  `organization_id` int(10),
  `feeders_characters_id` int(10),
  `age_info_id` int(10),
  `name` varchar(255),
  `cycle` int(10),
  `date_start` int(20),
  `date_end` int(20),
  `status_archive` int(11) DEFAULT '0',
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `menus`
--


/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
LOCK TABLES `menus` WRITE;
INSERT INTO `menus` VALUES (21,7,2,2,'Меню тестовое измененное1',2,1585688400,1587762000,0,'2020-04-14 05:18:04'),(23,7,5,9,'Меню тестовое измененноеlll',4,1585688400,1588885200,0,'2020-04-14 05:22:56'),(24,2,2,2,'Название меню 12',54,16,16,0,'2020-04-16 02:11:06');
UNLOCK TABLES;
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;

--
-- Table structure for table `menus_days`
--

DROP TABLE IF EXISTS `menus_days`;
CREATE TABLE `menus_days` (
  `id` int(11) auto_increment,
  `menu_id` int(10),
  `days_id` int(11),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `menus_days`
--


/*!40000 ALTER TABLE `menus_days` DISABLE KEYS */;
LOCK TABLES `menus_days` WRITE;
INSERT INTO `menus_days` VALUES (1,20,1,'2020-04-14 05:15:05'),(2,20,2,'2020-04-14 05:15:05'),(3,20,3,'2020-04-14 05:15:05'),(207,24,1,'2020-04-16 02:11:06'),(327,23,1,'2020-05-19 08:46:45'),(328,23,2,'2020-05-19 08:46:45'),(329,23,5,'2020-05-19 08:46:45'),(330,23,7,'2020-05-19 08:46:45'),(379,21,1,'2020-05-26 02:13:53'),(380,21,2,'2020-05-26 02:13:53'),(381,21,3,'2020-05-26 02:13:53'),(382,21,7,'2020-05-26 02:13:53');
UNLOCK TABLES;
/*!40000 ALTER TABLE `menus_days` ENABLE KEYS */;

--
-- Table structure for table `menus_dishes`
--

DROP TABLE IF EXISTS `menus_dishes`;
CREATE TABLE `menus_dishes` (
  `id` int(11) auto_increment,
  `menu_id` int(10),
  `cycle` int(10),
  `days_id` int(10),
  `nutrition_id` int(10),
  `dishes_id` int(10),
  `yield` float,
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `menus_dishes`
--


/*!40000 ALTER TABLE `menus_dishes` DISABLE KEYS */;
LOCK TABLES `menus_dishes` WRITE;
INSERT INTO `menus_dishes` VALUES (1,23,1,1,1,2,20,'2020-04-15 05:14:36'),(19,23,2,1,1,4,150,'2020-04-20 07:21:36'),(27,21,2,1,1,1,500,'2020-04-20 09:08:01'),(43,21,2,1,1,3,250,'2020-04-21 02:28:47'),(45,21,2,1,1,4,25,'2020-04-21 02:38:51'),(53,23,3,1,2,2,125,'2020-04-21 04:56:10'),(54,21,2,1,6,4,250,'2020-04-21 04:56:48'),(58,21,2,1,6,3,156,'2020-04-21 05:40:38'),(59,21,2,1,3,2,240,'2020-04-21 09:23:50'),(60,21,2,1,3,1,250,'2020-04-22 07:41:16'),(66,23,2,1,2,1,250,'2020-04-23 10:41:17'),(67,21,1,1,3,2,250,'2020-04-23 10:42:01'),(68,21,2,1,3,4,18,'2020-04-24 03:17:44'),(83,21,2,1,1,1,250,'2020-04-24 09:27:20'),(84,21,2,1,6,2,200,'2020-04-24 09:27:59'),(85,21,2,1,3,3,300,'2020-04-24 09:28:10'),(86,21,2,1,1,2,25,'2020-04-24 09:29:22'),(87,21,1,2,1,2,25,'2020-04-24 09:29:22'),(88,21,1,2,3,2,25,'2020-04-24 09:29:22'),(89,21,1,3,3,2,25,'2020-04-24 09:29:22'),(90,21,1,7,6,2,380,'2020-04-27 05:05:16'),(91,21,1,7,1,1,270,'2020-04-27 05:20:45'),(92,21,1,7,3,4,700,'2020-04-27 05:21:36'),(94,21,1,1,6,1,90,'2020-04-27 08:34:00'),(95,21,1,1,6,2,100,'2020-04-28 09:16:46'),(96,21,1,2,6,1,25,'2020-04-28 09:19:38'),(97,21,1,3,6,1,100,'2020-04-28 09:19:50'),(98,21,1,3,1,1,122,'2020-04-28 09:30:27'),(106,21,1,1,1,2,100,'2020-04-30 08:05:56'),(107,21,1,1,1,2,100,'2020-04-30 08:21:24'),(108,21,1,1,1,1,123,'2020-04-30 08:21:50'),(110,21,1,1,1,1,140,'2020-04-30 08:22:40'),(113,21,1,1,1,4,140,'2020-04-30 08:30:18'),(116,21,1,1,1,3,140,'2020-04-30 08:32:35'),(118,21,1,1,3,2,290,'2020-04-30 09:05:31'),(120,21,1,1,6,2,22,'2020-05-13 02:52:43'),(124,21,1,1,6,3,700,'2020-05-13 05:26:01'),(125,21,1,1,1,2,22,'2020-05-13 05:36:28'),(126,21,1,1,1,4,100,'2020-05-13 05:45:58'),(127,21,1,1,1,2,22,'2020-05-13 05:55:57'),(128,21,1,1,1,3,22,'2020-05-13 07:09:17'),(130,21,2,7,6,1,200,'2020-05-14 05:29:04'),(131,21,1,1,1,2,180,'2020-05-14 07:40:54');
UNLOCK TABLES;
/*!40000 ALTER TABLE `menus_dishes` ENABLE KEYS */;

--
-- Table structure for table `menus_nutrition`
--

DROP TABLE IF EXISTS `menus_nutrition`;
CREATE TABLE `menus_nutrition` (
  `id` int(11) auto_increment,
  `menu_id` int(10),
  `nutrition_id` int(10),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `menus_nutrition`
--


/*!40000 ALTER TABLE `menus_nutrition` DISABLE KEYS */;
LOCK TABLES `menus_nutrition` WRITE;
INSERT INTO `menus_nutrition` VALUES (82,24,4,'2020-04-16 02:11:06'),(83,24,5,'2020-04-16 02:11:06'),(180,23,1,'2020-05-19 08:46:45'),(181,23,2,'2020-05-19 08:46:45'),(218,21,1,'2020-05-26 02:13:53'),(219,21,3,'2020-05-26 02:13:53'),(220,21,6,'2020-05-26 02:13:53');
UNLOCK TABLES;
/*!40000 ALTER TABLE `menus_nutrition` ENABLE KEYS */;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration` (
  `version` varchar(180) DEFAULT '',
  `apply_time` int(11),
  PRIMARY KEY (`version`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `migration`
--


/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
LOCK TABLES `migration` WRITE;
INSERT INTO `migration` VALUES ('m000000_000000_base',1581786577),('m130524_201442_init',1581786588),('m140506_102106_rbac_init',1581787478),('m170907_052038_rbac_add_index_on_auth_assignment_user_id',1581787479),('m180523_151638_rbac_updates_indexes_without_prefix',1581787480),('m190124_110200_add_verification_token_column_to_user_table',1581786590);
UNLOCK TABLES;
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;

--
-- Table structure for table `months`
--

DROP TABLE IF EXISTS `months`;
CREATE TABLE `months` (
  `id` int(11) auto_increment,
  `name` varchar(250),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `months`
--


/*!40000 ALTER TABLE `months` DISABLE KEYS */;
LOCK TABLES `months` WRITE;
INSERT INTO `months` VALUES (1,'Январь','2020-04-22 05:14:37'),(2,'Февраль','2020-04-22 05:14:38'),(3,'Март','2020-04-22 05:14:49'),(4,'Апрель','2020-04-22 05:14:50'),(5,'Май','2020-04-22 05:14:53'),(6,'Июнь','2020-04-22 05:14:57'),(7,'Июль','2020-04-22 05:15:00'),(8,'Август','2020-04-22 05:15:06'),(9,'Сентябрь','2020-04-22 05:15:09'),(10,'Октябрь','2020-04-22 05:15:13'),(11,'Ноябрь','2020-04-22 05:15:17'),(12,'Декабрь','2020-04-22 05:15:21');
UNLOCK TABLES;
/*!40000 ALTER TABLE `months` ENABLE KEYS */;

--
-- Table structure for table `normativ_info`
--

DROP TABLE IF EXISTS `normativ_info`;
CREATE TABLE `normativ_info` (
  `id` int(11) auto_increment,
  `age_info_id` int(10),
  `nutrition_info_id` int(10),
  `kkal` float,
  `kkal_min_procent` float,
  `kkal_middle_procent` float,
  `kkal_max_procent` float,
  `min_kkal` float,
  `middle_kkal` float,
  `max_kkal` float,
  `protein_min_kkal` float,
  `protein_middle_kkal` float,
  `protein_max_kkal` float,
  `fat_min_kkal` float,
  `fat_middle_kkal` float,
  `fat_max_kkal` float,
  `carbohydrates_min_kkal` float,
  `carbohydrates_middle_kkal` float,
  `carbohydrates_max_kkal` float,
  `protein_min_procent` float,
  `protein_middle_procent` float,
  `protein_max_procent` float,
  `fat_min_procent` float,
  `fat_middle_procent` float,
  `fat_max_procent` float,
  `carbohydrates_min_procent` float,
  `carbohydrates_middle_procent` float,
  `carbohydrates_max_procent` float,
  `itogo_min` float,
  `itogo_middle` float,
  `itogo_max` float,
  `protein_min_weight` float,
  `protein_middle_weight` float,
  `protein_max_weight` float,
  `fat_min_weight` float,
  `fat_middle_weight` float,
  `fat_max_weight` float,
  `carbohydrates_min_weight` float,
  `carbohydrates_middle_weight` float,
  `carbohydrates_max_weight` float,
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `normativ_info`
--


/*!40000 ALTER TABLE `normativ_info` DISABLE KEYS */;
LOCK TABLES `normativ_info` WRITE;
INSERT INTO `normativ_info` VALUES (1,2,1,1400,20,22.5,25,280,315,350,38.6,43.4,48.3,86.9,97.8,108.6,154.5,173.8,193.1,12.3,13.8,15.3,27.6,31,34.5,49,55.2,61.3,88.9,100,111.1,9.7,10.9,12.1,9.7,10.9,12.1,38.6,43.4,48.3,'2020-04-28 06:46:02'),(2,2,2,1400,5,5,5,70,70,70,9.7,9.7,9.7,21.7,21.7,21.7,38.6,38.6,38.6,13.8,13.8,13.8,31,31,31,55.2,55.2,55.2,100,100,100,2.4,2.4,2.4,2.4,2.4,2.4,9.7,9.7,9.7,'2020-04-28 07:00:58'),(3,2,3,1400,30,32.5,35,420,455,490,57.9,62.8,67.6,130.3,141.2,152.1,231.7,251,270.3,12.7,13.8,14.9,28.6,31,33.4,50.9,55.2,59.4,92.3,100,107.7,14.5,15.7,16.9,14.5,15.7,16.9,57.9,62.8,67.6,'2020-04-28 07:10:53'),(4,2,4,1400,10,12.5,15,140,175,210,19.3,24.1,29,43.4,54.3,65.2,77.2,96.6,115.9,11,13.8,16.6,24.8,31,37.2,44.1,55.2,66.2,80,100,120,4.8,6,7.2,4.8,6,7.2,19.3,24.1,29,'2020-04-28 07:17:51'),(5,2,5,1400,20,22.5,25,280,315,350,38.6,43.4,48.3,86.9,97.8,108.6,154.5,173.8,193.1,12.3,13.8,15.3,27.6,31,34.5,49,55.2,61.3,88.9,100,111.1,9.7,10.9,12.1,9.7,10.9,12.1,38.6,43.4,48.3,'2020-04-28 07:27:41'),(6,2,0,1400,90,100,110,1260,1400,1540,174,193,212,391,434,478,695,772,850,12,14,15,28,31,34,50,55,61,90,100,110,43.4,48.3,53.1,43.4,48.3,53.1,173.8,193.1,212.4,'2020-04-28 07:41:03'),(7,2,6,1400,5,5,5,70,70,70,9.7,9.7,9.7,21.7,21.7,21.7,38.6,38.6,38.6,13.8,13.8,13.8,31,31,31,55.2,55.2,55.2,100,100,100,2.4,2.4,2.4,2.4,2.4,2.4,9.7,9.7,9.7,'2020-04-28 09:21:30');
UNLOCK TABLES;
/*!40000 ALTER TABLE `normativ_info` ENABLE KEYS */;

--
-- Table structure for table `nutrition_info`
--

DROP TABLE IF EXISTS `nutrition_info`;
CREATE TABLE `nutrition_info` (
  `id` int(11) auto_increment,
  `name` varchar(255),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `nutrition_info`
--


/*!40000 ALTER TABLE `nutrition_info` DISABLE KEYS */;
LOCK TABLES `nutrition_info` WRITE;
INSERT INTO `nutrition_info` VALUES (1,'Завтрак','2020-04-13 09:48:09'),(2,'Второй завтрак','2020-04-13 09:48:45'),(3,'Обед','2020-04-13 09:49:06'),(4,'Полдник','2020-04-13 09:49:32'),(5,'Ужин','2020-04-13 09:49:55'),(6,'Второй ужин','2020-04-13 09:50:17');
UNLOCK TABLES;
/*!40000 ALTER TABLE `nutrition_info` ENABLE KEYS */;

--
-- Table structure for table `organization`
--

DROP TABLE IF EXISTS `organization`;
CREATE TABLE `organization` (
  `id` int(10) auto_increment,
  `title` varchar(255),
  `short_title` varchar(255),
  `address` varchar(255),
  `federal_district_id` int(10),
  `region_id` int(10),
  `type_org` varchar(255),
  `municipality` varchar(255),
  `phone` varchar(255),
  `email` varchar(255),
  `inn` varchar(255),
  `organizator_food` int(2) DEFAULT '0',
  `medic_service_programm` int(2) DEFAULT '0',
  `status` int(2) DEFAULT '1',
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `organization`
--


/*!40000 ALTER TABLE `organization` DISABLE KEYS */;
LOCK TABLES `organization` WRITE;
INSERT INTO `organization` VALUES (4,'Школа №1212','просто школа','adress123',1,1,'1','Школа','','fdg@mail.ru','123',1,1,1,'2020-04-06 03:42:35'),(5,'dgfdfgdfgdfgdgdgg','','',1,1,'1','dgfdfgdfgdfgdgdgg','','','',0,0,1,'2020-04-06 07:36:52'),(6,'Школа','','ur_address',2,4,'1','Школа','9236565665','fdg@mail.ru','2212',1,0,1,'2020-04-06 08:52:54'),(7,'Муниципальное образование 45','Короткое название','ываыа',1,1,'3','Муниципальное образование','','fdg@mail.ru','1234567890',1,0,1,'2020-04-07 02:17:14'),(8,'Школа','','',1,1,'1','Школа','','','',0,0,1,'2020-04-10 04:16:53');
UNLOCK TABLES;
/*!40000 ALTER TABLE `organization` ENABLE KEYS */;

--
-- Table structure for table `physical_groups`
--

DROP TABLE IF EXISTS `physical_groups`;
CREATE TABLE `physical_groups` (
  `id` int(11) auto_increment,
  `name` varchar(250),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `physical_groups`
--


/*!40000 ALTER TABLE `physical_groups` DISABLE KEYS */;
LOCK TABLES `physical_groups` WRITE;
INSERT INTO `physical_groups` VALUES (1,'Основная','2020-04-22 04:37:19'),(2,'Подготовительная','2020-04-22 04:37:35'),(3,'ЛФК','2020-04-22 04:37:40');
UNLOCK TABLES;
/*!40000 ALTER TABLE `physical_groups` ENABLE KEYS */;

--
-- Table structure for table `price`
--

DROP TABLE IF EXISTS `price`;
CREATE TABLE `price` (
  `id` int(11) auto_increment,
  `price` decimal(10,0),
  `name` varchar(255),
  `description` varchar(255),
  `count` int(11),
  `status` int(11) DEFAULT '1',
  `created_at` datetime DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `price`
--


/*!40000 ALTER TABLE `price` DISABLE KEYS */;
LOCK TABLES `price` WRITE;
INSERT INTO `price` VALUES (6,'900','товар 1','описание товара',2,1,'2020-03-19 12:45:34'),(7,'1000','Дрова деревянныенн','просто деревянные дрова',10,1,'2020-03-19 13:58:45'),(9,'17900','Мобильный телефон ','samsung 2387 белый',1,1,'2020-03-19 18:41:13'),(10,'1199','Доводчик 60','белый NOTEDO 287-314',1,1,'2020-03-19 18:43:47'),(11,'699','Мышь компьютерная','Белая m908-76-90',1,0,'2020-03-19 18:46:24'),(13,'999','Спортивные штаны','Штаны usa черные китай',1,0,'2020-03-20 14:48:01'),(14,'123','rrg','rg',0,1,'2020-04-16 16:24:54');
UNLOCK TABLES;
/*!40000 ALTER TABLE `price` ENABLE KEYS */;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) auto_increment,
  `name` varchar(255),
  `products_category_id` int(10),
  `products_subcategory_id` int(10),
  `sort` int(10),
  `water` float,
  `protein` float,
  `fat` float,
  `carbohydrates_total` float,
  `carbohydrates_saccharide` float,
  `carbohydrates_starch` float,
  `carbohydrates_lactose` float,
  `carbohydrates_sacchorose` float,
  `carbohydrates_cellulose` float,
  `dust_total` float,
  `dust_nacl` float,
  `apple_acid` float,
  `na` float,
  `k` float,
  `ca` float,
  `mg` float,
  `p` float,
  `fe` float,
  `i` float,
  `se` float,
  `f` float,
  `vitamin_a` float,
  `vitamin_b_carotene` float,
  `vitamin_b1` float,
  `vitamin_b2` float,
  `vitamin_pp` float,
  `vitamin_c` float,
  `vitamin_d` float,
  `energy_kkal` float,
  `energy_kdj` float,
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `products`
--


/*!40000 ALTER TABLE `products` DISABLE KEYS */;
LOCK TABLES `products` WRITE;
INSERT INTO `products` VALUES (1,'мука картофельная',1,1,0,6,6,0,77,0,0,0,0,5,3,0,0,55,1001,65,65,168,1,0,0,0,0,0,0,0,3,3,0,357,1493,'2020-05-21 05:24:04'),(2,'мука рисовая',1,1,0,12,6,1,77,0,0,0,0,2,0,0,0,0,0,10,35,98,0,0,15,0,0,0,0,0,2,0,0,366,1531,'2020-05-21 05:24:04'),(3,'хлеб безглютеновый',1,1,0,0,2,2,41,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,211,882,'2020-05-21 05:24:04'),(4,'дрожжи прессованные',2,1,0,75,12,0,8,0,0,0,0,1,1,0,0,19,560,27,64,385,3,0,0,0,0,0,0,0,11,0,0,85,356,'2020-05-21 05:24:04'),(5,'жир кулинарный',3,1,0,0,0,99,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,897,3753,'2020-05-21 05:24:04'),(6,'ванилин',4,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'2020-05-21 05:24:04'),(7,'вода',4,1,0,100,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'2020-05-21 05:24:04'),(8,'кислота лимонная',4,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'2020-05-21 05:24:04'),(9,'натрий двууглекислый',4,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'2020-05-21 05:24:04'),(10,'разрыхлитель',4,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'2020-05-21 05:24:04'),(11,'сироп консервированного компота',4,1,0,0,0,0,14,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,58,242,'2020-05-21 05:24:04'),(12,'какао',5,1,0,2,13,54,15,2,13,0,0,9,2,0,2,4,1340,10,50,430,6,0,0,0,0,0,0,0,1,0,0,606,2536,'2020-05-21 05:24:04'),(13,'какао порошок',5,1,0,5,24,15,10,2,8,0,0,17,6,0,4,13,1509,128,425,655,22,0,0,0,0,0,0,0,1,0,0,289,1209,'2020-05-21 05:24:04'),(14,'картофель',6,1,0,75,2,0,19,1,18,0,0,1,1,0,0,28,568,10,23,58,0,0,0,0,0,0,0,0,0,20,0,83,347,'2020-05-21 05:24:04'),(15,'ацидофилин',7,1,0,81,2,3,10,0,0,3,7,0,0,0,1,50,136,120,14,92,0,0,0,0,20,0,0,0,0,0,0,84,351,'2020-05-21 05:24:04'),(16,'бифидок 2,5%',7,1,0,89,2,2,4,4,0,0,0,0,0,0,0,50,136,121,15,94,0,0,0,0,20,10,0,0,0,0,0,53,221,'2020-05-21 05:24:04'),(17,'варенец',7,1,0,89,2,2,4,0,0,0,0,0,0,0,0,51,144,118,16,96,0,9,2,20,22,0,0,0,0,0,0,53,221,'2020-05-21 05:24:04'),(18,'йогурт 1,5 %-ной жирности',7,1,0,86,4,1,5,0,0,3,0,0,0,0,1,50,152,124,15,95,0,0,0,0,10,0,0,0,0,0,0,57,213,'2020-05-21 05:24:04'),(19,'йогурт 2 %-ной жирности',7,1,0,88,2,2,13,0,0,0,0,0,0,0,0,50,152,124,15,95,0,0,0,0,10,0,0,0,0,0,0,57,213,'2020-05-21 05:24:04'),(20,'йогурт 2,5',7,1,0,0,3,2,5,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,52,217,'2020-05-21 05:24:04'),(21,'йогурт 3,2 %-ной жирности',7,1,0,86,5,3,3,0,0,3,0,0,0,0,1,50,146,120,14,91,0,0,0,0,20,0,0,0,0,0,0,67,280,'2020-05-21 05:24:04'),(22,'кефир 2,5% жирности',7,1,0,89,2,2,4,0,0,4,0,0,0,0,0,50,146,120,14,90,0,0,0,0,20,0,0,0,0,0,0,53,222,'2020-05-21 05:24:04'),(23,'кефир жирный',7,1,0,88,2,3,4,0,0,4,0,0,0,0,0,50,146,120,14,95,0,0,0,0,20,0,0,0,0,0,0,59,247,'2020-05-21 05:24:04'),(24,'кефир нежирный',7,1,0,91,3,0,3,0,0,3,0,0,0,0,0,52,152,126,15,95,0,0,0,0,0,0,0,0,0,0,0,30,126,'2020-05-21 05:24:04'),(25,'простокваша',7,1,0,88,2,3,4,0,0,4,0,0,0,0,0,50,146,121,14,94,0,0,0,0,20,0,0,0,0,0,0,58,243,'2020-05-21 05:24:04'),(26,'простокваша Мечниковская',7,1,0,85,2,6,4,0,0,4,0,0,0,0,0,50,146,124,14,92,0,0,0,0,40,0,0,0,0,0,0,83,347,'2020-05-21 05:24:04'),(27,'ряженка 2,5 %',7,1,0,88,2,2,4,0,0,4,0,0,0,0,0,50,146,124,14,82,0,0,0,0,20,0,0,0,0,0,0,54,226,'2020-05-21 05:24:04'),(28,'снежок 2,5',7,1,0,83,2,2,10,0,0,0,0,0,0,0,0,50,136,121,15,94,0,9,2,20,22,0,0,0,0,0,0,79,330,'2020-05-21 05:24:04'),(29,'сыворотка молочная',7,1,0,93,0,0,5,5,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,24,100,'2020-05-21 05:24:04'),(30,'колбаса докторская',8,1,0,60,13,22,1,0,0,0,0,0,2,2,0,828,243,29,22,178,1,0,0,0,0,0,0,0,0,0,0,260,1088,'2020-05-21 05:24:04'),(31,'колбаса молочная',8,1,0,62,11,22,1,0,0,0,0,0,2,2,0,835,250,40,21,169,1,0,0,0,0,0,0,0,0,0,0,252,1054,'2020-05-21 05:24:04'),(32,'колбаса п/к',8,1,0,39,15,40,0,0,0,0,0,0,4,0,0,1622,302,26,25,202,2,0,0,0,0,0,0,0,2,0,0,423,1770,'2020-05-21 05:24:04'),(33,'сардельки I сорта',8,1,0,68,9,17,1,2,2,0,0,0,2,2,0,904,212,7,17,149,1,0,0,0,0,0,0,0,0,0,0,198,828,'2020-05-21 05:24:04'),(34,'сервелат',8,1,0,39,28,27,0,0,0,0,0,0,4,3,0,1528,367,8,30,243,2,0,0,0,0,0,0,0,0,0,0,360,1506,'2020-05-21 05:24:04'),(35,'сосиски молочные',8,1,0,60,12,25,0,0,0,0,0,0,2,1,0,745,237,29,20,161,1,0,0,0,0,0,0,0,0,0,0,277,1159,'2020-05-21 05:24:04'),(36,'вафли с жиросодержащими начинками',9,1,0,1,3,30,64,44,20,0,0,0,0,0,0,7,43,8,2,33,0,0,0,0,0,0,0,0,0,0,0,530,2218,'2020-05-21 05:24:04'),(37,'вафли с фруктовыми начинками',9,1,0,12,3,2,80,63,16,0,0,0,0,0,0,5,33,10,2,33,0,0,0,0,0,0,0,0,0,0,0,342,1431,'2020-05-21 05:24:04'),(38,'галеты',9,1,0,9,9,10,68,2,66,0,0,0,0,0,1,12,112,18,0,80,1,0,0,0,0,0,0,0,1,0,0,393,1644,'2020-05-21 05:24:04'),(39,'зефир',9,1,0,20,0,0,78,73,4,0,0,0,0,0,0,0,0,9,0,8,0,0,0,0,0,0,0,0,0,0,0,299,1251,'2020-05-21 05:24:04'),(40,'карамель леденцовая',9,1,0,3,0,0,95,83,12,0,0,0,0,0,0,1,2,14,6,6,0,0,0,0,0,0,0,0,0,0,0,362,1515,'2020-05-21 05:24:04'),(41,'карамель с фруктово-ягодными начинками',9,1,0,6,0,0,92,80,11,0,0,0,0,0,0,0,2,15,6,8,0,0,0,0,0,0,0,0,0,0,0,348,1456,'2020-05-21 05:24:04'),(42,'конфеты с начинками между слоями вафель',9,1,0,0,5,32,57,48,9,0,0,1,1,0,0,14,402,44,44,146,1,0,0,0,0,0,0,0,0,0,0,529,2213,'2020-05-21 05:24:04'),(43,'конфеты с шоколадно-кремовыми корпусами',9,1,0,0,4,39,51,47,4,0,0,2,1,0,0,19,539,6,18,144,2,0,0,0,0,0,0,0,0,0,0,566,2368,'2020-05-21 05:24:04'),(44,'мармелад фруктово-ягодный формовой',9,1,0,22,0,0,76,74,1,0,0,0,0,0,0,0,0,11,0,12,0,0,0,0,0,0,0,0,0,0,0,289,1209,'2020-05-21 05:24:04'),(45,'пастила',9,1,0,18,0,0,80,76,3,0,0,0,0,0,0,0,0,11,0,5,0,0,0,0,0,0,0,0,0,0,0,305,1276,'2020-05-21 05:24:04'),(46,'печенье',9,1,0,5,7,11,74,23,50,0,0,0,0,0,0,36,90,20,13,69,1,0,0,0,0,0,0,0,0,0,0,417,1745,'2020-05-21 05:24:04'),(47,'пряники заварные',9,1,0,0,5,4,75,0,0,0,0,0,0,0,0,0,0,11,9,50,0,0,0,0,0,0,0,0,0,0,0,366,1532,'2020-05-21 05:24:04'),(48,'халва подсолнечная ванильная',9,1,0,2,11,29,54,41,12,0,0,0,1,0,0,87,351,211,178,292,33,0,0,0,0,0,0,0,4,0,0,516,2159,'2020-05-21 05:24:04'),(49,'шоколад в порошке',9,1,0,0,5,24,0,58,5,0,0,3,1,0,0,2,518,5,19,165,2,0,0,0,0,0,0,0,0,0,0,483,2021,'2020-05-21 05:24:04'),(50,'шоколад молочный',9,1,0,0,6,35,52,49,2,0,0,2,1,0,0,76,543,187,38,235,1,0,0,0,0,0,0,0,0,0,0,547,2289,'2020-05-21 05:24:04'),(51,'кофейный напиток',10,1,0,5,24,15,10,2,8,0,0,17,6,0,4,13,1509,128,425,655,22,0,0,0,0,0,0,0,1,0,0,289,1209,'2020-05-21 05:24:04'),(52,'крахмал картофельный',11,1,0,20,0,0,79,0,79,0,0,0,0,0,0,6,15,40,0,77,0,0,0,0,0,0,0,0,0,0,0,299,1251,'2020-05-21 05:24:04'),(53,'крахмал кукурузный',11,1,0,13,1,0,85,0,85,0,0,0,0,0,0,30,0,17,1,20,0,0,0,0,0,0,0,0,0,0,0,329,1377,'2020-05-21 05:24:04'),(54,'горох',12,1,0,14,23,1,53,4,46,0,0,5,2,0,0,69,873,115,107,329,9,0,0,0,0,0,0,0,2,0,0,303,1268,'2020-05-21 05:24:04'),(55,'горох лущеный',12,1,0,14,23,1,57,3,47,0,0,1,2,0,0,0,731,89,88,226,7,0,0,0,0,0,0,0,2,0,0,323,1351,'2020-05-21 05:24:04'),(56,'крупа гречневая продел',12,1,0,14,9,1,72,2,64,0,0,1,1,0,0,0,0,48,0,253,4,0,0,0,0,0,0,0,3,0,0,326,1364,'2020-05-21 05:24:04'),(57,'крупа гречневая ядрица',12,1,0,14,12,2,68,2,63,0,0,1,1,0,0,0,167,70,98,298,8,0,0,0,0,0,0,0,4,0,0,308,1377,'2020-05-21 05:24:04'),(58,'крупа кукурузная',12,1,0,14,8,1,75,2,70,0,0,0,0,0,0,55,147,20,36,109,2,0,0,0,0,0,0,0,1,0,0,325,1360,'2020-05-21 05:24:04'),(59,'крупа кус-кус',12,1,0,14,11,2,59,2,55,0,0,10,1,0,0,8,337,54,108,370,5,0,0,2,0,10,0,0,5,0,0,305,1276,'2020-05-21 05:24:04'),(60,'крупа манная',12,1,0,14,11,0,73,1,70,0,0,0,0,0,0,22,120,20,30,84,2,0,0,0,0,0,0,0,1,0,0,326,1364,'2020-05-21 05:24:04'),(61,'крупа овсяная',12,1,0,12,11,5,65,2,54,0,0,2,2,0,0,45,292,64,116,361,3,0,0,0,0,0,0,0,1,0,0,345,1444,'2020-05-21 05:24:04'),(62,'крупа перловая',12,1,0,14,9,1,73,1,65,0,0,1,0,0,0,0,172,38,94,323,3,0,0,0,0,0,0,0,2,0,0,324,1356,'2020-05-21 05:24:04'),(63,'крупа полбы',12,1,0,11,14,2,59,2,50,0,0,0,0,0,0,8,388,27,136,401,4,0,11,0,0,0,0,0,6,0,0,338,1414,'2020-05-21 05:24:04'),(64,'крупа пшеничная Артек',12,1,0,14,12,0,71,1,67,0,0,0,0,0,0,0,0,0,0,276,6,0,0,0,0,0,0,0,1,0,0,326,1364,'2020-05-21 05:24:04'),(65,'крупа пшенная',12,1,0,0,11,3,66,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,342,0,'2020-05-21 05:24:04'),(66,'крупа рисовая',12,1,0,14,7,0,77,1,73,0,0,0,0,0,0,26,54,24,21,97,1,0,0,0,0,0,0,0,1,0,0,323,1351,'2020-05-21 05:24:04'),(67,'крупа ячневая',12,1,0,14,10,1,71,1,65,0,0,1,1,0,0,0,0,0,0,343,1,0,0,0,0,0,0,0,2,0,0,322,1347,'2020-05-21 05:24:04'),(68,'кунжут',12,1,0,0,19,48,12,0,0,0,0,0,0,0,0,0,0,1474,540,720,16,0,0,0,0,0,1,0,0,0,0,0,0,'2020-05-21 05:24:04'),(69,'льняное семя грубого помола',12,1,0,7,18,42,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,534,0,'2020-05-21 05:24:04'),(70,'мак',12,1,0,0,17,47,14,0,0,0,0,0,0,0,0,0,0,1667,442,903,10,0,0,0,0,0,0,0,0,1,0,556,0,'2020-05-21 05:24:04'),(71,'нут',12,1,0,14,20,5,54,6,43,0,0,3,3,0,0,72,1084,193,126,444,2,0,0,0,0,0,0,0,0,0,0,329,1376,'2020-05-21 05:24:04'),(72,'овсяные хлопья Геркулес',12,1,0,12,13,6,65,3,59,0,0,1,1,0,0,0,0,52,142,363,7,0,0,0,0,0,0,0,1,0,0,355,1485,'2020-05-21 05:24:04'),(73,'проростки пшеницы',12,1,0,14,12,0,71,1,67,0,0,0,0,0,0,0,0,0,0,1932,46,0,0,0,0,0,2,0,9,0,0,326,1364,'2020-05-21 05:24:04'),(74,'пшеничная Полтавская',12,1,0,14,12,1,70,2,68,0,0,0,0,0,0,0,0,0,0,261,6,0,0,0,0,0,0,0,1,0,0,325,1360,'2020-05-21 05:24:04'),(75,'пшено',12,1,0,14,12,2,69,1,64,0,0,0,1,0,0,39,201,27,101,233,7,0,0,0,0,0,0,0,1,0,0,334,1397,'2020-05-21 05:24:04'),(76,'соя',12,1,0,12,34,17,26,9,2,0,0,4,5,0,0,44,1607,348,191,510,11,0,0,0,0,0,0,0,2,0,0,395,1653,'2020-05-21 05:24:04'),(77,'толокно',12,1,0,10,12,5,68,1,54,0,0,1,1,0,0,23,351,58,111,328,10,0,0,0,0,0,0,0,0,0,0,357,1494,'2020-05-21 05:24:04'),(78,'фасоль',12,1,0,14,22,1,54,4,43,0,0,3,3,0,0,40,1100,150,103,541,12,0,0,0,0,0,0,0,2,0,0,309,1293,'2020-05-21 05:24:04'),(79,'чечевица',12,1,0,14,24,1,53,2,39,0,0,3,2,0,0,101,672,83,0,294,15,0,0,0,0,0,0,0,1,0,0,310,1297,'2020-05-21 05:24:04'),(80,'макаронные изделия безглютеновые',13,1,0,74,3,0,20,0,0,0,0,0,0,0,0,177,25,7,6,24,0,0,0,0,0,0,0,0,0,0,0,98,410,'2020-05-21 05:24:04'),(81,'макаронные изделия высшего сорта',13,1,0,13,10,0,75,1,68,0,0,0,0,0,0,0,10,124,16,87,1,0,0,0,0,0,0,0,1,0,0,332,1389,'2020-05-21 05:24:04'),(82,'макаронные изделия высшего сорта, яичные',13,1,0,13,11,1,73,2,66,0,0,0,0,0,0,17,135,23,21,105,1,0,0,0,0,0,0,0,1,0,0,338,1414,'2020-05-21 05:24:04'),(83,'маргарин сливочный',14,1,0,15,0,82,1,0,0,0,0,0,0,0,0,187,13,12,1,8,0,0,0,0,400,0,0,0,0,0,0,746,3121,'2020-05-21 05:24:04'),(84,'масло подсолнечное рафинированное',14,1,0,0,0,99,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,899,3761,'2020-05-21 05:24:04'),(85,'масло сливочное 72,5 %',15,1,0,15,0,72,0,0,0,0,0,0,0,0,0,74,23,22,3,19,0,0,0,0,500,0,0,0,0,0,1,748,3130,'2020-05-21 05:24:04'),(86,'масло сливочное несоленое',15,1,0,15,0,82,0,0,0,0,0,0,0,0,0,74,23,22,3,19,0,0,0,0,500,0,0,0,0,0,1,748,3130,'2020-05-21 05:24:04'),(87,'мед',16,1,0,17,0,0,74,5,0,0,0,0,0,0,0,10,36,14,3,18,0,0,0,0,0,0,0,0,0,0,0,328,1372,'2020-05-21 05:24:04'),(88,'биомороженое ванильное',17,1,0,4,3,6,21,0,0,0,0,0,0,0,0,60,168,148,18,117,0,6,1,0,48,0,0,0,0,0,0,159,665,'2020-05-21 05:24:04'),(89,'биомороженое клубника',17,1,0,4,2,9,20,0,0,0,0,0,0,0,0,54,163,120,15,94,0,5,1,0,68,0,0,0,0,3,0,180,753,'2020-05-21 05:24:04'),(90,'биомороженое манго-банан',17,1,0,4,2,9,21,0,0,0,0,0,0,0,0,52,169,119,15,94,0,5,1,0,76,0,0,0,0,5,0,185,774,'2020-05-21 05:24:04'),(91,'молоко коровье сухое обезжиренное',17,1,0,4,33,1,52,0,0,0,0,0,0,0,0,442,1224,1155,160,920,1,55,10,150,10,0,0,1,7,4,0,362,1514,'2020-05-21 05:24:04'),(92,'молоко коровье сухое цельное 25% жирности',17,1,0,4,24,25,39,0,0,4,0,0,0,0,0,400,1200,1000,119,790,0,50,12,110,147,0,0,1,6,4,0,483,2020,'2020-05-21 05:24:04'),(93,'молоко пастеризованное',17,1,0,88,2,3,4,0,0,4,0,0,0,0,0,50,146,121,14,91,0,0,0,0,20,0,0,0,0,1,0,58,243,'2020-05-21 05:24:04'),(94,'молоко сгущенное с сахаром',17,1,0,26,7,8,56,0,0,12,43,0,1,0,0,106,380,307,34,219,0,0,0,0,30,0,0,0,0,1,0,315,1318,'2020-05-21 05:24:04'),(95,'молоко сгущенное стерилизованное',17,1,0,74,7,7,9,0,0,9,0,0,1,0,0,133,308,242,37,204,0,0,0,0,30,0,0,0,0,1,0,135,565,'2020-05-21 05:24:04'),(96,'молоко стерилизованное 2,5 % жирности',17,1,0,89,2,2,4,0,0,4,0,0,0,0,0,50,146,120,14,90,0,0,0,0,20,0,0,0,0,1,0,54,226,'2020-05-21 05:24:04'),(97,'молоко стерилизованное 3,2 % жирности',17,1,0,88,2,3,4,0,0,4,0,0,0,0,0,50,146,124,14,92,0,0,0,0,20,0,0,0,0,0,0,58,243,'2020-05-21 05:24:04'),(98,'пломбир',17,1,0,60,3,15,20,0,0,5,15,0,0,0,0,50,162,159,21,114,0,0,0,0,60,0,0,0,0,0,0,226,946,'2020-05-21 05:24:04'),(99,'сливки 20 %-ной жирности',17,1,0,72,2,20,3,0,0,3,0,0,0,0,0,35,109,86,8,60,0,0,0,0,150,0,0,0,0,0,0,205,858,'2020-05-21 05:24:04'),(100,'сливки 35 %-ной жирности',17,1,0,59,2,35,3,0,0,3,0,0,0,0,0,31,90,86,7,58,0,0,0,0,250,0,0,0,0,0,0,337,1410,'2020-05-21 05:24:04'),(101,'сливки из коровьего молока 10 %-ной жирности',17,1,0,82,3,10,4,0,0,4,0,0,0,0,0,50,124,90,10,62,0,0,0,0,60,0,0,0,0,0,0,118,494,'2020-05-21 05:24:04'),(102,'булгур',18,1,0,9,12,1,75,0,0,0,0,12,1,0,0,17,410,35,164,300,2,2,0,0,0,5,0,0,0,0,0,342,1430,'2020-05-21 05:24:04'),(103,'мука гречневая',18,1,0,0,13,1,71,0,0,0,0,0,0,0,0,0,0,130,48,250,4,0,0,0,0,0,0,0,0,0,0,353,0,'2020-05-21 05:24:04'),(104,'мука кокосовая',18,1,0,0,0,0,2,0,0,0,0,0,0,0,0,0,0,24,25,20,0,0,0,0,0,0,0,0,0,2,0,19,0,'2020-05-21 05:24:04'),(105,'мука кукурузная',18,1,0,14,7,1,75,1,68,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,327,1368,'2020-05-21 05:24:04'),(106,'мука пшеничная высший сорт',18,1,0,14,10,0,74,1,67,0,0,0,0,0,0,10,122,18,16,86,1,0,0,0,0,0,0,0,1,0,0,327,1368,'2020-05-21 05:24:04'),(107,'мука ржаная обдирная',18,1,0,14,8,1,73,5,59,0,0,1,1,0,0,17,149,34,60,189,3,0,0,0,0,0,0,0,1,0,0,325,1360,'2020-05-21 05:24:04'),(108,'мука ржаная обойная',18,1,0,14,10,1,70,5,54,0,0,1,1,0,0,19,294,43,75,256,4,0,0,0,0,0,0,0,1,0,0,321,1343,'2020-05-21 05:24:04'),(109,'мука ячменная',18,1,0,14,10,1,71,3,55,0,0,1,1,0,0,28,147,8,63,175,0,0,0,0,0,0,0,0,2,0,0,322,1347,'2020-05-21 05:24:04'),(110,'баранина I категории',18,1,0,67,16,15,0,0,0,0,0,0,0,0,0,60,270,9,18,178,2,0,0,0,0,0,0,0,2,0,0,203,849,'2020-05-21 05:24:04'),(111,'говядина I категории',18,1,0,67,18,12,0,0,0,0,0,0,1,0,0,60,315,9,21,198,2,0,0,0,0,0,0,0,2,0,0,187,782,'2020-05-21 05:24:04'),(112,'говядина тушеная',18,1,0,63,16,18,0,0,0,0,0,0,1,1,0,444,284,9,19,178,2,0,0,0,0,0,0,0,1,0,0,232,971,'2020-05-21 05:24:04'),(113,'кости пищевые говяжьи',18,1,0,80,15,5,0,0,0,0,0,0,1,0,0,65,325,10,21,200,3,7,0,0,0,0,0,0,2,0,0,105,439,'2020-05-21 05:24:04'),(114,'оленина I категории',18,1,0,71,19,8,0,0,0,0,0,0,1,0,0,77,325,15,22,220,3,0,0,0,0,0,0,0,5,0,0,155,649,'2020-05-21 05:24:04'),(115,'свинина мясная',18,1,0,51,14,33,0,0,0,0,0,0,0,0,0,51,242,7,21,164,1,0,0,0,0,0,0,0,2,0,0,355,1485,'2020-05-21 05:24:04'),(116,'телятина I категории',18,1,0,78,19,1,0,0,0,0,0,0,1,0,0,108,344,11,24,189,1,0,0,0,0,0,0,0,3,0,0,90,377,'2020-05-21 05:24:04'),(117,'напиток тыквенный',38,1,0,89,0,0,10,0,0,0,0,0,0,0,0,1,0,6,3,5,0,0,0,0,300,0,0,0,0,0,0,39,163,'2020-05-21 05:24:04'),(118,'баклажаны',20,1,0,91,0,0,5,4,0,0,0,1,0,0,0,6,238,15,9,34,0,0,0,0,0,0,0,0,0,5,0,24,100,'2020-05-21 05:24:04'),(119,'брокколи',20,1,0,89,2,0,0,1,0,0,0,0,0,0,0,33,316,47,21,0,0,2,0,31,0,0,0,0,0,89,0,34,142,'2020-05-21 05:24:04'),(120,'брюква',20,1,0,87,1,0,8,7,0,0,0,1,1,0,0,10,238,40,7,41,1,0,0,0,0,0,0,0,0,30,0,37,155,'2020-05-21 05:24:04'),(121,'горошек зеленый',20,1,0,80,5,0,13,6,6,0,0,1,0,0,0,0,2,26,38,122,0,0,0,0,0,0,0,0,2,25,0,72,301,'2020-05-21 05:24:04'),(122,'горошек зеленый консервированный',20,1,0,87,3,0,7,3,3,0,0,1,1,0,0,360,135,16,21,53,0,0,0,0,0,0,0,0,0,10,0,41,172,'2020-05-21 05:24:04'),(123,'икра кабачковая',20,1,0,93,1,6,7,7,0,0,7,0,1,0,1,5,426,27,20,32,0,0,0,5,40,0,0,0,1,15,0,90,376,'2020-05-21 05:24:04'),(124,'кабачки',20,1,0,93,0,0,5,4,0,0,0,0,0,0,0,2,238,15,9,12,0,0,0,0,0,0,0,0,0,15,0,27,113,'2020-05-21 05:24:04'),(125,'капуста белокочанная',20,1,0,90,1,0,5,4,0,0,0,0,0,0,0,13,185,48,16,31,1,0,0,0,98,0,0,0,0,50,0,28,117,'2020-05-21 05:24:04'),(126,'капуста брюссельская',20,1,0,86,4,0,6,5,0,0,0,1,1,0,0,7,375,31,40,78,1,0,0,0,50,0,0,0,0,120,0,46,192,'2020-05-21 05:24:04'),(127,'капуста квашеная',20,1,0,90,0,0,1,0,0,0,0,1,3,0,1,187,51,17,34,1,0,0,0,0,0,0,0,0,0,20,0,14,59,'2020-05-21 05:24:04'),(128,'капуста кольраби',20,1,0,86,2,0,8,7,0,0,0,1,1,0,0,10,370,46,30,50,0,0,0,0,17,0,0,0,0,50,0,43,180,'2020-05-21 05:24:04'),(129,'капуста краснокочанная',20,1,0,90,1,0,6,4,0,0,0,1,0,0,0,4,302,53,16,32,0,0,0,0,17,0,0,0,0,60,0,31,130,'2020-05-21 05:24:04'),(130,'капуста цветная',20,1,0,90,2,0,4,4,0,0,0,0,0,0,0,10,210,26,17,51,1,0,0,0,0,0,0,0,0,70,0,29,121,'2020-05-21 05:24:04'),(131,'кинза',20,1,0,92,2,0,3,0,0,0,0,2,1,0,0,46,521,67,26,48,1,0,0,337,0,3930,0,0,0,27,0,23,96,'2020-05-21 05:24:04'),(132,'кукуруза сахарная консервированная',20,1,0,83,2,0,12,0,0,0,0,1,0,0,0,195,136,4,15,46,0,0,0,18,0,0,0,0,0,2,0,61,255,'2020-05-21 05:24:04'),(133,'кукуруза целыми зернами',20,1,0,87,2,0,14,1,9,0,0,0,1,1,0,400,0,5,0,50,0,0,0,0,0,0,0,0,0,4,0,68,285,'2020-05-21 05:24:04'),(134,'лук зеленый',20,1,0,92,1,0,4,3,0,0,0,0,1,0,0,57,259,121,18,26,1,0,0,0,333,2,0,0,0,30,0,22,92,'2020-05-21 05:24:04'),(135,'лук порей',20,1,0,87,3,0,7,6,0,0,0,1,1,0,0,50,225,87,10,58,1,0,0,0,333,0,0,0,0,35,0,40,167,'2020-05-21 05:24:04'),(136,'лук репчатый',20,1,0,86,1,0,9,9,0,0,0,0,1,0,0,18,175,31,14,58,0,0,0,0,0,0,0,0,0,10,0,43,180,'2020-05-21 05:24:04'),(137,'микрозелень (проростки) кольраби',20,1,0,86,2,0,8,7,0,0,0,1,1,0,0,70,2590,322,210,350,4,0,0,0,119,0,0,0,6,350,0,43,180,'2020-05-21 05:24:04'),(138,'микрозелень (проростки) люцерна',20,1,0,92,3,0,2,0,0,0,0,1,0,0,0,6,79,32,27,70,0,0,0,0,155,87,0,0,0,8,0,43,180,'2020-05-21 05:24:04'),(139,'морковь',20,1,0,88,1,0,7,6,0,0,0,1,1,0,0,21,200,51,38,55,1,0,0,0,366,9,0,0,1,5,0,33,138,'2020-05-21 05:24:04'),(140,'огурцы',20,1,0,95,0,0,3,2,0,0,0,0,0,0,0,8,141,23,14,42,0,0,0,0,10,0,0,0,0,10,0,15,63,'2020-05-21 05:24:04'),(141,'огурцы соленые',20,1,0,92,0,0,1,0,0,0,0,0,3,0,0,0,0,25,0,20,1,0,0,0,0,0,0,0,0,0,0,19,79,'2020-05-21 05:24:04'),(142,'патиссоны',20,1,0,93,0,0,4,4,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,23,0,19,79,'2020-05-21 05:24:04'),(143,'перец зеленый',20,1,0,92,1,0,4,4,0,0,0,1,0,0,0,7,139,6,10,25,0,250,0,0,0,1,0,0,0,150,0,23,96,'2020-05-21 05:24:04'),(144,'перец красный',20,1,0,91,1,0,5,5,0,0,0,1,0,0,0,19,163,8,11,16,0,250,0,0,0,2,0,0,1,250,0,27,113,'2020-05-21 05:24:04'),(145,'петрушка (зелень)',20,1,0,85,3,0,8,6,1,0,0,1,1,0,0,79,340,245,85,95,1,0,0,0,950,1,0,0,0,150,0,45,188,'2020-05-21 05:24:04'),(146,'петрушка (корень)',20,1,0,85,1,0,10,9,0,0,0,1,1,0,0,0,262,86,41,82,1,0,0,0,0,0,0,0,1,35,0,47,197,'2020-05-21 05:24:04'),(147,'редис',20,1,0,93,1,0,4,3,0,0,0,0,0,0,0,10,255,39,13,44,1,0,0,0,0,0,0,0,0,25,0,20,84,'2020-05-21 05:24:04'),(148,'репа',20,1,0,90,1,0,5,5,0,0,0,1,0,0,0,58,238,49,17,34,0,0,0,0,17,0,0,0,0,20,0,28,117,'2020-05-21 05:24:04'),(149,'салат',20,1,0,95,1,0,2,1,0,0,0,0,1,0,0,8,220,77,40,34,0,0,0,0,292,1,0,0,0,15,0,14,59,'2020-05-21 05:24:04'),(150,'свекла',20,1,0,86,1,0,8,9,0,0,0,0,1,0,0,86,288,37,43,43,1,0,0,0,0,0,0,0,0,10,0,48,201,'2020-05-21 05:24:04'),(151,'сельдерей (корень)',20,1,0,90,1,0,6,5,0,0,0,1,1,0,0,77,393,63,33,27,0,0,0,0,0,0,0,0,0,8,0,31,130,'2020-05-21 05:24:04'),(152,'томат',20,1,0,93,0,0,4,3,0,0,0,0,0,0,0,40,290,14,20,26,1,0,0,0,133,1,0,0,0,25,0,19,79,'2020-05-21 05:24:04'),(153,'томат-пюре',20,1,0,80,3,0,11,0,0,0,0,0,2,0,1,151,0,20,0,70,2,0,0,0,0,1,0,0,0,26,0,63,264,'2020-05-21 05:24:04'),(154,'тыква',20,1,0,90,1,0,6,4,2,0,0,1,0,0,0,14,170,40,14,25,0,0,0,0,0,1,0,0,0,8,0,29,121,'2020-05-21 05:24:04'),(155,'укроп',20,1,0,86,2,0,4,4,0,0,0,3,2,0,0,43,335,223,70,93,1,0,0,0,0,1,0,0,0,100,0,32,134,'2020-05-21 05:24:04'),(156,'фасоль (стручок)',20,1,0,90,4,0,4,2,2,0,0,1,0,0,0,0,0,65,0,44,1,0,0,0,0,0,0,0,0,20,0,32,134,'2020-05-21 05:24:04'),(157,'фасоль консервированная',20,1,0,92,1,0,0,0,0,0,0,0,0,0,0,560,130,37,13,28,0,0,0,0,0,300,0,0,0,5,0,16,66,'2020-05-21 05:24:05'),(158,'чеснок',20,1,0,70,6,0,21,3,2,0,0,0,1,0,0,120,260,90,30,140,1,0,0,0,0,0,0,0,1,10,0,106,444,'2020-05-21 05:24:05'),(159,'шпинат',20,1,0,91,2,0,2,1,0,0,0,0,1,0,0,24,774,106,82,83,3,0,0,0,0,4500,0,0,0,55,0,23,96,'2020-05-21 05:24:05'),(160,'бройлеры (цыплята)',21,1,0,69,17,12,0,0,0,0,0,0,0,0,0,100,300,10,25,210,1,0,0,0,40,0,0,0,3,0,0,183,766,'2020-05-21 05:24:05'),(161,'кура 1 категории',21,1,0,62,18,18,4,0,0,0,0,0,0,0,0,70,194,16,18,165,1,0,0,0,70,0,0,0,7,1,0,238,996,'2020-05-21 05:24:05'),(162,'куриная грудка (филе)',21,1,0,73,23,1,0,0,0,0,0,0,0,0,0,60,292,8,86,165,1,0,0,0,70,0,0,0,7,1,0,113,473,'2020-05-21 05:24:05'),(163,'горбуша консервированная',22,1,0,70,20,5,0,0,0,0,0,0,2,1,0,0,260,185,56,230,0,0,0,0,0,0,0,0,2,0,10,138,577,'2020-05-21 05:24:05'),(164,'горбуша потрошенная',22,1,0,71,20,6,0,0,0,0,0,0,1,0,0,70,335,20,30,200,0,0,44,0,0,0,0,0,8,0,0,140,586,'2020-05-21 05:24:05'),(165,'икра горбуши зернистая',22,1,0,49,31,11,0,0,0,0,0,0,7,5,0,0,264,75,141,426,2,0,0,0,0,0,0,0,0,0,0,230,962,'2020-05-21 05:24:05'),(166,'кальмар',22,1,0,80,18,0,0,0,0,0,0,0,1,0,0,109,321,43,74,0,0,0,0,0,0,0,0,0,1,0,0,75,314,'2020-05-21 05:24:05'),(167,'камбала дальневосточная',22,1,0,79,15,3,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,15,0,0,0,1,0,2,90,376,'2020-05-21 05:24:05'),(168,'кета потрошенная',22,1,0,71,20,6,0,0,0,0,0,0,1,0,0,70,335,20,30,200,0,0,44,0,0,0,0,0,8,0,0,140,586,'2020-05-21 05:24:05'),(169,'минтай',22,1,0,80,15,0,0,0,0,0,0,0,1,0,0,0,428,0,57,0,0,0,0,0,10,0,0,0,1,0,1,70,293,'2020-05-21 05:24:05'),(170,'навага',22,1,0,82,15,0,0,0,0,0,0,0,1,0,0,0,492,152,32,0,0,0,0,0,15,0,0,0,0,0,0,69,289,'2020-05-21 05:24:05'),(171,'палтус',22,1,0,76,18,3,0,0,0,0,0,0,1,0,0,0,513,0,60,0,0,0,0,0,100,0,0,0,2,0,0,103,431,'2020-05-21 05:24:05'),(172,'печень трески консервированная',22,1,0,26,4,65,1,0,0,0,0,0,2,1,0,0,113,35,51,230,0,0,0,0,4400,0,0,0,2,0,250,613,2565,'2020-05-21 05:24:05'),(173,'сайра бланшированная',22,1,0,56,18,23,0,0,0,0,0,0,2,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2,0,0,283,1184,'2020-05-21 05:24:05'),(174,'сельдь атлантическая',22,1,0,62,17,19,0,0,0,0,0,0,1,0,0,0,129,102,30,278,0,0,0,0,20,0,0,0,3,2,30,242,1013,'2020-05-21 05:24:05'),(175,'сельдь слабосолёная',22,1,0,53,19,17,0,0,0,0,0,0,9,8,0,0,162,66,51,0,0,0,0,0,10,0,0,0,0,0,3,235,983,'2020-05-21 05:24:05'),(176,'сёмга',22,1,0,62,20,15,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,40,0,0,0,0,0,10,219,916,'2020-05-21 05:24:05'),(177,'скумбрия консервированная',22,1,0,59,16,21,0,0,0,0,0,0,2,1,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,10,258,1079,'2020-05-21 05:24:05'),(178,'треска',22,1,0,82,16,0,0,0,0,0,0,0,1,0,0,55,340,25,30,210,0,0,0,0,10,0,0,0,2,1,0,69,289,'2020-05-21 05:24:05'),(179,'хек тихоокеанский потрошенный',22,1,0,79,16,2,0,0,0,0,0,0,1,0,0,200,335,30,35,240,0,0,0,0,0,0,0,0,4,0,0,86,360,'2020-05-21 05:24:05'),(180,'пудра рафинадная',23,1,0,0,0,0,99,0,0,0,0,0,0,0,0,1,3,2,0,0,0,0,0,0,0,0,0,0,0,0,0,399,1669,'2020-05-21 05:24:05'),(181,'сахар-песок',23,1,0,0,0,0,99,99,0,0,0,0,0,0,0,1,3,2,0,0,0,0,0,0,0,0,0,0,0,0,0,374,1565,'2020-05-21 05:24:05'),(182,'сироп брусника на фруктозе',39,1,0,0,0,0,51,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,204,242,'2020-05-21 05:24:05'),(183,'сироп клюква на сорбите',39,1,0,0,0,0,58,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,232,970,'2020-05-21 05:24:05'),(184,'сироп малина на фруктозе',39,1,0,0,0,0,65,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,260,1087,'2020-05-21 05:24:05'),(185,'сироп на стевии',39,1,0,0,0,0,32,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,128,535,'2020-05-21 05:24:05'),(186,'сироп облепиха на фруктозе',39,1,0,0,0,0,63,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,244,1020,'2020-05-21 05:24:05'),(187,'сироп черника на сорбите',39,1,0,0,0,0,51,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,204,853,'2020-05-21 05:24:05'),(188,'сироп черника на фруктозе',39,1,0,0,0,0,50,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,200,836,'2020-05-21 05:24:05'),(189,'сироп шиповник на сорбите',39,1,0,0,0,0,80,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,205,857,'2020-05-21 05:24:05'),(190,'сметана 15 %-ной жирности',24,1,0,78,2,15,3,0,0,3,0,0,0,0,0,40,116,88,9,60,0,0,0,0,107,0,0,0,0,0,0,162,677,'2020-05-21 05:24:05'),(191,'сметана 20 %-ной жирности',24,1,0,72,2,20,3,0,0,3,0,0,0,0,0,35,109,86,8,60,0,0,0,0,150,0,0,0,0,0,0,206,862,'2020-05-21 05:24:05'),(192,'сметана диетическая 10 %-ной жирности',24,1,0,82,3,10,2,0,0,2,0,0,0,0,0,50,124,90,10,62,0,0,0,0,60,0,0,0,0,0,0,116,485,'2020-05-21 05:24:05'),(193,'золотой шар',25,1,0,0,0,0,97,0,0,0,0,0,0,0,0,0,0,574,0,0,0,0,0,0,795,0,1,0,10,55,0,390,1631,'2020-05-21 05:24:05'),(194,'кисель из концентрата',25,1,0,9,0,0,89,64,22,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,338,1414,'2020-05-21 05:24:05'),(195,'компот абрикосовый',25,1,0,76,0,0,21,21,0,0,0,0,0,0,1,18,183,12,8,18,0,0,0,0,0,1,0,0,0,4,0,85,356,'2020-05-21 05:24:05'),(196,'компот вишневый',25,1,0,72,0,0,25,24,0,0,0,0,0,0,1,10,0,10,0,17,0,0,0,0,0,0,0,0,0,2,0,101,423,'2020-05-21 05:24:05'),(197,'компот грушевый',25,1,0,79,0,0,19,18,0,0,0,1,0,0,0,7,86,9,3,10,0,0,0,0,0,0,0,0,0,2,0,74,310,'2020-05-21 05:24:05'),(198,'компот персиковый',25,1,0,76,0,0,21,19,0,0,0,0,0,0,0,0,0,8,0,20,0,0,0,0,0,0,0,0,0,4,0,84,351,'2020-05-21 05:24:05'),(199,'компот черешня',25,1,0,76,0,0,21,19,0,0,0,0,0,0,0,7,0,0,0,0,0,0,0,0,0,0,0,0,0,3,0,84,351,'2020-05-21 05:24:05'),(200,'концентрат киселя Валетек',25,1,0,9,0,0,96,68,23,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,520,0,1,1,15,100,0,380,1589,'2020-05-21 05:24:05'),(201,'концентрат напитка Вита лайт НК',25,1,0,9,0,0,33,23,8,0,0,0,0,0,0,0,27,0,0,0,0,0,0,0,200,0,0,1,5,36,0,133,557,'2020-05-21 05:24:05'),(202,'сок абрикосовый',25,1,0,84,0,0,14,13,0,0,0,0,0,0,0,15,245,3,0,18,0,0,0,0,0,1,0,0,0,4,0,56,234,'2020-05-21 05:24:05'),(203,'сок апельсиновый',25,1,0,84,0,0,13,12,0,0,0,0,0,0,1,0,0,18,0,13,0,0,0,0,0,0,0,0,0,40,0,55,230,'2020-05-21 05:24:05'),(204,'сок виноградный',25,1,0,81,0,0,16,0,0,0,0,0,0,0,1,16,150,20,9,12,0,0,0,0,0,0,0,0,0,2,0,70,293,'2020-05-21 05:24:05'),(205,'сок вишневый',25,1,0,85,0,0,12,12,0,0,0,0,0,0,1,15,212,19,16,20,0,0,0,0,0,0,0,0,0,2,0,72,301,'2020-05-21 05:24:05'),(206,'сок гранатовый',25,1,0,82,0,0,14,14,0,0,0,0,0,0,0,4,102,12,5,8,1,0,0,0,0,20,0,0,0,4,0,56,234,'2020-05-21 05:24:05'),(207,'сок морковный',25,1,0,0,0,0,8,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,43,180,'2020-05-21 05:24:05'),(208,'сок персиковый',25,1,0,82,0,0,16,17,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,25,0,41,172,'2020-05-21 05:24:05'),(209,'сок сливовый',25,1,0,82,0,0,16,16,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6,0,65,272,'2020-05-21 05:24:05'),(210,'сок томатный',25,1,0,94,1,0,3,0,0,0,0,0,0,0,0,0,286,13,26,32,0,0,0,0,0,0,0,0,0,10,0,18,75,'2020-05-21 05:24:05'),(211,'сок черешневый',25,1,0,81,0,0,17,17,0,0,0,0,0,0,0,0,133,40,35,20,0,0,0,0,0,0,0,0,0,85,0,39,163,'2020-05-21 05:24:05'),(212,'сок яблочный',25,1,0,87,0,0,11,10,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,10,0,69,289,'2020-05-21 05:24:05'),(213,'соль поваренная йодированная',26,1,0,3,0,0,0,0,0,0,0,0,97,0,0,37417,15,485,97,0,10,4000,0,0,0,0,0,0,0,0,0,0,0,'2020-05-21 05:24:05'),(214,'соль поваренная пищевая',26,1,0,3,0,0,0,0,0,0,0,0,97,0,0,37417,15,485,97,0,10,0,0,0,0,0,0,0,0,0,0,0,0,'2020-05-21 05:24:05'),(215,'ваниль натуральная',27,1,0,0,1,0,12,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,287,1200,'2020-05-21 05:24:05'),(216,'гвоздика молотая',27,1,0,10,6,13,31,0,0,0,0,33,0,0,0,277,141,632,259,104,11,0,7,0,8,0,0,0,1,0,0,274,1146,'2020-05-21 05:24:05'),(217,'корица',27,1,0,11,4,1,27,0,0,0,0,53,0,0,0,10,31,1002,60,64,8,0,3,0,15,0,0,0,1,3,0,247,1033,'2020-05-21 05:24:05'),(218,'лавровый лист',27,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'2020-05-21 05:24:05'),(219,'перец черный горошком',27,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'2020-05-21 05:24:05'),(220,'тмин',27,1,0,10,19,14,11,0,0,0,0,38,0,0,0,17,0,689,258,568,16,0,12,0,18,0,0,0,3,21,0,333,1393,'2020-05-21 05:24:05'),(221,'печень говяжья',28,1,0,72,17,3,0,0,0,0,0,0,1,0,0,63,240,5,18,339,9,0,0,0,3830,1,0,2,6,33,0,98,410,'2020-05-21 05:24:05'),(222,'кешью',29,1,0,5,18,48,22,0,0,0,0,2,0,0,0,16,553,47,270,206,3,11,11,21,0,0,0,0,6,0,0,600,2510,'2020-05-21 05:24:05'),(223,'миндаль',29,1,0,4,18,53,13,0,0,0,0,7,0,0,0,10,748,273,234,473,4,2,2,91,3,0,0,0,6,1,0,609,2548,'2020-05-21 05:24:05'),(224,'смесь сухофруктов',29,1,0,0,2,0,59,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,270,1130,'2020-05-21 05:24:05'),(225,'сушеная вишня',29,1,0,18,0,0,73,46,0,0,0,0,4,0,5,109,1280,185,130,150,7,0,0,0,0,0,0,0,1,20,0,286,1197,'2020-05-21 05:24:05'),(226,'сушеная груша',29,1,0,24,2,0,62,46,0,0,0,6,4,0,1,85,872,107,66,92,13,0,0,0,0,0,0,0,0,8,0,246,1029,'2020-05-21 05:24:05'),(227,'сушеная курага',29,1,0,20,5,0,65,55,0,0,0,3,4,0,1,171,1717,160,105,146,12,0,0,0,0,3,0,0,3,4,0,272,1138,'2020-05-21 05:24:05'),(228,'сушеные яблоки',29,1,0,20,3,0,68,64,0,0,0,5,1,0,2,156,580,111,60,77,15,0,0,0,0,0,0,0,0,2,0,273,1142,'2020-05-21 05:24:05'),(229,'сушеный изюм',29,1,0,19,1,0,70,66,0,0,0,3,4,0,1,117,860,80,42,129,3,0,0,0,0,0,0,0,0,0,0,276,1155,'2020-05-21 05:24:05'),(230,'сушеный урюк',29,1,0,18,5,0,67,53,0,0,0,3,4,0,2,171,1781,166,109,152,12,0,0,0,0,3,0,0,3,4,0,278,1163,'2020-05-21 05:24:05'),(231,'сушеный чернослив',29,1,0,25,2,0,65,57,0,0,0,1,2,0,3,104,864,80,102,83,13,0,0,0,0,0,0,0,1,3,0,264,1105,'2020-05-21 05:24:05'),(232,'фундук',29,1,0,5,15,61,9,0,0,0,0,5,0,0,0,3,717,170,172,299,3,0,2,17,2,0,0,0,5,1,0,651,2723,'2020-05-21 05:24:05'),(233,'цукаты',29,1,0,17,0,0,81,0,0,0,0,1,0,0,0,98,0,18,4,5,0,0,0,0,1,0,0,0,0,0,0,322,1347,'2020-05-21 05:24:05'),(234,'чернослив',29,1,0,25,2,0,57,0,0,0,0,0,2,0,0,10,864,80,102,83,0,0,0,0,0,60,0,0,1,3,0,256,1071,'2020-05-21 05:24:05'),(235,'шиповник (сухой)',29,1,0,14,4,0,60,50,0,0,0,10,5,0,5,13,58,66,20,20,28,0,0,0,817,6,0,0,1,350,0,253,1059,'2020-05-21 05:24:05'),(236,'сыр голландский',30,1,0,39,26,27,0,0,0,0,0,0,4,0,2,1000,130,1040,0,544,0,0,0,0,210,0,0,0,0,2,0,361,1510,'2020-05-21 05:24:05'),(237,'сыр пошехонский',30,1,0,41,26,26,0,0,0,0,0,0,4,0,2,800,0,1050,0,480,0,0,0,0,230,0,0,0,0,2,0,350,1464,'2020-05-21 05:24:05'),(238,'сыр российский',30,1,0,40,23,30,0,0,0,0,0,0,4,0,2,1000,116,1000,47,544,0,0,0,0,260,0,0,0,0,1,0,371,1552,'2020-05-21 05:24:05'),(239,'сыр советский',30,1,0,35,25,32,0,0,0,0,0,0,4,0,2,1000,0,1050,580,0,0,0,0,0,270,0,0,0,0,1,0,400,1674,'2020-05-21 05:24:05'),(240,'аммоний углекислый',31,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'2020-05-21 05:24:05'),(241,'бульон',31,1,0,0,2,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,15,62,'2020-05-21 05:24:05'),(242,'метилцеллюлоза',31,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'2020-05-21 05:24:05'),(243,'сироп инвертный',31,1,0,30,0,0,69,0,0,0,0,0,0,0,0,0,2,3,0,0,0,0,0,30,0,0,0,0,0,0,0,278,1163,'2020-05-21 05:24:05'),(244,'сода',31,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'2020-05-21 05:24:05'),(245,'уксусная эссенция',31,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'2020-05-21 05:24:05'),(246,'сырки и масса творожные особые',32,1,0,41,7,23,27,0,0,1,26,0,0,0,0,41,112,135,23,200,0,0,0,0,10,0,0,0,0,0,0,340,1422,'2020-05-21 05:24:05'),(247,'творог нежирный',32,1,0,77,18,0,3,0,0,1,0,0,1,0,1,44,115,176,24,224,0,0,0,0,0,0,0,0,0,0,0,86,360,'2020-05-21 05:24:05'),(248,'творог полужирный',32,1,0,71,16,9,3,0,0,1,0,0,1,0,1,41,112,164,23,220,0,0,0,0,50,0,0,0,0,0,0,169,652,'2020-05-21 05:24:05'),(249,'абрикосы',33,1,0,86,0,0,10,10,0,0,0,0,0,0,1,30,305,28,19,26,2,0,0,0,267,1,0,0,0,10,0,46,192,'2020-05-21 05:24:05'),(250,'авокадо',33,1,0,73,2,14,8,0,0,0,0,6,1,0,0,7,485,12,29,52,0,0,7,7,0,62,0,0,0,10,0,160,669,'2020-05-21 05:24:05'),(251,'апельсин',33,1,0,87,0,0,8,7,0,0,0,1,0,0,1,13,197,34,13,23,0,0,0,0,0,0,0,0,0,60,0,38,159,'2020-05-21 05:24:05'),(252,'бананы',33,1,0,74,1,0,22,19,2,0,0,0,0,0,0,31,348,8,42,28,0,0,0,0,20,0,0,0,0,10,0,91,381,'2020-05-21 05:24:05'),(253,'брусника',33,1,0,86,0,0,8,0,0,0,0,0,0,0,0,7,90,25,7,16,0,0,0,0,1,50,0,0,0,15,0,46,192,'2020-05-21 05:24:05'),(254,'варенье',33,1,0,23,0,0,74,0,0,0,0,0,0,0,0,13,135,10,7,10,0,0,0,0,0,20,0,0,0,8,0,285,1192,'2020-05-21 05:24:05'),(255,'виноград',33,1,0,80,0,0,17,16,0,0,0,0,0,0,0,26,255,45,17,22,0,0,0,0,0,0,0,0,0,6,0,69,289,'2020-05-21 05:24:05'),(256,'вишня',33,1,0,85,0,0,11,10,0,0,0,0,0,0,1,20,256,37,26,30,1,0,0,0,2,0,0,0,0,15,0,49,205,'2020-05-21 05:24:05'),(257,'груша',33,1,0,87,0,0,10,9,0,0,0,0,0,0,0,14,155,19,12,16,2,0,0,0,0,0,0,0,0,5,0,42,176,'2020-05-21 05:24:05'),(258,'джем из абрикосов',33,1,0,25,0,0,71,68,0,0,0,0,0,0,0,15,152,12,0,18,1,0,0,0,0,0,0,0,0,1,0,273,1142,'2020-05-21 05:24:05'),(259,'джем из мандаринов',33,1,0,26,0,0,72,67,0,0,0,0,0,0,0,6,78,0,0,0,0,0,0,0,0,0,0,0,0,10,0,273,1142,'2020-05-21 05:24:05'),(260,'джем из черной смородины',33,1,0,23,0,0,73,68,0,0,0,1,0,0,1,8,93,0,0,0,0,0,0,0,0,0,0,0,0,40,0,281,1176,'2020-05-21 05:24:05'),(261,'инжир',33,1,0,83,0,0,13,11,0,0,0,2,1,0,0,18,190,0,0,0,3,0,0,0,13,0,0,0,0,2,0,56,234,'2020-05-21 05:24:05'),(262,'клубника',33,1,0,89,0,0,4,3,0,0,0,2,0,0,3,12,119,14,8,11,0,0,0,0,0,0,0,0,0,15,0,28,117,'2020-05-21 05:24:05'),(263,'клюква',33,1,0,89,0,0,4,3,0,0,0,2,0,0,3,12,119,14,8,11,0,0,0,0,0,0,0,0,0,15,0,28,117,'2020-05-21 05:24:05'),(264,'лимон',33,1,0,87,0,0,3,3,0,0,0,1,0,0,5,11,163,40,12,22,0,0,0,0,0,0,0,0,0,40,0,31,130,'2020-05-21 05:24:05'),(265,'малина',33,1,0,89,0,0,4,3,0,0,0,2,0,0,3,12,119,14,8,11,0,0,0,0,0,0,0,0,0,15,0,28,117,'2020-05-21 05:24:05'),(266,'манго',33,1,0,83,0,0,13,13,0,0,0,0,0,0,1,1,168,11,10,14,0,0,0,0,54,0,0,0,0,36,0,60,251,'2020-05-21 05:24:05'),(267,'мандарин',33,1,0,88,0,0,8,8,0,0,0,0,0,0,1,12,155,35,11,17,0,0,0,0,0,0,0,0,0,38,0,38,159,'2020-05-21 05:24:05'),(268,'облепиха',33,1,0,89,0,0,4,3,0,0,0,2,0,0,3,12,119,14,8,11,0,0,0,0,0,0,0,0,0,15,0,28,117,'2020-05-21 05:24:05'),(269,'персики',33,1,0,86,0,0,10,9,0,0,0,0,0,0,0,0,363,20,16,34,4,0,0,0,83,0,0,0,0,10,0,44,184,'2020-05-21 05:24:05'),(270,'повидло абрикосовое',33,1,0,34,0,0,63,62,0,0,0,0,0,0,0,18,183,22,14,19,1,0,0,0,0,0,0,0,0,0,0,242,1013,'2020-05-21 05:24:05'),(271,'повидло яблочное',33,1,0,32,0,0,65,65,0,0,0,0,0,0,0,16,149,14,7,9,1,0,0,0,0,0,0,0,0,0,0,247,1033,'2020-05-21 05:24:05'),(272,'слива',33,1,0,87,0,0,9,9,0,0,0,0,0,0,1,18,214,28,17,27,2,0,0,0,17,0,0,0,0,10,0,43,180,'2020-05-21 05:24:05'),(273,'смородина черная',33,1,0,85,1,0,8,7,0,0,0,3,0,0,2,32,372,36,35,33,1,0,0,0,2,0,0,0,0,200,0,40,167,'2020-05-21 05:24:05'),(274,'финики',33,1,0,20,2,0,72,68,0,0,0,3,1,0,0,32,370,65,69,56,1,0,0,0,0,0,0,0,0,0,0,281,1176,'2020-05-21 05:24:05'),(275,'фруктовое пюре',33,1,0,0,0,0,15,0,0,0,0,0,0,0,0,0,140,0,0,0,0,0,0,0,0,0,0,0,0,0,0,60,260,'2020-05-21 05:24:05'),(276,'хурма',33,1,0,81,0,0,15,15,0,0,0,0,0,0,0,15,200,127,56,42,2,0,0,0,200,1,0,0,0,15,0,67,259,'2020-05-21 05:24:05'),(277,'цедра лимона',33,1,0,81,1,0,16,0,0,0,4,10,0,0,0,6,160,134,15,0,0,0,0,12,3,7,0,0,0,129,0,47,196,'2020-05-21 05:24:05'),(278,'черешня',33,1,0,85,1,0,12,11,0,0,0,0,0,0,0,13,233,33,24,28,1,0,0,0,25,0,0,0,0,15,0,52,218,'2020-05-21 05:24:05'),(279,'черника',33,1,0,86,0,0,8,0,0,0,0,0,0,0,0,7,90,25,7,16,0,0,0,0,1,50,0,0,0,15,0,46,192,'2020-05-21 05:24:05'),(280,'яблоки',33,1,0,86,0,0,9,9,0,0,0,0,0,0,0,26,248,16,9,11,2,0,0,0,0,0,0,0,0,13,0,46,192,'2020-05-21 05:24:05'),(281,'баранки простые',34,1,0,17,10,1,68,0,0,0,0,0,2,0,0,560,175,33,47,114,2,0,0,0,0,0,0,0,2,0,0,312,1305,'2020-05-21 05:24:05'),(282,'батон йодированный',34,1,0,36,7,2,51,3,0,0,0,0,1,0,0,402,125,25,33,82,1,0,0,0,4,0,0,0,1,0,0,250,1046,'2020-05-21 05:24:05'),(283,'батоны нарезные',34,1,0,36,7,2,51,3,0,0,0,0,1,0,0,402,125,25,33,82,1,0,0,0,0,0,0,0,1,0,0,250,1046,'2020-05-21 05:24:05'),(284,'батоны простые',34,1,0,37,7,1,51,0,0,0,0,0,1,0,0,368,133,25,35,86,1,0,0,0,0,0,0,0,1,0,0,236,987,'2020-05-21 05:24:05'),(285,'булки городские',34,1,0,34,7,2,53,3,0,0,0,0,1,0,0,417,130,26,34,85,1,0,0,0,0,0,0,0,1,0,0,254,1063,'2020-05-21 05:24:05'),(286,'сухари армейские (пшеничные)',34,1,0,11,13,2,67,0,0,0,0,2,2,0,1,531,299,53,102,355,4,0,0,0,0,0,0,0,4,0,0,329,1376,'2020-05-21 05:24:05'),(287,'сухари панировочные',34,1,0,7,13,5,0,6,59,0,0,0,0,0,0,732,0,183,43,0,4,25,0,0,0,0,0,0,6,0,0,395,1428,'2020-05-21 05:24:05'),(288,'сушки простые',34,1,0,12,11,1,73,0,0,0,0,0,2,0,0,615,185,36,50,121,2,0,0,0,0,0,0,0,2,0,0,330,1381,'2020-05-21 05:24:05'),(289,'хлеб пшеничный',34,1,0,44,8,1,42,0,0,0,0,1,2,0,0,575,185,37,65,218,2,0,0,0,0,0,0,0,2,0,0,233,975,'2020-05-21 05:24:05'),(290,'сухари армейские (ржано-пшеничные)',35,1,0,11,11,1,69,0,0,0,0,1,3,0,1,611,358,59,83,271,4,0,0,0,0,0,0,0,1,0,0,326,1364,'2020-05-21 05:24:05'),(291,'хлеб бородинский',35,1,0,42,6,1,39,0,0,0,0,0,0,0,0,246,235,47,49,157,3,4,0,24,1,0,0,0,2,0,0,201,841,'2020-05-21 05:24:05'),(292,'хлеб ржаной простой формовой',35,1,0,47,6,1,40,0,0,0,0,1,2,0,1,583,206,38,49,156,2,0,0,0,0,0,0,0,0,0,0,190,795,'2020-05-21 05:24:05'),(293,'хлеб ржано-пшеничный простой формовой',35,1,0,46,7,1,40,0,0,0,0,1,2,0,1,589,195,37,55,178,2,0,0,0,0,0,0,0,1,0,0,193,808,'2020-05-21 05:24:05'),(294,'хлеб украинский',35,1,0,46,7,1,40,0,0,0,0,1,2,0,1,589,195,37,55,178,2,0,0,0,0,0,0,0,1,0,0,193,808,'2020-05-21 05:24:05'),(295,'хлебцы ржаные',35,1,0,14,11,3,64,1,57,0,0,1,3,0,1,3,396,43,75,269,4,0,18,50,2,0,0,0,3,0,0,320,1339,'2020-05-21 05:24:05'),(296,'чай зеленый листовой',36,1,0,8,20,0,6,4,0,0,0,4,5,0,1,82,2480,495,440,825,82,0,0,0,0,0,0,1,8,10,0,109,456,'2020-05-21 05:24:05'),(297,'чай каркаде',36,1,0,0,1,0,11,0,0,0,0,0,0,0,0,6,208,215,51,0,1,0,0,37,14,0,0,0,0,12,0,49,205,'2020-05-21 05:24:05'),(298,'чай черный байховый',36,1,0,8,20,0,6,4,0,0,0,4,5,0,1,82,2480,495,440,825,82,0,0,0,0,0,0,1,8,10,0,109,456,'2020-05-21 05:24:05'),(299,'меланж',37,1,0,74,12,11,0,0,0,0,0,0,0,0,0,134,0,55,12,192,2,20,30,0,260,0,0,0,3,0,0,157,656,'2020-05-21 05:24:05'),(300,'яйца куриные',37,1,0,74,12,11,0,0,0,0,0,0,1,0,0,71,153,55,54,185,2,0,0,0,350,0,0,0,0,0,2,157,657,'2020-05-21 05:24:05');
UNLOCK TABLES;
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

--
-- Table structure for table `products_category`
--

DROP TABLE IF EXISTS `products_category`;
CREATE TABLE `products_category` (
  `id` int(11) auto_increment,
  `name` varchar(255),
  `sort` int(10) DEFAULT '0',
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `products_category`
--


/*!40000 ALTER TABLE `products_category` DISABLE KEYS */;
LOCK TABLES `products_category` WRITE;
INSERT INTO `products_category` VALUES (1,'безглютеновые продукты',0,'2020-04-09 08:10:28'),(2,'дрожжи хлебопекарные',0,'2020-04-10 05:59:47'),(3,'жировые продукты',0,'2020-04-13 05:00:06'),(4,'иные продукты',30,'2020-04-13 05:00:13'),(5,'какао-порошок',0,'2020-04-13 05:00:22'),(6,'картофель',0,'2020-04-13 05:00:30'),(7,'кисломолочные продукты',0,'2020-04-13 05:00:37'),(8,'колбасные изделия',0,'2020-04-13 05:00:43'),(9,'кондитерские изделия',0,'2020-04-13 05:00:52'),(10,'кофейный напиток',0,'2020-04-13 05:00:59'),(11,'крахмал',0,'2020-04-13 05:01:05'),(12,'крупы, бобовые',0,'2020-04-13 05:01:11'),(13,'макаронные изделия',0,'2020-04-13 05:01:18'),(14,'масло растительное',0,'2020-04-13 05:01:24'),(15,'масло сливочное',0,'2020-04-13 05:01:35'),(16,'мед',20,'2020-04-13 05:01:46'),(17,'молоко и молочные продукты',0,'2020-04-13 05:02:31'),(18,'мука пшеничная',0,'2020-04-13 05:02:36'),(19,'мясо жилованное',0,'2020-04-13 05:02:45'),(20,'овощи',0,'2020-04-13 05:03:18'),(21,'птица (куры, цыплята-бройлеры, индейка – потрошеная 1 кат)',0,'2020-04-13 05:03:25'),(22,'рыба (филе)',0,'2020-04-13 05:03:32'),(23,'сахар',0,'2020-04-13 05:03:41'),(24,'сметана',0,'2020-04-13 05:03:46'),(25,'соки плодоовощные, напитки витаминизированные, в т.ч. инстантные',0,'2020-04-13 05:03:58'),(26,'соль пищевая поваренная',0,'2020-04-13 05:04:08'),(27,'специи',0,'2020-04-13 05:04:41'),(28,'субпродукты (печень, язык, сердце)',0,'2020-04-13 05:04:48'),(29,'сухофрукты',0,'2020-04-13 05:04:54'),(30,'сыр',0,'2020-04-13 05:05:02'),(31,'сырье',0,'2020-04-13 05:05:08'),(32,'творог',0,'2020-04-13 05:05:15'),(33,'фрукты свежие',0,'2020-04-13 05:05:22'),(34,'хлеб пшеничный',0,'2020-04-13 05:05:28'),(35,'хлеб ржаной',0,'2020-04-13 05:05:34'),(36,'чай',0,'2020-04-13 05:05:41'),(37,'яйцо',0,'2020-04-13 05:05:48'),(38,'не известно',0,'2020-04-13 05:06:01'),(39,'сахарозаменители',0,'2020-05-21 04:34:02');
UNLOCK TABLES;
/*!40000 ALTER TABLE `products_category` ENABLE KEYS */;

--
-- Table structure for table `products_subcategory`
--

DROP TABLE IF EXISTS `products_subcategory`;
CREATE TABLE `products_subcategory` (
  `id` int(11) auto_increment,
  `product_category_id` int(10),
  `name` varchar(255),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `products_subcategory`
--


/*!40000 ALTER TABLE `products_subcategory` DISABLE KEYS */;
LOCK TABLES `products_subcategory` WRITE;
INSERT INTO `products_subcategory` VALUES (2,1,'безглютеновые продукты','2020-04-10 06:06:29'),(3,2,'дрожжи хлебопекарнтые','2020-04-10 07:04:54'),(4,3,'жир','2020-04-13 05:10:34'),(5,4,'вода','2020-04-13 05:10:45'),(6,4,'сырье','2020-04-13 05:12:11'),(7,4,'иные продукты','2020-04-13 05:12:21'),(8,5,'какао','2020-04-13 05:12:47'),(9,5,'шоколад и какао порошок','2020-04-13 05:12:57'),(10,6,'картофель','2020-04-13 05:13:06'),(11,7,'кисломолочные продукты','2020-04-13 05:13:15'),(12,8,'вареные колбасы','2020-04-13 05:13:28'),(13,8,'сардельки','2020-04-13 05:13:50'),(14,8,'сосиски','2020-04-13 05:13:59'),(15,8,'варено-копченые колбасы','2020-04-13 05:14:12'),(16,8,'колбасные изделия','2020-04-13 05:14:21'),(17,9,'карамель','2020-04-13 05:14:35'),(18,9,'шоколад и какао порошок','2020-04-13 05:14:43'),(19,9,'конфеты','2020-04-13 05:14:58'),(20,9,'мармелад','2020-04-13 05:15:09'),(21,9,'пастила и зефир','2020-04-13 05:15:24'),(22,9,'халва','2020-04-13 05:15:32'),(23,9,'мучные кондитерские изделия','2020-04-13 05:15:44'),(24,10,'сырье','2020-04-13 05:15:55'),(25,11,'крахмал','2020-04-13 05:16:02'),(26,12,'зернобобовые','2020-04-13 05:16:11'),(27,12,'крупа','2020-04-13 05:16:24'),(28,13,'макаронные изделия','2020-04-13 05:20:30'),(29,14,'масло растительное','2020-04-13 05:20:40'),(30,14,'маргарин','2020-04-13 05:20:54'),(31,15,'масло сливочное','2020-04-13 05:21:05'),(32,16,'мед','2020-04-13 05:21:16'),(33,17,'молоко','2020-04-13 05:21:27'),(34,17,'сливки','2020-04-13 05:21:39'),(35,17,'мороженое','2020-04-13 05:21:48'),(36,17,'консервы молочные','2020-04-13 05:21:59'),(37,18,'мука','2020-04-13 05:22:18'),(38,19,'мясо','2020-04-13 05:23:24'),(39,19,'консервы мясные','2020-04-13 05:23:37'),(40,20,'овощи','2020-04-13 05:23:53'),(41,20,'бахчевые','2020-04-13 05:24:42'),(42,20,'консервы овощные натуральные','2020-04-13 05:24:50'),(43,20,'овощи квашеные и соленые','2020-04-13 05:25:04'),(44,21,'цыплята I категории потрошенные','2020-04-13 05:25:35'),(45,21,'курица','2020-04-13 05:25:44'),(46,22,'рыба свежая, охлажденная и мороженая','2020-04-13 05:25:57'),(47,22,'продукты из нерыбных объектов промысла','2020-04-13 05:26:12'),(48,22,'рыба солёная','2020-04-13 05:26:25'),(49,22,'икра','2020-04-13 05:26:34'),(50,22,'рыбные консервы','2020-04-13 05:26:46'),(51,23,'сахар','2020-04-13 05:27:01'),(52,24,'сметана','2020-04-13 05:27:12'),(53,25,'соки','2020-04-13 05:27:28'),(54,25,'фруктовые компоты','2020-04-13 05:27:41'),(55,25,'сырье','2020-04-13 05:27:54'),(56,26,'соль','2020-04-13 05:29:16'),(57,27,'специи','2020-04-13 05:29:34'),(58,28,'печень','2020-04-13 05:30:24'),(59,29,'орехи','2020-04-13 05:30:35'),(60,29,'шиповник','2020-04-13 05:30:52'),(61,29,'фрукты сушеные','2020-04-13 05:32:05'),(62,29,'сухофрукты','2020-04-13 05:39:04'),(63,30,'сыр','2020-04-13 05:39:18'),(64,31,'сырье','2020-04-13 05:39:30'),(65,32,'творог','2020-04-13 05:39:39'),(66,33,'фрукты свежие','2020-04-13 05:39:51'),(67,33,'ягоды','2020-04-13 05:40:00'),(68,33,'фрукты','2020-04-13 05:40:12'),(69,33,'ягоды','2020-04-13 05:40:51'),(70,33,'джем, повидло','2020-04-13 05:41:00'),(71,34,'хлебные изделия из пшеничной муки','2020-04-13 05:41:26'),(72,34,'булочные изделия','2020-04-13 05:41:36'),(73,34,'бараночные изделия','2020-04-13 05:41:46'),(74,34,'сухарные изделия','2020-04-13 05:41:56'),(75,35,'сухарные изделия','2020-04-13 05:42:07'),(76,35,'хлеб из смеси ржаной и пшеничной муки','2020-04-13 05:42:15'),(77,35,'хлеб из ржаной муки','2020-04-13 05:42:52'),(78,36,'чай','2020-04-13 05:43:06'),(79,37,'яйцо','2020-04-13 05:43:15'),(80,38,'не известно','2020-04-13 05:43:31');
UNLOCK TABLES;
/*!40000 ALTER TABLE `products_subcategory` ENABLE KEYS */;

--
-- Table structure for table `recipes_collection`
--

DROP TABLE IF EXISTS `recipes_collection`;
CREATE TABLE `recipes_collection` (
  `id` int(11) auto_increment,
  `name` varchar(255),
  `year` int(10),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `recipes_collection`
--


/*!40000 ALTER TABLE `recipes_collection` DISABLE KEYS */;
LOCK TABLES `recipes_collection` WRITE;
INSERT INTO `recipes_collection` VALUES (1,'Сборник рецептур на продукцию для обучающихся во всех образовательных учреждениях / Новосибирск 2020 г.',2020,'2020-04-13 05:31:41'),(2,'Сборник рецептур тестовый 2',2019,'2020-04-22 05:02:06'),(3,'Сборник рецептур тестовый 3',2020,'2020-04-22 05:02:06');
UNLOCK TABLES;
/*!40000 ALTER TABLE `recipes_collection` ENABLE KEYS */;

--
-- Table structure for table `region`
--

DROP TABLE IF EXISTS `region`;
CREATE TABLE `region` (
  `id` int(10) auto_increment,
  `district_id` int(10),
  `name` varchar(255),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `region`
--


/*!40000 ALTER TABLE `region` DISABLE KEYS */;
LOCK TABLES `region` WRITE;
INSERT INTO `region` VALUES (1,1,'Амурская область','2020-04-08 07:44:57'),(2,1,'Еврейская автономная область','2020-04-08 07:44:57'),(3,1,'Забайкальский край','2020-04-08 07:44:57'),(4,1,'Камчатский край','2020-04-08 07:44:57'),(5,1,'Магаданская область','2020-04-08 07:44:57'),(6,1,'Приморский край','2020-04-14 09:03:58'),(7,1,'Республика Бурятия','2020-04-14 09:04:07'),(8,1,'Республика Саха (Якутия)','2020-04-14 09:04:18'),(9,1,'Сахалинская область','2020-04-14 09:05:34'),(10,1,'Хабаровский край','2020-04-14 09:05:36'),(11,1,'Чукотский автономный округ','2020-04-14 09:05:41'),(12,2,'Кировская область','2020-04-14 09:05:46'),(13,2,'Нижегородская область','2020-04-14 09:06:23'),(14,2,'Оренбургская область','2020-04-14 09:10:29'),(15,2,'Пензенская область','2020-04-14 09:10:42'),(16,2,'Пермский край','2020-04-14 09:10:48'),(17,2,'Республика Башкортостан','2020-04-14 09:10:53'),(18,2,'Республика Марий Эл','2020-04-14 09:10:58'),(19,2,'Республика Мордовия','2020-04-14 09:11:02'),(20,2,'Республика Татарстан','2020-04-14 09:11:07'),(21,2,'Самарская область','2020-04-14 09:11:11'),(22,2,'Саратовская область','2020-04-14 09:11:16'),(23,2,'Удмуртская Республика','2020-04-14 09:11:20'),(24,2,'Ульяновская область','2020-04-14 09:11:26'),(25,2,'Чувашская Республика','2020-04-14 09:11:32'),(26,3,'Архангельская область','2020-04-14 09:14:18'),(27,3,'Вологодская область','2020-04-14 09:15:43'),(28,3,'Калининградская область','2020-04-14 09:15:53'),(29,3,'Ленинградская область','2020-04-14 09:16:06'),(30,3,'Мурманская область','2020-04-14 09:16:13'),(31,3,'Ненецкий автономный округ','2020-04-14 09:16:19'),(32,3,'Новгородская область','2020-04-14 09:16:28'),(33,3,'Псковская область','2020-04-14 09:16:33'),(34,3,'Республика Карелия','2020-04-14 09:16:40'),(35,3,'Республика Коми','2020-04-14 09:16:41'),(36,3,'Санкт-Петербург','2020-04-14 09:16:47'),(37,4,'Кабардино-Балкарская Республика','2020-04-14 09:19:10'),(38,4,'Карачаево-Черкесская Республика','2020-04-14 09:19:16'),(39,4,'Республика Дагестан','2020-04-14 09:19:41'),(40,4,'Республика Ингушетия','2020-04-14 09:19:46'),(41,4,'Республика Северная Осетия','2020-04-14 09:19:52'),(42,4,'Ставропольский край','2020-04-14 09:19:57'),(43,4,'Чеченская Республика','2020-04-14 09:20:03'),(44,5,'Алтайский край','2020-04-14 09:20:30'),(45,5,'Иркутская область','2020-04-14 09:20:35'),(46,5,'Кемеровская область','2020-04-14 09:20:40'),(47,5,'Красноярский край','2020-04-14 09:20:45'),(48,5,'Новосибирская область','2020-04-14 09:20:53'),(49,5,'Омская область','2020-04-14 09:20:59'),(50,5,'Республика Алтай','2020-04-14 09:21:03'),(51,5,'Республика Тыва','2020-04-14 09:21:07'),(52,5,'Республика Хакасия','2020-04-14 09:21:08'),(53,5,'Томская область','2020-04-14 09:21:12'),(54,6,'Курганская область','2020-04-14 09:21:51'),(55,6,'Свердловская область','2020-04-14 09:21:57'),(56,6,'Тюменская область','2020-04-14 09:22:06'),(57,6,'Ханты-Мансийский автономный округ — Югра','2020-04-14 09:22:10'),(58,6,'Челябинская область','2020-04-14 09:22:15'),(59,6,'Ямало-Ненецкий автономный округ','2020-04-14 09:22:19'),(60,7,'Белгородская область','2020-04-14 09:22:36'),(61,7,'Брянская область','2020-04-14 09:22:43'),(62,7,'Владимирская область','2020-04-14 09:22:48'),(63,7,'Воронежская область','2020-04-14 09:22:56'),(64,7,'Ивановская область','2020-04-14 09:23:00'),(65,7,'Калужская область','2020-04-14 09:23:02'),(66,7,'Костромская область','2020-04-14 09:23:05'),(67,7,'Курская область','2020-04-14 09:23:11'),(68,7,'Липецкая область','2020-04-14 09:23:22'),(69,7,'Москва','2020-04-14 09:23:25'),(70,7,'Московская область','2020-04-14 09:23:29'),(71,7,'Орловская область','2020-04-14 09:23:39'),(72,7,'Рязанская область','2020-04-14 09:23:40'),(73,7,'Смоленская область','2020-04-14 09:23:45'),(74,7,'Тамбовская область','2020-04-14 09:23:48'),(75,7,'Тверская область','2020-04-14 09:23:53'),(76,7,'Тульская область','2020-04-14 09:23:57'),(77,7,'Ярославская область','2020-04-14 09:24:02'),(78,8,'Астраханская область','2020-04-14 09:24:28'),(79,8,'Волгоградская область','2020-04-14 09:24:32'),(80,8,'Краснодарский край','2020-04-14 09:24:37'),(81,8,'Республика Адыгея','2020-04-14 09:24:42'),(82,8,'Республика Калмыкия','2020-04-14 09:24:47'),(83,8,'Республика Крым','2020-04-14 09:24:50'),(84,8,'Ростовская область','2020-04-14 09:24:54'),(85,8,'Севастополь','2020-04-14 09:24:57');
UNLOCK TABLES;
/*!40000 ALTER TABLE `region` ENABLE KEYS */;

--
-- Table structure for table `resource`
--

DROP TABLE IF EXISTS `resource`;
CREATE TABLE `resource` (
  `id` int(11) auto_increment,
  `name` varchar(255),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `resource`
--


/*!40000 ALTER TABLE `resource` DISABLE KEYS */;
LOCK TABLES `resource` WRITE;
INSERT INTO `resource` VALUES (2,'123','2020-04-09 07:32:00');
UNLOCK TABLES;
/*!40000 ALTER TABLE `resource` ENABLE KEYS */;

--
-- Table structure for table `type_organization`
--

DROP TABLE IF EXISTS `type_organization`;
CREATE TABLE `type_organization` (
  `id` int(10) auto_increment,
  `name` varchar(255),
  `created_at` timestamp DEFAULT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `type_organization`
--


/*!40000 ALTER TABLE `type_organization` DISABLE KEYS */;
LOCK TABLES `type_organization` WRITE;
INSERT INTO `type_organization` VALUES (1,'Общеобразовательная организация','2020-04-08 07:44:06'),(2,'Организация дошкольного образования','2020-04-08 07:44:06'),(3,'Организатор питания','2020-04-08 07:44:06'),(4,'Муниципальный орган управления образования','2020-04-08 07:44:06'),(5,'Администратор стационарных загородных организаций отдыха и оздоровления','2020-04-08 07:44:06'),(6,'Учреждение для детей с круглосуточным прибыванием, коррекционные, школы интрернаты','2020-04-08 07:44:06');
UNLOCK TABLES;
/*!40000 ALTER TABLE `type_organization` ENABLE KEYS */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) auto_increment,
  `name` varchar(255),
  `login` varchar(255),
  `auth_key` varchar(32),
  `password_hash` varchar(255),
  `password_reset_token` varchar(255),
  `organization_id` int(10),
  `parent_id` int(10) DEFAULT '0',
  `email` varchar(255),
  `phone` varchar(255),
  `post` varchar(255),
  `photo` varchar(255),
  `status` smallint(6) DEFAULT '10',
  `created_at` int(11),
  `updated_at` int(11),
  `verification_token` varchar(255),
  PRIMARY KEY (`id`)
)/*! engine=InnoDB */;

--
-- Dumping data for table `user`
--


/*!40000 ALTER TABLE `user` DISABLE KEYS */;
LOCK TABLES `user` WRITE;
INSERT INTO `user` VALUES (2,'Ruslan','ad','2FNdvHLGA9cJ0zURv23HyB2c3XEicmi_','$2y$13$.IQkhiayBmgHFTvqQJtwUuo2sT2gd7TFyjpWRCfngbnx8g7rlXX3S',NULL,7,0,'rsbrodo6v@mail.ru','2147483647','','image/users/200x200.png',10,1582129819,1582129819,NULL),(9,'Misha','name1','p16iHGXU5c0AlffsNJqb2P99E7K4RT3z','$2y$13$avsbZ1lFhr6jaf5eLowbq.uROVlsOA5PoSlvs5Pi9xI9cdSmJ3L2W',NULL,0,0,'misha@mail.ru',NULL,'',NULL,9,1585675791,1585675791,'KfP46nXFCOa9YmtFAbuPJQwh-QjhHSeb_1585675791'),(10,'Name','name2','Pgze80tCKWDQSJ3N2Klax2SQjoe8--5X','$2y$13$.IQkhiayBmgHFTvqQJtwUuo2sT2gd7TFyjpWRCfngbnx8g7rlXX3S',NULL,0,0,'name@mail.ru',NULL,'',NULL,9,1585707781,1585707781,'KGhvnf6jMHLJJ7-JPJ3CNgCFs15STzeJ_1585707781'),(13,'rus','name3','k4OT8yS5ZBk3rPN5d8j8JlKr7Lh1mLrD','$2y$13$8AkjW53bUTLbWwJz0L/EHufayIbhYAbdSxJDE9N0cbi2A8zvNUCD2',NULL,0,0,'rsb4rodov@mail.ru','2147483647','','image/users/200x200.png',10,1585713011,1585713011,NULL),(17,'Ima','name4','8h507vHkvdRd1Kg66Bd0deFUNHykSb4L','$2y$13$TzpHFWHBZ1SQQQE8t9v2wuaSYLSkrXEYICukqisrepKmdIQqEKJay',NULL,0,0,'ima@mail.ru',NULL,'',NULL,9,1585714642,1585714642,'f-x7EFg02OD3YgO2G6yTryZAc6dMudWT_1585714642'),(21,'Name Name','name6','TQtPCfKownUOEIZ06e8eyOMTxEK-v40m','$2y$13$naesQnvwj2TubcZBGz5qF.wmJN5exnsjyHk3oA/Wvf7zMTp9qyND2',NULL,0,0,'usreer@mail.ru','2147483647','Director','image/users/200x200.png',10,1585729458,1585729458,NULL),(25,'Administrator','admin@mail.ru','7CeyLcVR1WvKGJBqs-ppYOA7sFcCwFLA','$2y$13$9r8pLcfJzEQy93G9gH2W1.UlSvnWHa5BuT61pfVli9Iw7pIVctERa',NULL,7,0,'rsbrodov@mail.ru','2323232323','Director','image/users/200x200.png',10,1585822780,1585886026,NULL),(26,'name','name7','apUFyv7amTpq8KF3RIhNmw0P32sc5-bk','$2y$13$Co.HVdyhF.sZcLPrJUClCuoGHNSUSi9s0B3mAdwRnrdvZuPt04uIu',NULL,0,0,'rsbrodov@mail.ru','','','image/users/200x200.png',10,1585890067,1585890067,NULL),(27,'name','name8','qLDnWggd2HSWW9jkvziMa71xaRJ6AG4l','$2y$13$FtHVsXgCx8U8Gim5Cpmq..jAVg/FCGFMwve9NQTYU3QEQR9yC.t9u',NULL,0,0,'rsbrodov@mail.ru','','','image/users/200x200.png',10,1585890076,1585890076,NULL),(28,'name','name9','E1FxorwRUCNAx06Lk9yLVDtKswGVe0_M','$2y$13$Yekm55cxpEm9V.TDK.7jFOM3GpWQz1qEt4it30tlvp4.I6ySgq9aC',NULL,0,0,'rsbrodov@mail.ru','','','image/users/200x200.png',10,1585890091,1585890091,NULL),(29,'Сотрудник','name10','LP9D349FKcg4fVSQGmH3j2nkJ8yV-WV3','$2y$13$XVHaRiPkJ/jRiH0/hFE28em4E0uWHh7U/qMtjqW7MKUOgCAkGk06e',NULL,0,0,'',NULL,'1',NULL,10,1585903166,1585903166,NULL),(30,'Сотрудник','name11','qooD2InDJISG4_XTui_7K72E4ybpXk8S','$2y$13$TpQsLE27AZ1x2mzYoMwYUefJrDN6lnnky0xAB96ot5Gz0/JMLg7uC',NULL,0,0,'',NULL,'3',NULL,10,1585907979,1585907979,NULL),(31,'Сотрудник','name12','TCk8ny1YLmd2_vQFu-CRUsFf_-aLrK68','$2y$13$L6keU141EzX06P6bL6yy/Ox9oDmVcfjWk/APKQBGxWG/tXClNxTbK',NULL,0,0,'',NULL,'3',NULL,10,1585908984,1585908984,NULL),(32,'ertertert','rsbrodov@mail.ru','535fluaDru5RqNAfUvLorfOxZOEysLAd','$2y$13$5erDHAGED2k.D/PeQdojWO3Nkq.zju3lKVoDa51wi/Kud2OxicQOm',NULL,0,0,'rsbrodov@mail.ru','4353534534','retetert',NULL,10,1586157133,1586157133,NULL),(33,'qwewqeewqqeqeq','ima@mail.ru','QH6KmlSt36AAxVga7gocsSwbA6VCru5k','$2y$13$gGfuiRIqgAGOWGc1LQsySuDueQHmi8R86kjQhxOXT7HMbVwj5ebuq',NULL,0,0,'ima@mail.ru','2342342342','wqeqweqweqweqe',NULL,10,1586157788,1586157788,NULL),(34,'gdfgdfgdfgdfg','nama@mail.ru','ktjp-5naYkvsqHQeJwQnJNQ8HVCAAE_Q','$2y$13$o0.fu38iryhvgrPxLUAm9O/ULZ6GV.36q1/UjDB8n9um/2r59y07a',NULL,0,0,'nama@mail.ru','3423423423','dfgdfgdfgdf',NULL,10,1586158117,1586158117,NULL),(35,'retertertret','user@mail.ru','JEUF4UQvaIwFS1i0_SFh4chDIiVw9a4w','$2y$13$i3pDHOxlPw4/WuWLiz8eHeNABaUBEqaOvMHvHIKTCuy7eGAn/l2gG',NULL,0,0,'user@mail.ru','3453453453','rtrtrtert',NULL,10,1586158297,1586158297,NULL),(36,'dfgdfgdfgdfgdf','ertertertret@mail.ru','L0JmF0XHXupCeMDV_kjtsS9B-4d82UeM','$2y$13$Nc5ffhDOA1/IggZTSMZIS.ppQ.v1eNAj10rNUGYcUFcqYXSuPdf1G',NULL,0,0,'ertertertret@mail.ru','4534534534','fdgdgdfgdfgdfg',NULL,10,1586158370,1586158370,NULL),(37,'dfgfdgdfgdgg','name@mail.ru','WweHdevdrINDQIvV71YqmXKw-N77Chi5','$2y$13$87IfYTEUnp7lbi89OZFJeusSHOVaBNIS.VtfpXBTXff/Nme/sAiYy',NULL,0,0,'name@mail.ru','3454353535','gdfgdfgdfgdfgdfg',NULL,10,1586158486,1586158486,NULL),(38,'user123dgfgfdgf','ima@mail.ru','r-pI2ifQw_Pd8EdDNhUZnLITEeDDX9kQ','$2y$13$qc6bxOf3n9R9a8utS1CaB.jP24QwIveDMhvncRSXmOkylOZdIit9W',NULL,0,0,'ima@mail.ru','4353453534','dfgdfgdfgdgdgdfgd',NULL,10,1586158612,1586158612,NULL),(39,'Ima ima ima','rsbrodov@mail.ru','Z_evkdmQZ7JSEa1vUhdUIAzgS9AplXk1','$2y$13$0rdswWL1qz/hgi7zZ8vGleCm/30nZo3ZYsS5hjFXJ7SJHJbUz0h1i',NULL,0,0,'rsbrodov@mail.ru','1234556677','Director',NULL,10,1586163174,1586163174,NULL),(40,'Фио сотрудника','rsbrodov@mail.ru','qpEdZIah8ECEvrGnCQnFTemqQEpYdq-6','$2y$13$98pFuII6abySKhgaN6Sa3OV0lcSpZKxFl1P6/JrPKc.FFlIEeIoYS',NULL,7,0,'rsbrodov@mail.ru','1231232131','Директор',NULL,10,1586225835,1586225835,NULL),(41,'новый юзер','12345678','J0aiJOoaUh7qG2zplgh3QMO_ea623Iwu','$2y$13$/5.7trVYhWWiAbH1dIRx4.TCE34BJh6n3Ly4gbJ2spML9NmlGDi.6',NULL,0,2,'',NULL,'2',NULL,10,1586227693,1586227693,NULL),(42,'новый юзер2','12345678','tEYutUyMKTfKLSfxH0lQkAiyglhCloEg','$2y$13$tQiCcB39TPP7wkukmZqju.aROCphqsWdhROmWMco8EcOXZCwVTAFW',NULL,6,2,'',NULL,'1',NULL,10,1586227772,1586227772,NULL),(43,'Сотрудник','12345678','hjWEjDfHKD7jF11x3rWJpAvodHp07xPy','$2y$13$2BF55CEhgpXf182q.IoHBO1ndcaeuJ/KpmvyMooRF0Aus9hWE1KmG',NULL,5,2,'',NULL,'1',NULL,10,1586227929,1586227929,NULL),(44,'Сотрудник2','12345678','tlNOi2tdNBzxfNaJRCWzvh1OiQ5oAC8W','$2y$13$LwdiOsO6HSqs99cC.dvGzuqq4vZJfWJeuSfm6ZtMSp1xenHeeuM7i',NULL,4,2,'',NULL,'3',NULL,10,1586228134,1586228134,NULL),(45,'Сотрудник','12345678','79AMkaUOIFC6DRWrPVg3QnPhaDJvRl7U','$2y$13$WeP8snz1qT3d.aSKs6hpm.S6De19N52u7ibC7CqllD0sx37y9Lpdi',NULL,7,2,'sfsdf',NULL,'1',NULL,10,1586228552,1586228552,NULL),(46,'Namertretert','name@mail.ru','CxlnGTMhXlF6q5THwUGLQwDZlzVhAnFE','$2y$13$mwK3LHHzcllPHyhIOuvmLepkHgLqPxsz0IlacXwvs7n26MuXUZBi2',NULL,8,0,'name@mail.ru','3453453453','Director',NULL,10,1586492214,1586492214,NULL),(47,'Сотрудник','12345678','tyJP0IYK0Re5np_6UndPN2mn2Bb1Ua3D','$2y$13$s9J.mRXY2fHFEqBjOj6HNOxexa/u/jrrPg8z/2xvT2pT63bss57bm',NULL,7,25,'',NULL,'1',NULL,10,1586506065,1586506065,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

