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

//import needed JoomLIB helpers
jimport('joomla.application.component.modellist');

class BookProModelWiretransfers extends JModelList
{
    
	public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'wiretransfer.payment_date',
                
            );
        }
        parent::__construct($config);
    }
    protected function populateState($ordering = null, $direction = null) {
    	$app = JFactory::getApplication();
    	$company_name = $this->getUserStateFromRequest($this->context . '.filter.company_name', 'company_name');
    	$this->setState('filter.company_name', $company_name);
        
        $fromdate = $this->getUserStateFromRequest($this->context . '.filter.fromdate', 'filter_from_date');
    	$this->setState('filter.fromdate', $fromdate);
        
        $todate = $this->getUserStateFromRequest($this->context . '.filter.todate', 'filter_to_date');
    	$this->setState('filter.todate', $todate);
        
        $wire_status = $this->getUserStateFromRequest($this->context . '.filter.wire_status', 'wire_status');
    	$this->setState('filter.wire_status', $wire_status);
        
       
    	$this->setState('filter.state', $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string'));
    	$value = $this->getUserStateFromRequest($this->context.'.ordercol', 'filter_order', $ordering);
    	$this->setState('list.ordering', $value);
    	$value = $this->getUserStateFromRequest($this->context.'.orderdirn', 'filter_order_Dir', $direction);
    	$this->setState('list.direction', $value);
    	parent::populateState('wiretransfer.payment_date', 'DESC');
    }

    /**
     * Get MySQL loading query for customers list
     * 
     * @return string complet MySQL query
     */
    protected function getListQuery() {
    	$db = $this->getDbo();
    	$query = $db->getQuery(true);
    	$query->select('wiretransfer.*,agent.company')->from('#__bookpro_wiretransfer AS wiretransfer');
    	$query->join ( 'left', '#__bookpro_agent AS agent ON agent.id = wiretransfer.company_id ' );
        $company_name = $this->getState('filter.company_name');
        
        if($company_name) {
            $query->where('wiretransfer.company_name LIKE "%'.$company_name.'%"');
        }
        
        
        $wire_status = $this->getState('filter.wire_status');
        
        if($wire_status) {
            $query->where('wiretransfer.wire_status = "'.$wire_status.'"');
        }
        
        
        $from_date = $this->getState ( 'filter.fromdate');
		
		if ($from_date){
				
			$from_date = JFactory::getDate(DateHelper::createFromFormat($from_date)->getTimestamp())->format('Y-m-d 00:00:00');
				
		}
		$to_date = $this->getState ( 'filter.todate' );
		if ($to_date){
			$to_date = JFactory::getDate(DateHelper::createFromFormat($to_date)->getTimestamp())->format('Y-m-d 23:59:59');
		}
        
        
        if($from_date && $to_date ){
				$query->where ( 'wiretransfer.payment_date >= ' . $db->quote ( $from_date ) . ' AND wiretransfer.payment_date <= ' . $db->quote ( $to_date ) );
		}
    	$query->order($db->escape($this->state->get('list.ordering', 'wiretransfer.payment_date').' '.$this->state->get('list.direction', 'DESC')));
    	return $query;
    }
  }

?>