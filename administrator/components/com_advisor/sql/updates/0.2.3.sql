
CREATE TABLE   IF NOT EXISTS #__advisor_stats (
  id int(10) unsigned NOT NULL auto_increment,
  optionvalues varchar(500) default NULL,
  flow int(10) unsigned NOT NULL,
  date timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


