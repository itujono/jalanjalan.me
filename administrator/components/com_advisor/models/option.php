<?php
/*
 * @component %%COMPONENTNAME%% 
 * @copyright Copyright (C) August 2017. 
 * @license GPL 3.0
 * This program is free software: you can redistribute it and/or modify it under the terms of the 
 * GNU General Public License as published by the Free Software Foundation, either version 3 of the License, 
 * or (at your option) any later version.
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 * See the GNU General Public License for more details.
 * See <http://www.gnu.org/licenses/>.
 * More info www.moonsoft.es gestion@moonsoft.es 
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );
include 'main_advisor.php';

class AdvisorModelOption extends AdvisorModelWrapper{
    var $_id;
    var $_idflow;
    var $_idstep;
    var $_pageNav;
    
    function __construct(){
        parent::__construct();
        $this->_id =null;
        $array = JRequest::getVar('cid',  -1, '', 'array');
        $this->setId((int)$array[0]);
        $this->_idflow =null;
        $this->setIdFlow(JRequest::getVar('idflow'));
        $this->_idstep =null;
        $this->setIdStep(JRequest::getVar('idstep'));
    }
    function setId($id){
        $this->_id = $id;
    }
	function setIdFlow($id){
        $this->_idflow = $id;
    }
    function getIdFlow(){
    	return $this->_idflow;
    }
	function setIdStep($id){
        $this->_idstep = $id;
    }
    function getIdStep(){
    	return $this->_idstep;
    }
    function getPageNav(){
      return $this->_pageNav;
    }
    
    /** Database Section **/
    function _buildQuerySelect($id=null){
        $query = "select o.*
          from #__advisor_option o where o.idstep=".$this->_idstep;
        if ($id!=null) $query .= ' and o.id='.$id;        
        $query .= ' order by `order`';
        return $query;
    }    
	function _buildQuerySelectCount(){
        $query = "select count(*) from #__advisor_option where idstep=".$this->_idstep;
        return $query;
    }
	
    /** Function Section **/
	function getData(){
      jimport('joomla.html.pagination');
      $mainframe = JFactory::getApplication();
      $limit = JRequest::getVar('limit', $mainframe->getCfg('list_limit'));
      $limitstart = JRequest::getVar('limitstart', 0);
      $db = JFactory::getDBO();
      $query = $this->_buildQuerySelectCount();
      $db->setQuery( $query );
      $total = $db->loadResult();
      $query = $this->_buildQuerySelect();
      $db->setQuery( $query, $limitstart, $limit );
      $this->_pageNav = new JPagination($total, $limitstart, $limit);
      return $db->loadObjectList();      
    }
    
    function getDataOne() {       
      $db = JFactory::getDBO();
      $query = $this->_buildQuerySelect($this->_id);
      $db->setQuery($query);
      return $db->loadAssoc();
    }

	function store(){
    	$db = JFactory::getDBO();
        $row = $this->getTable('option');        
        $data = JRequest::get( 'post' );
        $data['content'] = JRequest::getVar('contentoption', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $data['id']=JRequest::getVar('idoption');
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }     
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }     
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return $row->id;
    }
    
	function remove(){
    	$db = JFactory::getDBO();                
        $cids=JRequest::getVar('cid',array(),'','array');
        foreach ($cids as $id){
        	$row =& $this->getTable('option');
        	$row->delete( $id );	
        }
        return true;    	
    }
    
    function saveOrder(){
    	$options=$this->getData();
    	$updates=array();
    	foreach ($options as $option):
    		$ord=JRequest::getVar('ord'.$option->id,0);
    		$updates[]='update #__advisor_option set `order`='.$ord.' where id='.$option->id;
    	endforeach;
    	$db = JFactory::getDBO();
    	foreach ($updates as $query):
    		$db->setQuery($query);
    		$db->query();
    	endforeach;
    	return true;
    }
}


?>
