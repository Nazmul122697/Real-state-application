/*
SQLyog Professional v13.1.1 (64 bit)
MySQL - 10.1.38-MariaDB : Database - bti
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `cities` */

DROP TABLE IF EXISTS `cities`;

CREATE TABLE `cities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET latin1 NOT NULL,
  `status` smallint(2) DEFAULT '1' COMMENT '1=Active, 0=Deactive',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `cities` */

/*Table structure for table `countries` */

DROP TABLE IF EXISTS `countries`;

CREATE TABLE `countries` (
  `COUNTRY_PK_NO` int(11) NOT NULL AUTO_INCREMENT,
  `iso` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `nicename` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `iso3` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL,
  `phonecode` int(5) NOT NULL,
  PRIMARY KEY (`COUNTRY_PK_NO`)
) ENGINE=InnoDB AUTO_INCREMENT=240 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `countries` */

insert  into `countries`(`COUNTRY_PK_NO`,`iso`,`name`,`nicename`,`iso3`,`numcode`,`phonecode`) values 
(1,'AF','AFGHANISTAN','Afghanistan','AFG',4,93),
(2,'AL','ALBANIA','Albania','ALB',8,355),
(3,'DZ','ALGERIA','Algeria','DZA',12,213),
(4,'AS','AMERICAN SAMOA','American Samoa','ASM',16,1684),
(5,'AD','ANDORRA','Andorra','AND',20,376),
(6,'AO','ANGOLA','Angola','AGO',24,244),
(7,'AI','ANGUILLA','Anguilla','AIA',660,1264),
(8,'AQ','ANTARCTICA','Antarctica',NULL,NULL,0),
(9,'AG','ANTIGUA AND BARBUDA','Antigua and Barbuda','ATG',28,1268),
(10,'AR','ARGENTINA','Argentina','ARG',32,54),
(11,'AM','ARMENIA','Armenia','ARM',51,374),
(12,'AW','ARUBA','Aruba','ABW',533,297),
(13,'AU','AUSTRALIA','Australia','AUS',36,61),
(14,'AT','AUSTRIA','Austria','AUT',40,43),
(15,'AZ','AZERBAIJAN','Azerbaijan','AZE',31,994),
(16,'BS','BAHAMAS','Bahamas','BHS',44,1242),
(17,'BH','BAHRAIN','Bahrain','BHR',48,973),
(18,'BD','BANGLADESH','Bangladesh','BGD',50,880),
(19,'BB','BARBADOS','Barbados','BRB',52,1246),
(20,'BY','BELARUS','Belarus','BLR',112,375),
(21,'BE','BELGIUM','Belgium','BEL',56,32),
(22,'BZ','BELIZE','Belize','BLZ',84,501),
(23,'BJ','BENIN','Benin','BEN',204,229),
(24,'BM','BERMUDA','Bermuda','BMU',60,1441),
(25,'BT','BHUTAN','Bhutan','BTN',64,975),
(26,'BO','BOLIVIA','Bolivia','BOL',68,591),
(27,'BA','BOSNIA AND HERZEGOVINA','Bosnia and Herzegovina','BIH',70,387),
(28,'BW','BOTSWANA','Botswana','BWA',72,267),
(29,'BV','BOUVET ISLAND','Bouvet Island',NULL,NULL,0),
(30,'BR','BRAZIL','Brazil','BRA',76,55),
(31,'IO','BRITISH INDIAN OCEAN TERRITORY','British Indian Ocean Territory',NULL,NULL,246),
(32,'BN','BRUNEI DARUSSALAM','Brunei Darussalam','BRN',96,673),
(33,'BG','BULGARIA','Bulgaria','BGR',100,359),
(34,'BF','BURKINA FASO','Burkina Faso','BFA',854,226),
(35,'BI','BURUNDI','Burundi','BDI',108,257),
(36,'KH','CAMBODIA','Cambodia','KHM',116,855),
(37,'CM','CAMEROON','Cameroon','CMR',120,237),
(38,'CA','CANADA','Canada','CAN',124,1),
(39,'CV','CAPE VERDE','Cape Verde','CPV',132,238),
(40,'KY','CAYMAN ISLANDS','Cayman Islands','CYM',136,1345),
(41,'CF','CENTRAL AFRICAN REPUBLIC','Central African Republic','CAF',140,236),
(42,'TD','CHAD','Chad','TCD',148,235),
(43,'CL','CHILE','Chile','CHL',152,56),
(44,'CN','CHINA','China','CHN',156,86),
(45,'CX','CHRISTMAS ISLAND','Christmas Island',NULL,NULL,61),
(46,'CC','COCOS (KEELING) ISLANDS','Cocos (Keeling) Islands',NULL,NULL,672),
(47,'CO','COLOMBIA','Colombia','COL',170,57),
(48,'KM','COMOROS','Comoros','COM',174,269),
(49,'CG','CONGO','Congo','COG',178,242),
(50,'CD','CONGO, THE DEMOCRATIC REPUBLIC OF THE','Congo, the Democratic Republic of the','COD',180,242),
(51,'CK','COOK ISLANDS','Cook Islands','COK',184,682),
(52,'CR','COSTA RICA','Costa Rica','CRI',188,506),
(53,'CI','COTE D\'IVOIRE','Cote D\'Ivoire','CIV',384,225),
(54,'HR','CROATIA','Croatia','HRV',191,385),
(55,'CU','CUBA','Cuba','CUB',192,53),
(56,'CY','CYPRUS','Cyprus','CYP',196,357),
(57,'CZ','CZECH REPUBLIC','Czech Republic','CZE',203,420),
(58,'DK','DENMARK','Denmark','DNK',208,45),
(59,'DJ','DJIBOUTI','Djibouti','DJI',262,253),
(60,'DM','DOMINICA','Dominica','DMA',212,1767),
(61,'DO','DOMINICAN REPUBLIC','Dominican Republic','DOM',214,1809),
(62,'EC','ECUADOR','Ecuador','ECU',218,593),
(63,'EG','EGYPT','Egypt','EGY',818,20),
(64,'SV','EL SALVADOR','El Salvador','SLV',222,503),
(65,'GQ','EQUATORIAL GUINEA','Equatorial Guinea','GNQ',226,240),
(66,'ER','ERITREA','Eritrea','ERI',232,291),
(67,'EE','ESTONIA','Estonia','EST',233,372),
(68,'ET','ETHIOPIA','Ethiopia','ETH',231,251),
(69,'FK','FALKLAND ISLANDS (MALVINAS)','Falkland Islands (Malvinas)','FLK',238,500),
(70,'FO','FAROE ISLANDS','Faroe Islands','FRO',234,298),
(71,'FJ','FIJI','Fiji','FJI',242,679),
(72,'FI','FINLAND','Finland','FIN',246,358),
(73,'FR','FRANCE','France','FRA',250,33),
(74,'GF','FRENCH GUIANA','French Guiana','GUF',254,594),
(75,'PF','FRENCH POLYNESIA','French Polynesia','PYF',258,689),
(76,'TF','FRENCH SOUTHERN TERRITORIES','French Southern Territories',NULL,NULL,0),
(77,'GA','GABON','Gabon','GAB',266,241),
(78,'GM','GAMBIA','Gambia','GMB',270,220),
(79,'GE','GEORGIA','Georgia','GEO',268,995),
(80,'DE','GERMANY','Germany','DEU',276,49),
(81,'GH','GHANA','Ghana','GHA',288,233),
(82,'GI','GIBRALTAR','Gibraltar','GIB',292,350),
(83,'GR','GREECE','Greece','GRC',300,30),
(84,'GL','GREENLAND','Greenland','GRL',304,299),
(85,'GD','GRENADA','Grenada','GRD',308,1473),
(86,'GP','GUADELOUPE','Guadeloupe','GLP',312,590),
(87,'GU','GUAM','Guam','GUM',316,1671),
(88,'GT','GUATEMALA','Guatemala','GTM',320,502),
(89,'GN','GUINEA','Guinea','GIN',324,224),
(90,'GW','GUINEA-BISSAU','Guinea-Bissau','GNB',624,245),
(91,'GY','GUYANA','Guyana','GUY',328,592),
(92,'HT','HAITI','Haiti','HTI',332,509),
(93,'HM','HEARD ISLAND AND MCDONALD ISLANDS','Heard Island and Mcdonald Islands',NULL,NULL,0),
(94,'VA','HOLY SEE (VATICAN CITY STATE)','Holy See (Vatican City State)','VAT',336,39),
(95,'HN','HONDURAS','Honduras','HND',340,504),
(96,'HK','HONG KONG','Hong Kong','HKG',344,852),
(97,'HU','HUNGARY','Hungary','HUN',348,36),
(98,'IS','ICELAND','Iceland','ISL',352,354),
(99,'IN','INDIA','India','IND',356,91),
(100,'ID','INDONESIA','Indonesia','IDN',360,62),
(101,'IR','IRAN, ISLAMIC REPUBLIC OF','Iran, Islamic Republic of','IRN',364,98),
(102,'IQ','IRAQ','Iraq','IRQ',368,964),
(103,'IE','IRELAND','Ireland','IRL',372,353),
(104,'IL','ISRAEL','Israel','ISR',376,972),
(105,'IT','ITALY','Italy','ITA',380,39),
(106,'JM','JAMAICA','Jamaica','JAM',388,1876),
(107,'JP','JAPAN','Japan','JPN',392,81),
(108,'JO','JORDAN','Jordan','JOR',400,962),
(109,'KZ','KAZAKHSTAN','Kazakhstan','KAZ',398,7),
(110,'KE','KENYA','Kenya','KEN',404,254),
(111,'KI','KIRIBATI','Kiribati','KIR',296,686),
(112,'KP','KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF','Korea, Democratic People\'s Republic of','PRK',408,850),
(113,'KR','KOREA, REPUBLIC OF','Korea, Republic of','KOR',410,82),
(114,'KW','KUWAIT','Kuwait','KWT',414,965),
(115,'KG','KYRGYZSTAN','Kyrgyzstan','KGZ',417,996),
(116,'LA','LAO PEOPLE\'S DEMOCRATIC REPUBLIC','Lao People\'s Democratic Republic','LAO',418,856),
(117,'LV','LATVIA','Latvia','LVA',428,371),
(118,'LB','LEBANON','Lebanon','LBN',422,961),
(119,'LS','LESOTHO','Lesotho','LSO',426,266),
(120,'LR','LIBERIA','Liberia','LBR',430,231),
(121,'LY','LIBYAN ARAB JAMAHIRIYA','Libyan Arab Jamahiriya','LBY',434,218),
(122,'LI','LIECHTENSTEIN','Liechtenstein','LIE',438,423),
(123,'LT','LITHUANIA','Lithuania','LTU',440,370),
(124,'LU','LUXEMBOURG','Luxembourg','LUX',442,352),
(125,'MO','MACAO','Macao','MAC',446,853),
(126,'MK','MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','Macedonia, the Former Yugoslav Republic of','MKD',807,389),
(127,'MG','MADAGASCAR','Madagascar','MDG',450,261),
(128,'MW','MALAWI','Malawi','MWI',454,265),
(129,'MY','MALAYSIA','Malaysia','MYS',458,60),
(130,'MV','MALDIVES','Maldives','MDV',462,960),
(131,'ML','MALI','Mali','MLI',466,223),
(132,'MT','MALTA','Malta','MLT',470,356),
(133,'MH','MARSHALL ISLANDS','Marshall Islands','MHL',584,692),
(134,'MQ','MARTINIQUE','Martinique','MTQ',474,596),
(135,'MR','MAURITANIA','Mauritania','MRT',478,222),
(136,'MU','MAURITIUS','Mauritius','MUS',480,230),
(137,'YT','MAYOTTE','Mayotte',NULL,NULL,269),
(138,'MX','MEXICO','Mexico','MEX',484,52),
(139,'FM','MICRONESIA, FEDERATED STATES OF','Micronesia, Federated States of','FSM',583,691),
(140,'MD','MOLDOVA, REPUBLIC OF','Moldova, Republic of','MDA',498,373),
(141,'MC','MONACO','Monaco','MCO',492,377),
(142,'MN','MONGOLIA','Mongolia','MNG',496,976),
(143,'MS','MONTSERRAT','Montserrat','MSR',500,1664),
(144,'MA','MOROCCO','Morocco','MAR',504,212),
(145,'MZ','MOZAMBIQUE','Mozambique','MOZ',508,258),
(146,'MM','MYANMAR','Myanmar','MMR',104,95),
(147,'NA','NAMIBIA','Namibia','NAM',516,264),
(148,'NR','NAURU','Nauru','NRU',520,674),
(149,'NP','NEPAL','Nepal','NPL',524,977),
(150,'NL','NETHERLANDS','Netherlands','NLD',528,31),
(151,'AN','NETHERLANDS ANTILLES','Netherlands Antilles','ANT',530,599),
(152,'NC','NEW CALEDONIA','New Caledonia','NCL',540,687),
(153,'NZ','NEW ZEALAND','New Zealand','NZL',554,64),
(154,'NI','NICARAGUA','Nicaragua','NIC',558,505),
(155,'NE','NIGER','Niger','NER',562,227),
(156,'NG','NIGERIA','Nigeria','NGA',566,234),
(157,'NU','NIUE','Niue','NIU',570,683),
(158,'NF','NORFOLK ISLAND','Norfolk Island','NFK',574,672),
(159,'MP','NORTHERN MARIANA ISLANDS','Northern Mariana Islands','MNP',580,1670),
(160,'NO','NORWAY','Norway','NOR',578,47),
(161,'OM','OMAN','Oman','OMN',512,968),
(162,'PK','PAKISTAN','Pakistan','PAK',586,92),
(163,'PW','PALAU','Palau','PLW',585,680),
(164,'PS','PALESTINIAN TERRITORY, OCCUPIED','Palestinian Territory, Occupied',NULL,NULL,970),
(165,'PA','PANAMA','Panama','PAN',591,507),
(166,'PG','PAPUA NEW GUINEA','Papua New Guinea','PNG',598,675),
(167,'PY','PARAGUAY','Paraguay','PRY',600,595),
(168,'PE','PERU','Peru','PER',604,51),
(169,'PH','PHILIPPINES','Philippines','PHL',608,63),
(170,'PN','PITCAIRN','Pitcairn','PCN',612,0),
(171,'PL','POLAND','Poland','POL',616,48),
(172,'PT','PORTUGAL','Portugal','PRT',620,351),
(173,'PR','PUERTO RICO','Puerto Rico','PRI',630,1787),
(174,'QA','QATAR','Qatar','QAT',634,974),
(175,'RE','REUNION','Reunion','REU',638,262),
(176,'RO','ROMANIA','Romania','ROM',642,40),
(177,'RU','RUSSIAN FEDERATION','Russian Federation','RUS',643,70),
(178,'RW','RWANDA','Rwanda','RWA',646,250),
(179,'SH','SAINT HELENA','Saint Helena','SHN',654,290),
(180,'KN','SAINT KITTS AND NEVIS','Saint Kitts and Nevis','KNA',659,1869),
(181,'LC','SAINT LUCIA','Saint Lucia','LCA',662,1758),
(182,'PM','SAINT PIERRE AND MIQUELON','Saint Pierre and Miquelon','SPM',666,508),
(183,'VC','SAINT VINCENT AND THE GRENADINES','Saint Vincent and the Grenadines','VCT',670,1784),
(184,'WS','SAMOA','Samoa','WSM',882,684),
(185,'SM','SAN MARINO','San Marino','SMR',674,378),
(186,'ST','SAO TOME AND PRINCIPE','Sao Tome and Principe','STP',678,239),
(187,'SA','SAUDI ARABIA','Saudi Arabia','SAU',682,966),
(188,'SN','SENEGAL','Senegal','SEN',686,221),
(189,'CS','SERBIA AND MONTENEGRO','Serbia and Montenegro',NULL,NULL,381),
(190,'SC','SEYCHELLES','Seychelles','SYC',690,248),
(191,'SL','SIERRA LEONE','Sierra Leone','SLE',694,232),
(192,'SG','SINGAPORE','Singapore','SGP',702,65),
(193,'SK','SLOVAKIA','Slovakia','SVK',703,421),
(194,'SI','SLOVENIA','Slovenia','SVN',705,386),
(195,'SB','SOLOMON ISLANDS','Solomon Islands','SLB',90,677),
(196,'SO','SOMALIA','Somalia','SOM',706,252),
(197,'ZA','SOUTH AFRICA','South Africa','ZAF',710,27),
(198,'GS','SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS','South Georgia and the South Sandwich Islands',NULL,NULL,0),
(199,'ES','SPAIN','Spain','ESP',724,34),
(200,'LK','SRI LANKA','Sri Lanka','LKA',144,94),
(201,'SD','SUDAN','Sudan','SDN',736,249),
(202,'SR','SURINAME','Suriname','SUR',740,597),
(203,'SJ','SVALBARD AND JAN MAYEN','Svalbard and Jan Mayen','SJM',744,47),
(204,'SZ','SWAZILAND','Swaziland','SWZ',748,268),
(205,'SE','SWEDEN','Sweden','SWE',752,46),
(206,'CH','SWITZERLAND','Switzerland','CHE',756,41),
(207,'SY','SYRIAN ARAB REPUBLIC','Syrian Arab Republic','SYR',760,963),
(208,'TW','TAIWAN, PROVINCE OF CHINA','Taiwan, Province of China','TWN',158,886),
(209,'TJ','TAJIKISTAN','Tajikistan','TJK',762,992),
(210,'TZ','TANZANIA, UNITED REPUBLIC OF','Tanzania, United Republic of','TZA',834,255),
(211,'TH','THAILAND','Thailand','THA',764,66),
(212,'TL','TIMOR-LESTE','Timor-Leste',NULL,NULL,670),
(213,'TG','TOGO','Togo','TGO',768,228),
(214,'TK','TOKELAU','Tokelau','TKL',772,690),
(215,'TO','TONGA','Tonga','TON',776,676),
(216,'TT','TRINIDAD AND TOBAGO','Trinidad and Tobago','TTO',780,1868),
(217,'TN','TUNISIA','Tunisia','TUN',788,216),
(218,'TR','TURKEY','Turkey','TUR',792,90),
(219,'TM','TURKMENISTAN','Turkmenistan','TKM',795,7370),
(220,'TC','TURKS AND CAICOS ISLANDS','Turks and Caicos Islands','TCA',796,1649),
(221,'TV','TUVALU','Tuvalu','TUV',798,688),
(222,'UG','UGANDA','Uganda','UGA',800,256),
(223,'UA','UKRAINE','Ukraine','UKR',804,380),
(224,'AE','UNITED ARAB EMIRATES','United Arab Emirates','ARE',784,971),
(225,'GB','UNITED KINGDOM','United Kingdom','GBR',826,44),
(226,'US','UNITED STATES','United States','USA',840,1),
(227,'UM','UNITED STATES MINOR OUTLYING ISLANDS','United States Minor Outlying Islands',NULL,NULL,1),
(228,'UY','URUGUAY','Uruguay','URY',858,598),
(229,'UZ','UZBEKISTAN','Uzbekistan','UZB',860,998),
(230,'VU','VANUATU','Vanuatu','VUT',548,678),
(231,'VE','VENEZUELA','Venezuela','VEN',862,58),
(232,'VN','VIET NAM','Viet Nam','VNM',704,84),
(233,'VG','VIRGIN ISLANDS, BRITISH','Virgin Islands, British','VGB',92,1284),
(234,'VI','VIRGIN ISLANDS, U.S.','Virgin Islands, U.s.','VIR',850,1340),
(235,'WF','WALLIS AND FUTUNA','Wallis and Futuna','WLF',876,681),
(236,'EH','WESTERN SAHARA','Western Sahara','ESH',732,212),
(237,'YE','YEMEN','Yemen','YEM',887,967),
(238,'ZM','ZAMBIA','Zambia','ZMB',894,260),
(239,'ZW','ZIMBABWE','Zimbabwe','ZWE',716,263);

/*Table structure for table `customers` */

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `id` bigint(20) unsigned NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_code` int(11) NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `customers` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `proc_code` */

DROP TABLE IF EXISTS `proc_code`;

CREATE TABLE `proc_code` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `script` text COLLATE utf8_unicode_ci,
  `tname` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pname` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=587 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `proc_code` */

/*Table structure for table `profiles` */

DROP TABLE IF EXISTS `profiles`;

CREATE TABLE `profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` datetime DEFAULT NULL,
  `city_id` int(10) unsigned DEFAULT NULL,
  `country_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `profiles` */

/*Table structure for table `s_company` */

DROP TABLE IF EXISTS `s_company`;

CREATE TABLE `s_company` (
  `c_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `c_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_pk_no` bigint(20) DEFAULT NULL,
  `c_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_logo` varchar(201) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_slogan` varchar(202) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_addr1` varchar(203) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_addr2` varchar(204) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_addr3` varchar(205) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_city` varchar(206) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_phone1` varchar(207) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_phone2` varchar(208) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_contact_person` varchar(209) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`c_pk_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `s_company` */

/*Table structure for table `s_groupcomp` */

DROP TABLE IF EXISTS `s_groupcomp`;

CREATE TABLE `s_groupcomp` (
  `gc_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `gc_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_logo` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_slogan` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_addr1` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_addr2` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_addr3` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_city` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_phone1` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_phone2` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gc_contact_person` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`gc_pk_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `s_groupcomp` */

/*Table structure for table `s_lookdata` */

DROP TABLE IF EXISTS `s_lookdata`;

CREATE TABLE `s_lookdata` (
  `lookup_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `lookup_type` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lookup_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lookup_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lookup_row_status` int(11) DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`lookup_pk_no`)
) ENGINE=MyISAM AUTO_INCREMENT=120 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `s_lookdata` */

insert  into `s_lookdata`(`lookup_pk_no`,`lookup_type`,`lookup_id`,`lookup_name`,`lookup_row_status`,`c_pk_no_created`,`created_by`,`created_at`,`updated_by`,`updated_at`,`c_pk_no_updated`) values 
(1,'0','1','Super Admin',1,1,1,'2020-01-04',NULL,NULL,NULL),
(2,'10','1','Business',1,1,1,'2020-01-04',NULL,NULL,NULL),
(3,'10','1','Service Holder',1,1,1,'2020-01-04',NULL,'2020-01-12',NULL),
(5,'10','1','Bank Job',1,1,1,'2020-01-04',NULL,NULL,NULL),
(6,'10','1','Private Service Holder',1,1,1,'2020-01-04',NULL,'2020-01-12',NULL),
(7,'10','1','University Professor',1,1,1,'2020-01-04',NULL,NULL,NULL),
(8,'10','1','Job',1,1,1,'2020-01-04',NULL,NULL,NULL),
(9,'10','1','Creative designer',1,1,1,'2020-01-04',NULL,NULL,NULL),
(10,'10','1','Corporate Service',1,1,1,'2020-01-04',NULL,NULL,NULL),
(11,'10','1','Banker',1,1,1,'2020-01-04',NULL,NULL,NULL),
(12,'10','1','Businessman (Civil Engineer)',1,1,1,'2020-01-04',NULL,NULL,NULL),
(13,'10','1','Doctor',1,1,1,'2020-01-04',NULL,NULL,NULL),
(14,'10','1','Teacher',1,1,1,'2020-01-04',NULL,NULL,NULL),
(15,'10','1','Engineer',1,1,1,'2020-01-04',NULL,NULL,NULL),
(16,'10','1','Student',1,1,1,'2020-01-04',NULL,NULL,NULL),
(17,'10','1','Interior Architect',1,1,1,'2020-01-04',NULL,NULL,NULL),
(18,'10','1','Freelancer',1,1,1,'2020-01-04',NULL,NULL,NULL),
(19,'10','1','Private company service',1,1,1,'2020-01-04',NULL,NULL,NULL),
(20,'10','1','Private Service',1,1,1,'2020-01-04',NULL,NULL,NULL),
(21,'10','1','Private Service (National Professional)',1,1,1,'2020-01-04',NULL,NULL,NULL),
(22,'10','1','Architect',1,1,1,'2020-01-04',NULL,NULL,NULL),
(23,'4','1','Classic',1,1,1,'2020-01-04',NULL,NULL,NULL),
(24,'4','1','Premium',1,1,1,'2020-01-04',NULL,NULL,NULL),
(25,'4','1','Brokerage',1,1,1,'2020-01-04',NULL,NULL,NULL),
(26,'4','1','Commercial',1,1,1,'2020-01-04',NULL,NULL,NULL),
(27,'4','1','Chittagong',1,1,1,'2020-01-04',NULL,NULL,NULL),
(28,'4','1','Standard',1,1,1,'2020-01-04',NULL,NULL,NULL),
(29,'4','1','Platinum',1,1,1,'2020-01-04',NULL,NULL,NULL),
(30,'5','1','Uttara',1,1,1,'2020-01-04',NULL,NULL,NULL),
(31,'5','1','Gulshan',1,1,1,'2020-01-04',NULL,NULL,NULL),
(32,'5','1','Bashundhara',1,1,1,'2020-01-04',NULL,NULL,NULL),
(33,'5','1','Mirpur',1,1,1,'2020-01-04',NULL,NULL,NULL),
(34,'5','1','Lalmatia',1,1,1,'2020-01-04',NULL,NULL,NULL),
(35,'5','1','Kallanpur',1,1,1,'2020-01-04',NULL,NULL,NULL),
(36,'5','1','Badda',1,1,1,'2020-01-04',NULL,NULL,NULL),
(37,'5','1','Norda',1,1,1,'2020-01-04',NULL,NULL,NULL),
(38,'5','1','Rampura',1,1,1,'2020-01-04',NULL,NULL,NULL),
(39,'5','1','Wari',1,1,1,'2020-01-04',NULL,NULL,NULL),
(40,'5','1','Savar',1,1,1,'2020-01-04',NULL,NULL,NULL),
(41,'5','1','Uttarkhan',1,1,1,'2020-01-04',NULL,NULL,NULL),
(42,'5','1','Dhanmondi',1,1,1,'2020-01-04',NULL,NULL,NULL),
(43,'5','1','Banani',1,1,1,'2020-01-04',NULL,NULL,NULL),
(44,'6','1','Casafliz',1,1,1,'2020-01-04',NULL,NULL,NULL),
(45,'6','1','Three',1,1,1,'2020-01-04',NULL,NULL,NULL),
(46,'6','1','Quantam',1,1,1,'2020-01-04',NULL,NULL,NULL),
(47,'6','1','lavarna',1,1,1,'2020-01-04',NULL,NULL,NULL),
(48,'6','1','Gift',1,1,1,'2020-01-04',NULL,NULL,NULL),
(49,'6','1','Afnan',1,1,1,'2020-01-04',NULL,NULL,NULL),
(50,'6','1','Home',1,1,1,'2020-01-04',NULL,NULL,NULL),
(51,'6','1','Plaza',1,1,1,'2020-01-04',NULL,NULL,NULL),
(52,'6','1','Twin',1,1,1,'2020-01-04',NULL,NULL,NULL),
(53,'6','1','Rosemary',1,1,1,'2020-01-04',NULL,NULL,NULL),
(54,'6','1','Nobab',1,1,1,'2020-01-04',NULL,NULL,NULL),
(55,'6','1','Shopnobilash',1,1,1,'2020-01-04',NULL,NULL,NULL),
(56,'6','1','Chayabithi',1,1,1,'2020-01-04',NULL,NULL,NULL),
(57,'6','1','Shopnoneer',1,1,1,'2020-01-04',NULL,NULL,NULL),
(58,'6','1','Destination',1,1,1,'2020-01-04',NULL,NULL,NULL),
(59,'6','1','Park panoroma',1,1,1,'2020-01-04',NULL,NULL,NULL),
(60,'6','1','Vanetion',1,1,1,'2020-01-04',NULL,NULL,NULL),
(61,'6','1','Address',1,1,1,'2020-01-04',NULL,NULL,NULL),
(62,'6','1','Enclave',1,1,1,'2020-01-04',NULL,NULL,NULL),
(63,'7','1','1500 sqft.',1,1,1,'2020-01-04',NULL,NULL,NULL),
(64,'7','1','5000 sqft.',1,1,1,'2020-01-04',NULL,NULL,NULL),
(65,'7','1','1200 sqft.',1,1,1,'2020-01-04',NULL,NULL,NULL),
(66,'7','1','2000 sqft.',1,1,1,'2020-01-04',NULL,NULL,NULL),
(67,'7','1','1800 sqft.',1,1,1,'2020-01-04',NULL,NULL,NULL),
(68,'7','1','1100 sqft.',1,1,1,'2020-01-04',NULL,NULL,NULL),
(69,'7','1','2100 sqft.',1,1,1,'2020-01-04',NULL,NULL,NULL),
(70,'7','1','1400 sqft',1,1,1,'2020-01-04',NULL,NULL,NULL),
(71,'7','1','3000 sqft.',1,1,1,'2020-01-04',NULL,NULL,NULL),
(72,'7','1','2500 sqft.',1,1,1,'2020-01-04',NULL,NULL,NULL),
(73,'1','1','CRE - Customer Relationship Executives',1,1,1,'2020-01-04',NULL,NULL,NULL),
(74,'1','1','DM - Digital Marketing',1,1,1,'2020-01-04',NULL,NULL,NULL),
(75,'1','1','IR - Internel Reference',1,1,1,'2020-01-04',NULL,NULL,NULL),
(76,'1','1','HC - Hotline Custodian',1,1,1,'2020-01-04',NULL,NULL,NULL),
(77,'1','1','ST - Sales Team',1,1,1,'2020-01-04',NULL,NULL,NULL),
(83,'16','1','K1',1,1,1,'2020-01-04',NULL,NULL,NULL),
(84,'16','1','Leads',1,1,1,'2020-01-04',NULL,NULL,NULL),
(85,'16','1','Priority',1,1,1,'2020-01-04',NULL,NULL,NULL),
(86,'16','1','Transferred',1,1,1,'2020-01-04',NULL,NULL,NULL),
(87,'16','1','Sold',1,1,1,'2020-01-04',NULL,NULL,NULL),
(88,'16','1','Hold',1,1,1,'2020-01-04',NULL,NULL,NULL),
(89,'16','1','Closed',1,1,1,'2020-01-04',NULL,NULL,NULL),
(90,'16','1','Accepted',1,1,1,'2020-01-04',NULL,NULL,NULL),
(91,'11','1','Prothom Alo',1,1,1,'2020-01-04',NULL,NULL,NULL),
(92,'11','1','The Daily Star',1,1,1,'2020-01-04',NULL,NULL,NULL),
(93,'11','1','The Jugantor',1,1,1,'2020-01-04',NULL,NULL,NULL),
(94,'11','1','The Daily Somokal',1,1,1,'2020-01-04',NULL,NULL,NULL),
(95,'12','1','Uttara',1,1,1,'2020-01-04',NULL,NULL,NULL),
(96,'12','1','Banani',1,1,1,'2020-01-04',NULL,NULL,NULL),
(97,'12','1','Gulshan',1,1,1,'2020-01-04',NULL,NULL,NULL),
(98,'12','1','Dhanmondi',1,1,1,'2020-01-04',NULL,NULL,NULL),
(99,'18','1','CRE - Customer Relationship Executives',1,1,1,'2020-01-04',NULL,NULL,NULL),
(100,'18','1','DM - Digital Marketing',1,1,1,'2020-01-04',NULL,NULL,NULL),
(101,'18','1','IR - Internal Reference',1,1,1,'2020-01-04',NULL,NULL,NULL),
(102,'18','1','HC - Hotline Custodian',1,1,1,'2020-01-04',NULL,NULL,NULL),
(103,'18','1','ST - Sales Team',1,1,1,'2020-01-04',NULL,NULL,NULL),
(118,'3','1','Website',1,1,1,'2020-01-05',NULL,NULL,NULL),
(117,'3','1','Facebook',1,1,1,'2020-01-05',NULL,NULL,NULL),
(109,'17','1','Today Follow up List',1,1,1,'2020-01-04',NULL,NULL,NULL),
(110,'17','1','Missed Follow up',1,1,1,'2020-01-04',NULL,NULL,NULL),
(111,'17','1','Next Follow up',1,1,1,'2020-01-04',NULL,NULL,NULL),
(112,'2','1','Facebook',1,1,1,'2020-01-04',NULL,NULL,NULL),
(113,'2','1','Website',1,1,1,'2020-01-04',NULL,NULL,NULL),
(114,'2','1','Email',1,1,1,'2020-01-04',NULL,NULL,NULL),
(115,'2','1','Youtube',1,1,1,'2020-01-04',NULL,NULL,NULL),
(116,'2','1','Linkedin',1,1,1,'2020-01-04',NULL,NULL,NULL),
(119,'1','1','SAC',1,1,1,'2020-01-11',NULL,NULL,NULL);

/*Table structure for table `s_pages` */

DROP TABLE IF EXISTS `s_pages`;

CREATE TABLE `s_pages` (
  `page_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `page_route` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `row_status` int(11) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `module_lookup_pk_no` int(11) DEFAULT NULL,
  PRIMARY KEY (`page_pk_no`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `s_pages` */

insert  into `s_pages`(`page_pk_no`,`page_name`,`page_route`,`row_status`,`created_by`,`created_at`,`updated_by`,`updated_at`,`module_lookup_pk_no`) values 
(1,'Lead Entry','lead.index',1,1,'2019-11-29',NULL,NULL,1),
(2,'Lead QC','lead_qc',1,1,'2019-11-29',NULL,NULL,1),
(3,'Lead Followup','lead_follow_up.index',1,1,'2019-11-29',NULL,NULL,2),
(4,'Lead Transfer','lead_transfer',1,1,'2019-11-29',NULL,NULL,2),
(5,'Search Engine','search_engine',1,1,'2019-11-29',NULL,NULL,3),
(6,'Lookup','settings.index',1,1,'2019-11-29',NULL,NULL,4),
(7,'Users','user.index',1,1,'2019-11-29',NULL,NULL,4),
(8,'Access Management','rbac',1,1,'2019-11-29',NULL,NULL,4),
(9,'Team Management','team.index',1,1,'2019-11-29',NULL,NULL,4),
(10,'Lead Distribution','lead_dist_list',1,1,'2019-11-29',NULL,NULL,1),
(11,'Project wise Flat setup','project_wise_flat',1,1,'2019-11-29',NULL,NULL,4),
(12,'Dashboard','admin.dashboard',1,1,'2019-11-29',NULL,NULL,5),
(13,'Team Target','team_target',1,1,'2019-11-29',NULL,NULL,2),
(14,'','',NULL,NULL,NULL,NULL,NULL,0);

/*Table structure for table `s_projectwiseflatlist` */

DROP TABLE IF EXISTS `s_projectwiseflatlist`;

CREATE TABLE `s_projectwiseflatlist` (
  `flatlist_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `project_lookup_pk_no` bigint(20) DEFAULT NULL,
  `flat_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flat_name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_lookup_pk_no` bigint(20) DEFAULT NULL,
  `area_lookup_pk_no` bigint(20) DEFAULT NULL,
  `size_lookup_pk_no` bigint(20) DEFAULT NULL,
  `flat_description` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flat_status` int(11) DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`flatlist_pk_no`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `s_projectwiseflatlist` */

insert  into `s_projectwiseflatlist`(`flatlist_pk_no`,`project_lookup_pk_no`,`flat_id`,`flat_name`,`category_lookup_pk_no`,`area_lookup_pk_no`,`size_lookup_pk_no`,`flat_description`,`flat_status`,`c_pk_no_created`,`created_by`,`created_at`,`updated_by`,`updated_at`,`c_pk_no_updated`) values 
(1,44,NULL,'A1',23,32,63,'Uttara',1,NULL,1,'2020-01-07',NULL,'2020-01-12',NULL),
(2,44,NULL,'A2',23,NULL,63,NULL,0,NULL,1,'2020-01-04',NULL,'2020-01-04',NULL),
(3,53,NULL,'A1',24,NULL,64,'Gulshan',0,NULL,1,'2020-01-04',NULL,'2020-01-04',NULL),
(4,53,NULL,'A2',24,NULL,64,'Gulshan',0,NULL,1,'2020-01-04',NULL,'2020-01-04',NULL),
(5,46,NULL,'A1',24,NULL,66,'Uttara',0,NULL,1,'2020-01-04',NULL,'2020-01-04',NULL),
(6,46,NULL,'B1',24,NULL,66,'Banani',0,NULL,1,'2020-01-04',NULL,'2020-01-04',NULL);

/*Table structure for table `s_rbac` */

DROP TABLE IF EXISTS `s_rbac`;

CREATE TABLE `s_rbac` (
  `rbac_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `role_lookup_pk_no` bigint(20) NOT NULL,
  `page_pk_no` bigint(20) NOT NULL,
  `row_status` int(11) NOT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`rbac_pk_no`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `s_rbac` */

insert  into `s_rbac`(`rbac_pk_no`,`role_lookup_pk_no`,`page_pk_no`,`row_status`,`created_by`,`created_at`,`updated_by`,`updated_at`) values 
(1,1,8,1,NULL,'2019-12-14',NULL,'2019-12-14'),
(2,1,1,1,NULL,'2019-12-14',NULL,'2019-12-14'),
(3,1,2,1,NULL,'2019-12-14',NULL,'2019-12-14'),
(4,1,3,1,NULL,'2019-12-14',NULL,'2019-12-14'),
(5,1,5,1,NULL,'2019-12-14',NULL,'2019-12-14'),
(6,1,6,1,NULL,'2019-12-14',NULL,'2019-12-14'),
(7,1,9,1,NULL,'2019-12-14',NULL,'2019-12-14'),
(8,1,4,1,NULL,'2019-12-14',NULL,'2019-12-14'),
(9,1,7,1,NULL,'2019-12-14',NULL,'2019-12-14'),
(10,49,1,1,NULL,'2019-12-15',NULL,'2019-12-15'),
(11,52,1,1,NULL,'2019-12-18',NULL,'2019-12-18'),
(12,49,2,1,NULL,'2019-12-19',NULL,'2019-12-19'),
(13,1,10,1,NULL,'2019-12-22',NULL,'2019-12-22'),
(14,1,12,1,NULL,'2020-01-02',NULL,'2020-01-02'),
(15,1,11,1,NULL,'2020-01-02',NULL,'2020-01-02'),
(16,1,13,1,NULL,'2020-01-02',NULL,'2020-01-02'),
(17,52,3,1,NULL,'2020-01-02',NULL,'2020-01-02'),
(18,52,4,1,NULL,'2020-01-02',NULL,'2020-01-02'),
(19,52,5,1,NULL,'2020-01-02',NULL,'2020-01-02'),
(20,52,12,1,NULL,'2020-01-02',NULL,'2020-01-02'),
(21,73,1,1,NULL,'2020-01-04',NULL,'2020-01-04'),
(22,73,12,1,NULL,'2020-01-04',NULL,'2020-01-04'),
(23,74,1,1,NULL,'2020-01-04',NULL,'2020-01-04'),
(24,74,12,1,NULL,'2020-01-04',NULL,'2020-01-04'),
(25,75,1,1,NULL,'2020-01-04',NULL,'2020-01-04'),
(26,75,12,1,NULL,'2020-01-04',NULL,'2020-01-04'),
(27,76,1,1,NULL,'2020-01-04',NULL,'2020-01-04'),
(28,76,12,1,NULL,'2020-01-04',NULL,'2020-01-04'),
(29,73,2,1,NULL,'2020-01-04',NULL,'2020-01-04'),
(30,73,10,1,NULL,'2020-01-04',NULL,'2020-01-04'),
(31,73,3,0,NULL,'2020-01-04',NULL,'2020-01-04'),
(32,73,4,0,NULL,'2020-01-04',NULL,'2020-01-04'),
(33,73,13,0,NULL,'2020-01-04',NULL,'2020-01-04'),
(34,73,5,1,NULL,'2020-01-04',NULL,'2020-01-04'),
(35,76,2,1,NULL,'2020-01-05',NULL,'2020-01-05'),
(36,76,10,1,NULL,'2020-01-05',NULL,'2020-01-05'),
(37,73,8,0,NULL,'2020-01-05',NULL,'2020-01-05'),
(38,73,9,1,NULL,'2020-01-05',NULL,'2020-01-05'),
(39,74,3,1,NULL,'2020-01-12',NULL,'2020-01-12'),
(40,74,4,1,NULL,'2020-01-12',NULL,'2020-01-12'),
(41,74,13,1,NULL,'2020-01-12',NULL,'2020-01-12'),
(42,77,3,1,NULL,'2020-01-13',NULL,'2020-01-13'),
(43,77,4,1,NULL,'2020-01-13',NULL,'2020-01-13'),
(44,77,5,1,NULL,'2020-01-13',NULL,'2020-01-13'),
(45,77,12,1,NULL,'2020-01-13',NULL,'2020-01-13');

/*Table structure for table `s_teamuser` */

DROP TABLE IF EXISTS `s_teamuser`;

CREATE TABLE `s_teamuser` (
  `team_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `role_lookup_pk_no` bigint(20) DEFAULT NULL,
  `user_pk_no` bigint(20) DEFAULT NULL,
  `is_team_leader` int(11) DEFAULT NULL,
  `row_status` int(11) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`team_pk_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `s_teamuser` */

/*Table structure for table `s_user` */

DROP TABLE IF EXISTS `s_user`;

CREATE TABLE `s_user` (
  `user_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `User_name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_fullname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_lookup_pk_no` bigint(20) DEFAULT NULL,
  `email_id` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile_no` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nid` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_photo` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `row_status` int(11) DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL,
  `is_bypass` int(11) DEFAULT NULL,
  `bypass_date` date DEFAULT NULL,
  `user_type` int(11) DEFAULT '0',
  `is_super_admin` int(11) DEFAULT NULL,
  `auto_distribute` int(11) DEFAULT '0',
  `distribute_date` date DEFAULT NULL,
  PRIMARY KEY (`user_pk_no`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `s_user` */

insert  into `s_user`(`user_pk_no`,`user_id`,`User_name`,`user_fullname`,`employee_id`,`role_lookup_pk_no`,`email_id`,`mobile_no`,`address`,`nid`,`user_photo`,`row_status`,`c_pk_no_created`,`created_by`,`created_at`,`updated_by`,`updated_at`,`c_pk_no_updated`,`is_bypass`,`bypass_date`,`user_type`,`is_super_admin`,`auto_distribute`,`distribute_date`) values 
(1,16,'Jahid Hasan','Jahid Hasan','',74,'jahid0209@gmail.com','01676089093','Dhaka',NULL,NULL,NULL,NULL,NULL,'2019-12-07',NULL,'2020-01-12',NULL,1,'1970-01-01',2,NULL,0,NULL),
(2,17,'sumon','Shumon Khan','',1,'shumonkhan@gmail.com','01547896589','Uttara',NULL,NULL,NULL,NULL,NULL,'2019-12-07',NULL,'2019-12-07',NULL,NULL,NULL,0,NULL,0,NULL),
(3,18,'Zaman','Zaman','',76,'zaman1234@gmail.com','01745784521','Uttara',NULL,NULL,NULL,NULL,NULL,'2019-12-28',NULL,'2020-01-11',NULL,1,'2020-01-11',1,NULL,0,'1970-01-01'),
(4,19,'Meraj Ahmed','Meraj Ahmed','',74,'meraj.dm@btibd.org','01673896446','Dhaka, Bangladesh',NULL,NULL,NULL,NULL,NULL,'2020-01-02',NULL,'2020-01-04',NULL,NULL,NULL,0,NULL,0,NULL),
(5,20,'Shila Ahamed','Shila Ahamed','',73,'shila123@gmail.com','01745465115','Banasree, Dhaka',NULL,NULL,NULL,NULL,NULL,'2020-01-04',NULL,'2020-01-07',NULL,NULL,NULL,1,NULL,0,NULL),
(6,21,'Md. Mahfuz','Md. Mahfuz','',73,'mahfuz.rt@xibd.org','01524546844','Badda, Dhaka',NULL,NULL,NULL,NULL,NULL,'2020-01-04',NULL,'2020-01-04',NULL,NULL,NULL,2,NULL,0,NULL),
(7,22,'Mrs. Jamila','Mrs. Jamila','',74,'jamila@gmail.com','01234565455','Dhanmondi',NULL,NULL,NULL,NULL,NULL,'2020-01-04',NULL,'2020-01-04',NULL,NULL,NULL,1,NULL,0,NULL),
(8,23,'Mrs. Kamila','Mrs. Kamila','',75,'kamila34@btibd.org','01234567857','Mirpur, Dhaka',NULL,NULL,NULL,NULL,NULL,'2020-01-04',NULL,'2020-01-04',NULL,NULL,NULL,1,NULL,0,NULL),
(10,27,'maidul','maidul','',73,'maidul23@bti.com','012123215421','Mohakhali, Dhaka',NULL,NULL,NULL,NULL,NULL,'2020-01-05',NULL,'2020-01-05',NULL,NULL,NULL,1,NULL,0,NULL),
(9,26,'Raha','Raha','',73,'raha.rt@gmail.com','01234564471','Mirpur, Dhaka',NULL,NULL,NULL,NULL,NULL,'2020-01-04',NULL,'2020-01-05',NULL,NULL,NULL,2,NULL,0,NULL),
(11,28,'Shuvro','Shuvro','',73,'shuvro@cm.com','01234556788','Mohammadpur, Dhaka',NULL,NULL,NULL,NULL,NULL,'2020-01-05',NULL,'2020-01-05',NULL,NULL,NULL,2,NULL,0,NULL),
(12,29,'Jisan','Jisan','',73,'jisan@c.com','0123456789','Badda, Dhaka',NULL,NULL,NULL,NULL,NULL,'2020-01-05',NULL,'2020-01-05',NULL,NULL,NULL,1,NULL,0,NULL),
(13,30,'Rakib','Rakib','',73,'rakib@gmail.com','0123456878','Banani, Dhaka',NULL,NULL,NULL,NULL,NULL,'2020-01-05',NULL,'2020-01-05',NULL,NULL,NULL,2,NULL,0,NULL),
(14,31,'Emon Khan','Emon Khan','',76,'emon@gmail.com','01989389586','Dhaka',NULL,NULL,NULL,NULL,NULL,'2020-01-05',NULL,'2020-01-05',NULL,NULL,NULL,1,NULL,0,NULL),
(15,24,'Admin','Administrator',NULL,1,'admin@app.com',NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,1,0,NULL),
(16,32,'Sohanul Islam Khan','Sohanul Islam Khan','',77,'sohan@gmail.com','01689598659',NULL,NULL,NULL,NULL,NULL,NULL,'2020-01-12',NULL,'2020-01-12',NULL,NULL,NULL,2,NULL,0,NULL),
(17,33,'test user','test user','',77,'test@gmail.com','01989695869',NULL,NULL,NULL,NULL,NULL,NULL,'2020-01-12',NULL,'2020-01-12',NULL,NULL,NULL,2,NULL,0,NULL);

/*Table structure for table `t_leadfollowup` */

DROP TABLE IF EXISTS `t_leadfollowup`;

CREATE TABLE `t_leadfollowup` (
  `lead_followup_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `lead_followup_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lead_followup_datetime` date DEFAULT NULL,
  `lead_pk_no` bigint(20) DEFAULT NULL,
  `Followup_type_pk_no` bigint(20) DEFAULT NULL,
  `followup_Note` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lead_stage_before_followup` int(11) DEFAULT NULL,
  `next_followup_flag` int(11) DEFAULT NULL,
  `Next_FollowUp_date` date DEFAULT NULL,
  `next_followup_Prefered_Time` datetime DEFAULT NULL,
  `next_followup_Note` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lead_stage_after_followup` int(200) DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`lead_followup_pk_no`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `t_leadfollowup` */

insert  into `t_leadfollowup`(`lead_followup_pk_no`,`lead_followup_id`,`lead_followup_datetime`,`lead_pk_no`,`Followup_type_pk_no`,`followup_Note`,`lead_stage_before_followup`,`next_followup_flag`,`Next_FollowUp_date`,`next_followup_Prefered_Time`,`next_followup_Note`,`lead_stage_after_followup`,`c_pk_no_created`,`created_by`,`created_at`,`updated_by`,`updated_at`,`c_pk_no_updated`) values 
(34,'1','2020-01-13',54,101,'Discuss about price',3,1,'2020-01-18','2020-01-18 10:00:00','Discuss about price',4,1,16,'2020-01-13',NULL,NULL,NULL);

/*Table structure for table `t_leadlifecycle` */

DROP TABLE IF EXISTS `t_leadlifecycle`;

CREATE TABLE `t_leadlifecycle` (
  `leadlifecycle_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `leadlifecycle_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lead_pk_no` bigint(20) DEFAULT NULL,
  `lead_dist_type` int(11) DEFAULT '0',
  `lead_sales_agent_pk_no` bigint(20) DEFAULT NULL,
  `lead_current_stage` int(11) DEFAULT NULL,
  `lead_qc_flag` int(11) DEFAULT NULL,
  `lead_qc_datetime` date DEFAULT NULL,
  `lead_qc_by` bigint(20) DEFAULT NULL,
  `lead_k1_flag` int(11) DEFAULT NULL,
  `lead_k1_datetime` date DEFAULT NULL,
  `lead_k1_by` bigint(20) DEFAULT NULL,
  `lead_priority_flag` int(11) DEFAULT NULL,
  `lead_priority_datetime` date DEFAULT NULL,
  `lead_priority_by` bigint(20) DEFAULT NULL,
  `lead_hold_flag` int(11) DEFAULT NULL,
  `lead_hold_datetime` date DEFAULT NULL,
  `lead_hold_by` bigint(20) DEFAULT NULL,
  `lead_closed_flag` int(11) DEFAULT NULL,
  `lead_closed_datetime` date DEFAULT NULL,
  `lead_closed_by` bigint(20) DEFAULT NULL,
  `lead_sold_flag` int(11) DEFAULT NULL,
  `flatlist_pk_no` bigint(11) DEFAULT NULL,
  `lead_sold_datetime` date DEFAULT NULL,
  `lead_sold_by` bigint(20) DEFAULT NULL,
  `lead_sold_date_manual` date DEFAULT NULL,
  `lead_sold_flatcost` float DEFAULT NULL,
  `lead_sold_utilitycost` float DEFAULT NULL,
  `lead_sold_parkingcost` float DEFAULT NULL,
  `lead_sold_customer_pk_no` bigint(20) DEFAULT NULL,
  `lead_sold_sales_agent_pk_no` bigint(20) DEFAULT NULL,
  `lead_sold_team_lead_pk_no` bigint(20) DEFAULT NULL,
  `lead_sold_team_manager_pk_no` bigint(20) DEFAULT NULL,
  `lead_transfer_flag` int(11) DEFAULT NULL,
  `lead_transfer_from_sales_agent_pk_no` bigint(20) DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`leadlifecycle_pk_no`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `t_leadlifecycle` */

insert  into `t_leadlifecycle`(`leadlifecycle_pk_no`,`leadlifecycle_id`,`lead_pk_no`,`lead_dist_type`,`lead_sales_agent_pk_no`,`lead_current_stage`,`lead_qc_flag`,`lead_qc_datetime`,`lead_qc_by`,`lead_k1_flag`,`lead_k1_datetime`,`lead_k1_by`,`lead_priority_flag`,`lead_priority_datetime`,`lead_priority_by`,`lead_hold_flag`,`lead_hold_datetime`,`lead_hold_by`,`lead_closed_flag`,`lead_closed_datetime`,`lead_closed_by`,`lead_sold_flag`,`flatlist_pk_no`,`lead_sold_datetime`,`lead_sold_by`,`lead_sold_date_manual`,`lead_sold_flatcost`,`lead_sold_utilitycost`,`lead_sold_parkingcost`,`lead_sold_customer_pk_no`,`lead_sold_sales_agent_pk_no`,`lead_sold_team_lead_pk_no`,`lead_sold_team_manager_pk_no`,`lead_transfer_flag`,`lead_transfer_from_sales_agent_pk_no`,`c_pk_no_created`,`created_by`,`created_at`,`updated_by`,`updated_at`,`c_pk_no_updated`) values 
(40,'1',54,1,16,4,1,'2020-01-13',5,1,'2020-01-13',8,1,'2020-01-13',16,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,8,'2020-01-13',1,'2020-01-13',NULL);

/*Table structure for table `t_leads` */

DROP TABLE IF EXISTS `t_leads`;

CREATE TABLE `t_leads` (
  `lead_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `lead_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_firstname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_lastname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone1_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone1` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone2_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone2` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_id` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `occupation_pk_no` bigint(20) DEFAULT NULL,
  `organization_pk_no` bigint(20) DEFAULT NULL,
  `project_category_pk_no` bigint(20) DEFAULT NULL,
  `project_area_pk_no` bigint(20) DEFAULT NULL,
  `Project_pk_no` bigint(20) DEFAULT NULL,
  `project_size_pk_no` bigint(20) DEFAULT NULL,
  `source_auto_pk_no` bigint(20) DEFAULT NULL,
  `source_auto_usergroup_pk_no` bigint(20) DEFAULT NULL,
  `source_sac_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_sac_note` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_digital_marketing` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_hotline` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_internal_reference` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_ir_emp_id` int(11) DEFAULT NULL,
  `source_ir_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_ir_position` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_ir_contact_no` int(11) DEFAULT NULL,
  `source_sales_executive` bigint(20) DEFAULT NULL,
  `Customer_dateofbirth` date DEFAULT NULL,
  `customer_wife_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_wife_dataofbirth` date DEFAULT NULL,
  `Marriage_anniversary` date DEFAULT NULL,
  `children_name1` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `children_dateofbirth1` date DEFAULT NULL,
  `children_name2` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `children_dateofbirth2` date DEFAULT NULL,
  `children_name3` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `children_dateofbirth3` date DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`lead_pk_no`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `t_leads` */

insert  into `t_leads`(`lead_pk_no`,`lead_id`,`customer_firstname`,`customer_lastname`,`phone1_code`,`phone1`,`phone2_code`,`phone2`,`email_id`,`occupation_pk_no`,`organization_pk_no`,`project_category_pk_no`,`project_area_pk_no`,`Project_pk_no`,`project_size_pk_no`,`source_auto_pk_no`,`source_auto_usergroup_pk_no`,`source_sac_name`,`source_sac_note`,`source_digital_marketing`,`source_hotline`,`source_internal_reference`,`source_ir_emp_id`,`source_ir_name`,`source_ir_position`,`source_ir_contact_no`,`source_sales_executive`,`Customer_dateofbirth`,`customer_wife_name`,`customer_wife_dataofbirth`,`Marriage_anniversary`,`children_name1`,`children_dateofbirth1`,`children_name2`,`children_dateofbirth2`,`children_name3`,`children_dateofbirth3`,`c_pk_no_created`,`created_by`,`created_at`,`updated_by`,`updated_at`,`c_pk_no_updated`) values 
(54,'L2020154','Siddiqur','Rahman','880','1598698974','880','1986589685','siddiq@gmail.com',3,1,23,32,44,63,23,23,'','','','','',0,'','',0,1,'0000-01-01','','0000-01-01','0000-01-01','','0000-01-01','','0000-01-01','','0000-01-01',1,8,'2020-01-13',NULL,NULL,NULL);

/*Table structure for table `t_leadtransfer` */

DROP TABLE IF EXISTS `t_leadtransfer`;

CREATE TABLE `t_leadtransfer` (
  `transfer_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `lead_transfer_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transfer_datetime` date DEFAULT NULL,
  `lead_pk_no` bigint(20) DEFAULT NULL,
  `transfer_from_sales_agent_pk_no` bigint(20) DEFAULT NULL,
  `transfer_to_sales_agent_pk_no` bigint(20) DEFAULT NULL,
  `transfer_to_sales_agent_flag` int(11) DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`transfer_pk_no`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `t_leadtransfer` */

insert  into `t_leadtransfer`(`transfer_pk_no`,`lead_transfer_id`,`transfer_datetime`,`lead_pk_no`,`transfer_from_sales_agent_pk_no`,`transfer_to_sales_agent_pk_no`,`transfer_to_sales_agent_flag`,`c_pk_no_created`,`created_by`,`created_at`,`updated_by`,`updated_at`,`c_pk_no_updated`) values 
(1,'1','2020-01-05',1,1,6,0,1,1,'2020-01-05',NULL,NULL,NULL),
(2,'1','2020-01-13',54,1,17,0,1,1,'2020-01-13',NULL,NULL,NULL);

/*Table structure for table `t_teambuild` */

DROP TABLE IF EXISTS `t_teambuild`;

CREATE TABLE `t_teambuild` (
  `teammem_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `teammem_id` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `team_lookup_pk_no` int(11) NOT NULL,
  `user_pk_no` bigint(20) NOT NULL,
  `category_lookup_pk_no` int(11) DEFAULT '0',
  `area_lookup_pk_no` int(11) DEFAULT '0',
  `hod_flag` int(11) DEFAULT '0',
  `hot_flag` int(11) DEFAULT '0',
  `team_lead_flag` int(11) DEFAULT '0',
  `hod_user_pk_no` int(11) DEFAULT '0',
  `hot_user_pk_no` int(11) DEFAULT '0',
  `team_lead_user_pk_no` int(11) DEFAULT '0',
  `agent_type` int(11) DEFAULT '0',
  `row_status` int(11) DEFAULT '0',
  `created_by` int(11) DEFAULT '0',
  `created_at` date DEFAULT NULL,
  `updated_by` int(11) DEFAULT '0',
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`teammem_pk_no`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `t_teambuild` */

insert  into `t_teambuild`(`teammem_pk_no`,`teammem_id`,`team_lookup_pk_no`,`user_pk_no`,`category_lookup_pk_no`,`area_lookup_pk_no`,`hod_flag`,`hot_flag`,`team_lead_flag`,`hod_user_pk_no`,`hot_user_pk_no`,`team_lead_user_pk_no`,`agent_type`,`row_status`,`created_by`,`created_at`,`updated_by`,`updated_at`) values 
(1,'1',100,1,0,0,0,0,0,0,2,3,1,1,1,'2020-01-12',0,'2020-01-12'),
(2,'1',100,2,0,0,0,1,0,0,2,3,1,1,1,'2020-01-12',0,'2020-01-12'),
(3,'1',100,3,0,0,0,0,1,0,2,3,1,1,1,'2020-01-12',0,'2020-01-12'),
(4,'1',100,4,0,0,0,0,0,0,2,3,1,1,1,'2020-01-12',0,'2020-01-12'),
(5,'1',99,5,23,30,0,0,1,7,6,5,1,1,15,'2020-01-13',0,NULL),
(6,'1',99,6,23,30,0,1,0,7,6,5,1,1,15,'2020-01-13',0,NULL),
(7,'1',99,7,23,30,1,0,0,7,6,5,1,1,15,'2020-01-13',0,NULL),
(8,'1',99,8,23,30,0,0,0,7,6,5,1,1,15,'2020-01-13',0,NULL),
(9,'1',103,16,23,30,0,0,0,0,0,0,2,1,15,'2020-01-13',0,NULL),
(10,'1',103,17,23,30,0,0,0,0,0,0,2,1,15,'2020-01-13',0,NULL);

/*Table structure for table `t_teamtarget` */

DROP TABLE IF EXISTS `t_teamtarget`;

CREATE TABLE `t_teamtarget` (
  `target_pk_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `teammem_pk_no` int(11) DEFAULT NULL,
  `lead_pk_no` bigint(20) DEFAULT NULL,
  `user_pk_no` bigint(20) DEFAULT NULL,
  `target_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_lookup_pk_no` bigint(20) DEFAULT NULL,
  `area_lookup_pk_no` bigint(20) DEFAULT NULL,
  `yy_mm` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `target_amount` int(11) DEFAULT NULL,
  `target_by_lead_qty` int(11) DEFAULT NULL,
  `c_pk_no_created` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `c_pk_no_updated` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`target_pk_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `t_teamtarget` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` int(11) DEFAULT '0',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `ic_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `is_super_admin` int(11) DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(2) DEFAULT '1' COMMENT '1=active, 0=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`role`,`user_type`,`email`,`phone`,`address`,`ic_number`,`email_verified_at`,`is_super_admin`,`password`,`remember_token`,`status`,`created_at`,`updated_at`,`deleted_at`) values 
(16,'Jahid Hasan','74',2,'jahid0209@gmail.com','01676089093','Dhaka',NULL,NULL,NULL,'$2y$10$hfLdfnQgrHQOJQSKRcJPCeWXnXtuuPxxzbY2Xlyk4l8g3uyc9wrOC',NULL,1,'2019-12-07 10:27:29','2020-01-12 16:04:21',NULL),
(17,'Shumon Khan','49',0,'shumonkhan@gmail.com','01547896589','Uttara',NULL,NULL,NULL,'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',NULL,1,'2019-12-07 10:28:11','2019-12-07 10:28:11',NULL),
(18,'Zaman','76',1,'zaman1234@gmail.com','01745784521','Uttara',NULL,NULL,NULL,'$2y$10$1dNq4dwEG7ECoDQxcAcud.Y6pwrGmsmmDgy/0JG9bv/K.9ob1KnGC',NULL,1,'2019-12-28 15:14:44','2020-01-11 11:56:34',NULL),
(19,'Meraj Ahmed','74',0,'meraj.dm@btibd.org','01673896446','Dhaka, Bangladesh',NULL,NULL,NULL,'$2y$10$zcFwQxGMvQcXCfmFXmujYuW2Yet1qebuCbfszLjGb0o1DAnLTN5DC',NULL,1,'2020-01-02 15:10:16','2020-01-04 18:05:45',NULL),
(20,'Shila Ahamed','73',1,'shila123@gmail.com','01745465115','Banasree, Dhaka',NULL,NULL,NULL,'$2y$10$z6P8iAPk1LoAB5ulm3Dsk.ZYX10SEUHJ/DSkfFZS9lPfNpWFEyeUC',NULL,1,'2020-01-04 13:06:22','2020-01-07 11:42:05',NULL),
(21,'Md. Mahfuz','73',2,'mahfuz.rt@xibd.org','01524546844','Badda, Dhaka',NULL,NULL,NULL,'$2y$10$M0y4cwqJ1g/Q3dVTRYkbne4.ocwJXlylCRV.4tgsb3uKQErm8aKPm',NULL,1,'2020-01-04 13:07:48','2020-01-04 16:51:31',NULL),
(22,'Mrs. Jamila','74',1,'jamila@gmail.com','01234565455','Dhanmondi',NULL,NULL,NULL,'$2y$10$l16XCsG0Tu1u7WLOM7eNfOVZEAziJFlXeOSn3cUa6K8k6sYcQhEHe',NULL,1,'2020-01-04 13:11:36','2020-01-04 16:43:00',NULL),
(23,'Mrs. Kamila','75',1,'kamila34@btibd.org','01234567857','Mirpur, Dhaka',NULL,NULL,NULL,'$2y$10$PxzLUkV3XW6jcg.zD9fya.CjTOmkcMQsBsp2xLJB8Ksmm0MRrxhpa',NULL,1,'2020-01-04 13:24:42','2020-01-04 16:48:22',NULL),
(24,'Admin','1',0,'admin@app.com',NULL,NULL,NULL,NULL,1,'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',NULL,1,'2020-01-04 13:24:42',NULL,NULL),
(25,'',NULL,0,'',NULL,NULL,NULL,NULL,NULL,'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',NULL,1,NULL,NULL,NULL),
(26,'Raha','73',0,'raha.rt@gmail.com','01234564471','Mirpur, Dhaka',NULL,NULL,NULL,'$2y$10$qlIZY/qVG969H81wo12og.JBPs25ZWGrwab7z4n3pFhCeU0tVfdCC',NULL,1,'2020-01-04 16:52:41','2020-01-05 10:55:47',NULL),
(27,'maidul','73',0,'maidul23@bti.com','012123215421','Mohakhali, Dhaka',NULL,NULL,NULL,'$2y$10$PkUAsIEHOWSRKrJAxe4Z0uJj6aaDZ5UhwuDEKmacKvqRTUW78V45G',NULL,1,'2020-01-05 11:20:26','2020-01-05 11:20:26',NULL),
(28,'Shuvro','73',0,'shuvro@cm.com','01234556788','Mohammadpur, Dhaka',NULL,NULL,NULL,'$2y$10$qt66LQOiyhetk40V9QtOeO/H9nwChkFRl4u9dpnHRXPhHe/.CvhoW',NULL,1,'2020-01-05 11:21:59','2020-01-05 11:21:59',NULL),
(29,'Jisan','73',1,'jisan@c.com','0123456789','Badda, Dhaka',NULL,NULL,NULL,'$2y$10$cXZtDU6Zz1PrxHg9TE2DUuMT7WVqskId7fqjf6pJSnsGcDMSfWJ2W',NULL,1,'2020-01-05 11:23:26','2020-01-12 13:29:46',NULL),
(30,'Rakib','73',0,'rakib@gmail.com','0123456878','Banani, Dhaka',NULL,NULL,NULL,'$2y$10$nbZDgkLziwB6Sr9jtdP5K.r/oB3CLLq1uKOwg61L2y7CUIdRUMXcu',NULL,1,'2020-01-05 11:24:46','2020-01-05 11:24:46',NULL),
(31,'Emon Khan','76',0,'emon@gmail.com','01989389586','Dhaka',NULL,NULL,NULL,'$2y$10$IfwLlcp91YXHGpIQOwfcquVBf34BYn5FGTppxD4VtyUQdR5mYLb3W',NULL,1,'2020-01-05 14:58:22','2020-01-05 14:58:22',NULL),
(32,'Sohanul Islam Khan','77',2,'sohan@gmail.com','01689598659',NULL,NULL,NULL,NULL,'$2y$10$XAKAoF5aG5DHoKSZFX8Xsui.zxRjMCFiNkzenM3gMKocDL2rRwdGu',NULL,1,'2020-01-12 13:27:24','2020-01-12 13:29:07',NULL),
(33,'test user','77',2,'test@gmail.com','01989695869',NULL,NULL,NULL,NULL,'$2y$10$QRNaHGuTct2gLhubDC511uQARqOw0raIPLcBOcAzROUbzv.ZJgHZe',NULL,1,'2020-01-12 17:19:32','2020-01-12 17:19:32',NULL);

/* Function  structure for function  `fnc_checkdataprivs` */

/*!50003 DROP FUNCTION IF EXISTS `fnc_checkdataprivs` */;
DELIMITER $$

/*!50003 CREATE FUNCTION `fnc_checkdataprivs`(in_user_pk_no BIGINT, in_lead_sales_agent_pk_no BIGINT) RETURNS int(11)
BEGIN
    
    DECLARE l_team_lookup_pk_no BIGINT;
    DECLARE l_cnt INT;
    
	IF in_user_pk_no = in_lead_sales_agent_pk_no THEN 
		RETURN 1;
	ELSE		
		SELECT team_lookup_pk_no INTO l_team_lookup_pk_no
		FROM t_teambuild
		WHERE user_pk_no = in_user_pk_no AND (COALESCE(coleasehod_flag,0) = 1 OR COALESCE(hot_flag,0) =1 OR COALESCE(team_lead_flag,0) =1);
		
		SELECT COUNT(1) INTO l_cnt
		FROM t_teambuild
		WHERE user_pk_no = in_lead_sales_agent_pk_no AND team_lookup_pk_no = l_team_lookup_pk_no;
		
		IF COALESCE(l_cnt,0) >0 THEN
			RETURN 2;
		ELSE
			RETURN 0;	
		END IF; 
	END IF;
		
	RETURN 0;
		
    END */$$
DELIMITER ;

/* Procedure structure for procedure `proc_getsalesagentauto` */

/*!50003 DROP PROCEDURE IF EXISTS  `proc_getsalesagentauto` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `proc_getsalesagentauto`( in in_category_pk_no bigint, in in_area_pk_no bigint)
BEGIN
	
	declare l_lead_sales_agent_pk_no bigint;
	
	-- select lead_sales_agent_pk_no, project_category_pk_no, project_area_pk_no, lead_count from (
	
	SELECT lead_sales_agent_pk_no INTO l_lead_sales_agent_pk_no FROM (	
	SELECT @cnt = @cnt+1 cnt, lead_sales_agent_pk_no, project_category_pk_no, project_area_pk_no, COUNT(lead_sales_agent_pk_no) lead_count 
	FROM t_leads ld JOIN t_leadlifecycle lc ON (ld.lead_pk_no = lc.lead_pk_no)
	WHERE lead_hold_flag <> 1 AND lead_closed_flag <> 1 AND lead_sold_flag <> 1
	GROUP BY lead_sales_agent_pk_no, project_category_pk_no, project_area_pk_no
	ORDER BY lead_count ASC) m
	WHERE m.project_category_pk_no = in_category_pk_no AND m.project_area_pk_no = in_area_pk_no AND cnt =1;
	
	
	select l_lead_sales_agent_pk_no;
	
	
    END */$$
DELIMITER ;

/* Procedure structure for procedure `proc_leadfollowup_ins` */

/*!50003 DROP PROCEDURE IF EXISTS  `proc_leadfollowup_ins` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `proc_leadfollowup_ins`(
 IN in_lead_followup_id varchar (30),
 IN in_lead_followup_datetime date,
 IN in_lead_pk_no bigint,
 IN in_Followup_type_pk_no bigint,
 IN in_followup_Note varchar (200),
 IN in_lead_stage_before_followup int,
 IN in_next_followup_flag int,
 IN in_Next_FollowUp_date date,
 IN in_next_followup_Prefered_Time datetime,
 IN in_next_followup_Note VARCHAR (200),
 IN in_lead_stage_after_followup int,
 IN in_c_pk_no_created bigint,
 IN in_created_by bigint,
IN in_created_at date
)
BEGIN 
 INSERT INTO t_leadfollowup (
lead_followup_id, 
lead_followup_datetime, 
lead_pk_no, 
Followup_type_pk_no, 
followup_Note, 
lead_stage_before_followup, 
next_followup_flag, 
Next_FollowUp_date, 
next_followup_Prefered_Time, 
next_followup_Note, 
lead_stage_after_followup, 
c_pk_no_created, 
created_by, 
created_at
) values ( 
 in_lead_followup_id, 
 in_lead_followup_datetime, 
 in_lead_pk_no, 
 in_Followup_type_pk_no, 
 in_followup_Note, 
 in_lead_stage_before_followup, 
 in_next_followup_flag, 
 in_Next_FollowUp_date, 
 in_next_followup_Prefered_Time, 
 in_next_followup_Note, 
 in_lead_stage_after_followup, 
 in_c_pk_no_created, 
 in_created_by, 
in_created_at
) ;
commit;
END */$$
DELIMITER ;

/* Procedure structure for procedure `proc_leadlifecycle_ins` */

/*!50003 DROP PROCEDURE IF EXISTS  `proc_leadlifecycle_ins` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `proc_leadlifecycle_ins`(
 IN in_leadlifecycle_id VARCHAR (30),
 IN in_lead_pk_no BIGINT,
 IN in_lead_sales_agent_pk_no BIGINT,
 IN in_lead_current_stage INT,
 IN in_lead_qc_flag INT,
 IN in_lead_qc_datetime DATE,
 IN in_lead_qc_by BIGINT,

 IN in_lead_k1_flag int,
 IN in_lead_k1_datetime date,
 IN in_lead_k1_by bigint,
 /* IN in_lead_priority_flag int,
 IN in_lead_priority_datetime date,
 IN in_lead_priority_by bigint,
 IN in_lead_hold_flag int,
 IN in_lead_hold_datetime date,
 IN in_lead_hold_by bigint,
 IN in_lead_closed_flag int,
 IN in_lead_closed_datetime date,
 IN in_lead_closed_by bigint,
 IN in_lead_sold_flag int,
 IN in_lead_sold_datetime date,
 IN in_lead_sold_by bigint,
 IN in_lead_sold_date_manual date,
 IN in_lead_sold_flatcost float,
 IN in_lead_sold_utilitycost float,
 IN in_lead_sold_parkingcost float,
 IN in_lead_sold_customer_pk_no bigint,
 IN in_lead_sold_sales_agent_pk_no bigint,
 IN in_lead_sold_team_lead_pk_no bigint,
 IN in_lead_sold_team_manager_pk_no bigint,
 IN in_lead_transfer_flag int,
 IN in_lead_transfer_from_sales_agent_pk_no bigint,
 */
 IN in_c_pk_no_created BIGINT,
 IN in_created_by BIGINT,
IN in_created_at DATE
)
BEGIN 
 INSERT INTO t_leadlifecycle (
leadlifecycle_id, 
lead_pk_no, 
lead_sales_agent_pk_no, 
lead_current_stage, 
lead_qc_flag, 
lead_qc_datetime, 
lead_qc_by, 

lead_k1_flag, 
lead_k1_datetime, 
lead_k1_by, 
/*lead_priority_flag, 
lead_priority_datetime, 
lead_priority_by, 
lead_hold_flag, 
lead_hold_datetime, 
lead_hold_by, 
lead_closed_flag, 
lead_closed_datetime, 
lead_closed_by, 
lead_sold_flag, 
lead_sold_datetime, 
lead_sold_by, 
lead_sold_date_manual, 
lead_sold_flatcost, 
lead_sold_utilitycost, 
lead_sold_parkingcost, 
lead_sold_customer_pk_no, 
lead_sold_sales_agent_pk_no, 
lead_sold_team_lead_pk_no, 
lead_sold_team_manager_pk_no, 
lead_transfer_flag, 
lead_transfer_from_sales_agent_pk_no, 
*/
c_pk_no_created, 
created_by, 
created_at
) VALUES ( 
 in_leadlifecycle_id, 
 in_lead_pk_no, 
 in_lead_sales_agent_pk_no, 
 in_lead_current_stage, 
in_lead_qc_flag, 
 in_lead_qc_datetime, 
 in_lead_qc_by, 
 
 in_lead_k1_flag, 
 in_lead_k1_datetime, 
 in_lead_k1_by, 
  /*in_lead_priority_flag, 
 in_lead_priority_datetime, 
 in_lead_priority_by, 
 in_lead_hold_flag, 
 in_lead_hold_datetime, 
 in_lead_hold_by, 
 in_lead_closed_flag, 
 in_lead_closed_datetime, 
 in_lead_closed_by, 
 in_lead_sold_flag, 
 in_lead_sold_datetime, 
 in_lead_sold_by, 
 in_lead_sold_date_manual, 
 in_lead_sold_flatcost, 
 in_lead_sold_utilitycost, 
 in_lead_sold_parkingcost, 
 in_lead_sold_customer_pk_no, 
 in_lead_sold_sales_agent_pk_no, 
 in_lead_sold_team_lead_pk_no, 
 in_lead_sold_team_manager_pk_no, 
 in_lead_transfer_flag, 
 in_lead_transfer_from_sales_agent_pk_no, 
 */
 in_c_pk_no_created, 
 in_created_by, 
in_created_at
) ;
COMMIT;
END */$$
DELIMITER ;

/* Procedure structure for procedure `proc_leadlifecycle_upd_stage` */

/*!50003 DROP PROCEDURE IF EXISTS  `proc_leadlifecycle_upd_stage` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `proc_leadlifecycle_upd_stage`(
 IN in_lead_pk_no BIGINT,
 IN in_datetime DATE,
 IN in_by BIGINT,
 in in_tostage int,
 IN in_c_pk_no_created BIGINT
)
BEGIN 

if in_tostage = 2 then
	update t_leadlifecycle 
	set  
	lead_qc_flag = in_tostage, 
	lead_qc_datetime = in_datetime, 
	lead_qc_by = in_by,
	lead_current_stage = in_tostage
	where lead_pk_no = in_lead_pk_no;

elseif in_tostage = 3 THEN
	UPDATE t_leadlifecycle 
	SET  
	lead_k1_flag = in_tostage, 
	lead_k1_datetime = in_datetime, 
	lead_k1_by = in_by,
	lead_current_stage = in_tostage
	WHERE lead_pk_no = in_lead_pk_no;
ELSEIF in_tostage = 4 THEN
	UPDATE t_leadlifecycle 
	SET  
	lead_priority_flag = in_tostage, 
	lead_priority_datetime = in_datetime, 
	lead_priority_by = in_by,
	lead_current_stage = in_tostage
	WHERE lead_pk_no = in_lead_pk_no;	
ELSEIF in_tostage = 5 THEN
	UPDATE t_leadlifecycle 
	SET  
	lead_hold_flag = in_tostage, 
	lead_hold_datetime = in_datetime, 
	lead_hold_by = in_by,
	lead_current_stage = in_tostage
	WHERE lead_pk_no = in_lead_pk_no;
ELSEIF in_tostage = 6 THEN
	UPDATE t_leadlifecycle 
	SET  
	lead_closed_flag = in_tostage, 
	lead_closed_datetime = in_datetime, 
	lead_closed_by = in_by,
	lead_current_stage = in_tostage
	WHERE lead_pk_no = in_lead_pk_no;
ELSEIF in_tostage = 7 THEN
	UPDATE t_leadlifecycle 
	SET  
	lead_sold_flag = in_tostage, 
	lead_sold_datetime = in_datetime, 
	lead_sold_by = in_by,
	lead_current_stage = in_tostage
	WHERE lead_pk_no = in_lead_pk_no;	
ELSE
	UPDATE t_leadlifecycle 
	SET lead_current_stage = in_tostage
	WHERE lead_pk_no = in_lead_pk_no;		
end if;

COMMIT;
END */$$
DELIMITER ;

/* Procedure structure for procedure `proc_leadtransfer_ins` */

/*!50003 DROP PROCEDURE IF EXISTS  `proc_leadtransfer_ins` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `proc_leadtransfer_ins`(
 IN in_lead_transfer_id varchar (30),
 IN in_transfer_datetime date,
 IN in_lead_pk_no bigint,
 IN in_transfer_from_sales_agent_pk_no bigint,
 IN in_transfer_to_sales_agent_pk_no bigint,
 IN in_transfer_to_sales_agent_flag int,
 IN in_c_pk_no_created bigint,
 IN in_created_by bigint,
IN in_created_at date
)
BEGIN 
 INSERT INTO t_leadtransfer (
lead_transfer_id, 
transfer_datetime, 
lead_pk_no, 
transfer_from_sales_agent_pk_no, 
transfer_to_sales_agent_pk_no, 
transfer_to_sales_agent_flag, 
c_pk_no_created, 
created_by, 
created_at
) values ( 
 in_lead_transfer_id, 
 in_transfer_datetime, 
 in_lead_pk_no, 
 in_transfer_from_sales_agent_pk_no, 
 in_transfer_to_sales_agent_pk_no, 
 in_transfer_to_sales_agent_flag, 
 in_c_pk_no_created, 
 in_created_by, 
in_created_at
) ;
commit;
END */$$
DELIMITER ;

/* Procedure structure for procedure `proc_lookdata_ins` */

/*!50003 DROP PROCEDURE IF EXISTS  `proc_lookdata_ins` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `proc_lookdata_ins`(
 IN in_lookup_type varchar (30),
 IN in_lookup_id varchar (30),
 IN in_lookup_name varchar (200),
 IN in_lookup_row_status int,
 IN in_c_pk_no_created bigint,
 IN in_created_by bigint,
IN in_created_at date
)
BEGIN 
 INSERT INTO s_lookdata (
lookup_type, 
lookup_id, 
lookup_name, 
lookup_row_status, 
c_pk_no_created, 
created_by, 
created_at
) values ( 
 in_lookup_type, 
 in_lookup_id, 
 in_lookup_name, 
 in_lookup_row_status, 
 in_c_pk_no_created, 
 in_created_by, 
in_created_at
) ;
commit;
END */$$
DELIMITER ;

/* Procedure structure for procedure `proc_leads_ins` */

/*!50003 DROP PROCEDURE IF EXISTS  `proc_leads_ins` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `proc_leads_ins`(
 IN in_lead_id varchar (30),
 IN in_customer_firstname varchar (200),
 IN in_customer_lastname varchar (200),
 IN in_phone1_code varchar (10),
 IN in_phone1 VARCHAR (200),
 IN in_phone2_code varchar (10),
 IN in_phone2 VARCHAR (200),
 IN in_email_id varchar (200),
 IN in_occupation_pk_no bigint,
 IN in_organization_pk_no bigint,
 IN in_project_category_pk_no bigint,
 IN in_project_area_pk_no bigint,
 IN in_Project_pk_no bigint,
 IN in_project_size_pk_no bigint,
 IN in_source_auto_pk_no bigint,
 IN in_source_auto_usergroup_pk_no bigint,
 IN in_source_sac_name varchar (200),
 IN in_source_sac_note varchar (200),
 IN in_source_digital_marketing varchar (30),
 IN in_source_hotline varchar (30),
 IN in_source_internal_reference varchar (30),
 IN in_source_ir_emp_id VARCHAR (100),
 IN in_source_ir_emp_name VARCHAR (200),
 IN in_source_ir_position VARCHAR (200),
 IN in_source_ir_contact_no INT (11),
 IN in_source_sales_executive bigint,
 IN in_Customer_dateofbirth date,
 IN in_customer_wife_name varchar (200),
 IN in_customer_wife_dataofbirth date,
 IN in_Marriage_anniversary date,
 IN in_children_name1 varchar (200),
 IN in_children_dateofbirth1 date,
 IN in_children_name2 varchar (200),
 IN in_children_dateofbirth2 date,
 IN in_children_name3 varchar (200),
 IN in_children_dateofbirth3 date,
 IN in_c_pk_no_created bigint,
 IN in_created_by bigint,
IN in_created_at date
)
BEGIN 
DECLARE l_lead_pk_no BIGINT;
DECLARE l_lead_id VARCHAR(30);
declare l_mm, l_yy int;
 INSERT INTO t_leads (
lead_id, 
customer_firstname, 
customer_lastname, 
phone1_code, 
phone1, 
phone2_code, 
phone2, 
email_id, 
occupation_pk_no, 
organization_pk_no, 
project_category_pk_no, 
project_area_pk_no, 
Project_pk_no, 
project_size_pk_no, 
source_auto_pk_no, 
source_auto_usergroup_pk_no, 
source_sac_name, 
source_sac_note, 
source_digital_marketing, 
source_hotline, 
source_internal_reference, 
source_ir_emp_id,
source_ir_name,
source_ir_position,
source_ir_contact_no,
source_sales_executive, 
Customer_dateofbirth, 
customer_wife_name, 
customer_wife_dataofbirth, 
Marriage_anniversary, 
children_name1, 
children_dateofbirth1, 
children_name2, 
children_dateofbirth2, 
children_name3, 
children_dateofbirth3, 
c_pk_no_created, 
created_by, 
created_at
) values ( 
 in_lead_id, 
 in_customer_firstname, 
 in_customer_lastname, 
 in_phone1_code, 
 in_phone1, 
 in_phone2_code, 
 in_phone2, 
 in_email_id, 
 in_occupation_pk_no, 
 in_organization_pk_no, 
 in_project_category_pk_no, 
 in_project_area_pk_no, 
 in_Project_pk_no, 
 in_project_size_pk_no, 
 in_source_auto_pk_no, 
 in_source_auto_usergroup_pk_no, 
 in_source_sac_name, 
 in_source_sac_note, 
 in_source_digital_marketing, 
 in_source_hotline, 
 in_source_internal_reference, 
 in_source_ir_emp_id,
 in_source_ir_emp_name,
 in_source_ir_position,
 in_source_ir_contact_no,
 in_source_sales_executive, 
 in_Customer_dateofbirth, 
 in_customer_wife_name, 
 in_customer_wife_dataofbirth, 
 in_Marriage_anniversary, 
 in_children_name1, 
 in_children_dateofbirth1, 
 in_children_name2, 
 in_children_dateofbirth2, 
 in_children_name3, 
 in_children_dateofbirth3, 
 in_c_pk_no_created, 
 in_created_by, 
in_created_at
) ;
 set l_lead_pk_no = LAST_INSERT_ID();
 SET l_lead_id = concat('L',l_lead_pk_no);
SELECT MONTH(CURTIME()), YEAR(CURTIME()) into l_mm, l_yy;
select CONCAT('L',l_yy,l_mm,l_lead_pk_no) into l_lead_id;
update t_leads 
set lead_id = l_lead_id
where lead_pk_no = l_lead_pk_no;
select l_lead_pk_no,l_lead_id;
commit;
END */$$
DELIMITER ;

/* Procedure structure for procedure `proc_projectwiseflatlist_ins` */

/*!50003 DROP PROCEDURE IF EXISTS  `proc_projectwiseflatlist_ins` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `proc_projectwiseflatlist_ins`(
 IN in_project_lookup_pk_no bigint,
 IN in_flat_id varchar (30),
 IN in_flat_name varchar (30),
 IN in_category_lookup_pk_no bigint,
 IN in_size_lookup_pk_no bigint,
 IN in_flat_description varchar (30),
 IN in_flat_status int,
 IN in_c_pk_no_created bigint,
 IN in_created_by bigint,
IN in_created_at date
)
BEGIN 
 INSERT INTO s_projectwiseflatlist (
project_lookup_pk_no, 
flat_id, 
flat_name, 
category_lookup_pk_no, 
size_lookup_pk_no, 
flat_description, 
flat_status, 
c_pk_no_created, 
created_by, 
created_at
) values ( 
 in_project_lookup_pk_no, 
 in_flat_id, 
 in_flat_name, 
 in_category_lookup_pk_no, 
 in_size_lookup_pk_no, 
 in_flat_description, 
 in_flat_status, 
 in_c_pk_no_created, 
 in_created_by, 
in_created_at
) ;
commit;
END */$$
DELIMITER ;

/* Procedure structure for procedure `run_sys_procgenerate` */

/*!50003 DROP PROCEDURE IF EXISTS  `run_sys_procgenerate` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `run_sys_procgenerate`()
begin

CALL sys_procgenerate('t_leads','proc_leads_ins');
CALL sys_procgenerate('t_leadlifecycle','proc_leadlifecycle_ins	');
CALL sys_procgenerate('t_leadfollowup','proc_leadfollowup_ins');
CALL sys_procgenerate('t_leadtransfer','proc_leadtransfer_ins');
CALL sys_procgenerate('s_lookdata','proc_lookdata_ins');
CALL sys_procgenerate('s_projectwiseflatlist','proc_projectwiseflatlist_ins');
CALL sys_procgenerate('s_user','proc_user_inst');


END */$$
DELIMITER ;

/* Procedure structure for procedure `sys_procgenerate` */

/*!50003 DROP PROCEDURE IF EXISTS  `sys_procgenerate` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `sys_procgenerate`( IN in_tabname VARCHAR(30), IN in_procname VARCHAR(30))
BEGIN

DECLARE finished INTEGER DEFAULT 0;
DECLARE vcols VARCHAR(500);   

DECLARE out_txtend VARCHAR(500);
DECLARE out_txtst VARCHAR(500);
DECLARE vcolsins VARCHAR(500);
DECLARE instxt, instxtins VARCHAR(500);
DECLARE valtxt VARCHAR(500);
DECLARE cnt INT; 
DECLARE l_id BIGINT;

    DECLARE curColumns 
        CURSOR FOR 
		SELECT CONCAT(t1.column_def, t1.datalen) cols FROM (
		SELECT CONCAT(' IN in_' , column_name ,  ' ' , data_type ) AS column_def, 
		CASE WHEN data_type ='varchar' THEN CONCAT(' (',character_maximum_length,'),') ELSE ',' END AS datalen, ordinal_position AS pos
		FROM information_schema.`COLUMNS` WHERE table_name COLLATE utf8_general_ci = in_tabname COLLATE utf8_general_ci
		AND column_default IS NULL ) t1 WHERE pos > 1 ORDER BY pos ASC;
 
 
     DECLARE curColumnsIns 
        CURSOR FOR 
		SELECT CONCAT(column_name ,  ', ' ) AS column_ins
			, CONCAT(' in_' , column_name ,  ', ' ) column_val
			FROM information_schema.`COLUMNS` WHERE table_name COLLATE utf8_general_ci = in_tabname COLLATE utf8_general_ci 
			AND column_default IS NULL AND ordinal_position >1 ORDER BY ordinal_position ASC;
 
    -- declare NOT FOUND handler
    DECLARE CONTINUE HANDLER 
        FOR NOT FOUND SET finished = 1;
        
 DELETE FROM proc_code WHERE tname= in_tabname;
 COMMIT;
 
SET out_txtst = 'DELIMITER $$';

INSERT INTO proc_code (script, tname, pname) VALUES (out_txtst,in_tabname,in_procname);

SELECT CONCAT('DROP PROCEDURE IF EXISTS ', in_procname, '$$') INTO out_txtst ;

INSERT INTO proc_code (script, tname, pname) VALUES (out_txtst,in_tabname,in_procname);
 
 
SELECT CONCAT('CREATE PROCEDURE ',  in_procname , ' (') INTO out_txtst;

INSERT INTO proc_code (script, tname, pname) VALUES (out_txtst,in_tabname,in_procname);

SET out_txtst = NULL;
SET cnt =0;
    OPEN curColumns;
 
    getCols: LOOP
        FETCH curColumns INTO vcols;
	
        IF vcols IS NOT NULL THEN INSERT INTO proc_code (script, tname, pname) VALUES (vcols,in_tabname,in_procname); END IF;
	
        SET vcols = NULL;
        
        IF finished = 1 THEN 
            LEAVE getCols;		
        END IF;
        
    END LOOP getCols;
    CLOSE curColumns;


SET out_txtst = NULL;

SET out_txtst = ')  BEGIN ';

INSERT INTO proc_code (script, tname, pname) VALUES (out_txtst,in_tabname,in_procname);

SELECT CONCAT(' INSERT INTO ', in_tabname,' (') INTO out_txtst;

INSERT INTO proc_code (script, tname, pname) VALUES (out_txtst,in_tabname,in_procname);


SET finished = 0;
        


    OPEN curColumnsIns;
 
    getColsins: LOOP
        FETCH curColumnsIns INTO vcols, vcolsins;
        
        SELECT CONCAT(instxt,vcols) INTO instxt;
        SELECT CONCAT(instxtins,vcolsins) INTO instxtins;
        
        -- INSERT INTO proc_code (script, tname, pname) VALUES (vcols,in_tabname,in_procname);
	-- INSERT INTO proc_code (script, tname, pname) VALUES (vcolsins,in_tabname,in_procname);
	
	IF vcols IS NOT NULL THEN INSERT INTO proc_code (script, tname, pname) VALUES (vcols,in_tabname,in_procname); END IF;
	SET  vcols  = NULL;
	
        IF finished = 1 THEN 
            LEAVE getColsins;
        END IF;
        
        -- build email list
        -- SET emailList = CONCAT(emailAddress,";",emailList);
    END LOOP getColsins;
    CLOSE curColumnsIns;
    
    SET vcolsins = ') values ( ';
	INSERT INTO proc_code (script, tname, pname) VALUES (vcolsins,in_tabname,in_procname);
    
    
SET finished = 0; 

    OPEN curColumnsIns;
 
    getColsins: LOOP
        FETCH curColumnsIns INTO vcols, vcolsins;
        
        SELECT CONCAT(instxt,vcols) INTO instxt;
        SELECT CONCAT(instxtins,vcolsins) INTO instxtins;
        
        -- INSERT INTO proc_code (script, tname, pname) VALUES (vcols,in_tabname,in_procname);
	-- INSERT INTO proc_code (script, tname, pname) VALUES (vcolsins,in_tabname,in_procname);
	
	IF vcolsins IS NOT NULL THEN INSERT INTO proc_code (script, tname, pname) VALUES (vcolsins,in_tabname,in_procname); END IF;
	SET  vcolsins  = NULL;
	
        IF finished = 1 THEN 
            LEAVE getColsins;
        END IF;
        
        -- build email list
        -- SET emailList = CONCAT(emailAddress,";",emailList);
    END LOOP getColsins;
    CLOSE curColumnsIns;

SET vcolsins = ') ;';
 
 
 	INSERT INTO proc_code (script, tname, pname) VALUES (vcolsins,in_tabname,in_procname);


SET vcolsins = 'commit;';
 	INSERT INTO proc_code (script, tname, pname) VALUES (vcolsins,in_tabname,in_procname);


SET vcolsins = 'END$$';
 	INSERT INTO proc_code (script, tname, pname) VALUES (vcolsins,in_tabname,in_procname);
SET vcolsins = 'DELIMITER ;';
 	INSERT INTO proc_code (script, tname, pname) VALUES (vcolsins,in_tabname,in_procname);
 	


DELETE FROM proc_code
WHERE tname = in_tabname AND script LIKE '%updated%';


SELECT MAX(id) INTO l_id FROM proc_code WHERE id < (SELECT id FROM proc_code 
WHERE tname = in_tabname AND  script LIKE '%BEGIN%');

UPDATE proc_code 
SET script = LEFT(TRIM(script),LENGTH(TRIM(script))-1) 
WHERE id = l_id;

SELECT l_id;

SELECT MAX(id) INTO l_id FROM proc_code WHERE id < (SELECT id FROM proc_code 
WHERE tname = in_tabname AND script LIKE '%values%');


UPDATE proc_code 
SET script = LEFT(TRIM(script),LENGTH(TRIM(script))-1) 
WHERE id = l_id;

SELECT l_id;


SELECT MAX(id) INTO l_id FROM proc_code WHERE id < (SELECT id FROM proc_code 
WHERE tname = in_tabname AND  script LIKE '%) ;%');

UPDATE proc_code 
SET script = LEFT(TRIM(script),LENGTH(TRIM(script))-1) 
WHERE id = l_id;


COMMIT;

/*
SELECT MAX(id) INTO l_id FROM proc_code WHERE id < (SELECT id FROM proc_code 
WHERE tname = in_tabname AND  script LIKE '%BEGIN%');
*/



	-- INSERT INTO proc_code (script, tname, pname) VALUES (vcolsins,in_tabname,in_procname);
	
 	-- INSERT INTO proc_code (script, tname, pname) VALUES (instxt,in_tabname,in_procname);
	-- INSERT INTO proc_code (script, tname, pname) VALUES (instxtins,in_tabname,in_procname);
	
COMMIT;
	
END */$$
DELIMITER ;

/*Table structure for table `kpi_acr` */

DROP TABLE IF EXISTS `kpi_acr`;

/*!50001 DROP VIEW IF EXISTS `kpi_acr` */;
/*!50001 DROP TABLE IF EXISTS `kpi_acr` */;

/*!50001 CREATE TABLE  `kpi_acr`(
 `user_pk_no` bigint(20) ,
 `team_lead_user_pk_no` int(11) ,
 `user_name` varchar(30) ,
 `k1_count` decimal(32,0) ,
 `priority_count` decimal(32,0) ,
 `sold_count` decimal(32,0) ,
 `k1_priority_ratio` decimal(36,4) ,
 `priority_sold_ratio` decimal(36,4) 
)*/;

/*Table structure for table `kpi_acr_count` */

DROP TABLE IF EXISTS `kpi_acr_count`;

/*!50001 DROP VIEW IF EXISTS `kpi_acr_count` */;
/*!50001 DROP TABLE IF EXISTS `kpi_acr_count` */;

/*!50001 CREATE TABLE  `kpi_acr_count`(
 `lead_sales_agent_pk_no` bigint(20) ,
 `k1_count` decimal(32,0) ,
 `priority_count` decimal(32,0) ,
 `sold_count` decimal(32,0) 
)*/;

/*Table structure for table `kpi_apt` */

DROP TABLE IF EXISTS `kpi_apt`;

/*!50001 DROP VIEW IF EXISTS `kpi_apt` */;
/*!50001 DROP TABLE IF EXISTS `kpi_apt` */;

/*!50001 CREATE TABLE  `kpi_apt`(
 `user_pk_no` bigint(20) ,
 `team_lead_user_pk_no` int(11) ,
 `user_name` varchar(30) ,
 `lead2k1` decimal(10,4) ,
 `k12priority` decimal(10,4) ,
 `priority2sold` decimal(10,4) ,
 `k12sold` decimal(10,4) 
)*/;

/*Table structure for table `kpi_apt_avgprocdays` */

DROP TABLE IF EXISTS `kpi_apt_avgprocdays`;

/*!50001 DROP VIEW IF EXISTS `kpi_apt_avgprocdays` */;
/*!50001 DROP TABLE IF EXISTS `kpi_apt_avgprocdays` */;

/*!50001 CREATE TABLE  `kpi_apt_avgprocdays`(
 `lead_sales_agent_pk_no` bigint(20) ,
 `lead2k1` decimal(10,4) ,
 `k12priority` decimal(10,4) ,
 `priority2sold` decimal(10,4) ,
 `k12sold` decimal(10,4) 
)*/;

/*Table structure for table `kpi_avt` */

DROP TABLE IF EXISTS `kpi_avt`;

/*!50001 DROP VIEW IF EXISTS `kpi_avt` */;
/*!50001 DROP TABLE IF EXISTS `kpi_avt` */;

/*!50001 CREATE TABLE  `kpi_avt`(
 `user_pk_no` bigint(20) ,
 `team_lead_user_pk_no` int(11) ,
 `user_name` varchar(30) ,
 `yy_mm` varchar(30) ,
 `target_amount` int(11) ,
 `target_by_lead_qty` int(11) ,
 `sold_yymm` varchar(4) ,
 `sold_amt` double 
)*/;

/*Table structure for table `kpi_soldamt_yymm` */

DROP TABLE IF EXISTS `kpi_soldamt_yymm`;

/*!50001 DROP VIEW IF EXISTS `kpi_soldamt_yymm` */;
/*!50001 DROP TABLE IF EXISTS `kpi_soldamt_yymm` */;

/*!50001 CREATE TABLE  `kpi_soldamt_yymm`(
 `lead_sales_agent_pk_no` bigint(20) ,
 `sold_yymm` varchar(4) ,
 `sold_amt` double 
)*/;

/*Table structure for table `t_lead2lifecycle_vw` */

DROP TABLE IF EXISTS `t_lead2lifecycle_vw`;

/*!50001 DROP VIEW IF EXISTS `t_lead2lifecycle_vw` */;
/*!50001 DROP TABLE IF EXISTS `t_lead2lifecycle_vw` */;

/*!50001 CREATE TABLE  `t_lead2lifecycle_vw`(
 `lead_pk_no` bigint(20) ,
 `lead_id` varchar(30) ,
 `customer_firstname` varchar(200) ,
 `customer_lastname` varchar(200) ,
 `phone1` varchar(200) ,
 `phone2` varchar(200) ,
 `email_id` varchar(200) ,
 `occupation_pk_no` bigint(20) ,
 `occup_name` varchar(200) ,
 `organization_pk_no` bigint(20) ,
 `org_name` varchar(200) ,
 `project_category_pk_no` bigint(20) ,
 `project_category_name` varchar(200) ,
 `project_area_pk_no` bigint(20) ,
 `project_area` varchar(200) ,
 `Project_pk_no` bigint(20) ,
 `project_name` varchar(200) ,
 `project_size_pk_no` bigint(20) ,
 `project_size` varchar(200) ,
 `flatlist_pk_no` bigint(11) ,
 `source_auto_pk_no` bigint(20) ,
 `user_full_name` varchar(200) ,
 `source_auto_usergroup_pk_no` bigint(20) ,
 `source_sac_name` varchar(200) ,
 `source_sac_note` varchar(200) ,
 `source_digital_marketing` varchar(30) ,
 `source_hotline` varchar(30) ,
 `source_internal_reference` varchar(30) ,
 `source_sales_executive` bigint(20) ,
 `Customer_dateofbirth` date ,
 `customer_wife_name` varchar(200) ,
 `customer_wife_dataofbirth` date ,
 `Marriage_anniversary` date ,
 `children_name1` varchar(200) ,
 `children_dateofbirth1` date ,
 `children_name2` varchar(200) ,
 `children_dateofbirth2` date ,
 `children_name3` varchar(200) ,
 `children_dateofbirth3` date ,
 `created_by` bigint(20) ,
 `leadlifecycle_pk_no` bigint(20) ,
 `leadlifecycle_id` varchar(30) ,
 `lead_dist_type` int(11) ,
 `lead_sales_agent_pk_no` bigint(20) ,
 `lead_sales_agent_name` varchar(200) ,
 `role_lookup_pk_no` bigint(20) ,
 `user_group_name` varchar(200) ,
 `lead_current_stage` int(11) ,
 `lead_current_stage_name` varchar(200) ,
 `lead_qc_flag` int(11) ,
 `lead_qc_datetime` date ,
 `lead_qc_by` bigint(20) ,
 `lead_k1_flag` int(11) ,
 `lead_k1_datetime` date ,
 `lead_k1_by` bigint(20) ,
 `lead_priority_flag` int(11) ,
 `lead_priority_datetime` date ,
 `lead_priority_by` bigint(20) ,
 `lead_hold_flag` int(11) ,
 `lead_hold_datetime` date ,
 `lead_hold_by` bigint(20) ,
 `lead_closed_flag` int(11) ,
 `lead_closed_datetime` date ,
 `lead_closed_by` bigint(20) ,
 `lead_sold_flag` int(11) ,
 `lead_sold_datetime` date ,
 `lead_sold_by` bigint(20) ,
 `lead_sold_date_manual` date ,
 `lead_sold_flatcost` float ,
 `lead_sold_utilitycost` float ,
 `lead_sold_parkingcost` float ,
 `lead_sold_customer_pk_no` bigint(20) ,
 `lead_sold_sales_agent_pk_no` bigint(20) ,
 `lead_sold_team_lead_pk_no` bigint(20) ,
 `lead_sold_team_manager_pk_no` bigint(20) ,
 `lead_transfer_flag` int(11) ,
 `lead_transfer_from_sales_agent_pk_no` bigint(20) 
)*/;

/*View structure for view kpi_acr */

/*!50001 DROP TABLE IF EXISTS `kpi_acr` */;
/*!50001 DROP VIEW IF EXISTS `kpi_acr` */;

/*!50001 CREATE VIEW `kpi_acr` AS select `tb`.`user_pk_no` AS `user_pk_no`,`tb`.`team_lead_user_pk_no` AS `team_lead_user_pk_no`,`u`.`User_name` AS `user_name`,`acrcnt`.`k1_count` AS `k1_count`,`acrcnt`.`priority_count` AS `priority_count`,`acrcnt`.`sold_count` AS `sold_count`,(`acrcnt`.`priority_count` / `acrcnt`.`k1_count`) AS `k1_priority_ratio`,(`acrcnt`.`sold_count` / `acrcnt`.`priority_count`) AS `priority_sold_ratio` from ((`t_teambuild` `tb` join `s_user` `u` on((`tb`.`user_pk_no` = `u`.`user_pk_no`))) left join `kpi_acr_count` `acrcnt` on((`acrcnt`.`lead_sales_agent_pk_no` = `u`.`user_pk_no`))) */;

/*View structure for view kpi_acr_count */

/*!50001 DROP TABLE IF EXISTS `kpi_acr_count` */;
/*!50001 DROP VIEW IF EXISTS `kpi_acr_count` */;

/*!50001 CREATE VIEW `kpi_acr_count` AS select `t_leadlifecycle`.`lead_sales_agent_pk_no` AS `lead_sales_agent_pk_no`,sum(`t_leadlifecycle`.`lead_k1_flag`) AS `k1_count`,sum(`t_leadlifecycle`.`lead_priority_flag`) AS `priority_count`,sum(`t_leadlifecycle`.`lead_sold_flag`) AS `sold_count` from `t_leadlifecycle` group by `t_leadlifecycle`.`lead_sales_agent_pk_no` */;

/*View structure for view kpi_apt */

/*!50001 DROP TABLE IF EXISTS `kpi_apt` */;
/*!50001 DROP VIEW IF EXISTS `kpi_apt` */;

/*!50001 CREATE VIEW `kpi_apt` AS select `tb`.`user_pk_no` AS `user_pk_no`,`tb`.`team_lead_user_pk_no` AS `team_lead_user_pk_no`,`u`.`User_name` AS `user_name`,`aptdd`.`lead2k1` AS `lead2k1`,`aptdd`.`k12priority` AS `k12priority`,`aptdd`.`priority2sold` AS `priority2sold`,`aptdd`.`k12sold` AS `k12sold` from ((`t_teambuild` `tb` join `s_user` `u` on((`tb`.`user_pk_no` = `u`.`user_pk_no`))) left join `kpi_apt_avgprocdays` `aptdd` on((`aptdd`.`lead_sales_agent_pk_no` = `u`.`user_pk_no`))) */;

/*View structure for view kpi_apt_avgprocdays */

/*!50001 DROP TABLE IF EXISTS `kpi_apt_avgprocdays` */;
/*!50001 DROP VIEW IF EXISTS `kpi_apt_avgprocdays` */;

/*!50001 CREATE VIEW `kpi_apt_avgprocdays` AS select `t_leadlifecycle`.`lead_sales_agent_pk_no` AS `lead_sales_agent_pk_no`,avg((to_days(`t_leadlifecycle`.`lead_k1_datetime`) - to_days(`t_leadlifecycle`.`lead_qc_datetime`))) AS `lead2k1`,avg((to_days(`t_leadlifecycle`.`lead_priority_datetime`) - to_days(`t_leadlifecycle`.`lead_k1_datetime`))) AS `k12priority`,avg((to_days(coalesce(`t_leadlifecycle`.`lead_sold_date_manual`,`t_leadlifecycle`.`lead_sold_datetime`)) - to_days(`t_leadlifecycle`.`lead_priority_datetime`))) AS `priority2sold`,avg((to_days(coalesce(`t_leadlifecycle`.`lead_sold_date_manual`,`t_leadlifecycle`.`lead_sold_datetime`)) - to_days(`t_leadlifecycle`.`lead_k1_datetime`))) AS `k12sold` from `t_leadlifecycle` group by `t_leadlifecycle`.`lead_sales_agent_pk_no` */;

/*View structure for view kpi_avt */

/*!50001 DROP TABLE IF EXISTS `kpi_avt` */;
/*!50001 DROP VIEW IF EXISTS `kpi_avt` */;

/*!50001 CREATE VIEW `kpi_avt` AS select `tb`.`user_pk_no` AS `user_pk_no`,`tb`.`team_lead_user_pk_no` AS `team_lead_user_pk_no`,`u`.`User_name` AS `user_name`,`tt`.`yy_mm` AS `yy_mm`,`tt`.`target_amount` AS `target_amount`,`tt`.`target_by_lead_qty` AS `target_by_lead_qty`,`samt`.`sold_yymm` AS `sold_yymm`,`samt`.`sold_amt` AS `sold_amt` from (((`t_teambuild` `tb` join `s_user` `u` on((`tb`.`user_pk_no` = `u`.`user_pk_no`))) left join `t_teamtarget` `tt` on((`u`.`user_pk_no` = `tt`.`user_pk_no`))) left join `kpi_soldamt_yymm` `samt` on((`samt`.`lead_sales_agent_pk_no` = `u`.`user_pk_no`))) */;

/*View structure for view kpi_soldamt_yymm */

/*!50001 DROP TABLE IF EXISTS `kpi_soldamt_yymm` */;
/*!50001 DROP VIEW IF EXISTS `kpi_soldamt_yymm` */;

/*!50001 CREATE VIEW `kpi_soldamt_yymm` AS select `t_leadlifecycle`.`lead_sales_agent_pk_no` AS `lead_sales_agent_pk_no`,date_format(coalesce(`t_leadlifecycle`.`lead_sold_date_manual`,`t_leadlifecycle`.`lead_sold_datetime`),'%y%m') AS `sold_yymm`,sum(`t_leadlifecycle`.`lead_sold_flatcost`) AS `sold_amt` from `t_leadlifecycle` group by `t_leadlifecycle`.`lead_sales_agent_pk_no`,date_format(coalesce(`t_leadlifecycle`.`lead_sold_date_manual`,`t_leadlifecycle`.`lead_sold_datetime`),'%y%m') */;

/*View structure for view t_lead2lifecycle_vw */

/*!50001 DROP TABLE IF EXISTS `t_lead2lifecycle_vw` */;
/*!50001 DROP VIEW IF EXISTS `t_lead2lifecycle_vw` */;

/*!50001 CREATE VIEW `t_lead2lifecycle_vw` AS select `ld`.`lead_pk_no` AS `lead_pk_no`,`ld`.`lead_id` AS `lead_id`,`ld`.`customer_firstname` AS `customer_firstname`,`ld`.`customer_lastname` AS `customer_lastname`,`ld`.`phone1` AS `phone1`,`ld`.`phone2` AS `phone2`,`ld`.`email_id` AS `email_id`,`ld`.`occupation_pk_no` AS `occupation_pk_no`,`lk_oc`.`lookup_name` AS `occup_name`,`ld`.`organization_pk_no` AS `organization_pk_no`,`lk_og`.`lookup_name` AS `org_name`,`ld`.`project_category_pk_no` AS `project_category_pk_no`,`lk_cat`.`lookup_name` AS `project_category_name`,`ld`.`project_area_pk_no` AS `project_area_pk_no`,`lk_area`.`lookup_name` AS `project_area`,`ld`.`Project_pk_no` AS `Project_pk_no`,`lk_pr`.`lookup_name` AS `project_name`,`ld`.`project_size_pk_no` AS `project_size_pk_no`,`lk_size`.`lookup_name` AS `project_size`,`lf`.`flatlist_pk_no` AS `flatlist_pk_no`,`ld`.`source_auto_pk_no` AS `source_auto_pk_no`,`usr`.`user_fullname` AS `user_full_name`,`ld`.`source_auto_usergroup_pk_no` AS `source_auto_usergroup_pk_no`,`ld`.`source_sac_name` AS `source_sac_name`,`ld`.`source_sac_note` AS `source_sac_note`,`ld`.`source_digital_marketing` AS `source_digital_marketing`,`ld`.`source_hotline` AS `source_hotline`,`ld`.`source_internal_reference` AS `source_internal_reference`,`ld`.`source_sales_executive` AS `source_sales_executive`,`ld`.`Customer_dateofbirth` AS `Customer_dateofbirth`,`ld`.`customer_wife_name` AS `customer_wife_name`,`ld`.`customer_wife_dataofbirth` AS `customer_wife_dataofbirth`,`ld`.`Marriage_anniversary` AS `Marriage_anniversary`,`ld`.`children_name1` AS `children_name1`,`ld`.`children_dateofbirth1` AS `children_dateofbirth1`,`ld`.`children_name2` AS `children_name2`,`ld`.`children_dateofbirth2` AS `children_dateofbirth2`,`ld`.`children_name3` AS `children_name3`,`ld`.`children_dateofbirth3` AS `children_dateofbirth3`,`ld`.`created_by` AS `created_by`,`lf`.`leadlifecycle_pk_no` AS `leadlifecycle_pk_no`,`lf`.`leadlifecycle_id` AS `leadlifecycle_id`,`lf`.`lead_dist_type` AS `lead_dist_type`,`lf`.`lead_sales_agent_pk_no` AS `lead_sales_agent_pk_no`,`s_agent`.`user_fullname` AS `lead_sales_agent_name`,`s_agent`.`role_lookup_pk_no` AS `role_lookup_pk_no`,`s_user_group`.`lookup_name` AS `user_group_name`,`lf`.`lead_current_stage` AS `lead_current_stage`,`lk_st`.`lookup_name` AS `lead_current_stage_name`,`lf`.`lead_qc_flag` AS `lead_qc_flag`,`lf`.`lead_qc_datetime` AS `lead_qc_datetime`,`lf`.`lead_qc_by` AS `lead_qc_by`,`lf`.`lead_k1_flag` AS `lead_k1_flag`,`lf`.`lead_k1_datetime` AS `lead_k1_datetime`,`lf`.`lead_k1_by` AS `lead_k1_by`,`lf`.`lead_priority_flag` AS `lead_priority_flag`,`lf`.`lead_priority_datetime` AS `lead_priority_datetime`,`lf`.`lead_priority_by` AS `lead_priority_by`,`lf`.`lead_hold_flag` AS `lead_hold_flag`,`lf`.`lead_hold_datetime` AS `lead_hold_datetime`,`lf`.`lead_hold_by` AS `lead_hold_by`,`lf`.`lead_closed_flag` AS `lead_closed_flag`,`lf`.`lead_closed_datetime` AS `lead_closed_datetime`,`lf`.`lead_closed_by` AS `lead_closed_by`,`lf`.`lead_sold_flag` AS `lead_sold_flag`,`lf`.`lead_sold_datetime` AS `lead_sold_datetime`,`lf`.`lead_sold_by` AS `lead_sold_by`,`lf`.`lead_sold_date_manual` AS `lead_sold_date_manual`,`lf`.`lead_sold_flatcost` AS `lead_sold_flatcost`,`lf`.`lead_sold_utilitycost` AS `lead_sold_utilitycost`,`lf`.`lead_sold_parkingcost` AS `lead_sold_parkingcost`,`lf`.`lead_sold_customer_pk_no` AS `lead_sold_customer_pk_no`,`lf`.`lead_sold_sales_agent_pk_no` AS `lead_sold_sales_agent_pk_no`,`lf`.`lead_sold_team_lead_pk_no` AS `lead_sold_team_lead_pk_no`,`lf`.`lead_sold_team_manager_pk_no` AS `lead_sold_team_manager_pk_no`,`lf`.`lead_transfer_flag` AS `lead_transfer_flag`,`lf`.`lead_transfer_from_sales_agent_pk_no` AS `lead_transfer_from_sales_agent_pk_no` from (((((((((((`t_leads` `ld` left join `t_leadlifecycle` `lf` on((`ld`.`lead_pk_no` = `lf`.`lead_pk_no`))) left join `s_lookdata` `lk_oc` on((`lk_oc`.`lookup_pk_no` = `ld`.`occupation_pk_no`))) left join `s_lookdata` `lk_og` on((`lk_og`.`lookup_pk_no` = `ld`.`organization_pk_no`))) left join `s_lookdata` `lk_st` on((`lk_st`.`lookup_pk_no` = `lf`.`lead_current_stage`))) left join `s_lookdata` `lk_pr` on((`lk_pr`.`lookup_pk_no` = `ld`.`Project_pk_no`))) left join `s_user` `s_agent` on((`s_agent`.`user_pk_no` = `lf`.`lead_sales_agent_pk_no`))) left join `s_lookdata` `s_user_group` on((`s_user_group`.`lookup_pk_no` = `s_agent`.`role_lookup_pk_no`))) left join `s_lookdata` `lk_cat` on((`lk_cat`.`lookup_pk_no` = `ld`.`project_category_pk_no`))) left join `s_lookdata` `lk_area` on((`lk_area`.`lookup_pk_no` = `ld`.`project_area_pk_no`))) left join `s_lookdata` `lk_size` on((`lk_size`.`lookup_pk_no` = `ld`.`project_size_pk_no`))) left join `s_user` `usr` on((`usr`.`user_pk_no` = `ld`.`source_auto_pk_no`))) */;
/*!50001 CREATE VIEW `t_lead2lifecycle_vw` AS select `ld`.`lead_pk_no` AS `lead_pk_no`,`ld`.`lead_id` AS `lead_id`,`ld`.`customer_firstname` AS `customer_firstname`,`ld`.`customer_lastname` AS `customer_lastname`,`ld`.`phone1` AS `phone1`,`ld`.`phone2` AS `phone2`,`ld`.`email_id` AS `email_id`,`ld`.`occupation_pk_no` AS `occupation_pk_no`,`lk_oc`.`lookup_name` AS `occup_name`,`ld`.`organization_pk_no` AS `organization_pk_no`,`lk_og`.`lookup_name` AS `org_name`,`ld`.`project_category_pk_no` AS `project_category_pk_no`,`lk_cat`.`lookup_name` AS `project_category_name`,`ld`.`project_area_pk_no` AS `project_area_pk_no`,`lk_area`.`lookup_name` AS `project_area`,`ld`.`Project_pk_no` AS `Project_pk_no`,`lk_pr`.`lookup_name` AS `project_name`,`ld`.`project_size_pk_no` AS `project_size_pk_no`,`lk_size`.`lookup_name` AS `project_size`,`lf`.`flatlist_pk_no` AS `flatlist_pk_no`,`ld`.`source_auto_pk_no` AS `source_auto_pk_no`,`usr`.`user_fullname` AS `user_full_name`,`ld`.`source_auto_usergroup_pk_no` AS `source_auto_usergroup_pk_no`,`ld`.`source_sac_name` AS `source_sac_name`,`ld`.`source_sac_note` AS `source_sac_note`,`ld`.`source_digital_marketing` AS `source_digital_marketing`,`ld`.`source_hotline` AS `source_hotline`,`ld`.`source_internal_reference` AS `source_internal_reference`,`ld`.`source_sales_executive` AS `source_sales_executive`,`ld`.`Customer_dateofbirth` AS `Customer_dateofbirth`,`ld`.`customer_wife_name` AS `customer_wife_name`,`ld`.`customer_wife_dataofbirth` AS `customer_wife_dataofbirth`,`ld`.`Marriage_anniversary` AS `Marriage_anniversary`,`ld`.`children_name1` AS `children_name1`,`ld`.`children_dateofbirth1` AS `children_dateofbirth1`,`ld`.`children_name2` AS `children_name2`,`ld`.`children_dateofbirth2` AS `children_dateofbirth2`,`ld`.`children_name3` AS `children_name3`,`ld`.`children_dateofbirth3` AS `children_dateofbirth3`,`ld`.`created_by` AS `created_by`,`lf`.`leadlifecycle_pk_no` AS `leadlifecycle_pk_no`,`lf`.`leadlifecycle_id` AS `leadlifecycle_id`,`lf`.`lead_dist_type` AS `lead_dist_type`,`lf`.`lead_sales_agent_pk_no` AS `lead_sales_agent_pk_no`,`s_agent`.`user_fullname` AS `lead_sales_agent_name`,`s_agent`.`role_lookup_pk_no` AS `role_lookup_pk_no`,`s_user_group`.`lookup_name` AS `user_group_name`,`lf`.`lead_current_stage` AS `lead_current_stage`,`lk_st`.`lookup_name` AS `lead_current_stage_name`,`lf`.`lead_qc_flag` AS `lead_qc_flag`,`lf`.`lead_qc_datetime` AS `lead_qc_datetime`,`lf`.`lead_qc_by` AS `lead_qc_by`,`lf`.`lead_k1_flag` AS `lead_k1_flag`,`lf`.`lead_k1_datetime` AS `lead_k1_datetime`,`lf`.`lead_k1_by` AS `lead_k1_by`,`lf`.`lead_priority_flag` AS `lead_priority_flag`,`lf`.`lead_priority_datetime` AS `lead_priority_datetime`,`lf`.`lead_priority_by` AS `lead_priority_by`,`lf`.`lead_hold_flag` AS `lead_hold_flag`,`lf`.`lead_hold_datetime` AS `lead_hold_datetime`,`lf`.`lead_hold_by` AS `lead_hold_by`,`lf`.`lead_closed_flag` AS `lead_closed_flag`,`lf`.`lead_closed_datetime` AS `lead_closed_datetime`,`lf`.`lead_closed_by` AS `lead_closed_by`,`lf`.`lead_sold_flag` AS `lead_sold_flag`,`lf`.`lead_sold_datetime` AS `lead_sold_datetime`,`lf`.`lead_sold_by` AS `lead_sold_by`,`lf`.`lead_sold_date_manual` AS `lead_sold_date_manual`,`lf`.`lead_sold_flatcost` AS `lead_sold_flatcost`,`lf`.`lead_sold_utilitycost` AS `lead_sold_utilitycost`,`lf`.`lead_sold_parkingcost` AS `lead_sold_parkingcost`,`lf`.`lead_sold_customer_pk_no` AS `lead_sold_customer_pk_no`,`lf`.`lead_sold_sales_agent_pk_no` AS `lead_sold_sales_agent_pk_no`,`lf`.`lead_sold_team_lead_pk_no` AS `lead_sold_team_lead_pk_no`,`lf`.`lead_sold_team_manager_pk_no` AS `lead_sold_team_manager_pk_no`,`lf`.`lead_transfer_flag` AS `lead_transfer_flag`,`lf`.`lead_transfer_from_sales_agent_pk_no` AS `lead_transfer_from_sales_agent_pk_no` from (((((((((((`t_leads` `ld` left join `t_leadlifecycle` `lf` on((`ld`.`lead_pk_no` = `lf`.`lead_pk_no`))) left join `s_lookdata` `lk_oc` on((`lk_oc`.`lookup_pk_no` = `ld`.`occupation_pk_no`))) left join `s_lookdata` `lk_og` on((`lk_og`.`lookup_pk_no` = `ld`.`organization_pk_no`))) left join `s_lookdata` `lk_st` on((`lk_st`.`lookup_pk_no` = `lf`.`lead_current_stage`))) left join `s_lookdata` `lk_pr` on((`lk_pr`.`lookup_pk_no` = `ld`.`Project_pk_no`))) left join `s_user` `s_agent` on((`s_agent`.`user_pk_no` = `lf`.`lead_sales_agent_pk_no`))) left join `s_lookdata` `s_user_group` on((`s_user_group`.`lookup_pk_no` = `s_agent`.`role_lookup_pk_no`))) left join `s_lookdata` `lk_cat` on((`lk_cat`.`lookup_pk_no` = `ld`.`project_category_pk_no`))) left join `s_lookdata` `lk_area` on((`lk_area`.`lookup_pk_no` = `ld`.`project_area_pk_no`))) left join `s_lookdata` `lk_size` on((`lk_size`.`lookup_pk_no` = `ld`.`project_size_pk_no`))) left join `s_user` `usr` on((`usr`.`user_pk_no` = `ld`.`source_auto_pk_no`))) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
