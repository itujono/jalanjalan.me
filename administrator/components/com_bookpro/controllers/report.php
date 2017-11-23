<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: airport.php 66 2012-07-31 23:46:01Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

class BookProControllerReport extends JControllerLegacy
{
    
	function cancelroute(){
		
			$mainframe = JFactory::getApplication ();
			$input = JFactory::getApplication ()->input;
			$room_id = $input->get ( 'id' );
			$date = $input->get ( 'date' );
			$db = JFactory::getDbo ();
			$query = $db->getQuery ( true );
			$query->delete ( '#__bookpro_roomrate' )->where ( 'room_id=' . $room_id )->where('date='.$db->q($date));
			$db->setQuery ( $query );
			$db->execute ();
			$this->setRedirect('index.php?option=com_bookpro&view=reports');
		
		
	}

}

?>