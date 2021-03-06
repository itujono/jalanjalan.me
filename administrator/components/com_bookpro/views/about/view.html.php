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

jimport( 'joomla.application.component.view' );
AImporter::helper('bpcheck','route');

class BookproViewAbout extends JViewLegacy {
	
	/**
	* About view display method
	* @return void
	* */
	function display($tpl = null) {
		
		$check = new SubscriptionCheck;
		$config=AFactory::getConfig();
		$result = $check->CheckKey($config->hostname);
		/* Get the IP Address  */
		$ipaddress = gethostbyname($result['hostname']);
		/* Assign the values */
		$this->assignRef('result', $result['result']);
		$this->assignRef('expiredate', $result['uxdate']);
		$this->assignRef('hostname', $result['hostname']);
		$this->assignRef('ipaddress', $ipaddress);
		
		
		
		
		/* Display it all */
		parent::display($tpl);
	}
}
?>