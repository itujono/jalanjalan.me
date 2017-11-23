<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: order.php 56 2012-07-21 07:53:28Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');


AImporter::helper('bookpro');

class BookProModelOrder extends JModelAdmin
{

	
	public function getTable($type = 'Orders', $prefix = 'Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	function populateState(){
		$table = $this->getTable();
		$key = $table->getKeyName();
		$pk = JFactory::getApplication()->input->getInt($key);
		if ($pk) {
			$this->setState($this->getName() . '.id', $pk);
		}
	}
	
	public function getForm($data = array(), $loadData = true){
		$app = JFactory::getApplication();
		//get data from Form
		$form = $this->loadForm('com_bookpro.order','order', array('control'=> 'jform', 'load_data'=>$loadData));
		if (empty($form)){
			return false;
		}
	
		return $form;
	}
	
	protected function loadFormData(){
		$data = JFactory::getApplication()->getUserState('com_bookpro.edit.order.data', array());
		if(empty($data)){
			$data = $this->getItem();
		}
		return $data;
	}
	
	function getByOrderNumber($number){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('obj.*');
		$query->select('c.telephone,c.mobile,c.lastname,c.firstname,c.email');
		$query->from('#__bookpro_orders AS obj');
		$query->join('LEFT', '#__bookpro_customer AS c ON c.id = obj.user_id');
		$query->where('obj.order_number='.$number);
		$db->setQuery($query);
		$item = $db->loadObject();
		
		if($item->id){
			$query = $db->getQuery(true);
			$query->select('passenger.*, country.country_name AS country');
			$query->select('cgroup.title AS group_title');
			$query->from('#__bookpro_passenger AS passenger');
			$query->join('LEFT', '#__bookpro_cgroup AS cgroup ON passenger.group_id = cgroup.id');
			$query->leftJoin('#__bookpro_country AS country ON country.id = passenger.country_id');
			$query->where('passenger.order_id = '. (int) $item->id);
			$db->setQuery($query);
			$passengers =  $db->loadObjectList();
		
		}
		
		return $item;
	}

	
	function processDiscount($order_id){
		$this->setId($order_id);
		$order=$this->getObject();
		AImporter::model('customer','cgroup');
		$cModel=new BookProModelCustomer();
		$cModel->setId($order->user_id);
		$customer=$cModel->getObject();
		if($customer->cgroup_id){
			$gModel=new BookProModelCGroup();
			$gModel->setId($customer->cgroup_id);
			$cgroup=$gModel->getObject();
			if($cgroup->discount){
				
				$discount=$order->total*$cgroup->discount;
				$newTotal=$order->total-$discount;
				$order->total=$newTotal;
				$order->discount=$discount;
				$order->store();
				
			}
		}

	}
	
	function getComplexItem($pk=null){
		AImporter::helper('bus');
		$item = parent::getItem($pk);
				
		$db = JFactory::getDbo();
		
		if ($item->user_id){
			$query = $db->getQuery(true);
			$query->select('customer.*,country.country_name');
			$query->from('#__bookpro_customer AS customer');
			$query->leftJoin('#__bookpro_country AS country ON country.id = customer.country_id');
			$query->where('customer.id = '.$item->user_id);
			$db->setQuery($query);
			$customer = $db->loadObject();
			$item->customer=$customer;
			
		}
		//var_dump($item->created_by);die;
		if ($item->created_by){
			$query = $db->getQuery(true);
			$query->select('customer.*');
			$query->from('#__bookpro_customer AS customer');
			$query->where('customer.id = '.$item->created_by);
			$db->setQuery($query);
			$customer = $db->loadObject();
			$item->created_by_account=$customer;
			//var_dump($item->created_by_account);die;
		}
		
		if($item->id){
			$query = $db->getQuery(true);
			$query->select('passenger.*, country.country_name AS country');
			$query->select('cgroup.title AS group_title');
			$query->from('#__bookpro_passenger AS passenger');
			$query->join('LEFT', '#__bookpro_cgroup AS cgroup ON passenger.group_id = cgroup.id');
			$query->leftJoin('#__bookpro_country AS country ON country.id = passenger.country_id');
			$query->where('passenger.order_id = '. (int) $item->id);
			$db->setQuery($query);
			$passengers =  $db->loadObjectList();
		
		}
		//get addon
		//echo "<pre>";print_r($item->params);die;
		$addons_items=array();
		if(isset($item->params['chargeInfo']['addon'])){
			$addons=$item->params['chargeInfo']['addon'];
			
			
			
			if (count($addons)>0){
			
			foreach ($addons as $key=>$value) {
				
				AImporter::model('addon');
				$modelAddon=new BookProModelAddon();
				$addon=$modelAddon->getItem($key);
				$addon->qty=$value;
				$addons_items[]=$addon;
				
				}
			}
		}
		//
		
		$item->addons=$addons_items;
		AImporter::model('bustrip');
		$bustripModel = new BookProModelBusTrip ();
		$bustrips = $bustripModel->getBookingTrip ($item->params['chargeInfo']);
		foreach ($bustrips as $bustrip){
			if($bustrip->id==$passengers[0]->route_id)
				$bustrip->seat=$item->seat;
			if($bustrip->id==$passengers[0]->return_route_id)
				$bustrip->seat=$item->return_seat;
		}
		$item->bustrips = $bustrips;
		$item->passengers = $passengers;
		
		//
		
		return $item;
	}
	
	
}
?>