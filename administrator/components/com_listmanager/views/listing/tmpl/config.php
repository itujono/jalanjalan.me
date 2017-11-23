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
JHTML::_('behavior.formvalidation');
JHTML::_('behavior.calendar');
JHTML::_('behavior.framework', true);
$document =JFactory::getDocument(); 
$css=$this->__getCSS();                                                                          
foreach ($css as $style){                                                        
	$document->addStyleSheet($style);
}
$js=$this->__getJS();
$js [] = JURI::base () . 'components/com_listmanager/assets/js/jquery.markitup.js';
$js [] = JURI::base () . 'components/com_listmanager/assets/js/markitup/sets/html/set.js';
foreach ($js as $jscript){                                                        
	?><script src="<?php echo $jscript;?>"></script><?php 
}
$arrElements=array();
$arrElements[]='LM_MODAL_FORM';
$arrElements[]='LM_DATE_FORMAT';
$arrElements[]='LM_NUMBER_FORMAT';
$arrElements[]='LM_SAVEHISTORIC';
$arrElements[]='LM_DELETEHISTORIC';
$arrElements[]='LM_RATEMETHOD';
$arrElements[]='LM_PDFORIENTATION';
$arrElements[]='LM_EXPORT_PREHTML';
$arrElements[]='LM_EXPORT_POSTHTML';
$arrElements[]='DEFAULT_VIEW_ORDER';
$arrElements[]='SHOW_TYPE';
$arrElements[]='NUMBER_CARDS';
?>
<?php echo $this->__getHelp('CONFIG',$arrElements);?>
<link rel="stylesheet" type="text/css" href="<?php echo JURI::base ();?>components/com_listmanager/assets/js/markitup/skins/markitup/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo JURI::base ();?>components/com_listmanager/assets/js/markitup/sets/html/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo JURI::base ();?>components/com_listmanager/assets/js/markitup/templates/preview.css" />
<script>
<!--
//window.addEvent('load',function(){
jQuery(document).ready(function(){
	historic();
	jQuery('.cb_radio button').click(function(){			
		jQuery(this).closest('.cb_radio').find('.radio_hidden').val(jQuery(this).attr('aria-label'));
	});    
	jQuery('#add_order').click(function(){
		jQuery('#order_copy').clone().css('display','').appendTo('#order_list');
		delOrder();
	});
	setOrder();
	delOrder();
	/*jQuery("#dateinfo").dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "blind",
        duration: 1000
      }
    });
 
	jQuery("#dateformatbutton").click(function() {
		jQuery( "#dateinfo" ).dialog( "open" );
    });	*/
    /*jQuery(".dateformatbutton").each(function(index,elem){
    	jQuery(elem).click(function() {
    		jQuery("#dateinfo").modal();
        });
    });*/
    jQuery('#html_ta_A').markItUp(HTMLSettings);
    jQuery('#html_ta_B').markItUp(HTMLSettings);
});
function historic(){
	var opt1=jQuery('deletehistoric_0').val();
	var opt2=jQuery('deletehistoric_1').find('option:selected').val();
	jQuery('deletehistoric').val(opt1+'#'+opt2);
}
function delOrder(){
	jQuery('.del_order').click(function(){
		jQuery(this).closest('.controls').remove();
	});
}
function setOrder(){
	var a_elems=jQuery.parseJSON('<?php echo $this->item['default_order']?>');
	if(a_elems==null) return true;
	for(var i=0;i<a_elems.length;i++){
		var tmpControl=jQuery('#order_copy').clone().css('display','').appendTo('#order_list');
		jQuery(tmpControl).find('select[name=default_order_id]').find('option[value="'+a_elems[i].headerId+'"]').attr('selected', 'selected');
		jQuery(tmpControl).find('select[name=default_order_dir]').find('option[value="'+a_elems[i].order+'"]').attr('selected', 'selected');		
	}
}
function order_wrapper(){
	var a_elems=new Array();
	jQuery('#order_list').find('.controls').each(function(i,controls){		
		var tmp=new Object();
		tmp.headerId=jQuery(controls).find('select[name=default_order_id]').val();
		if (tmp.headerId=='-1') return;
		tmp.order=jQuery(controls).find('select[name=default_order_dir]').val();
		a_elems.push(tmp);
	});
	jQuery('#default_order').val(jQuery.stringify(a_elems));
}
Joomla.submitbutton = function(task){	
	if (task == ''){
		return false;    
	} else {		
		order_wrapper();
		Joomla.submitform(task);
        return true;
    }
}
jQuery.extend({
    stringify  : function stringify(obj) {
        var t = typeof (obj);
        if (t != "object" || obj === null) {
        // simple data type
        if (t == "string") obj = '"' + obj + '"';
        return String(obj);
    } else {
        // recurse array or object
        var n, v, json = [], arr = (obj && obj.constructor == Array);

        for (n in obj) {
            v = obj[n];
            t = typeof(v);
            if (obj.hasOwnProperty(n)) {
                if (t == "string") v = '"' + v + '"'; else if (t == "object" && v !== null) v = jQuery.stringify(v);
                json.push((arr ? "" : '"' + n + '":') + String(v));
            }
        }
        return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
    }
}
});   
//-->
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">

<div class="cb_table_wrapper">

<ul class="nav nav-tabs" id="configTab">
<li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_( 'LM_CONFIG_MAIN' ); ?></a></li>
<li><a data-toggle="tab" href="#pdf"><?php echo JText::_( 'LM_CONFIG_PDF' ); ?></a></li>
<li><a href="#front" data-toggle="tab"><?php echo JText::_( 'LM_CONFIG_FRONT' ); ?></a></li>
<li><a href="#shsections" data-toggle="tab"><?php echo JText::_( 'LM_CONFIG_SHOWHIDE_SECTIONS' ); ?></a></li>
</ul> 
<div class="tab-content">
	<div id="general" class="tab-pane active in form-horizontal">		
		<div class="control-group">
			<label class="control-label"><?php echo JText::_( 'LM_SAVEHISTORIC' ); ?></label>
			<div class="controls">
				<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
				    <button type="button" class="btn btn_no <?php echo ($this->item['savehistoric']=='0'||$this->item['savehistoric']==null)?'active':'';?>" aria-label="0"><?php echo JText::_('LM_NO')?></button>
				    <button type="button" class="btn btn_yes <?php echo $this->item['savehistoric']=='1'?'active':'';?>" aria-label="1"><?php echo JText::_('LM_YES')?></button>
				    <input type="hidden" class="radio_hidden" name="savehistoric" value="<?php echo ($this->item['savehistoric']=='0'||$this->item['savehistoric']==null)?'0':'1';?>"/>
			    </div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_( 'LM_SAVESEARCH' ); ?></label>
			<div class="controls">
				<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
				    <button type="button" class="btn btn_no <?php echo ($this->item['savesearch']=='0'||$this->item['savesearch']==null)?'active':'';?>" aria-label="0"><?php echo JText::_('LM_NO')?></button>
				    <button type="button" class="btn btn_yes <?php echo $this->item['savesearch']=='1'?'active':'';?>" aria-label="1"><?php echo JText::_('LM_YES')?></button>
				    <input type="hidden" class="radio_hidden" name="savesearch" value="<?php echo ($this->item['savesearch']=='0'||$this->item['savesearch']==null)?'0':'1';?>"/>
			    </div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_( 'LM_DELETEHISTORIC' ); ?></label>
			<?php
	      	$arrhist=explode('#',$this->item['deletehistoric']); 
	      	$deletehistoric_0=$arrhist[0];
	      	$deletehistoric_1=null;
	      	if (isset($arrhist[1])) $deletehistoric_1=$arrhist[1];
	      	?>
			<div class="controls">
				<input type="text" name="deletehistoric_0" id="deletehistoric_0" value="<?php echo $arrhist[0];?>" size="5" style="width:20px;" onChange="javascript:historic();"/>
	      		<select id="deletehistoric_1" name="deletehistoric_1" onChange="javascript:historic();">   
	      			<option value="-1" <?php if ($deletehistoric_1=='-1') echo 'selected';?>><?php echo JText::_( "SELECT" ); ?></option>
	      			<optgroup label="<?php echo JText::_("LM_TIME_DELETE")?>">   
		             	<option value="0" <?php if ($deletehistoric_1=='0') echo 'selected';?>><?php echo JText::_( "LM_WEEK" ); ?></option>
		                <option value="1" <?php if ($deletehistoric_1=='1') echo 'selected';?>><?php echo JText::_( "LM_MONTH" ); ?></option>
		                <option value="2" <?php if ($deletehistoric_1=='2') echo 'selected';?>><?php echo JText::_( "LM_YEAR" ); ?></option>
	                </optgroup>
	                <optgroup label="<?php echo JText::_("LM_TIME_REGISTERS")?>">   
		             	<option value="3" <?php if ($deletehistoric_1=='3') echo 'selected';?>><?php echo JText::_( "LM_REGISTERS" ); ?></option>	                
	                </optgroup>
	            </select>
			</div>
		</div>		
		<div class="control-group">
			<label class="control-label"><?php echo JText::_( 'LM_RATEMETHOD' ); ?></label>			
			<div class="controls">
				<select id="ratemethod" name="ratemethod">
                      <option value="-1"><?php echo JText::_( "SELECT" ); ?></option>
                      <option value="0" <?php if ($this->item['ratemethod']=='0') echo 'selected';?>><?php echo JText::_( "LM_BY_USER" ); ?></option>
                      <option value="1" <?php if ($this->item['ratemethod']=='1') echo 'selected';?>><?php echo JText::_( "LM_BY_IP" ); ?></option>
            	</select>
			</div>
		</div>
	</div>
	<div id="pdf" class="tab-pane form-horizontal">
		<div class="control-group">
			<label class="control-label"><?php echo JText::_( 'LM_PDFORIENTATION' ); ?></label>
			<div class="controls">
				<select id="pdforientation" name="pdforientation">
                      <option value="0" <?php if ($this->item['pdforientation']=='0') echo 'selected';?>><?php echo JText::_( "PORTRAIT" ); ?></option>
                      <option value="1" <?php if ($this->item['pdforientation']=='1') echo 'selected';?>><?php echo JText::_( "LANDSCAPE" ); ?></option>
                </select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_( 'LM_EXPORT_PREHTML' ); ?></label>
			<div class="controls">
			<textarea id="html_ta_A" name="preprint"
										style="width: 90%" rows="15"><?php echo $this->item['preprint'];?></textarea>
				<?php
				/*$editor =JFactory::getEditor();
				echo $editor->display('preprint', $this->item['preprint'], '100%','400','100','6');*/
				?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_( 'LM_EXPORT_POSTHTML' ); ?></label>
			<div class="controls">
				<textarea id="html_ta_B" name="postprint"
										style="width: 90%" rows="15"><?php echo $this->item['postprint'];?></textarea>
				<?php
				/*$editor =JFactory::getEditor();
				echo $editor->display('postprint', $this->item['postprint'], '100%','400','100','6');*/
				?>
			</div>
		</div>
	</div>
	<div id="front" class="tab-pane form-horizontal row-fluid">
		<div class="span5">
			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'LM_DEFAULT_ORDER_BY' ); ?><br />
				<a class="btn btn-primary" id="add_order"><?php echo JText::_( 'LM_ADD' ); ?></a>
				</label>
				<div id="order_list"  style="max-height:150px;overflow:auto;margin-top:8px;">				
				</div>			
				<div id="order_copy" class="controls" style="display:none;margin-left: 20px;margin-top:8px;">
					<a class="btn btn-danger del_order"><?php echo JText::_( 'LM_DELETE' ); ?></a>
					<select name="default_order_id" style="width:auto;">
						<option value="-1"><?php echo JText::_('LM_NONE');?></option>
						<?php foreach ($this->fields as $field){?>
						<option value="<?php echo $field->id;?>"><?php echo $field->name;?></option>
						<?php }?>
					</select>
					<select name="default_order_dir" style="width:auto;">
						<option value="asc"><?php echo JText::_('LM_ASC');?></option>
						<option value="desc"><?php echo JText::_('LM_DESC');?></option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'LM_LIST_TYPE' ); ?></label>
				<div class="controls">
					<select name="list_type">
						<option value="0" <?php echo $this->item['list_type']=='0'?'selected="selected"':'';?>><?php echo JText::_( 'LM_LIST_TYPE_TABLE' ); ?></option>
						<option value="1" <?php echo $this->item['list_type']=='1'?'selected="selected"':'';?>><?php echo JText::_( 'LM_LIST_TYPE_CARD' ); ?></option>
						<option value="2" <?php echo $this->item['list_type']=='2'?'selected="selected"':'';?>><?php echo JText::_( 'LM_LIST_TYPE_SHOP' ); ?></option>
					</select>							
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'LM_SHOW_FILTERS' ); ?></label>
				<div class="controls">
					<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
					    <button type="button" class="btn btn_no <?php echo ($this->item['show_filter']=='0'||$this->item['show_filter']==null)?'active':'';?>" aria-label="0"><?php echo JText::_('LM_NO')?></button>
					    <button type="button" class="btn btn_yes <?php echo $this->item['show_filter']=='1'?'active':'';?>" aria-label="1"><?php echo JText::_('LM_YES')?></button>
					    <input type="hidden" class="radio_hidden" name="show_filter" value="<?php echo ($this->item['show_filter']=='0'||$this->item['show_filter']==null)?'0':'1';?>"/>
				    </div>							
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'LM_FILTER_AUTOMATIC' ); ?></label>
				<div class="controls">
					<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
					    <button type="button" class="btn btn_no <?php echo ($this->item['filter_automatic']=='0'||$this->item['filter_automatic']==null)?'active':'';?>" aria-label="0"><?php echo JText::_('LM_NO')?></button>
					    <button type="button" class="btn btn_yes <?php echo $this->item['filter_automatic']=='1'?'active':'';?>" aria-label="1"><?php echo JText::_('LM_YES')?></button>
					    <input type="hidden" class="radio_hidden" name="filter_automatic" value="<?php echo ($this->item['filter_automatic']=='0'||$this->item['filter_automatic']==null)?'0':'1';?>"/>
				    </div>							
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'LM_EDITOR_CHOOSE' ); ?></label>
				<div class="controls">
					<select id="editor" name="editor">
	                      <option value="0" <?php if ($this->item['editor']=='0') echo 'selected';?>><?php echo JText::_( "LM_EDITOR" ); ?></option>
	                      <option value="1" <?php if ($this->item['editor']=='1') echo 'selected';?>><?php echo JText::_( "JOOMLA_EDITOR" ); ?></option>
	                </select>
				</div>				
			</div>
			<div class="col-xs-12"> <small><?php echo JText::_( 'LM_EDITOR_CHOOSE_SUPPORT'); ?></small></div>
			<legend><?php echo JText::_( 'LM_LIST_ONLY_CARD_SHOP' ); ?></legend>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'LM_LIST_COLUMNS' ); ?></label>
				<div class="controls">
					<input type="text" class="input-mini" name="list_columns" value="<?php echo $this->item['list_columns'];?>"/>							
				</div>
			</div>	
			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'LM_LIST_HEIGHT' ); ?></label>
				<div class="controls">
					<div class="input-append">
					<input type="text" class="input-mini" name="list_height" value="<?php echo $this->item['list_height'];?>" disabled="disabled"/>
					<span class="add-on"><?php echo JText::_('LM_PX')?></span>
					</div>							
				</div>
			</div>	
			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'LM_LIST_CSS_NAME' ); ?></label>
				<div class="controls">
					<input type="text" class="input-small" name="list_name_class" value="<?php echo $this->item['list_name_class'];?>"/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'LM_LIST_CSS_VALUE' ); ?></label>
				<div class="controls">
					<input type="text" class="input-small" name="list_value_class" value="<?php echo $this->item['list_value_class'];?>"/>
				</div>
			</div>
		</div>
		
		
		<div class="span6">
			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'LM_MODAL_FORM' ); ?></label>
				<div class="controls">
					<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
					    <button type="button" class="btn btn_no <?php echo ($this->item['modalform']=='0'||$this->item['modalform']==null)?'active':'';?>" aria-label="0"><?php echo JText::_('LM_NO')?></button>
					    <button type="button" class="btn btn_yes <?php echo $this->item['modalform']=='1'?'active':'';?>" aria-label="1"><?php echo JText::_('LM_YES')?></button>
					    <input type="hidden" class="radio_hidden" name="modalform" value="<?php echo ($this->item['modalform']=='0'||$this->item['modalform']==null)?'0':'1';?>"/>
				    </div>							
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'DETAIL_ONCLICK' ); ?></label>
				<div class="controls">
					<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
					    <button type="button" class="btn btn_no <?php echo ($this->item['detail_onclick']=='0'||$this->item['detail_onclick']==null)?'active':'';?>" aria-label="0"><?php echo JText::_('LM_NO')?></button>
					    <button type="button" class="btn btn_yes <?php echo $this->item['detail_onclick']=='1'?'active':'';?>" aria-label="1"><?php echo JText::_('LM_YES')?></button>
					    <input type="hidden" class="radio_hidden" name="detail_onclick" value="<?php echo ($this->item['detail_onclick']=='0'||$this->item['detail_onclick']==null)?'0':'1';?>"/>
				    </div>							
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'LM_VIEW_DETAILMODE' ); ?></label>
				<div class="controls">
					<select name="detailmode">
						<option value="0" <?php echo $this->item['detailmode']=='0'?'selected="selected"':'';?>><?php echo JText::_( 'LM_VIEW_DETAILMODE_POPUP' ); ?></option>
						<option value="1" <?php echo $this->item['detailmode']=='1'?'selected="selected"':'';?>><?php echo JText::_( 'LM_VIEW_DETAILMODE_NEWPAGE' ); ?></option>
						<option value="2" <?php echo $this->item['detailmode']=='2'?'selected="selected"':'';?>><?php echo JText::_( 'LM_VIEW_DETAILMODE_SAMEPAGE' ); ?></option>
					</select>
				</div>
			</div>		
			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'LM_VIEW_SEARCH_TYPE' ); ?></label>
				<div class="controls">
					<select name="search_type">
						<option value="0" <?php echo $this->item['search_type']=='0'?'selected="selected"':'';?>><?php echo JText::_( 'LM_VIEW_SEARCH_TYPE_ALL' ); ?></option>
						<option value="1" <?php echo $this->item['search_type']=='1'?'selected="selected"':'';?>><?php echo JText::_( 'LM_VIEW_SEARCH_TYPE_SPLITTED' ); ?></option>
					</select>
				</div>
			</div>		
			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'FIRST_LOAD_HIDE' ); ?></label>
				<div class="controls">
					<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
					    <button type="button" class="btn btn_no <?php echo ($this->item['hidelist']=='0'||$this->item['hidelist']==null)?'active':'';?>" aria-label="0"><?php echo JText::_('LM_NO')?></button>
					    <button type="button" class="btn btn_yes <?php echo $this->item['hidelist']=='1'?'active':'';?>" aria-label="1"><?php echo JText::_('LM_YES')?></button>
					    <input type="hidden" class="radio_hidden" name="hidelist" value="<?php echo ($this->item['hidelist']=='0'||$this->item['hidelist']==null)?'0':'1';?>"/>
				    </div>							
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'LM_TOOL_COLUMN_POSITION' ); ?></label>
				<div class="controls">
					<input type="text" id="tool_column_position" name="tool_column_position" value="<?php echo $this->item['tool_column_position']?>" style="width:30px;"/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'LM_TOOL_COLUMN_NAME' ); ?></label>
				<div class="controls">
					<input type="text" id="tool_column_name" name="tool_column_name" value="<?php echo $this->item['tool_column_name']?>" style="width:120px;"/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'LM_DATE_FORMAT_DATABASE' ); ?></label>
				<div class="controls">
					<input type="text" id="date_format_bbdd" name="date_format_bbdd" value="<?php echo $this->item['date_format_bbdd']?>" style="width:90px;"/>
					<a href="#dateinfo" id="dateformatbutton" role="button" class="btn" data-toggle="modal" style="cursor:pointer;"><?php echo JText::_( 'LM_DATE_FORMAT_INFO' ); ?></a>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'LM_DATE_FORMAT' ); ?></label>
				<div class="controls">
					<input type="text" id="date_format" name="date_format" value="<?php echo $this->item['date_format']?>" style="width:90px;"/>
					<a href="#dateinfo" id="dateformatbutton" role="button" class="btn" data-toggle="modal" style="cursor:pointer;"><?php echo JText::_( 'LM_DATE_FORMAT_INFO' ); ?></a>
				</div>
			</div>			
			<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'LM_NUMBER_FORMAT' ); ?></label>
				<div class="controls">
					<div class="input-prepend" style="margin-right:20px">
						<span class="add-on"><?php echo JText::_('LM_DECIMAL_FORMAT');?></span>
						<input type="text" id="decimal" name="decimal" value="<?php echo $this->item['decimal']?>" style="width:12px;"/>
					</div>
					<div class="input-prepend">
						<span class="add-on"><?php echo JText::_('LM_THOUSAND_FORMAT');?></span>
						<input type="text" id="thousand" name="thousand" value="<?php echo $this->item['thousand']?>" style="width:12px;"/>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="shsections" class="tab-pane form-horizontal">	
		<div class="control-group">
			<label class="control-label"><?php echo JText::_( 'LM_VIEW_TOOLBAR' ); ?></label>
			<div class="controls">
				<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
				    <button type="button" class="btn btn_no <?php echo ($this->item['view_toolbar']=='0')?'active':'';?>" aria-label="0"><?php echo JText::_('LM_NO')?></button>
				    <button type="button" class="btn btn_yes <?php echo ($this->item['view_toolbar']=='1'||$this->item['view_toolbar']==null)?'active':'';?>" aria-label="1"><?php echo JText::_('LM_YES')?></button>
				    <input type="hidden" class="radio_hidden" name="view_toolbar" value="<?php echo ($this->item['view_toolbar']=='1'||$this->item['view_toolbar']==null)?'1':'0';?>"/>
			    </div>
			</div>
		</div>	
		<div class="control-group">
			<label class="control-label"><?php echo JText::_( 'LM_VIEW_TOOLBAR_BOTTOM' ); ?></label>
			<div class="controls">
				<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
				    <button type="button" class="btn btn_no <?php echo ($this->item['view_toolbar_bottom']=='0'||$this->item['view_toolbar_bottom']==null)?'active':'';?>" aria-label="0"><?php echo JText::_('LM_NO')?></button>
				    <button type="button" class="btn btn_yes <?php echo ($this->item['view_toolbar_bottom']=='1')?'active':'';?>" aria-label="1"><?php echo JText::_('LM_YES')?></button>
				    <input type="hidden" class="radio_hidden" name="view_toolbar_bottom" value="<?php echo ($this->item['view_toolbar_bottom']=='0'||$this->item['view_toolbar_bottom']==null)?'0':'1';?>"/>
			    </div>
			</div>
		</div>		
		<div class="control-group">
			<label class="control-label"><?php echo JText::_( 'LM_VIEW_FILTER' ); ?></label>
			<div class="controls">
				<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
				    <button type="button" class="btn btn_no <?php echo ($this->item['view_filter']=='0')?'active':'';?>" aria-label="0"><?php echo JText::_('LM_NO')?></button>
				    <button type="button" class="btn btn_yes <?php echo ($this->item['view_filter']=='1'||$this->item['view_filter']==null)?'active':'';?>" aria-label="1"><?php echo JText::_('LM_YES')?></button>
				    <input type="hidden" class="radio_hidden" name="view_filter" value="<?php echo ($this->item['view_filter']=='1'||$this->item['view_filter']==null)?'1':'0';?>"/>
			    </div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_( 'LM_VIEW_BOTTOMBAR' ); ?></label>
			<div class="controls">
				<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
				    <button type="button" class="btn btn_no <?php echo ($this->item['view_bottombar']=='0')?'active':'';?>" aria-label="0"><?php echo JText::_('LM_NO')?></button>
				    <button type="button" class="btn btn_yes <?php echo ($this->item['view_bottombar']=='1'||$this->item['view_bottombar']==null)?'active':'';?>" aria-label="1"><?php echo JText::_('LM_YES')?></button>
				    <input type="hidden" class="radio_hidden" name="view_bottombar" value="<?php echo ($this->item['view_bottombar']=='1'||$this->item['view_bottombar']==null)?'1':'0';?>"/>
			    </div>
			</div>
		</div>
	</div>
</div>
</div>


<div id="dateinfo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="dateformatbutton" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="height:15px;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>        
      </div>
      <div class="modal-body" style="padding-top:0px;">        
		    <?php echo JText::_('LM_DATE_FORMAT_INFO_EXTENDED');?>
      </div>      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
	

<input type="hidden" name="id" value="<?php echo $this->item['id']; ?>" />
<input type="hidden" name="cid[]" value="<?php echo $this->item['id']; ?>" />
<input type="hidden" name="idlisting" value="<?php echo $this->item['id']; ?>" />
<input type="hidden" name="deletehistoric" id="deletehistoric" value="" />
<input type="hidden" name="option" value="com_listmanager" />
<input type="hidden" name="task" value="storeConfig" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="listing" />
<input type="hidden" name="check" value="post"/>
<input type="hidden" name="default_order" id="default_order"/>
</form>
