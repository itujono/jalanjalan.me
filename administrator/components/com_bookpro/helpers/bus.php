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
class BusHelper {
	
	static function getOptionsDestination() {
		AImporter::model ( 'airports' );
		$model = new BookProModelAirports ();
		$state = $model->getState ();
		$state->set ( 'list.start', 0 );
		$state->set ( 'list.limit', 0 );
		$state->set ( 'list.state', 1 );
		$state->set ( 'list.province', 1 );
		$state->set ( 'list.parent_id', 1 );
		$fullList = $model->getItems ();
		$option = JHtmlSelect::option ( '0', JText::_ ( 'COM_BOOKPRO_SELECT_DESTINATION' ), 'id', 'title' );
		array_unshift ( $fullList, $option );
		return JHtmlSelect::options ( $fullList, 'id', 'title' );
	}
	
	
	static function getRouteDestination($route){
		
		
		
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( '*' );
		$query->from('#__bookpro_dest')->where('id IN ('.$route.')');
		$db->setQuery($query);
		return 	$db->loadObjectList();
		
	}
	
	
	/*
	static function convertArrToObj($arrs) {
		$obj = new stdClass ();
		foreach ( $arrs as $key => $value ) {
			$obj->$key = $value;
		}
		return $obj;
	}*/
	
	static function getPassengerForm($types) {
		$passengers = array ();
		
		
		//var_dump($types);die;
		
		for($i = 0; $i < count($types); $i ++) {
			
			for ($j=0;$j<$types[$i]->qty;$j++) {
				$passenger = new stdClass ();
				$passenger->title = JText::sprintf ( $types[$i]->title, $i + 1 );
				$passenger->group_id = $types[$i]->pricetype;
				$passenger->type = 'adult';
				$passenger->number = $i;
				$passenger->fieldname = 'person['.$types[$i]->pricetype.'][' . $j . ']';
				$passengers [] = $passenger;
			}
		}
		
		return $passengers;
	}
	
	/**
	 *
	 * @param unknown $price        	
	 * @param number $adult        	
	 * @param number $child        	
	 * @param number $infant        	
	 * @param number $roundtrip        	
	 * @return number
	 */
	static function getTotalPrice($price,$roundtrip) {
		
		$total = 0;
			for ($i = 0; $i < count($price); $i++) {
					if($roundtrip==1)
						$total=$total+($price[$i]->adult_roundtrip)*$price[$i]->qty;
					else 
						$total=$total+$price[$i]->adult*$price[$i]->qty;
			
		}
		
		return $total;
	}
	/**
	 *
	 * @param
	 *        	unknown
	 * @return multitype:mixed
	 */
	static function getAgentBustrip($bustrip_id) {
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( 'bustrip.*,agent.id AS agent_id' );
		$query->from ( '#__bookpro_bustrip AS bustrip' );
		$query->join ( 'LEFT', '#__bookpro_bus AS bus ON bus.id = bustrip.bus_id' );
		$query->join ( 'LEFT', '#__bookpro_agent AS agent ON bus.agent_id = agent.id' );
		if ($bustrip_id) {
			$query->where ( 'bustrip.id = ' . $bustrip_id );
		}
		$db->setQuery ( $query );
		$agent = $db->loadObject ();
		return $agent;
	}

	/**
	 * Get Route pair
	 *
	 * @param unknown $from        	
	 * @param unknown $to        	
	 * @return Ambigous <mixed, NULL, multitype:unknown mixed >
	 */
	static function getRoutePair($from, $to) {
		$db = JFactory::getDBO ();
		$query = "SELECT * FROM #__bookpro_dest AS h WHERE h.id =" . $from;
		$query .= ' UNION ';
		$query .= "SELECT * FROM #__bookpro_dest AS h1 WHERE h1.id =" . $to;
		$db->setQuery ( $query );
		return $db->loadObjectList ();
	}

	
	static function getRouteFromParams($params){
		
		AImporter::model('bustrip');
		$model=new BookProModelBusTrip();
		
		if(isset($params['chargeInfo']['onward']['id'])){
			$bustrip=$model->getComplexItem($params['chargeInfo']['onward']['id']);
		}else {
			return null;
		}
		return $bustrip->from_name.'-'.$bustrip->to_name;		
	}
	
	
	
	
	
	
	/*
	 * static function getObjectFullById($id, $date, $roundtrip = 0) { AImporter::model ( 'bustrip' ,'busstops'); $model = new BookProModelBusTrip (); $bustrip = $model->getObjectFullById ( $id ); $price = BusHelper::getPriceBustripDate ( $bustrip->id, $date ); if ($roundtrip == 0) { $last_price = $price->discount ? $price->discount : $price->adult; } else { $last_price = $price->adult_roundtrip; } // $last_price=$bustrip->discount_price?$bustrip->discount_price:$bustrip->price; AImporter::model('busstops'); $price->roundtrip = $roundtrip; $bustrip->last_price = $last_price; $bustrip->price = $price; $bustrip->depart = $date; $stopModel = new BookproModelBusstops(); $state = $stopModel->getState(); $state->set('filter.bustrip_id',$bustrip->id); $state->set('filter.state',1); $stations = $stopModel->getItems(); //$stations = BusHelper::getStations ( $bustrip ); $bustrip->stations = $stations; return $bustrip; }
	 */
	/*
	static function getPassengerPrice($price, $group_id, $roundtrip = 0) {
		$last_price = 0;
		
		if ($roundtrip == 0) {
			if ($group_id == 1) {
				$last_price = $price->discount ? $price->discount : $price->adult;
			} elseif ($group_id == 2) {
				$last_price = $price->child;
			} elseif ($group_id == 3) {
				$last_price = $price->infant;
			}
		} else {
			if ($group_id == 1) {
				$last_price = $price->adult_roundtrip;
			} elseif ($group_id == 2) {
				$last_price = $price->child_roundtrip;
			} elseif ($group_id == 3) {
				$last_price = $price->infant_roundtrip;
			}
		}
		return $last_price;
	}
	*/
	
	/*	
	static function getInFosList($order_id) {
		AImporter::model ( 'bustrip', 'passengers', 'order' );
		
		$orderModel = new BookProModelOrder ();
		$order = $orderModel->getItem ( $order_id );
		if (! empty ( $order->params )) {
			$oparam = JArrayHelper::toObject ( $order->params );
		} else {
			$oparam = new JObject ();
		}
		
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( 'obj.*' );
		$query->from ( '#__bookpro_passenger AS obj' );
		if ($order_id) {
			$query->where ( 'obj.order_id=' . $order_id );
		}
		$query->group ( 'obj.route_id' );
		$db->setQuery ( $query );
		$obj = $db->loadObject ();
		
		$bustrips = array ();
		$bustripModel = new BookProModelBusTrip ();
		$bustrip = $bustripModel->getObjectFullById ( $obj->route_id );
		
		$params = json_decode ( $obj->params, false );
		
		if ($bustrip) {
			
			$price = BusHelper::getPassengerPrice ( $params->rate, ( int ) $obj->group_id );
			$bustrip->price = $price;
			$bustrip->depart_date = $obj->start;
			$bustrip->seat = BusHelper::getCountSeat ( $bustrip->id, $order_id );
			
			$passmodel = new BookproModelpassengers ();
			
			$state = $passmodel->getState ();
			$state->set ( 'filter.order_id', $order_id );
			$state->set ( 'filter.route_id', $bustrip->id );
			
			$passengers = $passmodel->getItems ();
			
			$bustrip->passengers = $passengers;
			$bustrip->return = false;
			if (! empty ( $order->params )) {
				if (isset ( $oparam->boarding->boarding )) {
					$bustrip->boarding = $oparam->boarding->boarding;
				}
				if (isset ( $oparam->dropping->dropping )) {
					$bustrip->dropping = $oparam->dropping->dropping;
				}
			}
			$bustrip->booked_seat = $order->seat;
			
			$bustrips [] = $bustrip;
			if ($obj->return_route_id) {
				$bustripModel = new BookProModelBusTrip ();
				$return_bustrip = $bustripModel->getObjectFullById ( $obj->return_route_id );
				
				// $price = BusHelper::getPriceBustripDate ( $return_bustrip->id, $obj->return_start );
				$return_rate = $params->return_rate;
				$price = BusHelper::getPassengerPrice ( $params->return_rate, ( int ) $obj->group_id );
				
				$last_price = $return_rate->adult_discount ? $return_rate->adult_discount : $return_rate->adult;
				
				$return_bustrip->last_price = $last_price ? $last_price : 0;
				$return_bustrip->price = $price;
				$return_bustrip->depart_date = $obj->return_start;
				
				$return_bustrip->seat = BusHelper::getCountSeat ( $return_bustrip->id, $order_id, true );
				
				$return_bustrip->return = true;
				$passmodel = new BookproModelpassengers ();
				
				$state = $passmodel->getState ();
				$state->set ( 'filter.order_id', $order_id );
				$state->set ( 'filter.return_route_id', $return_bustrip->id );
				$passengers = $passmodel->getItems ();
				$return_bustrip->passengers = $passengers;
				
				if (! empty ( $order->params ) && isset ( $oparam->boarding->return_boarding )) {
					$return_bustrip->boarding = $oparam->boarding->return_boarding;
				} else {
					$return_bustrip->boarding = null;
				}
				if (! empty ( $order->params ) && isset ( $oparam->dropping->return_dropping )) {
					
					$return_bustrip->dropping = $oparam->dropping->return_dropping;
				} else {
					$return_bustrip->dropping = null;
				}
				$return_bustrip->booked_seat = $order->return_seat;
				$bustrips [] = $return_bustrip;
			}
		}
		
		return $bustrips;
		
	}
	*/
	
	static function getBusstopType($name = '',$selected = null){
		$options = array();
		$options[] = JHtmlSelect::option(0,JText::_('COM_BOOKPRO_SELECT_TYPE'));
		$options[] = JHtmlSelect::option(1,JText::_('COM_BOOKPRO_BOARDING'));
		$options[] = JHtmlSelect::option(2,JText::_('COM_BOOKPRO_DROPPING'));
		return JHtmlSelect::genericlist($options, $name,'class="input-medium"','value','text',$selected);
	
	}
	
	
	static function getCountSeat($bustrip_id, $order_id, $return = false) {
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( 'COUNT(obj.group_id)' );
		$query->from ( '#__bookpro_passenger AS obj' );
		$query->where ( 'obj.order_id=' . $order_id );
		if (! $return) {
			if ($bustrip_id) {
				$query->where ( 'obj.route_id=' . $bustrip_id );
			}
		} else {
			if ($bustrip_id) {
				$query->where ( 'obj.return_route_id=' . $bustrip_id );
			}
		}
		$db->setQuery ( $query );
		return $db->loadResult ();
	}
	static function getBustripLocation($bustrip_id, $order_id, $return = false) {
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( 'obj.*' );
		$query->from ( '#__bookpro_passenger AS obj' );
		$query->where ( 'obj.order_id=' . $order_id );
		if (! $return) {
			$query->where ( 'obj.route_id=' . $bustrip_id );
		} else {
			$query->where ( 'obj.return_route_id=' . $bustrip_id );
		}
		$db->setQuery ( $query );
		$locations = array ();
		$items = $db->loadObjectList ();
		foreach ( $items as $item ) {
			if (! $return) {
				$locations [] = $item->seat;
			} else {
				$locations [] = $item->return_seat;
			}
		}
		$location = implode ( ",", $locations );
		return $location;
	}
	
	
	static function getConvertLocationArr($location) {
		$str = str_replace ( array (
				'[',
				']',
				'"' 
		), '', $location );
		$array_deny_select = explode ( ',', trim ( $str ) );
		return $array_deny_select;
	}
	
	
	/**
	 * Get All route in the same code
	 *
	 * @param unknown $bustrip_id        	
	 * @return multitype:stdClass
	 */
	static function getRouteArr($bustrip_id) {
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( 'bustrip.*' );
		$query->from ( '#__bookpro_bustrip AS bustrip' );
		$query->where ( 'id=' . $bustrip_id );
		$db->setQuery ( $query );
		$bustrip = $db->loadObject ();
		
		$arrRoute = explode ( ";", $bustrip->route );
		
		$keyFrom = array_search ( $bustrip->from, $arrRoute );
		$keyTo = array_search ( $bustrip->to, $arrRoute );
		
		$arrFrom = BusHelper::getArrDestRoute ( 0, $keyFrom, $arrRoute );
		$arrTo = BusHelper::getArrDestRoute ( $keyTo, count ( $arrRoute ) - 1, $arrRoute );
		
		$routes = BusHelper::getArrFromTo ( $arrFrom, $arrTo );
		
		return $routes;
	}
	
	private static function getArrFromTo($arrFrom, $arrTo) {
		$arr = array ();
		foreach ( $arrFrom as $from ) {
			foreach ( $arrTo as $to ) {
				$obj = new stdClass ();
				$obj->from = $from;
				$obj->to = $to;
				$arr [] = $obj;
			}
		}
		return $arr;
	}
	
	
	/**
	 * Get bustrip detail
	 *
	 * @param unknown $price_id        	
	 * @return mixed
	 * @author quannv
	 */
	static function getBusDetail($route_id, $date=null,$boarding=null,$dropping=null) {
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( 'bt.id,agent.brandname,bt.code,bt.start_time,bt.end_time,bt.duration')
		->select('dest1.title  AS from_title,dest2.title AS to_title,dest1.code AS from_code,dest2.code AS to_code,bus.title AS bus_name' )
		->from ( '#__bookpro_bustrip AS bt' );
		
		$query->leftJoin ( '#__bookpro_bus AS bus ON bus.id=bt.bus_id' );
		$query->join ( "LEFT", '#__bookpro_dest AS dest1 ON bt.from = dest1.id' );
		$query->join ( "LEFT", '#__bookpro_dest AS dest2 ON bt.to = dest2.id' );
		$query->join ( 'LEFT', '#__bookpro_agent AS agent ON agent.id = bt.agent_id' );
		$query->where('bt.id='.$route_id);
		
		
		//echo $query->dump();
		$db->setQuery ( $query );
		$item = $db->loadObject ();
		
		if($item && $date){
			
			AImporter::model('roomrates');
			$rateModel=new BookProModelRoomRates();
			$state = $rateModel->getState();
			$state->set('filter.bustrip_id',$item->id);
			$state->set('filter.date',$date);
			$state->set('list.limit',null);
			$item->prices =  $rateModel->getItems();
			
			
			AImporter::model('busstop');
			$model=new BookProModelBusstop();
			
			if($boarding){
				
				$item->boarding = $model->getItem($boarding);
			}
			if($dropping){
					
				$item->dropping = $model->getItem($dropping);
			}
			$item->depart = $date;
		}
		return $item;
	}
	
	/**
	 * Non-graphical seat mode
	 * @param Date $depart
	 * @param String $code
	 * @return string
	 */
	static function getBookedNoSeat($depart,$code){
		
		$config=JComponentHelper::getParams('com_bookpro');
		AImporter::helper('orderstatus');
		OrderStatus::init();
		$db = JFactory::getDbo();
		
		//select all route in this code
		//TODO Update for the segment contains segment
		
		$query = $db->getQuery(true);
		$query->select('id')->from('#__bookpro_bustrip')->where('code='.$db->quote($code));
		$db->setQuery($query);
		$bustrips=$db->loadColumn();
		
		$string_ids=implode(',', $bustrips);
		
		//var_dump($bustrips);die;
		$total=0;
		//foreach ($bustrips as $obj_id){
		
			$start = JFactory::getDate($depart)->format('Y-m-d');
			$query = $db->getQuery(true);
			$query->select('count(*)');
			$query->from('#__bookpro_passenger AS obj');
			$query->innerJoin('#__bookpro_orders AS od ON obj.order_id = od.id');
			$query->where('((DATE_FORMAT(obj.start,"%Y-%m-%d")='.$db->quote($start).') AND od.route_id IN ('.$string_ids.')) OR (  
					(DATE_FORMAT(od.return_start,"%Y-%m-%d")='.$db->quote($start).') AND od.return_route_id IN ('.$string_ids.')  )' );
			$query->where(' (obj.route_id IN ('.$string_ids.') OR obj.return_route_id IN ('.$string_ids.'))' );
			$query->where('od.order_status='.$db->quote('CONFIRMED'));
			
			//$sqlt=' AND (od.order_status='.$db->quote(OrderStatus::$CONFIRMED->getValue()).' OR od.order_status='.$db->quote(OrderStatus::$PENDING->getValue()).')';
			//$query->union('SELECT count(*) from #__bookpro_passenger AS obj
				//INNER JOIN #__bookpro_orders AS od ON obj.order_id = od.id WHERE DATE_FORMAT(obj.return_start,"%Y-%m-%d")='.$db->quote(JFactory::getDate($start)->format('Y-m-d')).'
					//AND obj.return_route_id='.(int)$obj_id. $sqlt);
		
			$db->setQuery($query);
			//echo $query->dump();
			
			$total=$db->loadResult();
			
			return $total;
		
		
	}
	
static function getBookedSeatSingleRoute($depart,$id){
		
		$config=JComponentHelper::getParams('com_bookpro');
		AImporter::helper('orderstatus');
		OrderStatus::init();
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		$query->select('od.seat AS aseat');
		$query->from('#__bookpro_passenger AS obj');
		$query->innerJoin('#__bookpro_orders AS od ON obj.order_id = od.id');
		$query->where('DATE_FORMAT(obj.start,"%Y-%m-%d")='.$db->quote($depart));
		$query->where('obj.route_id='.(int)$id);
		$query->where('od.order_status='.$db->quote(OrderStatus::$CONFIRMED->getValue()));
		//echo $query->dump();
		$db->setQuery($query);
		$tmp=$db->loadColumn();
		$results=array();
		if(count($tmp)>0)
			foreach ($tmp as $key=>$value) {
				$results=array_merge($results,explode(',', $value));
			}
			
			
		$query = $db->getQuery(true);
		$query->select('od.seat AS aseat');
		$query->from('#__bookpro_passenger AS obj');
		$query->innerJoin('#__bookpro_orders AS od ON obj.order_id = od.id');
		$query->where('DATE_FORMAT(obj.return_start,"%Y-%m-%d")='.$db->quote($depart));
		$query->where('obj.return_route_id='.(int)$id);
		$query->where('od.order_status='.$db->quote(OrderStatus::$CONFIRMED->getValue()));
		//echo $query->dump();
		$db->setQuery($query);
		$tmp=$db->loadColumn();
		
		if(count($tmp)>0)
			foreach ($tmp as $key=>$value) {
				$results=array_merge($results,explode(',', $value));
			}
			
		return array_unique ($results);
		
	}
	static function getBookedSeat($depart,$code){
	
		$config=JComponentHelper::getParams('com_bookpro');
		AImporter::helper('orderstatus');
		OrderStatus::init();
		$db = JFactory::getDbo();
	
		//select all route in this code
		//TODO Update for the segment contains segment
	
		$query = $db->getQuery(true);
		$query->select('id')->from('#__bookpro_bustrip')->where('code='.$db->quote($code));
		$db->setQuery($query);
		$bustrips=$db->loadColumn();
		
		//echo "<pre>";print_r($bustrips);die;
		
		//$string_ids=implode(',', $bustrips);
		
		$seats=array();
		foreach ($bustrips as $obj_id){
				
			
			$start = $depart;
			$query = $db->getQuery(true);
			$query->select('od.seat AS aseat');
			$query->from('#__bookpro_passenger AS obj');
			$query->innerJoin('#__bookpro_orders AS od ON obj.order_id = od.id');
			$query->where('DATE_FORMAT(obj.start,"%Y-%m-%d")='.$db->quote($start));
			$query->where('obj.route_id='.(int)$obj_id);
				
			
				$query->where('od.order_status='.$db->quote(OrderStatus::$CONFIRMED->getValue()));
				$sqlt=' AND od.order_status='.$db->quote(OrderStatus::$CONFIRMED->getValue());
			
					
			$query->union('SELECT od.return_seat AS aseat from #__bookpro_passenger AS obj
				INNER JOIN #__bookpro_orders AS od ON obj.order_id = od.id WHERE DATE_FORMAT(obj.return_start,"%Y-%m-%d")='.$db->quote($start).'
					AND obj.return_route_id='.(int)$obj_id. $sqlt);
				
			
			$db->setQuery($query);
			//echo $query->dump();
			
			$tmp=$db->loadColumn();
			if(count($tmp)>0)
				$seats= array_merge($seats,$tmp);
			$query->clear();
							
		}
		//echo "<pre>";print_r($seats);die;
		return implode(',', $seats);
			
	
	}
	
	static function getPassengerCountText($adults) {
		
		$result=array();
		
		foreach ($adults as $adult) {
				
			if($adult->qty>0)
				$result[]=$adult->title.': '.$adult->qty;
		
		}
		
		return implode(', ', $result);
		
	}
	static function getPassengerString($adult) {
		$result = array ($adult);
				
		return implode(',', $result);
		
	}
	static function getBusSelectBox($selected, $field = 'bus_id') {
		$model = new BookProModelBuses ();
		$fullList = $model->getItems ();
		$box = JHtmlSelect::genericlist ( $fullList, $field, '', 'id', 'title', $selected );
		return $box;
		// return AHtml::getFilterSelect($field, JText::_('COM_BOOKPRO_SELECT_BUS'), $fullList, $selected, true, '', 'id', 'title');
	}
	static function getStationSelectByType($station, $type) {
		$list = array ();
		for($i = 0; $i < count ( $station ); $i ++) {
			if ($station [$i]->type == $type) {
				$station [$i]->title=DateHelper::formatTime($station[$i]->depart).'-'.$station[$i]->location;
				if ($station [$i]->price) {
					$station [$i]->stoptitle = JText::sprintf ( 'COM_BOOKPRO_BOARRDING_TITLE_PRICE_TXT', $station [$i]->title, CurrencyHelper::displayPrice ( $station [$i]->price ) );
				} else {
					$station [$i]->stoptitle = $station [$i]->title;
				}
				$list [] = $station [$i];
			}
		}
		
		return $list;
	}
	static function getTotalSeat($seatmap){
		
		$seat= json_decode($seatmap);
		$block_type=$seat->block_type;
		$count=0;
		for ($i = 0; $i < count($block_type) ; $i++) {
			
			if ($block_type[$i]=="seat") {
				$count++;
			}
			
		}
		return $count;
	}
	
}