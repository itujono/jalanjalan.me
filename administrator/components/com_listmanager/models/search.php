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

class ListmanagerModelSearch extends ListmanagerMain
{
           
    var $_id;
    var $_pageNav;
   
    /**
     * Constructor that retrieves the ID from the request
     *
     * @access    public
     * @return    void
     */
    function __construct()
    {
        parent::__construct();
        $this->_id =null;
        $array = JRequest::getVar('cid',  -1, '', 'array');
        $this->setId((int)$array[0]);
    }
    
    function setId($id){
        $this->_id = $id;
    }
    function getPageNav(){
      return $this->_pageNav;
    }   
     
    
    /* Views */
	function _buildQuerySelectSearch($idlisting,$id=null,$postQuery=null){
        $query = "select * from #__listmanager_search where idlisting=".$idlisting;
        if ($id!=null) $query .= ' and id='.$id;        
        if ($postQuery!=null) $query .= $postQuery;
        return $query;
    } 
    
    function _buildQuerySelectSearchCount($idlisting,$postQuery=null){
        $query = "select count(*) from #__listmanager_search where idlisting=".$idlisting;
        if ($postQuery!=null) $query .= $postQuery;
        return $query;
    }
    
	function _buildQueryHistoric($idlisting){
		if (!is_numeric($idlisting)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	$query = "select * from #__listmanager_search where idlisting=".$idlisting;
        return $query;
	}
    
    /* Functions */    
	function getDataSearch(){
      jimport('joomla.html.pagination');
      $mainframe = JFactory::getApplication();
      $idlisting=JRequest::getVar( 'idlisting' );
      $limit = JRequest::getVar('limit', $mainframe->getCfg('list_limit'));
      $limitstart = JRequest::getVar('limitstart', 0);
      // Create postquery
      $postquery='';
      $search=JRequest::getVar('search');
      if (isset($search)&&strlen($search)>0) $postquery.=' and (upper(terms) like upper("%'.$search.'%"))';
      /*$type=JRequest::getVar('type');
      if (isset($type) && strlen($type)>0 && $type!='-1') $postquery.=' and type="'.$type.'"';*/
      $user=JRequest::getVar('user');
      if (isset($user) && strlen($user)>0 && $user!='-1') $postquery.=' and iduser="'.$user.'"';
      
      $db = JFactory::getDBO();
      $query = $this->_buildQuerySelectSearchCount($idlisting,$postquery);
      $db->setQuery( $query );
      $total = $db->loadResult();
      $query = $this->_buildQuerySelectSearch($idlisting,null,$postquery);
      $db->setQuery( $query, $limitstart, $limit );
      $result=$db->loadObjectList();
      $this->_pageNav = new JPagination($total, $limitstart, $limit);
      return $result;      
    }
    
	function getDataOneSearch() {       
      $db = JFactory::getDBO();
      $idlisting=JRequest::getVar( 'idlisting' );
      $query = $this->_buildQuerySelectSearch($idlisting,$this->_id);
      $db->setQuery($query);
      return $db->loadAssoc();
    }
        
	function getAllHistoric(){
    	$idlisting=JRequest::getVar( 'idlisting');
    	$db = JFactory::getDBO();      	
      	$query = $this->_buildQueryHistoric($idlisting);  
      	$db->setQuery( $query );
      	return $db->loadObjectList();
    }
	
}


?>
