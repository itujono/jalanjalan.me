<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: rooms.php 48 2012-07-13 14:13:31Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');


class BookProModelRoomRates extends JModelList
{
	
	public function __construct($config = array()) {
		 
		parent::__construct($config);
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = null, $direction = null) {
		// Initialise variables.
		$app = JFactory::getApplication('administrator');
	
		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
	
		$published = $app->getUserStateFromRequest($this->context . '.filter.room_id', 'filter_published', '', 'string');
		$this->setState('filter.room_id', $published);
	
		// List state information.
		parent::populateState('a.pricetype', 'asc');
	}
	
	protected function getListQuery() {
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
	
		// Select the required fields from the table.
		$query->select('a.*,c.title,c.id AS cgroup_id');
		$query->from('#__bookpro_roomrate AS a');
		$query->innerJoin('#__bookpro_cgroup AS c ON c.id=a.pricetype');
	
	
		// Filter by published state
		$published = $this->getState('filter.room_id');

		if($published)
			$query->where('a.room_id = ' . (int) $published);

		$date = $this->getState('filter.date');
		
		if($date)
			$query->where('DATE_FORMAT(a.date,"%Y-%m-%d") = ' . $db->q($date));
		
		
		//echo $query->dump();
		
		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');
		if ($orderCol && $orderDirn) {
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}
	
		return $query;
	}
	
	function getItems(){
		
		$items=parent::getItems();
		
		foreach ($items as $item) {

			unset($item->child);
			unset($item->infant);
			unset($item->child_roundtrip);
			unset($item->infant_roundtrip);
		}
		return $items;
		
	}
	

}

?>