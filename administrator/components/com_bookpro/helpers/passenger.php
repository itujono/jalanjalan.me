<?php



/**

 * @package 	Bookpro

 * @author 		Ngo Van Quan

 * @link 		http://joombooking.com

 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan

 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html

 * @version 	$Id: bookpro.php 80 2012-08-10 09:25:35Z quannv $

 **/



defined('_JEXEC') or die('Restricted access');



class PassengertHelper

{
	static function getListPassengerForBustrip($bustrips,$date){
		AImporter::model('orderinfos','passengers');
		$passengers = array();
		
		foreach ($bustrips as $bustrip){
				 
			$orderinfosModel = new BookProModelOrderInfos();
			$objs = $orderinfosModel->getListsByObj($bustrip->id,$date);
			
			if (count($objs)) {
				foreach ($objs as $obj){
					$passModel = new BookProModelPassengers();
					$lists = array('order_id'=>$obj->order_id);
					$passModel->init($lists);
					$pass = $passModel->getData();
					if (count($pass)) {
						foreach ($pass as $pas){
							$pas->boarding_location = $bustrip->fromName;
							
							$passengers[] = $pas;
						}
					}
					//$pass= $passModel->getObjectOrderId($obj->order_id);
					
				}
			}	 
		}
		return $passengers;
	}
	
	static function getPassengers($user_id,$date,$route_id,$params=array()){
		
		
		$db = JFactory::getDbo ();
		
		/*
		$query1 = $db->getQuery ( true );
		
		$query1->select ( 'bus.title as title_bus,r.*,r.return_start AS start_date,c.title as group_title,rbustrip.start_time,rbustrip.end_time' );
		
		$query1->select ( 'CONCAT(`rdest1`.`title`,' . $db->quote ( '-' ) . ',`rdest2`.`title`) AS triptitle ' );
		
		$query1->select ( 'od.order_number,od.params AS oparams,rbustrip.code AS tripcode,od.return_seat as oseat,od.created AS booking_date,cus.firstname AS agent_name,od.pay_status,od.order_status' );
		
		$query1->from ( '#__bookpro_passenger AS r' );
		
		$query1->join ( 'LEFT', '#__bookpro_cgroup AS c ON c.id = r.group_id' );
		
		$query1->join ( 'LEFT', '#__bookpro_bustrip AS rbustrip ON rbustrip.id = r.return_route_id' );
		
		$query1->join ( 'LEFT', '#__bookpro_dest AS rdest1 ON rbustrip.from = rdest1.id' );
		
		$query1->join ( 'LEFT', '#__bookpro_dest AS rdest2 ON rbustrip.to = rdest2.id' );
		
		$query1->join ( 'LEFT', '#__bookpro_orders AS od ON od.id = r.order_id' );
		
		$query1->join ( 'LEFT', '#__bookpro_customer AS cus ON cus.id = od.created_by' );
		
		$query1->join ( 'LEFT', '#__bookpro_bus as bus ON rbustrip.bus_id=bus.id' );
		
		// $query1->join('LEFT', '#__bookpro_bus_seattemplate as seattemplate ON seattemplate.id=bus.seattemplate_id');
		
		if (! $route_id) {
				
			$query1->where ( 'r.return_route_id IN (' . implode ( ',', $routes ) . ')' );
			
		} else {
				
			$query1->where ( 'r.return_route_id=' . $route_id );
		}
		
		$query1->where ( ' DATE_FORMAT(r.return_start,"%Y-%m-%d")=' . $db->quote ( $date ) );
		*/
		// $query1->where('od.order_status='.$db->quote(OrderStatus::$CONFIRMED->getValue()));
		
		
		$query = $db->getQuery ( true );
		
		$query->select ( 'bus.title as title_bus,l.*,`l`.`start` AS start_date,c.title as group_title,bustrip.start_time,bustrip.end_time' );
		
		$query->select ( 'CONCAT(`dest1`.`title`,' . $db->quote ( '-' ) . ',`dest2`.`title`) AS triptitle ' );
		
		$query->select ( 'od.order_number,od.params as oparams,bustrip.code AS tripcode,od.seat AS oseat,od.created AS booking_date,cus.firstname AS agent_name,od.pay_status,od.order_status' );
		
		$query->from ( '#__bookpro_passenger AS l' );
		
		$query->join ( 'LEFT', '#__bookpro_cgroup AS c ON c.id = l.group_id' );
		
		$query->join ( 'LEFT', '#__bookpro_bustrip AS bustrip ON bustrip.id = l.route_id' );
		
		$query->join ( 'LEFT', '#__bookpro_dest AS dest1 ON bustrip.from = dest1.id' );
		
		$query->join ( 'LEFT', '#__bookpro_dest AS dest2 ON bustrip.to = dest2.id' );
		
		$query->join ( 'inner', '#__bookpro_orders AS od ON od.id = l.order_id' );
		
		$query->join ( 'LEFT', '#__bookpro_customer AS cus ON cus.id = od.created_by' );
		
		$query->join ( 'LEFT', '#__bookpro_bus as bus ON bustrip.bus_id=bus.id' );
		
		// $query->join('LEFT', '#__bookpro_bus_seattemplate as seattemplate ON seattemplate.id=bus.seattemplate_id');
		
		$query->where('od.order_status='.$db->q('CONFIRMED'));
		
				
		$query->where ( 'l.route_id ='.$route_id );
		
		$query->where ( ' DATE_FORMAT(l.start,"%Y-%m-%d")=' . $db->quote ( $date ) );
		
		if($params['order_number']){
			$query->where('od.order_number LIKE '.$db->q('%'.$params['order_number'].'%'));
			
		}
		
		// echo $query->dump();die;
		
		
		// $query->where('od.order_status='.$db->quote(OrderStatus::$CONFIRMED->getValue()));
		
		// $query->unionAll($query1);
		
		//$sql1 = ( string ) $query1;
		
		//$sql = ( string ) $query;
		
		//$usql = "($sql1) UNION ALL ($sql)";
	
		
		$db->setQuery ( $query );
		
		$items = $db->loadObjectList();
		
		
		
		for ($i = 0; $i < count($items); $i++) {
				
			$oparams=json_decode($items[$i]->oparams,true);
			
			$items[$i]->oparams=$oparams;
			
			if(isset($oparams['chargeInfo']['onward']['boarding'])){
				
				$items[$i]->boarding=$oparams['chargeInfo']['onward']['boarding'];
				
				
			}
			
			if(isset($oparams['chargeInfo']['onward']['dropping'])){
			
				$items[$i]->dropping=$oparams['chargeInfo']['onward']['dropping'];
			
			
			}
			
		}
		
		
		
		return $items;
		
		
	}
	
	static function formatPassenger(&$passengers){
		
		AImporter::helper('bus');
		$items=array();
		for ($i = 0; $i < count($passengers); $i++) {
			
			$item=&$passengers[$i];
			
			
			
			$boarding=isset($item->oparams['chargeInfo']['onward']['boarding'])?$item->oparams['chargeInfo']['onward']['boarding']:null;
			$dropping=isset($item->oparams['chargeInfo']['onward']['dropping'])?$item->oparams['chargeInfo']['onward']['dropping']:null;
			
			
			//var_dump($boarding);die;
			$item->bustrip=BusHelper::getBusDetail($item->route_id,null);
			$item->bustrip->boarding=$boarding;
			$item->bustrip->dropping=$dropping;
			$item->roundtrip=0;
			//$item->seat=$item->seat?$item->seat:$item->oparams['chargeInfo']['onward']['seat'];
			
			if($item->return_route_id){
				$boarding=isset($item->oparams['chargeInfo']['return']['boarding'])?$item->oparams['chargeInfo']['return']['boarding']:null;
				$dropping=isset($item->oparams['chargeInfo']['return']['dropping'])?$item->oparams['chargeInfo']['return']['dropping']:null;
				$item->rbustrip=BusHelper::getBusDetail($item->return_route_id);
				$item->rbustrip->boarding=$boarding;
				$item->rbustrip->dropping=$dropping;
				$item->roundtrip=1;
				//$item->return_seat=$item->return_seat?$item->return_seat:$item->oparams['chargeInfo']['return']['seat'];
				
			}
			
			if($item->roundtrip){
				$item->type=JText::_('COM_BOOKPRO_ROUNDTRIP');
			
			}else{
				$item->type=JText::_('COM_BOOKPRO_ONEWAY');
			}
			
			//boarding, dropping
			//echo "<pre>";print_r($item);die;
			/*
			if($item->onward){
				$item->astart=$item->bustrip->start_time;
				$item->aseat=$item->seat?$item->seat:$item->oseat;
			}elseif($route_id==$subject->return_route_id){
				$item->astart=$item->return_start;
				$item->aseat=$item->return_seat?$item->return_seat:$item->oreturn_seat;
			}*/
			/*
			if ($item->onward){
			}
			if (!$item->onward){
				 
				$boarding_id=isset($item->oparams['chargeInfo']['return']['boarding_id'])?$item->oparams['chargeInfo']['onward']['boarding_id']:null;
				$dropping_id=isset($item->oparams['chargeInfo']['return']['dropping_id'])?$item->oparams['chargeInfo']['onward']['dropping_id']:null;
			}
			*/
			
			$items[]=$item;
			
			
		}
		//echo "<pre>";print_r($items);die;
		
		return $items;
		
	}
	
}


?>

