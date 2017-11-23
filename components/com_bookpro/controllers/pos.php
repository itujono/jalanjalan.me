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

use Joomla\Registry\Registry;

AImporter::helper('bookpro','orderstatus');

class BookProControllerPos extends JControllerLegacy{

	
	public function display($cachable = false, $urlparams = false){
		
		parent::display();

	}
  
	
	function confirm()
	{
		jimport('joomla.log.log');
		JLog::addLogger ( array ('text_file' => 'booking.txt'), JLog::ALL, array ('com_bookpro'));
		
		JLog::add ( new JLogEntry ( 'POS: Starting to save booking:', JLog::INFO,'com_bookpro' ));
		
		AImporter::helper('bus','log','date');
		AImporter::model('roomrates','bustrip');
		
		$config=JComponentHelper::getParams('com_bookpro');
		$app = JFactory::getApplication();
		
		$input = $app->input;
		
		$roundtrip =JFactory::getApplication()->getUserStateFromRequest('filter.roundtrip','filter_roundtrip',0 );
		$this->start =JFactory::getApplication()->getUserStateFromRequest('filter.start', 'filter_start' );
		$this->start=DateHelper::createFromFormat ($this->start )->format ('Y-m-d');
		
		if($roundtrip==1){
			$this->end = $input->get('filter_end' );
			$this->end=DateHelper::createFromFormat ($this->end )->format ('Y-m-d');
		
		}
		$this->adults=JFactory::getApplication()->getUserStateFromRequest ( 'filter.adult', 'filter_adult', array(),'array' );
		
		//start calcuate price
		
		$this->total_pax=0;
		foreach ($this->adults as $key=>$value) {
			$this->total_pax+=$value;
		}
		
		
		$this->bustrip_id= $input->get( 'bustrip_id', 0 );
		$this->boarding_id= $input->get( 'boarding'.$this->bustrip_id, 0 );
		$this->dropping_id= $input->get( 'dropping'.$this->bustrip_id, 0 );
		
		
		$seat = $input->get('seat', '','raw' );
		$seat_arr=explode(',', $seat);
		
		//echo $seat;die;
		
		$chargeInfo=array();
		$tripModel=new BookProModelBusTrip();
		$bustrips=array();
		$subtotal = 0;
		
		$bustrip=$tripModel->getComplexItem($this->bustrip_id,$this->start);
		
		//echo "<pre>";print_r($bustrip);die;
		
		
		$bustrip->depart_date=$this->start;
		$chargeInfo['onward']['id']=$this->bustrip_id;
		//$chargeInfo['onward']['rate_id']=$bustrip->price->id;
		$chargeInfo['onward']['total']=0;
		if($this->boarding_id)	{
			$boarding=$bustrip->stations[$this->boarding_id];
			$bustrip->boarding = $boarding;
			//$chargeInfo['onward']['boarding_id']=$this->boarding_id;
			$chargeInfo['onward']['boarding']=$boarding;
			$chargeInfo['onward']['total']+=$boarding['price']*$total_pax;
		}
		if($this->dropping_id){
			$dropping=$bustrip->stations[$this->dropping_id];
			$bustrip->dropping = $dropping;
			//$chargeInfo['onward']['dropping_id']=$this->dropping_id;
			$chargeInfo['onward']['dropping']=$dropping;
			$chargeInfo['onward']['total']+=$dropping['price']*$total_pax;
		}
		$bustrip->booked_seat = $seat;
		$chargeInfo['onward']['seat']=$seat;
		
		$this->start=$this->start.' '.$bustrip->start_time;
		
		$chargeInfo['onward']['date']=$this->start;
		
		$this->rates=$bustrip->price;
		foreach ($this->adults as $key=>$value) {
				
			foreach ($this->rates as $rate)
				if($rate->pricetype==$key)
					$rate->qty=$value;
					
		}
		
		
		$onward_total=BusHelper::getTotalPrice($this->rates, $roundtrip);
		
		
		
		
		$chargeInfo['onward']['total']+=$onward_total;
		
		$subtotal=$chargeInfo['onward']['total'];
		$bustrips[]=$bustrip;
		
		// Return trip
		if($roundtrip==1){
				
			//echo "die";die;
			$this->return_bustrip_id= $input->get('return_bustrip_id',0);
			$this->listseat= $input->get( 'returnlistseat'.$this->return_bustrip_id, '' );
			$this->return_boarding_id =  $input->get( 'return_boarding'.$this->return_bustrip_id, 0 );
			$this->return_dropping_id =  $input->get( 'return_dropping'.$this->return_bustrip_id, 0 );
			$seat =  $input->get( 'return_seat', '' );
				
				
			$bustrip=$tripModel->getComplexItem($this->return_bustrip_id,$this->end);
			$bustrip->depart_date=$this->end;
				
			$bustrip->booked_seat = $seat;
			$chargeInfo['return']['id']=$this->return_bustrip_id;
			//$chargeInfo['return']['rate_id']=$bustrip->price->id;
			$chargeInfo['return']['seat']=$seat;
			$this->end=$this->end.' '.$bustrip->end_time;
			$chargeInfo['return']['date']=$this->end;
			$chargeInfo['return']['total']=0;
			if($this->return_boarding_id)	{
				$boarding=$bustrip->stations[$this->return_boarding_id];
				$bustrip->boarding = $boarding;
				$chargeInfo['return']['boarding_id']=$this->return_boarding_id;
				$chargeInfo['return']['boarding']=$boarding['price'];
				$chargeInfo['return']['total']+=$boarding['price']*$total_pax;
			}
			if($this->return_dropping_id){
				$dropping=$bustrip->stations[$this->return_dropping_id];
				$bustrip->dropping = $dropping;
				$chargeInfo['return']['dropping_id']=$this->return_dropping_id;
				$chargeInfo['return']['dropping']=$dropping['price'];
				$chargeInfo['return']['total']+=$dropping['price']*$total_pax;
			}
			$bustrips[]=$bustrip;
				
			$rates=$bustrip->price;
				
			foreach ($this->adults as $key=>$value) {
					
				foreach ($rates as $rate)
					if($rate->pricetype==$key)
						$rate->qty=$value;
		
			}
				
			$return_total=BusHelper::getTotalPrice($rates, $roundtrip);
			$chargeInfo['return']['total']+=$return_total;
			
			
			$subtotal+=$chargeInfo['return']['total'];
		}
		
		//echo "<pre>";print_r($subtotal);die;
		
		/*
		$chargeInfo['sum']['pax']=$this->total_pax;
		$chargeInfo['sum']['subtotal']=$subtotal;
		$chargeInfo['sum']['fee']=($subtotal*$config->get('tax'))/100;
		$chargeInfo['sum']['total']=$subtotal+$chargeInfo['sum']['fee'];
		*/
		
		//end calculate price
		
		$total_addon=0;
		$addons=$input->get('addon',array(),'array');
		$booked_addons=array();
		if(count($addons)>0){
			$qty=$addons['qty'];
			$ids=$addons['id'];
				for ($i = 0; $i < count($qty); $i++) {
					if($qty[$i]>0){
						
						$booked_addons[$ids[$i]]=$qty[$i];
						AImporter::model('addon');
						$modelAddon=new BookProModelAddon();
						$item=$modelAddon->getItem($ids[$i]);
					 	$total_addon+=$qty[$i]*$item->price;
					}
					
				}
				$chargeInfo['addon']=$booked_addons;
		}
		if($roundtrip==1){
			
			$total_addon=$total_addon*2;
		}
		
		$chargeInfo['sum']['subtotal']=$subtotal+$total_addon;
		$fee=($chargeInfo['sum']['subtotal']*$config->get('tax'))/100;
		$total=$fee+$chargeInfo['sum']['subtotal'];
		$chargeInfo['sum']['fee']=$fee;
		$chargeInfo['sum']['total']=$total;
		
		JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');
		$person=$input->get('person',array(),'array');
		
		$total_pax=count($person);
		
		$prepare_pax=array();
		
		$ownard_prices =  $this->rates;
		
		//var_dump($ownard_prices);die;
		
		if($roundtrip==1){
					
			$return_prices =  $rates;
		}
		
		
		
		foreach ($person as $key_persons=>$listpersons){
		
			for ($i = 0;$i < count($listpersons);$i++){
				$listpersons[$i]['route_id'] = $chargeInfo['onward']['id'];
				$listpersons[$i]['start'] =$this->start; 
				$listpersons[$i]['seat'] =$seat_arr[$i];
				
				if($roundtrip==1){
					
							$listpersons[$i]['return_route_id'] = $chargeInfo['return']['id'];
							$listpersons[$i]['return_start'] =  $this->end;
				
				}
				
				$stop_price=($chargeInfo['onward']['boarding']['price']+$chargeInfo['onward']['dropping']['price']);
				
				foreach ($ownard_prices as $type){
				
					if($listpersons[$i]['group_id']==$type->pricetype)
							
						if($roundtrip == 1){
							
							$listpersons[$i]['price']=$type->adult_roundtrip;
				
						}else{
						
							$listpersons[$i]['price']=$type->adult;
				
						}
						
				}
				if($roundtrip == 1){
					foreach ($return_prices as $type){
					
						if($listpersons[$i]['group_id']==$type->cgroup_id)
								
						    if($roundtrip == 1){
								
							 $listpersons[$i]['price']+=$type->adult_roundtrip;
					
						}
					
					}
				}
				$listpersons[$i]['price']+=$stop_price;
				
				
				$subtotal+=$listpersons[$i]['price'];
				
				$prepare_pax[]=$listpersons[$i];
			}
		}
		//var_dump($prepare_pax);die;
		
		
		
		
		
		
		AImporter::table ( 'customer','orders' );
		$db = JFactory::getDbo ();
		$user = JFactory::getUser ();
		try {
			
			$db->transactionStart ();
			$user = JFactory::getUser();
			$post= $input->get('jform',array(),'array');
			
			if($post['id']>0){
				
				
				
			}
			
			//var_dump($post);die;
			$account=JBFactory::getAccount();
			
			$post['state'] = 1;
			$post['created'] = JFactory::getDate()->toSql();
			$customerTable=new TableCustomer($db);
			$customerTable->save($post);
			$cid = $customerTable->id;
			
			$params = new JObject();
			$params->chargeInfo=$chargeInfo;
			OrderStatus::init();
			if(isset($chargeInfo['return'])){
				$return_seat=$chargeInfo['return']['seat'];
				
			}
			$order=array(
					'id'=>0,
					'type'=>'BUS',
					'user_id'=>$cid,
					'total'=>$chargeInfo['sum']['total'],
					'total_bg'=>$total_addon,
					'subtotal'=>$chargeInfo['sum']['subtotal'],
					'pay_method'=>'offline',
					'route_id'=>$this->bustrip_id,
					'start'=>$this->start,
					'seat'=>$seat,
					'return_seat'=>$return_seat,
					'pay_status'=>'PENDING',
					'order_status'=>OrderStatus::$PENDING->getValue(),
					'notes'=>$notes,
					'tax'=>$chargeInfo['sum']['fee'],
					'service_fee'=>$chargeInfo['sum']['fee'],
					'created_by'=>$account->id,
					'params'=>json_encode($params)
						
			);
			//var_dump($order);die;
			$orderTable=new TableOrders($db);
			$orderTable->save ($order);
			$orderid=$orderTable->id;
			
			JLog::add(new JLogEntry ('Booking number created:'.$orderTable->order_number, JLog::INFO,'com_bookpro' ));
			
					
			foreach ($prepare_pax as $listperson){
					
					$listperson['order_id'] = $orderid;
					//$listperson['params'] = $rateJson;
					AImporter::table ( 'passenger' );
					$Tablepassenger = new TablePassenger($db);
					
					$Tablepassenger->save ($listperson);
			}
				 
			$db->transactionCommit();
			$redirect='index.php?option=com_bookpro&view=pos&layout=preview&order_id='.$orderid.'&Itemid='.$input->get('Itemid');
			$this->setRedirect($redirect);
			return;
		}catch (Exception $e){
			
			JBLog::addException($e);
			$db->transactionRollback();
			$this->setRedirect(JURI::base(),'index.php?option=com_bookpro&view=pos',$e->getMessage());
		}
	
	}
	
	
	function confirm_driver()
	{
		jimport('joomla.log.log');
		JLog::addLogger ( array ('text_file' => 'booking.txt'), JLog::ALL, array ('com_bookpro'));
	
		JLog::add ( new JLogEntry ( 'POS: Starting to save booking:', JLog::INFO,'com_bookpro' ));
	
		AImporter::helper('bus','log','date');
		AImporter::model('roomrates','bustrip');
	
		$config=JComponentHelper::getParams('com_bookpro');
		$app = JFactory::getApplication();
	
		$input = $app->input;
	
		$roundtrip =JFactory::getApplication()->getUserStateFromRequest('filter.roundtrip','filter_roundtrip',0 );
		$this->start =JFactory::getApplication()->getUserStateFromRequest('filter.start', 'filter_start',JFactory::getDate()->format($config->get('date_day_short')) );
		$this->start=DateHelper::createFromFormat ($this->start )->format ('Y-m-d');
	
		//$this->start=JFactory::getDate()->format('Y-m-d');
	
		$this->adults=JFactory::getApplication()->getUserStateFromRequest ( 'filter.adult', 'filter_adult', array(),'array' );
	
		//start calcuate price
	
		$this->total_pax=0;
		foreach ($this->adults as $key=>$value) {
			$this->total_pax+=$value;
		}
	
	
		$this->bustrip_id= $input->get( 'bustrip_id', 0 );
		
		//var_dump($this->bustrip_id);die;
		$this->boarding_id= $input->get( 'boarding'.$this->bustrip_id, 0 );
		$this->dropping_id= $input->get( 'dropping'.$this->bustrip_id, 0 );
	
	
		//echo "<pre>"; print_r($this->boarding_id);die;
		
		$seat = $input->get('seat', '','raw' );
		
		$seat_arr=explode(',', $seat);
		
	
		//echo $seat;die;
	
		$chargeInfo=array();
		$tripModel=new BookProModelBusTrip();
		$bustrips=array();
		$subtotal = 0;
	
		$bustrip=$tripModel->getComplexItem($this->bustrip_id,$this->start);
		
		
		
		
		$bustrip->depart_date=$this->start;
		$chargeInfo['onward']['id']=$this->bustrip_id;
		//$chargeInfo['onward']['rate_id']=$bustrip->price->id;
		$chargeInfo['onward']['total']=0;
		if($this->boarding_id)	{
			$boarding=$bustrip->stations[$this->boarding_id];
			
			//echo "<pre>"; print_r($boarding);die;
			
			
			$bustrip->boarding = $boarding;
			
			
			
			$chargeInfo['onward']['boarding_id']=$this->boarding_id;
			$chargeInfo['onward']['boarding']=$boarding;
	
			$chargeInfo['onward']['total']+=$boarding['price']*$total_pax;
		}
		if($this->dropping_id){
			$dropping=$bustrip->stations[$this->dropping_id];
			$bustrip->dropping = $dropping;
			$chargeInfo['onward']['dropping_id']=$this->dropping_id;
			$chargeInfo['onward']['dropping']=$dropping;
			$chargeInfo['onward']['total']+=$dropping['price']*$total_pax;
		}
		
		$bustrip->booked_seat = $seat;
		$chargeInfo['onward']['seat']=$seat;
	
		$this->start=$this->start.' '.$bustrip->start_time;
	
		$chargeInfo['onward']['date']=$this->start;
	
		$this->rates=$bustrip->price;
		foreach ($this->adults as $key=>$value) {
	
			foreach ($this->rates as $rate)
				if($rate->pricetype==$key)
					$rate->qty=$value;
						
		}
	
		$onward_total=BusHelper::getTotalPrice($this->rates, $roundtrip);
	
		$chargeInfo['onward']['total']+=$onward_total;
	
		//$bustrip->total = $subtotal;
		$bustrips[]=$bustrip;
	
	
		foreach ($chargeInfo as $info) {
			$subtotal+=$info['total'];
	
		}
	
		$chargeInfo['sum']['pax']=$this->total_pax;
		$chargeInfo['sum']['subtotal']=$subtotal;
		$chargeInfo['sum']['fee']=($subtotal*$config->get('tax'))/100;
		$chargeInfo['sum']['total']=$subtotal+$chargeInfo['sum']['fee'];
	
	
		//end calculate price
	
		$total_addon=0;
		$addons=$input->get('addon',array(),'array');
		$booked_addons=array();
		if(count($addons)>0){
			$qty=$addons['qty'];
			$ids=$addons['id'];
			for ($i = 0; $i < count($qty); $i++) {
				if($qty[$i]>0){
	
					$booked_addons[$ids[$i]]=$qty[$i];
					AImporter::model('addon');
					$modelAddon=new BookProModelAddon();
					$item=$modelAddon->getItem($ids[$i]);
					$total_addon+=$qty[$i]*$item->price;
				}
					
			}
			$chargeInfo['addon']=$booked_addons;
		}
		
		$chargeInfo['sum']['subtotal']+=$total_addon;
		$fee=($chargeInfo['sum']['subtotal']*$config->get('tax'))/100;
		$total=$fee+$chargeInfo['sum']['subtotal'];
		$chargeInfo['sum']['fee']=$fee;
		$chargeInfo['sum']['total']=$total;
	
		JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');
		$person=$input->get('person',array(),'array');
	
		$total_pax=count($person);
	
		$prepare_pax=array();
	
		$ownard_prices =  $this->rates;
	
		//var_dump($chargeInfo);die;
	
		
		$subtotal=0;
	
	
		foreach ($person as $key_persons=>$listpersons){
	
			for ($i = 0;$i < count($listpersons);$i++){
				$listpersons[$i]['route_id'] = $chargeInfo['onward']['id'];
				$listpersons[$i]['start'] =$this->start;
				$listpersons[$i]['seat'] =$seat_arr[$i];
	
				$stop_price=($chargeInfo['onward']['boarding']['price']+$chargeInfo['onward']['dropping']['price']);
	
				foreach ($ownard_prices as $type){
	
					if($listpersons[$i]['group_id']==$type->pricetype){
	
						$listpersons[$i]['price']=$type->adult;
	
					}
				}
				
				$listpersons[$i]['price']+=$stop_price;
	
	
				$subtotal+=$listpersons[$i]['price'];
	
				$prepare_pax[]=$listpersons[$i];
			}
		}
		//var_dump($prepare_pax);die;
	
		$chargeInfo['sum']['pax']=$this->total_pax;
		$chargeInfo['sum']['subtotal']=$subtotal;
		$chargeInfo['sum']['fee']=($subtotal*$config->get('tax'))/100;
		$chargeInfo['sum']['total']=$subtotal+$chargeInfo['sum']['fee'];
	
	
		AImporter::table ( 'customer','orders' );
		$db = JFactory::getDbo ();
		$user = JFactory::getUser ();
		try {
				
			$db->transactionStart ();
			$user = JFactory::getUser();
			$post= $input->get('jform',array(),'array');
				
			if($post['id']>0){
	
	
			}
				
			//var_dump($post);die;
			$account=JBFactory::getAccount();
				
			
			$cid = $account->id;
				
			$params = new JObject();
			$params->chargeInfo=$chargeInfo;
			OrderStatus::init();
			if(isset($chargeInfo['return'])){
				$return_seat=$chargeInfo['return']['seat'];
	
			}
			$order=array(
					'id'=>0,
					'type'=>'BUS',
					'user_id'=>$cid,
					'total'=>$chargeInfo['sum']['total'],
					'total_bg'=>$total_addon,
					'subtotal'=>$chargeInfo['sum']['subtotal'],
					'pay_method'=>'offline',
					'seat'=>$seat,
					'route_id'=>$this->bustrip_id,
					'start'=>$this->start,
					'return_seat'=>$return_seat,
					'pay_status'=>'SUCCESS',
					'order_status'=>'CONFIRMED',
					'notes'=>$notes,
					'tax'=>$chargeInfo['sum']['fee'],
					'service_fee'=>$chargeInfo['sum']['fee'],
					'created_by'=>$account->id,
					'params'=>json_encode($params)
	
			);
			//var_dump($order);die;
			$orderTable=new TableOrders($db);
			$orderTable->save ($order);
			$orderid=$orderTable->id;
				
			JLog::add(new JLogEntry ('Booking number created:'.$orderTable->order_number, JLog::INFO,'com_bookpro' ));
				
				
			foreach ($prepare_pax as $listperson){
					
				$listperson['order_id'] = $orderid;
				$listperson['state']=1;
				//$listperson['params'] = $rateJson;
				AImporter::table ( 'passenger' );
				$Tablepassenger = new TablePassenger($db);
					
				$Tablepassenger->save ($listperson);
			}
				
			$db->transactionCommit();
			$redirect='index.php?option=com_bookpro&view=driver&layout=preview&order_id='.$orderid.'&Itemid='.$input->get('Itemid');
			$this->setRedirect($redirect);
			return;
		}catch (Exception $e){
				
			JBLog::addException($e);
			$db->transactionRollback();
			$this->setRedirect(JURI::base(),'index.php?option=com_bookpro&view=pos',$e->getMessage());
		}
	
	}
	
	function checkin(){
		
		$app = JFactory::getApplication();
		
		$input = $app->input;
		
		$cid=$input->get('cid',array(),'array');
		
		if(count($cid)>0) {
			$db = JFactory::getDbo ();
			$query = $db->getQuery ( true );
			$query->update( '#__bookpro_passenger' )->set('state=1')->where('id IN ('.implode(',', $cid).')');
			$db->setQuery ( $query );
			$db->execute();
		}
		$redirect='index.php?option=com_bookpro&view=driver&layout=passengers&order_id='.$orderid.'&Itemid='.$app->getUserStateFromRequest('driver.Itemid','Itemid');
		$this->setRedirect($redirect);
		
		return;
	}
	
	
	function save()
	{
		jimport('joomla.log.log');
		JLog::addLogger ( array ('text_file' => 'booking.txt'), JLog::ALL, array ('com_bookpro'));
	
		JLog::add ( new JLogEntry ( 'POS: Starting to edit booking:', JLog::INFO,'com_bookpro' ));
	
		AImporter::helper('bus','log','date');
		AImporter::model('roomrates','bustrip');
	
		$config=JComponentHelper::getParams('com_bookpro');
		$app = JFactory::getApplication();
	
		$input = $app->input;
	
		AImporter::table ( 'customer','orders' );
		$db = JFactory::getDbo ();
		$user = JFactory::getUser ();
		try {
				
			$db->transactionStart ();
			$post= $input->get('jform',array(),'array');
				
			$account=JBFactory::getAccount();
			$customerTable=new TableCustomer($db);
			$customerTable->save($post);
			$cid = $customerTable->id;
				
			$params = new JObject();
			//$params->chargeInfo=$chargeInfo;
			
			OrderStatus::init();
			$person=$input->get('person',array(),'array');
			
			
			foreach ($person as $key_persons=>$listpersons){
					$prepare_pax[]=$listpersons;
			}
			AImporter::table ( 'passenger','orders' );
			$newtotal=0;
			
						
			foreach ($prepare_pax as $listperson){
					
				
				$Tablepassenger = new TablePassenger($db);
				$Tablepassenger->save($listperson);
				$newtotal+=$listperson['price'];
			}
			$order_status=$input->get('order_status');
			$order_id=$input->get('order_id');
			$ordersTable = new TableOrders($db);
			$ordersTable->load($order_id);
			$ordersTable->order_status=$order_status;
			if($newtotal !=$ordersTable->subtotal){
				//$ordersTable->subtotal=$newtotal;
				//$ordersTable->tax=($newtotal*$config->get('tax'))/100;
				//$ordersTable->total=$newtotal+$ordersTable->tax;
			}
			$ordersTable->store();
				
			$db->transactionCommit();
			
			$redirect='index.php?option=com_bookpro&view=pos&layout=preview&order_id='.$order_id.'&Itemid='.$input->get('Itemid');
			$this->setRedirect($redirect);
			return;
		}catch (Exception $e){
				
			JBLog::addException($e);
			$db->transactionRollback();
			$this->setRedirect(JRoute::_('index.php?option=com_bookpro&view=pos',$e->getMessage()));
		}
	
	}
	
	function printticket(){
		AImporter::model('passengers');
		AImporter::helper('passenger');
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');
		$route_id=JFactory::getApplication()->input->get('filter_route_id');
		JArrayHelper::toInteger($cid);
		$model=new BookproModelpassengers();
		//$passengers=$model->getItemsByIds($cid,$route_id);
		
		$state=$model->getState();
		$state->set('filter.route_id',$route_id);
		$state->set('filter.ids', implode(',', $cid) );
		
		$passengers=$model->getItems();
		//echo "<pre>";print_r($passengers);die;
		
		
		$view=$this->getView('printticket','html','BookProView');
		
		
		$passengers=PassengertHelper::formatPassenger($passengers);
		
		
		
		$view->passengers=$passengers;
		
		
		$view->display();
	}
	
	
	function search(){
		$app=JFactory::getApplication();
		$order_number = JFactory::getApplication()->input->get('order_number');
		$this->setRedirect('index.php?option=com_bookpro&view=driver&layout=passengers&order_number='.$order_number.'&Itemid='.$app->getUserStateFromRequest('driver.Itemid','Itemid'));
	}



}