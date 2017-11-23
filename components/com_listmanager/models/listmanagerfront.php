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

class ListmanagerModelListmanagerfront extends ListmanagerMain
{
    var $_id;
    var $_idview;
    var $_data;
 
    /**
     * Returns the query
     * @return string The query to be used to retrieve the rows from the database
     */
    function _buildQuerySelect($id=null){           
        $db = JFactory::getDBO();
        $query = "select * from #__listmanager_listing ";
        if ($id!=null){
          if (is_numeric($id)) $query .= 'where '.$db->quote('id').' ='.$db->quote($id);
          else JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
        }        
        return $query;
    }  
    
    function setId($id){
    	if (strrpos($id,'v_')!==false){
    		$this->_id = $this->getFieldView(substr($id, 2));
    		//$this->_idview = strstr($id, 'v_');
    		$this->_idview = substr($id, 2);
    	}
    	else	
        	$this->_id = $id;
    }
    
    
    /**
     *Cabecera de la lista
     */         
    protected function _buildQuery($id){
      if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
      $query = "select u.* from #__listmanager_listing u ";
      if ($id!=null) $query .= 'where u.id='.$id;        
      return $query;      
      
   }
	function _buildQuerySelectViewFieldsOne($id,$idview){
        if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
      	if (!is_numeric($idview)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
      	$defaultcondition="ifnull(v.defaulttext,f.defaulttext)";
       	if(!defined('DS')){
            define('DS',DIRECTORY_SEPARATOR);
        }
       	if(file_exists (dirname(__FILE__).DS.'customserverpages.php')){
        	include(dirname(__FILE__).DS.'customserverpages.php');
     	}
     
      $query = "select f.id,f.mandatory, f.idlisting,f.type, f.`decimal`,f.`order`,
                f.size,f.name,f.limit0,f.limit1,f.multivalue,f.sqltext,f.total, f.readmore, f.readmore_word_count,
                f.link_type, f.link_width, f.link_height, f.css, f.placeholder,f.bulk,
                ifnull(v.visible,f.visible) as visible,
                ifnull(v.autofilter,f.autofilter) as autofilter,
                ifnull(v.showorder,f.showorder) as showorder,
                ".$defaultcondition." as defaulttext,
                ifnull(v.order,f.order) as `order`,
                f.exportable,f.innername
            from #__listmanager_field f left join #__listmanager_field_view v on f.id=v.idfield and v.idview=".$idview."
                where f.idlisting=".$id;     
      $query.=" order by v.`order`, f.`order` asc";
      return $query;
 
  }
   
   
    function _buildQuerySelectFieldsOne($id,$type=null){
        if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
        $query = "select f.*, (select fin.name from #__listmanager_field fin where fin.id=f.link_id) as link_name
          from #__listmanager_field f where idlisting=".$id." order by `order` asc";
        return $query;
    }
    
    function _buildQueryRecords($id,$idrecord=null){    
      if (!is_numeric($id)) JError::raiseError(506,JText::_('INTERNAL SERVER ERROR'));    
      $query = "SELECT v.id,v.idfield,v.value,v.idrecord,f.type,f.decimal,f.order,f.size,f.name,f.visible,f.total,f.defaulttext,f.bulk      
      			FROM #__listmanager_values v,#__listmanager_field f
      			where f.id=v.idfield and  idlisting=".$id;
      if ($idrecord!=null)
      	$query .= " and v.idrecord=".$idrecord;
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
	
  
   function _buildQueryGetNumRecord($id){   
    if (!is_numeric($id)) JError::raiseError(503,JText::_('INTERNAL SERVER ERROR'));   
      $query = "select IFNULL(max(v.idrecord)+1,1) as maxidrecord
        from #__listmanager_values v,#__listmanager_field f where f.idlisting=".$id." and f.id=v.idfield";
      return $query;
  }  
  
  function _buildQueryDeleteRecords($id,$idrecord){
    if (!is_numeric($id)) JError::raiseError(501,JText::_('INTERNAL SERVER ERROR'));
    if (!is_numeric($idrecord)) JError::raiseError(502,JText::_('INTERNAL SERVER ERROR'));  
      $query = "delete from #__listmanager_values where idrecord=".$idrecord." and idfield in (select f.id from #__listmanager_field f where f.idlisting=".$id.")";
      return $query;
  }
  
  function _buildQueryInsertRecord($idfield,$idrecord,$value){
    if (!is_numeric($idfield)||!is_numeric($idrecord)) JError::raiseError(507,JText::_('INTERNAL SERVER ERROR'));    
    $db = JFactory::getDBO();
    $value=$db->quote($value);
    $query = "insert into  #__listmanager_values (idfield,idrecord,value) values (".$idfield.",".$idrecord.",".$value.")";
    return $query;
  }
    
  function _buildQuerySelectACLString($id,$idGroup){
   if (!is_numeric($id)||!is_numeric($idGroup)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR').$id);
   $query = "select acl from #__listmanager_acl where idlisting=".$id." and  idgroup=".$idGroup;
    return $query;  
  }
  
	function _buildQuerySelectAllFieldsLayout($id){
		if (!is_numeric($id)) JError::raiseError(501,JText::_('INTERNAL SERVER ERROR'));
        $query = "select * from #__listmanager_field where idlisting=".$id;
        return $query; 
    }
    
	function _buildQuerySelectView($id){
		if (!is_numeric($id)) JError::raiseError(501,JText::_('INTERNAL SERVER ERROR'));
        $query = "select idlisting from #__listmanager_view where id=".$id;        
        return $query;
    }
    private function _buildQuerySelectViewParameters($id){
    	$query = "select * from #__listmanager_view where id=".$id;
    	return $query;
    }

  function getDataFields() {       
    $db = JFactory::getDBO();
    $id =JRequest::getVar('id');
    $query = $this->_buildQuerySelectFieldsOne2($id);
    $db->setQuery($query);
    return $db->loadAssocList();
  }

	function getFieldView($id) {       
      $db = JFactory::getDBO();
      $query = $this->_buildQuerySelectView($id);
      $db->setQuery($query);
      return $db->loadResult();
    }

    function getDataRecords() {
    	$this->getDataRecordsWrapper(null);
    }
    function getDataRecord($idrecord) {
    	$this->getDataRecordsWrapper($idrecord);
    }
    private function getDataRecordsWrapper($idrecord=null) {
      $db = JFactory::getDBO();
      $id =JRequest::getVar('id');
      $query = $this->_buildQueryRecords($id,$idrecord);      
      $db->setQuery($query);
      $arrRecords=$db->loadAssocList();
      $format = JRequest::getVar('format'); //task      
      $filter = &JFilterInput::getInstance();
      
      //filtro permisos
      $arrdescartes=array(-1);
      $access_type=JRequest::getVar('access_type');
      $user_on=JRequest::getVar('user_on');
      
      //var_dump($access_type);
      
      foreach ($arrRecords as $rec) {
	      //si hay que filtrar por usuario, hacerlo aqui (access_type, user_on))
	      if($rec['type']=='6'){	      
	        $result[$rec['idrecord']]['_struser']=& JFactory::getUser($rec['value'])->name;
	        if($access_type=='1'&&$user_on!=$rec['value']){	        
	          $arrdescartes[]=$rec['idrecord'];	        
	        }
	      }
	        $result[$rec['idrecord']]['id']=$rec['idrecord'];
	        //$result[$rec['idrecord']][$rec['idfield']]=$rec['value'];
	        if ($format=="pdfr"){        
	          $result[$rec['idrecord']][$rec['idfield']]=$filter->clean($rec['value']);
	        } else {
	          $result[$rec['idrecord']][$rec['idfield']]=$rec['value'];
	        }
      }
      foreach($arrdescartes as $d){
        unset($result[$d]);
      }          
      return $result;
  }  
    
     public function getDataFieldsParam() {       
    $db = JFactory::getDBO();    
    $query = $this->_buildQuerySelectFieldsOne2($this->_id);
    if ($this->_idview!=null) $query = $this->_buildQuerySelectViewFieldsOne($this->_id,$this->_idview);
    $db->setQuery($query);
    //return $db->loadAssocList();
    return $db->loadObjectList();
  }
  
  public function getDataParam() { 
  	$db = JFactory::getDBO();
    $query = $this->_buildQuery($this->_id);
    $db->setQuery($query);
    $ret= $db->loadAssoc();
    if ($this->_idview!=null):
    	$ret=array_merge($ret,array_filter($this->checkViewParameters($this->_idview)));    	
    endif;
    return $ret;
  }
  private function checkViewParameters($idview){
  	$db = JFactory::getDBO();
  	$query = $this->_buildQuerySelectViewParameters($idview);
  	$db->setQuery($query);
  	return $db->loadAssoc();
  }
    
   public function getACLStringParam() {      
    $aclString=""; 
    $db = JFactory::getDBO();        
    $user = JFactory::getUser();
    $arrGroups=$user->getAuthorisedGroups();
    
    foreach($arrGroups as $idgrupo){
     $query = $this->_buildQuerySelectACLString($this->_id,$idgrupo);
      $db->setQuery($query);
      $aclString.="#".$db->loadResult();
    
    }
    return $aclString;
  }
     
	public function getDataAllFieldsLayout() {       
      $db = JFactory::getDBO();
      $query = $this->_buildQuerySelectAllFieldsLayout($this->_id);
      if ($this->_idview!=null) $query = $this->_buildQuerySelectViewFieldsOne($this->_id,$this->_idview);
      $db->setQuery($query);
      return $db->loadObjectList('id');
   }
   
	private function _buildQueryDetailList($id){
   		$db = JFactory::getDBO();
   		return  "select detail from #__listmanager_listing where id=".$db->quote($id);
   }
	private function _buildQueryDetailView($id){
   		$db = JFactory::getDBO();
   		return  "select detail from #__listmanager_view where id=".$db->quote($id);
   }
    public function getDetail(){
    	$id=$this->_id;
		$db = JFactory::getDBO();
		$query = $this->_buildQueryDetailList($id);
    	$db->setQuery($query);
    	$result=$db->loadResult();
		if ($this->_idview):
    		$query = $this->_buildQueryDetailView($this->_idview);
    		$db->setQuery($query);
    		$viewres=$db->loadResult();
    		if ($viewres!=null && $viewres!=''){ $result=$viewres;}
    	endif;
    	preg_match_all('/\[#auth([^]]+)\]([^]]+)\[#endauth\]/', $result, $autharr,PREG_SET_ORDER);
    	if (count($autharr)>0):
	    	$user_  = JFactory::getUser();
	    	$usergroups=$user_->groups;
	    	for ($i=0;$i<count($autharr);$i++):
		    	$occurrence=$autharr[$i];
		    	$total=$occurrence[0];
		    	$params=$occurrence[1];
		    	$content=$occurrence[2];
		    	preg_match_all('/([yes|no]+)[=]([\d\,]*)/', $params, $gr,PREG_SET_ORDER);
		    	$res=true;
		    	if($gr!=null):
			    	foreach ($gr as $tmpgr):
				    	$paramsgr=explode(',',$tmpgr[2]);
				    	foreach ($paramsgr as $pgr):
					    	if($tmpgr[1]=='yes'):
						    	if(in_array($pgr, $usergroups)):
							    	$res=true;
							    	break;
						    	endif;
						    elseif ($tmpgr[1]=='no'):
							   	if(in_array($pgr, $usergroups)):
							    	$res=false;
							    	break;
						    	endif;
					    	endif;
				    	endforeach;
			    	endforeach;
		    	endif;
		    	if($res):
		    		$result=str_replace($total, $content, $result);
		    	else:
		    		$result=str_replace($total,'', $result);
		    	endif;
	    	endfor;
    	endif;
		return $result;
    }
    public function repopulateList(){    	
    	$id=$this->_id;    	
		$db = JFactory::getDBO();
		$query = $this->_buildQuery($id);
    	$db->setQuery($query);
    	$listing=$db->loadObject();
    	if (strlen(strip_tags($listing->info))>0):
    		$this->deleteAllRecords($id);
    		$this->resetAutoincrement();
	    	$db->setQuery(strip_tags($listing->info));
	    	$arrIns=$db->loadRowList();
	    	$dataFields=$this->getDataFieldsPop();    	
	    	$arrids;
	    	$f=0;
	    	foreach($dataFields as $field){ $arrids[$f++]=$field->id;}    	
	    	for($i=0;$i<count($arrIns);$i++){
	    		$dataIns=$arrIns[$i];
	    		$_idrecord=$this->_getNumRecord($id);
	    		for ($j=0;$j<count($arrids);$j++){
	    			$queryins = $this->_buildQueryInsertRecord($arrids[$j],$_idrecord,trim($dataIns[$j]));
	    			$db->setQuery($queryins);
	    			$db->query();
	    		}
	    	}    	
	    endif;
    }
    private function deleteAllRecords($idlisting) {
    	$db = JFactory::getDBO();
    	$query = $this->_buildQueryGetRecordsFromList($idlisting);
    	$db->setQuery( $query );
    	$cids=$db->loadColumn();
    	foreach($cids as $cid) {
    		$query = $this->_buildDeleteRecord($cid,$idlisting);
    		$db->setQuery( $query );
    		$db->query();
    		$query = $this->_buildDeleteRates($cid,$idlisting);
    		$db->setQuery( $query );
    		$db->query();
    	}
    	return true;
    }
    private function _buildDeleteRecord($idrecord,$idlist){
	    $query ="delete from #__listmanager_values where idrecord=".$idrecord." and idfield in (select id from #__listmanager_field where idlisting=".$idlist.")";
	    return $query;
	  
	  }
	private function _buildDeleteRates($idrecord,$idlisting){
    	if (!is_numeric($idrecord)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	if (!is_numeric($idlisting)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
        $query = "delete from #__listmanager_rate where idrecord=".$idrecord.' and idlisting='.$idlisting ;
        return $query;
    }
    private function _buildQueryGetRecordsFromList($id){   
    	if (!is_numeric($id)) JError::raiseError(503,JText::_('INTERNAL SERVER ERROR'));
      	$query = "select distinct(v.idrecord) as idrecord
        	from #__listmanager_values v,#__listmanager_field f where f.idlisting=".$id." and f.id=v.idfield";
      	return $query;
  	}
  	private function resetAutoincrement(){
  		$maxElem=$this->getLatestId()+1;
  		$db = JFactory::getDBO();
  		$query = 'ALTER TABLE #__listmanager_values AUTO_INCREMENT = '.$maxElem;
  		$db->setQuery($query);
  		$db->query();
  	}
  	private function getLatestId(){
  		$db = JFactory::getDBO();
  		$query = 'select max(id) from #__listmanager_values';
  		$db->setQuery($query);
  		return $db->loadResult();
  	}
    private function getDataFieldsPop() {  
      $idlist=JRequest::getVar('idlisting',null);
      if ($idlist==null){  $idlist=$this->_id;}     
      $db = JFactory::getDBO();
      $query = $this->_buildQuerySelectFieldsOne($idlist);
      $db->setQuery($query);
      return $db->loadObjectList();
    }
    private function _getNumRecord($idlisting){
    	$db = JFactory::getDBO();
    	$query = $this->_buildQueryGetNumRecord($idlisting);
        $db->setQuery($query);
        return $db->loadResult();
    }	
    
}


?>
