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
defined ( '_JEXEC' ) or die ( 'Restricted access' );
AImporter::helper ( 'bus', 'date' );
class BookProViewAjaxpos extends JViewLegacy {
	function display($tpl = null) {
		//JSession::checkToken () or jexit ( 'Invalid Token' );
		$this->config = JComponentHelper::getParams ( 'com_bookpro' );
		$app = JFactory::getApplication ();
		$this->resetCart();
		
		$default_date=JFactory::getDate();
		$default_date->add(new DateInterval('P1D'));
		$default_date = $default_date->format('m-d-Y',true);
		
		
		$from = JFactory::getApplication ()->getUserStateFromRequest ( 'filter.from', 'filter_from' );
		$to = JFactory::getApplication ()->getUserStateFromRequest ( 'filter.to', 'filter_to', null );
		$this->roundtrip = JFactory::getApplication ()->getUserStateFromRequest ('filter.roundtrip', 'filter_roundtrip', 0 );
		$this->start = JFactory::getApplication ()->getUserStateFromRequest ( 'filter.start', 'filter_start',$default_date );
		
		//$this->adult = JFactory::getApplication()->getUserStateFromRequest ( 'filter.adult', 'filter_adult', 1 );
		//$this->child = JFactory::getApplication()->getUserStateFromRequest ( 'filter.child', 'filter_child', 0 );
		//$this->senior = JFactory::getApplication()->getUserStateFromRequest ( 'filter.senior', 'filter_senior',0 );
		
		$this->adults= JFactory::getApplication()->getUserStateFromRequest ( 'filter.adult', 'filter_adult', array(),'array' );
		
		$this->total_pax=0;
		foreach ($this->adults as $key=>$value) {
			$this->total_pax+=$value;
		}
		
		$this->start = DateHelper::createFromFormat ($this->start )->format ('Y-m-d');
			
		if($this->roundtrip==1) {
			$this->end = JFactory::getApplication ()->getUserStateFromRequest ( 'filter.end', 'filter_end' );
			$this->end = DateHelper::createFromFormat ( $this->end )->format ( 'Y-m-d' );
		}
		$state = $this->get('State' );
		$state->set ( 'filter.depart_date', $this->start );
		$state->set ( 'filter.from', $from );
		$state->set ( 'filter.to', $to );
		
		
		
		if (JFactory::getDate ()->format ( 'Y-m-d' ) ==  $this->start ) {
			
			$state->set ( 'filter.cutofftime', $this->config->get ( 'cutofftime' ) );
		}
		
		$going_trip = $this->get('Items');
		
		
		$this->going_trips = $going_trip;
		
		
	
		if ($this->roundtrip==1) {
			
			
			$model=new BookProModelBustrips();
			$state = $model->getState();
			$state->set ( 'filter.depart_date', $this->end );
			$state->set ( 'filter.from', $to );
			$state->set ( 'filter.to', $from );
			if (JFactory::getDate ()->format ( 'Y-m-d' ) ==  $this->start ) {
				$state->set ( 'filter.cutofftime', $this->config->get ( 'cutofftime' ) );
			}
			$return_trips = $model->getItems();
			$this->return_trips = $return_trips;
		}
		$this->from_to = BusHelper::getRoutePair ( $from, $to );
		$this->_prepare ();
		parent::display ( $tpl );
	}
	protected function _prepare() {
		$document = JFactory::getDocument ();
		$document->setTitle ( JText::_ ( 'COM_BOOKPRO_SELECT_TRIP' ) );
	}
	private function resetCart(){
	
		JFactory::getApplication()->setUserState('filter.end', '');
		JFactory::getApplication()->setUserState('filter.start', '');
		JFactory::getApplication()->setUserState('filter.roundtrip', '');
		JFactory::getApplication()->setUserState('filter.boarding_id', '');
		JFactory::getApplication()->setUserState('filter.dropping_id', '');
		JFactory::getApplication()->setUserState('filter.return_boarding_id', '');
		JFactory::getApplication()->setUserState('filter.return_dropping_id', '');
		JFactory::getApplication()->setUserState('filter.seat', '');
		JFactory::getApplication()->setUserState('filter.return_seat', '');
	
		JFactory::getApplication()->setUserState('filter.adult', '');
		JFactory::getApplication()->setUserState('filter.child', '');
		JFactory::getApplication()->setUserState('filter.senior', '');
	
	}
}
