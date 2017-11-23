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

class AdvisorModelStep extends AdvisorModelWrapper{
    var $_id;
    var $_idflow;
    var $_pageNav;
    
    function __construct(){
        parent::__construct();
        $this->_id =null;
        $array = JRequest::getVar('cid',  -1, '', 'array');
        $this->setId((int)$array[0]);
        $this->_idflow =null;
        $this->setIdFlow(JRequest::getVar('idflow'));
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
    function getPageNav(){
      return $this->_pageNav;
    }
    
    /** Database Section **/
    function _buildQuerySelect($id=null){
        $query = "select s.*,(select n.name from #__advisor_step n where n.id=s.idprevstep) as prevstep
          from #__advisor_step s where s.idflow=".$this->_idflow;
        if ($id!=null) $query .= ' and s.id='.$id;        
        return $query;
    }    
	function _buildQuerySelectCount(){
        $query = "select count(*) from #__advisor_step where idflow=".$this->_idflow;
        return $query;
    }
	function _buildQuerySelectOtherSteps($id=null){
        $query = "select s.*,(select n.name from #__advisor_step n where n.id=s.idprevstep) as prevstep
          from #__advisor_step s where s.idflow=".$this->_idflow;
        if ($id!=null) $query .= ' and s.id!='.$id;        
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

	function getAllSteps(){
      $db = JFactory::getDBO();
      $query = $this->_buildQuerySelectOtherSteps($this->_id);
      $db->setQuery($query);
      return $db->loadObjectList();
    }

    function store(){
    	$db =& JFactory::getDBO();
        $row =& $this->getTable('step');        
        $data = JRequest::get( 'post' );
        $data['text'] = JRequest::getVar('text', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $data['id']=JRequest::getVar('idstep');
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
    	$db =& JFactory::getDBO();                
        $cids=JRequest::getVar('cid',array(),'','array');
        foreach ($cids as $id){
        	$row =& $this->getTable('step');
        	$row->delete( $id );	
        }
        return true;    	
    }
    
}


?>
