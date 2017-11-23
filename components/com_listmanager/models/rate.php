<?php
/*
 * @component List Manager 
 * @copyright Copyright (C) November 2017. 
 * @license GPL 3.0 
 * This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version. 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 * See the GNU General Public License for more details. 
 * See <http://www.gnu.org/licenses/>. 
 * More info www.moonsoft.es 
 * gestion@moonsoft.es
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );
include JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'main.model.php';

class ListmanagerModelRate extends ListmanagerMain{           
     /**
     * Constructor that retrieves the ID from the request
     *
     * @access    public
     * @return    void
     */
    function __construct(){
        parent::__construct();        
    }
    
    /* SQL */
	function _buildQueryRateShow($idlisting=null,$idrecord=null,$idfield=null){
		if (!is_numeric($idlisting)) JError::raiseError(501,JText::_('INTERNAL SERVER ERROR'));
		if (!is_numeric($idrecord)) JError::raiseError(501,JText::_('INTERNAL SERVER ERROR'));
		if (!is_numeric($idfield)) JError::raiseError(501,JText::_('INTERNAL SERVER ERROR'));
        $query = "select sum(rate)/count(*) as rate from #__listmanager_rate 
        	where idlisting=".$idlisting." and idrecord=".$idrecord." and idfield=".$idfield;
        return $query;
    }
    
	function _buildQueryRate($idlisting=null,$idrecord=null,$idfield=null, $iduser=null, $ip=null){
		if (!is_numeric($idlisting)) JError::raiseError(501,JText::_('INTERNAL SERVER ERROR'));
		if (!is_numeric($idrecord)) JError::raiseError(501,JText::_('INTERNAL SERVER ERROR'));
		if (!is_numeric($idfield)) JError::raiseError(501,JText::_('INTERNAL SERVER ERROR'));
        $query = "select * from #__listmanager_rate 
        	where idlisting=".$idlisting." and idrecord=".$idrecord." and idfield=".$idfield;
        if ($iduser!=null) $query.=" and iduser=".$iduser;
        if ($ip!=null) $query.=" and ip='".$ip."'";
        $query.=' limit 1';
        return $query;
    }
    
    function _buildQuerySelect($id=null){           
        $db = JFactory::getDBO();
        $query = "select * from #__listmanager_listing ";
        if ($id!=null){
          if (is_numeric($id)) $query .= 'where id ='.$id;
          else JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
        }       
        return $query;
    }
	function _buildQuerySelectView($id){
		if (!is_numeric($id)) JError::raiseError(501,JText::_('INTERNAL SERVER ERROR'));
        $query = "select idlisting from #__listmanager_view where id=".$id;        
        return $query;
    }

	function checkId($id){
	  	if (!$this->isView($id))
	    	return $id;
	    else
	    	return $this->getFieldView(substr($id, 2));
	  }
	  
	  function isView($id){
	  	return (strrpos($id,'v_')!==false);
	  }
	 
	  function getFieldView($id) {
	  	  $db = JFactory::getDBO();
	      $query = $this->_buildQuerySelectView($id);
	      $db->setQuery($query);
	      return $db->loadResult();
    }
 	function getList(){  	
	  	$db = JFactory::getDBO();
	    $id =JRequest::getVar('idlisting');
	    $id=$this->checkId($id); 
	    $query = $this->_buildQuerySelect($id);
	    $db->setQuery($query);
	    return $db->loadObject();
	  }
     
    /* Functions */    
	function getRate(){             
      $db = JFactory::getDBO();
      $idlisting=JRequest::getVar( 'idlisting' );
      $idrecord=JRequest::getVar( 'idrecord' );
      $idfield=JRequest::getVar( 'idfield' );
      $query = $this->_buildQueryRateShow($idlisting,$idrecord,$idfield);
      $db->setQuery($query);
      return $db->loadResult();
    }
    
	function getRateFiltered($iduser,$ip){             
      $db = JFactory::getDBO();
      $idlisting=JRequest::getVar( 'idlisting' );
      $idrecord=JRequest::getVar( 'idrecord' );
      $idfield=JRequest::getVar( 'idfield' );
      $query = $this->_buildQueryRate($idlisting,$idrecord,$idfield,$iduser,$ip);      
      $db->setQuery($query);
      return $db->loadObject();
    }

    function getAddRate(){
    	$type=JRequest::getVar( 'type' );
    	if ($type!='1'){
    		$idlisting=JRequest::getVar( 'idlisting' );
    		$list=$this->getList();
    		$idRate=null;    		
    		switch ($list->ratemethod){
    			case '-1': break;
    			case '0':
    				// by user
    				$user =& JFactory::getUser();
    				if ($user->id==0) break;
    				$rated = $this->getRateFiltered($user->id, null);
    				if (isset($rated->id)) $idRate=$rated->id;
    				break;
    			case '1':
    				// by IP
    				$ip=$_SERVER['REMOTE_ADDR'];
    				$rated = $this->getRateFiltered(null, $ip);
    				if (isset($rated->id)) $idRate=$rated->id;    				
    				break;
    		}    		
	    	$db = JFactory::getDBO();
		    $row = $this->getTable();
		    $data = JRequest::get( 'post' );
		    $user = JFactory::getUser();
		    $data['iduser']=$user->id;
		    $data['ip']=$_SERVER['REMOTE_ADDR'];
		    if ($idRate!=null){
		    	$data['id']=$idRate;
		    	$row->load($idRate);		    	
		    }
		    if (!$row->bind($data)) {
	            $this->setError($row->getErrorMsg());
	            return false;
	        }
	        if (!$row->check()) {
	            $this->setError($row->getErrorMsg());
	            return false;
	        }
	        if (!$row->store()) {
	            $this->setError($row->getErrorMsg());
	            return false;
	        }
    	}
        return true;
    }
	
}


?>
