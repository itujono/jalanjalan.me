<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: controller.php 129 2012-09-10 04:34:01Z quannv $
 **/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

AImporter::helper('bookpro');
class BookProController extends JControllerLegacy
{
	
	

	public function display($cachable = false, $urlparams = false)
	{
		// Get the document object.
		$document	= JFactory::getDocument();
		// Set the default view name and format from the Request.
		$vName	 = JRequest::getCmd('view', 'login');
		$Itemid=JRequest::getVar('Itemid');
		
		$user = JFactory::getUser();
		switch ($vName) {
			case 'profile':

				// If the user is a guest, redirect to the login page.

				if ($user->get('guest') == 1) {
					$return = 'index.php?option=com_bookpro&view=profile';
					$url    = 'index.php?option=com_bookpro&view=login';
					$url   .= '&return='.urlencode(base64_encode($return));
					$this->setRedirect(JRoute::_($url), false);
					return;
				}
				break;

			case 'mypage':

				// If the user is a guest, redirect to the login page.
				if ($user->get('guest') == 1) {
					$return = 'index.php?option=com_bookpro&view=mypage';
					$url    = 'index.php?option=com_bookpro&view=login';
					$url   .= '&return='.urlencode(base64_encode($return));
					$this->setRedirect(JRoute::_($url), false);
					return;
				}
				break;
				
				case 'pos':
				
					// If the user is a guest, redirect to the login page.
					if ($user->get('guest') == 1) {
						$return = 'index.php?option=com_bookpro&view=pos&Itemid='.$Itemid;
						$url    = 'index.php?option=com_bookpro&view=login';
						$url   .= '&return='.urlencode(base64_encode($return));
						$this->setRedirect(JRoute::_($url), false);
						return;
					}
					break;
				
			case 'driverreport':
				
				if ($user->get('guest') == 1) {
						
					$return = 'index.php?option=com_bookpro&view=driverreport';
					$url    = 'index.php?option=com_users&view=login';
					$url   .= '&return='.urlencode(base64_encode($return));
					$this->setRedirect(JRoute::_($url), false);
					return;
				}
				break;
			case 'agentpage':
			
				// If the user is a guest, redirect to the login page.
				
				if ($user->get('guest') == 1) {
					
					$return = 'index.php?option=com_bookpro&view=agentpage';
					$url    = 'index.php?option=com_users&view=login';
					$url   .= '&return='.urlencode(base64_encode($return));
					$this->setRedirect(JRoute::_($url), false);
					return;
				}
				break;
			case 'login':
				if(!$user->get('guest')){
					$return=JRequest::getVar('return');
					$this->setRedirect(base64_decode($return));
				}
				break;

		}
		
		JRequest::setVar('view', $vName);
		parent::display();

	}
	

	
	function listdestination()
	{

		$desfrom=JRequest::getVar( 'desfrom',0);
		$model = $this->getModel('BookPro');
		$dests = $model->getToAirportByFrom($desfrom);
		$return = "<?xml version=\"1.0\" encoding=\"utf8\" ?>";
		$return .= "<options>";
		$return .= "<option id='0'>".JText::_( 'TO' )."</option>";
		if(is_array($dests)) {
			foreach ($dests as $dest) {
				$return .="<option id='".$dest->key."'>".JText::_($dest->value)."</option>";
			}
		}
		$return .= "</options>";
		echo $return;
		//$mainframe->close();
	}
	
	function js() {
		header('Content-type: text/javascript');
		require_once( JPATH_COMPONENT_SITE.'/assets/js/master.js');
		die();
	}
	function restore(){
		BookProHelper::runsql('dummy.sql');
		die;
	}
	//release the hold booking (Set status is pending)
	function unhold(){
		
		//echo JFactory::getDate()->toSql(true);
		
		$expired=JBFactory::getConfig()->get('expired_hour',2);
		$method=JBFactory::getConfig()->get('hold_payment_method','');
		
		$methods=explode(',', $method);
		
		foreach($methods as $word){
			$sql[] = 'pay_method LIKE %'.trim($word).'%';
		}
		
		
		$current=JFactory::getDate('now')->sub(new DateInterval('PT'.$expired.'M'))->toSql();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update('#__bookpro_orders ')->set('order_status="PENDING"')->where('order_status="CONFIRMED"')->where('pay_status="PENDING"')->where(implode(' OR ', $sql))
		->where('created <'.$db->q($current));
		$db->setQuery($query);
		//echo $query->dump();
		$curror=$db->execute();
		//var_dump($curror);
		die;
		
		
	}
	
	/**
	 * 
	 * @param unknown $from_id
	 * @param unknown $to_id
	 */
	function getdepart_date(){

		$input=JFactory::getApplication()->input;
		$from=$input->get('from');
		$to=$input->get('to');
		$bustrip_id=$input->get('bustrip_id');
		if($from && $to){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('DATE_FORMAT(p.date,"%d-%m-%Y")')->from('#__bookpro_roomrate  AS p')->innerJoin('#__bookpro_bustrip AS b ON b.id=p.room_id')
			->where(array('b.from='.$from,'b.to='.$to,'b.state=1','p.date >='.$db->quote(JFactory::getDate()->format('Y-m-d 00:00:00'))))
			->group('p.date');
			$db->setQuery($query);
			$dates= json_encode($db->loadColumn());
			//$dates=str_replace('-','/', $dates);
			echo $dates;
			die;
		}
		if($bustrip_id){
			
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('DATE_FORMAT(p.date,"%d-%m-%Y")')->from('#__bookpro_roomrate  AS p')
			->where(array('p.room_id='.$bustrip_id,'p.date >='.$db->quote(JFactory::getDate()->format('Y-m-d 00:00:00'))))
			->group('p.date');
			$db->setQuery($query);
			$dates= json_encode($db->loadColumn());
			//$dates=str_replace('-','/', $dates);
			echo $dates;
			die;
			
		}
		
		else 
			return FALSE;
		
	}


	



}
