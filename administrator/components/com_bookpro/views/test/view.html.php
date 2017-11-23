<?php
/**
 * 
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id$
 **/

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

AImporter::helper('email');
class BookproViewTest extends JViewLegacy {
	
	/**
	* About view display method
	* @return void
	* */
	function display($tpl = null) {
		
		//echo JHtml::date('now','d-m-Y H:i:s');echo "<br>";
		echo JFactory::getDate()->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'))->format('d-m-Y H:i:s A',true);echo "<br>";
		
		$dd=JFactory::getDate(JHtml::date('now','d-m-Y H:i:s'));
		$dd = $dd->add( new DateInterval('PT5H'));
		echo $dd->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'))->format('d-m-Y H:i:s A');echo "<br>";
		$now=$dd->getTimestamp();
		
		$book="26-01-2016 19:30";
		$route_date=JFactory::getDate($book)->getTimestamp();
		$route_date1=JFactory::getDate('26-01-2016 19:32')->getTimestamp();
		//echo $cutofftime;echo "<br>";
		echo $route_date;echo "<br>";
		//echo $route_date1;echo "<br>";
		echo $now;echo "<br>";
		
		if ($route_date > $now)
			echo ">";else echo "<";
		die;
		
		$order_id=JFactory::getApplication()->input->get('order_id');
		$mail=new EmailHelper();
		$mail->sendMail($order_id);
		die;
		/* Display it all */
		parent::display($tpl);
	}
}
?>