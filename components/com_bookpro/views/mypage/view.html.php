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

AImporter::model('airports','customer');
AImporter::helper('bookpro','orderstatus','paystatus');

class BookproViewMyPage extends JViewLegacy
{

	function display($tpl = null)
	{
		AImporter::model('orders');
		$mainframe = JFactory::getApplication();
		$document = JFactory::getDocument();
		$model = new BookProModelCustomer();
		$customer = $model->getItemByUser();
		
		
		$this->customer= $customer;
		$document->setTitle($customer->firstname);
				
		
		$orderModel=new BookProModelOrders();
		$this->state = $orderModel->getState();
		$this->state->set('filter.user_id',$customer->id?$customer->id:-1);
		
		$this->ranges=BookProHelper::getRangeSelect($this->state->get('filter.range'));
		$this->orderstatus=$this->getOrderStatusSelect($this->state->get('filter.order_status'));
		$this->paystatus=$this->getPayStatusSelect($this->state->get('filter.pay_status'));
		
		$this->orders=$orderModel->getItems();
		$this->pagination=$orderModel->getPagination();
		parent::display($tpl);

	}
	
	function getOrderStatusSelect($select){
		OrderStatus::init();
		return AHtml::getFilterSelect('filter_order_status', JText::_('COM_BOOKPRO_SELECT_ORDER_STATUS'), OrderStatus::$map, $select, false, 'class="input input-medium"', 'value', 'text');
	}
	function getPayStatusSelect($select) {
		PayStatus::init();
		return AHtml::getFilterSelect('filter_pay_status', JText::_('COM_BOOKPRO_SELECT_PAY_STATUS'), PayStatus::$map, $select, false, 'class="input input-medium"', 'value', 'text');
	}


	
}

?>
