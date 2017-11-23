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

class ListmanagerMain extends JModel{
	
    /**
     * Constructor that retrieves the ID from the request
     *
     * @access    public
     * @return    void
     */
    function __construct(){
        parent::__construct();        
    }
    
    function _buildQueryRecordAccess($id){    
	    if (!is_numeric($id)) JError::raiseError(506,JText::_('INTERNAL SERVER ERROR').$id);
	    $query = "SELECT v.value, f.name, f.idlisting FROM #__listmanager_field f left join #__listmanager_values v on (f.id=v.idfield and v.idrecord=".$id.")";
	    return $query;
	} 
	
	function _getDataAccess($id){
		$db = JFactory::getDBO();
      	$query = $this->_buildQueryRecordAccess($id);
      	$db->setQuery($query);
      	return $db->loadObjectList();
	}
    
	function _addAccessInsert($id,$idlisting){		     	
		$this->_addAccessWrapper('0', $id,$idlisting);
    }
    
	function _addAccessUpdate($id,$idlisting){
		$this->_addAccessWrapper('1', $id,$idlisting);
    }
    
	function _addAccessDelete($id,$idlisting){
		$this->_addAccessWrapper('2', $id,$idlisting);
    }
     
    function _addAccessWrapper($type, $id,$idlisting){    	
    	// get data from ID
    	$data=$this->_getDataAccess($id);
    	$data4save="";
    	//$idlisting=0;
    	foreach ($data as $valname){
    		$data4save.=' [ '.$valname->name.' : '.$valname->value.' ] ';
    		//$idlisting=$valname->idlisting;
    	}
    	
    	$listing=$this->_getListing($idlisting);
    	if ($listing['savehistoric']=='1'){	    	
	    	$db =& JFactory::getDBO();
	        $row =& $this->getTable('access');
	        $datasave=array();
	        $user=JFactory::getUser();
	        $dt=JFactory::getDate();
	        $datasave['idlisting']=$idlisting;
	        $datasave['iduser']=$user->id;
	        $datasave['username']=$user->name;
	        $datasave['type']=$type;
	        $datasave['dt']=$dt->toMySQL();
	        $datasave['ip']=$_SERVER["REMOTE_ADDR"];
	        $datasave['idrecord']=$id;
	        $datasave['value']=$data4save;
	        if (!$row->bind($datasave)) {
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
	        $this->_deleteHistoric($idlisting);     
    	}
    }
    
    function _deleteHistoric($id){
    	$listing=$this->_getListing($id);
    	$deletehistoric=$listing['deletehistoric'];    	
    	$hist=explode('#',$deletehistoric);
    	if (isset($hist[0])&&isset($hist[1])){
	    	$date_from=null;
	    	switch($hist[1]){
	    		case '0':    			
	    			// Weeks
	    			$date_from=date("Y-m-d H:i:s",strtotime("-".$hist[0]." week"));
	    			break;
	    		case '1':
	    			// Months
	    			$date_from=date("Y-m-d H:i:s",strtotime("-".$hist[0]." month"));
	    			break;
	    		case '2':
	    			// Years
	    			$date_from=date("Y-m-d H:i:s",strtotime("-".$hist[0]." year"));
	    			break;
	    	}
	    	$db = JFactory::getDBO();
	    	if ($hist[1]<3){
	    		$date_ins=JFactory::getDate($date_from);
		    	$query = 'delete from #__listmanager_access where dt<"'.$date_ins->toMySQL().'"';
	    	} else {
	    		$query='select id from #__listmanager_access order by id limit 0,'.$hist[0];
	    		$db->setQuery($query);
	    		$arrids=$db->loadResultArray();	    		
	    		$query = 'delete from #__listmanager_access where id not in ('.implode(',', $arrids).')';
	    	}
		    $db->setQuery($query);
		    $db->query();
    	}    	
    }
    
	protected function _getListing($idlisting) {       
      $db = JFactory::getDBO();
      $query = $this->_buildQuerySelect($idlisting);
      $db->setQuery($query);
      return $db->loadAssoc();
    }
    
    /* 
     * Common functions
     */
    // Queries
    function _buildQuerySelect($id=null){
        $query = "select l.* from #__listmanager_listing l ";
        if ($id!=null) $query .= 'where l.id='.$id;        
        return $query;
    }
    // Functions
	function getDataOneListing() {       
      $db = JFactory::getDBO();
      $idlisting= JRequest::getVar( 'idlisting');
      $query = $this->_buildQuerySelect($idlisting);
      $db->setQuery($query);
      return $db->loadAssoc();
    }
	protected function getEscapedWrapper($text, $extra = false){
		$db = JFactory::getDBO();
		return $db->getEscaped($text,$extra);
	}
}


?>
