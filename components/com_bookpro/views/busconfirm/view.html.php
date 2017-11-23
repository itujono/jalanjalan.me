<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php  23-06-2012 23:33:14
 **/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

AImporter::model('bustrip','customer');
AImporter::helper('bus','date');
class BookProViewBusConfirm extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		$this->config=JComponentHelper::getParams('com_bookpro');
		
		
		$app = JFactory::getApplication();
		
		$input = $app->input;
		$cart = JModelLegacy::getInstance('BusCart', 'bookpro');
		$cart->clear();
		
		$this->roundtrip = JFactory::getApplication ()->getUserStateFromRequest ('filter.roundtrip', 'filter_roundtrip', false,'boolean' );
		$this->start = JFactory::getApplication ()->getUserStateFromRequest ( 'filter.start', 'filter_start',JFactory::getDate()->format('d-m-Y') );
		$this->start = DateHelper::createFromFormat ($this->start )->format ( 'Y-m-d' );
		
		$this->adults= JFactory::getApplication()->getUserStateFromRequest ( 'filter.adult', 'filter_adult', array(),'array' );
		
		
		
		$this->total_pax=0;
		foreach ($this->adults as $key=>$value) {
			$this->total_pax+=$value;
		}
		$total_pax=$this->total_pax;
		
		$this->bustrip_id=JFactory::getApplication()->getUserStateFromRequest ( 'filter.bustrip_id', 'bustrip_id', 0 );
		$this->boarding_id=JFactory::getApplication()->getUserStateFromRequest ( 'filter.boarding_id', 'boarding'.$this->bustrip_id, 0 );
		$this->dropping_id=JFactory::getApplication()->getUserStateFromRequest ( 'filter.dropping_id', 'dropping'.$this->bustrip_id, 0 );
		
		$this->private_boarding=JFactory::getApplication()->getUserStateFromRequest ( 'filter.private_boarding', 'private_boarding'.$this->bustrip_id, 0 );
		$this->private_dropping=JFactory::getApplication()->getUserStateFromRequest ( 'filter.private_dropping', 'private_dropping'.$this->bustrip_id, 0 );
		
		//$this->listseat=JFactory::getApplication()->getUserStateFromRequest ( 'filter.listseat', 'listseat'.$this->bustrip_id, '' );
		
		
		
		$seat = JFactory::getApplication()->getUserStateFromRequest ( 'filter.seat', 'seat', '' );
		
		
		$chargeInfo=array();
		$tripModel=new BookProModelBusTrip();
		$cmodel=new BookProModelCustomer();
		$customer=$cmodel->getItemByUser();
		$bustrips=array();
		$subtotal = 0;
		
		$bustrip=$tripModel->getComplexItem($this->bustrip_id,$this->start);
		$bustrip->depart_date=$this->start;
		//echo "<pre>";print_r($bustrip->stations[$this->boarding_id]);die;
		$chargeInfo['onward']['id']=$this->bustrip_id;
		//$chargeInfo['onward']['rate_id']=$bustrip->price->id;
		$chargeInfo['onward']['total']=0;
		if($this->boarding_id)	{	
			$boarding=$bustrip->stations[$this->boarding_id];
			$boarding['private_boarding']=$this->private_boarding;
			$bustrip->boarding = $boarding;
			
			$chargeInfo['onward']['boarding_id']=$this->boarding_id;
			$chargeInfo['onward']['boarding']=$boarding;
			
			$chargeInfo['onward']['total']+=$boarding['price']*$total_pax;
		}
		if($this->dropping_id){
			$dropping=$bustrip->stations[$this->dropping_id];
			$dropping['private_dropping']=$this->private_dropping;
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
		
		
		$onward_total=BusHelper::getTotalPrice($this->rates, $this->roundtrip);
		
		$chargeInfo['onward']['total']+=$onward_total;
		
		//$bustrip->total = $subtotal;
		$bustrips[]=$bustrip;
		
		// Return trip
		if($this->roundtrip==1){
			
			//echo "die";die;
			$this->end = JFactory::getApplication ()->getUserStateFromRequest ( 'filter.end', 'filter_end' );
			$this->end = DateHelper::createFromFormat ($this->end )->format ( 'Y-m-d' );
			$this->return_bustrip_id=JFactory::getApplication()->getUserStateFromRequest('filter.return_bustrip_id','return_bustrip_id',0);
			$this->listseat=JFactory::getApplication()->getUserStateFromRequest ( 'filter.returnlistseat', 'returnlistseat'.$this->return_bustrip_id, '' );
			$this->return_boarding_id = JFactory::getApplication()->getUserStateFromRequest ( 'filter.return_boarding', 'return_boarding'.$this->return_bustrip_id, 0 );
			$this->return_dropping_id = JFactory::getApplication()->getUserStateFromRequest ( 'filter.return_dropping_id', 'return_dropping'.$this->return_bustrip_id, 0 );
			
			
			$this->return_private_boarding=JFactory::getApplication()->getUserStateFromRequest ( 'filter.return_private_boarding', 'return_private_boarding'.$this->return_bustrip_id, '' );
			$this->return_private_dropping=JFactory::getApplication()->getUserStateFromRequest ( 'filter.return_private_dropping', 'return_private_dropping'.$this->return_bustrip_id, '' );	
			
			
			//echo $this->return_private_boarding;
			//echo $this->return_private_dropping;
			$seat = JFactory::getApplication()->getUserStateFromRequest ( 'filter.return_seat', 'return_seat', '' );
			
			
			
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
				$boarding['private_boarding']=$this->return_private_boarding;
				$bustrip->boarding = $boarding;
				$chargeInfo['return']['boarding_id']=$this->return_boarding_id;
				$chargeInfo['return']['boarding']=$boarding['price'];
				$chargeInfo['return']['total']+=$boarding['price']*$total_pax;
			}
			if($this->return_dropping_id){
				$dropping=$bustrip->stations[$this->return_dropping_id];
				$dropping['private_dropping']=$this->return_private_dropping;
				$bustrip->dropping = $dropping;
				$chargeInfo['return']['dropping_id']=$this->return_dropping_id;
				$chargeInfo['return']['dropping']=$dropping['price'];
				$chargeInfo['return']['total']+=$dropping['price']*$total_pax;
			}
			$bustrips[]=$bustrip;
			$rates=$bustrip->price;
			//var_dump($rates);
			foreach ($this->adults as $key=>$value) {
					
				foreach ($rates as $rate)
					if($rate->pricetype==$key)
						$rate->qty=$value;
						
			}
			
			$return_total=BusHelper::getTotalPrice($rates, $this->roundtrip);
			
			//var_dump($return_total);
			$chargeInfo['return']['total']+=$return_total;
		}
		
		
		foreach ($chargeInfo as $info) {
			$subtotal+=$info['total'];
			
		}
		//echo "<pre>";print_r($chargeInfo);die;
		// Total
		$chargeInfo['sum']['pax']=$this->total_pax;
		$chargeInfo['sum']['subtotal']=$subtotal;
		$chargeInfo['sum']['fee']=($subtotal*$this->config->get('tax'))/100;
		$chargeInfo['sum']['total']=$subtotal+$chargeInfo['sum']['fee'];
		$cart->chargeInfo=$chargeInfo;
		$cart->saveToSession();
		$this->chargeInfo=$chargeInfo;
		$this->bustrips=$bustrips;
		$this->customer=$customer;
		$this->passengers=$this->rates;
		$this->addons=$this->getAddons();
		$this->_prepare();
		parent::display($tpl);
	}
	
	protected function _prepare(){
		JFactory::getDocument()->setTitle(JText::_('COM_BOOKPRO_SELECT_BUS_ROUTE'));
	}
	function getListSeat($field = 'pSeat[]',$id = 'pSeat'){
		$cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
		$cart->load();
		
		$seat = str_replace(array('[', ']','"'), '', $cart->listseat);
		
		$seats = explode(",", $seat);
		
		$options = array();
		$options[] = JHtmlSelect::option(0,JText::_('COM_BOOKPRO_PASSENGER_SEAT'));
		for($i =0;$i < count($seats);$i++){
			$options[] = JHtmlSelect::option($seats[$i],$seats[$i]);
		}
		return JHtmlSelect::genericlist($options, $field,'class="input-small"');
		
	}
	
	function getReturnListSeat($field = 'pReturnSeat[]',$id = 'pReturnSeat'){
		$cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
		$cart->load();
	
		$seat = str_replace(array('[', ']','"'), '', $cart->returnlistseat);
		$seats = explode(",", $seat);
		$options = array();
		$options[] = JHtmlSelect::option(0,JText::_('COM_BOOKPRO_PASSENGER_RETURNSEAT'));
		for($i =0;$i < count($seats);$i++){
			$options[] = JHtmlSelect::option($seats[$i],$seats[$i]);
		}
		return JHtmlSelect::genericlist($options, $field,'class="input-small"');
	
	}
	function getAddons(){
		AImporter::model('addons');
		$model=new BookProModelAddons();
		$state=$model->getState();
		$state->set('filter.state',1);
		$addons=$model->getItems();
		return $addons;
		
	}
	


}
