<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: order.php 66 2012-07-31 23:46:01Z quannv $
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import needed JoomLIB helpers
class BookProControllerOrder extends JControllerForm {
	var $_model;
	
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->_model = $this->getModel('order');
		$this->_controllerName = CONTROLLER_ORDER;
	}
	function createticket() {
		$mainframe = JFactory::getApplication ();
		$mainframe->redirect ( JUri::base () . 'index.php?option=com_bookpro&view=bussearch' );
	}


	function save($apply = false)
	{
		$db = JFactory::getDbo();
		$mainframe = &JFactory::getApplication ();
		$task = $this->getTask();
		$input 		= JFactory::getApplication ()->input;
		$jform 		= $input->get ( 'jform', array (), 'array' );
		$order_id 	= $jform['id'];
		
		try {
			$db->transactionStart ();
			$this->_model->save($jform);
			
			AImporter::model('passenger');
			$model 		= new BookproModelPassenger();
			$dataall 		= $input->get ( 'person', array (), 'array' );
			$person = json_decode(json_encode($dataall),false);
		
			foreach ($person as $passenger){
				$Tablepassenger = JTable::getInstance('Passenger','Table');
				
				$Tablepassenger->bind ( $passenger );
				$Tablepassenger->check();	
				$Tablepassenger->store ();
			}
			
			
			$db->transactionCommit ();
			JFactory::getApplication ()->enqueueMessage ( 'Update successful', 'message');
		}catch (Exception $e){
			$db->transactionRollback ();
			JErrorPage::render ( $e );
			$mainframe->enqueueMessage ( $e->getMessage () );
		}
		
		
	
		
	
		if(($task=="apply")){
			$this->setRedirect ( 'index.php?option=com_bookpro&view=com_bookpro&view=order&layout=edit&id='.$order_id );
		}elseif(($task=="save")){
			$this->setRedirect('index.php?option=com_bookpro&view=orders');
		}
	
	
	}
	function changePayStatus() {
		
		$input = JFactory::getApplication ()->input;
		$id = $input->post->get ( 'paystatus_id');
 
	
		$value = $input->post->get ( 'paystatus' );
		
		if($id && $value){
			$db = JFactory::getDbo ();
			$query = $db->getQuery ( true );
			$query->update ( $db->quoteName ( '#__bookpro_orders' ) );
			$query->set ( $db->quoteName ( 'pay_status' ) . ' = ' . $db->quote ( $value ) );
			$query->where ( $db->quoteName ( 'id' ) . ' = ' . $id );
			$db->setQuery ( $query );
			
			try {
				$db->execute ();
				
				if($value=="SUCCESS"){
					//trigger SMS
					
					JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_bookpro/tables' );
					$order = JTable::getInstance('Orders', 'Table');
					$order->load($id);
					
					$dispatcher = JEventDispatcher::getInstance();
					JPluginHelper::importPlugin ('bookpro');
					$smsresult=$dispatcher->trigger('onBookproSendSms',array($order));
					
					
				}
				
				echo json_encode(true);
		 
			} catch ( RuntimeException $e ) {
			 	echo json_encode(false);
			}
			
		}
	 	die;
	 
	}
	/**
	 * 
	 * @return boolean
	 */
	function changeOrderstatus() {
		
		$input = JFactory::getApplication ()->input;
		$id = $input->post->get ( 'orderstatus_id' );
		$value = $input->post->get ( 'orderstatus' );
	 
		if ($id && $value) {
			$db = JFactory::getDbo ();
			$query = $db->getQuery ( true );
			$query->update ( $db->quoteName ( '#__bookpro_orders' ) );
			$query->set ( $db->quoteName ( 'order_status' ) . ' = ' . $db->quote ( $value ) );
			$query->where ( $db->quoteName ( 'id' ) . ' = ' . $id );
			$db->setQuery ( $query );
		
			try {
				$db->execute ();
				//send email						
				$this->sendEmailToCustomer($id,$value);   
				 
				echo json_encode(true);  
			} catch ( RuntimeException $e ) {
			 	echo json_encode(false);
			}
		}
	
	 
	
		die;
		
	}
	
	function sendEmailToCustomer($id,$value){
		if($id){
			$model=new BookProModelOrder();
			$model->setId($id);
			$order=$model->getObject();
		 
			$db = JFactory::getDbo();
			$query = $db->getQuery ( true );
			$query->select('a.email');
				
			$query->from('#__bookpro_customer AS a');
			$query->innerJoin('#__bookpro_orders AS b ON b.user_id=a.id');
			$query->where('b.id='.$id);
			$db->setQuery($query);
			$data=$db->loadAssoc();	
		}
		
		$link=JUri::root().('index.php?option=com_bookpro&view=ticket&layout=ticket&order_number='.$order->order_number);
		$subject=JText::_('COM_BOOKPRO_SUBJECT_MAIL_CONFIRM_PAYMENT');
		$body=JText::sprintf('COM_BOOKPRO_BODY_MAIL_CONFIRM_PAYMENT',$link);
		
		$subjectCancel=JText::_('COM_BOOKPRO_SUBJECT_MAIL_CANCEL_PAYMENT');
		$bodyCacel=JText::sprintf('COM_BOOKPRO_BODY_MAIL_CANCEL_PAYMENT');
		
		$config = JFactory::getConfig();
		
		$post['fromname'] = $config->get('fromname');
		$post['mailfrom'] = $config->get('mailfrom');
		$post['sitename'] = $config->get('sitename');
		$post['siteurl'] = JUri::root();
 		if($value=="CONFIRMED"){
		JFactory::getMailer()->sendMail($post['mailfrom'], $post['fromname'], $data['email'], $subject, $body);
 		}
 		
 		if($value=="CANCELLED"){
 			JFactory::getMailer()->sendMail($post['mailfrom'], $post['fromname'], $data['email'], $subjectCancel, $bodyCacel);
 		}
	}
	
	
	
	
	
	
}

?>