<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id$
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );
AImporter::model ( 'order', 'application' );
AImporter::helper ( 'currency', 'date','orderstatus','paystatus','bookpro' );
class EmailHelper {
	/**
	 *
	 * @param String $input        	
	 * @param CustomerTable $customer        	
	 */
	var $config;
	var $app;
	var $tempalte = 'default';
	var $emails=array();
	function __construct() {
		
	}
	public function setTemplate($value) {
		$this->tempalte = $value;
	}
	public function sendMail($order_id) {
		
		jimport('joomla.log.log');
		JLog::addLogger ( array ('text_file' => 'booking.txt'), JLog::ALL, array ('com_bookpro'));
		
		
				
		$mailer=JFactory::getMailer();
		$jconfig=JFactory::getConfig();
		AImporter::helper ( 'bus' );
		$orderModel = new BookProModelOrder();
		$applicationModel = new BookProModelApplication ();
		
		$config=JComponentHelper::getParams('com_bookpro');
		
		$order = $orderModel->getComplexItem( $order_id );
		
		$customer = $order->customer;
		$this->app = $applicationModel->getObjectByCode ( $order->type );
		$body_customer = $this->app->email_customer_body;
		
		$body_customer=str_replace ( '{company_name}', $config->get('company_name'), $body_customer );
		
		$body_customer=str_replace ( '{company_logo}', $config->get('company_logo'), $body_customer );
		
		$body_customer=str_replace ( '{company_address}', $config->get('company_address'), $body_customer );
		
		$body_customer = $this->fillCustomer ( $body_customer, $customer );
		$body_customer = $this->fillOrder ( $body_customer, $order );
		
		$this->emails[]=JString::trim($customer->email);
		
		
		$string_email=implode(',', $this->emails);
		
		//
		$result= $mailer->sendMail($jconfig->get('mailfrom'), $jconfig->get('fromname'), array_unique($this->emails), $this->app->email_customer_subject, $body_customer, true);
		JLog::add ( new JLogEntry ( 'Send email to:'.$string_email.':'.$result , JLog::INFO,'com_bookpro' ));
		//
		
		//BookProHelper::sendMail ( $this->app->email_send_from, $this->app->email_send_from_name, $customer->email, $this->app->email_customer_subject, $body_customer, true );
		
		$body_admin = $this->app->email_admin_body;
		$body_admin = $this->fillCustomer ( $body_admin, $customer );
		$body_admin = $this->fillOrder ( $body_admin, $order );
		BookProHelper::sendMail ( $jconfig->get('mailfrom'), $jconfig->get('fromname'), $this->app->email_admin, $this->app->email_admin_subject, $body_admin, true );
	}
	
	
	/**
	 *
	 * @param html $input        	
	 * @param Customer $customer        	
	 * @return mixed
	 */
	public function fillCustomer($input, $customer) {
		
		$input = str_replace ( '{email}', $customer->email, $input );
		$input = str_replace ( '{firstname}', $customer->firstname, $input );
		$input = str_replace ( '{lastname}', $customer->lastname, $input );
		$input = str_replace ( '{address}', $customer->address, $input );
		$input = str_replace ( '{city}', $customer->city, $input );
		$input = str_replace ( '{gender}', BookProHelper::formatGender ( $customer->gender ), $input );
		$input = str_replace ( '{telephone}', $customer->telephone, $input );
		$input = str_replace ( '{states}', $customer->states, $input );
		$input = str_replace ( '{zip}', $customer->zip ? 'N/A' : $customer->zip, $input );
		$input = str_replace ( '{country}', $customer->country_name, $input );
		return $input;
	}
	public function fillOrder($input, $order) {
		$input = str_replace ( '{order_number}', $order->order_number, $input );
		$input = str_replace ( '{total}', CurrencyHelper::formatprice ( $order->total ), $input );
		$input = str_replace ( '{tax}', CurrencyHelper::formatprice ( $order->tax ), $input );
		$input = str_replace ( '{subtotal}', CurrencyHelper::formatprice ( $order->subtotal ), $input );
		$input = str_replace ( '{payment_status}',PayStatus::format($order->pay_status), $input );
		$input = str_replace ( '{deposit}', $order->deposit, $input );
		$input = str_replace ( '{pay_method}', $order->pay_method, $input );
		$input = str_replace ( '{note}', $order->notes, $input );
		$input = str_replace ( '{created}',DateHelper::toNormalDate($order->created), $input );
		$input = str_replace ( '{order_status}', OrderStatus::format($order->order_status), $input );
		$order_link = BookProHelper::getOrderLink($order->order_number, $order->customer->email);
		$input = str_replace ( '{order_link}', $order_link, $input );
		$input = str_replace ( '{discounted}', CurrencyHelper::formatprice ( $order->discount ), $input );
		
	
		
		if ($order->type == 'BUS') {
			// get passenger information
			
			AImporter::model ( 'passengers' );
			$model = new BookproModelpassengers ();
			
			
			
			
			$state = $model->getState ();
			$state->set ( 'filter.order_id', $order->id );
			$passengers = $model->getItems ();
			$data = new JObject ();
			$route = BookProHelper::renderLayout ( 'tripinfo', $order );
			$input = str_replace ( '{tripdetail}', $route, $input );
			
			foreach ($passengers as $pa){
				if($pa->email){
					$this->emails[]=JString::trim($pa->email);
				}
			}
			
			$data->passengers = $passengers;
			$layout = new JLayoutFile('mail_passengers', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
			$passengers_html = $layout->render($data);
			$input = str_replace ( '{passenger}', $passengers_html, $input );
			
			$layout = new JLayoutFile('addons', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
			$addons_html = $layout->render($order);
			$input = str_replace ( '{addons}', $addons_html, $input );
			
			
		}
		
		return $input;
	}
	
	static public function cancelOrder($order_id) {
		$orderModel = new BookProModelOrder ();
		$applicationModel = new BookProModelApplication ();
		$customerModel = new BookProModelCustomer ();
		$order = $orderModel->getItem($order_id);
		
		$customer = $customerModel->getItem($order->user_id);
		$app = $applicationModel->getObjectByCode ( $order->type );
		$msg = 'COM_BOOKPRO_ORDER_STATUS_' . $order->order_status . '_EMAIL_BODY';
		$body_customer = JText::sprintf ( 'COM_BOOKPRO_CANCEL_ORDER_EMAIL_BODY', $order->order_number );
		
		// $body_customer=$this->fillCustomer($body_customer, $customer);
		// $body_customer=$this->fillOrder($body_customer,$order);
		$subject = JText::sprintf ( 'COM_BOOKPRO_ORDER_STATUS_CANCEL_EMAIL_SUB', $order->order_number );
		BookProHelper::sendMail ( $app->email_send_from, $app->email_send_from_name, $customer->email, $subject, $body_customer, true );
	}
	
	public function changeOrderStatus($order_id) {
		$orderModel = new BookProModelOrder ();
		$applicationModel = new BookProModelApplication ();
		$customerModel = new BookProModelCustomer ();
	
		$order = $orderModel->getItem ($order_id);
		$customerModel->setId ( $order->user_id );
		$customer = $customerModel->getComplexItem ($order->user_id);
		$this->app = $applicationModel->getObjectByCode ( $order->type );
		$msg = 'COM_BOOKPRO_ORDER_STATUS_' . $order->order_status . '_EMAIL_BODY';
		$body_customer = JText::_ ( $msg );
		$body_customer = $this->fillCustomer ( $body_customer, $customer );
		$body_customer = $this->fillOrder ( $body_customer, $order );
	
		BookProHelper::sendMail ( $this->app->email_send_from, $this->app->email_send_from_name, $customer->email, JText::_ ( 'COM_BOOKPRO_ORDER_STATUS_CHANGE_EMAIL_SUB' ), $body_customer, true );
	}
	
}
