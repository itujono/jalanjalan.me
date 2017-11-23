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

class BookProControllerBus extends JControllerLegacy{

	public function BookProControllerBus(){
		parent::__construct();
		
	}
	public function display($cachable = false, $urlparams = false){
		
		parent::display();

	}
	
	function ticket(){
	
	
		AImporter::model('bustrips','bustrip','passengers','order');
		$order_number = JRequest::getVar('order_number','');
		$order = new BookProModelOrder();
		$this->order = $order->getByOrderNumber($order_number);	
		if ($this->order->id) {
			$link = 'index.php?option=com_bookpro&view=ticket&layout=ticket&order_number='.$order_number.'&Itemid='.JRequest::getVar('Itemid');
			$this->setRedirect($link,false);
		}else {
			$link = 'index.php?option=com_bookpro&view=ticket&Itemid='.JRequest::getVar('Itemid');
			$msg = JText::_('COM_BOOKPRO_TICKET_INVALID');
			$this->setRedirect($link,$msg);
		}
	
	}
	function smscron(){
		$log=JLog::getInstance('cron.txt');
		$log->addEntry(array('comment'=>'SMS cron start'));
	}
	
	function listdestination()
	{
		$from=JRequest::getVar('from',0);
		$db = JFactory::getDBO();
		$query =$db->getQuery(true);
		$query->select('f.to AS `key` ,`d2`.`title` AS `title`,`d2`.`ordering` AS `t_order`');
		$query->from('#__bookpro_bustrip AS f');
		$query->leftJoin('#__bookpro_dest AS d2 ON f.to =d2.id');
		$query->where(array('f.from='.$from,'f.state=1'));
		$query->group('f.to');
		$query->order('t_order');
		$sql = (string)$query;
		$db->setQuery($sql);
		$dests = $db->loadObjectList();
			
		$return = "<?xml version=\"1.0\" encoding=\"utf8\" ?>";
		$return .= "<options>";
		$return .= "<option id='0'>".JText::_( 'COM_BOOKPRO_TO' )."</option>";
		if(is_array($dests)) {
			foreach ($dests as $dest) {
				$return .="<option id='".$dest->key."'>".JText::_($dest->title)."</option>";
			}
		}
		$return .= "</options>";
		echo $return;
	}

	/**
	 * Find destination for ajax call
	 */
	function findDestination()
	{
		$from=JFactory::getApplication()->input->getInt('desfrom');
		$db = JFactory::getDBO();
		$query =$db->getQuery(true);
		$query->select('d2.id AS code ,d2.title AS title');
		$query->from('#__bookpro_bustrip AS f');
		$query->leftJoin('#__bookpro_dest AS d2 ON f.to =d2.id');
		$query->where(array('f.from='.$from,'f.state=1'));
		$query->group('f.to');
		$query->order('title');
		$sql = (string)$query;
		$db->setQuery($sql);
		$dests = $db->loadObjectList();
			
		$return = '<option value="">'.JText::_('COM_BOOKPRO_BUSTRIP_TO').'</option>';
		if(is_array($dests)) {
			foreach ($dests as $dest) {
				$return .="<option value='".$dest->code."'>".$dest->title."</option>";
			}
		}
		echo trim($return);
		die();

	}
	/**
	 * Save booking of POS
	 * 
	 */
  	
  	/**
  	 * Confirm online booking 
  	 * 
  	 */
	function confirm()
	{
		jimport('joomla.log.log');
		JLog::addLogger ( array ('text_file' => 'booking.txt'), JLog::ALL, array ('com_bookpro'));
		
		JLog::add ( new JLogEntry ( 'Starting to save booking:', JLog::INFO,'com_bookpro' ));
		
		//$ticketypes=array('adult'=>1,'child'=>2,'infant'=>3);
		
		
		AImporter::helper('bus','log','date');
		AImporter::model('roomrates');
		
		$config=JComponentHelper::getParams('com_bookpro');
		$app = JFactory::getApplication();
		
		$input = $app->input;
		$cart = JModelLegacy::getInstance('BusCart', 'bookpro');
		$cart->load();
		$chargeInfo=$cart->chargeInfo;
		
		$roundtrip = JFactory::getApplication ()->getUserStateFromRequest ('filter.roundtrip', 'filter_roundtrip', false,'boolean' );
		
		
		$this->start = JFactory::getApplication ()->getUserStateFromRequest ( 'filter.start', 'filter_start' );
		
		$this->start=DateHelper::createFromFormat ($this->start )->format ('Y-m-d');
		
		if($roundtrip==1){
			$this->end = JFactory::getApplication ()->getUserStateFromRequest ( 'filter.end', 'filter_end' );
			$this->end=DateHelper::createFromFormat ($this->end )->format ('Y-m-d');
		
		}
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
		$chargeInfo['sum']['subtotal']+=$total_addon;
		$fee=($chargeInfo['sum']['subtotal']*$config->get('tax'))/100;
		$total=$fee+$chargeInfo['sum']['subtotal'];
		$chargeInfo['sum']['fee']=$fee;
		$chargeInfo['sum']['total']=$total;
		
		JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');
		$person=$input->get('person',array(),'array');
		
		$total_pax=count($person);
		
		$prepare_pax=array();
		
		$rateModel=new BookProModelRoomRates();
		$state = $rateModel->getState();
		$state->set('filter.room_id',$chargeInfo['onward']['id']);
		$state->set('filter.date',$this->start);
		$state->set('list.limit',null);
		
		$ownard_prices =  $rateModel->getItems();
		
		
		//var_dump($ownard_prices);die;
		
		if($roundtrip==1){
			
			$rateModel=new BookProModelRoomRates();
			$state = $rateModel->getState();
			$state->set('filter.room_id',$chargeInfo['return']['id']);
			$state->set('filter.date',$this->end);
			$state->set('list.limit',null);
			
			$return_prices =  $rateModel->getItems();
		}
		
		
		foreach ($person as $key_persons=>$listpersons){
		
			for ($i = 0;$i < count($listpersons);$i++){
				$listpersons[$i]['route_id'] = $chargeInfo['onward']['id'];
				$listpersons[$i]['start'] =$this->start; 
				
				if($roundtrip==1){
					
							$listpersons[$i]['return_route_id'] = $chargeInfo['return']['id'];
							$listpersons[$i]['return_start'] =  $this->end;
				
				}
				
				$stop_price=($chargeInfo['onward']['boarding']['price']+$chargeInfo['onward']['dropping']['price']);
				
				foreach ($ownard_prices as $type){
				
					if($listpersons[$i]['group_id']==$type->cgroup_id)
							
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
				
				$prepare_pax[]=$listpersons[$i];
			}
		}
				
	
		
		AImporter::table ( 'customer','orders' );
		$db = JFactory::getDbo ();
		$user = JFactory::getUser ();
		try {
			
			$db->transactionStart ();
			$user = JFactory::getUser();
			$post= $input->getArray($_POST);
			
			if($config->get('is_lead')){
				
				$post['firstname']=$prepare_pax[0]['firstname'];
				$post['lastname']=$prepare_pax[0]['lastname'];
				$post['telephone']=$prepare_pax[0]['phone'];
				$post['email']=$prepare_pax[0]['email'];
				
			}
			
			if ($user->id) {
				$account=JBFactory::getAccount();
				if($account->id){
					$cid = $account->id;
				}
				else
				{
					
					$post['id']=0;
					$post['state'] = 1;
					$post['user']=$user->id;
					$post['created'] = JFactory::getDate()->toSql();
					$customerTable=new TableCustomer($db);
					$customerTable->save($post);
					$cid = $customerTable->id;
				}
				
				
			}else{
					
				$post['id']=0;
				$post['state'] = 1;
				$post['created'] = JFactory::getDate()->toSql();
				$customerTable=new TableCustomer($db);
				$customerTable->save($post);
				$cid = $customerTable->id;	
			}
			$params = new JObject();
			$params->chargeInfo=$chargeInfo;
			OrderStatus::init();
			if(isset($chargeInfo['return'])){
				$return_seat=$chargeInfo['return']['seat'];
				
			}
			
			$agent_plg= JPluginHelper::getPlugin('joombooking','jb_agent');
			$created_by=0;
			if($agent_plg){
				
				$agent_plg_array=json_decode($agent_plg->params,true);
				
				$created_by=$agent_plg_array['web_agent_id'];
			}
			//var_dump($created_by);die;
			
			$order=array(
					'id'=>0,
					'type'=>'BUS',
					'user_id'=>$cid,
					'total'=>$chargeInfo['sum']['total'],
					'total_bg'=>$total_addon,
					'subtotal'=>$chargeInfo['sum']['subtotal'],
					'pay_method'=>'',
					'pay_status'=>'PENDING',
					'order_status'=>OrderStatus::$PENDING->getValue(),
					'route_id'=>$chargeInfo['onward']['id'],
					'return_route_id'=>$chargeInfo['return']['id'],
					'start'=>$this->start,
					'return_start'=>$this->end,
					'created_by'=>$created_by,
					'seat'=>$chargeInfo['onward']['seat'],
					'return_seat'=>$return_seat,
					'tax'=>$chargeInfo['sum']['fee'],
					'service_fee'=>$chargeInfo['sum']['fee'],
					'notes'=>$input->get('notes'),
					'params'=>json_encode($params)
						
			);
			$orderTable=new TableOrders($db);
			$orderTable->save ($order);
			$orderid=$orderTable->id;
			
			JLog::add(new JLogEntry ('Booking number created:'.$orderTable->order_number, JLog::INFO,'com_bookpro' ));
			
			
			//save passenger
			/*
			AImporter::model('roomrate');
			$rateModel = new BookProModelRoomRate();
			$rate = $rateModel->getItem($chargeInfo['onward']['rate_id']);
			$complexRate = new JObject();
			$complexRate->rate = $rate;
			if ($roundtrip == 1){
				$return_rate = $rateModel->getItem($chargeInfo['return']['rate_id']);
				$complexRate->return_rate = $return_rate;
			}
			$rateJson = json_encode($complexRate);
			
			*/
			//auto assign seat
			if($chargeInfo['onward']['seat'])
				$seats=explode(',', $chargeInfo['onward']['seat']);
			//
			$i=0;
			foreach ($prepare_pax as $listperson){
					
					$listperson['order_id'] = $orderid;
					
					if(count($seats)>0)
					$listperson['seat'] = $seats[$i];
						
					//$listperson['params'] = $rateJson;
					AImporter::table ( 'passenger' );
					$Tablepassenger = new TablePassenger($db);
					$Tablepassenger->save ($listperson);
					$i++;
			}
				 
			$db->transactionCommit();
			$this->setRedirect(JURI::base().'index.php?option=com_bookpro&view=formpayment&order_id='.$orderid.'&'.JSession::getFormToken().'=1');
			return;
		}catch (Exception $e){
			
			JBLog::addException($e);
			$db->transactionRollback();
			$this->setRedirect(JURI::base(),$e->getMessage());
		}
	
	}
	

	function ajaxsearch(){
		$config = JComponentHelper::getParams('com_bookpro');
		$app=JFactory::getApplication();
		$input=$app->input;
		if (! class_exists('BookProModelBustrips')) {
			AImporter::model('bustrips');
		}
		
		AImporter::helper('bus');
		
		$view=&$this->getView('ajaxbustrip','html','BookProView');
		$cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
		$cart->load();

		if(!$cart->from){
			$app->enqueueMessage(JText::_('COM_BOOKPRO_SESSION_EXPIRED'));
			$app->redirect(JUri::root());
		}else {
			$start=JRequest::getVar('start',null);
			if($start)
				$cart->start=$start;
            $agent=JRequest::getVar('agent',null);
            if($agent)
                $cart->agent=$agent;
			$lists=array();
			$lists['from']= $cart->from;
			$lists['to']= $cart->to;
			$timestamp = strtotime($cart->start);
			$lists['depart_date']=$cart->start;
			if(JFactory::getDate()->format('Y-m-d')==JFactory::getDate($cart->start)->format('Y-m-d')){
				$lists['cutofftime']=$config->get('cutofftime');
			}
			$going_trip = BusHelper::getBustripSearch($lists,(int) $cart->roundtrip);
			$view->going_trips=$going_trip;

			if($cart->roundtrip=='1'){

				$end=JRequest::getVar('end',null);
				if($end)
					$cart->end=$end;
				
				$lists=array();
				$lists['from']= $cart->to;
				$lists['to']= $cart->from;
				
				$end=$cart->end;
				if(is_null($end)){
					$end=JFactory::getDate()->format('Y-m-d');
				}
				$timestamp = strtotime($cart->end);
				$lists['depart_date']=$cart->end;
				$return_trips=BusHelper::getBustripSearch($lists,(int) $cart->roundtrip,true);
				$view->return_trips=$return_trips;
			}

			$cart->saveToSession();
			$view->assign('cart',$cart);
			$view->display();

		}
	}


}