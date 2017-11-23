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
jimport( 'joomla.user.user' );
include JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'main.model.php';

class ListmanagerModelListmanager extends ListmanagerMain
{
    
    var $_data;
 
    /**
     * Returns the query
     * @return string The query to be used to retrieve the rows from the database
     */
    function _buildQuerySelect($id=null){           
        $db =JFactory::getDBO();
        $query = "select * from #__listmanager_listing ";
        if ($id!=null){
          if (is_numeric($id)) $query .= 'where id ='.$db->quote($id);
          else JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
        }        
        return $query;
    }  

    
    /**
     *Cabecera de la lista
     */         
    protected function _buildQuery($id){
      if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
      $query = "select u.id, u.name, u.info 
          from #__listmanager_listing u ";
      if ($id!=null) $query .= 'where u.id='.$id;        
      return $query;      
      
   }
   
   
    function _buildQuerySelectFieldsOne($id,$type=null){
        if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
        $query = "select *
          from #__listmanager_field  where idlisting=".$id;
        if ($type!=null) $query .= ' and type='.$type;
        $query.=" order by `order` asc";
        return $query;
    }

  
   function _buildQuerySelectFieldsOne2($id){
   
     if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
      $query = "select * from #__listmanager_field  where idlisting=".$id;
      $query.=" order by `order` asc";
      return $query;
  }
  
 function _buildQuerySelectFieldsOne2View($id,$idview){   
     if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
     if (!is_numeric($idview)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
      $query = "select f.id,f.mandatory, f.idlisting,f.type, f.`decimal`,f.`order`,
			    f.size,f.name,f.limit0,f.limit1,f.multivalue,f.sqltext,f.total,
			    ifnull(v.visible,f.visible) as visible,
			    ifnull(v.autofilter,f.autofilter) as autofilter,
			    ifnull(v.showorder,f.showorder) as showorder,
			    ifnull(v.defvalue,f.defvalue) as defvalue
			from #__listmanager_field f left join #__listmanager_field_view v on f.id=v.idfield and v.idview=".$idview." 
				where f.idlisting=".$id;      
      $query.=" order by f.`order` asc";
      return $query;
  }
  
  function _buildQueryView($idview){
  	 if (!is_numeric($idview)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
      $query = "select * from #__listmanager_field_view where idview=".$idview;
      return $query;
  }
  
  
   function _buildQueryGetNumRecord($id){   
    if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));   
      $query = "select IFNULL(max(v.idrecord)+1,1) as maxidrecord
        from #__listmanager_values v,#__listmanager_field f where f.idlisting=".$id." and f.id=v.idfield";
      return $query;
  }
  
  
  function _buildQueryDeleteRecords($id,$idrecord,$fields=null){
    if (!is_numeric($id)) JError::raiseError(501,JText::_('INTERNAL SERVER ERROR'));
    if (!is_numeric($idrecord)) JError::raiseError(502,JText::_('INTERNAL SERVER ERROR'));
    $query = "delete from #__listmanager_values where idrecord=".$idrecord." and idfield in (select f.id from #__listmanager_field f where f.idlisting=".$id.")";
    if ($fields!=null && count($fields)>0){
    	$query.=" and idfield not in (".implode(',', $fields).")";
    }
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
   if (!is_numeric($id)||!is_numeric($idGroup)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
   $query = "select acl from #__listmanager_acl where idlisting=".$id." and  idgroup=".$idGroup;
      return $query;
  
  }
  
  function _buildQuerySelectView($id){
  	if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
        $query = "select idlisting from #__listmanager_view where id=".$id;        
        return $query;
    }
    
  function _buildQueryUserRecord($idrecord,$id){
  	if (!is_numeric($id)||!is_numeric($idrecord)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
  	$query = "select v.value
  	from #__listmanager_values v left join #__listmanager_field f on v.idfield=f.id
	where v.idrecord=".$idrecord." and f.type=6 and f.idlisting=".$id;
  	return $query;
  }
  
  function _buildDeleteRates($idrecord,$idlisting){
    	if (!is_numeric($idrecord)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	if (!is_numeric($idlisting)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
        $query = "delete from #__listmanager_rate where idrecord=".$idrecord.' and idlisting='.$idlisting ;
        return $query;
    } 
  
    function _buildQuerySelectNextAutoIncrement($idfield){
    	if (!is_numeric($idfield)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	$query = "select max(0+value) from #__listmanager_values where idfield=".$idfield ;
        return $query;
    }
    
    function _buildQueryCountRecords($_id,$keyfields,$idrecord){
    	if (!is_numeric($_id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
    	$db = JFactory::getDBO();
    	$query = "select count(idfield),idrecord from #__listmanager_values where (";
    	$indx=0;
    	foreach ($keyfields as $key=>$fields):
    		if ($indx>0) $query .= " or ";    		
    		$query .= " (idfield=".$key." and value=".$db->quote($fields).") ";
    		$indx++;
    	endforeach;
    	$query .= " ) ";
    	if ($idrecord!=null && count($idrecord)>0) $query .= " and idrecord!=".$idrecord." "; 
    	$query .= " group by idrecord having count(idfield)=".count($keyfields);
    	//$query .= " group by idrecord having count(idfield)>0"; //to deny if only one match
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
    $id =JRequest::getVar('id');
    return $this->getListWrapper($id);
  }
	function getListWrapper($id){  	
  	$db = JFactory::getDBO();
    $id=$this->checkId($id); 
    $query = $this->_buildQuerySelect($id);
    $db->setQuery($query);
    return $db->loadObject();
  }
  
  function getFieldsView($idview){
  	$db = JFactory::getDBO();
  	$query = $this->_buildQueryView($idview);
  	$db->setQuery($query);
    return $db->loadObjectList('idfield');
  }
  
  

  function getDataFields() {       
    $db = JFactory::getDBO();
    $id =JRequest::getVar('id');
    if ($this->isView($id)){
    	$idview=substr($id, 2);
	    $id=$this->checkId($id);	    
	    $query = $this->_buildQuerySelectFieldsOne2View($id,$idview);
    } else {
    	$query = $this->_buildQuerySelectFieldsOne2($id);
    }    
    $db->setQuery($query);
    return $db->loadAssocList();
  }
  
   function saveDataForm(){         
   	return $this->saveData(true);
   }
    function saveData($isFormModule=false){         
      $_data = JRequest::get('post'); 
      $_idant = JRequest::getVar('id'); //idlisting
      $_id=$this->checkId($_idant);    
      $_list=$this->getListWrapper($_id);
      $_idrecord = JRequest::getVar('idrecord',''); //idrecord
      $db = JFactory::getDBO();
      
      //data array
      //get fields del listing      
      $query = $this->_buildQuerySelectFieldsOne2($_id);
      $db->setQuery($query);
      $arrfields= $db->loadAssocList();
      
      $isNew=true;
      if(strlen($_idrecord)>0){ $isNew=false;}
      
      
      // Permission
      if($isNew && !$this->getACLPermission($_idant, 'add') && !$isFormModule) return false;
      $NOPermissionFields=array();
      $uid=0;
      if(!$isNew){
      	  foreach($arrfields as $field){
	      	if ($field['type']=='6'){
	       		$query = $this->_buildQueryUserRecord($_idrecord,$_id);
	      		$db->setQuery($query);
	      		$uid= $db->loadResult();
	       	}
	      }
      } 
      if (!$isFormModule){
	      foreach($arrfields as $field){
	      	if (!$isNew && !$this->getACLPermission($_idant, 'edit',$field['id'],$uid)){
	      		$NOPermissionFields[]=$field['id'];
	      	}
	      }
      }
      
      // Comprobar los keyfields. Contar registros con parametros. Si es editar y hay +1 o nuevo y hay +0 no hacer nada
      if (strlen(trim($_list->keyfields))>0):      	
      	$keyfields=array();
      	$keyfieldsid=explode(',',$_list->keyfields);
      	foreach ($keyfieldsid as $keyid):
      		$value=JRequest::getVar('fld_'.$keyid, '', '', 'string', JREQUEST_ALLOWRAW);
	      	foreach($arrfields as $field){
	      		if ($field['type']=='6' && $field['id']==$keyid){
	      			$user = JFactory::getUser();
	      			$value= $user->id;
	      			break;
	      		}
	      	}
      		$keyfields[$keyid]=$value;
      	endforeach;      	
      	$query = $this->_buildQueryCountRecords($_id,$keyfields,$_idrecord);
        $db->setQuery($query);
        $_countkeys= $db->loadResult();
        if ($_countkeys>0) return true;
      endif;
      
      if($isNew){
      	//get max registro para el idlisting      
        $query = $this->_buildQueryGetNumRecord($_id);
        $db->setQuery($query);
        $_idrecord= $db->loadResult();                 
      } else {      
        $query = $this->_buildQueryDeleteRecords($_id,$_idrecord,$NOPermissionFields);
        $db->setQuery($query);       
        $result = $db->query();       
      }

      // Access Type
      /*$session = JFactory::getSession();
	  $acl_global=$session->get('lm_'.$_idant);
      $access_type=$acl_global['access_type'];*/
      
     foreach($arrfields as $field){
       	$tmp_value=JRequest::getVar('fld_'.$field['id'], '', '', 'string', JREQUEST_ALLOWRAW);
       	if ($field['type']=='6'){ 
       		$user = JFactory::getUser();
       		$tmp_value=$user->id;
       	} elseif ($field['type']=='11' || $field['type']=='16'){
       		$tmp_array = array_unique(JRequest::getVar('fld_'.$field['id'], array() , '', 'array'));
       		if (count($tmp_array)>0){
       			$tmp_value=implode("#", $tmp_array);
       		}         	       
       	} elseif ($field['type']=='19' && $isNew){ //Autoincrement
			$query = $this->_buildQuerySelectNextAutoIncrement($field['id']);
			$db->setQuery($query); 
	        $idai=$db->loadResult();
	        if (!is_numeric($idai)) $idai=0;	        
	        $tmp_value=$idai+1;
	        //var_dump($tmp_value);
       	} elseif ($field['type']=='12'){ //Today
       		if ($isNew){
       			$tmp_value=date($this->__jQueryUIDatePickerFormatToPhpFormat($_list->date_format_bbdd));
       		}
       	} 
       	// CSS para guardar
       	if (strpos('uppercase',$field['css'])!==false):
       		$tmp_value=strtoupper($tmp_value);
       	elseif (strpos('lowercase',$field['css'])!==false):
       		$tmp_value=strtolower($tmp_value);
       	elseif (strpos('camelcase',$field['css'])!==false):
       		$tmp_value=ucwords(strtolower($tmp_value));
       	endif;
       	if (!in_array($field['id'], $NOPermissionFields)){       		    	
	        $query = $this->_buildQueryInsertRecord($field['id'],$_idrecord,$tmp_value);
	        $db->setQuery($query);               
	        $result = $db->query();
       	}        	
       }
       
      if ($isNew){
      	$this->_addAccessInsert($_idrecord,$_id);	
      }else{
      	$this->_addAccessUpdate($_idrecord,$_id);
      }
    
    }
    
    
	function deleteData(){    
      $_idant = JRequest::getVar('id'); //idlisting
      $_id=$this->checkId($_idant); 
      $_idrecord = JRequest::getVar('idrecord'); //idrecord
      $db = JFactory::getDBO();
      //data array
      //get fields del listing      
      $query = $this->_buildQueryUserRecord($_idrecord,$_id);
      $db->setQuery($query);
      $uid= $db->loadResult();	  
      if($this->getACLPermission($_idant, 'delete',null,$uid)){
	      $this->_addAccessDelete($_idrecord,$_id);
	      $db = JFactory::getDBO();
	      $query = $this->_buildQueryDeleteRecords($_id,$_idrecord);
	      $db->setQuery($query);               
	      $db->query();
	      $query = $this->_buildDeleteRates($_idrecord,$_id);
	      $db->setQuery($query);               
	      $db->query();   
      }        
    } 

    function getACLPermission($id,$type,$header=null,$uid=0){
      // Check permission
      $session = JFactory::getSession();
      $user = JFactory::getUser();
	  $acl_global=$session->get('lm_'.$id);
	  $arrAcl=explode('#', $acl_global['acl']);
	  $viewonly=$acl_global['viewonly'];
	  $access_type=$acl_global['access_type'];
	  if($viewonly=='1') return false;	  
	  switch ($type){
	  	case 'delete': 
	  		if($access_type!='0' && $uid!=$user->id) return false;
	  		return in_array('delete', $arrAcl); 
	  		break;
	  	case 'add': 
	  		return in_array('add', $arrAcl); 
	  		break;
	  	case 'edit':
	  		if($access_type!='0' && $uid!=$user->id) return false;
      		if(in_array('edit', $arrAcl)) return true;
		  	if($header!=null){
		  		return in_array($header, $arrAcl);
		  	}
		  	break;
	  	default:
	  		break;
	  }
	  return false;
    }    
    
    
     public function getDataFieldsParam($id) {   
     	$id=$this->checkId($id);     
    $db = JFactory::getDBO();
    $query = $this->_buildQuerySelectFieldsOne2($id);
    $db->setQuery($query);
    return $db->loadAssocList();
  }
  
  public function getDataParam($id) {   
  	$id=$this->checkId($id);     
    $db = JFactory::getDBO();
    $query = $this->_buildQuery($id);
    $db->setQuery($query);
    return $db->loadAssoc();
  }
    
  

  public function getACLString() {      
    $aclString=""; 
    $db = JFactory::getDBO();
    
    
    $user = JFactory::getUser();
    $arrGroups=$user->getAuthorisedGroups();
        
    $id =JRequest::getVar('id');
    $id=$this->checkId($id);
    
    
    foreach($arrGroups as $idgrupo){
     $query = $this->_buildQuerySelectACLString($id,$idgrupo);
      $db->setQuery($query);
      $aclString.="#".$db->loadResult();
    
    }
    return $aclString;
  }
  private function __jQueryUIDatePickerFormatToPhpFormat($dateFormat) {
  	$chars = array(
  			// Day
  			'dd' => 'd', 'd' => 'j', 'DD' => 'l', 'D' => 'D',
  			// Month
  			'mm' => 'm', 'm' =>'n', 'MM' => 'F', 'M' => 'M',
  			// Year
  			'yy' => 'Y', 'y' => 'y',
  	);
  	return strtr((string)$dateFormat, $chars);
  }  

  
}


?>
