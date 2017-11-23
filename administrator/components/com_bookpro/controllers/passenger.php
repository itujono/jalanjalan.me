<?php
/**
* @version		$Id: default_controller.php 136 2013-09-24 14:49:14Z michel $ $Revision$ $DAte$ $Author$ $
* @package		Bookpro1
* @subpackage 	Controllers
* @copyright	Copyright (C) 2014, Ngo Van Quan. All rights reserved.
* @license #http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class BookproControllerPassenger extends JControllerForm
{
	public function __construct($config = array())
	{
	
		$this->view_item = 'passenger';
		$this->view_list = 'pmibustrips';
		parent::__construct($config);
	}	
	
}// class
?>