<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
defined('_JEXEC') or die('Restricted access');

class BookProModelBustrips extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
					'l.id',
					'l.title',
			);
		}

		parent::__construct($config);
	}

	
	protected function populateState($ordering = null, $direction = null)
	{
		parent::populateState();
		$app = JFactory::getApplication();
			
		// Load the filter state.
		$depart_date = $this->getUserStateFromRequest($this->context . '.filter.depart_date', 'filter_depart_date');
		$this->setState('filter.depart_date', $depart_date);
			
		$from = $this->getUserStateFromRequest($this->context . '.filter.from', 'filter_from');
		$this->setState('filter.from', $from);
			
		$to = $this->getUserStateFromRequest($this->context . '.filter.to', 'filter_to', 0, 'int');
		$this->setState('filter.to', $to);
		
		$bus_id = $this->getUserStateFromRequest($this->context . '.filter.bus_id', 'filter_bus_id', 0, 'int');
		$this->setState('filter.bus_id', $bus_id);
		
		$agent_id = $this->getUserStateFromRequest($this->context . '.filter.agent_id', 'filter_agent_id', 0, 'int');
		$this->setState('filter.agent_id', $agent_id);
		
		$cutofftime = $this->getUserStateFromRequest($this->context . '.filter.cutofftime', 'filter_cutofftime');
		$this->setState('filter.cutofftime', $cutofftime);
		
				
		$value = $app->getUserStateFromRequest($this->context.'.ordercol', 'filter_order', $ordering);
		$this->setState('list.ordering', $value);
		$value = $app->getUserStateFromRequest($this->context.'.orderdirn', 'filter_order_Dir', $direction);
		$this->setState('list.direction', $value);
		parent::populateState('a.lft', 'asc');
			
	}
	

	function getListQuery()
	{
		
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$depart_date = $this->getState('filter.depart_date');
		
		if ($depart_date) {
			
			//echo $depart_date;
			//$depart_date=JFactory::getDate($depart_date)->format('Y-m-d');
			//$depart_date = JFactory::getDate($depart_date)->format(DateHelper::getConvertDateFormat('P'));
			//$depart_date1=DateHelper::createFromFormat($depart_date);
			
			//var_dump($depart_date1);die;
			
			
		}
		
		
		$config=JBFactory::getConfig();
		
		
		//$subQuery = $db->getQuery(true);
		//
		//$subQuery->select('rate.*');
		//$subQuery->from('#__bookpro_roomrate AS rate');
		//$subQuery->where('DATE_FORMAT(rate.date,"%Y-%m-%d")='.$db->quote($depart_date));
		//$subQuery->order('rate.adult ASC LIMIT 0,1');
		
		$query->select('bustrip.*, CONCAT(DATE_FORMAT(rate.date,"%Y-%m-%d")," ",bustrip.start_time) AS full_date,`agent`.`brandname` AS `brandname`,agent.company,`agent`.`image` AS `agent_logo`, `bus`.`id` AS `bus_id_`,  `dest1`.`title` as `fromName`, `dest2`.`title` as `toName`');
		
		$query->select('`bus`.`seat` AS `bus_seat`,  `bus`.`image` as `bus_image`, `bus`.`title` as `bus_name`, `bus`.`desc` as `bus_sum`,CONCAT(`dest1`.`title`,'.$db->quote('-').',`dest2`.`title`) AS title,`bustrip`.`id` AS b_id');
		
		$query->from('#__bookpro_bustrip AS bustrip');
		$query->innerJoin('#__bookpro_dest AS `dest1` ON `bustrip`.`from` = `dest1`.`id`');
		$query->innerJoin('#__bookpro_dest AS `dest2` ON `bustrip`.`to` = `dest2`.`id`');
		$query->innerJoin('#__bookpro_bus AS `bus` ON `bus`.`id` = `bustrip`.`bus_id`');
		$query->innerJoin('#__bookpro_agent AS agent ON `agent`.`id` = `bustrip`.`agent_id`');
		$query->innerJoin('#__bookpro_roomrate AS rate ON rate.room_id=bustrip.id');
		//$query->join('inner', '('.$subQuery->__toString().') AS r ON r.room_id = bustrip.id');
		
		if(!$config->get('no_seat')){
			$query->select('`seattemplate`.`id` AS `seattemplate_id`,`seattemplate`.`block_layout` AS `block_layout`');
			$query->select('`upperseattemplate`.`block_layout` AS `upper_block_layout`');
			$query->leftJoin('#__bookpro_bus_seattemplate AS `seattemplate` ON `seattemplate`.`id` = `bus`.`seattemplate_id`');
			$query->leftJoin('#__bookpro_bus_seattemplate AS `upperseattemplate` ON `upperseattemplate`.`id` = `bus`.`upperseattemplate_id`');
		}
		if ($depart_date) {
			$query->where('DATE_FORMAT(rate.date,"%Y-%m-%d")='.$db->quote($depart_date));
		
		}
		$from = $this->getState('filter.from');
		if ($from){
			
			$query->where('bustrip.from='.$from);
		}
		$to = $this->getState('filter.to');
		if ($to){
			
			$query->where('bustrip.to='.$to);
		}
		$bus_id = $this->getState('filter.bus_id');
		if ($bus_id){
			
			$query->where('bustrip.bus_id='.$bus_id);
		}
		$agent_id = $this->getState('filter.agent_id');
		if ($agent_id){
			
			$query->where('bus.agent_id='.$agent_id);
		}
		
		$driver_id = $this->getState('filter.driver_id');
		if ($driver_id){
				
			$query->where('bustrip.driver_id='.$driver_id);
		}
		
		
		$ids = $this->getState ( 'filter.ids' );
		if ($ids) {
			$query->where ( 'bustrip.id IN (' . $ids . ')' );
		}
		
		//
		$cutofftime = $this->getState('filter.cutofftime');
		if($cutofftime){
			
			$timezone=new DateTimeZone(JFactory::getApplication()->get('offset'));
			//echo JFactory::getDate('now', $timezone)->format('Y-m-d H:i',true);die;
			
			$current_date=JFactory::getDate('now', $timezone)->add(new DateInterval('PT'.$cutofftime.'M'))->format('Y-m-d H:i:s',true);
			$query->where('CONCAT(DATE_FORMAT(rate.date,"%Y-%m-%d")," ",bustrip.start_time)  > '.$db->q($current_date));
			
			//$query->where('DATE_FORMAT(DATE_ADD(now(), INTERVAL '.$cutofftime.' MINUTE),"%H:%i") < `bustrip`.`start_time`');
		}
		//
		$query->where('bustrip.state = 1');
		//$query->having('`r`.`adult` > 0');
		$query->order('bustrip.start_time ASC');
		
		$query->group('bustrip.id');
		//echo $query->dump();
		return $query;
	}
	function getItems(){
		$items = parent::getItems();
		
		$config=JBFactory::getConfig();
		$depart_date = $this->getState('filter.depart_date');
		AImporter::helper('bus');
		AImporter::model('busstops','roomrates');
		$results=array();
		foreach ($items as $item){
			$item->depart_date=$depart_date;
			$model = new BookproModelBusstops();
			$state = $model->getState();
			$state->set('filter.bustrip_id',$item->id);
			$state->set('list.limit',100);
			$item->stations =  $model->getItems();
			
			
			$rateModel=new BookProModelRoomRates();
			$state = $rateModel->getState();
			$state->set('filter.room_id',$item->id);
			$state->set('filter.date',$depart_date);
			$state->set('list.limit',100);
			$item->prices =  $rateModel->getItems();
			if(count($item->prices)>0){
				if($config->get('no_seat')){
					
					$item->booked_seat_location = BusHelper::getBookedNoSeat ($depart_date, $item->code);
					
					
				
					
					$item->avail=$item->bus_seat-$item->booked_seat_location;
					
					//echo "<pre>";print_r($item);die;
					
				}else{
					
					$item->booked_seat_location = BusHelper::getBookedSeat($depart_date, $item->code);
					
					//echo $item->booked_seat_location;
					//die;
				}
				$results[]=$item;
				
			}
		}
		
		return $results;
	}
	
	
}