<?php

/**
 * Popup element to select destination.
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: destination.php 44 2012-07-12 08:05:38Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.parameter.element');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldDestination extends JFormFieldList
{
	
	protected function getInput() {
		
		$db =JFactory::getDBO();
		$sql = "SELECT id, title  FROM #__bookpro_dest WHERE id > 1 ORDER BY title ASC ";
		$db->setQuery($sql);
		$options 	= array();
		$options[] 	= JHTML::_('select.option',  '', JText::_('COM_BOOKPRO_SELECT_DESTINATION'), 'id', 'title');
		$options = array_merge($options, $db->loadObjectList()) ;
		return JHTML::_('select.genericlist', $options, $this->name, ' class="inputbox" ', 'id', 'title', $this->value,$this->id) ;
	
	}

	

}

?>