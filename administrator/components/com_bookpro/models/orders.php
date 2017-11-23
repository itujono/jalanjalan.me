<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: orders.php 56 2012-07-21 07:53:28Z quannv $
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );
class BookProModelOrders extends JModelList {
	function __construct() {
		parent::__construct ();
	}
	protected function populateState($ordering = null, $direction = null) {
		AImporter::helper('date');
		// Load the filter state.
		$search = $this->getUserStateFromRequest ( $this->context . '.filter.search', 'filter_search' );
		$this->setState ( 'filter.search', $search );
		
		$pay_status = $this->getUserStateFromRequest ( $this->context . '.filter.pay_status', 'filter_pay_status', null, 'string' );
		$this->setState ( 'filter.pay_status', $pay_status );
		
		$order_status = $this->getUserStateFromRequest ( $this->context . '.filter.order_status', 'filter_order_status', null, 'string' );
		$this->setState ( 'filter.order_status', $order_status );
		
		$user_id = $this->getUserStateFromRequest ( $this->context . '.filter.user_id', 'filter_user_id', null, 'int' );
		$this->setState ( 'filter.user_id', $user_id );
		
		$create_by = $this->getUserStateFromRequest ( $this->context . '.filter.created_by', 'filter_created_by', null, 'int' );
		$this->setState ( 'filter.created_by', $create_by );
		
		
		$agent_id = $this->getUserStateFromRequest ( $this->context . '.filter.agent_id', 'filter_agent_id', null, 'int' );
		$this->setState ( 'filter.agent_id', $agent_id );
		
		
		$group_id = $this->getUserStateFromRequest ( $this->context . '.filter.group_id', 'filter_group_id', null, 'int' );
		$this->setState ( 'filter.group_id', $group_id );
		
		$pay_method = $this->getUserStateFromRequest ( $this->context . '.filter.pay_method', 'filter_pay_method', null, 'int' );
		$this->setState ( 'filter.pay_method', $pay_method );
		
		$route_id = $this->getUserStateFromRequest ( $this->context . '.filter.route_id', 'filter_route_id', null, 'int' );
		$this->setState ( 'filter.route_id', $route_id );
		
		$range = $this->getUserStateFromRequest ( $this->context . '.filter.range', 'filter_range', null, 'string' );
		$this->setState ( 'filter.range', $range );
		
		
		$from_date = $this->getUserStateFromRequest ( $this->context . '.filter.from_date', 'filter_from_date', null, 'string' );
		
		$this->setState ( 'filter.from_date', $from_date );
		
		$to_date = $this->getUserStateFromRequest ( $this->context . '.filter.to_date', 'filter_to_date', null, 'string' );
		
		
		$this->setState ( 'filter.to_date', $to_date );
		
		$date_type = $this->getUserStateFromRequest ( $this->context . '.filter.date_type', 'filter_date_type', null, 'int' );
		$this->setState ( 'filter.date_type', $date_type );
		
		parent::populateState ( 'a.id', 'DESC' );
	}
	function getListQuery() {
		AImporter::helper('date');
		$db = $this->getDbo ();
		
		
		$pQuery = $db->getQuery ( true );
		$pQuery->select ( "count(*)");
		$pQuery->from ( '#__bookpro_passenger as p' );
		$pQuery->where ( array ('p.order_id=a.id'));
		
		
		$query = $db->getQuery ( true );
		$query->select ( 'a.*,CONCAT(`c`.`firstname`," ",`c`.`lastname`) AS ufirstname,c.telephone, c.firstname,c.lastname,c.email,a.start AS depart,('.$pQuery.') AS seats' );
		$query->from ( '#__bookpro_orders AS a' );
		$query->leftJoin ( '#__bookpro_customer AS c ON c.id=a.user_id' );
		$query->select('created_by.firstname AS created_firstname,created_by.lastname AS created_lastname');
		$query->leftJoin ( '#__bookpro_customer AS created_by ON created_by.id=a.created_by' );
		//$query->innerJoin ( '#__bookpro_passenger AS p ON p.order_id=a.id' );
		
		
		$query->select ( 'oneway.start_time,oneway.end_time, dest1.code AS from_code,dest2.code as to_code,`dest1`.`title` as `fromName`, `dest2`.`title` as `toName`' );
		$query->innerJoin('#__bookpro_bustrip AS oneway ON oneway.id=a.route_id' );
		$query->innerJoin ( '#__bookpro_dest AS `dest1` ON `oneway`.`from` = `dest1`.`id`' );
		$query->innerJoin ( '#__bookpro_dest AS `dest2` ON `oneway`.`to` = `dest2`.`id`' );
		
		
		$query->select ( 'ret.start_time AS rstart_time,ret.end_time AS rend_time,rdest1.code AS rfrom_code,rdest2.code as rto_code,rdest1.title as rfromName, rdest2.title as `rtoName`' );
		$query->leftJoin('#__bookpro_bustrip AS ret ON ret.id=a.return_route_id' );
		$query->leftJoin ( '#__bookpro_dest AS rdest1 ON ret.from = rdest1.id' );
		$query->leftJoin ( '#__bookpro_dest AS rdest2 ON ret.to = rdest2.id' );
		
		//$query->select
		//$query->innerJoin ( '#__bookpro_bustrip AS b ON b.id=p.route_id' );
		// Filter by search in title
		$search = $this->getState ( 'filter.search' );
		if (! empty ( $search )) {
			if (stripos ( $search, 'id:' ) === 0) {
				$query->where ( 'a.id = ' . ( int ) substr ( $search, 3 ) );
			} else {
				$search = $db->quote ( '%' . $db->escape ( $search, true ) . '%' );
				$query->where ( '(c.firstname LIKE ' . $search . '  OR c.lastname LIKE ' . $search . ')' );
			}
		}
		
		//$group_id = $this->getState ( 'filter.group_id' );
		
		//if ($group_id) {
			$user_id = $this->getState ( 'filter.user_id' );
			if ($user_id) {
				$query->where ( 'a.user_id=' . $user_id );
			}
		//}
		
		$created_by = $this->getState ( 'filter.created_by' );
		if ($created_by) {
			$query->where ( 'a.created_by=' . $created_by );
		}	
		
		$pay_method = $this->getState ( 'filter.pay_method' );
		if($pay_method==1){
			$query->where('a.pay_method like '.$db->q('%offline%'));
		}else if($pay_method==2){
						
			$query->where('a.pay_method not like '.$db->q('offline%'));
		}
		
			
		$pay_status = $this->getState ( 'filter.pay_status' );
		if ($pay_status) {
			$query->where ( 'a.pay_status LIKE ' . $db->quote ( '%' . $pay_status . '%' ) );
		}
		
		$order_status = $this->getState ( 'filter.order_status' );
		if ($order_status) {
			$query->where ( 'a.order_status LIKE ' . $db->quote ( '%' . $order_status . '%' ) );
		}
		
		$type = $this->getState ( 'filter.type' );
		if ($type) {
			$query->where ( 'a.type=' . $db->quote ( $type ) );
		}
		
		
		$agent_id = $this->getState ( 'filter.agent_id' );
		if ($agent_id) {
			$query->where ( 'oneway.agent_id=' . $db->quote ( $agent_id ) );
		}
		

		$route_id = $this->getState ( 'filter.route_id' );
		$depart_date= $this->getState ( 'filter.depart_date');
		
		if ($route_id && $depart_date) {
				
				
				
			$query->where ( '(( a.route_id=' . $route_id .' OR oneway.parent_id='.$route_id.') AND (DATE_FORMAT(a.start,"%Y-%m-%d")='.$db->q($depart_date).')) OR
			
			(( a.return_route_id=' . $route_id .' OR ret.parent_id='.$route_id.') AND (DATE_FORMAT(a.return_start,"%Y-%m-%d")='.$db->q($depart_date).'))' );
				
				
		}
		
		
		
		$route_ids = $this->getState ( 'filter.route_ids' );
		
		if ($route_ids) {
			$query->where ( ' a.route_id IN (' . implode(',', $route_ids) .') OR a.return_route_id IN ('.implode(',', $route_ids) .')' );
		}
		
		
		
		$range = $this->getState ( 'filter.range' );
		
		$from_date = $this->getState ( 'filter.from_date');
		
		if ($from_date){
				
			$from_date = JFactory::getDate(DateHelper::createFromFormat($from_date)->getTimestamp())->format('Y-m-d 00:00:00');
				
		}
		$to_date = $this->getState ( 'filter.to_date' );
		if ($to_date){
			$to_date = JFactory::getDate(DateHelper::createFromFormat($to_date)->getTimestamp())->format('Y-m-d 23:59:59');
		}
		
		//
		
		// Apply the range filter.
		if ($range) {
			// Get UTC for now.
			$dNow = new JDate ();
			$dStart = clone $dNow;
			
			switch ($range) {
				case 'past_week' :
					$dStart->modify ( '-7 day' );
					break;
				
				case 'past_1month' :
					$dStart->modify ( '-1 month' );
					break;
				
				case 'past_3month' :
					$dStart->modify ( '-3 month' );
					break;
				
				case 'past_6month' :
					$dStart->modify ( '-6 month' );
					break;
				
				case 'today' :
					// Ranges that need to align with local 'days' need special treatment.
					$app = JFactory::getApplication ();
					$offset = $app->get ( 'offset' );
					
					// Reset the start time to be the beginning of today, local time.
					$dStart = new JDate ( 'now', $offset );
					$dStart->setTime ( 0, 0, 0 );
					
					
					// Now change the timezone back to UTC.
					//$tz = new DateTimeZone ( 'GMT' );
				//	$dStart->setTimezone ( $tz );
					
					$dStart=JFactory::getDate(JHtml::_('date',$dStart,'Y-m-d'));
					break;
			}
			
			if ($this->getState ( 'filter.date_type' )) {
				$query->where ( 'a.created >= ' . $db->quote ( $dStart->format ( 'Y-m-d' ) ) . ' AND a.created <= ' . $db->quote ( $dNow->format ( 'Y-m-d 23:59:59' ) ) );
			} else {
				$query->having ( 'a.start >= ' . $db->quote ( $dStart->format ( 'Y-m-d' ) ) . ' AND a.start <= ' . $db->quote ( $dNow->format ( 'Y-m-d 23:59:59' ) ) );
			}
		}else if($from_date && $to_date ){
			
			//$from_date=JFactory::getDate($from_date);
			//$to_date=JFactory::getDate($to_date);
			
			if ($this->getState ( 'filter.date_type' )) {
				$query->where ( 'a.created >= ' . $db->quote ( $from_date ) . ' AND a.created <= ' . $db->quote ( $to_date ) );
			} else {
				$query->having ( '((a.start >= ' . $db->quote ( $from_date ) . ' AND a.start <= ' . $db->quote ( $to_date ) .') OR (a.return_start >= ' . $db->quote ( $from_date ) . ' AND a.return_start <= ' . $db->quote ( $to_date ) .'))');
			}
			
		}
		
		$orderCol = $this->state->get ( 'list.ordering', 'id' );
		$orderDirn = $this->state->get ( 'list.direction', 'DESC' );
		if (empty ( $orderCol ))
			$orderCol = 'id';
		if (empty ( $orderDirn ))
			$orderDirn = 'DESC';
		$query->order ( $db->escape ( $orderCol . ' ' . $orderDirn ) );
		$query->group ( 'a.id' );
		//echo $query->dump();
		return $query;
	}
	
	public function getItems(){
		
		$items= parent::getItems();
		
		foreach ($items as $item) {
			
			$params=json_decode($item->params,true);
			
			if(isset($params['onward']['boarding'])){
				$item->start_time=$params['onward']['boarding']['depart'];
				
			}
			
			if(isset($params['onward']['dropping'])){
				$item->end_time=$params['onward']['dropping']['depart'];
			
			}
			
			if(isset($params['return']['boarding'])){
				$item->rstart_time=$params['return']['boarding']['depart'];
			
			}
				
			if(isset($params['return']['dropping'])){
				$item->rend_time=$params['return']['dropping']['depart'];
					
			}
			
			
		}
		
		return $items;
		
		
	}
	
	
}

?>