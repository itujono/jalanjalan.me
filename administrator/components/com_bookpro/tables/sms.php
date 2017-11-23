<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: airline.php  23-06-2012 23:33:14
 **/
defined('_JEXEC') or die('Restricted access');

class TableSms extends JTable
{
	public function __construct(&$db) {
		parent::__construct('#__bookpro_sms', 'id', $db);
	}
  
}

?>