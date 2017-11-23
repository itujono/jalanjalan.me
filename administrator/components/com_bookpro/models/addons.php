<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: controller.php 104 2012-08-29 18:01:09Z quannv $
 **/

defined('_JEXEC') or die;


class BookProModelAddons extends JModelList
{

	public function __construct($config =array()){
		if (empty($config['filter_fields'])){
			$config['filter_fields'] = array(
					'id', 'a.id',
					'title', 'a.title',
					'code', 'a.code',
					'state', 'a.state',
					'price', 'a.price',
					'child_price', 'a.child_price',
			);
		}

		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null)
	{
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		
		$this->setState('filter.search', $search);
		$this->setState('filter.state', $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string'));
		
		parent::populateState('a.title', 'asc');
	}

	protected function getListQuery()
	{
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$query->select('*')->from('#__bookpro_addon AS a');

		//Filter by search in code
		$search = $this->getState('filter.search');
		if(!empty($search))
		{
			$search = $db->Quote('%'.$db->escape($search, true).'%');
			$query->where('(a.title LIKE '.$search.')');
		}
		$published = $this->getState('filter.state');
		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		
		// Add the list ordering clause
		$listOrdering = $this->getState('list.ordering', 'a.state');
		$listDirn = $db->escape($this->getState('list.direction', 'ASC'));
		
		$query->order($db->escape($listOrdering) . ' ' . $listDirn);
		
		//echo $query->dump();
		
		return $query;
	}


	

}