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

class AdvisorModelProduct extends AdvisorModelWrapper{
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
        $query = "select *
          from #__advisor_product where idflow=".$this->_idflow;
        if ($id!=null) $query .= ' and id='.$id;
        $query .=" order by `order` asc";        
        return $query;
    }    
	function _buildQuerySelectCount(){
        $query = "select count(*) from #__advisor_product where idflow=".$this->_idflow;
        return $query;
    }
    
    function _buildQuerySelectMaxOrder(){
        $query = "select max(`order`)+1 as res from #__advisor_product where idflow=".$this->_idflow;
        return $query;
    }
    
	function _buildQuerySelectOrdered($idproduct){
        $query = "select `order` from #__advisor_product where id=".$idproduct;
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
    
	function _getMaxOrder() {       
      $db = JFactory::getDBO();
      $query = $this->_buildQuerySelectMaxOrder($this->_id);
      $db->setQuery($query);
      return $db->loadResult();
    }
    
	function _getIdByOrder($id) {       
      $db = JFactory::getDBO();
      $query = $this->_buildQuerySelectOrdered($id);
      $db->setQuery($query);
      return $db->loadResult();
    }

	function store(){
    	$db =& JFactory::getDBO();
        $row =& $this->getTable('product');        
        $data = JRequest::get( 'post' );
        $data['content'] = JRequest::getVar('contentproduct', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $data['id']=JRequest::getVar('idproduct');
        if ($data['order']==null) $data['order']=$this->_getMaxOrder();        
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
        	$row =& $this->getTable('product');
        	$row->delete( $id );	
        }
        return true;    	
    }
    
    function order(){
    	$db =& JFactory::getDBO();
    	$data = JRequest::get( 'post' );
        $row =& $this->getTable('product');        
        $row->load($data['idproduct']);
        $idordertochange=$row->order;
        switch ($data['direction']){
        	case 'up':        		
        		$row->order=$this->_getIdByOrder($data['prev_pid']);        		
        		break;
        	case 'down':
        		$row->order=$this->_getIdByOrder($data['next_pid']);
        		break;
        }        
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }     
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        
    	$row =& $this->getTable('product');        
        switch ($data['direction']){
        	case 'up':
        		$row->load($data['prev_pid']);
        		break;
        	case 'down':
        		$row->load($data['next_pid']);
        		break;
        }        
        $row->order=$idordertochange;
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
    
}


?>
