<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bustrip.php 66 2012-07-31 23:46:01Z quannv $
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );
class BookProControllerGenerate extends JControllerLegacy {
	function __construct($config = array()) {
		parent::__construct ( $config );
	}
	
	/**
	 * Display default view - Airport list
	 */
	function bulksave() {
		AImporter::helper ( 'date' );
		
		$app = JFactory::getApplication ();
		$input = JFactory::getApplication ()->input;
		$weekdays = $input->get ( 'weekday', null, 'array' );
		$startdate = new JDate ( $mainframe->getUserStateFromRequest ( 'rate.startdate', 'startdate', JFactory::getDate ()->format ( 'Y-m-d' ) ) );
		$startclone = clone $startdate;
		$enddate = new JDate ( $mainframe->getUserStateFromRequest ( 'rate.enddate', 'enddate', JFactory::getDate ()->add ( new DateInterval ( 'P60D' ) )->format ( 'Y-m-d' ) ) );
		$starttoend = $startdate->diff ( $enddate )->days;
		
		$agent_id = $input->get ( 'agent_id' );
		$bus_id = $input->get ( 'bus_id' );
		$code = $input->get ( 'code' );
		$dests = $input->get ( 'dest_id', array (), 'array' );
		$depart = $input->get ( 'depart', array (), 'array' );
		$count = count ( $dests );
		$db = JFactory::getDbo ();
		try {
			
			$db->transactionStart ();
			
			for($i = 0; $i < count ( $array_data ); $i ++) {
				
				$src = $array_data [$i];
				$bustrip = new TableBusTrip ( $db );
				$bustrip->bind ( $src );
				$bustrip->store ();
				
				// insert availability
				if ($bustrip->id) {
					$room_id = $bustrip->id;
					for($i = 0; $i <= $starttoend; $i ++) {
						$dw = ( int ) $startdate->format ( 'N' );
						if (in_array ( "$dw", $weekdays )) {
							
							// Delete all existing rate in this duration and fligh route
							// $query = $db->getQuery ( true );
							// $query->delete ( '#__bookpro_roomrate' )->where ( array (
							// 'room_id=' . $room_id,
							// 'date BETWEEN ' . $db->quote ( $startdate ) . ' AND ' . $db->quote ( $enddate )
							// ) );
							// $db->setQuery ( $query );
							// $db->execute ();
							// $query = $db->getQuery ( true );
							$values = array ();
							$query = $db->getQuery ( true );
							$query->insert ( '#__bookpro_roomrate' ); // ON DUPLICATE KEY UPDATE date='2014-11-25 00:00:00', room_id=20000,adult=10011
							$query->columns ( 'room_id,date,adult,child,infant,adult_roundtrip,child_roundtrip,infant_roundtrip,discount' );
							// $query->insert ( '#__bookpro_roomrate' );
							// $query->columns ( 'room_id,date,adult,child,infant,adult_roundtrip,child_roundtrip,infant_roundtrip,discount' );
							
							$temp = array (
									$input->get ( 'room_id' ),
									$db->quote ( $startdate->toSql () ),
									$input->get ( 'adult', 0 ),
									$input->get ( 'child', 0 ),
									$input->get ( 'infant', 0 ),
									
									$input->get ( 'adult_roundtrip', 0 ),
									$input->get ( 'child_roundtrip', 0 ),
									$input->get ( 'infant_roundtrip', 0 ),
									$input->getFloat ( 'discount', 0 ) 
							);
							$values [] = implode ( ',', $temp );
							$query->values ( $values );
							$sql = ( string ) $query;
							
							$updates = array (
									'room_id=' . $room_id,
									'date=' . $db->quote ( $startdate->toSql () ),
									'adult=' . $input->get ( 'adult', 0 ),
									'child=' . $input->get ( 'child', 0 ),
									'infant=' . $input->get ( 'infant', 0 ),
									'adult_roundtrip=' . $input->get ( 'adult_roundtrip', 0 ),
									'child_roundtrip=' . $input->get ( 'child_roundtrip', 0 ),
									'infant_roundtrip=' . $input->get ( 'infant_roundtrip', 0 ),
									'discount=' . $input->get ( 'discount', 0 ) 
							);
							
							$sqltotal = $sql . ' ON DUPLICATE KEY UPDATE ' . implode ( ',', $updates );
							$db->setQuery ( $sqltotal );
							$db->execute ();
						}
						$startdate = $startdate->add ( new DateInterval ( 'P1D' ) );
					}
					
					// save rate log
					
					$params = array (
							'adult' => $input->get ( 'adult', 0 ),
							'child' => $input->get ( 'child', 0 ),
							'infant' => $input->get ( 'infant', 0 ),
							'adult_roundtrip' => $input->get ( 'adult_roundtrip', 0 ),
							'child_roundtrip' => $input->get ( 'child_roundtrip', 0 ),
							'infant_roundtrip' => $input->get ( 'infant_roundtrip', 0 ),
							'start' => $startclone->toSql (),
							'end' => $enddate->toSql (),
							'weekday' => implode ( ',', $weekdays ) 
					);
					
					$query = $db->getQuery ( true );
					$query->insert ( '#__bookpro_roomratelog' );
					
					$query->columns ( 'room_id,params' );
					$reg = new Registry ();
					$reg->loadArray ( $params );
					
					$data = array (
							
							$room_id,
							$db->q ( ( string ) $reg ) 
					);
					$query->values ( implode ( ',', $data ) );
					$db->setQuery ( $query );
					$db->execute ();
				}
			}
			
			// update parent
			$query = $db->getQuery ( true );
			$query->select ( 'id' )->from ( '#__bookpro_bustrip' )->where ( array (
					'`from`=' . $dests [0],
					'`to`=' . $dests [$count - 1],
					'code=' . $db->quote ( $code ) 
			) );
			$db->setQuery ( $query );
			$id = $db->loadResult ();
			
			$query = $db->getQuery ( true );
			$query->update ( '#__bookpro_bustrip' )->set ( array (
					'parent_id=' . $id,
					'level=2' 
			) )->where ( array (
					'`id` <>' . $id,
					'code=' . $db->quote ( $code ) 
			) );
			$db->setQuery ( $query );
			
			$db->execute ();
			
			$db->transactionCommit ();
			$this->setRedirect ( 'index.php?option=com_bookpro&view=bustrips' );
		} catch ( Exception $e ) {
			$db->transactionRollback ();
			$app->enqueueMessage ( $e->getMessage () );
		}
	}
	function save() {
		AImporter::helper ( 'date' );
		$app = JFactory::getApplication ();
		$input = JFactory::getApplication ()->input;
		$agent_id = $input->get ( 'agent_id' );
		$bus_id = $input->get ( 'bus_id' );
		$code = $input->get ( 'code' );
		$dests = $input->get ( 'dest_id', array (), 'array' );
		$depart = $input->get ( 'depart', array (), 'array' );
		$count = count ( $dests );
		$db = JFactory::getDbo ();
		try {
			$db->transactionStart ();
			$query = $db->getQuery ( true );
			$query->insert ( '#__bookpro_bustrip' );
			$query->columns ( 'level,parent_id,code,agent_id,bus_id,`from`,`to`,start_time,end_time,duration,state,route' );
			$values = array ();
			for($i = 0; $i < count ( $dests ); $i ++) {
				
				for($j = $i + 1; $j < count ( $dests ); $j ++) {
					
					$duration = $temp = array (
							1,
							0,
							$db->quote ( $code ),
							$agent_id,
							$bus_id,
							$dests [$i],
							$dests [$j],
							$db->quote ( $depart [$i] ),
							$db->quote ( $depart [$j] ),
							$db->quote ( DateHelper::getDuration ( $depart [$i], $depart [$j] ) ),
							1,
							$db->quote ( implode ( ',', $dests ) ) 
					);
					
					$values [] = implode ( ',', $temp );
				}
			}
			$query->values ( $values );
			
			$db->setQuery ( $query );
			
			$db->execute ();
			
			// update parent
			$query = $db->getQuery ( true );
			$query->select ( 'id' )->from ( '#__bookpro_bustrip' )->where ( array (
					'`from`=' . $dests [0],
					'`to`=' . $dests [$count - 1],
					'code=' . $db->quote ( $code ) 
			) );
			$db->setQuery ( $query );
			$id = $db->loadResult ();
			
			$query = $db->getQuery ( true );
			$query->update ( '#__bookpro_bustrip' )->set ( array (
					'parent_id=' . $id,
					'level=2' 
			) )->where ( array (
					'`id` <>' . $id,
					'code=' . $db->quote ( $code ) 
			) );
			$db->setQuery ( $query );
			
			$db->execute ();
			
			// insert price & availablity
			
			
			//
			
			$db->transactionCommit ();
			
			
			$query=$db->getQuery(true);
			$query->select('id')->from('#__bookpro_bustrip')->where('code='.$db->q($code));
			$db->setQuery($query);
			$ids= $db->loadColumn();
			foreach ($ids as $key){
				$this->saveRate($db,$key);
			}
			
			
			
			$this->setRedirect ( 'index.php?option=com_bookpro&view=bustrips' );
		} catch ( Exception $e ) {
			$db->transactionRollback ();
			$app->enqueueMessage ( $e->getMessage () );
		}
	}
	private function saveRate(&$db,$room_id) {
		$mainframe = JFactory::getApplication ();
		
		$input = JFactory::getApplication ()->input;
		
		$weekdays = $input->get ( 'weekday', null, 'array' );
		
		$startdate = new JDate ( $mainframe->getUserStateFromRequest ( 'rate.startdate', 'startdate', JFactory::getDate ()->format ( 'Y-m-d' ) ) );
		$startclone = clone $startdate;
		$enddate = new JDate ( $mainframe->getUserStateFromRequest ( 'rate.enddate', 'enddate', JFactory::getDate ()->add ( new DateInterval ( 'P60D' ) )->format ( 'Y-m-d' ) ) );
		
		$starttoend = $startdate->diff ( $enddate )->days;
		
		// delete old record
		
		//$room_id = $input->get ( 'room_id' );
		
		
		
		$seat = $input->get ( 'seat' );
		$driver_id = $input->get ( 'driver_id' );
		$pricetype = $input->get ( 'pricetype', null, 'array' );
		
		// var_dump($pricetype);die;
		$adult = $input->get ( 'adult', null, 'array' );
		$adult_roundtrip = $input->get ( 'adult_roundtrip', null, 'array' );
		
		AImporter::table ( 'job' );
		
		$total_sql=array();
		
		
		$query = $db->getQuery ( true );
		$query->insert ( '#__bookpro_roomrate' );
		$query->columns ( 'pricetype,room_id,date,adult,adult_roundtrip' );
		
		for($i = 0; $i <= $starttoend; $i ++) {
			$dw = ( int ) $startdate->format ( 'N' );
			
			for($j = 0; $j < count ( $pricetype ); $j ++) {
				
				if (in_array ( "$dw", $weekdays )) {
					
					// update job;
					
					$job = new TableJob ( $db );
					$job->load ( array (
							'date' => $startdate->toSql (),
							'route_id' => $room_id 
					) );
					if (! $job) {
						$job->id = null;
					}
					$job->route_id = $room_id;
					$job->date = $startdate->toSql ();
					$job->seat = $seat;
					$job->cid = $driver_id;
					$job->state = 1;
					$job->store ();
					
					$values = array ();
					
					
					$temp = array (
							$pricetype [$j],
							$room_id,
							$db->quote ( $startdate->toSql () ),
							$adult [$j],
							$adult_roundtrip [$j] 
					);
					
					$values [] = implode ( ',', $temp );
					$query->values ( $values );
					//$sql = ( string ) $query;
					
					/*
					$updates = array (
							'pricetype=' . $pricetype [$j],
							'room_id=' . $room_id,
							'date=' . $db->quote ( $startdate->toSql () ),
							'adult=' . $adult [$j],
							'adult_roundtrip=' . $adult_roundtrip [$j] 
					);
					
					$sqltotal = $sql . ' ON DUPLICATE KEY UPDATE ' . implode ( ',', $updates );
					*/
					
					//$total_sql[]=$sqltotal;
					
					
					
					//$db->setQuery ( $sqltotal );
					// echo $sqltotal;
					//$db->execute ();
				}
				
			}
			
			
			$startdate = $startdate->add ( new DateInterval ( 'P1D' ) );
		}
		
		
		//$db=JFactory::getDbo();
		//$string_sql=implode(';', $total_sql);
		$db->setQuery ( $query );
		$db->execute ();
		
	}
	private function validate($code) {
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( 'count(bustrip.id)' );
		$query->from ( '#__bookpro_bustrip AS bustrip' );
		$query->where ( 'bustrip.code =' . $db->quote ( $code ) );
		$db->setQuery ( $query );
		$result = $db->loadResult ();
		
		return $result;
	}
	function validate_code() {
		$input = JFactory::getApplication ()->input;
		$code = $input->get ( 'code', '', 'string' );
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( 'count(bustrip.id)' );
		$query->from ( '#__bookpro_bustrip AS bustrip' );
		$query->where ( '`bustrip`.`code` =' . $db->quote ( $code ) );
		$db->setQuery ( $query );
		$result = $db->loadResult ();
		echo $result;
		die ();
	}
	function create() {
		AImporter::model ( 'bustrip' );
		AImporter::helper ( 'generate' );
		$mainframe = JFactory::getApplication ();
		
		$input = JFactory::getApplication ()->input;
		$jdfrom = $input->get ( 'dfrom', array (), 'array' );
		
		if (empty ( $jdfrom )) {
			$jdfrom ['dest_id'] = 0;
		}
		$jgenerate = $input->get ( 'generate', array (), 'array' );
		if (empty ( $jgenerate )) {
			$jgenerate ['code'] = '';
			$jgenerate ['agent_id'] = 0;
			$jgenerate ['bus_id'] = 0;
		} else {
			$ok = $this->validate ( $jgenerate ['code'] );
			
			if (( int ) $ok > 0) {
				$mainframe->enqueueMessage ( JText::_ ( 'COM_BOOKPRO_GENERATE_CODE_INVALID' ), 'error' );
				$mainframe->redirect ( 'index.php?option=com_bookpro&view=generate&task=generate.create' );
			}
		}
		
		$dfrom = json_decode ( json_encode ( $jdfrom ), false );
		
		$jform = json_decode ( json_encode ( $input->get ( 'jform', array (), 'array' ) ), false );
		$jform = GenerateHelper::getFilterRoute ( $jform );
		
		$generate = json_decode ( json_encode ( $jgenerate ), false );
		$bustrips = array ();
		$routes = GenerateHelper::getRoutes ( $dfrom, $jform );
		
		$arrroutes = GenerateHelper::getGenerateRoute ( $dfrom, $jform, $routes, $generate );
		
		$arrjform = $jform;
		$bustrips = array_merge ( $bustrips, $arrroutes );
		while ( count ( $arrjform ) > 1 ) {
			$from = array_shift ( $arrjform );
			$arrroutes = GenerateHelper::getGenerateRoute ( $from, $arrjform, $routes, $generate );
			$bustrips = array_merge ( $bustrips, $arrroutes );
		}
		
		$view = $this->getView ( 'generate', 'html', 'BookProView' );
		$view->assignRef ( 'jdfrom', $dfrom );
		$view->assignRef ( 'jform', $jform );
		$view->assignRef ( 'generate', $generate );
		$view->assignRef ( 'bustrips', $bustrips );
		$view->display ();
	}
	function getStartTime($dfrom, $from) {
		$date = JFactory::getDate ( 'now' )->format ( 'Y-m-d' );
		$date_time = $date . ' ' . $dfrom->start_time;
		
		$start = new JDate ( $date_time );
		
		$interval = 'P' . $from->duration->day . 'DT' . $from->duration->hour . 'H' . $from->duration->minute . 'M';
		
		$start->add ( new DateInterval ( $interval ) );
		
		$start_time = $start->format ( 'H:i' );
		
		$from->start_time = $start_time;
		return $from;
	}
	function setStartTime($dfrom, $jform) {
		$routes = array ();
		
		$arrFrom = $jform;
		foreach ( $arrFrom as $from ) {
			$routes [] = $this->getStartTime ( $dfrom, $from );
		}
		return $routes;
	}
	function getBusTrips($from, $arrTo, $routes, $duration = false) {
		$bustrips = array ();
		for($i = count ( $arrTo ) - 1; $i >= 0; $i --) {
			$arr = $arrTo [$i];
			$obj = new stdClass ();
			
			$obj->from = $from->dest_id;
			$obj->to = $arr->dest_id;
			$obj->start_time = $from->start_time;
			$obj->end_time = $arr->start_time;
			$obj->start_loc = $from->location;
			$obj->end_loc = $arr->location;
			$obj->route = implode ( ";", $routes );
			
			if ($from->duration) {
				
				$start = new JDate ();
				$interval = 'P' . $from->duration->day . 'DT' . $from->duration->hour . 'H' . $from->duration->minute . 'M';
				
				$start->add ( new DateInterval ( $interval ) );
				
				$end = new JDate ();
				$interval = 'P' . $arr->duration->day . 'DT' . $arr->duration->hour . 'H' . $arr->duration->minute . 'M';
				$end->add ( new DateInterval ( $interval ) );
				
				$diff = $end->diff ( $start );
				
				$hour = $diff->d * 24 + $diff->h;
				$hour = $hour < 10 ? "0" . $hour : $hour;
				$minute = $diff->i < 10 ? "0" . $diff->i : $diff->i;
				
				$duration = $hour . ":" . $minute;
				
				$obj->duration = $duration;
				// $obj->duration = JFactory::getDate($time)->format('H:i');
			} else {
				$hour = $arr->duration->day * 24 + $arr->duration->hour;
				$hour = $hour < 10 ? "0" . $hour : $hour;
				$minute = $arr->duration->minute < 10 ? "0" . $arr->duration->minute : $arr->duration->minute;
				$duration = $hour . ":" . $minute;
				$obj->duration = $duration;
			}
			$bustrips [] = $obj;
		}
		
		return $bustrips;
	}
	function getGenerateRoute($from, $arrs, $routes, $generate) {
		$routes = array ();
		AImporter::model ( 'airport', 'agent', 'bus' );
		for($i = count ( $arrs ) - 1; $i >= 0; $i --) {
			$agentModel = new BookProModelAgent ();
			$agentModel->getItem ( $generate->agent_id );
			$busModel = new BookproModelBus ();
			$busModel->getItem ();
			
			$arr = $arrs [$i];
			$route = new JObject ();
			$route->code = $generate->code;
			$route->agent_id = $generate->agent_id;
			
			$route->bus_id = $generate->bus_id;
			
			$route->from = $from->dest_id;
			$fromModel = new BookProModelAirport ();
			$item_from = $fromModel->getItem ( $from->dest_id );
			$route->from_title = $item_from->title;
			
			$route->to = $arr->dest_id;
			$toModel = new BookProModelAirport ();
			$item_to = $toModel->getItem ( $arr->dest_id );
			$route->to_title = $item_to->title;
			$route->route = implode ( ",", $routes );
			$routes [] = $route;
		}
		
		return $routes;
	}
	function getRoutes($from, $arrs) {
		$routes = array (
				$from->dest_id 
		);
		
		foreach ( $arrs as $arr ) {
			array_push ( $routes, $arr->dest_id );
		}
		return $routes;
	}
	function cancel() {
		$this->setRedirect ( 'index.php?option=com_bookpro&view=bustrips' );
	}
}

?>