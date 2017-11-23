<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id$
 **/
defined('_JEXEC') or die('Restricted access');
AImporter::helper('paystatus','orderstatus');
class BookProControllerPayment extends JControllerLegacy{


	function __construct(){

		parent::__construct();
		
	}
	
	function process(){
		
		JSession::checkToken() or jexit('Invalid Token');
		AImporter::helper('bus');
		$input=JFactory::getApplication()->input;
		$payment_plugin = $input->getString('payment_plugin','', 'bookpro');
		$element=explode('_', $payment_plugin);
		$order_id=$input->getInt('order_id');
		
		JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');
		$order = JTable::getInstance('orders', 'table');
		$customer = JTable::getInstance('customer', 'table');
		
		$order->load($order_id);
		$order->pay_method=$element[1];
		$order->store();
		
		$customer->load($order->user_id);
		
		//Prepare value to complete payment
		$values=array();
		$values['payment_plugin'] =$payment_plugin;
		$values['total']=$order->total;
		$values['order_number']=$order->order_number;
		$values['title']=BusHelper::getRouteFromParams(json_decode($order->params,true));
		$values['city']=$customer->city;
		$values['firstname']=$customer->firstname;
		$values['lastname']=$customer->lastname;
		$values['address']=$customer->address;
		$values['mobile']=$customer->mobile;
		$values['email']=$customer->email;
		$values['desc']=$order->order_number;
		
		//cal payment plugin
		
		$dispatcher    = JDispatcher::getInstance();
		JPluginHelper::importPlugin ('bookpro');
		$results = $dispatcher->trigger( "onBookproPrePayment", array($payment_plugin,$values ));
		
		echo $results;
		exit;
			
	}
	function getPaymentForm($element='')
	{
		$app = JFactory::getApplication();
		$values = JRequest::get('post');
		$html = '';
		$text = "";
		$user = JFactory::getUser();
		if (empty($element)) {
			$element = JRequest::getVar( 'payment_element' );
		}
		$results = array();
		$dispatcher    = JDispatcher::getInstance();
		JPluginHelper::importPlugin ('bookpro');
	
		$results = $dispatcher->trigger( "onBookproGetPaymentForm", array( $element, $values ) );
		for ($i=0; $i<count($results); $i++)
		{
		$result = $results[$i];
		$text .= $result;
		}
		$html = $text;
		// set response array
		$response = array();
		$response['msg'] = $html;
		// encode and echo (need to echo to send back to browser)
		echo json_encode($response);
		//$app->close();
		return;
	}

	function postpayment()
	{
		
		jimport('joomla.log.log');
		JLog::addLogger ( array ('text_file' => 'booking.txt'), JLog::ALL, array ('com_bookpro'));
		
		$app =JFactory::getApplication();
		$plugin = $app->input->getString('method');
		$pluginsms = $app->input->get('methodsms','product_sms','string');
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin ('bookpro');
		$values=array();
		$results = $dispatcher->trigger( "onBookproPostPayment", array($plugin, $values ));
		
		/// Send email
		
		if($results){
			AImporter::helper('email');
			$mail=new EmailHelper();
			if($results[0]->pay_method=="offline" || $results[0]->pay_status=="SUCCESS") {
				
				if($app->input->get('nomail')==1){
					//Do something for no email
				}else {	
					JLog::add ( new JLogEntry ( 'Send confirmation sms:'.$results[0]->order_number , JLog::INFO,'com_bookpro' ));
					$smsresult=$dispatcher->trigger('onBookproSendSms',array($results[0]));
					JLog::add ( new JLogEntry ( 'Send confirmation email:'.$results[0]->order_number , JLog::INFO,'com_bookpro' ));
					$mail->sendMail($results[0]->id);
				}
			}
			if($results[0]->pay_status=="REFUNDED") {
				
				//Do something for refund
			}
			
		}
		
		AImporter::model('order');
		$orderModel=new BookProModelOrder();
		
		$this->setRedirect(JUri::root().'index.php?option=com_bookpro&view=postpayment&order_id='.$results[0]->id);
		//$view = $this->getView('postpayment','html','Bookproview');
		//$view->assign('order',$orderModel->getComplexItem($results[0]->id));
		//$view->display();
	}


	

}
