<?php
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
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
 
class ModListManagerHelper { 

  protected function _buildQuerySelectFieldsOne($id,$type=null){
  	if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
      $query = "select * from #__listmanager_field where idlisting=".$id;
      if ($type!=null) $query .= ' and type='.$type;
      $query.=" order by `order` asc";
      return $query;
  }
    
  protected function _buildQuerySelectViewFieldsOne($id,$idview){
      if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
      if (!is_numeric($idview)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));     
      $defaultcondition="ifnull(v.defaulttext,f.defaulttext)"; 
      if(!defined('DS')){
            define('DS',DIRECTORY_SEPARATOR);
      } 
      if(file_exists (dirname(__FILE__).DS.'customhelper.php')){       
        include(dirname(__FILE__).DS.'customhelper.php');     
      }
      $query = "select f.id,f.mandatory, f.idlisting,f.type, f.`decimal`,f.`order`,
                f.size,f.name,f.limit0,f.limit1,f.multivalue,f.sqltext,f.total, f.readmore, f.readmore_word_count,
                f.link_type, f.link_width, f.link_height, f.css, f.placeholder,f.bulk,f.link_url,f.link_id,
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
  

  protected function _buildQuery($id){
  if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
      $query = "select u.* from #__listmanager_listing u ";
      if ($id!=null) $query .= 'where u.id='.$id;        
      return $query;      
      
   }
   
   protected function _buildQueryRecords($id,$order=null,$dorder=null,$limit0=null, $limit1=null){
   if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
   
      $query = "SELECT v.id,v.idfield,v.value,v.idrecord,f.type,f.decimal,f.order,f.size,f.name,
      				f.visible,f.total, f.autofilter, f.defvalue, f.readmore, f.readmore_word_count, 
      				f.link_id, f.link_url, f.link_type, f.link_width, f.link_height, f.bulk
      			FROM #__listmanager_values v,#__listmanager_field f
      			where f.id=v.idfield and  idlisting=".$id;
      if ($order!=null) $query .= 'order by '.$order." ".$dorder;            
      
      if ($limit0!=null) $query .= 'limit '.$limit0.", ".$limit1;
      return $query;
  }
  
  protected function _buildQuerySelectACLString($id,$idGroup){
   if (!is_numeric($id)||!is_numeric($idGroup)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
   $query = "select acl from #__listmanager_acl where idlisting=".$id." and  idgroup=".$idGroup;
      return $query;
  
  }   
  
  protected function _buildQuerySelectAllFieldsLayout($id){
        $query = "select * from #__listmanager_field where idlisting=".$id;
        return $query; 
    }
  
  protected function _buildQuerySelectView($id){
        $query = "select idlisting from #__listmanager_view where id=".$id;        
        return $query;
    }
    
   protected function _buildQuerySelectViewParameters($id){
    	$query = "select * from #__listmanager_view where id=".$id;
    	return $query;
    }
    
  protected function _buildQueryFieldIdByName($name,$idlisting){
  	$query = "select id from #__listmanager_field where idlisting=".$idlisting." and name='".$name."'";        
    return $query;
  }
  protected function _buildSelectMultivalues($id){		
        $query = "select * from #__listmanager_field_multivalue where idfield=".$id." order by ord" ;
        return $query;
    }
  public function getDataFields($id) {
  	$isList=strrpos($id,'v_')===false;
  	$idlist=$this->checkId($id);       
    $db = JFactory::getDBO();
    $query = $this->_buildQuerySelectFieldsOne($idlist);
    if (!$isList) $query = $this->_buildQuerySelectViewFieldsOne($idlist,substr($id, 2));    
    $db->setQuery($query);
    //return $db->loadAssocList();
    return $db->loadObjectList();
  }
  
  public function getData($id) {
  	$oldid=$id;   
  	$id=$this->checkId($id);    
    $db = JFactory::getDBO();
    $query = $this->_buildQuery($id);
    $db->setQuery($query);
    $ret= $db->loadAssoc();    
    if (strrpos($oldid,'v_')!==false):    	
    	$ret=array_merge($ret,array_filter($this->checkViewParameters(substr($oldid, 2))));
    endif;    
    return $ret;
  }
  
  private function checkViewParameters($idview){
  	  $db = JFactory::getDBO();
      $query = $this->_buildQuerySelectViewParameters($idview);
      $db->setQuery($query);
      return $db->loadAssoc();
  }
  
  public function getFieldView($id) {
  	  $db = JFactory::getDBO();
      $query = $this->_buildQuerySelectView($id);
      $db->setQuery($query);
      return $db->loadResult();
    }
    
  public function checkId($id){
  	if (strrpos($id,'v_')===false)
    	return $id;
    else
    	return $this->getFieldView(substr($id, 2));
  }
  protected function getMultivalues($id){
      $db = JFactory::getDBO();
      $query = $this->_buildSelectMultivalues($id);
      $db->setQuery($query);
      return $db->loadObjectList();
    }
    
    protected function getMultivalueswrapper($field){
    	$multivalues = $this->getMultivalues($field->id);
    	/*$ret =array();
    	foreach($multivalues as $fila){
        	$ret[]=$fila;
    	}
    	return $ret;*/
    	return $multivalues;
    }
  public function getDataAllFieldsLayout($id) {
  	  $isList=strrpos($id,'v_')===false; 
  	  $idlist=$this->checkId($id);       
      $db = JFactory::getDBO();
      $query = $this->_buildQuerySelectAllFieldsLayout($idlist);
      if (!$isList) $query = $this->_buildQuerySelectViewFieldsOne($idlist,substr($id, 2));    
      $db->setQuery($query);
      return $db->loadObjectList('id');
   }
   
	public function getExecuteQuery($query) {       
      $db = JFactory::getDBO();
      $userdata=JFactory::getUser();      
      $query=str_replace('##userid##', $userdata->id, $query);
      $db->setQuery($query);
      return $db->loadRowList();
    }
  
   public function getDataRecords($id,$data,$order=null,$dorder=null,$limit0=null, $limit1=null) {
   	$id=$this->checkId($id);       
    $db = JFactory::getDBO();
    $query = $this->_buildQueryRecords($id,$order,$dorder,$limit0, $limit1);
    $db->setQuery($query);
    $arrRecords=$db->loadAssocList();                             
    
    foreach ($arrRecords as $rec) {                                
      $result[$rec['idrecord']]['id']=$rec['idrecord'];
      $result[$rec['idrecord']][$rec['idfield']]=$rec['value'];               
    } 
    
    return $result;
  }
  
  
   public function getACLString($id) {   
   	$id=$this->checkId($id);   
    $aclString=""; 
    $db = JFactory::getDBO();
    
    
    $user = JFactory::getUser();
    $arrGroups=$user->getAuthorisedGroups();
    
   	foreach($arrGroups as $idgrupo){
     $query = $this->_buildQuerySelectACLString($id,$idgrupo);
      $db->setQuery($query);
      $aclString.="#".$db->loadResult();
    
    }
    return $aclString;
  }
  
  public function getEditAcl4Field($field, $acl){
  	$permisos=explode("#",$acl);
  	/*var_dump($permisos);
  	echo in_array($field->id,$permisos).'#'.in_array('edit',$permisos).'<br>';*/
  	if (!in_array($field->id,$permisos) && !in_array('edit',$permisos)){  	
  		return false;
  	}
  	return true;
  }
  
  public function getFieldIdByName($name,$idlisting){
  	$db = JFactory::getDBO();
    $query = $this->_buildQueryFieldIdByName($name,$idlisting);
    $db->setQuery($query);
    return $db->loadResult();
  }
  
  public function createHidden($field){
  	 return '<input type="hidden" id="fld_'.$field->id.'" value="'.$field->defaulttext.'"  data-type="'.$field->type.'" name="fld_'.$field->id.'" />';
  }
  
  public function getFieldHTML($data,$seccionfield,$field,$seed,$listing,$acl,$bulk=false){
  		//var_dump($listing); 
  		$editor1 =JFactory::getEditor();   	
    	$pathImagenCalendar=JURI::base().'modules/mod_listmanager/assets/img/calendar.png';
    	$retorno="";
  		  	$arr_suffix=$seccionfield?'[]':'';
		    $arrValidator=array();    
		    $class='';
		    $isEditable='';
		    $isEditFields=true;
		    if(!$bulk):
			    $isEditFields=$this->getEditAcl4Field($field,$acl);									
			endif;
			if (!$isEditFields) $isEditable='disabled="disabled"';
			//$retorno.='#'.$isEditFields.'#';
		    // Custom Validators
		    
		    //$required=($field->mandatory==1)?'required':'';
		    $required='';
		    
		    if ($field->mandatory==1) $arrValidator[]='required';    
		    if ($field->mandatory==1||strlen($field->limit0) || strlen($field->limit1) || 
		    	$field->type==0 || $field->type==8 || strlen($field->decimal)) {
      			$arrValidator[]='validate-elem'.$field->id;
    		}  
    		if (count($arrValidator)>0) $class='class="'.implode(' ',$arrValidator).' '.$field->css;
		    else $class.=' class="'.$field->css;
		    $multiple=''; // only for select multiple
		    switch ($field->type) {
		      case '0' : 
		      case '8' :        
		        $class.=' lm_number form-controllm"';
		        $retorno.='<input type="text" '.$required.' id="fld_'.$field->id.'" name="fld_'.$field->id.'" '.$class.' '.$isEditable.'  placeholder="'.$field->placeholder.'" value="'.$field->defaulttext.'"/>';
		        break;
		       
		      case '12':
		      	$randCal=rand(0, 9999999);	
		      	$class.=' form-controllm"';
		      	//$retorno.='<input type="text" id="fld_'.$field->id.'" lmtype="today" name="fld_'.$field->id.$arr_suffix.'" disabled '.$class.' value="'.$field->defaulttext.'"/>';
		      	$retorno.='<input type="text" id="fld_'.$field->id.'_'.$randCal.'" lmtype="today" name="fld_'.$field->id.$arr_suffix.'" disabled '.$class.' value="'.$field->defaulttext.'"/>';
		      	if($isEditFields):
		      			$retorno.='<script>
			            	LMJQ(document).ready(function() {
			            		setCalendarLM("fld_'.$field->id.'_'.$randCal.'", "fld_'.$field->id.'_img_'.$randCal.'","'.$seed.'");
			            	});
			            </script>';
		      	endif;
		      	break;	      	
		      case '1' : 
		       $class.=' form-controllm input-append lmdate"';		       
		       $randCal=rand(0, 9999999);
		      	//$retorno.='<div '.$class.'>
		      	$retorno.='<div>
					    <input '.$class.' '.$required.' type="text" 
					    	name="fld_'.$field->id.'" id="fld_'.$field->id.'_'.$randCal.'"  placeholder="'.$field->placeholder.'" value="'.$field->defaulttext.'" />
					    <!--<span class="add-on" id="fld_'.$field->id.'_img_'.$randCal.'"><i class="icon-calendar"></i></span>-->
					    </div>';
		      	if($isEditFields):
					    $retorno.='<script>
			            	LMJQ(document).ready(function() {
			            		setCalendarLM("fld_'.$field->id.'_'.$randCal.'", "fld_'.$field->id.'_img_'.$randCal.'","'.$seed.'");
			            	});
			            </script>';
		      	endif;
		        break;
		      case '16' :
		      	$multiple=' multiple="multiple" ';
		      case '2' :		      	
		      	if ($required!='' && !$multiple):
		      		$class.=' lm_option form-controllm lm_select_required"';
		      	else:
		      		$class.=' lm_option form-controllm"';
		      	endif;
		    	$multivalues=$this->getMultivalueswrapper($field);		    	
		        if (count($multivalues)>0 || ($field->sqltext!=null && trim($field->sqltext)!='')){
		          $retorno.='<select '.$multiple.' '.$required.' name="fld_'.$field->id.$arr_suffix.'" '.$class.' id="fld_'.$field->id.'"  '.$isEditable.'>';
		          // Poner value por defecto y descomentar la siguiente linea
		          //$retorno.='<option value="">'.JText::_('LM_OPTION_BLANK').'</option>';		          
		          if ($field->sqltext!=null && trim($field->sqltext)!=''){
		          	$more_fields=$this->getExecuteQuery($field->sqltext);		          	
		          	foreach ($more_fields as $sqlfields){
		          		$selected=$field->defaulttext==$sqlfields[0]?'selected="selected"':'';
		          		$retorno.='<option value="'.$sqlfields[0].'"  mv_option="'.$sqlfields[0].'" '.$selected.'>'.JText::_($sqlfields[1]).'</option>';
		          	}
		          }
		          foreach ($multivalues as $multiv){
		          	$selected=$field->defaulttext==$multiv->value?'selected="selected"':'';
		          	$retorno.='<option value="'.$multiv->id.'"  mv_option="'.$multiv->value.'"  '.$selected.'>'.JText::_($multiv->name).'</option>';
		          }		          
		          $retorno.='</select>';		          
		        }		      	
		        break;
		      case '3' :
		      	$class.=' lm_option form-controllm"';
		        $retorno.='<input type="checkbox" '.$required.' id="check_fld_'.$field->id.'" name="check_fld_'.$field->id.$arr_suffix.'" '.$class.' 
		        			value="'.JText::_("LM_YES_VALUE").'"  '.$isEditable.' onclick="changeCheck(this);"/>';
		        $retorno.='<input type="hidden" id="fld_'.$field->id.'" name="fld_'.$field->id.$arr_suffix.'" value="'.JText::_("LM_NO_VALUE").'"  '.$isEditable.'/>';
		        break;
		      case '4' :		      	
		      case '17':
		      case '18':
		      	$class.=' lm_text form-controllm"';
		        $retorno.='<input type="text" id="fld_'.$field->id.'" '.$required.' name="fld_'.$field->id.$arr_suffix.'" '.$class.' value="'.$field->defaulttext.'"  '.$isEditable.'  placeholder="'.$field->placeholder.'"/>';
		        break;
		      case '6' :
		      	$class.=' form-controllm"';
		        $retorno.='<input type="hidden" id="fld_'.$field->id.'" value="'.JFactory::getUser()->id.'" name="fld_'.$field->id.'"/>';
        		break;
		      case '7' :
		        $class.=' lm_text form-controllm"';
        		$retorno.='<textarea id="fld_'.$field->id.'" '.$required.' name="fld_'.$field->id.'" '.$class.' '.$isEditable.' placeholder="'.$field->placeholder.'">'.$field->defaulttext.'</textarea>';
        		break;   
		      case '9' :
		      	if ($listing['editor']==0):
		      		$retorno.='<div style="width:100%"><textarea id="fld_'.$field->id.'" name="fld_'.$field->id.'" class="cleditor"></textarea></div>';
		      		$retorno.='<script>LMJQ(document).ready(function() {LMJQ("#fld_'.$field->id.'").cleditor();});</script>';
		      	else:
		      		$retorno.=$editor1->display('fld_'.$field->id, '', '100%', '400', '60', '20', false);
		      		$retorno.='<script>arrEditores'.$seed.'+="'.str_replace('\r', '', str_replace('\n', '', str_replace('"','\'',$editor1->save('fld_'.$field->id)))).'";</script>';
		      		//$retorno.='<script>arrEditoresSetters'.$seed.'+="'.str_replace('\r', '', str_replace('\n', '', str_replace('"','\'',$editor1->initialise()))).'";</script>';
		      	endif;
		        break;
		      case '10' :
		      	$class.=' radio"';
		    	$multivalues=$this->getMultivalueswrapper($field);
		        if (count($multivalues)>0 || ($field->sqltext!=null && trim($field->sqltext)!='')){
		        	$retorno.='<div class="radgroup">';
		          if ($field->sqltext!=null && trim($field->sqltext)!=''){
		          	$more_fields=$this->getExecuteQuery($field->sqltext);
		          	$counter_multivalue=0;
		          	foreach ($more_fields as $sqlfields){
		          		$checked=$field->defaulttext==$sqlfields[0]?'checked="checked"':'';
		          		$counter_multivalue++;
		          		$retorno.='<label '.$class.' >';
		          		$retorno.='<input type="radio" id="fld_'.$field->id.'_'.$counter_multivalue.'" rad_name="fld_'.$field->id.$arr_suffix.'" name="rfld_'.$field->id.$arr_suffix.'" value="'.$sqlfields[0].'" mv_radio="'.$sqlfields[0].'" '.$checked.'/>';
		          		$retorno.=JText::_($sqlfields[1]);
		          		$retorno.='</label>';
		          	}
		          } 
		          /*$i=0;
		          foreach ($multivalues as $multiv){*/
		          for ($i=0;$i<count($multivalues);$i++){
		          	$multiv=$multivalues[$i];
		          	$checked=$field->defaulttext==$multiv->value?'checked="checked"':'';
		          	//var_dump($multiv);
		            $retorno.='<label '.$class.' >';
		          	$retorno.='<input type="radio" id="fld_'.$field->id.'_'.$i.'"  
		          				rad_name="fld_'.$field->id.$arr_suffix.'" name="rfld_'.$field->id.$arr_suffix.'" value="'.$multiv->id.'" mv_radio="'.$multiv->value.'" '.$checked.'/>';
		          	$retorno.=JText::_($multiv->name);
		          	$retorno.='</label>';		          	
			        //$i++;
		          }		          
		        }		        
		        $retorno.='<input type="hidden" name="fld_'.$field->id.$arr_suffix.'"/>';
		        $retorno.='</div>';
		        //echo var_dump($multivalues);
		      	break;       
		      case '11':
		    	$class.=' checkbox"';
		      	$multivalues=$this->getMultivalueswrapper($field);
		        if (count($multivalues)>0 || ($field->sqltext!=null && trim($field->sqltext)!='')){
		          if ($field->sqltext!=null && trim($field->sqltext)!=''){
		          	$more_fields=$this->getExecuteQuery($field->sqltext);
		          	$counter_multivalue=0;
		          	foreach ($more_fields as $sqlfields){
		          		$counter_multivalue++;
		          		$checked=$field->defaulttext==$sqlfields[0]?'checked="checked"':'';
		          		$retorno.='<div class="checkbox"><label '.$class.' >';
		          		$retorno.='<input type="checkbox" id="fld_'.$field->id.'_'.$counter_multivalue.'" 
		          					name="fld_'.$field->id.'[]" value="'.$sqlfields[0].'" mv_check="'.$sqlfields[1].'"  '.$checked.'/>';
		          		$retorno.=JText::_($sqlfields[1]);
		          		$retorno.='</label></div>';
		          	}
		          } 
		          $i=0;
		          foreach ($multivalues as $multiv){
		          	$checked=$field->defaulttext==$multiv->value?'checked="checked"':'';
		            $retorno.='<div class="checkbox"><label '.$class.' >';
		          	$retorno.='<input type="checkbox" id="fld_'.$field->id.'_'.$i.'"  name="fld_'.$field->id.'[]" value="'.$multiv->id.'"  
		          				mv_check="'.$multiv->value.'" '.$checked.'/>';
		          	$retorno.=JText::_($multiv->name);
		          	$retorno.='</label></div>';
			        $i++;
		          }
		        }
		        break;
		      case '14': 
		         $class.=' lm_number form-controllm"';
		        $retorno.='<input type="text" id="fld_'.$field->id.'" name="fld_'.$field->id.'" '.$class.' style="width:'.$field->size.'px" '.$isEditable.' value="'.$field->defaulttext.'"/>';
		        $limit0=$field->limit0!=null?$field->limit0:0;
		        $limit1=$field->limit1!=null?$field->limit1:100;		        
		        if($isEditFields){
		        	$retorno.='<div id="fld_'.$field->id.'_slider" class="lm_slider"></div>';
			        $retorno.='<script>
			        	LMJQ(document).ready(function() {
			        		// Mootools-jQuery conflict
			        		LMJQ("#fld_'.$field->id.'_slider")[0].slide = null;
				        	LMJQ("#fld_'.$field->id.'_slider").slider({
				        		min:'.$limit0.',
				        		max:'.$limit1.',
				        		change: function(event, ui) {
				        			LMJQ("#fld_'.$field->id.'").val(ui.value);
			    				},
			    				slide: function(event, ui) {
				        			LMJQ("#fld_'.$field->id.'").val(ui.value);
			    				}
			    			});		    			
						});
					</script>';
		        }
		        break; 
		     case '15':		     	
		     	$retorno.='<div id="fld_'.$field->id.'_rate" class="rateit rateitForm" data-idlisting="'.$listing['id'].'" data-idrecord="" 
			  		data-idfield="'.$field->id.'" data-rateit-value="" data-rateit-ispreset="true" data-rateit-readonly="true"></div>
			  		<input type="hidden" id="fld_'.$field->id.'" isRate="true"  value="" />';
			  	break;		                  
		    }
		    $validations=array();
		    //Validacion de limit
		    if (strlen($field->limit0) || strlen($field->limit1) || strlen($field->decimal) || $field->type==0) {
		    	$is_date='false';  
		        if($field->type==1) $is_date='true';
		        if($field->type==0){ 
		        	$validations[]="LMJQ('#fld_".$field->id."').rules('add', {
		    		  'lmnumber':['".$listing['thousand']."', '".$field->decimal."','".$listing['decimal']."']
		    		});";
		        }	
	  			if (strlen($field->limit0) || strlen($field->limit1)) {
	    		  if ($field->limit0!=''&&$field->limit1!='') { 
	    		  	$validations[]="LMJQ('#fld_".$field->id."').rules('add', {
		    		  '2limits':['".$field->limit0."', '".$field->limit1."', ".$is_date.",'".$listing['date_format']."', 0,'".$listing['thousand']."']
		    		});";		    	
		    	} elseif ($field->limit0!='') {
		    		$validations[]=" LMJQ('#fld_".$field->id."').rules('add', {
		    		  '1limit':['".$field->limit0."', null, ".$is_date.",'".$listing['date_format']."', 1,'".$listing['thousand']."']
		    		});";
		    	} elseif ($field->limit1!='') {
		    		$validations[]="LMJQ('#fld_".$field->id."').rules('add', {
		    		  'limit':[null, '".$field->limit1."', ".$is_date.",'".$listing['date_format']."', 2,'".$listing['thousand'];"']
		    		});";	              
		    	}
	  			} 
		    }
		    if(count($validations)>0): ?>
		    	<script>LMJQ(document).ready(function(){
			    	<?php echo implode($validations);?>
		    	});</script>	
		    <?php endif; 
		   
		  return $retorno;
    }
 	private function _buildQueryDetailList($id){
   		$db = JFactory::getDBO();
   		return  "select detail from #__listmanager_listing where id=".$db->quote($id);
   }
	private function _buildQueryDetailView($id){
   		$db = JFactory::getDBO();
   		return  "select detail from #__listmanager_view where id=".$db->quote($id);
   }
    public function getDetail($id){
		$db = JFactory::getDBO();
		$isList=strrpos($id,'v_')===false;
		$idlist=$this->checkId($id);
		$query = $this->_buildQueryDetailList($idlist);
		$db->setQuery($query);
		$result=$db->loadResult();
		if (!$isList):			
    		$query = $this->_buildQueryDetailView(substr($id, 2));
    		$db->setQuery($query);
			$resultview=$db->loadResult();
			if($resultview!=null && $resultview!=''){$result=$resultview;}
    	endif;    	
		return $result; 
    }
   
} 
?>
