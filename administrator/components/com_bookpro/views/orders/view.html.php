<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 56 2012-07-21 07:53:28Z quannv $
 **/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
AImporter::model('agents');
AImporter::helper('bookpro', 'paystatus','orderstatus');
class BookProViewOrders extends JViewLegacy
{
    
    var $items;
    var $state;
    var $pagination;

    
    function display($tpl = null)
    {
    	$mainframe = JFactory::getApplication();
       $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_BOOKPRO_ORDER_LIST'));
        $this->state=$this->get('State');
        $this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
       //	$this->assign('orderstatus',$this->getOrderStatusSelect(''));//$this->lists['order_status']
       //	$this->agents=BookProHelper::getCustomerGroupSelect($this->state->get('filter.created_by'));
       
		$this->agents=$this->getAgents($this->state->get('filter.created_by'));
		$this->methods=$this->getPayMethod($this->state->get('filter.pay_method'));
       	$this->ranges=BookProHelper::getRangeSelect($this->state->get('filter.range'));
       	
       	$this->companies=$this->getCompanySelectBox($this->state->get('filter.agent_id','filter_agent_id'));
       	
       	//
        $this->orderstatus=$this->getOrderStatusSelect($this->state->get('filter.order_status'));
       	$this->paystatus=$this->getPayStatusSelect($this->state->get('filter.pay_status'));
       	
		$this->addToolbar();
       	parent::display($tpl);
    }
    
    protected function addToolbar(){
    	JToolbarHelper::title(JText::_('COM_BOOPRO_MANAGER_ORDERS'),'basket');
    	JToolbarHelper::editList('order.edit');
    	JToolbarHelper::deleteList('','orders.delete', 'JTOOLBAR_DELETE');
    }
    
	function getOrderStatusSelect($select){
		OrderStatus::init();
		return AHtml::getFilterSelect('filter_order_status', JText::_('COM_BOOKPRO_SELECT_ORDER_STATUS'), OrderStatus::$map, $select, false, 'class="input input-medium"', 'value', 'text');
	}
	function getPayStatusSelect($select) {
		PayStatus::init();
		return AHtml::getFilterSelect('filter_pay_status', JText::_('COM_BOOKPRO_SELECT_PAY_STATUS'), PayStatus::$map, $select, false, 'class="input input-medium"', 'value', 'text');
	}
	function getCompanySelectBox($select,$field="filter_agent_id"){
		$model=new BookProModelAgents();
		$items=$model->getItems();
		return AHtml::getFilterSelect($field, JText::_('COM_BOOKPRO_SELECT_COMPANY'), $items, $select, false, '', 'id', 'brandname');
	}
	
	
	function getAgents($select){
		AImporter::model('customers');
		$config=JComponentHelper::getParams('com_bookpro');
		$model=new BookProModelCustomers();
		$state=$model->getState();
		$state->set('filter.group_id',$config->get('agent_usergroup'));
		$items=$model->getItems();
		return AHtml::getFilterSelect('filter_created_by', JText::_('COM_BOOKPRO_SELECT_AGENT'), $items , $select, false, 'class="input input-medium"', 'id', 'fullname');
	}
	
	function getPayMethod($selected){
		$option[]=JHtmlSelect::option('0','Pay option');
		$option[]=JHtmlSelect::option('1','Offline');
		$option[]=JHtmlSelect::option('2','Online');
		return JHtmlSelect::genericlist($option,'filter_pay_method','class="input-medium"','value','text',$selected);
	}
	
	
	
	function td_getPayStatusSelect($select,$id) {
		PayStatus::init();
		return JHtmlSelect::genericlist(PayStatus::$map,$id, 'class="td_paystatus input-small"' ,'value', 'text', $select);
	}
	
	function td_getOrderStatusSelect($select,$id){
		OrderStatus::init();
		return JHtmlSelect::genericlist(OrderStatus::$map,$id, 'class="td_orderstatus input-small"','value', 'text',$select);
	}
	
	
}