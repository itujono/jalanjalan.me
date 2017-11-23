<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: tours.php 21 2012-07-06 04:06:17Z quannv $
 **/
// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );
class TableJob extends JTable {
	
	function __construct(& $db) {
		parent::__construct ( '#__bookpro_job', 'id', $db );
	}
	
	
}