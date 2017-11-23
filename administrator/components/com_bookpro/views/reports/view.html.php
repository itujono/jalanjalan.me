<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 47 2012-07-13 09:43:14Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');


//import needed JoomLIB helpers
AImporter::helper( 'bookpro', 'bus');
AImporter::model('buses','airports','agents','bustrips');

class BookProViewReports extends JViewLegacy
{
	protected $items;
	function display($tpl = null)
	{
		$default=JFactory::getApplication()->getUserStateFromRequest('reports.depart', 'depart',JFactory::getDate()->format('Y-m-d'));
		$model=new BookProModelBusTrips();
		$this->state = $model->getState();
		$this->state->set('filter.price',1);
		$this->state->set('filter.state',1);
		$this->state->set('filter.depart_date',$default);
		$this->items = $model->getItems();
        $this->pagination = $model->getPagination();
        
		$airportFrombox=$this->getDestinationSelectBox($this->state->get('filter.from'),'filter_from',JText::_('COM_BOOKPRO_SELECT_FROM'));
		$airportTobox=$this->getDestinationSelectBox($this->state->get('filter.to'),'filter_to',JText::_('COM_BOOKPRO_SELECT_TO'));
		$this->dfrom=$airportFrombox;
		$this->dto=$airportTobox;
		$this->bus=BusHelper::getBusSelectBox($this->state->get('filter.bus_id'),'filter_bus_id');
		$this->agents=$this->getAgentSelectBox($this->state->get('filter.agent_id','filter_agent_id'));
		$this->addToolbar();
		parent::display($tpl);
		 
	}
	
	function getDestinationSelectBox($select, $field = 'filter_from',$text)
	{
		AImporter::model('airports');
    	$model = new BookProModelAirports();
    	
    	$state=$model->getState();
    	$state->set('list.start',0);
    	$state->set('list.limit', 0);
    	$state->set('list.state', 1);
    	$state->set('list.province', 1);
    	$state->set('list.parent_id', 1);
    	$fullList = $model->getItems();
       
		return AHtml::getFilterSelect($field, $text, $fullList, $select, true, '', 'id', 'title');
	}
	function getAgentSelectBox($select,$field="filter_agent_id"){
		$model=new BookProModelAgents();
		$items=$model->getItems();
		return AHtml::getFilterSelect($field, JText::_('COM_BOOKPRO_SELECT_AGENT'), $items, $select, true, '', 'id', 'brandname');
	}
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('Reports'), 'chart');
		JToolBarHelper::cancel();
	}
}

?>