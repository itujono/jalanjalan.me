<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bookpro.php 80 2012-08-10 09:25:35Z quannv $
 **/

defined('_JEXEC') or die;
AImporter::model('roomrates','bustrip');

class BookproViewRatedetail extends JViewLegacy
{
	
	
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		$bustrip_id = $input->get('bustrip_id',0);
		$this->date = $input->get('date','');
		$model = new BookProModelRoomRates();
		$bustripModel=new BookProModelBusTrip();
		
		//$state=$model->getState();
		//$state->set('filter.date',$this->date);
		//$state->set('filter.room_id',$bustrip_id);
		//$this->rates = $model->getItems();
		
		$this->bustrip=$bustripModel->getComplexItem($bustrip_id);
		parent::display($tpl);
	}
	
	function getDrivers($selected) {
		AImporter::model('customers');
		$config=JBFactory::getConfig();
		$model=new BookProModelCustomers();
		$state=$model->getState();
		$state->set('filter.group_id',$config->get('driver_usergroup'));
		$items=$model->getItems();
		$options[] 	= JHTML::_('select.option',  '', JText::_('COM_BOOKPRO_SELECT_DRIVER'), 'id', 'firstname');
		$options = array_merge($options, $items) ;
		return JHTML::_('select.genericlist', $items, 'driver_id', ' class="input" ', 'id', 'firstname',$selected) ;
	
	}

	
}