<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: airports.php 100 2012-08-29 14:55:21Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');
jimport('joomla.application.component.modellist');
class BookProModelAirports extends JModelList
{
	/**
	 * Main table
	 *
	 * @var TableCustomer
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
					'l.id',
					'l.title',
			);
		}

		parent::__construct($config);
	}

	
	protected function populateState($ordering = null, $direction = null)
	{
		parent::populateState();
		$app = JFactory::getApplication();
		$id = JRequest::getVar('id', 0, '', 'int');
		$this->setState('airports.id', $id);
			
		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
			
	
			
		$state = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_search');
		$this->setState('filter.state', $state);
			
		$level = $this->getUserStateFromRequest($this->context . '.filter.level', 'filter_level', 0, 'int');
		$this->setState('filter.level', $level);
		
		$country_id = $this->getUserStateFromRequest($this->context . '.filter.country_id', 'filter_country_id', 0, 'int');
		$this->setState('filter.country_id', $country_id);
		
		$app = JFactory::getApplication();
		$value = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
		$limit = $value;
		$this->setState('list.limit', $limit);
			
		$value = $app->getUserStateFromRequest($this->context.'.limitstart', 'limitstart', 0);
		$limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
		$this->setState('list.start', $limitstart);
			
		$value = $app->getUserStateFromRequest($this->context.'.ordercol', 'filter_order', $ordering);
		$this->setState('list.ordering', $value);
		$value = $app->getUserStateFromRequest($this->context.'.orderdirn', 'filter_order_Dir', $direction);
		$this->setState('list.direction', $value);
		parent::populateState('a.lft', 'asc');
			
	}
	/**
	 * Get MySQL loading query for customers list
	 *
	 * @return string complet MySQL query
	 */
	protected function getListQuery()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*,country.country_name AS country_name');
		$query->from('#__bookpro_dest AS a');
		$query->join('LEFT', '#__bookpro_country AS country ON country.id = a.country_id');
		$query->where('parent_id <> 0');
		if ($level = $this->getState('filter.level'))
		{
			$query->where('a.level <= ' . (int) $level);
		}
		
		// Filter by access level.
		if ($access = $this->getState('filter.access'))
		{
			$query->where('a.access = ' . (int) $access);
		}
		$published = $this->getState('filter.state');
		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.state IN (0, 1))');
		}
		$listOrdering = $this->getState('list.ordering', 'a.lft');
		$listDirn = $db->escape($this->getState('list.direction', 'ASC'));
		if ($listOrdering == 'a.access')
		{
			$query->order('a.access ' . $listDirn . ', a.lft ' . $listDirn);
		}
		else
		{
			$query->order($db->escape($listOrdering) . ' ' . $listDirn);
		}
		
		//echo nl2br(str_replace('#__','jos_',$query));]v
		
		return $query;
	}
	
	function getDestinationByIds($Ids){
		 
		$query = 'SELECT `dest`.* FROM `' . $this->_table->getTableName() . '` AS `dest` ';
		$query .= 'WHERE `dest`.`id` IN (' . implode(',', $Ids).')';
		$this->_db->setQuery($query);
		return  $this->_db->loadObjectList();
	}
	 
	function getDestinationParents(){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('dest.*');
		$query->from('#__bookpro_dest AS dest');
		$query->where(array('dest.state=1','dest.parent_id=1'));
		
		$db->setQuery($query);
		$lists = $db->loadObjectList();
		
		return $lists;
	}
	function getDestbyParent($parent_id){
		$db = JFactory::getDbo();
		
		if($parent_id){
			$query = $db->getQuery(true);
			$query->select('dest.*');
			$query->from('#__bookpro_dest AS dest');
			$query->where('dest.state=1');
			$query->where('dest.parent_id='.$parent_id);
			$db->setQuery($query);
			$lists = $db->loadObjectList();
		}else{
			$lists = array();
		}
		return $lists;
	}

	/**
	 * Get MySQL filter criteria for customers list
	 *
	 * @return string filter criteria in MySQL format
	 */
	
	
}

?>