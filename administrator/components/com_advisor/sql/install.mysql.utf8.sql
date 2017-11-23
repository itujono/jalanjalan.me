CREATE TABLE IF NOT EXISTS `#__advisor_step` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idflow` int(11) NOT NULL,
  `idprevstep` int(11) NOT NULL DEFAULT '0',
  `name` varchar(500) DEFAULT NULL,
  `precondition` varchar(1500) DEFAULT NULL,
  `text` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__advisor_solution_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idsolution` int(11) NOT NULL,
  `idstep` int(11) NOT NULL,
  `idoption` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__advisor_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idstep` int(11) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `content` text,
  `value` varchar(2000) DEFAULT NULL,
  `desc` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__advisor_flow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `published` tinyint(4) NOT NULL DEFAULT '0',
  `viewresume` tinyint(4) NOT NULL DEFAULT '0',
  `viewpdf` tinyint(4) NOT NULL DEFAULT '0',
  `container` int(11) NOT NULL DEFAULT '0',
  `containerstep` int(11) NOT NULL DEFAULT '0',
  `containerwidth` varchar(45) NOT NULL DEFAULT '100%',
  `containerstepwidth` varchar(45) NOT NULL DEFAULT '100%',
  `containerstepresume` varchar(45) NOT NULL DEFAULT '100px',
  `containerheight` varchar(45) DEFAULT NULL,
  `title` text,
  `firstpage` longtext,
  `prehtml` longtext,
  `posthtml` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__advisor_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idflow` int(11) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `title` text,
  `content` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__advisor_solution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idproduct` int(11),
  `idhikaproduct` int(11),
  `idvirtueproduct` int(11),
  `idjoomlaproduct` int(11),
  `idflow` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__advisor_stats` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `optionvalues` varchar(500) default NULL,
  `flow` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



