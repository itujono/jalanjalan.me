<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bustrip.php 14 2012-06-26 12:42:05Z quannv $
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );

AImporter::helper ( 'bookpro' );
class BookProModelBusTrip extends JModelAdmin {
	public function getForm($data = array(), $loadData = true) {
		$form = $this->loadForm ( 'com_bookpro.bustrip', 'bustrip', array (
				'control' => 'jform',
				'load_data' => $loadData 
		) );
		if (empty ( $form ))
			return false;
		return $form;
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see JModelForm::loadFormData()
	 */
	protected function loadFormData() {
		$data = JFactory::getApplication ()->getUserState ( 'com_bookpro.edit.bustrip.data', array () );
		if (empty ( $data ))
			$data = $this->getItem ();
		return $data;
	}
	protected function populateState() {
		$table = $this->getTable ();
		$key = $table->getKeyName ();
		
		// Get the pk of the record from the request.
		
		$pk = JFactory::getApplication ()->input->getInt ( $key );
		if ($pk) {
			$this->setState ( $this->getName () . '.id', $pk );
		}
		
		// Load the parameters.
	}
	function getItem($pk = null) {
		$item = parent::getItem ( $pk );
		
		if ($item->id) {
			AImporter::model ( 'busstops' );
			$model = new BookproModelBusstops ();
			$state = $model->getState ();
			$state->set ( 'filter.bustrip_id', $item->id );
			$state->set ( 'filter.state', 1 );
			$state->set ( 'list.limit', 0 );
			$stops = $model->getItems ();
			$item->busstops = $stops;
		}
		return $item;
	}
	function getComplexItem($pk = null, $date = null) {
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( 'bustrip.*' );
		$query->select ( 'CONCAT(`dest1`.`title`,' . $this->_db->quote ( '-' ) . ',`dest2`.`title`) AS title' );
		$query->from ( '#__bookpro_bustrip AS bustrip' );
		$query->select ( 'agent.company,agent.brandname,agent.image as agent_logo,bus.seat' );
		$query->join ( 'LEFT', '#__bookpro_agent AS agent ON agent.id = bustrip.agent_id' );
		$query->select ( 'bus.title AS bus_name' );
		$query->join ( 'LEFT', '#__bookpro_bus AS bus ON bus.id = bustrip.bus_id' );
		$query->select ( 'seattemplate.block_layout AS block_layout' );
		$query->join ( 'LEFT', '#__bookpro_bus_seattemplate AS seattemplate ON `seattemplate`.`id` = `bus`.`seattemplate_id`' );
		$query->select ( 'dest1.title AS from_name' );
		$query->join ( 'LEFT', '#__bookpro_dest AS dest1 ON bustrip.from = dest1.id' );
		$query->select ( 'dest2.title AS to_name' );
		$query->join ( 'LEFT', '#__bookpro_dest AS dest2 ON bustrip.to = dest2.id' );
		$query->where ( 'bustrip.id = ' . $pk );
		$db->setQuery ( $query );
		
		//echo $query->dump();
		$bustrip = $db->loadObject ();
		
		if ($bustrip->id) {
			
			$query = $db->getQuery ( true );
			$query->select ( '*' )->from ( '#__bookpro_busstop' )->where ( 'bustrip_id=' . $pk );
			$query->order ( 'depart' );
			$db->setQuery ( $query );
			$bustrip->stations = $db->loadAssocList ( 'id' );
		}
		if ($date) {
			$db = JFactory::getDbo ();
			$query = $db->getQuery ( true );
			$query->select ( 'rate.*,c.title' );
			$query->from ( '#__bookpro_roomrate AS rate' );
			$query->innerJoin('#__bookpro_cgroup AS c ON c.id=rate.pricetype');
			$query->where ( 'rate.room_id=' . $pk );
			$query->where ( 'DATE_FORMAT(rate.date,"%Y-%m-%d")=' . $db->quote ( JFactory::getDate ( $date )->format ( 'Y-m-d' ) ) );
			$db->setQuery ( $query );
			$price = $db->loadObjectList ();
			$bustrip->price = $price;
		}
		return $bustrip;
	}
	function save($data) {
		if (! parent::save ( $data )) {
			return false;
		}
		
		
		//echo "<pre>";print_r($data);die;
		//TODO: Update record and add new is seperately
		$bustrip_id=$this->getState()->get('bustrip.id');
		//echo $bustrip_id;die;
		$busstops = JFactory::getApplication ()->input->post->get ( 'busstop', array (), 'array' );
		$db = JFactory::getDbo ();
		
		try {
			
			$db->transactionStart ();
			$query = $db->getQuery ( true );
			$query->delete ( '#__bookpro_busstop' )->where ( 'bustrip_id=' . $bustrip_id );
			$db->setQuery ( $query );
			$db->execute ();
			
			$query = $db->getQuery ( true );
			$query->insert ( '#__bookpro_busstop' );
			$query->columns ( 'id,bustrip_id,type,location,depart,price,state' );
			$values = array ();
			
			$ids = $busstops ['id'];
			$type = $busstops ['type'];
			
			JArrayHelper::toInteger($type);
			
				
			for($i = 0; $i < count ( $type ); $i ++) {
				if ($type [$i] > 0) {
					$temp = array (
							$ids [$i],
							$bustrip_id,
							$type [$i],
							$db->quote ( $busstops ['location'] [$i] ),
							$db->quote ( $busstops ['depart'] [$i] ),
							$db->quote($busstops ['price'] [$i]),
							1 
					);
					$values [] = implode ( ',', $temp );
				}
			}
			
			if(count($values)>0){
				$query->values ( $values );
				$db->setQuery ( $query );
				$db->execute ();
			}
			$db->transactionCommit ();
		} catch ( Exception $e ) {
			
			$db->transactionRollback ();
			JErrorPage::render($e);
			return false;
		}
		
		return true;
	}
	function getBookingTrip($params) {
		
		AImporter::model ( 'busstop' );
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( 'bustrip.*' );
		$query->select ( 'CONCAT(`dest1`.`title`,' . $this->_db->quote ( '-' ) . ',`dest2`.`title`) AS title' );
		$query->from ( '#__bookpro_bustrip AS bustrip' );
		$query->select ( 'agent.company,agent.brandname,agent.image as agent_logo' );
		$query->join ( 'LEFT', '#__bookpro_agent AS agent ON agent.id = bustrip.agent_id' );
		$query->select ( 'bus.title AS bus_name' );
		$query->join ( 'LEFT', '#__bookpro_bus AS bus ON bus.id = bustrip.bus_id' );
		$query->select ( 'seattemplate.block_layout AS block_layout' );
		$query->join ( 'LEFT', '#__bookpro_bus_seattemplate AS seattemplate ON `seattemplate`.`id` = `bus`.`seattemplate_id`' );
		$query->select ( 'dest1.title AS from_name' );
		$query->join ( 'LEFT', '#__bookpro_dest AS dest1 ON bustrip.from = dest1.id' );
		$query->select ( 'dest2.title AS to_name' );
		$query->join ( 'LEFT', '#__bookpro_dest AS dest2 ON bustrip.to = dest2.id' );
		$query->where ( 'bustrip.id = ' . $params ['onward'] ['id'] );
		$db->setQuery ( $query );
		$bustrip = $db->loadObject ();
		$bustrip->booked_seat = $params ['onward'] ['seat'];
		$bustrip->depart_date = $params ['onward'] ['date'];
		$busstopModel = new BookProModelBusstop ();
		if (isset ( $params ['onward'] ['boarding'] ))
			$bustrip->boarding = $params ['onward'] ['boarding'];
		if (isset ( $params ['onward'] ['dropping'] ))
			$bustrip->dropping = $params ['onward'] ['dropping'];
		
		$bustrips [] = $bustrip;
		
		if (isset ( $params ['return'] ['id'] )) {
			$query->clear ( 'where' );
			$query->where ( 'bustrip.id = ' . $params ['return'] ['id'] );
			$db->setQuery ( $query );
			$bustrip = $db->loadObject ();
			$bustrip->booked_seat = $params ['return'] ['seat'];
			$bustrip->depart_date = $params ['return'] ['date'];
			if (isset ( $params ['return'] ['boarding'] ))
				$bustrip->boarding = $params ['return'] ['boarding'];
			if (isset ( $params ['return'] ['dropping'] ))
				$bustrip->dropping = $params ['return'] ['boarding'];
			
			$bustrips [] = $bustrip;
		}
		
		return $bustrips;
	}
	public function publish(&$pks, $value = 1) {
		$user = JFactory::getUser ();
		$table = $this->getTable ();
		$pks = ( array ) $pks;
		
		// Attempt to change the state of the records.
		if (! $table->publish ( $pks, $value, $user->get ( 'id' ) )) {
			$this->setError ( $table->getError () );
			
			return false;
		}
		
		return true;
	}
	public function rebuild() {
		// Get an instance of the table object.
		$table = $this->getTable ();
		
		if (! $table->rebuild ()) {
			$this->setError ( $table->getError () );
			return false;
		}
		
		// Clear the cache
		$this->cleanCache ();
		
		return true;
	}
}

?>