<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php  23-06-2012 23:33:14
 **/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class BookProViewAjaxBustrip extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		$this->setLayout(JFactory::getApplication()->input->get('layout','default'));	
		parent::display($tpl);
	}
		
}
