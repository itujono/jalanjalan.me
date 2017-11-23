<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bookpro.php 27 2012-07-08 17:15:11Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');
use Joomla\Registry\Format\Json;
use Joomla\Registry\Registry;
// import needed JoomLIB helpers

AImporter::model ( 'roomratelog', 'bustrip' );
class BookProControllerRoomRate extends JControllerLegacy 

{
	
	/**
	 * Cancel edit operation.
	 * Check in subject and redirect to subjects list.
	 */
	function cancel() 

	{
		$mainframe = JFactory::getApplication ();
		
		$mainframe->enqueueMessage ( JText::_ ( 'Subject editing canceled' ) );
		
		$mainframe->redirect ( 'index.php?option=com_bookpro&view=bustrips' );
	}
	function emptyrate() {
		$mainframe = JFactory::getApplication ();
		$input = JFactory::getApplication ()->input;
		$room_id = $input->get ( 'room_id' );
		$db = JFactory::getDbo ();
		
		try {
			$db->transactionStart ();
			// Delete all existing rate and log
			$query = $db->getQuery ( true );
			$query->delete ( '#__bookpro_roomrate' )->where ( 'room_id=' . $room_id );
			$db->setQuery ( $query );
			$db->execute ();
			$db->transactionCommit ();
		} catch ( Exception $e ) {
			$mainframe->enqueueMessage ( $e->getMessage () );
			$db->transactionRollback ();
		}
		$this->setRedirect ( 'index.php?option=com_bookpro&view=roomrate&bustrip_id=' . $room_id );
	}
	
	function emptylog() {
		$mainframe = JFactory::getApplication ();
		$input = JFactory::getApplication ()->input;
		$room_id = $input->get ( 'room_id' );
		$db = JFactory::getDbo ();
	
		try {
			$db->transactionStart ();
			$query = $db->getQuery ( true );
			$query->delete ( '#__bookpro_roomratelog' )->where ( 'room_id=' . $room_id );
			$db->setQuery ( $query );
			$db->execute ();
				
			$db->transactionCommit ();
		} catch ( Exception $e ) {
			$mainframe->enqueueMessage ( $e->getMessage () );
			$db->transactionRollback ();
		}
		$this->setRedirect ( 'index.php?option=com_bookpro&view=roomrate&bustrip_id=' . $room_id );
	}
	
	
	
	
	
	/**
	 * Save subject and state on edit page.
	 */
	function apply() 

	{
		$this->save ( true );
	}
	
	/**
	 *
	 * Save subject.
	 *
	 *
	 *
	 * @param boolean $apply
	 *        	true state on edit page, false return to browse list
	 *        	
	 */
	
	function savedayrate() {
		$model = $this->getModel ( 'Roomrate', '', array () );
		$input = JFactory::getApplication ()->input;
		$data = $input->get ( 'jform', array (), 'array' );
		$model->save ( $data );
		JFactory::getApplication ()->enqueueMessage ( 'Update successful' );
		$this->setRedirect ( 'index.php?option=com_bookpro&view=bustrips' );
	}
	
	function savedate(){
		
		$input	= JFactory::getApplication()->input;
		$data	= $input->get('jform',array(),'array');
		AImporter::table('roomrate','job');
		$db=JFactory::getDbo();
		
		try {
			
			foreach ($data as $row) {
				$table=new TableRoomRate($db);
				$table->save($row);
			}
			
			//save job
			$job=new TableJob($db);
			$job->load(array('date'=>JFactory::getDate($input->get('date'))->toSql(),'route_id'=>$input->get('route_id')));
			$job->id=$input->get('job_id');
			$job->cid=$input->get('driver_id');
			$job->seat=$input->get('seat');
			$job->state=1;
			$job->date=$input->get('date');
			$job->route_id=$input->get('room_id');
			$job->store();
			
			echo json_encode("Saved");
			
		} catch (Exception $e) {
			
			echo json_encode("Save failed:".$e->getMessage());
		}
		die;
		
	}
	

	function delete()
	
	{
		$mainframe = JFactory::getApplication ();
		//echo "die";die;
		$input = JFactory::getApplication ()->input;
	
		$weekdays = $input->get ( 'weekday', null, 'array' );
	
		$startdate = new JDate ( $mainframe->getUserStateFromRequest ( 'rate.startdate', 'startdate', JFactory::getDate ()->format ( 'Y-m-d' ) ) );
		$startclone = clone $startdate;
		$enddate = new JDate ( $mainframe->getUserStateFromRequest ( 'rate.enddate', 'enddate', JFactory::getDate ()->add ( new DateInterval ( 'P60D' ) )->format ( 'Y-m-d' ) ) );
	
		$starttoend = $startdate->diff ( $enddate )->days;
	
		// delete old record
	
		$room_id = $input->get ( 'room_id' );
	
		$db = JFactory::getDbo ();
		
	
		try {
			$db->transactionStart ();
			$datearr=array();
			for($i = 0; $i <= $starttoend; $i ++) {
				$dw = ( int ) $startdate->format ( 'N' );
				if (in_array ( "$dw", $weekdays )) {
					$datearr[]='DATE_FORMAT(`date`,"%Y-%m-%d")='.$db->q($startdate->format('Y-m-d'));
				}
				$startdate = $startdate->add ( new DateInterval ( 'P1D' ) );
			}
			
			
			if(count($datearr)>0){
				$str=implode(' OR ', $datearr);			
				$query = $db->getQuery(true );
				$query->delete ( '#__bookpro_roomrate' )->where ( 'room_id=' . $room_id);
				$query->where('('.$str.')');
				//var_dump($query->dump());die;
				$db->setQuery ( $query );
				$db->execute ();
			}
				
			$db->transactionCommit ();
			
		} catch ( Exception $e ) {
			$db->transactionRollback ();
			$mainframe->enqueueMessage ( $e->getMessage () );
		}
	
		$this->setRedirect ( 'index.php?option=com_bookpro&view=roomrate&bustrip_id=' . $room_id );
	}
	
	
	
	function save($apply = false) 

	{
		$mainframe = JFactory::getApplication ();
		
		$input = JFactory::getApplication ()->input;
		
		$weekdays = $input->get ( 'weekday', null, 'array' );
		
		$startdate = new JDate ( $mainframe->getUserStateFromRequest ( 'rate.startdate', 'startdate', JFactory::getDate ()->format ( 'Y-m-d' ) ) );
		$startclone = clone $startdate;
		$enddate = new JDate ( $mainframe->getUserStateFromRequest ( 'rate.enddate', 'enddate', JFactory::getDate ()->add ( new DateInterval ( 'P60D' ) )->format ( 'Y-m-d' ) ) );
		
		$starttoend = $startdate->diff ( $enddate )->days;
		
		// delete old record
		
		$room_id = $input->get ( 'room_id' );
		$seat = $input->get ( 'seat' );
		$driver_id = $input->get ( 'driver_id' );
		$pricetype= $input->get ( 'pricetype', null, 'array' );
		
		//var_dump($pricetype);die;
		$adult= $input->get ( 'adult', null, 'array' );
		$adult_roundtrip= $input->get ( 'adult_roundtrip', null, 'array' );
		
		$db = JFactory::getDbo ();
		AImporter::table('job');
		try {
			$db->transactionStart ();
			
			
			$query = $db->getQuery ( true );
			$query->delete('#__bookpro_roomrate')->where('room_id='.$room_id)
			->where('DATE_FORMAT(date,"%Y-%m-%d") BETWEEN CAST('.$db->q($startdate).' AS DATE) AND CAST('.$db->q($enddate).' AS DATE)');
			$db->setQuery($query);
			$db->execute();
			
			
			for($i = 0; $i <= $starttoend; $i ++) {
				$dw = ( int ) $startdate->format ( 'N' );
				
				for ($j = 0; $j < count($pricetype); $j++) {
					
				
				if (in_array ( "$dw", $weekdays )) {
					
					
					//update job;
					
					$job=new TableJob($db);
					$job->load(array('date'=>$startdate->toSql (),'route_id'=>$room_id));
					if(!$job){
						$job->id=null;
					}
					$job->route_id=$room_id;
					$job->date=$startdate->toSql ();
					$job->seat=$seat;
					$job->cid=$driver_id;
					$job->state=1;
					$job->store();
					
					$values = array ();
					$query = $db->getQuery ( true );
					$query->insert ( '#__bookpro_roomrate' ); // ON DUPLICATE KEY UPDATE date='2014-11-25 00:00:00', room_id=20000,adult=10011
					$query->columns ( 'pricetype,room_id,date,adult,adult_roundtrip' );
					
					$temp = array (
							$pricetype[$j],
							$room_id,
							$db->quote ( $startdate->toSql () ),
							$adult[$j],
							$adult_roundtrip[$j],
					);
					
					$values [] = implode ( ',', $temp );
					$query->values ( $values );
					$sql=(string)$query;
					
					  $updates=  array('pricetype='.$pricetype[$j],'room_id='.$room_id,
							'date='.$db->quote ( $startdate->toSql ()),
							'adult='.$adult[$j],
							'adult_roundtrip='.$adult_roundtrip[$j]
							 );
					
					$sqltotal=$sql.' ON DUPLICATE KEY UPDATE '. implode(',', $updates) ;
					$db->setQuery ( $sqltotal );
					//echo $sqltotal;
					$db->execute ();
				}
				}
				$startdate = $startdate->add ( new DateInterval ( 'P1D' ) );
			}
			
			// save rate log
			/*
			$params = array (
					'adult' => $input->get ( 'adult', 0 ),
					'child' => $input->get ( 'child', 0 ),
					'infant' => $input->get ( 'infant', 0 ),
					'adult_roundtrip' => $input->get ( 'adult_roundtrip', 0 ),
					'child_roundtrip' => $input->get ( 'child_roundtrip', 0 ),
					'infant_roundtrip' => $input->get ( 'infant_roundtrip', 0 ),
					'start' => $startclone->toSql (),
					'end' => $enddate->toSql (),
					'weekday' => implode ( ',', $weekdays ) 
			);
			
			$query = $db->getQuery ( true );
			$query->insert ( '#__bookpro_roomratelog' );
			
			$query->columns ( 'room_id,params' );
			$reg = new Registry ();
			$reg->loadArray ( $params );
			
			$data = array (
					
					$room_id,
					$db->q ( ( string ) $reg ) 
			);
			$query->values ( implode ( ',', $data ) );
			$db->setQuery ( $query );
			$db->execute ();
			*/
			
			// TODO Save log rate
			
			$db->transactionCommit ();
			
			$mainframe->enqueueMessage ( 'Saved successful' );
		} catch ( Exception $e ) {
			$db->transactionRollback ();
			
			$mainframe->enqueueMessage ( $e->getMessage () );
		}
		
		$this->setRedirect ( 'index.php?option=com_bookpro&view=roomrate&bustrip_id=' . $room_id );
	}
	
	
	function save1($apply = false)
	
	{
		$mainframe = JFactory::getApplication ();
	
		$input = JFactory::getApplication ()->input;
	
		$weekdays = $input->get ( 'weekday', null, 'array' );
	
		$startdate = new JDate ( $mainframe->getUserStateFromRequest ( 'rate.startdate', 'startdate', JFactory::getDate ()->format ( 'Y-m-d' ) ) );
		$startclone = clone $startdate;
		$enddate = new JDate ( $mainframe->getUserStateFromRequest ( 'rate.enddate', 'enddate', JFactory::getDate ()->add ( new DateInterval ( 'P60D' ) )->format ( 'Y-m-d' ) ) );
	
		$starttoend = $startdate->diff ( $enddate )->days;
	
		// delete old record
	
		$room_id = $input->get ( 'room_id' );
	
		$db = JFactory::getDbo ();
	
		try {
			$db->transactionStart ();
				
			for($i = 0; $i <= $starttoend; $i ++) {
				$dw = ( int ) $startdate->format ( 'N' );
				if (in_array ( "$dw", $weekdays )) {
						
					// Delete all existing rate in this duration and fligh route
					// $query = $db->getQuery ( true );
					// $query->delete ( '#__bookpro_roomrate' )->where ( array (
					// 'room_id=' . $room_id,
					// 'date BETWEEN ' . $db->quote ( $startdate ) . ' AND ' . $db->quote ( $enddate )
					// ) );
					// $db->setQuery ( $query );
					// $db->execute ();
					// $query = $db->getQuery ( true );
					$values = array ();
					$query = $db->getQuery ( true );
					$query->insert ( '#__bookpro_roomrate' ); // ON DUPLICATE KEY UPDATE date='2014-11-25 00:00:00', room_id=20000,adult=10011
					$query->columns ( 'room_id,date,adult,child,infant,adult_roundtrip,child_roundtrip,infant_roundtrip,discount' );
					// $query->insert ( '#__bookpro_roomrate' );
					// $query->columns ( 'room_id,date,adult,child,infant,adult_roundtrip,child_roundtrip,infant_roundtrip,discount' );
						
					$temp = array (
							$input->get ( 'room_id' ),
							$db->quote ( $startdate->toSql () ),
							$input->get ( 'adult', 0 ),
							$input->get ( 'child', 0 ),
							$input->get ( 'infant', 0 ),
								
							$input->get ( 'adult_roundtrip', 0 ),
							$input->get ( 'child_roundtrip', 0 ),
							$input->get ( 'infant_roundtrip', 0 ),
							$input->getFloat ( 'discount', 0 )
					);
					$values [] = implode ( ',', $temp );
					$query->values ( $values );
					$sql=(string)$query;
						
					$updates=  array('room_id='.$input->get ( 'room_id' ),
							'date='.$db->quote ( $startdate->toSql ()),
							'adult='.$input->get ( 'adult', 0 ),
							'child='.$input->get ( 'child', 0 ),
							'infant='.$input->get ( 'infant', 0 ),
							'adult_roundtrip='.$input->get ( 'adult_roundtrip', 0 ),
							'child_roundtrip='.$input->get ( 'child_roundtrip', 0 ),
							'infant_roundtrip='.$input->get ( 'infant_roundtrip', 0 ),
							'discount='.$input->get ( 'discount', 0 )
					);
						
					$sqltotal=$sql.' ON DUPLICATE KEY UPDATE '. implode(',', $updates) ;
					$db->setQuery ( $sqltotal );
					$db->execute ();
				}
				$startdate = $startdate->add ( new DateInterval ( 'P1D' ) );
			}
				
			// save rate log
				
			$params = array (
					'adult' => $input->get ( 'adult', 0 ),
					'child' => $input->get ( 'child', 0 ),
					'infant' => $input->get ( 'infant', 0 ),
					'adult_roundtrip' => $input->get ( 'adult_roundtrip', 0 ),
					'child_roundtrip' => $input->get ( 'child_roundtrip', 0 ),
					'infant_roundtrip' => $input->get ( 'infant_roundtrip', 0 ),
					'start' => $startclone->toSql (),
					'end' => $enddate->toSql (),
					'weekday' => implode ( ',', $weekdays )
			);
				
			$query = $db->getQuery ( true );
			$query->insert ( '#__bookpro_roomratelog' );
				
			$query->columns ( 'room_id,params' );
			$reg = new Registry ();
			$reg->loadArray ( $params );
				
			$data = array (
						
					$room_id,
					$db->q ( ( string ) $reg )
			);
			$query->values ( implode ( ',', $data ) );
			$db->setQuery ( $query );
			$db->execute ();
				
			// TODO Save log rate
				
			$db->transactionCommit ();
			$mainframe->enqueueMessage ( 'Saved successful' );
		} catch ( Exception $e ) {
			$db->transactionRollback ();
			JErrorPage::render ( $e );
			$mainframe->enqueueMessage ( $e->getMessage () );
		}
	
		$this->setRedirect ( 'index.php?option=com_bookpro&view=roomrate&bustrip_id=' . $room_id );
	}
}

?>