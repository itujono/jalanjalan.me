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
include 'main.model.php';

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
	function _buildQueryRate($idlisting=null,$idrecord=null,$idfield=null){
        $query = "select sum(rate)/count(*) from #__listmanager_rate 
        	where idlisting=".$idlisting." and idrecord=".$idrecord." and idfield=".$idfield;
        return $query;
    }
     
    /* Functions */    
	function getRate(){             
      $db = JFactory::getDBO();
      $idlisting=JRequest::getVar( 'idlisting' );
      $idrecord=JRequest::getVar( 'idrecord' );
      $idfield=JRequest::getVar( 'idfield' );
      $query = $this->_buildQueryRate($idlisting,$idrecord,$idfield);
      $db->setQuery($query);
      return $db->loadResult();
    }

    function getAddRate(){
    	$db = JFactory::getDBO();
	    $row =& $this->getTable();
	    $data = JRequest::get( 'post' );
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
        return true;
    }
	
}


?>
