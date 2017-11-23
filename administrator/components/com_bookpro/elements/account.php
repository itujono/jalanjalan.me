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

class JFormFieldAccount extends JFormFieldList
{
	
	protected function getInput() {
		AImporter::model('customers');
		$config=JBFactory::getConfig();
		$model=new BookProModelCustomers();
		$state=$model->getState();
		$state->set('filter.group_id',$config->get('driver_usergroup'));
		$items=$model->getItems();
		$options[] 	= JHTML::_('select.option',  '', JText::_('COM_BOOKPRO_SELECT_DRIVER'), 'id', 'firstname');
		$options = array_merge($options, $items) ;
		return JHTML::_('select.genericlist', $options, $this->name, ' class="input" ', 'id', 'firstname', $this->value,$this->id) ;
	
	}

	

}

?>