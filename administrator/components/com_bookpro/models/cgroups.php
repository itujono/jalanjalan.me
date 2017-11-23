<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: cgroups.php 102 2012-08-29 17:33:02Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
jimport('joomla.application.component.modellist');

class BookProModelCGroups extends JModelList
{
    
	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
					'a.id',
					'a.title',
			);
		}
		parent::__construct($config);
	}
	protected function populateState($ordering = null, $direction = null) {
		$app = JFactory::getApplication();
		$this->setState('filter.state', $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string'));
		parent::populateState('a.id', 'asc');
	}
	
	/**
	 * Get MySQL loading query for customers list
	 *
	 * @return string complet MySQL query
	 */
	protected function getListQuery() {
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*')->from('#__bookpro_cgroup AS a');
		$query->order($db->escape($this->state->get('list.ordering', 'a.id').' '.$this->state->get('list.direction', 'asc')));
		
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.state = ' . (int) $published);
		} else if ($published === '') {
			$query->where('(a.state IN (0, 1))');
		}
		
		return $query;
	}
    /**
     * Get MySQL loading query for customers list
     * 
     * @return string complet MySQL query
     */
   

  }

?>