<?php defined('_JEXEC') or die('Restricted access');
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
JHTML::_('behavior.framework', true);

JHTML::_('behavior.formvalidation');
$document =JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/com_listmanager/assets/css/jquery-ui-1.10.0.custom.css','text/css','screen');
$document->addStyleSheet(JURI::base().'components/com_listmanager/assets/css/rateit.css','text/css','screen');
$scriptbase=JURI::base().'components/com_listmanager/assets/js/';
$css=$this->__getCSS();          
foreach ($css as $style){                                                        
	$document->addStyleSheet($style);
}
$js=$this->__getJS();
foreach ($js as $jscript){                                                        
	?><script src="<?php echo $jscript;?>"></script><?php 
}
	function _buildSelectMultivalues($id){		
        $query = "select * from #__listmanager_field_multivalue where idfield=".$id." order by ord" ;
        return $query;
    }
	function getMultivalues($id){
      $db = JFactory::getDBO();
      $query = _buildSelectMultivalues($id);
      $db->setQuery($query);
      return $db->loadObjectList();
    }
    
    function getMultivalueswrapper($field){
    	$multivalues = getMultivalues($field['idfield']);    	
    	return $multivalues;
    }
    function getExecuteQuery($query) {       
      $db = JFactory::getDBO();
      //$query=str_replace('##userid##', 'true', $query);
      $user=JFactory::getUser();
      $query=str_replace('##userid##', $user->id, $query);
      $db->setQuery($query);
      return $db->loadRowList();      	
    }
    

?>
<?php //echo $this->__getHelp('EDIT_DATA');?>
<script type="text/javascript" src="<?php echo $scriptbase.'jquery-1.9.0.js';?>"></script>
<script type="text/javascript" src="<?php echo $scriptbase.'jquery-ui-1.10.0.custom.min.js';?>"></script>
<script type="text/javascript" src="<?php echo $scriptbase.'jquery.rateit.min.js';?>"></script>
<script type="text/javascript" src="<?php echo $scriptbase.'jquery.ddslick.js';?>"></script>
<script>jQuery.noConflict();</script>
<script>
var arrEditores="";

window.addEvent('load', function(){
	document.formvalidator.setHandler('alpha',
			function (value) {
				regex=/^[a-zA-Z]*$/;
				return regex.test(value);
			}
		);
	document.formvalidator.setHandler('alphanum',
			function (value) {
				regex=/^[a-zA-Z0-9]*$/;
				return regex.test(value);
			}
		);
	document.formvalidator.setHandler('url',
			function (value) {
				regex=/(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/;
				return regex.test(value);
			}
		);	
	jQuery('.radgroup').find('input[type=radio]').each(function(index,elem){
		jQuery(elem).click(function(){
			jQuery(this).closest('.radgroup').find('input[type=hidden][name='+jQuery(this).attr('rad_name')+']').val(jQuery(this).val());
		});
	});
	jQuery('.rateit').bind('rated reset', function (e) {		
	    var ri = jQuery(this);
	    var type = 0;
	    if(e.type == 'reset') type=1;
	    //if the use pressed reset, it will get value: 0 (to be compatible with the HTML range control), we could check if e.type == 'reset', and then set the value to  null .
	    var value = ri.rateit('value');
	    var idlisting = ri.data('idlisting'); 
	    var idrecord = ri.data('idrecord');
	    var idfield = ri.data('idfield');
	    //maybe we want to disable voting?
	    //ri.rateit('readonly', true);	    
	    jQuery.ajax({
	        url: 'index.php', //your server side script
	        data: {
	        	"option":"com_listmanager",
	        	"task":"rate",
	        	"controller":"rate",
	        	"format":"raw",
	        	"idlisting":idlisting,
	        	"idrecord":idrecord,
	        	"idfield":idfield,
	        	"rate":value,
	        	"type":type
	        }, //our data
	        type: 'POST',
	        success: function (data) {
	            //alert('ok: '+data);
	            ri.rateit('value',data);
	        },
	        error: function (jxhr, msg, err) {
	            //alert('error: '+msg);
	            alert('<?php echo JText::_('VOTE_ERROR');?>')
	        }
	    });
	});
});
Joomla.submitbutton = function(task){
        if (task == ''){return false;}
        else{
                var isValid=true;
                var action = task.split('.');
                if (action[1] != 'cancel' && action[1] != 'close'){
                        var forms = $$('form.form-validate');
                        for (var i=0;i<forms.length;i++){
                                if (!document.formvalidator.isValid(forms[i])){
                                        isValid = false;
                                        break;
                                }
                        }
                } 
                if (isValid){
                        Joomla.submitform(task);
                        return true;
                }else{
                        alert(Joomla.JText._('ERROR_UNACCEPTABLE','Some values are unacceptable'));
                        return false;
                }
        }
}
function setIMGCombo(){
	jQuery('.imgcombo').ddslick({
	    selectText: "",
	    width:130,
	    background: "#fff",
	    onSelected: function (data) {
	    	var hddn=jQuery(data.selectedItem).closest('#adminForm').find('input').filter('[lm_fid]');
	    	jQuery(hddn).val(data.selectedData.value);	    	
	    }
	});
}
function setCalendarLM(inputf, buttonfield){
	//Calendar.setup({'inputField':inputf,'ifFormat':getDateFormat(),'button':buttonfield});	
	jQuery('#'+inputf).datepicker({ 
		dateFormat: '<?php echo $this->item['date_format_bbdd'];?>', 
		onSelect: function() { jQuery(".ui-datepicker a").removeAttr("href");}
	});
}
</script>
<div class="cb_table_wrapper">
<fieldset>
		<legend><?php echo JText::_('LISTINGSDATARECORD')?></legend>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">
<?php
JHTML::_('behavior.calendar');
$pathImagenCalendar=JURI::base().'../modules/mod_listmanager/assets/img/calendar.png';
$counter_multivalue=0;

$editor1 =JFactory::getEditor();

  $retorno='';
  foreach ($this->record as $field) {   
    $mand=''; 
    if (isset($field['mandatory'])&&$field['mandatory']==1){ $mand='<span class="lm_asterisk">*</span>';}
            
    $retorno.='<div class="control-group"><label class="control-label">'.$mand.$field['name'].'</label>';
    $retorno.='<div class="controls">';
    //$retorno.="<tr><td class='lm_nameform'>".$mand.$field['name']."</td><td colspan='2' class='lm_value'>";
    
    $arrValidator=array();    
    $class='';
    //if (isset($field['validate'])) $arrValidator[]='validate-'.$field['validate'];
    
    if (isset($field['mandatory'])&&$field['mandatory']==1){ $arrValidator[]='required';}    
    if ((isset($field['mandatory'])&&$field['mandatory']==1)||
    	(isset($field['limit0'])&&strlen($field['limit0'])) || 
    	(isset($field['limit1'])&&strlen($field['limit1'])) || 
    	(isset($field['type'])&&$field['type']==0) || 
    	(isset($field['decimal'])&&strlen($field['decimal']))) {
      $arrValidator[]='validate-elem'.$field['idfield'];
    }   
    //$arrValidator[]='inputbox';    
    if (count($arrValidator)>0) $class='class="'.implode(' ',$arrValidator).' ';
    $class.=' '.$field['css'];
    $multiple='';// Only for select multiple
    switch ($field['type']) {
      case '0' :  
      case '8' :  
      	$class.=' lm_number"';       
        $retorno.='<input type="text" id="fld_'.$field['idfield'].'" name="fld_'.$field['idfield'].'" '.$class.' size="'.$field['size'].'" value="'.$field['value'].'" placeholder="'.$field['placeholder'].'"/>';
        break;
      case '1' :  case '12';
      $class.='lmdate"';
        $val_tmp=$field['value'];
        $randCal=rand(0, 9999999);
        //if (strlen($val_tmp)<=0 && $field['type']=='12' ) $val_tmp=date(str_replace('%', '',  $this->item['date_format']));     
        $retorno.='<input type="text" name="fld_'.$field['idfield'].'" id="fld_'.$field['idfield'].'_'.$randCal.'" value="'.$val_tmp.'" '.$class.' size="10"/>
          <span id="span_fld_'.$field['idfield'].'">          
            <script>              
              jQuery(document).ready(function() {';
        		$retorno.='setCalendarLM("fld_'.$field['idfield'].'_'.$randCal.'", "fld_'.$field['idfield'].'_img_'.$randCal.'");';
        		if (strlen($val_tmp)<=0 && $field['type']=='12' ):
              		$retorno.='jQuery("#fld_'.$field['idfield'].'_'.$randCal.'").datepicker("setDate", new Date());';
              	endif;              		
              	$retorno.='
    			});           
            </script>              
          </span>';
        break;
      case '16' :
      	$multiple='multiple="multiple"';
      	//$values_arr=explode("#", $field['value']);
      case '2' :      	
    	$class.=' lm_option ';
      	$valuetmp=$field['value'];
      	if ($multiple!=''){ $valuetmp=explode("#", $field['value']);}
      	$multivalues=getMultivalueswrapper($field);
      	$is_image='"';
      	foreach ($multivalues as $multiv){
          	// Image         	
          	if (!strncmp($multiv->name, '<img', strlen('<img'))):
          		$is_image=' imgcombo"';
          	endif;
        }	
        $class.=$is_image;	
        if (count($multivalues)>0 || ($field['sqltext']!=null && trim($field['sqltext'])!='')){
        	$name='name="fld_'.$field['idfield'].''.(strlen($multiple)>0?"[]":"").'"';
        	if ($is_image!='"') $name='name=""';
        	
          	$retorno.='<select '.$multiple.' '.$name.' '.$class.' id="fld_'.$field['idfield'].'">';
          //TODO Poner value por defecto y descomentar la siguiente linea
          //$retorno.='<option value="">'.JText::_('LM_OPTION_BLANK').'</option>';
          if ($field['sqltext']!=null && trim($field['sqltext'])!=''){
          	$more_fields=getExecuteQuery($field['sqltext']);
          	foreach ($more_fields as $sqlfields){
          		$isSelected=false;
          		if ($multiple!='') $isSelected=in_array($sqlfields[0], $valuetmp);
          		else $isSelected= ($valuetmp==$sqlfields[0]);         		
          		$retorno.='<option value="'.$sqlfields[0].'"  mv_option="'.$sqlfields[0].'" '.($isSelected?'selected="selected"':'').'>'.JText::_($sqlfields[1]).'</option>';
          	}
          }
          foreach ($multivalues as $multiv){
          	$isSelected=false;
          	if ($multiple!='') $isSelected=in_array($multiv->id, $valuetmp);
          	else $isSelected= ($valuetmp==$multiv->id);
          	// Image         	
          	if (!strncmp($multiv->name, '<img', strlen('<img'))):
          		$pattern = "/src='([^']*)'/";
				preg_match($pattern, $multiv->name,$matches);
				$retorno.='<option value="'.$multiv->id.'" data-imagesrc="'.$matches[1].'"  mv_option="'.$multiv->value.'" '.($isSelected?'selected="selected"':'').'></option>';				          	
          	else:
          		$retorno.='<option value="'.$multiv->id.'"  mv_option="'.$multiv->value.'" '.($isSelected?'selected="selected"':'').'>'.JText::_($multiv->name).'</option>';
          	endif;          	         	
          	
          }		          
          $retorno.='</select>';
          if($is_image!='"'):
          	$retorno.='<input type="hidden" name="fld_'.$field['idfield'].'" lm_fid="'.$field['idfield'].'" seed="" id="fld_'.$field['idfield'].'">';
          endif;
        }
        break;
      case '3' :
        $class.=' lm_option"';
		$retorno.='<input type="checkbox" id="fld_'.$field['idfield'].'" name="fld_'.$field['idfield'].'" '.$class.' value="'.JText::_("LM_YES_VALUE").'" '.($field['value']==JText::_("LM_YES_VALUE")?'checked="checked"':'').'/>';
		break;
	  case '17':
	  case '18':
      case '4' :
      	$class.=' lm_text"';
        $retorno.='<input type="text" id="fld_'.$field['idfield'].'" name="fld_'.$field['idfield'].'" '.$class.' size="'.$field['size'].'" value="'.$field['value'].'"/>';
        break;  
       case '7' :
      $class.=' lm_text"';
        $retorno.='<textarea id="fld_'.$field['idfield'].'" name="fld_'.$field['idfield'].'" '.$class.' width="'.$field['size'].'">'.$field['value'].'</textarea>';
        break;   
       case '6' :
           /*
            $retorno.='<input type="text" id="fld_'.$field['idfield'].'" value="'.$field['value'].'" name="fld_'.$field['idfield'].'" '.$class.' size="'.$field['size'].'"/>';*/
            
            
            $retorno.='<select id="fld_'.$field['idfield'].'" name="fld_'.$field['idfield'].'" '.$class.'><option value="" ></option>';
            
            foreach ($this->users as $usr){
            $retorno.='<option value="'.$usr['id'].'"';
              if($usr['id']==$field['value'])  $retorno.=' selected ';
            $retorno.='>'.$usr['name'].'</option>';
            }
            $retorno.='</select>';
            break;
        
       case '9' :
       		$retorno.=$editor1->display('fld_'.$field['idfield'], $field['value'], '100%', '400', '60', '20');
              //$retorno.='<script>arrEditores+="'.str_replace('"','\'',$editor1->save('fld_'.$field['id'])).'";</script>';
              //$arreditores['fld_'.$field['id']]='fld_'.$field['id'];
              break;
       
      case '10' :
      	$class.=' radio"';
      	$retorno.='<div class="radgroup edit-multi-wrapper">';
    	$multivalues=getMultivalueswrapper($field);
        if (count($multivalues)>0 || ($field['sqltext']!=null && trim($field['sqltext'])!='')){
          if ($field['sqltext']!=null && trim($field['sqltext'])!=''){
          	$more_fields=getExecuteQuery($field['sqltext']);
          	$counter_multivalue=0;
          	foreach ($more_fields as $sqlfields){
          		$counter_multivalue++;
          		$retorno.='<label '.$class.' >';
          		$retorno.='<input type="radio" id="fld_'.$field['idfield'].'_'.$counter_multivalue.'" 
          					rad_name="fld_'.$field['idfield'].'" name="rfld_'.$field['idfield'].'" 
          					value="'.$sqlfields->mvkey.'" mv_radio="'.$sqlfields->value.'" 
          					'.($field['value']==$sqlfields->mvkey?'checked="checked"':'').'/>';
          		$retorno.=JText::_($sqlfields->mvvalue);
          		$retorno.='</label>';
          	}
          } 
          /*$i=0;
          foreach ($multivalues as $multiv){*/
          for ($i=0;$i<count($multivalues);$i++){
          	$multiv=$multivalues[$i];
          	//var_dump($multiv);
            $retorno.='<label '.$class.' >';
          	$retorno.='<input type="radio" id="fld_'.$field['idfield'].'_'.$i.'"  
          				rad_name="fld_'.$field['idfield'].'" name="rfld_'.$field['idfield'].'" 
          				value="'.$multiv->id.'" mv_radio="'.$multiv->value.'" 
          				'.($field['value']==$multiv->id?'checked="checked"':'').'/>';
          	$retorno.=JText::_($multiv->name);
          	$retorno.='</label>';		          	
	        //$i++;
          }		          
        }		        
        $retorno.='<input type="hidden" name="fld_'.$field['idfield'].'" value="'.$field['value'].'"/>';
        $retorno.='</div>';
		break;      
        
	  case '11';
    	$class.=' checkbox"';
    	$valuetmp=explode("#", $field['value']);
    	$multivalues=getMultivalueswrapper($field);
    	$retorno.='<div class="edit-multi-wrapper">';
        if (count($multivalues)>0 || ($field['sqltext']!=null && trim($field['sqltext'])!='')){
          if ($field['sqltext']!=null && trim($field['sqltext'])!=''){
          	$more_fields=getExecuteQuery($field['sqltext']);
          	$counter_multivalue=0;
          	foreach ($more_fields as $sqlfields){
          		$isSelected=false;
          		$isSelected=in_array($sqlfields->mvkey, $valuetmp);  
          		$counter_multivalue++;
          		$retorno.='<label '.$class.' >';
          		$retorno.='<input type="checkbox" '.($isSelected?'checked="checked"':'').' id="fld_'.$field['idfield'].'_'.$counter_multivalue.'" name="fld_'.$field['idfield'].'[]" value="'.$sqlfields->mvkey.'" mv_check="'.$sqlfields->value.'"/>';
          		$retorno.=JText::_($sqlfields->mvvalue);
          		$retorno.='</label>';
          	}
          } 
          $i=0;
          foreach ($multivalues as $multiv){
          	$isSelected=false;
          	$isSelected=in_array($multiv->id, $valuetmp);
            $retorno.='<label '.$class.' >';
          	$retorno.='<input type="checkbox" '.($isSelected?'checked="checked"':'').' id="fld_'.$field['idfield'].'_'.$i.'"  name="fld_'.$field['idfield'].'[]" value="'.$multiv->id.'"  mv_check="'.$multiv->value.'"/>';
          	$retorno.=JText::_($multiv->name);
          	$retorno.='</label>';
	        $i++;
          }		          
        }
        $retorno.='</div>';
      	break;
      	
      case '14':
      		$class.=' lm_number"';
      		$limit0=$field['limit0']!=null?$field['limit0']:0;
		    $limit1=$field['limit1']!=null?$field['limit1']:100;
		        $retorno.='<div id="fld_'.$field['idfield'].'_slider" style="width:350px"></div>
		        	<input type="text" id="fld_'.$field['idfield'].'" name="fld_'.$field['idfield'].'" '.$class.' value="'.$field['value'].'" size="'.$field['size'].'" />';
		        $retorno.='<script>
		        	jQuery(document).ready(function() {
		        		// Mootools-jQuery conflict
		        		jQuery("#fld_'.$field['idfield'].'_slider")[0].slide = null;
			        	jQuery("#fld_'.$field['idfield'].'_slider").slider({
			        		value:"'.$field['value'].'",
			        		min:'.$limit0.',
			        		max:'.$limit1.',
			        		change: function(event, ui) {
			        			jQuery("#fld_'.$field['idfield'].'").val(ui.value);
		    				}
		    			});			
					});
				</script>';
      	break; 

	  case '15':
	  	$retorno.='<div class="rateit" data-idlisting="'.JRequest::getVar( 'idlisting').'" data-idrecord="'.JRequest::getVar( 'idrecord').'" 
	  		data-idfield="'.$field['idfield'].'" data-rateit-value="'.$field['ratevalue'].'" data-rateit-ispreset="true"></div>
	  		<input type="text" class="span2" name="fld_'.$field['idfield'].'" value=""/>
	  		<input type="hidden" id="fld_'.$field['id'].'" value="" />';
	  	break;
	  case '19':
	  	$retorno.='<input type="text" disabled="disabled" value="'.$field['value'].'"/><input type="hidden" id="fld_'.$field['id'].'" name="fld_'.$field['idfield'].'" value="'.$field['value'].'" />';
	  	break;
              
    }    
    $retorno.='</div></div>';        
  } 
  echo $retorno;
?>
 
<input type="hidden" name="option" value="com_listmanager" />
<input type="hidden" name="task" value="saverecord" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="listing" />
<input type="hidden" name="countermv" value="<?php echo $counter_multivalue;?>" />
<input type="hidden" name="idlisting" value="<?php echo JRequest::getVar( 'idlisting')?>"/>
<input type="hidden" name="idrecord" value="<?php echo JRequest::getVar( 'idrecord')?>"/>
</form>
</fieldset>
</div>
