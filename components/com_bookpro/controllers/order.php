<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bookpro.php 27 2012-07-08 17:15:11Z quannv $
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );
class BookProControllerOrder extends JControllerLegacy {
	
	public function display($cachable = false, $urlparams = false) {
		
	}
	function cancelorder() {
		$order_id = JRequest::getVar ( 'order_id' );
		if (! class_exists ( 'BookProModelOrder' )) {
			AImporter::model ( 'orders' );
		}
		
		AImporter::helper ( 'bus', 'email', 'paystatus', 'refund' );
		$model = new BookProModelOrder ();
		$order = $model->getItem ( $order_id );
		
		$orderinfos = BusHelper::getInFosList ( $order->id );
		$orderinfo = $orderinfos [0];
		$cancel_amount = RefundHelper::refundPrice ( $orderinfo, $order );
		$order->refund_amount = $cancel_amount;
		
		PayStatus::init ();
		$order->order_status = 'CANCELLED';
		$table = JTable::getInstance ( 'Orders', 'Table' );
		
		$table->id = $order->id;
		$table->refund_amount = $cancel_amount;
		$table->order_status = "CANCELLED";
		// $table->pay_status = PayStatus::$REFUND->getValue();
		if (! $table->store ()) {
			JError::raiseError ( 500, $table->getError () );
		}
		$mail = new EmailHelper ();
		$mail->cancelOrder ( $order->id );
		$msg = JText::_ ( 'COM_BOOKPRO_CANCEL_CUSTOMER_MSG' );
		$this->setRedirect ( JURI::root () . 'index.php?option=com_bookpro&view=ticket&layout=ticket&order_number=' . $order->order_number, $msg, 'info' );
		return;
	}
	function print_ticket(){
		
		AImporter::model('order');
		AImporter::helper('passenger');
		//$cid = JFactory::getApplication()->input->get('cid', array(), 'array');
		$order_id = JFactory::getApplication()->input->get('order_id');
		
		$model = new BookProModelOrder ();
		$order = $model->getComplexItem ( $order_id );
		
		//echo "<pre>";print_r($order);die;
		
		
		$cid=JArrayHelper::getColumn($order->passengers, 'id');
		$route_id=array($order->passengers[0]->route_id);
		if($order->passengers[0]->return_route_id){
			$route_id[]=$order->passengers[0]->return_route_id;
		}
		$passengers=$order->passengers;
		
		foreach ($passengers as $passenger) {
			$passenger->total=$order->total;
			
			//$registry = new Joomla\Registry\Registry;
			//$registry->loadString($order->params);
			//$passenger->oparams = $registry->toArray();
			$passenger->order_status=$order->order_status;
			$passenger->oparams=$order->params;
			$passenger->order_number=$order->order_number;
			$passenger->created=$order->created;
			$passenger->pay_method=$order->pay_method;
			$passenger->name=$passenger->firstname.' '. $passenger->lastname;
			
		}
		//echo "<pre>";print_r($passengers);die;
		
		//JArrayHelper::toInteger($cid);
		//$model=new BookproModelpassengers();
		//$result=array();
		//foreach ($route_id as $id){
			//$passengers=$model->getItemsByIds($cid,$id);
			//array_merge($result,$passengers);
		//}
		
		$view=$this->getView('ticket','html','BookProView');
		$view->passengers=PassengertHelper::formatPassenger($passengers);
		//echo "<pre>";print_r($order->passengers);die;
		$view->display();
		
	}
	function cancel() {
		$order_id = JRequest::getVar ( 'order_id' );
		if (! class_exists ( 'BookProModelOrder' )) {
			AImporter::model ( 'orders' );
		}
		$model = new BookProModelOrder ();
		$model->setId ( $order_id );
		$order = $model->getObject ();
		$order->order_status = 'CANCELLED';
		if (! $order->store ()) {
			JError::raiseError ( 500, $row->getError () );
		}
		$this->setRedirect ( JURI::root () . 'index.php?option=com_bookpro&view=mypage' );
		return;
	}
	function applycoupon() {
		$input = JFactory::getApplication ()->input;
		$code = $input->getString ( 'coupon' );
		$order_id = $input->getInt ( 'order_id' );
		
		AImporter::table ( 'orders', 'coupon' );
		$coupon = JTable::getInstance ( 'Coupon', 'table' );
		$coupon->load ( array (
				'code' => $code 
		) );
		
		$check = true;
		if ($coupon) {
			if (( int ) $coupon->total == 0) {
				$check = false;
				$msg = JText::_ ( 'COM_BOOKPRO_COUPON_INVALID' );
			} else {
				
				$order = JTable::getInstance ( 'Orders', 'table' );
				$order->load ( $order_id );
				
				if ($order->discount > 0) {
					$check = false;
					$msg = JText::_ ( 'COM_BOOKPRO_COUPON_APPLY_ERROR' );
				} else {
					
					if ($coupon->subtract_type == 1) {
						$discount = ($order->total * $coupon->amount) / 100;
						$order->total = $order->total - $discount;
						$order->discount = $discount;
					} else {
						$order->total = $order->total - $coupon->amount;
						$order->discount = $coupon->amount;
					}
					$order->coupon_id = $coupon->id;
					$coupon->total = $coupon->total - 1;
					$coupon->store ();
					$order->store ();
					$msg = JText::_ ( 'COM_BOOKPRO_COUPON_VALID' );
				}
			}
		} else {
			$check = false;
			$msg = JText::_ ( 'COM_BOOKPRO_COUPON_INVALID' );
		}
		$this->setRedirect ( JURI::base () . 'index.php?option=com_bookpro&view=formpayment&order_id=' . $order_id . '&' . JSession::getFormToken () . '=1', $msg );
		return;
	}
	function changestatus() {
		$post = JFactory::getApplication()->input;;
		$order_status = $post->get('order_status');
		$id=$post->get('id');
		$ids= String::substr($id, 6) ;
		AImporter::table ( 'orders' );
		$order = JTable::getInstance ( 'Orders', 'table' );
		$order->load ($ids);
		$order->order_status=$order_status;
		return $order->store();
	}
	function update(){
		AImporter::helper('orderstatus');
		$input = JFactory::getApplication ()->input;
		$order_id = $input->getInt ( 'order_id' );
		$order_status = $input->get('order_status');
		AImporter::table ( 'orders' );
		$orders = JTable::getInstance ( 'Orders', 'table' );
		$orders->load($order_id);
		$orders->order_status=$order_status;
		if($orders->store()){
			 echo json_encode(OrderStatus::format($order_status));
		}
		else{
			echo json_encode(false);
		}
		die;
		
		
	}
	function detail() {
		$order_id = JRequest::getInt ( 'order_id' );
		$user = JFactory::getUser ();
		
		if ($user->get ( 'guest' ) == 1) {
			$return = 'index.php?option=com_bookpro&controller=order&task=viewdetail&order_id=' . $order_id;
			$url = 'index.php?option=com_users&view=login';
			$url .= '&return=' . urlencode ( base64_encode ( $return ) );
			$this->setRedirect ( $url, false );
			return;
		} else {
			
			if (! class_exists ( 'BookProModelOrder' )) {
				AImporter::model ( 'order' );
			}
			$model = new BookProModelOrder ();
			
			$order = $model->getItem ( $order_id );
			$view = $this->getView ( 'orderdetail', 'html', 'BookProView' );
			$view->assign ( 'order', $order );
			$view->display ();
			return;
		}
	}
}