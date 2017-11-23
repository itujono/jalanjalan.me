CREATE TABLE IF NOT EXISTS `#__listmanager_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idlisting` int(11) DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL,
  `username` varchar(250) DEFAULT NULL,
  `dt` datetime DEFAULT NULL,
  `value` longtext,
  `type` int(11) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `idrecord` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__listmanager_acl` (
  `idlisting` int(10) unsigned NOT NULL,
  `idgroup` int(10) unsigned NOT NULL,
  `acl` varchar(500) NOT NULL,
  PRIMARY KEY (`idlisting`,`idgroup`)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__listmanager_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mandatory` tinyint(4) NOT NULL DEFAULT '0',
  `visible` tinyint(4) NOT NULL DEFAULT '0',
  `idlisting` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL COMMENT '0-numero\n1-fecha\n2-lista opciones\n3-boolean\n4-texto\n5-fichero',
  `decimal` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `innername` varchar(150) DEFAULT NULL,
  `limit0` varchar(45) DEFAULT NULL,
  `limit1` varchar(45) DEFAULT NULL,
  `multivalue` longtext,
  `sqltext` varchar(750) DEFAULT NULL,
  `total` tinyint(4) NOT NULL DEFAULT '-1',
  `autofilter` tinyint(4) NOT NULL DEFAULT '-1',
  `showorder` tinyint(4) NOT NULL DEFAULT '0',
  `defaulttext` varchar(250) DEFAULT NULL,
  `validate` varchar(500) DEFAULT NULL,
  `css` varchar(250) DEFAULT NULL,
  `placeholder` varchar(500) DEFAULT NULL,
  `readmore_word_count` int(11) NOT NULL DEFAULT '100',
  `readmore` tinyint(4) NOT NULL DEFAULT '0',
  `exportable` tinyint(4) NOT NULL DEFAULT '0',
  `link_id` int(11) DEFAULT NULL,
  `link_url` varchar(1000) DEFAULT NULL,
  `link_type` int(11) NOT NULL DEFAULT '-1',
  `link_width` varchar(50) NOT NULL DEFAULT '800px',
  `link_height` varchar(50) NOT NULL DEFAULT '400px',
  `link_detail` tinyint(4) NOT NULL DEFAULT 0,
  `bulk`  tinyint(4) NOT NULL DEFAULT 0,
  `searchable`  tinyint(4) NOT NULL DEFAULT 1,
  `searchtype`  tinyint(4) NOT NULL DEFAULT 1,
  `cardview`  tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `LISTING` (`idlisting`)  
) CHARACTER SET utf8 COLLATE utf8_general_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__listmanager_field_multivalue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idfield` int(11) NOT NULL,
  `idobj` int(11) NOT NULL,
  `ord` int(11) NOT NULL,
  `name` varchar(500) DEFAULT NULL,
  `value` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__listmanager_field_view` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idview` int(11) NOT NULL,
  `idfield` int(11) NOT NULL,
  `visible` tinyint(4) NOT NULL DEFAULT '0',
  `autofilter` tinyint(4) NOT NULL DEFAULT '-1',
  `showorder` tinyint(4) NOT NULL DEFAULT '0',
  `filter_type` tinyint(4) NOT NULL DEFAULT '-1',
  `filter_value` varchar(250) DEFAULT NULL,
  `defaulttext` varchar(250) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__listmanager_listing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) DEFAULT NULL,
  `info` text,
  `layout` longtext,
  `detail` longtext,
  `preprint` longtext,
  `postprint` longtext,
  `pdforientation` tinyint(4) NOT NULL DEFAULT '0',
  `date_format` varchar(45) DEFAULT NULL,
  `decimal` varchar(10) DEFAULT '.',
  `thousand` varchar(10) DEFAULT ',',
  `modalform` tinyint(4) NOT NULL DEFAULT '0',
  `savehistoric` tinyint(4) NOT NULL DEFAULT '0',
  `savesearch` tinyint(4) NOT NULL DEFAULT '0',
  `deletehistoric` varchar(200) DEFAULT '0',
  `ratemethod` tinyint(4) NOT NULL DEFAULT '-1',
  `scriptjs` longtext,
  `hidelist` tinyint(4) NOT NULL DEFAULT '0',
  `default_order` varchar(2500) DEFAULT NULL,
  `list_type` int(11) NOT NULL DEFAULT '0',
  `list_columns` int(11) NOT NULL DEFAULT '3',
  `list_height` int(11) NOT NULL DEFAULT '150',
  `list_name_class` varchar(500) NOT NULL DEFAULT 'span5',
  `list_value_class` varchar(500) NOT NULL DEFAULT 'span6',
  `innername` varchar(500) DEFAULT NULL,
  `tool_column_position` int(11) NOT NULL DEFAULT '0',
  `tool_column_name` varchar(2500) DEFAULT NULL,
  `view_toolbar` tinyint(4) NOT NULL DEFAULT '1',
  `view_toolbar_bottom` tinyint(4) NOT NULL DEFAULT '0',
  `view_filter` tinyint(4) NOT NULL DEFAULT '1',
  `view_bottombar` tinyint(4) NOT NULL DEFAULT '1',
  `detail_onclick` tinyint(4) NOT NULL DEFAULT '0',  
  `keyfields` varchar(200) DEFAULT NULL,
  `detailpdf` longtext,
  `detailmode` INT NOT NULL DEFAULT 0,
  `date_format_bbdd` varchar(150) DEFAULT NULL,
  `detailrtf` varchar(1000) DEFAULT NULL,
  `listrtf` varchar(1000) DEFAULT NULL,
  `search_type` tinyint(4) NOT NULL DEFAULT '0',  
  `show_filter` tinyint(4) NOT NULL DEFAULT '0',
  `filter_automatic` tinyint(4) NOT NULL DEFAULT '0',
  `specialfilters` LONGTEXT NOT NULL DEFAULT '',
  `editor` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__listmanager_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idlisting` int(11) NOT NULL,
  `idrecord` int(11) NOT NULL,
  `idfield` int(11) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `iduser` int(11) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `LISTING` (`idlisting`,`idrecord`)
) CHARACTER SET utf8 COLLATE utf8_general_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__listmanager_values` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `idfield` int(11) DEFAULT NULL,
  `idrecord` int(11) NOT NULL,
  `value` longtext,
  PRIMARY KEY (`id`),
  KEY `FIELD` (`idfield`),
  KEY `RECORD_FIELD` (`idfield`,`idrecord`),
  KEY `RECORD` (`idrecord`)
) CHARACTER SET utf8 COLLATE utf8_general_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__listmanager_view` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idlisting` int(11) NOT NULL,
  `filter` varchar(2500) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `comments` longtext,
  `default_order` varchar(2500) DEFAULT NULL,
  `detail`longtext,
  `date_format` varchar(150) DEFAULT NULL,
  `detailpdf`longtext,
  PRIMARY KEY (`id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__listmanager_search` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `idlisting` INT NULL,
  `iduser` INT NULL,
  `searchdatetime` DATETIME NULL,
  `username` VARCHAR(250) NULL,
  `terms` VARCHAR(2500) NULL,
  PRIMARY KEY (`id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci AUTO_INCREMENT=1;

