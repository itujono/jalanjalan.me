<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bookpro.php 27 2012-07-08 17:15:11Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

AImporter::model('roomrate','bustrip', 'roomratelogs','bustrips');
AImporter::helper('bookpro','currency');


class BookProViewRoomRate extends JViewLegacy
{
	
	var $items;



	function display($tpl = null)
	{
		$this->_displayForm($tpl);
	}


	function _displayForm($tpl)
	{
		$input=JFactory::getApplication()->input;
		
		$bustrip_id = $input->get('bustrip_id', '', 'int');
		//hotel
		if($bustrip_id){
			$trip = new BookProModelBusTrip();
			
			$this->obj = $trip->getComplexItem($bustrip_id);
		}
		
		$this->rooms=$this->getRoomSelect($bustrip_id);

		$model = new BookProModelRoomRateLogs();
		$state=$model->getState();
		$state->set('filter.room_id',$bustrip_id);
		$state->set('list.ordering','ID');
		$state->set('list.direction','DESC');
		$this->pagination = $model->getPagination();
		$this->items = $model->getItems();
				 
		parent::display($tpl);
	}
	function getRoomSelect($bustrip_id){
		$param = array('order'=>'lft','order_Dir'=>'ASC');
		$model = new BookProModelBusTrips();
		$state=$model->getState();
		$state->set('list.limit',0);
		$lists=$model->getItems();
		$items = array();
		$items[] = JHtmlSelect::option(0,JText::_('COM_BOOKPRO_SELECT_ROUTE'),'id','title');
		foreach ($lists as $list){
			$title = str_repeat('-', $list->level - 1). $list->title;
			 
			$items[] = JHtmlSelect::option($list->id,$title,'id','title');
		}
		return JHTML::_('select.genericlist', $items, 'room_id', '', 'id', 'title', $bustrip_id);
		//return AHtml::getFilterSelect('room_id', 'COM_BOOKPRO_SELECT_ROOM', $list, $select, '', '', 'id', 'title');
	}
	
	function getDrivers() {
		AImporter::model('customers');
		$config=JBFactory::getConfig();
		$model=new BookProModelCustomers();
		$state=$model->getState();
		$state->set('filter.group_id',$config->get('driver_usergroup'));
		$items=$model->getItems();
		$options[] 	= JHTML::_('select.option',  '', JText::_('COM_BOOKPRO_SELECT_DRIVER'), 'id', 'firstname');
		$options = array_merge($options, $items) ;
		return JHTML::_('select.genericlist', $options, 'driver_id', ' class="input" ', 'id', 'firstname') ;
	
	}
	
	/**
	 *
	 * @param unknown $name
	 * @param unknown $selected
	 * @return Ambigous <s, string>
	 */
	static function getDayWeek($name){
		 AImporter::helper('date');
		$days=DateHelper::dayofweek();
		$daysweek=array();
		foreach ($days as $key => $value)
		{
			$object=new stdClass();
			$object->key=$key;
			$object->value=$value;
			$daysweek[]=$object;
		}
		$selected=array_keys($days);
		return AHtml::checkBoxList($daysweek,$name,'',$selected,'key','value');

	}


}

?>