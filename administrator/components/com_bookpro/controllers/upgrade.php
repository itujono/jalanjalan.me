<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: controller.php 104 2012-08-29 18:01:09Z quannv $
 **/
// No direct access to this file
defined ( '_JEXEC' ) or die ();
class BookProControllerUpgrade extends JControllerForm {
	
	/**
	 * Upgrade version
	 * Enter description here .
	 *
	 * ..
	 */
	function upgrade() {
		$app = JFactory::getApplication ();
		$db = JFactory::getDBO ();
		
		try {
			$db->transactionStart ();
			// 21-10-2015
			// $sql[]="ALTER TABLE `#__bookpro_passenger` CHANGE `customer_id` `phone` VARCHAR( 110 ) NULL DEFAULT NULL;";
			// $sql[]="ALTER TABLE `#__bookpro_orders` ADD `start` DATE NOT NULL ,
			// ADD `return_start` DATE NOT NULL ,
			// ADD `route_id` INT NOT NULL ,
			// ADD `return_route_id` INT NOT NULL";
			
			$columns = $db->getTableColumns ( '#__bookpro_customer' );
			if (! in_array ( 'passport', array_keys ( $columns ), true ))
				$sql [] = "ALTER TABLE  `#__bookpro_customer` ADD  `passport` VARCHAR( 110 ) NOT NULL";
			
			$columns = $db->getTableColumns ( '#__bookpro_orders' );
			if (! in_array ( 'created_by', array_keys ( $columns ), true ))
				$sql [] = "ALTER TABLE  `#__bookpro_orders` ADD  `created_by` INT NOT NULL";
			if (! in_array ( 'route_id', array_keys ( $columns ), true ))
				$sql [] = "ALTER TABLE  `#__bookpro_orders` ADD  `route_id` INT NOT NULL";
			if (! in_array ( 'return_route_id', array_keys ( $columns ), true ))
				$sql [] = "ALTER TABLE  `#__bookpro_orders` ADD  `return_route_id` INT NOT NULL";
			if (! in_array ( 'start', array_keys ( $columns ), true ))
				$sql [] = "ALTER TABLE  `#__bookpro_orders` ADD  `start` DATETIME NOT NULL";
			if (! in_array ( 'return_start', array_keys ( $columns ), true ))
				$sql [] = "ALTER TABLE  `#__bookpro_orders` ADD  `return_start` DATETIME NOT NULL";
			
			$columns = $db->getTableColumns ( '#__bookpro_passenger' );
			if (! in_array ( 'state', array_keys ( $columns ), true ))
				$sql [] = "ALTER TABLE `#__bookpro_passenger` ADD `state` INT NOT NULL";
			
			$columns = $db->getTableColumns ( '#__bookpro_bustrip' );
			if (! in_array ( 'title', array_keys ( $columns ), true ))
				$sql [] = "ALTER TABLE `#__bookpro_bustrip` ADD `title` varchar(200) NOT NULL";
				
				// $sql[]="ALTER TABLE `#__bookpro_passenger` CHANGE `documenttype` `email` VARCHAR( 110 ) NOT NULL";
			$sql [] = "ALTER TABLE  `#__bookpro_roomrate` CHANGE  `pricetype` `pricetype` INT NOT NULL";
			$sql [] = "CREATE TABLE IF NOT EXISTS `#__bookpro_addon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `ordering` int(11) NOT NULL,
  `description` text NOT NULL,
  `price` float DEFAULT NULL,
  `state` tinyint(4) DEFAULT NULL,
  `child_price` float DEFAULT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
			
			
	$sql [] = "CREATE TABLE IF NOT EXISTS `#__bookpro_job` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`cid` int(11) NOT NULL,
			`date` datetime NOT NULL,
			`route_id` int(11) NOT NULL,
			`state` int(11) NOT NULL,
			`seat` int(11) NOT NULL,
			 PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
			
					
			for($i = 0; $i < count ( $sql ); $i ++) {
				$db->setQuery ( $sql [$i] );
				$db->execute ();
			}
			$db->transactionCommit ();
			$this->setRedirect ( JUri::base () . 'index.php?option=com_bookpro', 'Successfull' );
		} catch ( Exception $e ) {
			$db->transactionRollback ();
			$this->setRedirect ( JUri::base () . 'index.php?option=com_bookpro', 'update failed:' . $e->getMessage () );
		}
	}
}