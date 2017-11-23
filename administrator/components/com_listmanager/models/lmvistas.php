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

class ListmanagerModelLmvistas extends ListmanagerMain
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
    
    /**
     * Method to set the hello identifier
     *
     * @access    public
     * @param    int Hello identifier
     * @return    void
     */
    function setId($id){
        $this->_id = $id;
    }
    function getPageNav(){
      return $this->_pageNav;
    }   
     
    
    /* Views */
	function _buildQuerySelectViews($idlisting,$id=null){
        $query = "select * from #__listmanager_view where idlisting=".$idlisting;
        if ($id!=null) $query .= ' and id='.$id;        
        return $query;
    } 
    
    function _buildQuerySelectViewsCount($idlisting){
        $query = "select count(*) from #__listmanager_view where idlisting=".$idlisting;
        return $query;
    }
    
    /* View Fields */
	function _buildQuerySelectViewFields($idview, $idlisting){
        $query = "select f.id, f.name, f.visible as list_visible, 
        	f.autofilter as list_autofilter, f.showorder as list_showorder,
        	f.defaulttext as list_defvalue, f.order as list_order,
        	fv.visible, fv.autofilter, fv.showorder,
        	fv.filter_type, fv.filter_value, fv.defaulttext,fv.order, f.type, f.innername  
        	from #__listmanager_field f left join #__listmanager_view v
        				on f.idlisting=v.idlisting 
        			left join #__listmanager_field_view fv
        				on v.id=fv.idview and f.id=fv.idfield
        	where v.idlisting=".$idlisting;
        if($idview!=null && $idview>-1) $query .=" and v.id=".$idview;
        else $query .=" group by f.id";
        $query .=" order by fv.order,f.order asc";
        return $query;
    }    
    
    function _buildQuerySelectViewFieldsNew($idlisting){
        $query = "select f.id, f.name, f.visible as list_visible, 
        	f.autofilter as list_autofilter, f.showorder as list_showorder,
        	f.defaulttext as list_defvalue, f.order as list_order, f.visible, f.autofilter, f.showorder,
        	'-1' as filter_type, '' as filter_value, f.defaulttext, f.order, f.type, f.innername
        	from #__listmanager_field f 
        	where f.idlisting=".$idlisting;
        return $query;
    }    
    
    function _buildInsertViewField($data){
    	$query = "insert into #__listmanager_field_view (idview, idfield, visible, autofilter, showorder, filter_type, filter_value, defaulttext,`order`) 
    		values('".$data['idview']."','".$data['idfield']."','".$data['visible']."','".$data['autofilter']."','".$data['showorder']."',
    		'".$data['filter_type']."','".$data['filter_value']."','".$data['defaulttext']."','".$data['order']."')";
        return $query;
    } 
    
    function _buildDeleteViewField($idview){
    	$query = "delete from #__listmanager_field_view where idview=".$idview;
    	return $query;
    }
 
    
    
    /* Functions */
    
	
    
	function getDataViews(){
      jimport('joomla.html.pagination');
      $mainframe = JFactory::getApplication();
      $idlisting=JRequest::getVar( 'idlisting' );
      $limit = JRequest::getVar('limit', $mainframe->getCfg('list_limit'));
      $limitstart = JRequest::getVar('limitstart', 0);
      $db = JFactory::getDBO();
      $query = $this->_buildQuerySelectViewsCount($idlisting);
      $db->setQuery( $query );
      $total = $db->loadResult();
      $query = $this->_buildQuerySelectViews($idlisting);
      $db->setQuery( $query, $limitstart, $limit );
      $this->_pageNav = new JPagination($total, $limitstart, $limit);
      return $db->loadObjectList();      
    }
    
	function getDataOneView() {       
      $db = JFactory::getDBO();
      $idlisting= JRequest::getVar( 'idlisting' );
      $query = $this->_buildQuerySelectViews($idlisting,$this->_id);
      $db->setQuery($query);
      return $db->loadAssoc();
    }
    
	function getDataFields() { 
	  $idlisting=JRequest::getVar( 'idlisting' );      
      $db = JFactory::getDBO();
      if ($this->_id<=-1)
      	$query = $this->_buildQuerySelectViewFieldsNew($idlisting);
      else
      	$query = $this->_buildQuerySelectViewFields($this->_id,$idlisting);
      $db->setQuery($query);
      return $db->loadObjectList();
    }
    
    function save(){
    	$db = JFactory::getDBO();
	    $row = $this->getTable('view');        
        $data = JRequest::get( 'post' );
        if ($data['idview']!='') $row->load($data['idview']);
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
        
        // Fields        
        $query = $this->_buildDeleteViewField($row->id);
      	$db->setQuery($query);
        $db->query();
        $fields=$this->getDataFields();
        foreach ($fields as $field){
        	$data_field=array();
        	$data_field['idview']=$row->id;
        	$data_field['idfield']=$field->id;
        	$data_field['visible']=$data['visible'.$field->id];
        	$data_field['autofilter']=$data['autofilter'.$field->id];
        	$data_field['showorder']=$data['showorder'.$field->id];
        	$data_field['filter_type']=$data['filter_type'.$field->id];
        	$data_field['filter_value']=$data['filter_value'.$field->id];
        	$data_field['defaulttext']=$data['defaulttext'.$field->id];
        	$data_field['order']=$data['order'.$field->id];
        	$query = $this->_buildInsertViewField($data_field);
      		$db->setQuery($query);
        	$db->query();
        }
        
        return $row->get('id');
    }
    function delete(){
    	$db = JFactory::getDBO();
	    $row = $this->getTable('view');        
        $data = JRequest::get( 'post' );
        
        $cids=JRequest::getVar('cid',  array(), '', 'array');
        foreach ($cids as $cid){
        	$query = $this->_buildDeleteViewField($cid);
      		$db->setQuery($query);
        	$db->query();
	        if (!$row->delete($cid)){
	    		$this->setError($row->getErrorMsg());
	            return false;
	    	}	
        }         	
    	return true;
    }
    function savedetail(){
    	$db = JFactory::getDBO();
    	$row = $this->getTable('view');
    	$data = JRequest::get( 'post' );
    	//$data['id']=JRequest::getVar( 'idlisting' );
    	$data['detail']=JRequest::getVar( 'detail','', 'post', 'string', JREQUEST_ALLOWHTML);
    	$row->load($data['id']);
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
    	return $data['id'];
    }
	function savedetailpdf(){
    	$db = JFactory::getDBO();
    	$row = $this->getTable('view');
    	$data = JRequest::get( 'post' );
    	//$data['id']=JRequest::getVar( 'idlisting' );
    	$data['detailpdf']=JRequest::getVar( 'detailpdf','', 'post', 'string', JREQUEST_ALLOWHTML);
    	$row->load($data['id']);
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
    	return $data['id'];
    }
}


?>
