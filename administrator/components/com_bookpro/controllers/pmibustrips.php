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
class BookProControllerPmiBustrips extends JControllerAdmin {
	function exportpdf() {
		$input = JFactory::getApplication ()->input;
		AImporter::helper ( 'pdf' );
		$model = $this->getModel ( 'PmiBustrips' );
		
		$depart_date = JFactory::getApplication ()->input->get ( 'depart_date', 0 );
		$router_id = JFactory::getApplication ()->input->get ( 'router_id', 0 );
		$agent_id = JFactory::getApplication ()->input->get ( 'agent_id', 0 );
		$children = JFactory::getApplication ()->input->get ( 'children', 0 );
		$pay_status = JFactory::getApplication ()->input->get ( 'pay_status', 0 );
		
		$state = $model->getState ();
		$model->setState ( 'filter.route_id', $router_id );
		$model->setState ( 'filter.depart_date', $depart_date );
		$model->setState ( 'filter.agent_id', $agent_id );
		$model->setState ( 'filter.children', $children );
		$model->setState ( 'filter.pay_status', $paystatus );
		$state->set ( 'list.limit', NULL );
		
		$ticket_view = $this->getView ( 'pmibustrips', 'html', 'BookProView' );
		$ticket_view->setModel ( $model, true );
		$ticket_view->setLayout ( 'report' );
		
		ob_start ();
		$ticket_view->display ();
		$pdf = ob_get_contents ();
		ob_end_clean ();
		PrintPdfHelper::printTicket ( $pdf, $order_number, 'L' );
		return;
	}
	
	
	function printcsv() {
		
		return;
	}
	
	function printpmi(){
		
		
	}
	function printticket(){
		AImporter::model('passengers');
		AImporter::helper('passenger');
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');
		$route_id=JFactory::getApplication()->input->get('filter_route_id');
		JArrayHelper::toInteger($cid);
		$model=new BookproModelpassengers();
		$passengers=$model->getItemsByIds($cid,$route_id);
		$view=$this->getView('printticket','html','BookProView');
		$view->passengers=PassengertHelper::formatPassenger($passengers);
		$view->display();
	}
	
	
	
	
	function sendsms(){
		AImporter::helper('date');
		$config=JBFactory::getConfig();
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');
		$sms_id = JFactory::getApplication()->input->get('sms_id','','int');
		$date = DateHelper::createFromFormat(JFactory::getApplication()->input->get( 'filter.depart_date' ));
		$date=DateHelper::toShortDate($date);
		$mailer=JFactory::getMailer();
		JArrayHelper::toInteger($cid);
		$jconfig=JFactory::getConfig();
	
		if($sms_id){
			
			AImporter::model('passengers','sms','bustrip');
			
			
			$model=new BookProModelSms();
			$sms=JString::trim($model->getItem($sms_id)->message);
		
			$model=new BookproModelpassengers();
			$state=$model->getState();
			$state->set('filter.ids',implode(',', $cid));
			$passengers=$model->getItems();
			$mobiles=array();
			$emails=array();
			
			foreach ($passengers as $pax) {
				if($pax->phone)
					$mobiles[]= $pax->phone;
				if($pax->email){
					$emails[]=$pax->email;
				}
			}
					
			
			
			$route_id=JFactory::getApplication()->input->get('filter_route_id');
			$bustripModel=new BookProModelBusTrip();
			$bustrip=$bustripModel->getComplexItem($route_id);
			
			$route_str=$bustrip->from_name.'-'.$bustrip->to_name.'('.$date. ')';
			$route_str.='. '.JText::sprintf('COM_BOOKPRO_BUSTRIP_DERPART_ARRIVAL_TXT',DateHelper::formatTime($bustrip->start_time),DateHelper::formatTime($bustrip->end_time)); 
			
			$sms = str_replace ( '{route}', $route_str, $sms );
			
			if(count($emails)>0){
				$result= $mailer->sendMail($jconfig->get('mailfrom'), $jconfig->get('fromname'), array_unique($emails),$jconfig->get('fromname') , $sms, true);
			}
			
			if(count($mobiles)>0){
			
				$data['mobile']=$mobiles;
				$data['sms']=$sms;
				
				$dispatcher = JDispatcher::getInstance();
				JPluginHelper::importPlugin ('bookpro');
				$smsresult=$dispatcher->trigger('onBookproMassSend',array($data));
				JFactory::getApplication()->setUserState('smsresult', $smsresult);
			}
			$this->setRedirect(JUri::base().'index.php?option=com_bookpro&view=pmibustrips&layout=complete');
			
		}
	
	}
}

?>