-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Host: sqletud.univ-mlv.fr
-- Generation Time: Feb 23, 2012 at 04:30 AM
-- Server version: 5.1.49
-- PHP Version: 5.3.3-7+squeeze8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `eyou01_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `attribute_ingredients`
--

CREATE TABLE IF NOT EXISTS `attribute_ingredients` (
  `id_type_attribute` bigint(20) unsigned NOT NULL,
  `id_ingredient` bigint(20) unsigned NOT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `id_ingredient` (`id_ingredient`),
  KEY `id_type_attribute` (`id_type_attribute`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `attribute_ingredients`
--


-- --------------------------------------------------------

--
-- Table structure for table `attribute_ingredients_type`
--

CREATE TABLE IF NOT EXISTS `attribute_ingredients_type` (
  `name_fr` varchar(50) NOT NULL,
  `name_en` varchar(50) NOT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `attribute_ingredients_type`
--


-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE IF NOT EXISTS `city` (
  `detail` varchar(255) DEFAULT NULL,
  `name_fr` varchar(255) DEFAULT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `id_country` bigint(20) unsigned NOT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `id_country` (`id_country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `city`
--


-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `id_country` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code_country` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `name_fr` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `name_en` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_country`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=239 ;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id_country`, `code_country`, `name_fr`, `name_en`) VALUES
(1, 'AF', 'Afghanistan', 'Afghanistan'),
(2, 'ZA', 'Afrique du Sud', 'South Africa'),
(3, 'AL', 'Albanie', 'Albania'),
(4, 'DZ', 'Algérie', 'Algeria'),
(5, 'DE', 'Allemagne', 'Germany'),
(6, 'AD', 'Andorre', 'Andorra'),
(7, 'AO', 'Angola', 'Angola'),
(8, 'AI', 'Anguilla', 'Anguilla'),
(9, 'AQ', 'Antarctique', 'Antarctica'),
(10, 'AG', 'Antigua-et-Barbuda', 'Antigua & Barbuda'),
(11, 'AN', 'Antilles néerlandaises', 'Netherlands Antilles'),
(12, 'SA', 'Arabie saoudite', 'Saudi Arabia'),
(13, 'AR', 'Argentine', 'Argentina'),
(14, 'AM', 'Arménie', 'Armenia'),
(15, 'AW', 'Aruba', 'Aruba'),
(16, 'AU', 'Australie', 'Australia'),
(17, 'AT', 'Autriche', 'Austria'),
(18, 'AZ', 'Azerbaïdjan', 'Azerbaijan'),
(19, 'BJ', 'Bénin', 'Benin'),
(20, 'BS', 'Bahamas', 'Bahamas, The'),
(21, 'BH', 'Bahreïn', 'Bahrain'),
(22, 'BD', 'Bangladesh', 'Bangladesh'),
(23, 'BB', 'Barbade', 'Barbados'),
(24, 'PW', 'Belau', 'Palau'),
(25, 'BE', 'Belgique', 'Belgium'),
(26, 'BZ', 'Belize', 'Belize'),
(27, 'BM', 'Bermudes', 'Bermuda'),
(28, 'BT', 'Bhoutan', 'Bhutan'),
(29, 'BY', 'Biélorussie', 'Belarus'),
(30, 'MM', 'Birmanie', 'Myanmar (ex-Burma)'),
(31, 'BO', 'Bolivie', 'Bolivia'),
(32, 'BA', 'Bosnie-Herzégovine', 'Bosnia and Herzegovina'),
(33, 'BW', 'Botswana', 'Botswana'),
(34, 'BR', 'Brésil', 'Brazil'),
(35, 'BN', 'Brunei', 'Brunei Darussalam'),
(36, 'BG', 'Bulgarie', 'Bulgaria'),
(37, 'BF', 'Burkina Faso', 'Burkina Faso'),
(38, 'BI', 'Burundi', 'Burundi'),
(39, 'CI', 'Côte d''Ivoire', 'Ivory Coast (see Cote d''Ivoire)'),
(40, 'KH', 'Cambodge', 'Cambodia'),
(41, 'CM', 'Cameroun', 'Cameroon'),
(42, 'CA', 'Canada', 'Canada'),
(43, 'CV', 'Cap-Vert', 'Cape Verde'),
(44, 'CL', 'Chili', 'Chile'),
(45, 'CN', 'Chine', 'China'),
(46, 'CY', 'Chypre', 'Cyprus'),
(47, 'CO', 'Colombie', 'Colombia'),
(48, 'KM', 'Comores', 'Comoros'),
(49, 'CG', 'Congo', 'Congo'),
(50, 'KP', 'Corée du Nord', 'Korea, Demo. People''s Rep. of'),
(51, 'KR', 'Corée du Sud', 'Korea, (South) Republic of'),
(52, 'CR', 'Costa Rica', 'Costa Rica'),
(53, 'HR', 'Croatie', 'Croatia'),
(54, 'CU', 'Cuba', 'Cuba'),
(55, 'DK', 'Danemark', 'Denmark'),
(56, 'DJ', 'Djibouti', 'Djibouti'),
(57, 'DM', 'Dominique', 'Dominica'),
(58, 'EG', 'Égypte', 'Egypt'),
(59, 'AE', 'Émirats arabes unis', 'United Arab Emirates'),
(60, 'EC', 'Équateur', 'Ecuador'),
(61, 'ER', 'Érythrée', 'Eritrea'),
(62, 'ES', 'Espagne', 'Spain'),
(63, 'EE', 'Estonie', 'Estonia'),
(64, 'US', 'États-Unis', 'United States'),
(65, 'ET', 'Éthiopie', 'Ethiopia'),
(66, 'FI', 'Finlande', 'Finland'),
(67, 'FR', 'France', 'France'),
(68, 'GE', 'Géorgie', 'Georgia'),
(69, 'GA', 'Gabon', 'Gabon'),
(70, 'GM', 'Gambie', 'Gambia, the'),
(71, 'GH', 'Ghana', 'Ghana'),
(72, 'GI', 'Gibraltar', 'Gibraltar'),
(73, 'GR', 'Grèce', 'Greece'),
(74, 'GD', 'Grenade', 'Grenada'),
(75, 'GL', 'Groenland', 'Greenland'),
(76, 'GP', 'Guadeloupe', 'Guinea, Equatorial'),
(77, 'GU', 'Guam', 'Guam'),
(78, 'GT', 'Guatemala', 'Guatemala'),
(79, 'GN', 'Guinée', 'Guinea'),
(80, 'GQ', 'Guinée équatoriale', 'Equatorial Guinea'),
(81, 'GW', 'Guinée-Bissao', 'Guinea-Bissau'),
(82, 'GY', 'Guyana', 'Guyana'),
(83, 'GF', 'Guyane française', 'Guiana, French'),
(84, 'HT', 'Haïti', 'Haiti'),
(85, 'HN', 'Honduras', 'Honduras'),
(86, 'HK', 'Hong Kong', 'Hong Kong, (China)'),
(87, 'HU', 'Hongrie', 'Hungary'),
(88, 'BV', 'Ile Bouvet', 'Bouvet Island'),
(89, 'CX', 'Ile Christmas', 'Christmas Island'),
(90, 'NF', 'Ile Norfolk', 'Norfolk Island'),
(91, 'KY', 'Iles Cayman', 'Cayman Islands'),
(92, 'CK', 'Iles Cook', 'Cook Islands'),
(93, 'FO', 'Iles Féroé', 'Faroe Islands'),
(94, 'FK', 'Iles Falkland', 'Falkland Islands (Malvinas)'),
(95, 'FJ', 'Iles Fidji', 'Fiji'),
(96, 'GS', 'Iles Géorgie du Sud et Sandwich du Sud', 'S. Georgia and S. Sandwich Is.'),
(97, 'HM', 'Iles Heard et McDonald', 'Heard and McDonald Islands'),
(98, 'MH', 'Iles Marshall', 'Marshall Islands'),
(99, 'PN', 'Iles Pitcairn', 'Pitcairn Island'),
(100, 'SB', 'Iles Salomon', 'Solomon Islands'),
(101, 'SJ', 'Iles Svalbard et Jan Mayen', 'Svalbard and Jan Mayen Islands'),
(102, 'TC', 'Iles Turks-et-Caicos', 'Turks and Caicos Islands'),
(103, 'VI', 'Iles Vierges américaines', 'Virgin Islands, U.S.'),
(104, 'VG', 'Iles Vierges britanniques', 'Virgin Islands, British'),
(105, 'CC', 'Iles des Cocos (Keeling)', 'Cocos (Keeling) Islands'),
(106, 'UM', 'Iles mineures éloignées des États-Unis', 'US Minor Outlying Islands'),
(107, 'IN', 'Inde', 'India'),
(108, 'ID', 'Indonésie', 'Indonesia'),
(109, 'IR', 'Iran', 'Iran, Islamic Republic of'),
(110, 'IQ', 'Iraq', 'Iraq'),
(111, 'IE', 'Irlande', 'Ireland'),
(112, 'IS', 'Islande', 'Iceland'),
(113, 'IL', 'Israël', 'Israel'),
(114, 'IT', 'Italie', 'Italy'),
(115, 'JM', 'Jamaïque', 'Jamaica'),
(116, 'JP', 'Japon', 'Japan'),
(117, 'JO', 'Jordanie', 'Jordan'),
(118, 'KZ', 'Kazakhstan', 'Kazakhstan'),
(119, 'KE', 'Kenya', 'Kenya'),
(120, 'KG', 'Kirghizistan', 'Kyrgyzstan'),
(121, 'KI', 'Kiribati', 'Kiribati'),
(122, 'KW', 'Koweït', 'Kuwait'),
(123, 'LA', 'Laos', 'Lao People''s Democratic Republic'),
(124, 'LS', 'Lesotho', 'Lesotho'),
(125, 'LV', 'Lettonie', 'Latvia'),
(126, 'LB', 'Liban', 'Lebanon'),
(127, 'LR', 'Liberia', 'Liberia'),
(128, 'LY', 'Libye', 'Libyan Arab Jamahiriya'),
(129, 'LI', 'Liechtenstein', 'Liechtenstein'),
(130, 'LT', 'Lituanie', 'Lithuania'),
(131, 'LU', 'Luxembourg', 'Luxembourg'),
(132, 'MO', 'Macao', 'Macao, (China)'),
(133, 'MG', 'Madagascar', 'Madagascar'),
(134, 'MY', 'Malaisie', 'Malaysia'),
(135, 'MW', 'Malawi', 'Malawi'),
(136, 'MV', 'Maldives', 'Maldives'),
(137, 'ML', 'Mali', 'Mali'),
(138, 'MT', 'Malte', 'Malta'),
(139, 'MP', 'Mariannes du Nord', 'Northern Mariana Islands'),
(140, 'MA', 'Maroc', 'Morocco'),
(141, 'MQ', 'Martinique', 'Martinique'),
(142, 'MU', 'Maurice', 'Mauritius'),
(143, 'MR', 'Mauritanie', 'Mauritania'),
(144, 'YT', 'Mayotte', 'Mayotte'),
(145, 'MX', 'Mexique', 'Mexico'),
(146, 'FM', 'Micronésie', 'Micronesia, Federated States of'),
(147, 'MD', 'Moldavie', 'Moldova, Republic of'),
(148, 'MC', 'Monaco', 'Monaco'),
(149, 'MN', 'Mongolie', 'Mongolia'),
(150, 'MS', 'Montserrat', 'Montserrat'),
(151, 'MZ', 'Mozambique', 'Mozambique'),
(152, 'NP', 'Népal', 'Nepal'),
(153, 'NA', 'Namibie', 'Namibia'),
(154, 'NR', 'Nauru', 'Nauru'),
(155, 'NI', 'Nicaragua', 'Nicaragua'),
(156, 'NE', 'Niger', 'Niger'),
(157, 'NG', 'Nigeria', 'Nigeria'),
(158, 'NU', 'Nioué', 'Niue'),
(159, 'NO', 'Norvège', 'Norway'),
(160, 'NC', 'Nouvelle-Calédonie', 'New Caledonia'),
(161, 'NZ', 'Nouvelle-Zélande', 'New Zealand'),
(162, 'OM', 'Oman', 'Oman'),
(163, 'UG', 'Ouganda', 'Uganda'),
(164, 'UZ', 'Ouzbékistan', 'Uzbekistan'),
(165, 'PE', 'Pérou', 'Peru'),
(166, 'PK', 'Pakistan', 'Pakistan'),
(167, 'PA', 'Panama', 'Panama'),
(168, 'PG', 'Papouasie-Nouvelle-Guinée', 'Papua New Guinea'),
(169, 'PY', 'Paraguay', 'Paraguay'),
(170, 'NL', 'Pays-Bas', 'Netherlands'),
(171, 'PH', 'Philippines', 'Philippines'),
(172, 'PL', 'Pologne', 'Poland'),
(173, 'PF', 'Polynésie française', 'French Polynesia'),
(174, 'PR', 'Porto Rico', 'Puerto Rico'),
(175, 'PT', 'Portugal', 'Portugal'),
(176, 'QA', 'Qatar', 'Qatar'),
(177, 'CF', 'République centrafricaine', 'Central African Republic'),
(178, 'CD', 'République démocratique du Congo', 'Congo, Democratic Rep. of the'),
(179, 'DO', 'République dominicaine', 'Dominican Republic'),
(180, 'CZ', 'République tchèque', 'Czech Republic'),
(181, 'RE', 'Réunion', 'Reunion'),
(182, 'RO', 'Roumanie', 'Romania'),
(183, 'GB', 'Royaume-Uni', 'Saint Pierre and Miquelon'),
(184, 'RU', 'Russie', 'Russia (Russian Federation)'),
(185, 'RW', 'Rwanda', 'Rwanda'),
(186, 'SN', 'Sénégal', 'Senegal'),
(187, 'EH', 'Sahara occidental', 'Western Sahara'),
(188, 'KN', 'Saint-Christophe-et-Niévès', 'Saint Kitts and Nevis'),
(189, 'SM', 'Saint-Marin', 'San Marino'),
(190, 'PM', 'Saint-Pierre-et-Miquelon', 'Saint Pierre and Miquelon'),
(191, 'VA', 'Saint-Siège ', 'Vatican City State (Holy See)'),
(192, 'VC', 'Saint-Vincent-et-les-Grenadines', 'Saint Vincent and the Grenadines'),
(193, 'SH', 'Sainte-Hélène', 'Saint Helena'),
(194, 'LC', 'Sainte-Lucie', 'Saint Lucia'),
(195, 'SV', 'Salvador', 'El Salvador'),
(196, 'WS', 'Samoa', 'Samoa'),
(197, 'AS', 'Samoa américaines', 'American Samoa'),
(198, 'ST', 'Sao Tomé-et-Principe', 'Sao Tome and Principe'),
(199, 'SC', 'Seychelles', 'Seychelles'),
(200, 'SL', 'Sierra Leone', 'Sierra Leone'),
(201, 'SG', 'Singapour', 'Singapore'),
(202, 'SI', 'Slovénie', 'Slovenia'),
(203, 'SK', 'Slovaquie', 'Slovakia'),
(204, 'SO', 'Somalie', 'Somalia'),
(205, 'SD', 'Soudan', 'Sudan'),
(206, 'LK', 'Sri Lanka', 'Sri Lanka (ex-Ceilan)'),
(207, 'SE', 'Suède', 'Sweden'),
(208, 'CH', 'Suisse', 'Switzerland'),
(209, 'SR', 'Suriname', 'Suriname'),
(210, 'SZ', 'Swaziland', 'Swaziland'),
(211, 'SY', 'Syrie', 'Syrian Arab Republic'),
(212, 'TW', 'Taïwan', 'Taiwan'),
(213, 'TJ', 'Tadjikistan', 'Tajikistan'),
(214, 'TZ', 'Tanzanie', 'Tanzania, United Republic of'),
(215, 'TD', 'Tchad', 'Chad'),
(216, 'TF', 'Terres australes françaises', 'French Southern Territories - TF'),
(217, 'IO', 'Territoire britannique de l''Océan Indien', 'British Indian Ocean Territory'),
(218, 'TH', 'Thaïlande', 'Thailand'),
(219, 'TL', 'Timor Oriental', 'Timor-Leste (East Timor)'),
(220, 'TG', 'Togo', 'Togo'),
(221, 'TK', 'Tokélaou', 'Tokelau'),
(222, 'TO', 'Tonga', 'Tonga'),
(223, 'TT', 'Trinité-et-Tobago', 'Trinidad & Tobago'),
(224, 'TN', 'Tunisie', 'Tunisia'),
(225, 'TM', 'Turkménistan', 'Turkmenistan'),
(226, 'TR', 'Turquie', 'Turkey'),
(227, 'TV', 'Tuvalu', 'Tuvalu'),
(228, 'UA', 'Ukraine', 'Ukraine'),
(229, 'UY', 'Uruguay', 'Uruguay'),
(230, 'VU', 'Vanuatu', 'Vanuatu'),
(231, 'VE', 'Venezuela', 'Venezuela'),
(232, 'VN', 'ViÃªt Nam', 'Viet Nam'),
(233, 'WF', 'Wallis-et-Futuna', 'Wallis and Futuna'),
(234, 'YE', 'Yémen', 'Yemen'),
(235, 'YU', 'Yougoslavie', 'Saint Pierre and Miquelon'),
(236, 'ZM', 'Zambie', 'Zambia'),
(237, 'ZW', 'Zimbabwe', 'Zimbabwe'),
(238, 'MK', 'ex-République yougoslave de Macédoine', 'Macedonia, TFYR');

-- --------------------------------------------------------

--
-- Table structure for table `evolution`
--

CREATE TABLE IF NOT EXISTS `evolution` (
  `weight` decimal(65,2) NOT NULL,
  `imc` decimal(65,2) NOT NULL,
  `id_user` bigint(20) unsigned NOT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `evolution`
--

INSERT INTO `evolution` (`weight`, `imc`, `id_user`, `id`, `date`) VALUES
(74.00, 24.16, 1, 1, '2012-02-22'),
(71.00, 23.18, 1, 2, '2012-02-22');

-- --------------------------------------------------------

--
-- Table structure for table `fridge`
--

CREATE TABLE IF NOT EXISTS `fridge` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint(20) unsigned NOT NULL,
  `id_ingredient` bigint(20) unsigned DEFAULT NULL,
  `text_default` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_ingredient` (`id_ingredient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `fridge`
--


-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_creator` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_creator` (`id_creator`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `id_creator`, `name`) VALUES
(1, 1, 'friends'),
(2, 2, 'friends'),
(3, 3, 'friends'),
(4, 1, 'family'),
(5, 1, 'school friends'),
(6, 4, 'friends'),
(7, 5, 'friends');

-- --------------------------------------------------------

--
-- Table structure for table `groups_relations`
--

CREATE TABLE IF NOT EXISTS `groups_relations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_group` bigint(20) unsigned NOT NULL,
  `id_user` bigint(20) unsigned NOT NULL,
  `approval` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`),
  KEY `id_group` (`id_group`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `groups_relations`
--

INSERT INTO `groups_relations` (`id`, `id_group`, `id_user`, `approval`) VALUES
(1, 6, 1, 1),
(4, 2, 1, 1),
(5, 1, 4, 1),
(7, 1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `information`
--

CREATE TABLE IF NOT EXISTS `information` (
  `date_birth` date DEFAULT NULL,
  `hobbies` varchar(255) DEFAULT NULL,
  `job` varchar(255) DEFAULT NULL,
  `music` varchar(255) DEFAULT NULL,
  `films` varchar(255) DEFAULT NULL,
  `books` varchar(255) DEFAULT NULL,
  `aboutme` varchar(255) DEFAULT NULL,
  `favouritefood` varchar(255) DEFAULT NULL,
  `id_user` bigint(20) unsigned NOT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `information`
--

INSERT INTO `information` (`date_birth`, `hobbies`, `job`, `music`, `films`, `books`, `aboutme`, `favouritefood`, `id_user`, `id`) VALUES
('1991-03-14', 'Books', 'MIT Security Engineer', 'Pop', '', 'Histoire des codes secrets', 'I nov you', '', 1, 1),
('1992-05-18', 'Piano', 'Pianist', 'Classical', '', '', '', '', 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE IF NOT EXISTS `ingredients` (
  `name_fr` varchar(50) NOT NULL,
  `description_fr` varchar(255) DEFAULT NULL,
  `name_en` varchar(50) NOT NULL,
  `description_en` varchar(255) DEFAULT NULL,
  `approval` int(10) DEFAULT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`name_fr`, `description_fr`, `name_en`, `description_en`, `approval`, `id`) VALUES
('', NULL, 'oil', NULL, NULL, 1),
('', NULL, 'lime juice', NULL, NULL, 2),
('', NULL, 'chicken', NULL, NULL, 3),
('', NULL, 'salt ', NULL, NULL, 4),
('', NULL, 'black pepper', NULL, NULL, 5),
('', NULL, 'flour', NULL, NULL, 6),
('', NULL, 'milk', NULL, NULL, 7),
('', NULL, 'sugar', NULL, NULL, 8),
('', NULL, 'water', NULL, NULL, 9),
('', NULL, 'eggs', NULL, NULL, 10),
('', NULL, 'beef', NULL, NULL, 11),
('', NULL, 'chili', NULL, NULL, 12),
('', NULL, 'salad cream', NULL, NULL, 13),
('', NULL, 'potatoes', NULL, NULL, 14),
('', NULL, 'corned beef', NULL, NULL, 15),
('', NULL, 'butter', NULL, NULL, 16),
('', NULL, 'garlic', NULL, NULL, 17),
('', NULL, 'mushroom cream', NULL, NULL, 18),
('', NULL, 'parmesan cheese', NULL, NULL, 19),
('', NULL, 'spinach', NULL, NULL, 20),
('', NULL, 'bacon', NULL, NULL, 21),
('', NULL, 'mozarella', NULL, NULL, 22),
('', NULL, 'orange', NULL, NULL, 23),
('', NULL, 'apple', NULL, NULL, 24),
('', NULL, 'banana', NULL, NULL, 25),
('', NULL, 'custard', NULL, NULL, 26),
('', NULL, 'ham', NULL, NULL, 27),
('', NULL, 'tomato', NULL, NULL, 28);

-- --------------------------------------------------------

--
-- Table structure for table `ingredients_photos`
--

CREATE TABLE IF NOT EXISTS `ingredients_photos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_ingredient` bigint(20) unsigned NOT NULL,
  `path` varchar(255) NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `id_ingredient` (`id_ingredient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ingredients_photos`
--


-- --------------------------------------------------------

--
-- Table structure for table `objective`
--

CREATE TABLE IF NOT EXISTS `objective` (
  `weight` decimal(65,2) NOT NULL,
  `size` int(100) NOT NULL,
  `id_user` int(100) unsigned NOT NULL,
  `lose_weight` int(10) NOT NULL,
  `gain_weight` int(10) NOT NULL,
  `taste` int(10) NOT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `objective`
--

INSERT INTO `objective` (`weight`, `size`, `id_user`, `lose_weight`, `gain_weight`, `taste`, `id`, `date`) VALUES
(74.00, 175, 1, 10, 0, 0, 1, '2012-02-22');

-- --------------------------------------------------------

--
-- Table structure for table `quantity_unit`
--

CREATE TABLE IF NOT EXISTS `quantity_unit` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `unit_type` varchar(255) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `name_fr` varchar(255) DEFAULT NULL,
  `abbrev` varchar(255) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `quantity_unit`
--

INSERT INTO `quantity_unit` (`id`, `unit_type`, `name_en`, `name_fr`, `abbrev`) VALUES
(1, 'liquid', 'Litter', 'Litre', 'L'),
(2, 'liquid', 'Teaspoon', 'Cuillière à café', NULL),
(3, 'solid', 'Gram', 'Gramme', 'g'),
(4, 'solid', 'Ounce', 'Once', 'oz'),
(5, 'piece', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE IF NOT EXISTS `recipes` (
  `name_en` varchar(50) NOT NULL,
  `description_en` longtext,
  `country_origin` bigint(20) unsigned DEFAULT NULL,
  `difficulty` int(11) DEFAULT NULL,
  `num_serves` int(5) DEFAULT NULL,
  `duration_preparation` int(5) DEFAULT NULL,
  `duration_cook` int(5) DEFAULT NULL,
  `preparation_en` longtext,
  `creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateupdate` timestamp NULL DEFAULT NULL,
  `approval` int(10) NOT NULL,
  `id_user` bigint(20) unsigned NOT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `country_origin` (`country_origin`),
  KEY `difficulty` (`difficulty`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`name_en`, `description_en`, `country_origin`, `difficulty`, `num_serves`, `duration_preparation`, `duration_cook`, `preparation_en`, `creation`, `dateupdate`, `approval`, `id_user`, `id`) VALUES
('toto', 'toto', 66, 3, 4, 20, 10, 'description ???', '2012-02-22 18:39:00', NULL, 0, 1, 1),
('titi', 'baby', 11, 1, 3, 10, 20, 'tititi this is a private recipe', '2012-02-22 18:41:47', NULL, 1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `recipe_albums`
--

CREATE TABLE IF NOT EXISTS `recipe_albums` (
  `title` varchar(50) NOT NULL,
  `description_album` varchar(255) NOT NULL,
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `recipe_albums`
--

INSERT INTO `recipe_albums` (`title`, `description_album`, `id`, `date`, `time`) VALUES
('toto', 'tototototo', 1, '2012-02-21', '11:34:42');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_comments`
--

CREATE TABLE IF NOT EXISTS `recipe_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_recipe` bigint(20) unsigned NOT NULL,
  `id_user` bigint(20) unsigned NOT NULL,
  `comment` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `id` (`id`),
  KEY `id_recipe` (`id_recipe`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `recipe_comments`
--


-- --------------------------------------------------------

--
-- Table structure for table `recipe_difficulty`
--

CREATE TABLE IF NOT EXISTS `recipe_difficulty` (
  `id` int(11) NOT NULL,
  `name_en` varchar(30) NOT NULL,
  `name_fr` varchar(30) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recipe_difficulty`
--

INSERT INTO `recipe_difficulty` (`id`, `name_en`, `name_fr`) VALUES
(1, 'Easy', 'Facile'),
(2, 'Normal', 'Normal'),
(3, 'Difficult', 'Difficile'),
(4, 'Madness', 'Compliquée');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_ingredients`
--

CREATE TABLE IF NOT EXISTS `recipe_ingredients` (
  `id_recipe` bigint(20) unsigned NOT NULL,
  `id_ingredient` bigint(20) unsigned NOT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `quantity` int(11) DEFAULT NULL,
  `quantity_unit` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_recipe` (`id_recipe`),
  KEY `id_ingredient` (`id_ingredient`),
  KEY `quantity_unit` (`quantity_unit`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `recipe_ingredients`
--

INSERT INTO `recipe_ingredients` (`id_recipe`, `id_ingredient`, `id`, `quantity`, `quantity_unit`) VALUES
(1, 23, 1, NULL, 0),
(1, 24, 2, NULL, 0),
(1, 25, 3, NULL, 0),
(1, 13, 4, NULL, 0),
(1, 26, 5, NULL, 0),
(1, 8, 6, NULL, 0),
(2, 3, 7, NULL, 0),
(2, 11, 8, NULL, 0),
(2, 27, 9, NULL, 0),
(2, 17, 10, NULL, 0),
(2, 28, 11, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `recipe_photos`
--

CREATE TABLE IF NOT EXISTS `recipe_photos` (
  `id_recipe` bigint(20) unsigned NOT NULL,
  `path_source` varchar(255) NOT NULL,
  `id_album` bigint(20) unsigned NOT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `id_recipe` (`id_recipe`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `recipe_photos`
--

INSERT INTO `recipe_photos` (`id_recipe`, `path_source`, `id_album`, `id`) VALUES
(1, './img/recipes/1_1.jpg', 0, 1),
(2, './img/recipes/1_2.jpg', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `recipe_rating`
--

CREATE TABLE IF NOT EXISTS `recipe_rating` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_recipe` bigint(20) unsigned NOT NULL,
  `id_user` bigint(20) unsigned NOT NULL,
  `rating` int(11) NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `id_recipe` (`id_recipe`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `recipe_rating`
--


-- --------------------------------------------------------

--
-- Table structure for table `recipe_taste`
--

CREATE TABLE IF NOT EXISTS `recipe_taste` (
  `id_user` bigint(20) unsigned NOT NULL,
  `id_recipe` bigint(20) unsigned NOT NULL,
  `taste` int(1) NOT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `recipe_taste`
--


-- --------------------------------------------------------

--
-- Table structure for table `recipe_view_permission`
--

CREATE TABLE IF NOT EXISTS `recipe_view_permission` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_recipe` bigint(20) unsigned NOT NULL,
  `id_group` bigint(20) unsigned NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `id_recipe` (`id_recipe`),
  KEY `id_group` (`id_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `recipe_view_permission`
--


-- --------------------------------------------------------

--
-- Table structure for table `shoplist`
--

CREATE TABLE IF NOT EXISTS `shoplist` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint(20) unsigned NOT NULL,
  `product` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `id` (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `shoplist`
--

INSERT INTO `shoplist` (`id`, `id_user`, `product`, `status`, `add_date`) VALUES
(1, 1, 'rice', 1, '2012-02-22 18:57:20'),
(2, 1, 'carrots', 1, '2012-02-22 18:57:34'),
(3, 1, 'dog meat', 1, '2012-02-22 23:48:08'),
(4, 1, 'vodka', 2, '2012-02-22 23:49:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `firstname` varchar(40) NOT NULL,
  `surname` varchar(40) NOT NULL,
  `sex` int(3) NOT NULL,
  `address` varchar(40) DEFAULT NULL,
  `city` bigint(20) unsigned DEFAULT NULL,
  `country` bigint(20) unsigned DEFAULT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(50) NOT NULL,
  `mail` varchar(80) NOT NULL,
  `avatar` varchar(200) NOT NULL,
  `creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `country` (`country`),
  KEY `city` (`city`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`firstname`, `surname`, `sex`, `address`, `city`, `country`, `username`, `password`, `mail`, `avatar`, `creation`, `id`) VALUES
('John', 'Wan', 1, '4 Rue Claude Debussy', NULL, 67, 'alisterwan', '9cf95dacd226dcf43da376cdb6cbba7035218921', 'alisterwan@gmail.com', './img/avatar/1_profile_07102011022.jpg', '2012-02-21 16:45:41', 1),
('firstname', 'surname', 2, NULL, NULL, NULL, 'username', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'email@email.email', './img/avatar/woman_default.png', '2012-02-21 16:54:25', 2),
('Jeffrey', 'Lucas', 1, NULL, NULL, NULL, 'extinguisher', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'lynxsilver@hotmail.fr', './img/avatar/3_profile_DSC00107.JPG', '2012-02-22 18:30:07', 3),
('Sarah', 'Ott', 2, NULL, NULL, NULL, 'Alice', '9cf95dacd226dcf43da376cdb6cbba7035218921', 'aso@hotmail.com', './img/avatar/4_profile_Alice Sara Ott.jpg', '2012-02-22 22:33:18', 4),
('Alice Sara', 'Ott', 1, NULL, NULL, NULL, 'aso', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'bien.manger@live.fr', './img/avatar/man_default.png', '2012-02-22 22:46:52', 5);

-- --------------------------------------------------------

--
-- Table structure for table `wall_post`
--

CREATE TABLE IF NOT EXISTS `wall_post` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint(20) unsigned NOT NULL,
  `id_poster` bigint(20) unsigned NOT NULL,
  `post_type` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `approval` int(11) NOT NULL,
  `like` int(11) NOT NULL,
  `dislike` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `id` (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_poster` (`id_poster`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `wall_post`
--


-- --------------------------------------------------------

--
-- Table structure for table `wall_post_comment`
--

CREATE TABLE IF NOT EXISTS `wall_post_comment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_wall_post` bigint(20) unsigned NOT NULL,
  `id_poster` bigint(20) unsigned NOT NULL,
  `comment` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `like` int(11) NOT NULL,
  `dislike` int(11) NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `id_wall_post` (`id_wall_post`),
  KEY `id_poster` (`id_poster`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `wall_post_comment`
--


-- --------------------------------------------------------

--
-- Table structure for table `wall_post_permission`
--

CREATE TABLE IF NOT EXISTS `wall_post_permission` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_wall_post` bigint(20) unsigned NOT NULL,
  `id_group` bigint(20) unsigned NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `id_wall_post` (`id_wall_post`),
  KEY `id_group` (`id_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `wall_post_permission`
--


--
-- Constraints for dumped tables
--

--
-- Constraints for table `attribute_ingredients`
--
ALTER TABLE `attribute_ingredients`
  ADD CONSTRAINT `attribute_ingredients_ibfk_2` FOREIGN KEY (`id_ingredient`) REFERENCES `ingredients` (`id`),
  ADD CONSTRAINT `attribute_ingredients_ibfk_1` FOREIGN KEY (`id_type_attribute`) REFERENCES `attribute_ingredients_type` (`id`);

--
-- Constraints for table `city`
--
ALTER TABLE `city`
  ADD CONSTRAINT `city_ibfk_1` FOREIGN KEY (`id_country`) REFERENCES `country` (`id_country`);

--
-- Constraints for table `fridge`
--
ALTER TABLE `fridge`
  ADD CONSTRAINT `fridge_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fridge_ibfk_2` FOREIGN KEY (`id_ingredient`) REFERENCES `ingredients` (`id`);

--
-- Constraints for table `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `groups_ibfk_1` FOREIGN KEY (`id_creator`) REFERENCES `users` (`id`);

--
-- Constraints for table `groups_relations`
--
ALTER TABLE `groups_relations`
  ADD CONSTRAINT `groups_relations_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `groups_relations_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `ingredients_photos`
--
ALTER TABLE `ingredients_photos`
  ADD CONSTRAINT `ingredients_photos_ibfk_1` FOREIGN KEY (`id_ingredient`) REFERENCES `ingredients` (`id`);

--
-- Constraints for table `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_ibfk_1` FOREIGN KEY (`country_origin`) REFERENCES `country` (`id_country`),
  ADD CONSTRAINT `recipes_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD CONSTRAINT `recipe_ingredients_ibfk_1` FOREIGN KEY (`id_recipe`) REFERENCES `recipes` (`id`),
  ADD CONSTRAINT `recipe_ingredients_ibfk_2` FOREIGN KEY (`id_ingredient`) REFERENCES `ingredients` (`id`);

--
-- Constraints for table `recipe_photos`
--
ALTER TABLE `recipe_photos`
  ADD CONSTRAINT `recipe_photos_ibfk_1` FOREIGN KEY (`id_recipe`) REFERENCES `recipes` (`id`);

--
-- Constraints for table `recipe_view_permission`
--
ALTER TABLE `recipe_view_permission`
  ADD CONSTRAINT `recipe_view_permission_ibfk_2` FOREIGN KEY (`id_group`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `recipe_view_permission_ibfk_1` FOREIGN KEY (`id_recipe`) REFERENCES `recipes` (`id`);

--
-- Constraints for table `shoplist`
--
ALTER TABLE `shoplist`
  ADD CONSTRAINT `shoplist_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`country`) REFERENCES `country` (`id_country`),
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`city`) REFERENCES `city` (`id`);
