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
JHtml::_('jquery.framework');
AImporter::model('customer');
AImporter::helper('bus','date','bookpro','orderstatus','paystatus','currency');

class BookproViewReport extends JViewLegacy
{

	function display($tpl = null)
	{
		
		$account=JBFactory::getAccount();
		if(!$account->isAgent){
			JFactory::getApplication()->enqueueMessage("Permission denied");
			JFactory::getApplication()->redirect(JUri::current());
			return;
		}
		$model = new BookProModelCustomer();
		//$customer = $model->getItemByUser();
		$this->config=JBFactory::getConfig();	
		$this->local=BookProHelper::getLocal();	
		$this->customer= $model->getItemByUser();
		
		AImporter::model('orders');
		$this->orderstatus=$this->getOrderStatusSelect(null);
		$orderModel=new BookProModelOrders();
		$customer=JBFactory::getAccount();
		
		$this->state = $orderModel->getState();
		$this->state->set('filter.created_by',$customer->id?$customer->id:-1);
		$this->orders=$orderModel->getItems();
		$this->pagination=$orderModel->getPagination();
		OrderStatus::init();
		
			
		parent::display($tpl);

	}
	
	function getResellers($selected){
		AImporter::model('customers');	
		$model=new BookProModelCustomers();
		$state=$model->getState();
		$state->set('filter.state',1);
		$state->set('filter.group_id',$this->config->get('agent_usergroup'));
		$items=$model->getItems();
		return AHtml::getFilterSelect('created_by',JText::_('COM_BOOKPRO_SELECT_RESELLER'),$items,$selected,false,'','id','firstname');
	
	}
	
	 function getPaxOptions() {
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( 'rate.*' );
		$query->from ( '#__bookpro_cgroup AS rate' )->where ( 'state=1' );
		$db->setQuery ( $query );
		$types = $db->loadObjectList ();
		return $types;
	}
	
	function createDestinationSelectBox( $selected,$att='') {
	
		$db = JFactory::getDbo();
	
		$subQuery = $db->getQuery ( true );
		$subQuery->select ( 'p.adult' );
		$subQuery->from ( '#__bookpro_roomrate AS p' );
		$subQuery->where ( $db->qn('date').' >= ' . $db->quote ( JFactory::getDate ()->toSql () ) );
		$subQuery->where ( 'p.room_id=t.id' );
		$subQuery->order ( 'p.adult asc limit 0,1' );
	
	
		$query = $db->getQuery(true);
		//$query->select(array ('d.id,d.title,(' . $subQuery . ') AS price'));
		//$query->select(array('d.id,d.title'));
		$query->select(array ('d.id,d.title'));
	
		$query->from($db->quoteName('#__bookpro_dest').  ' AS d');
		//$query->where->append('EXISTS ('.$subQuery.')');
		$query->innerJoin('#__bookpro_bustrip AS t on t.from=d.id');
		$query->where('d.state=1','t.state=1')->append('EXISTS ('.$subQuery.')');;
		$query->order('d.title ASC');
		//$query->having('price > 0');
		$query->group('d.id');
		$sql = (string)$query;
		$db->setQuery($sql);
		$dest = $db->loadObjectList();
	
		//echo $query->dump();
		//die;
	
		$options = array();
		foreach($dest as $des)
		{
			$options[] = JHtml::_('select.option', $des->id, $des->title);
		}
			
		$option = JHtml::_ ( 'select.option', '', JText::_ ( "COM_BOOKPRO_BUSTRIP_FROM" ) );
		array_unshift ( $options, $option );
		$select = JHtml::_ ( 'select.genericlist', $options, 'filter_from', $att, 'value', 'text', $selected, false );
		return $select;
	}
	
	public  function getArrivalDestination($field, $selected, $from) {
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( 'f.`to` AS `key` ,`d2`.`title` AS `value`' );
		$query->from ( '#__bookpro_bustrip AS f' );
		$query->leftJoin ( '#__bookpro_dest AS d2 ON f.to =d2.id' );
		$query->where ( array (
				'f.from=' . $from,
				'f.state=1'
		) );
		$query->group ( 'f.to' );
		$query->order ( 'value' );
		$sql = ( string ) $query;
		$db->setQuery ( $sql );
		$flight = $db->loadObjectList ();
	
		if (! $flight) {
			$temp = new stdClass ();
			$temp->key = '';
			$temp->value = JText::_ ( 'MOD_JBBUS_BUS_TO' );
			$flight [] = $temp;
		}
	
		$select = JHtml::_ ( 'select.genericlist', $flight, $field, 'class="input-medium" size="1"', 'key', 'value', $selected, false );
		return $select;
	}
	
	function getOrderStatusSelect($select){
		OrderStatus::init();
		return AHtml::getFilterSelect('filter_order_status', JText::_('COM_BOOKPRO_SELECT_ORDER_STATUS'), OrderStatus::$map, $select, false, 'class="input input-medium"', 'value', 'text');
	}


	
}

?>
