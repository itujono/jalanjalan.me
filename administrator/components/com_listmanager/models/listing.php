<?php
/*
 * @component List Manager 
 * @copyright Copyright (C) October 2015. 
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

class ListmanagerModelListing extends ListmanagerMain
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


 
    /**
     * Returns the query
     * @return string The query to be used to retrieve the rows from the database
     */    
    
    function _buildQuerySelectCount(){
        $query = "select count(*) from #__listmanager_listing ";
        return $query;
    }
    
    function _buildQuerySelectFieldsOne($id){    
    	if (!is_numeric($id)) JError::raiseError(505,JText::_('INTERNAL SERVER ERROR'));
        $query = "select f.*, (select fin.name from #__listmanager_field fin where fin.id=f.link_id) as link_name
          from #__listmanager_field f where idlisting=".$id." order by `order` asc";
        return $query;
    }
        
    function _buildDeleteAllFields($id,$arrValidFields){
    	if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
		$query = "delete from #__listmanager_field where idlisting=".$id;
        if (is_array($arrValidFields) && strlen(trim(implode(',', $arrValidFields))) >0){
          $query.= " and id not in (".implode(',', $arrValidFields).")";
        }
        return $query;
    }  
    
	function _buildDeleteAllMultivalues($id,$arrValidMVs){
		if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
		$query = "delete from #__listmanager_field_multivalue where idobj=".$id;
        if (is_array($arrValidMVs) && strlen(trim(implode(',', $arrValidMVs))) >0){
          $query.= " and id not in (".implode(',', $arrValidMVs).")";
        }
        return $query;
    }  
    
    function _buildQueryInsertRecord($idfield,$idrecord,$value){
	    if (!is_numeric($idfield)||!is_numeric($idrecord)) JError::raiseError(507,JText::_('INTERNAL SERVER ERROR'));
	    $db = JFactory::getDBO();
	    $value=$db->escape($value,false);
	    $query = "insert into  #__listmanager_values (idfield,idrecord,value) values (".$idfield.",".$idrecord.",'".$value."')";
      	return $query;
    }
    
	function _buildQueryUpdateRecord($idfield,$idrecord,$value){
	    if (!is_numeric($idfield)||!is_numeric($idrecord)) JError::raiseError(507,JText::_('INTERNAL SERVER ERROR'));    
	    $db = JFactory::getDBO();
	    $value=$db->escape($value,false);
	    $query = "update #__listmanager_values set value='".$value."' where idfield=".$idfield." and idrecord=".$idrecord;
      	return $query;
    }
  
   	function _buildQueryGetNumRecord($id){   
    	if (!is_numeric($id)) JError::raiseError(503,JText::_('INTERNAL SERVER ERROR'));
      	$query = "select IFNULL(max(v.idrecord)+1,1) as maxidrecord
        	from #__listmanager_values v,#__listmanager_field f where f.idlisting=".$id." and f.id=v.idfield";
      	return $query;
  	}
  	
   function _buildQueryRecords($id,$idRecords=null){    
      if (!is_numeric($id)) JError::raiseError(506,JText::_('INTERNAL SERVER ERROR'));
      /*$query = "SELECT v.id,v.idfield,
      	IF(f.type<>15,v.value,
      		(select sum(r.rate)/count(*) from #__listmanager_rate r where r.idlisting=".$id." and r.idrecord=v.idrecord and r.idfield=f.id)) as value,
      	v.idrecord,f.type,f.decimal,f.order,f.size,f.name,f.visible,f.sqltext,f.validate,f.limit0, f.limit1      
      FROM #__listmanager_values v,#__listmanager_field f
      where f.id=v.idfield and idlisting=".$id;*/
	$query = "SELECT v.id,v.idfield, v.value as value, v.idrecord,f.type,f.decimal,f.order,f.size,
		f.name,f.visible,f.sqltext,f.validate,f.limit0, f.limit1, f.exportable 
	  FROM #__listmanager_values v,#__listmanager_field f 
	  where f.id=v.idfield 
	  and idlisting=".$id;
	  if($idRecords!=null){
      	$query .= " and v.idrecord in(".implode(',', $idRecords).")";
      }
	  $query .= " union
		SELECT r.id,f.id, sum(r.rate)/count(*) as value, r.idrecord,f.type,f.decimal,
	    f.order,f.size,f.name,f.visible,f.sqltext,f.validate,f.limit0, f.limit1, f.exportable 
	  FROM #__listmanager_rate r,#__listmanager_field f 
	  where f.id=r.idfield 
	  and f.idlisting=".$id;
	  if($idRecords!=null){
      	$query .= " and r.idrecord in(".implode(',', $idRecords).")";
      }
	  $query .= " group by r.idrecord";   	  
      
      return $query;
  }
  
	function _buildQueryRecordsInLimit($id,$textFilter){
    
      if (!is_numeric($id)) JError::raiseError(506,JText::_('INTERNAL SERVER ERROR'));
      $query = "SELECT distinct(v.idrecord) FROM #__listmanager_values v,#__listmanager_field f
      where f.id=v.idfield and  idlisting=".$id;
      if ($textFilter!=null){
      	$query .= " and v.value like '%".$textFilter."%'";
      }
      return $query;
  }
  
	function _buildQueryCountRecords($id,$textFilter){    
      if (!is_numeric($id)) JError::raiseError(506,JText::_('INTERNAL SERVER ERROR'));
      $query = "SELECT count(distinct(v.idrecord)) as counter FROM #__listmanager_values v,#__listmanager_field f
      			where f.id=v.idfield and idlisting=".$id;
	  if ($textFilter!=null){
      	$query .= " and v.value like '%".$textFilter."%'";
      }
      //$query.=" group by v.idrecord";     
      return $query;
  }
   function _buildQueryRecordData($id,$idlist){    
      if (!is_numeric($id)) JError::raiseError(506,JText::_('INTERNAL SERVER ERROR').$id.":".$idlist);
      $query = "SELECT v.id,v.value,v.idrecord,f.type,f.decimal,f.order,f.size,f.name,f.validate ,f.visible,f.multivalue,
      	f.id as idfield,f.sqltext,f.validate,f.limit0, f.limit1,  f.exportable,f.placeholder,f.css,
      	(select sum(r.rate)/count(*) from #__listmanager_rate r where r.idlisting=".$idlist." and r.idrecord=".$id." and r.idfield=f.id) as ratevalue 
      	FROM #__listmanager_field f left join #__listmanager_values v on (f.id=v.idfield and  idrecord=".$id.") 
      	where f.idlisting=".$idlist." order by f.order";
      return $query;
  } 
  
   function _buildQuerySelectFieldsOne2($id,$type=null){
     if (!is_numeric($id)) JError::raiseError(505,JText::_('INTERNAL SERVER ERROR'));
     if ($type!=null&&!is_numeric($type)) JError::raiseError(504,JText::_('INTERNAL SERVER ERROR'));
      $query = "select * from #__listmanager_field  where idlisting=".$id;
      if ($type!=null) $query .= ' and type='.$type;
      $query.=" order by `order` asc";
      return $query;
  }
  
  function _buildDeleteRecord($idrecord,$idlist){
    $query ="delete from #__listmanager_values where idrecord=".$idrecord." and idfield in (select id from #__listmanager_field where idlisting=".$idlist.")";
    return $query;
  
  }

  function _buildQueryUsers(){
    $query ="select id, name from #__users order by name";
    return $query;
  }
          
     function _buildQuerySelectACL($idlist){
     if (!is_numeric($idlist)) JError::raiseError(503,JText::_('INTERNAL SERVER ERROR').$idlist);
       $query="select ug.id as idgroup, ug.title, IFNULL(a.acl,'#detail#add#delete#edit#') as acl from #__usergroups ug left join #__listmanager_acl a on (ug.id=a.idgroup and a.idlisting=".$idlist.")" ;
       return $query;
     }

     function _buildDeleteACLFromList($idlist){
     if (!is_numeric($idlist)) JError::raiseError(504,JText::_('INTERNAL SERVER ERROR').$idlist);
       $query="delete from #__listmanager_acl where idlisting=".$idlist;
       return $query;
     }
     
     function _buildQueryInsertACLGrupo($idlist,$idgrupo,$permisos){
     	if (!is_numeric($idlist)||!is_numeric($idgrupo)) JError::raiseError(503,JText::_('INTERNAL SERVER ERROR').$idlist);
       	$query="insert into #__listmanager_acl(idlisting,idgroup,acl) values(".$idlist.",".$idgrupo.",'".$permisos."')";
       	return $query;     
     }
     
	function _buildQueryInsertConfig($id,$preprint,$postprint,$pdforientation,$date_format,$decimal,$thousand,$modalform,
									 $savehistoric,$deletehistoric,$ratemethod,$hidelist,$default_order,$list_type,$list_columns,
									 $list_height,$list_name_class,$list_value_class,$tool_column_position,$tool_column_name,
									 $view_toolbar,$view_filter,$view_bottombar,$detail_onclick,$detailmode,$date_format_bbdd, $search_type,
									 $view_toolbar_bottom,$show_filter,$filter_automatic,$editor,$savesearch){
	    if (!is_numeric($id)) JError::raiseError(507,JText::_('INTERNAL SERVER ERROR'));
	    $db = JFactory::getDBO();
	    $preprint=$db->escape($preprint,false);
	    $postprint=$db->escape($postprint,false);
	    $query = "update #__listmanager_listing set preprint='".$preprint."',postprint='".$postprint."',
	    			pdforientation='".$pdforientation."',date_format='".$date_format."',
	    			`decimal`='".$decimal."',thousand='".$thousand."',modalform='".$modalform."',
	    			savehistoric='".$savehistoric."',deletehistoric='".$deletehistoric."',ratemethod='".$ratemethod."',
	    			hidelist='".$hidelist."',default_order='".$default_order."',list_type='".$list_type."'
	    			,list_columns='".$list_columns."',list_height='".$list_height."',list_name_class='".$list_name_class."'
	    			,list_value_class='".$list_value_class."',tool_column_position='".$tool_column_position."'
	    			,tool_column_name='".$tool_column_name."',view_toolbar='".$view_toolbar."'
	    			,view_filter='".$view_filter."',view_bottombar='".$view_bottombar."'
	    			,detail_onclick='".$detail_onclick."',detailmode='".$detailmode."',date_format_bbdd='".$date_format_bbdd."'
	    			,search_type='".$search_type."',view_toolbar_bottom='".$view_toolbar_bottom."'
	    			,show_filter='".$show_filter."',filter_automatic='".$filter_automatic."',editor='".$editor."', savesearch='".$savesearch."' 
	    			where id=".$id;
      	return $query;
    }
    
	function _buildDeleteView($id){
    	$query = "delete from #__listmanager_view where idlisting=".$id;
    	return $query;
    }
    
	function _buildSelectMultivalues($id){		
		if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
        $query = "select * from #__listmanager_field_multivalue where idobj=".$id." order by ord" ;
        return $query;
    }
    function _buildDeleteRates($idrecord,$idlisting){
    	if (!is_numeric($idrecord)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	if (!is_numeric($idlisting)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
        $query = "delete from #__listmanager_rate where idrecord=".$idrecord.' and idlisting='.$idlisting ;
        return $query;
    }
	function _buildDeleteAllRates($idlisting){
    	if (!is_numeric($idlisting)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
        $query = "delete from #__listmanager_rate where idlisting=".$idlisting ;
        return $query;
    } 
    
	function _buildDeleteAllAccess($idlisting){
    	if (!is_numeric($idlisting)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
        $query = "delete from #__listmanager_access where idlisting=".$idlisting ;
        return $query;
    }
    
    function _buildQueryAllList(){
    	$query="select * from #__listmanager_listing";
    	return $query;
    }    
    
    function _buildQueryRecordAccess($idrecord){
    	if (!is_numeric($idrecord)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	$query = "select * from #__listmanager_access where idrecord=".$idrecord.' order by dt desc' ;
        return $query;
    }
	function _buildQueryRecordAccessCount($idrecord){
    	if (!is_numeric($idrecord)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	$query = "select count(*) from #__listmanager_access where idrecord=".$idrecord.' order by dt desc' ;
        return $query;
    }
    
	function _buildSelectMVId($idlisting,$idfield,$value){
    	if (!is_numeric($idlisting)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	if (!is_numeric($idfield)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	$db = JFactory::getDBO();
    	$query = "select id from #__listmanager_field_multivalue where idobj=".$idlisting." and idfield=".$idfield." and value='".$db->escape($value)."'";    	
        return $query;
    }
    
    function _buildSelectNameMVId($idlisting,$id){
    	/*if (!is_numeric($idlisting)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	if (!is_numeric($idfield)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));*/
    	$db = JFactory::getDBO();
    	$query = "select name from #__listmanager_field_multivalue where idobj=".$idlisting." and id='".$db->escape($id)."'";
    	return $query;
    }
    
    function _buildSelectValueMVId($idlisting,$id){
    	/*if (!is_numeric($idlisting)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	 if (!is_numeric($idfield)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));*/
    	$db = JFactory::getDBO();    	
    	$query = "select value from #__listmanager_field_multivalue where idobj=".$idlisting." and id='".$db->escape($id)."'";    	
    	return $query;
    }
    
    
    
    /* Copy queries */
	function _buildQueryCopySelect($type,Array $params){
		switch($type){
			case 'listing':
				return 'select * from #__listmanager_listing where id='.$params['id'];
				break;
			case 'view':
				return 'select * from #__listmanager_view where idlisting='.$params['id'];
				break;
			case 'acl':
				return 'select * from #__listmanager_acl where idlisting='.$params['id'];
				break;
			case 'field':
				return 'select * from #__listmanager_field where idlisting='.$params['id'];
				break;
			case 'values':
				return 'select v.* from #__listmanager_values v left join #__listmanager_field f on v.idfield=f.id where f.idlisting='.$params['id'];
				break;	
			case 'rate':
				return 'select * from #__listmanager_rate where idlisting='.$params['id'];
				break;	
			case 'multivalue':
				return 'select * from #__listmanager_field_multivalue where idobj='.$params['id'];
				break;
			case 'fieldview':
				return 'select v.* from #__listmanager_field_view v left join #__listmanager_view vi on v.idview=vi.id where vi.idlisting='.$params['id'];
				break;
		}
	}
	
	function _buildDeleteRatesValue($cid,$idField){
		if (!is_numeric($cid)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	if (!is_numeric($idField)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
        $query = "delete from #__listmanager_values where idrecord=".$cid.' and idfield='.$idField ;
        return $query;		
	}
	
	function _buildQueryInsertRate($idlisting,$idfield,$idrecord,$rate){
	    if (!is_numeric($idfield)||!is_numeric($idrecord)) JError::raiseError(507,JText::_('INTERNAL SERVER ERROR'));
	    $db = JFactory::getDBO();
	    $rate=$db->escape($rate,false);
	    $query = "insert into  #__listmanager_rate (idlisting,idrecord,idfield,rate) values (".$idlisting.",".$idrecord.",".$idfield.",'".$rate."')";
      	return $query;
	}
	
	function _buildQuerySelectNextAutoIncrement($idfield){
    	if (!is_numeric($idfield)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	$query = "select max(0+value) from #__listmanager_values where idfield=".$idfield ;
        return $query;
    }
    
	function _buildQueryGetRecordsFromList($id){   
    	if (!is_numeric($id)) JError::raiseError(503,JText::_('INTERNAL SERVER ERROR'));
      	$query = "select distinct(v.idrecord) as idrecord
        	from #__listmanager_values v,#__listmanager_field f where f.idlisting=".$id." and f.id=v.idfield";
      	return $query;
  	}
	
		
    /* Functions */
    
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
    
    function getDataFields() {  
      $idlist=JRequest::getVar('idlisting',null);
      if ($idlist==null){  $idlist=$this->_id;}     
      $db = JFactory::getDBO();
      $query = $this->_buildQuerySelectFieldsOne($idlist);
      $db->setQuery($query);
      return $db->loadObjectList();
    }
    
    function storeConfig() {
        $db = JFactory::getDBO();
        $data = JRequest::get( 'post' );
        $data['preprint'] = JRequest::getVar('preprint', '', 'post', 'string', JREQUEST_ALLOWHTML);
        $data['postprint'] = JRequest::getVar('postprint', '', 'post', 'string', JREQUEST_ALLOWHTML);
        $query = $this->_buildQueryInsertConfig($this->_id,$data['preprint'],$data['postprint'],
        	$data['pdforientation'],$data['date_format'],$data['decimal'],$data['thousand'],$data['modalform'],
        	$data['savehistoric'],$data['deletehistoric'],$data['ratemethod'],$data['hidelist'],$data['default_order'],
        	$data['list_type'],$data['list_columns'],$data['list_height'],$data['list_name_class'],$data['list_value_class'],
        	$data['tool_column_position'],$data['tool_column_name'],
        	$data['view_toolbar'],$data['view_filter'],$data['view_bottombar'],$data['detail_onclick'],$data['detailmode'],$data['date_format_bbdd'],
        	$data['search_type'],$data['view_toolbar_bottom'],$data['show_filter'],$data['filter_automatic'],$data['editor'],$data['savesearch']);
      	$db->setQuery($query);
        $db->query();
        return $this->_id;      
    }
	function getMultivalues(){
      $db = JFactory::getDBO();
      $query = $this->_buildSelectMultivalues($this->_id);
      $db->setQuery($query);
      return $db->loadObjectList();
    }
    
	function getMultivalueIDFromValue($idfield,$value){
      $db = JFactory::getDBO();
      $query = $this->_buildSelectMVId($this->_id,$idfield,$value);
      $db->setQuery($query);
      return $db->loadResult();
    }
    
    
    
    /**
     * Method to store a record
     *
     * @access    public
     * @return    boolean    True on success
     */
    function store() {
        $db =JFactory::getDBO();
        $row =$this->getTable();        
        $data = JRequest::get('post');
        $idlisting = JRequest::getVar('idlisting',-1);
        $data['info'] = JRequest::getVar( 'info','', 'post', 'string', JREQUEST_ALLOWHTML ) ;
        $data['specialfilters']=JRequest::getVar('specialfilters','', 'post', 'string', JREQUEST_ALLOWRAW);
        if ( $idlisting==-1 || $idlisting=="" ){
        	$data['date_format']="yy-mm-dd";
        }
        
        if (!$row->bind($data)) {
            $this->setError($row->getError());
            return false;
        }
        if (!$row->check()) {
            $this->setError($row->getError());
            return false;
        }
        if (!$row->store()) {
            $this->setError($row->getError());
            return false;
        }       

        $a_fields=json_decode(stripslashes(JRequest::getVar('fields','', 'post', 'string', JREQUEST_ALLOWRAW)),true);
        //$a_fields=json_decode(JRequest::getVar('fields','', 'post', 'string', JREQUEST_ALLOWRAW),true);
    	//var_dump($a_fields);
        $arrValidFields=array();          
        $arrValidMVs=array();        
        foreach ($a_fields as $field){        	        	
        	$field['idlisting']=$row->id;
        	$field['sqltext']=htmlspecialchars_decode($field['sqltext']);
        	$rowField = $this->getTable('field');
        	if (isset($field['id'])) $rowField->load($field['id']);
	        if (!$rowField->bind($field)) {
	        	$this->setError($rowField->getError());
	            return false;
	        }	        
	        if (!$rowField->check()) {
	            $this->setError($rowField->getError());
	            return false;
	        }
	        if (!$rowField->store()) {
	            $this->setError($rowField->getError());
	            return false;
	        }	        	         
	        // Multivalue
	        $arr_mv_name=$field['mvname'];
	        $arr_mv_val=$field['mvval'];
	        $arr_mvid=$field['mvid'];
	        $arrInsertMultivalue=null;	        
	        for ($i=0;$i<count($arr_mv_name);$i++){
	        	$rowFieldMultivalue = $this->getTable('fieldmultivalue');
	        	$arrInsertMultivalue=array();
	        	$arrInsertMultivalue['idfield']=$rowField->id;
              	$arrInsertMultivalue['idobj']=$row->id;
              	$arrInsertMultivalue['name']=$arr_mv_name[$i];
              	$arrInsertMultivalue['value']=$arr_mv_val[$i];
              	$arrInsertMultivalue['id']=$arr_mvid[$i];
              	$arrInsertMultivalue['ord']=$i;
              	if (strlen($arrInsertMultivalue['id'])>0) $rowFieldMultivalue->load($arrInsertMultivalue['id']);
              	if (!$rowFieldMultivalue->bind($arrInsertMultivalue)) {
		              $this->setError($rowFieldMultivalue->getError());
		              return false;
		          }
		          if (!$rowFieldMultivalue->check()) {
		              $this->setError($rowFieldMultivalue->getError());
		              return false;
		          }
		          if (!$rowFieldMultivalue->store()) {
		              $this->setError($rowFieldMultivalue->getError());
		              return false;
		          }   
		          $arrValidMVs[]=$rowFieldMultivalue->id;
	        }	
	        $arrValidFields[]=$rowField->id;        
        }
        
        // Delete fields
    	$query = $this->_buildDeleteAllFields($row->id,$arrValidFields);
        $db->setQuery( $query );
        $db->query();
        // Delete multivalues
    	$query = $this->_buildDeleteAllMultivalues($row->id,$arrValidMVs);
        $db->setQuery( $query );
        $db->query();
        return $row->id;
    }
    
    /**
     * Method to delete record(s)
     *
     * @access    public
     * @return    boolean    True on success
     */
    function delete() {
      $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
      $row = $this->getTable();     
      $db = JFactory::getDBO();
      foreach($cids as $cid) {
        if (!$row->delete( $cid )) {
          $this->setError( $row->getErrorMsg() );
          return false;
        } 
        $query = $this->_buildDeleteAllMultivalues($cid,null);
        $db->setQuery($query);
        $db->query();
        $query = $this->_buildDeleteAllRates($cid);
        $db->setQuery($query);
        $db->query();
        $query = $this->_buildDeleteAllFields($cid,1);
        $db->setQuery($query);
        $db->query();
        $query = $this->_buildDeleteView($cid);
      	$db->setQuery($query);
      	$db->query();
      	$query = $this->_buildDeleteACLFromList($cid);
      	$db->setQuery($query);
      	$db->query();
      	$query = $this->_buildDeleteAllAccess($cid);
      	$db->setQuery($query);
      	$db->query();            
      }                            
      return true;
    }




	/**
     * Method to copy list(s)
     *
     * @access    public
     * @return    boolean    True on success
     */
    function copyList() {
    	$db = JFactory::getDBO();
      	$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
      	foreach ($cids as $id){      		
      		$arrold=array();
      		// Listing
			$query=$this->_buildQueryCopySelect('listing',array('id'=>$id));
			$db->setQuery($query);
			$arrlisting=$db->loadObject();			
			unset($arrlisting->id);
			JFactory::getDbo()->insertObject('#__listmanager_listing', $arrlisting);
			$arrold['idnew']=$db->insertid();
			// View
			$query=$this->_buildQueryCopySelect('view',array('id'=>$id));
			$db->setQuery($query);
			$arrview=$db->loadObjectList();
			$arroldviews=array();
			foreach ($arrview as $view){
				$antid=$view->id;
				unset($view->id);
				$view->idlisting=$arrold['idnew'];
				JFactory::getDbo()->insertObject('#__listmanager_view', $view);
				$arroldviews[$antid]=$db->insertid();
			}
			$arrold['views']=$arroldviews;
			// ACL
			$query=$this->_buildQueryCopySelect('acl',array('id'=>$id));
			$db->setQuery($query);
			$arracl=$db->loadObjectList();
			foreach ($arracl as $acl){
				$acl->idlisting=$arrold['idnew'];
				JFactory::getDbo()->insertObject('#__listmanager_acl', $acl);
			}
			// Field
      		$query=$this->_buildQueryCopySelect('field',array('id'=>$id));
			$db->setQuery($query);
			$arrfield=$db->loadObjectList();
			$arroldfields=array();
			foreach ($arrfield as $field){
				$antid=$field->id;
				unset($field->id);
				$field->idlisting=$arrold['idnew'];
				JFactory::getDbo()->insertObject('#__listmanager_field', $field);
				$arroldfields[$antid]=$db->insertid();
			}
			$arrold['fields']=$arroldfields;
			// Values
      		$query=$this->_buildQueryCopySelect('values',array('id'=>$id));
			$db->setQuery($query);
			$arrvalues=$db->loadObjectList();
			$arroldrecords=array();
			foreach ($arrvalues as $value){
				$antid=$value->idrecord;
				unset($value->id);
				$value->idfield=$arrold['fields'][$value->idfield];
				JFactory::getDbo()->insertObject('#__listmanager_values', $value);
				$arroldrecords[$antid]=$db->insertid();
			}
			$arrold['records']=$arroldrecords;
      		// Rate
      		$query=$this->_buildQueryCopySelect('rate',array('id'=>$id));
			$db->setQuery($query);
			$arrrates=$db->loadObjectList();
			foreach ($arrrates as $rate){
				unset($rate->id);
				$rate->idlisting=$arrold['idnew'];
				$rate->idrecord=$arrold['records'][$rate->idrecord];
				$rate->idfield=$arrold['fields'][$rate->idfield];
				JFactory::getDbo()->insertObject('#__listmanager_rate', $rate);
			}
	      	// Field Multivalue
      		$query=$this->_buildQueryCopySelect('multivalue',array('id'=>$id));
			$db->setQuery($query);
			$arrmultivalue=$db->loadObjectList();
			foreach ($arrmultivalue as $mv){
				unset($mv->id);
				$mv->idobj=$arrold['idnew'];
				$mv->idfield=$arrold['fields'][$mv->idfield];
				JFactory::getDbo()->insertObject('#__listmanager_field_multivalue', $mv);
			}
			// Field View
      		$query=$this->_buildQueryCopySelect('fieldview',array('id'=>$id));
			$db->setQuery($query);
			$arrfieldview=$db->loadObjectList();
			foreach ($arrfieldview as $fv){
				unset($fv->id);
				$fv->idfield=$arrold['fields'][$fv->idfield];
				$fv->idview=$arrold['views'][$fv->idview];
				JFactory::getDbo()->insertObject('#__listmanager_field_view', $fv);
			}
    	}                            
      	return true;
    }
    
    
    
   /**
     * Method to insert data from csv
     *
     * @access    public
     * @return    boolean    True on success
     */
    function insertDataCSV($idlisting) {    
      $db = JFactory::getDBO();	    	
      //$fileUploaded = JRequest::getVar( 'fileupload',null, 'files', 'array') ;
      $jinput = JFactory::getApplication()->input;
      $fileUploaded=$jinput->files->get('fileupload');
      if (count($fileUploaded)<=0) return false;
      //$datos=file_get_contents($fileUploaded['tmp_name']);
      
      $field_separator = JRequest::getVar('field_separator',';');
      if (strlen($field_separator)<=0) $field_separator=';';
      elseif ($field_separator=='#TAB#') $field_separator="\t";
      $row_separator = JRequest::getVar('row_separator',chr(13));
      if (strlen($row_separator)<=0) $row_separator=chr(13);      
      
      $arrfields=$this->getDataFields();
      
      $arrids;
      $arrtypes;
      $arrsql;
      $f=0; 
      foreach($arrfields as $field){ 
      	$arrids[$f]=$field->id; 
      	$arrtypes[$f]=$field->type;
      	$arrsql[$f]=$field->sqltext;
      	$f++;
      }      
      //$arrFilas=explode($row_separator,$datos);
      $arrData=array();
      if ($row_separator==chr(13)):
	      if (($handle = fopen($fileUploaded['tmp_name'], "r")) !== FALSE):
	      	while (($data = fgetcsv($handle, null, $field_separator)) !== FALSE):
	      		$arrData[]=$data;
	      	endwhile;      	
	      endif;
	  else:
	  	  $datos=file_get_contents($fileUploaded['tmp_name']);
	  	  $arrFilas=explode($row_separator,$datos);
	      for ($i=0;$i<count($arrFilas);$i++):
	      	$filaTmp=$arrFilas[$i];
	        $arrData[]=explode($field_separator,$arrFilas[$i]);
	      endfor;
	  endif;
        
        //get max registro para el idlisting      
        /*$query = $this->_buildQueryGetNumRecord($idlisting);
        $db->setQuery($query);
        $_idrecord= $db->loadResult();*/
      for ($i=JRequest::getVar('start_at',0);$i<count($arrData);$i++){
      	$arrColumnas=$arrData[$i];
        $_idrecord=$this->_getNumRecord($idlisting);        
        for ($j=0;$j<count($arrColumnas)&&$j<count($arrids);$j++){
        	$tmpval=trim($arrColumnas[$j]);
        	if ($arrtypes[$j]!=15):        		
        		// Comprobar si la columna es multiple y no viene de sql la columna para obtener el id del valor        	
	        	if ($this->isMultiple($arrtypes[$j])&&($arrsql[$j]==null || count($arrsql[$j])<=0)):
	        		$tmp_vals=explode('#',$tmpval);
	        		$tmpval='';
	        		foreach ($tmp_vals as $tmp_val):	        		
	        			if($tmpval!='') $tmpval.='#';
	        			$tmpval.=$this->getMultivalueIDFromValue($arrids[$j], $tmp_val);	        			      
	        		endforeach;	        		  
	        	endif;	        	
		        $queryins = $this->_buildQueryInsertRecord($arrids[$j],$_idrecord,$tmpval);
		    else: // Rate type
				// Get id rate field	        	        
		        $queryins = $this->_buildQueryInsertRate($idlisting,$arrids[$j],$_idrecord,$tmpval);
		    endif;
	        $db->setQuery($queryins);               
	        $db->query();
        }
      }   
      return true;
    }
    
    function isMultiple($fieldType){
    	$arrMul=array(2,11,16,10);
    	return in_array($fieldType, $arrMul);
    }
    
    function _getNumRecord($idlisting){
    	$db = JFactory::getDBO();
    	$query = $this->_buildQueryGetNumRecord($idlisting);
        $db->setQuery($query);
        return $db->loadResult();
    }	
    
    function insertDataSQL($idlisting) {  
    	$db = JFactory::getDBO();   
    	$query=JRequest::getVar('sqlquery','', 'post', 'string', JREQUEST_ALLOWHTML);
    	$arrIns=null;
    	$db->setQuery($query);
	    $arrIns=$db->loadRowList();
      	$dataFields=$this->getDataFields();
    	$arrids;
      	$f=0; 
      	foreach($dataFields as $field){ $arrids[$f++]=$field->id;}
      	for($i=0;$i<count($arrIns);$i++){
      		$dataIns=$arrIns[$i];
      		$_idrecord=$this->_getNumRecord($idlisting);
      		for ($j=0;$j<count($arrids);$j++){    
      			$queryins = $this->_buildQueryInsertRecord($arrids[$j],$_idrecord,trim($dataIns[$j]));
	        	$db->setQuery($queryins);               
	        	$db->query();
      		}
      	}
      	return true;
    }
    

    /**
     *Valores de campos para manage
     */  
    function getDataRecords() {
    	return $this->getDataRecordsWrapper(true);
    }
	function getDataRecordsUnlimited() {
    	return $this->getDataRecordsWrapper(false,true);
    }
            
    function getDataRecordsWrapper($isLimited=false,$multivaluesnames=false) {
      $idlist=JRequest::getVar('idlisting',$this->_id);
      jimport('joomla.html.pagination');
      $mainframe = JFactory::getApplication();
      $limit = JRequest::getVar('limit', $mainframe->getCfg('list_limit'));
      $textFilter = JRequest::getVar('lm_search', null);
      $limitstart = JRequest::getVar('limitstart', 0);     
      $db = JFactory::getDBO();      
      $query = $this->_buildQueryCountRecords($idlist,$textFilter);
      $db->setQuery( $query );
      //$countrecords = $db->loadAssocList();
      //$columns=$countrecords[0]['counter'];
      //$total = count($db->loadAssocList());
      $total = $db->loadResult();
      
      $idRecords=null;
      if ($isLimited){
      	$query = $this->_buildQueryRecordsInLimit($idlist,$textFilter);
      	$db->setQuery( $query, $limitstart , $limit);
      	$idRecords=$db->loadColumn();
      	if (count($idRecords)<=0) $idRecords[]=-1;       	
      }
      
      $query = $this->_buildQueryRecords($idlist,$idRecords);
      $db->setQuery($query);
      $arrRecords=$db->loadAssocList();
      $format = JRequest::getVar('format'); //task      
      $filter = JFilterInput::getInstance();
      
      //$result= array();
      
      // Get all data
      $applyFilter= array();
      $base	= JURI::root(true).'/';
      //$multiplegroup=[2,11,16,10];
      $multiplegroup=array(2,11,16,10);
      for ($i=0;$i<count($arrRecords);$i++){
      	$rec=$arrRecords[$i];
      	$rec_value=$rec['value'];
      	
      	if ($rec['type']=='9'){
		      $protocols	= '[a-zA-Z0-9]+:'; //To check for all unknown protocals (a protocol must contain at least one alpahnumeric fillowed by :
			  $regex		= '#(src|href|poster)="(?!/|'.$protocols.'|\#|\')([^"]*)"#m';
			  $rec_value		= preg_replace($regex, "$1=\"$base\$2\"", $rec_value);
	    } 	    
	    if ($multivaluesnames && in_array($rec['type'],$multiplegroup)):
	    	if ($rec['sqltext']!=null):
	    		$rec_value=$this->getMultivalueSQLById($rec['sqltext'],$rec_value)->name;
	    	else:
	    		$rec_value=$this->getMultivaluesNames($idlist,$rec_value);
	    	endif;
	    endif;
	   	if (!$multivaluesnames && in_array($rec['type'],$multiplegroup)):
	   		if ($rec['sqltext']!=null):
	   			$rec_value=$this->getMultivalueSQLById($rec['sqltext'],$rec_value)->name;
	   		else:
	   			$rec_value=$this->getMultivaluesValues($idlist,$rec_value);
	   		endif;
	    endif;    
	    if ($multivaluesnames && $rec['type']==6 && $rec_value!=null && $rec_value!=''):
	    	$user = JFactory::getUser($rec_value);
	    	$rec_value=$user->name;
	    endif;	    
	    
      	$applyFilter[$rec['idrecord']]['id']=$rec['idrecord'];
      	$applyFilter[$rec['idrecord']][$rec['idfield']]=$rec_value;
      }      
      $result= $applyFilter;
            
      $this->_pageNav = new JPagination($total, $limitstart, $limit);
      return $result;
            
  }
  
  private function getMultivalueSQLById($sqltext,$id){
  	if ($sqltext!=null && count($sqltext)>0){
  		$db = JFactory::getDBO();
  		$userdata=JFactory::getUser();
  		// agenius
  		$sqltext=str_replace('##userid##', $userdata->id, $sqltext);
  		//$sqltext=str_replace('##userid##', 994, $sqltext);
  		//var_dump($sqltext);
  		
  		$db->setQuery($sqltext);
  		$res=$db->loadRowList();
  		$fin=null;
  		$finres = new stdClass();
  		for($i=0;$i<count($res);$i++){
  			$tmpres=$res[$i];
  			if ($tmpres[0]==$id){
  				$fin=$tmpres[1];
  				break;
  			}
  		}
  		$finres->name=$fin;
  		return $finres;
  	}
  	return null;
  }
  
  private function getMultivaluesValues($idList,$ids){
  	$ret='';
  	if ($ids!=null && $ids!=''):
	  	$db = JFactory::getDBO();
	  	$ids=explode('#', $ids);
	  	foreach ($ids as $id):
	  		if (strlen($id)>0):
			  	$query = $this->_buildSelectValueMVId($idList,$id);
			  	$db->setQuery($query);
			  	$ret=$ret.' '.$db->loadResult();
			 endif;
	  	endforeach;
  	endif;
  	return $ret;
  }
  
  private function getMultivaluesNames($idList,$ids){
  	$ret='';
  	if ($ids!=null && $ids!=''):
	  	$db = JFactory::getDBO();
	  	$ids=explode('#', $ids);
	  	foreach ($ids as $id):  	
	  		if (strlen($id)>0):
			  	$query = $this->_buildSelectNameMVId($idList,$id);
		  		$db->setQuery($query);
			  	$ret=$ret.' '.$db->loadResult();
			endif;
	  	endforeach;
	endif;
  	return $ret;
  }
  
  /**
     * Method to delete records(s)
     *
     * @access    public
     * @return    boolean    True on success
     */
    function deleteRecords() {
      $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
      $idlisting=JRequest::getVar( 'idlisting');
      $db = JFactory::getDBO();
      
       
      foreach($cids as $cid) {
      	$this->_addAccessDelete($cid,$idlisting);      
        $query = $this->_buildDeleteRecord($cid,$idlisting);
        $db->setQuery( $query );
        $db->query();
        $query = $this->_buildDeleteRates($cid,$idlisting);
        $db->setQuery( $query );
        $db->query();
      }                       
      return true;
    }
    
    function deleteAllRecords() {
      $idlisting=JRequest::getVar( 'idlisting');
      $db = JFactory::getDBO();            
      $query = $this->_buildQueryGetRecordsFromList($idlisting);
      $db->setQuery( $query );
      $cids=$db->loadColumn();      
      foreach($cids as $cid) {
      	$this->_addAccessDelete($cid,$idlisting);      
        $query = $this->_buildDeleteRecord($cid,$idlisting);
        $db->setQuery( $query );
        $db->query();
        $query = $this->_buildDeleteRates($cid,$idlisting);
        $db->setQuery( $query );
        $db->query();
      }                       
      return true;
    }
    
    
    function getRecord() {       
      $db = JFactory::getDBO();
      $query = $this->_buildQueryRecordData($this->_id,JRequest::getVar('idlisting'));
      $db->setQuery($query);
      $arrRecords=$db->loadAssocList();
      $format = JRequest::getVar('format'); //task
      return $arrRecords;      
  }
  
  
  function getUsers() {       
      $db = JFactory::getDBO();
      $query = $this->_buildQueryUsers();
      $db->setQuery($query);
      $arrRecords=$db->loadAssocList();
      return $arrRecords;      
  }
  
  /**
     * Method to insert/update data record
     *
     * @access    public
     * @return    boolean    True on success
     */
    function saveRecord() {
      $idlist = JRequest::getVar( 'idlisting' );
      $idrecord = JRequest::getVar( 'idrecord','' );
      $db =JFactory::getDBO();
      $isNew=true;
      if(strlen($idrecord)>0) $isNew=false;
      if($isNew){	          
	      $query = $this->_buildQueryGetNumRecord($idlist);
	      $db->setQuery($query);
	      $idrecord= $db->loadResult();   
      } else {
	      $query = $this->_buildDeleteRecord($idrecord,$idlist);
	      $db->setQuery($query);
	      $db->query();      
      }
            
      $query = $this->_buildQuerySelectFieldsOne($idlist);
      $db->setQuery($query);
      $campos=$db->loadAssocList();
      
      foreach($campos as $fld){
      		if ($fld['type']=='11' || $fld['type']=='16'){
      			$tmp_array = array_unique(JRequest::getVar('fld_'.$fld['id'], array() , '', 'array'));
      			$tmp_value='';
	       		if (count($tmp_array)>0){
	       			$tmp_value=implode("#", $tmp_array);
	       		}         	     	            	            
	            $queryins = $this->_buildQueryInsertRecord($fld['id'],$idrecord,$tmp_value);      			
      		} elseif ($fld['type']=='15'){
      			$tmp_value=JRequest::getVar( 'fld_'.$fld['id']);
      			if ($tmp_value!='')
      				$queryins = $this->_buildQueryInsertRate($idlist,$fld['id'],$idrecord,$tmp_value); 
      		} elseif ($fld['type']=='19' && $isNew){ //Autoincrement
				$query = $this->_buildQuerySelectNextAutoIncrement($fld['id']);
				$db->setQuery($query); 
		        $idai=$db->loadResult();
		        if (!is_numeric($idai)) $idai=0;	        
		        $tmp_value=$idai+1;
		        //var_dump($tmp_value);
		        $queryins = $this->_buildQueryInsertRecord($fld['id'],$idrecord,$tmp_value);  
	       	} else {
	       		$queryins = $this->_buildQueryInsertRecord($fld['id'],$idrecord,JRequest::getVar( 'fld_'.$fld['id'], '', 'post', 'string', JREQUEST_ALLOWRAW ));
      		}
       		$db->setQuery($queryins);               
        	$db->query();      
      }     
      
      if ($isNew){
      	$this->_addAccessInsert($idrecord,$idlist);	
      }else{
      	$this->_addAccessUpdate($idrecord,$idlist);
      }
      
      return true;
    }
        
     function getDataACL() {       
      $db = JFactory::getDBO();
      $idlist = $this->_id;
      $query = $this->_buildQuerySelectACL($idlist);
      $db->setQuery($query);
      return $db->loadAssocList();
    }
    
    /**
     * Method to insert acl
     *
     * @access    public
     * @return    boolean    True on success
     */
    function saveDataACL($idlisting) {
      $datos = JRequest::getVar( 'acldata' );
      $db =& JFactory::getDBO();
      $idlist = JRequest::getVar( 'idlisting' );
      $query = $this->_buildDeleteACLFromList($idlist);
      $db->setQuery($query);
      $db->query();          
       
     $arrGrupos=explode("@",$datos);   
     for ($i=0;$i<count($arrGrupos);$i++){     
       if($arrGrupos[$i]!=""){
        $arrACLGrupo=explode(';',$arrGrupos[$i]); 
        $queryins = $this->_buildQueryInsertACLGrupo($idlist,$arrACLGrupo[0],$arrACLGrupo[1]);
        $db->setQuery($queryins);        
        $db->query();
      }
     } 
      return true;
    }
    
    function savelayout(){
    	$db = JFactory::getDBO();
	    $row =& $this->getTable();        
        $data = JRequest::get( 'post' );
        $data['id']=JRequest::getVar( 'idlisting' );
        $data['layout']=JRequest::getVar( 'layout','', 'post', 'string', JREQUEST_ALLOWHTML);
        $row->load($data['id']);
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
        return true;
    }
    
	function savedetail(){
    	$db = JFactory::getDBO();
	    $row =& $this->getTable();        
        $data = JRequest::get( 'post' );
        $data['id']=JRequest::getVar( 'idlisting' );
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
        return true;
    }
    
    function savedetailpdf(){
    	$db = JFactory::getDBO();
    	$row =& $this->getTable();
    	$data = JRequest::get( 'post' );
    	$data['id']=JRequest::getVar( 'idlisting' );
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
    	return true;
    }
    
    function savedetailrtf(){
    	$db = JFactory::getDBO();
    	$row = $this->getTable();
    	$data = JRequest::get( 'post' );
    	$data['id']=JRequest::getVar( 'idlisting' );
    	$importfile = JRequest::getVar( 'detailrtf', null, 'files', 'array' );    	    	    	
    	$filename='';
    	if (isset($importfile)) :
    		$filename = rand(1000, 9999).'_'.JFile::makeSafe ( $importfile ['name'] );
    		if ($importfile ['name']!='') :
    			$src = $importfile['tmp_name'];
    			$dest = JPATH_ROOT.DS."media".DS.'com_listmanager'.DS.$filename;
    			JFile::upload($src, $dest);
    			$data['detailrtf']=$filename;
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
    		endif;
    	else:
    		unset($data['detailrtf']);
    	endif;    	
    	return true;
    }
    
    function savelistrtf(){
    	$db = JFactory::getDBO();
    	$row = $this->getTable();
    	$data = JRequest::get( 'post' );
    	$data['id']=JRequest::getVar( 'idlisting' );
    	$importfile = JRequest::getVar( 'listrtf', null, 'files', 'array' );
    	$filename='';
    	if (isset($importfile)) :
    	$filename = rand(1000, 9999).'_'.JFile::makeSafe ( $importfile ['name'] );
    	if ($importfile ['name']!='') :
    	$src = $importfile['tmp_name'];
    	$dest = JPATH_ROOT.DS."media".DS.'com_listmanager'.DS.$filename;
    	JFile::upload($src, $dest);
    	$data['listrtf']=$filename;
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
    	endif;
    	else:
    	unset($data['listrtf']);
    	endif;
    	return true;
    }
    
    function getAlllist(){
    	$db = JFactory::getDBO();
    	$query = $this->_buildQueryAllList();
      	$db->setQuery($query);
      	return $db->loadObjectList();
    }
    
    function getDataAccess(){
    	jimport('joomla.html.pagination');
	    $mainframe = JFactory::getApplication();
	    $limit = JRequest::getVar('limit', $mainframe->getCfg('list_limit'));
	    $limitstart = JRequest::getVar('limitstart', 0);
	    $db = JFactory::getDBO();
	    $query = $this->_buildQueryRecordAccessCount($this->_id);
	    $db->setQuery( $query );
	    $total = $db->loadResult();
	    $query = $this->_buildQueryRecordAccess($this->_id);
	    $db->setQuery( $query, $limitstart, $limit );
	    $this->_pageNav = new JPagination($total, $limitstart, $limit);
	    return $db->loadObjectList();
    }
    
    function resetrating(){
    	$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
      	$idlisting=JRequest::getVar( 'idlisting');
      	$db = JFactory::getDBO();
      	foreach($cids as $cid) {
	      	$query = $this->_buildDeleteRates($cid,$idlisting);
	        $db->setQuery( $query );
	        $db->query();
	        // Get id rate field	        	        
	        $query = $this->_buildQuerySelectFieldsOne2($idlisting,15);
	        $db->setQuery( $query );
	        $idField=$db->loadAssoc();
	        if ($idField!=null):
		        // delete rate value
		        $query = $this->_buildDeleteRatesValue($cid,$idField['id']);
		        $db->setQuery( $query );
		        $db->query();
		    endif;
	      }                       
	      return true;
    }
    
	function resetratingone(){
    	$idrecord = JRequest::getVar( 'idrecord' );
      	$idlisting=JRequest::getVar( 'idlisting');
      	$db = JFactory::getDBO();      	
      	$query = $this->_buildDeleteRates($idrecord,$idlisting);
        $db->setQuery( $query );
        $db->query();
        // Get id rate field	        	        
        $query = $this->_buildQuerySelectFieldsOne2($idlisting,15);
        $db->setQuery( $query );
        $idField=$db->loadAssoc();
        if ($idField!=null):
	        // delete rate value
	        $query = $this->_buildDeleteRatesValue($idrecord,$idField['id']);
	        $db->setQuery( $query );
	        $db->query();	                           
	    endif;
	    return true;
    }
    
function getExportList(){
    	$result=array();
    	$result[]='<listmanager>';
    	$db = JFactory::getDBO();      	
    	$query='select * from #__listmanager_listing where id='.$this->_id;
    	$db->setQuery( $query );
    	$resquery=$db->loadAssoc();
    	$result[]=$this->_setXMLFromArray('listing',$resquery);
    	$query='select * from #__listmanager_view where idlisting='.$this->_id;
    	$db->setQuery( $query );
    	$resquery=$db->loadAssocList('id');
    	$idviews=array();
    	$result[]='<views>';
    	foreach ($resquery as $key=>$restmp):
    		$idviews[]=$key;
    		$result[]=$this->_setXMLFromArray('view',$restmp);
    	endforeach;
    	$result[]='</views>';
    	$query='select * from #__listmanager_acl where idlisting='.$this->_id;
    	$db->setQuery( $query );
    	$resquery=$db->loadAssocList();
    	$result[]='<acls>';
    	foreach ($resquery as $key=>$restmp):
    		$result[]=$this->_setXMLFromArray('acl',$restmp);
    	endforeach;    	    
    	$result[]='</acls>';
    	$query='select * from #__listmanager_field where idlisting='.$this->_id;
    	$db->setQuery( $query );
    	$resquery=$db->loadAssocList('id');
    	$idfields=array();
    	$result[]='<fields>';
    	foreach ($resquery as $key=>$restmp):
    		$idfields[]=$key;
    		$result[]=$this->_setXMLFromArray('field',$restmp);
    	endforeach;
    	$result[]='</fields>';
    	if (count($idfields)>0):
	    	$query='select * from #__listmanager_field_multivalue where idfield in ('.implode(',',$idfields).')';
	    	$db->setQuery( $query );
	    	$resquery=$db->loadAssocList();
	    	$result[]='<fields_multivalue>';
	    	foreach ($resquery as $key=>$restmp):
			//TODO: Add fix to NV!!	
	    		//$idfields[]=$key;
	    		$result[]=$this->_setXMLFromArray('field_multivalue',$restmp);
	    	endforeach;
	    	$result[]='</fields_multivalue>';	
	    endif;
    	if (count($idviews)>0):
	    	$query='select * from #__listmanager_field_view where idfield in ('.implode(',',$idfields).') and idview in ('.implode(',',$idviews).')';
	    	$db->setQuery( $query );
	    	$resquery=$db->loadAssocList();
	    	$result[]='<fields_view>';
	    	foreach ($resquery as $key=>$restmp):
	    		$result[]=$this->_setXMLFromArray('field_view',$restmp);
	    	endforeach;
	    	$result[]='</fields_view>';	
	    endif;
	    if (count($idfields)>0):
	    	$query='select * from #__listmanager_values where idfield in ('.implode(',',$idfields).')';
	    	$db->setQuery( $query );
			
		//TODO: Add fix to NV!!	
	    	//$resquery=$db->loadAssocList('idrecord');
			
			$resquery=$db->loadAssocList('id');
			
			
	    	$result[]='<values>';
	    	$idrecords=array();
	    	foreach ($resquery as $key=>$restmp):
			
			//TODO: Add fix to NV!!
	    		$idrecords[]=$restmp["idrecord"];
				
		//TODO: Add fix to NV!!
		
	    		$result[]=$this->_setXMLFromArray('value_field',$restmp);
	    	endforeach;
	    	$result[]='</values>';
	    
	    	/*$query='select * from #__listmanager_access where idfield in ('.implode(',',$idfields).') and idrecord in ('.implode(',',$idrecords).')';
	    	$db->setQuery( $query );
	    	$resquery=$db->loadAssocList();
	    	$result[]='<accesses>';
	    	if (is_array($resquery)):
		    	foreach ($resquery as $key=>$restmp):
		    		$result[]=$this->_setXMLFromArray('access',$restmp);
		    	endforeach;
		    endif;
		    $result[]='</accesses>';
		    */
	    	$query='select * from #__listmanager_rate where idlisting='.$this->_id.' and idrecord in ('.implode(',',$idrecords).')';
	    	$db->setQuery( $query );
	    	$resquery=$db->loadAssocList();
	    	$result[]='<rates>';
	    	if (is_array($resquery)):
		    	foreach ($resquery as $key=>$restmp):
		    		$result[]=$this->_setXMLFromArray('rate',$restmp);
		    	endforeach;
		    endif;
	    	
	    	$result[]='</rates>';
	    endif;
    	$result[]='</listmanager>';	
    	return implode('',$result);    	
    }
    
    private function _setXMLFromArray($title,$elems){
    	$result=array();
    	$result[]='<'.$title.'>';
    	foreach ($elems as $key=>$value){
    		$result[]='<'.$key.'><![CDATA['.$value.']]></'.$key.'>';		
    	}    	
    	$result[]='</'.$title.'>';
    	return implode('',$result);
    }
    
	public function import(){
    	$db = JFactory::getDBO ();
    	$new_idlisting=0;
    	$importfile = JRequest::getVar( 'import', null, 'files', 'array' );    	
    	if (isset($importfile)):
			$filename = JFile::makeSafe($importfile['name']);			
			if ($filename):
				$src = $importfile['tmp_name'];
				$dest = JPATH_ROOT.DS."media".DS.'com_listmanager'.DS.$filename;
    			JFile::upload($src, $dest);
				$xml = simplexml_load_file($dest);
				// Listing
				$listing=array();
				foreach ( $xml->listing->children() as $flds ):
					if (strlen((string)$flds)>0 && $flds->getName()!='id')
						$listing[$flds->getName()]=(string)$flds;
				endforeach;
				$listingObj=(object)$listing;
				$result = JFactory::getDbo()->insertObject('#__listmanager_listing', $listingObj);
				$new_idlisting=$db->insertid();				
				// Views
				$viewAssoc=array();
				foreach ( $xml->views->children() as $items ):
					$view=array();
					$idviewant=null;
					foreach($items->children() as $flds):
						if (strlen((string)$flds)>0 && $flds->getName()!='id')
							$view[$flds->getName()]=(string)$flds;
						elseif($flds->getName()=='id')
							$idviewant=(string)$flds;														
					endforeach;
					$view['idlisting']=$new_idlisting;
					$viewObj=(object)$view;
					$result = JFactory::getDbo()->insertObject('#__listmanager_view', $viewObj);
					$new_idview=$db->insertid();
					$viewAssoc[$idviewant]=$new_idview;		
				endforeach;
				// Acl
				foreach ( $xml->acls->children() as $items ):
					$acl=array();
					foreach($items->children() as $flds):
						if (strlen((string)$flds)>0 && $flds->getName()!='id')
							$acl[$flds->getName()]=(string)$flds;														
					endforeach;
					$acl['idlisting']=$new_idlisting;
					$aclObj=(object)$acl;
					$result = JFactory::getDbo()->insertObject('#__listmanager_acl', $aclObj);							
				endforeach;
				// Field
				$lstfieldsmultivalue=array();
				$fieldAssoc=array();
				foreach ( $xml->fields->children() as $items ):
					$field=array();
					$idfieldant=null;
					foreach($items->children() as $flds):
					
					
					
					
						if (strlen((string)$flds)>0 && $flds->getName()!='id')
							$field[$flds->getName()]=(string)$flds;		
						elseif($flds->getName()=='id')
							$idfieldant=(string)$flds;														
					endforeach;
					$field['idlisting']=$new_idlisting;
					
					
					//if multivalue a la lista
					
					if($field["type"]=="2"||$field["type"]=="11"||$field["type"]=="16"||$field["type"]=="10"){
					
								$lstfieldsmultivalue[]=$idfieldant;
					}
					
					
					$fieldObj=(object)$field;
					$result = JFactory::getDbo()->insertObject('#__listmanager_field', $fieldObj);
					$new_idfield=$db->insertid();
					$fieldAssoc[$idfieldant]=$new_idfield;													
				endforeach;
				
				
				
				$multifieldAssoc=array();
				
				// fields_multivalue
				foreach ( $xml->fields_multivalue->children() as $items ):
					$fmv=array();
					foreach($items->children() as $flds):
						if (strlen((string)$flds)>0 && $flds->getName()!='id')
							$fmv[$flds->getName()]=(string)$flds;
						elseif($flds->getName()=='id')
							$idmultifieldant=(string)$flds;			
					endforeach;
					$fmv['idfield']=$fieldAssoc[$fmv['idfield']];
					
					
		//TODO: ADD FIX TO NV!!!!!			
					
					$fmv['idobj']=$new_idlisting;
					
					
					
					$fmvObj=(object)$fmv;
					$result = JFactory::getDbo()->insertObject('#__listmanager_field_multivalue', $fmvObj);		

					$new_idmultifield=$db->insertid();
					$multifieldAssoc[$idmultifieldant]=$new_idmultifield;
					
				endforeach;
				// fields_view
				foreach ( $xml->fields_view->children() as $items ):
					$fv=array();
					foreach($items->children() as $flds):
						if (strlen((string)$flds)>0 && $flds->getName()!='id')
							$fv[$flds->getName()]=(string)$flds;														
					endforeach;
					$fv['idfield']=$fieldAssoc[$fv['idfield']];
					$fv['idview']=$viewAssoc[$fv['idview']];
					$fvObj=(object)$fv;
					$result = JFactory::getDbo()->insertObject('#__listmanager_field_view', $fvObj);							
				endforeach;
				// values
				$recordAssoc=array();
				foreach ( $xml->values->children() as $items ):
					$values=array();
					foreach($items->children() as $flds):
						if (strlen((string)$flds)>0 && $flds->getName()!='id')
							$values[$flds->getName()]=(string)$flds;														
					endforeach;
					
					
					
					//if multivalue-sustituir x nuevo idmultivalue
					
					if(in_array($values['idfield'],$lstfieldsmultivalue)):
						$multifieldassocarr=explode('#', $values['value']);
						$newmultifieldsarrvals=array();
						foreach ($multifieldassocarr as $innerval):
							$newmultifieldsarrvals[]=$multifieldAssoc[$innerval];
						endforeach;
						$values['value']=implode('#', $newmultifieldsarrvals);
					endif;
					
					
					$values['idfield']=$fieldAssoc[$values['idfield']];
					
					
					// No renumber recordid
					/*if (!isset($recordAssoc[$fv['idrecord']])):
						$new_idrecord=$this->_getNumRecord($new_idlisting);
						$recordAssoc[$values['idrecord']]=$new_idrecord;
					endif;
					$values['idrecord']=$recordAssoc[$values['idrecord']];*/
					$valuesObj=(object)$values;
					$result = JFactory::getDbo()->insertObject('#__listmanager_values', $valuesObj);							
				endforeach;
				// accesses
				/*foreach ( $xml->accesses->children() as $items ):
					$access=array();
					foreach($items->children() as $flds):
						if (strlen((string)$flds)>0 && $flds->getName()!='id')
							$access[$flds->getName()]=(string)$flds;														
					endforeach;
					$access['idlisting']=$new_idlisting;
					$accessObj=(object)$access;
					$result = JFactory::getDbo()->insertObject('#__listmanager_access', $accessObj);							
				endforeach;*/
				// rates
				foreach ( $xml->rates->children() as $items ):
					$rates=array();
					foreach($items->children() as $flds):
						if (strlen((string)$flds)>0 && $flds->getName()!='id')
							$rates[$flds->getName()]=(string)$flds;														
					endforeach;
					$rates['idlisting']=$new_idlisting;
					$ratesObj=(object)$rates;
					$result = JFactory::getDbo()->insertObject('#__listmanager_rate', $ratesObj);							
				endforeach;
				JFile::delete($dest);
    		endif;
    	endif;    	
    }    
    
    
}


?>
