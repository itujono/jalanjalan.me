 <?php

 /**
  * @package 	Bookpro
  * @author 		Ngo Van Quan
  * @link 		http://joombooking.com
  * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
  * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
  * @version 	$Id: orders.php 56 2012-07-21 07:53:28Z quannv $
  **/
 defined ( '_JEXEC' ) or die ( 'Restricted access' );


class BookproModelpassengers extends JModelList
{
	public function __construct($config = array())
	{		
	
		parent::__construct($config);		
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
			parent::populateState();
				
			
			// Load the filter state.
			$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
			$this->setState('filter.search', $search);
			
			$order_id = $this->getUserStateFromRequest($this->context . '.filter.order_id', 'filter_order_id');
			$this->setState('filter.order_id', $order_id);
			
			$pay_status = $this->getUserStateFromRequest($this->context . '.filter.pay_status', 'filter_pay_status');
			$this->setState('filter.pay_status', $pay_status);
			
			$route_id = $this->getUserStateFromRequest($this->context . '.filter.route_id', 'filter_route_id');
			$this->setState('filter.route_id', $route_id);
			
			$ids = $this->getUserStateFromRequest($this->context . '.filter.ids', 'filter_ids');
			$this->setState('filter.ids', $ids);
			
			$return_route_id = $this->getUserStateFromRequest($this->context . '.filter.return_route_id', 'filter_return_route_id');
			$this->setState('filter.return_route_id', $return_route_id);
		
			
			parent::populateState('a.id','DESC');

					
	}
    		
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('passengerlist.id');
						return parent::getStoreId($id);
	}	
	
	/**
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return	object	A JDatabaseQuery object to retrieve the data set.
	 */
	protected function getListQuery()
	{
		
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);		
		$query->select('a.*');
		$query->from('#__bookpro_passenger AS a');
	
		 // Filter by search in title
		$search = $this->getState('filter.search');
		
		if (!empty($search))
		{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.firstname LIKE ' . $search . '  OR a.lastname LIKE  ' . $search . '  OR a.email LIKE  ' . $search . '   OR a.phone LIKE ' . $search . ')');
			
		}
		$query->join('LEFT', '#__bookpro_cgroup AS cgroup ON a.group_id = cgroup.id');
		$query->select('cgroup.title AS group_title');
		
		$query->select('od.seat AS oseat, od.return_seat AS oreturn_seat,od.order_number,od.order_number,od.order_status,od.params AS oparams,od.created,od.pay_method');
		
		$query->join('INNER', '#__bookpro_orders AS od ON a.order_id = od.id');
		$order_id = $this->getState('filter.order_id');
		
		//
		$query->join('LEFT', '#__bookpro_country AS country ON a.country_id = country.id');
		$query->select('country.country_name as country');
		
		if ($order_id) {
			$query->where('a.order_id='.$order_id);
		}		
		$route_id = $this->getState('filter.route_id');
		$return_route_id = $this->getState('filter.return_route_id');
		if ($route_id) {
			$query->where('od.route_id='.$route_id);
			
		}
		if ($return_route_id) {
			$query->where('od.return_route_id='.$return_route_id);
		}
		
		$depart_date = $this->getState('filter.start');
		
		if ($depart_date) {
			$query->where ( ' DATE_FORMAT(od.start,"%Y-%m-%d")=' . $db->quote ( $depart_date ) );
		}
		
		
		$return_date = $this->getState('filter.return_start');
		
		if ($return_date) {
			$query->where ( ' DATE_FORMAT(od.return_start,"%Y-%m-%d")=' . $db->quote ( $return_date ) );
		}
		
		$ids = $this->getState ( 'filter.ids' );
		if ($ids) {
			$query->where ('a.id IN ('. $ids.')');
		}
		
		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'ASC');
		if(empty($orderCol)) $orderCol = 'id';
		if(empty($orderDirn)) $orderDirn = 'DESC'; 		
		$query->order($db->escape($orderCol . ' ' . $orderDirn));
		
		$autocomplete = $this->getState ( 'filter.autocomplete' );
		if($autocomplete)
		 	$query->group('a.id,a.firstname,a.phone');
		else 
			$query->group('a.id');
		
		return $query;
	}
	
	public function getItems(){
		
		$items=parent::getItems();
		
		foreach ($items as $item){
			
			$item->oparams=json_decode($item->oparams,true);
			$item->name=$item->firstname.' '. $item->lastname;
			
		}
		
		return $items;
		
	}
	
	public function  getItemsByIds($cid,$route_id){
		
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$query->select('a.*,cgroup.title AS group_title');
		$query->select('od.seat AS oseat, od.return_seat AS oreturn_seat,od.order_number,od.order_status,od.params AS oparams,od.created,od.pay_method');
		$query->from('#__bookpro_passenger as a');
		$query->join('LEFT', '#__bookpro_cgroup AS cgroup ON a.group_id = cgroup.id');
		//$query->join('LEFT', '#__bookpro_bustrip AS bustrip ON a.route_id = bustrip.id');
		$query->join('LEFT', '#__bookpro_orders AS od ON a.order_id = od.id');
		$query->join('LEFT', '#__bookpro_country AS country ON a.country_id = country.id');
		$query->select('country.country_name as country');
		
		$query->where('a.id IN ('.implode(',', $cid).')');
		// Add the list ordering clause.
		$query->order($db->escape('a.firstname ASC'));
		$db->setQuery($query);
		$passengers=$db->loadObjectList();
		for ($i = 0; $i < count($passengers); $i++) {
				
			
			$passenger=$passengers[$i];
			
			if($route_id==$passenger->route_id){
				$passenger->onward=true;
			}else {
				$passenger->onward=true;
			}
			
			$registry = new Joomla\Registry\Registry();
			$registry->loadString($passenger->oparams);
			$params = $registry->toArray();
			$passenger->name=$passenger->firstname.' '. $passenger->lastname;
			if(isset($params['return'])){
				$passenger->roundtrip=1;
				$passenger->type=JText::_('COM_BOOKPRO_ROUNDTRIP');
				
			}else{
				$passenger->roundtrip=0;
				$passenger->type=JText::_('COM_BOOKPRO_ONEWAY');
			}
			
		}
		//echo "<pre>";print_r($passengers);die;
		return $passengers;
		
	}	
}