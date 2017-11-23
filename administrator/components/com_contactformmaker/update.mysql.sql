CREATE TABLE IF NOT EXISTS `#__contactformmaker_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `public_key` text NOT NULL,
  `private_key` text NOT NULL,
  `map_key` text NOT NULL,
  PRIMARY KEY (`id`)
)  ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;