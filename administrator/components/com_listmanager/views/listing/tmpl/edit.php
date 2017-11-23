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
JHTML::_('behavior.calendar');
JHTML::_('bootstrap.framework');

$pathImagen=JURI::base().'components/com_listmanager/assets/img/';
$pathImagenCalendar=$pathImagen.'calendar.png';
$document=JFactory::getDocument();                                                                       
$css=$this->__getCSS();          
foreach ($css as $style){                                                        
	$document->addStyleSheet($style);
}
$js=$this->__getJS();
$js[]=JURI::base().'components/com_listmanager/assets/js/jquery.validate.min.js';
$js[]=JURI::base().'components/com_listmanager/assets/js/edit.js';
$js[]=JURI::base().'components/com_listmanager/assets/js/jquery.csv-0.71.min.js';
$js[]= JURI::base () . 'components/com_listmanager/assets/js/jquery.markitup.js';
$js[]= JURI::base () . 'components/com_listmanager/assets/js/markitup/sets/html/set.js';

foreach ($js as $jscript){                                                        
	?><script src="<?php echo $jscript;?>"></script><?php 
}
$arrFields=array();
$arrFields[]='TYPE';
$arrFields[]='TOTAL';
$arrFields[]='DECIMALS';
//$arrFields[]='SIZE';
$arrFields[]='DOWN_LIMIT';
$arrFields[]='UP_LIMIT';
$arrFields[]='MULTIVALUES';
$arrFields[]='DEFAULT_VALUE';
$arrFields[]='VALIDATION';
$arrFields[]='REQUIRED';
$arrFields[]='VISIBLE';
$arrFields[]='STYLECLASS';
$arrFields[]='AUTOFILTER';
$arrFields[]='SHOW_ORDER';
$arrFields[]='ORDER';
$arrFields[]='EXPORTABLE';
$arrFields[]='PLACEHOLDER';
$arrFields[]='READMORE';
$arrFields[]='URL';
$arrFields[]='FIELD_FILTER';
$arrFields[]='OPEN_LIST';
$arrTypes=array();
$arrTypes[]='NUMBER';
$arrTypes[]='SLIDER';
$arrTypes[]='PROGRESS';
$arrTypes[]='DATE';
$arrTypes[]='TODAY';
$arrTypes[]='OPTION_LIST';
$arrTypes[]='MULTIPLE_OPTION';
$arrTypes[]='MULTIPLE_OPTION_LIST';
$arrTypes[]='RADIO';
$arrTypes[]='YES_NO';
$arrTypes[]='TEXT';
$arrTypes[]='TEXT_AREA';
$arrTypes[]='HTML_EDITOR';
$arrTypes[]='USER';
$arrTypes[]='VOTE';
$arrTypes[]='BUY_NOW';
$arrTypes[]='LINK';

$arrElements[]=array();
$arrElements['fields']=$arrFields;
$arrElements['types']=$arrTypes;
// Info Editor
//$editor =JFactory::getEditor();
?>
<?php echo $this->__getHelp('EDIT',$arrElements);?>
<link rel="stylesheet" type="text/css" href="<?php echo JURI::base ();?>components/com_listmanager/assets/js/markitup/skins/markitup/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo JURI::base ();?>components/com_listmanager/assets/js/markitup/sets/html/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo JURI::base ();?>components/com_listmanager/assets/js/markitup/templates/preview.css" />
<script type="text/javascript">
<!--
<?php 
$componentParams = JComponentHelper::getParams('com_listmanager');
$param = $componentParams->get('editor', '0');
echo $param==0?'var see_editor=true;':'var see_editor=false;';
?>
function getLangTrads(text){
	switch(text){
		case 'INCORRECT_VALUES' : return '<?php echo JText::_('INCORRECT_VALUES')?>';
		case 'ADD_SQL_STRING_VALUES' : return '<?php echo JText::_('ADD_SQL_STRING_VALUES')?>';
		case 'SQL_STRING_VALUE_DELETED' : return '<?php echo JText::_('SQL_STRING_VALUE_DELETED')?>';
		case 'ADD_SQL_STRING' : return '<?php echo JText::_('ADD_SQL_STRING')?>';
		case 'ADD_VALUES' : return '<?php echo JText::_('ADD_VALUES')?>';
		case 'NAME' : return '<?php echo JText::_('NAME')?>';
		case 'VALUE' : return '<?php echo JText::_('VALUE')?>';
		case 'INCORRECT_VALUES' : return '<?php echo JText::_('INCORRECT_VALUES')?>';
		case 'SELECT' : return '<?php echo JText::_('SELECT')?>';
		case 'NUMBER' : return '<?php echo JText::_('NUMBER')?>';
		case 'DATE' : return '<?php echo JText::_('DATE')?>';
		case 'OPTION_LIST' : return '<?php echo JText::_('OPTION_LIST')?>';		
		case 'LINKED_LIST' : return '<?php echo JText::_('LINKED_LIST')?>';
		case 'MULTIPLE_OPTION_LIST' : return '<?php echo JText::_('MULTIPLE_OPTION_LIST')?>';
		case 'YES_NO' : return '<?php echo JText::_('YES_NO')?>';
		case 'TEXT' : return '<?php echo JText::_('TEXT')?>';
		case 'TEXTAREA' : return '<?php echo JText::_('TEXTAREA')?>';
		case 'CAPTCHA' : return '<?php echo JText::_('CAPTCHA')?>';
		case 'AUTOCOMPLETER' : return '<?php echo JText::_('AUTOCOMPLETER')?>';
		case 'FILE' : return '<?php echo JText::_('FILE')?>';
		case 'SECTION_NAME' : return '<?php echo JText::_('SECTION_NAME')?>';
		case 'SECTION_ADD' : return '<?php echo JText::_('SECTION_ADD')?>';
		case 'NO' : return '<?php echo JText::_('LM_NO')?>';
		case 'YES' : return '<?php echo JText::_('LM_YES')?>';
		case 'DEFAULT_SECTIONS' : return '<?php echo JText::_('DEFAULT_SECTIONS')?>';
		case 'LIMIT_SECTIONS' : return '<?php echo JText::_('LIMIT_SECTIONS')?>';
		case 'FIELDS' : return '<?php echo JText::_('FIELDS')?>';
		case 'NEW' : return '<?php echo JText::_('NEW')?>';
		case 'ERROR_SQL' : return '<?php echo JText::_('ERROR_SQL')?>';
		case 'AF_RANGE' : return '<?php echo JText::_('AF_RANGE')?>';
	}
}
//-->
</script>
<div  class="cb_table_wrapper">
<ul class="nav nav-tabs" id="navmenu">
	<li class="active">  		
		<a data-toggle="tab" style="height: 26px;" href="#general"><i class="igeneral pull-left">&nbsp;</i><div class="cb_editmenu_item"><?php echo JText::_( 'SM_GENERAL' ); ?></div></a>
    </li>
    <li>  		
    	<a data-toggle="tab" style="height: 26px;" href="#fields"><i class="ifields pull-left">&nbsp;</i><div class="cb_editmenu_item"><?php echo JText::_( 'SM_FIELDS' ); ?></div></a>			
    </li>    
    <li>  		
    	<a data-toggle="tab" style="height: 26px;" href="#keycolumns"><i class="ikeycolums pull-left">&nbsp;</i><div class="cb_editmenu_item"><?php echo JText::_( 'SM_KEY_COLUMNS' ); ?></div></a>			
    </li>
    <li>  		
    	<a data-toggle="tab" style="height: 26px;" href="#specialfilters"><i class="ispecialfilters pull-left">&nbsp;</i><div class="cb_editmenu_item"><?php echo JText::_( 'SM_SPECIAL_FILTERS' ); ?></div></a>			
    </li>
</ul>
<div id="editcell" class="tab-content">
	<div id="general" class="tab-pane active in form-horizontal">	
		<div class="control-group">
			<label class="control-label"><?php echo JText::_( 'NAME' ); ?></label>
			<div class="controls">
				<input type="text" name="name" value="<?php echo $this->item['name']; ?>"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_( 'INFORMATION' ); ?></label>
			<div class="controls">			
				<?php //echo $editor->display('info', $this->item['info'], '600','300','100','6');?>
				<textarea id="html_ta_A" name="info"
										style="width: 90%" rows="15"><?php echo $this->item['info'];?></textarea>
			</div>
		</div>
	</div>
	<div id="fields"  class="tab-pane fade in form-horizontal">
	        <div class="row">
                        <div class="span2 offset1">
                            <h4 rel="tooltip" data-original-title="<?php echo JText::_("T_USER_VARIABLE_LIST")?>" title="<?php echo JText::_("T_USER_VARIABLE_LIST")?>"><?php echo JText::_( 'USER_VARIABLE_LIST' ); ?></h4>
                        </div>
                        <div class="span9">
                            <button class="btn" type="button" name="addfield" id="addfield"><?php echo JText::_( "ADD_FIELD" ); ?></button>	                            
                            <button class="btn btn-danger" type="button" name="delfield" id="delfield"><?php echo JText::_( "DELETE_FIELD" ); ?></button>
                        </div>
              </div>
	        <div class="tabbable tabs-left" id="field_nav">	             
	          <ul class="nav nav-tabs" style="height:575px;overflow-y: auto;overflow-x: hidden;padding-right:10px;">
	          	<?php	          	
	          		foreach ($this->fields as $field){
	            ?>
			    <li>
			  	  <a data-toggle="tab" href="#fc_<?php echo $field->id;?>"><?php echo $field->name;?></a>
			    </li>
			    <?php }?>
			  </ul>		
			  <div class="tab-content">
			  	<?php
	                foreach ($this->fields as $field){
	            ?>
			  	<div id="fc_<?php echo $field->id;?>" class="tab-pane fade in form-horizontal row" style="min-height:550px;padding-left:6px;">
					<blockquote style="margin-left:26px;">
				    	<p><?php echo JText::_( 'SETTINGS' ); ?></p>
				    </blockquote>
					<div class="span5" style="padding-left:15px;">
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'ID' ); ?></label>
							<div class="controls">
								<span class="input-small uneditable-input"><?php echo $field->id; ?></span>
								<input name="id" id="chk" type="hidden" value="<?php echo $field->id; ?>"/>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'NAME' ); ?></label>
							<div class="controls">
								<input class="input-medium required" name="name" id="name" type="text" value="<?php echo $field->name; ?>"/>							
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'INNERNAME' ); ?></label>
							<div class="controls">
								<input class="input-medium basic_letters" name="innername" id="innername" type="text" value="<?php echo $field->innername; ?>"/>							
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'REQUIRED' ); ?></label>
							<div class="controls">
								<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
								    <button type="button" class="btn btn_no <?php echo ($field->mandatory=='0'||$field->mandatory==null)?'active':'';?>" aria-label="0"><?php echo JText::_('LM_NO')?></button>
								    <button type="button" class="btn btn_yes <?php echo $field->mandatory=='1'?'active':'';?>" aria-label="1"><?php echo JText::_('LM_YES')?></button>
								    <input type="hidden" class="radio_hidden" name="mandatory" value="<?php echo ($field->mandatory=='0'||$field->mandatory==null)?'0':'1';?>"/>
							    </div>							
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'TYPE' ); ?></label>
							<div class="controls" id="tdtype">
								<select class="type" onchange='javascript:changeType(this);' id="type" name="type">
			                          <option value="-1"><?php echo JText::_( "SELECT" ); ?></option>
			                          <optgroup label="<?php echo JText::_("NUMBER_GROUP")?>">
				                      <option value="0" <?php if ($field->type==0) echo 'selected';?>><?php echo JText::_( "NUMBER" ); ?></option>
				                      <option value="14" <?php if ($field->type==14) echo 'selected';?>><?php echo JText::_( "SLIDER" ); ?></option>
				                      <option value="8" <?php if ($field->type==8) echo 'selected';?>><?php echo JText::_( "PROGRESS" ); ?></option>
				                      </optgroup>
				                      <optgroup label="<?php echo JText::_("DATE_GROUP")?>">
				                      <option value="1" <?php if ($field->type==1) echo 'selected';?>><?php echo JText::_( "DATE" ); ?></option>
				                      <option value="12" <?php if ($field->type==12) echo 'selected';?>><?php echo JText::_( "TODAY" ); ?></option>
				                      </optgroup>
				                      <optgroup label="<?php echo JText::_("MULTIVALUE_GROUP")?>">
				                      <option value="2" <?php if ($field->type==2) echo 'selected';?>><?php echo JText::_( "OPTION_LIST" ); ?></option>				                      
				                      <option value="11" <?php if ($field->type==11) echo 'selected';?>><?php echo JText::_( "MULTIPLE_OPTION" ); ?></option>
				                      <option value="16" <?php if ($field->type==16) echo 'selected';?>><?php echo JText::_( "MULTIPLE_OPTION_LIST" ); ?></option>
				                      <option value="10" <?php if ($field->type==10) echo 'selected';?>><?php echo JText::_( "RADIOBUTTONS" ); ?></option>
				                      <option value="3" <?php if ($field->type==3) echo 'selected';?>><?php echo JText::_( "YES_NO" ); ?></option>
				                      </optgroup>
				                      <optgroup label="<?php echo JText::_("TEXT_GROUP")?>">
				                      <option value="4" <?php if ($field->type==4) echo 'selected';?>><?php echo JText::_( "TEXT" ); ?></option>
				                      <option value="7" <?php if ($field->type==7) echo 'selected';?>><?php echo JText::_( "TEXT_AREA" ); ?></option>
				                      <option value="9" <?php if ($field->type==9) echo 'selected';?>><?php echo JText::_( "HTML_EDITOR" ); ?></option>
				                      </optgroup>
				                      <optgroup label="<?php echo JText::_("OTHERS_GROUP")?>">				                              
				                      <option value="6" <?php if ($field->type==6) echo 'selected';?>><?php echo JText::_( "USER" ); ?></option>
				                      <option value="15" <?php if ($field->type==15) echo 'selected';?>><?php echo JText::_( "VOTE" ); ?></option>
				                      <option value="17" <?php if ($field->type==17) echo 'selected';?>><?php echo JText::_( "PAYPAL_BUTTON" ); ?></option>
				                      <option value="18" <?php if ($field->type==18) echo 'selected';?>><?php echo JText::_( "LIST_MANAGER_LINK" ); ?></option>	
				                      <option value="19" <?php if ($field->type==19) echo 'selected';?>><?php echo JText::_( "AUTOINCREMENT" ); ?></option>			                      
				                      </optgroup>
			                    </select>							
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'ORDER' ); ?></label>
							<div class="controls">
								<input class="input-mini order" name="order"  id="order" type="text" value="<?php echo $field->order; ?>"/>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'VISIBLE' ); ?></label>
							<div class="controls">
								<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
								    <button type="button" class="btn btn_no <?php echo ($field->visible=='0'||$field->visible==null)?'active':'';?>" aria-label="0"><?php echo JText::_('LM_NO')?></button>
								    <button type="button" class="btn btn_yes <?php echo ($field->visible=='1'||$field->visible==null)?'active':'';?>" aria-label="1"><?php echo JText::_('LM_YES')?></button>
								    <input type="hidden" class="radio_hidden" name="visible" value="<?php echo ($field->visible=='0'||$field->visible==null)?'0':'1';?>"/>
							    </div>							
							</div>
						</div>		
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'AUTOFILTER' ); ?> (1)</label>
							<div class="controls">
								<select id="autofilter" name="autofilter">
			                      <option value="-1"><?php echo JText::_( "SELECT" ); ?></option>
			                      <option value="0" <?php if ($field->autofilter==0) echo 'selected';?>><?php echo JText::_( "AF_SELECT" ); ?></option>
			                      <option value="1" <?php if ($field->autofilter==1) echo 'selected';?>><?php echo JText::_( "AF_TEXT" ); ?></option>
			                      <option value="2" <?php if ($field->autofilter==2) echo 'selected';?>><?php echo JText::_( "AF_MULTIPLE" ); ?></option>
			                      <?php 
			                      $typesAllowed=array(0,14,1,4,19);
			                      if (in_array($field->type, $typesAllowed)):
			                      ?>	<option value="3" <?php if ($field->autofilter==3) echo 'selected';?>><?php echo JText::_( "AF_RANGE" ); ?></option>
			                      <?php endif;?>
			                    </select>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'SHOW_ORDER' ); ?></label>
							<div class="controls">
								<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
								    <button type="button" class="btn btn_no <?php echo ($field->showorder=='0'||$field->showorder==null)?'active':'';?>" aria-label="0"><?php echo JText::_('LM_NO')?></button>
								    <button type="button" class="btn btn_yes <?php echo ($field->showorder=='1'||$field->showorder==null)?'active':'';?>" aria-label="1"><?php echo JText::_('LM_YES')?></button>
								    <input type="hidden" class="radio_hidden" name="showorder" value="<?php echo ($field->showorder=='0'||$field->showorder==null)?'0':'1';?>"/>
							    </div>							
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'EXPORTABLE' ); ?></label>
							<div class="controls">
								<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
								    <button type="button" class="btn btn_no <?php echo ($field->exportable=='0'||$field->exportable==null)?'active':'';?>" aria-label="0"><?php echo JText::_('LM_NO')?></button>
								    <button type="button" class="btn btn_yes <?php echo ($field->exportable=='1'||$field->exportable==null)?'active':'';?>" aria-label="1"><?php echo JText::_('LM_YES')?></button>
								    <input type="hidden" class="radio_hidden" name="exportable" value="<?php echo ($field->exportable=='0'||$field->exportable==null)?'0':'1';?>"/>
							    </div>							
							</div>
						</div>		
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'SEARCHABLE' ); ?></label>
							<div class="controls">
								<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
								    <button type="button" class="btn btn_no <?php echo ($field->searchable=='0'||$field->searchable==null)?'active':'';?>" aria-label="0"><?php echo JText::_('LM_NO')?></button>
								    <button type="button" class="btn btn_yes <?php echo ($field->searchable=='1'||$field->searchable==null)?'active':'';?>" aria-label="1"><?php echo JText::_('LM_YES')?></button>
								    <input type="hidden" class="radio_hidden" name="searchable" value="<?php echo ($field->searchable=='0'||$field->searchable==null)?'0':'1';?>"/>
							    </div>							
							</div>
						</div>
						<div class="control-group">
								<label class="control-label"><?php echo JText::_( 'LM_SEARCH_TYPE' ); ?></label>
								<div class="controls">
									<select name="searchtype">
										<option value="0" <?php echo $field->searchtype=='0'?'selected="selected"':'';?>><?php echo JText::_( 'LM_SEARCH_TYPE_BROAD' ); ?></option>
										<option value="1" <?php echo $field->searchtype=='1'?'selected="selected"':'';?>><?php echo JText::_( 'LM_SEARCH_TYPE_EXACT' ); ?></option>
									</select>				
								</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'BULK' ); ?></label>
							<div class="controls">
								<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
								    <button type="button" class="btn btn_no <?php echo ($field->bulk=='0'||$field->bulk==null)?'active':'';?>" aria-label="0"><?php echo JText::_('LM_NO')?></button>
								    <button type="button" class="btn btn_yes <?php echo ($field->bulk=='1'||$field->bulk==null)?'active':'';?>" aria-label="1"><?php echo JText::_('LM_YES')?></button>
								    <input type="hidden" class="radio_hidden" name="bulk" value="<?php echo ($field->bulk=='0'||$field->bulk==null)?'0':'1';?>"/>
							    </div>							
							</div>
						</div>			
					</div>
					<div class="span6" style="padding-left:15px;">
						<div class="control-group decimal">
							<label class="control-label" rel="tooltip"><?php echo JText::_( 'DECIMALS' ); ?></label>
							<div class="controls">
								<input class="input-mini validate-numeric" name="decimal" type="text" value="<?php echo $field->decimal; ?>" />							
							</div>
						</div>
						<div class="control-group total">
							<label class="control-label"><?php echo JText::_( 'TOTAL' ); ?></label>
							<div class="controls">
								  <select id="total" name="total">
			                      <option value="-1"><?php echo JText::_( "SELECT" ); ?></option>
			                      <option value="0" <?php if ($field->total==0) echo 'selected';?>><?php echo JText::_( "SUM" ); ?></option>
			                      <option value="1" <?php if ($field->total==1) echo 'selected';?>><?php echo JText::_( "SUM_CEIL" ); ?></option>
			                      <option value="2" <?php if ($field->total==2) echo 'selected';?>><?php echo JText::_( "SUM_FLOOR" ); ?></option>
			                      <option value="3" <?php if ($field->total==3) echo 'selected';?>><?php echo JText::_( "SUM_ROUND" ); ?></option>
			                      <option value="4" <?php if ($field->total==4) echo 'selected';?>><?php echo JText::_( "MAX" ); ?></option>
			                      <option value="5" <?php if ($field->total==5) echo 'selected';?>><?php echo JText::_( "MIN" ); ?></option>
			                      <option value="6" <?php if ($field->total==6) echo 'selected';?>><?php echo JText::_( "MEAN" ); ?></option>                      
			                      </select>													
							</div>
						</div>
						<div class="control-group validation">
							<label class="control-label"><?php echo JText::_( 'VALIDATION' ); ?></label>
							<div class="controls">
								<select id="validate" name="validate" >
			                      <option value="-1"><?php echo JText::_( "SELECT" ); ?></option>
			                      <option value="alpha" <?php if ($field->validate=='alpha') echo 'selected';?>><?php echo JText::_( "ONLY_LETTERS" ); ?></option>
			                      <option value="alphanum" <?php if ($field->validate=='alphanum') echo 'selected';?>><?php echo JText::_( "ONLY_LETTERS_NUMBERS" ); ?></option>
			                      <option value="email" <?php if ($field->validate=='email') echo 'selected';?>><?php echo JText::_( "EMAIL" ); ?></option>
			                      <option value="url" <?php if ($field->validate=='url') echo 'selected';?>><?php echo JText::_( "URL" ); ?></option>
			                    </select>
							</div>
						</div>
						<div class="control-group limit0">
							<label class="control-label"><?php echo JText::_( 'DOWN_LIMIT' ); ?></label>
							<div class="controls">
								<input class="input-medium" name="limit0" id="limit0" type="text" value="<?php echo $field->limit0; ?>"/>
							</div>
						</div>
						<div class="control-group limit1">
							<label class="control-label"><?php echo JText::_( 'UP_LIMIT' ); ?></label>
							<div class="controls">
								<input class="input-medium" name="limit1" id="limit1" type="text" value="<?php echo $field->limit1; ?>"/>
							</div>
						</div>
						<div class="control-group default">
							<label class="control-label"><?php echo JText::_( 'DEFAULT' ); ?></label>
							<div class="controls">
								<input class="input-medium" name="defaulttext" id="defaulttext" type="text" value="<?php echo $field->defaulttext; ?>"/>								
							</div>
						</div>
						<div class="control-group placeholder">
							<label class="control-label"><?php echo JText::_( 'PLACEHOLDER' ); ?></label>
							<div class="controls">
								<input class="input-medium" name="placeholder" id="placeholder" type="text" value="<?php echo $field->placeholder; ?>"/>
							</div>
						</div>	
						<div class="control-group css">
							<label class="control-label"><?php echo JText::_( 'CSS' ); ?></label>
							<div class="controls">
								<input class="input-medium" id="css" name="css" type="text" value="<?php echo $field->css; ?>" />							
							</div>
						</div>	
						<div class="control-group readmore">
							<label class="control-label"><?php echo JText::_( 'READMORE' ); ?></label>
							<div class="controls">
								<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
								    <button type="button" class="btn btn_no <?php echo ($field->readmore=='0'||$field->readmore==null)?'active':'';?>" aria-label="0"><?php echo JText::_('LM_NO')?></button>
								    <button type="button" class="btn btn_yes <?php echo ($field->readmore=='1'||$field->readmore==null)?'active':'';?>" aria-label="1"><?php echo JText::_('LM_YES')?></button>
								    <input type="hidden" class="radio_hidden" name="readmore" value="<?php echo ($field->readmore=='0'||$field->readmore==null)?'0':'1';?>"/>
							    </div>							
							</div>
						</div>	
						<div class="control-group readmore">
							<label class="control-label"><?php echo JText::_( 'READMORE_WORD_COUNT' ); ?></label>
							<div class="controls">
								<input class="input-mini" id="readmore_word_count" name="readmore_word_count" type="text" value="<?php echo $field->readmore_word_count; ?>" />							
							</div>
						</div>	
						<div class="control-group cardview">
							<label class="control-label"><?php echo JText::_( 'CARDVIEW' ); ?></label>
							<div class="controls">
								<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
								    <button type="button" class="btn btn_no <?php echo ($field->cardview=='0'||$field->cardview==null)?'active':'';?>" aria-label="0"><?php echo JText::_('NORMAL')?></button>
								    <button type="button" class="btn btn_yes <?php echo ($field->cardview=='1'||$field->cardview==null)?'active':'';?>" aria-label="1"><?php echo JText::_('LARGE')?></button>
								    <input type="hidden" class="radio_hidden" name="cardview" value="<?php echo ($field->cardview=='0'||$field->cardview==null)?'0':'1';?>"/>
							    </div>							
							</div>
						</div>
						<div class="lmlink">
							<h4><?php echo JText::_( 'LM_LINK_TITLE' ); ?></h4>							
						</div>
						<div class="control-group lmlink">
							<label class="control-label"><?php echo JText::_( 'LM_URL_LINK' ); ?></label>
							<div class="controls">
								<input type="text" class="input-large" name="link_url" value="<?php echo $field->link_url; ?>"/>												
							</div>
						</div>
						<div class="control-group lmlink">
							<label class="control-label"><?php echo JText::_( 'LM_LIST_LINK' ); ?></label>
							<div class="controls">
							<?php if($field->link_id!=null && $field->link_id!='' && $field->link_id!=0) {?>
							<label><?php echo JText::_( 'LM_LIST_LINK_SELECTED' ); ?> <br /><a class="btn btn-danger" style="margin-right:8px;" href="#" onclick="javascript:link_remove(this);"><i class="icon-remove" style="width:12px;">&nbsp;</i></a><strong><?php echo $field->link_name;?></strong></label>
							<?php }?>
								<select class="lmlinkselect">
									<option value="-1"><?php echo JText::_('LM_SELECT')?></option>
									<?php foreach ($this->alllist as $list){?>
									<option value="<?php echo $list->id;?>"><?php echo $list->name;?></option>
									<?php }?>
								</select>		
								<select class="fieldlist" id="fieldlist"></select>
								<input type="hidden" name="link_id_original" value="<?php echo $field->link_id;?>"/>
								<input type="hidden" name="link_id" value="<?php echo $field->link_id;?>"/>												
							</div>
						</div>		
						<div class="control-group lmlink">
							<label class="control-label"><?php echo JText::_( 'LM_LINK_TYPE' ); ?></label>
							<div class="controls">
								<select name="link_type">
									<option value="-1" <?php echo $field->link_type=='-1'?'selected="selected"':'';?>><?php echo JText::_('LM_SAME_WINDOW');?></option>
									<option value="0" <?php echo $field->link_type=='0'?'selected="selected"':'';?>><?php echo JText::_('LM_NEW_WINDOW');?></option>
									<option value="1" <?php echo $field->link_type=='1'?'selected="selected"':'';?>><?php echo JText::_('LM_POPUP');?></option>
								</select>
							</div>
						</div>		
						<div class="control-group lmlink">
							<label class="control-label"><?php echo JText::_( 'LM_WIDTH' ); ?></label>
							<div class="controls">
								<input type="text" class="input-mini" name="link_width" value="<?php echo $field->link_width;?>"/>
							</div>
						</div>										
						<div class="control-group lmlink">
							<label class="control-label"><?php echo JText::_( 'LM_HEIGHT' ); ?></label>
							<div class="controls">
								<input type="text" class="input-mini" name="link_height" value="<?php echo $field->link_height;?>"/>
							</div>
						</div>
						<div class="control-group lmlink">
							<label class="control-label"><?php echo JText::_( 'LM_LINK_DETAIL' ); ?></label>
							<div class="controls">
								<select name="link_detail">
									<option value="0" <?php echo $field->link_detail=='0'?'selected="selected"':'';?>><?php echo JText::_('LM_LINK_TOLIST');?></option>
									<option value="1" <?php echo $field->link_detail=='1'?'selected="selected"':'';?>><?php echo JText::_('LM_LINK_TODETAIL');?></option>
								</select>
							</div>
						</div>		
					</div>
					<div class="clearfix"></div>
					<div class="mv_block <?php if (!in_array($field->type, array('2','6','7','10'))){echo 'hide';}?>"  style="padding-left:15px;">
						<blockquote>
					    	<p><?php echo JText::_( 'MULTIVALUES' ); ?></p>
					    </blockquote>
						<div class="span5">
							<div class="control-group">
								<label class="control-label"><?php echo JText::_( 'MULTIVALUES_ACTIONS' ); ?></label>
								<div class="controls">
									<div class="btn-group">
										<button class="btn" type="button" id="multi" onclick="javascript:addMultivalue(this)" >
											<?php echo JText::_( 'MULTIVALUES_ACTIONS_ADD' ); ?>
				                        </button>
				                        <button class="btn btn-danger" type="button" id="multi" onclick="javascript:deleteAllMultivalue(this)" >
											<?php echo JText::_( 'MULTIVALUES_ACTIONS_DELALL' ); ?>
				                        </button>		       
			                        </div>                 		                      
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" rel="tooltip"><?php echo JText::_( 'MULTIVALUES_SQL_ACTIONS' ); ?></label>
								<div class="controls">
									<div class="btn-group">
										<button class="btn" type="button" id="multisql" onclick="javascript:addSQL(this)">
											<?php echo JText::_( 'MULTIVALUES_SQL_ADD' ); ?>
				                        </button>
				                      	<button class="btn btn-danger" type="button" id="multidelsql" onclick="javascript:delSQL(this)">
											<?php echo JText::_( 'MULTIVALUES_SQL_DEL' ); ?>
				                        </button>			                        
				                        <input type="hidden" name="sqltext" class="sqltext" value='<?php echo $field->sqltext;?>'/>		                      
				                    </div>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" rel="tooltip"><?php echo JText::_( 'MULTIVALUES_CSV_ACTIONS' ); ?></label>
								<div class="controls">
									<div class="btn-group">
										<button class="btn" type="button" id="multiimportcsv" onclick="javascript:importCSV(this)">
				                        	<?php echo JText::_( 'MULTIVALUES_CSV' ); ?>
				                        </button>		                      
				                    </div>
								</div>
							</div>			
						</div>					
						<div class="span6 multivalue">
						<?php    
							if(isset($this->multivalues)){
		                      foreach($this->multivalues as &$fila){
		                	  	if ($fila->idfield!=$field->id) continue;  
		                          ?>  
							<div class="form-inline multivalue_elem">
								<label class="cb_input_label"><?php echo JText::_( 'NAME' ); ?></label>
								<input type="text" class="input-small" id="mvname" name="mvname" value="<?php echo $fila->name; ?>">							
								<label class="cb_input_label"><?php echo JText::_( 'VALUE' ); ?></label>
								<input type="text" class="input-small" id="mvval" name="mvval" value="<?php echo $fila->value; ?>">
								<input type="hidden" id="mvid" name="mvid" value="<?php echo $fila->id; ?>">
								<div class="btn-group cb_mv_buttons">
									<a class="btn cb_cmove"><i class="icon-mvmove">&nbsp;</i></a>
									<a class="btn btn-danger" onclick="javascript:mv_remove(this);"><i class="icon-mvremove">&nbsp;</i></a>
								</div>
							</div>	
							<?php }?>
						<?php }?>
						</div>
					</div>					
				</div>
	            <?php }?>
	           </div>	           
	           <div id="sqldel" class="modal hide fade csvimport form-horizontal"  style="" tabindex="-1" role="dialog" aria-labelledby="<?php echo JText::_( 'CSV_FIELD_IMPORT' ); ?>" aria-hidden="true">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
						<h3><?php echo JText::_( 'SQL_DELETE_TITLE' ); ?></h3>
					</div>
					<div class="modal-body">
						<div class="span6 alert alert-block">
							<?php echo JText::_('SQL_DELETE_TEXT')?>				
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal"><?php echo JText::_('CLOSE');?></button>
						<button class="btn btn-primary" data-dismiss="modal" onclick="javascript:delSQLQuery();"><?php echo JText::_('SQL_DELETE_ACTION')?></button>
					</div>
				</div>
	           <div id="csvimport" class="modal hide fade csvimport form-horizontal" tabindex="-1" role="dialog" aria-labelledby="<?php echo JText::_( 'CSV_FIELD_IMPORT' ); ?>" aria-hidden="true">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3><?php echo JText::_( 'CSV_FIELD_IMPORT' ); ?></h3>
					</div>
					<div class="modal-body">
						<div class="span6">
							<h3><?php echo JText::_( 'CSV_TEXT' ); ?></h3>
							<h6><?php echo JText::_( 'CSV_EXAMPLE' ); ?></h6>
						</div>
						<div class="span6">
							<textarea rows="15" style="width:470px;" class="csvdata"></textarea>				
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal"><?php echo JText::_('CLOSE');?></button>
						<button class="btn btn-primary" data-dismiss="modal" onclick="javascript:addCSVData();"><?php echo JText::_('SAVE');?></button>
					</div>
				</div>
				<div id="sqlimport" class="modal hide fade sqlimport form-horizontal" style="" tabindex="-1" role="dialog" aria-labelledby="<?php echo JText::_( 'CSV_FIELD_IMPORT' ); ?>">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3><?php echo JText::_( 'SQL_FIELD_IMPORT' ); ?></h3>
					</div>
					<div class="modal-body" style="overflow-x:hidden">
						<div class="span12">
							<h6><?php echo JText::_( 'SQL_EXAMPLE' ); ?></h6>
						</div>
						<div class="span12">
							<textarea rows="10" style="width:470px;" class="sqldata"></textarea>				
						</div>
						<div class="span12">
							<h4><span class="label label-info" style="margin-right:5px;"><?php echo JText::_( 'INFO' ); ?></span><?php echo JText::_( 'SQL_TEXT' ); ?></h4>
							<h4><span class="label label-important" style="margin-right:5px;"><?php echo JText::_( 'IMPORTANT' ); ?></span><?php echo JText::_( 'SQL_TEXT_INTERACT' ); ?></h4>
						</div>
					</div>
					<div class="modal-footer">						
						<button class="btn" data-dismiss="modal"><?php echo JText::_('CLOSE');?></button>
						<button class="btn btn-success" type="button" id="multipreviewsql" onclick="javascript:previewSQL(this)">
			                        	<?php echo JText::_( 'MULTIVALUES_SQL_PREVIEW' ); ?>
			                        </button>
						<button class="btn btn-primary" data-dismiss="modal" onclick="javascript:addSQLQuery();"><?php echo JText::_('SAVE');?></button>
					</div>
					<div id="sqlpreview" class="modal hide fade sqlimport form-horizontal" style="" tabindex="-1" role="dialog">
						<div class="modal-header">
							<button type="button" class="sqlpreview_close close" >&times;</button>
							<h3><?php echo JText::_( 'SQL_PREVIEW_RESULT' ); ?></h3>
						</div>					
						<div class="modal-body result"></div>
						<div class="modal-footer">						
							<button class="btn sqlpreview_close"><?php echo JText::_('CLOSE');?></button>
						</div>
					</div>
	           </div>
			  </div>
			  <div id="input_field_duplicate" style="display:none">
				  <div class="tab-pane fade in form-horizontal row" style="min-height:550px;padding-left:6px;">
				  <blockquote style="margin-left:26px;">
				    	<p><?php echo JText::_( 'SETTINGS' ); ?></p>
				    </blockquote>
					<div class="span5" style="padding-left:15px;">
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'NAME' ); ?></label>
							<div class="controls">
								<input class="input-medium required" name="name" id="name" type="text"/>							
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'INNERNAME' ); ?></label>
							<div class="controls">
								<input class="input-medium basic_letters" name="innername" id="innername" type="text"/>							
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'REQUIRED' ); ?></label>
							<div class="controls">
								<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
								    <button type="button" class="btn btn_no active" aria-label="0"><?php echo JText::_('LM_NO')?></button>
								    <button type="button" class="btn btn_yes" aria-label="1"><?php echo JText::_('LM_YES')?></button>
								    <input type="hidden" class="radio_hidden" name="mandatory"/>
							    </div>							
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'TYPE' ); ?></label>
							<div class="controls" id="tdtype">
								<select class="type" onchange='javascript:changeType(this);' id="type" name="type">
			                          <option value="-1"><?php echo JText::_( "SELECT" ); ?></option>
			                          <optgroup label="<?php echo JText::_("NUMBER_GROUP")?>">
				                      <option value="0"><?php echo JText::_( "NUMBER" ); ?></option>
				                      <option value="14"><?php echo JText::_( "SLIDER" ); ?></option>
				                      <option value="8"><?php echo JText::_( "PROGRESS" ); ?></option>
				                      </optgroup>
				                      <optgroup label="<?php echo JText::_("DATE_GROUP")?>">
				                      <option value="1"><?php echo JText::_( "DATE" ); ?></option>
				                      <option value="12"><?php echo JText::_( "TODAY" ); ?></option>
				                      </optgroup>
				                      <optgroup label="<?php echo JText::_("MULTIVALUE_GROUP")?>">
				                      <option value="2"><?php echo JText::_( "OPTION_LIST" ); ?></option>				                      
				                      <option value="11"><?php echo JText::_( "MULTIPLE_OPTION" ); ?></option>
				                      <option value="16"><?php echo JText::_( "MULTIPLE_OPTION_LIST" ); ?></option>
				                      <option value="10"><?php echo JText::_( "RADIOBUTTONS" ); ?></option>
				                      <option value="3"><?php echo JText::_( "YES_NO" ); ?></option>
				                      </optgroup>
				                      <optgroup label="<?php echo JText::_("TEXT_GROUP")?>">
				                      <option value="4"><?php echo JText::_( "TEXT" ); ?></option>
				                      <option value="7"><?php echo JText::_( "TEXT_AREA" ); ?></option>
				                      <option value="9"><?php echo JText::_( "HTML_EDITOR" ); ?></option>
				                      </optgroup>
				                      <optgroup label="<?php echo JText::_("OTHERS_GROUP")?>">				                              
				                      <option value="6"><?php echo JText::_( "USER" ); ?></option>
				                      <option value="15"><?php echo JText::_( "VOTE" ); ?></option>
				                      <option value="17"><?php echo JText::_( "PAYPAL_BUTTON" ); ?></option>
				                      <option value="18"><?php echo JText::_( "LIST_MANAGER_LINK" ); ?></option>
				                      <option value="19"><?php echo JText::_( "AUTOINCREMENT" ); ?></option>
				                      </optgroup>
			                    </select>							
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'ORDER' ); ?></label>
							<div class="controls">
								<input class="input-mini order" name="order"  id="order" type="text"/>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'VISIBLE' ); ?></label>
							<div class="controls">
								<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
								    <button type="button" class="btn btn_no" aria-label="0"><?php echo JText::_('LM_NO')?></button>
								    <button type="button" class="btn btn_yes active" aria-label="1"><?php echo JText::_('LM_YES')?></button>
								    <input type="hidden" class="radio_hidden" name="visible" value="1"/>
							    </div>							
							</div>
						</div>	
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'AUTOFILTER' ); ?> (1)</label>
							<div class="controls">
								<select id="autofilter" name="autofilter">
			                      <option value="-1" selected><?php echo JText::_( "SELECT" ); ?></option>
			                      <option value="0"><?php echo JText::_( "AF_SELECT" ); ?></option>
			                      <option value="1"><?php echo JText::_( "AF_TEXT" ); ?></option>
			                      <option value="2"><?php echo JText::_( "AF_MULTIPLE" ); ?></option>
			                      <option value="3"><?php echo JText::_( "AF_RANGE" ); ?></option>
			                    </select>
							</div>
						</div>	
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'SHOW_ORDER' ); ?></label>
							<div class="controls">
								<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
								    <button type="button" class="btn btn_no active" aria-label="0"><?php echo JText::_('LM_NO')?></button>
								    <button type="button" class="btn btn_yes" aria-label="1"><?php echo JText::_('LM_YES')?></button>
								    <input type="hidden" class="radio_hidden" name="showorder"/>
							    </div>							
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'EXPORTABLE' ); ?></label>
							<div class="controls">
								<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
								    <button type="button" class="btn btn_no active" aria-label="0"><?php echo JText::_('LM_NO')?></button>
								    <button type="button" class="btn btn_yes" aria-label="1"><?php echo JText::_('LM_YES')?></button>
								    <input type="hidden" class="radio_hidden" name="exportable"/>
							    </div>							
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'SEARCHABLE' ); ?></label>
							<div class="controls">
								<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
								    <button type="button" class="btn btn_no active" aria-label="0"><?php echo JText::_('LM_NO')?></button>
								    <button type="button" class="btn btn_yes" aria-label="1"><?php echo JText::_('LM_YES')?></button>
								    <input type="hidden" class="radio_hidden" name="searchable" />
							    </div>							
							</div>
						</div>
						<div class="control-group">
								<label class="control-label"><?php echo JText::_( 'LM_SEARCH_TYPE' ); ?></label>
								<div class="controls">
									<select name="searchtype">
										<option value="0" selected="selected"><?php echo JText::_( 'LM_SEARCH_TYPE_BROAD' ); ?></option>
										<option value="1"><?php echo JText::_( 'LM_SEARCH_TYPE_EXACT' ); ?></option>
									</select>				
								</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_( 'BULK' ); ?></label>
							<div class="controls">
								<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
								    <button type="button" class="btn btn_no active" aria-label="0"><?php echo JText::_('LM_NO')?></button>
								    <button type="button" class="btn btn_yes" aria-label="1"><?php echo JText::_('LM_YES')?></button>
								    <input type="hidden" class="radio_hidden" name="bulk" />
							    </div>							
							</div>
						</div>						
					</div>
					<div class="span6" style="padding-left:15px;">
						<div class="control-group decimal">
							<label class="control-label" rel="tooltip"><?php echo JText::_( 'DECIMALS' ); ?></label>
							<div class="controls">
								<input class="input-mini validate-numeric" name="decimal" type="text"/>							
							</div>
						</div>
						<div class="control-group total">
							<label class="control-label"><?php echo JText::_( 'TOTAL' ); ?></label>
							<div class="controls">
								  <select id="total" name="total">
			                      <option value="-1" selected><?php echo JText::_( "SELECT" ); ?></option>
			                      <option value="0"><?php echo JText::_( "SUM" ); ?></option>
			                      <option value="1"><?php echo JText::_( "SUM_CEIL" ); ?></option>
			                      <option value="2"><?php echo JText::_( "SUM_FLOOR" ); ?></option>
			                      <option value="3"><?php echo JText::_( "SUM_ROUND" ); ?></option>
			                      <option value="4"><?php echo JText::_( "MAX" ); ?></option>
			                      <option value="5"><?php echo JText::_( "MIN" ); ?></option>
			                      <option value="6"><?php echo JText::_( "MEAN" ); ?></option>                      
			                      </select>													
							</div>
						</div>
						<div class="control-group validation">
							<label class="control-label"><?php echo JText::_( 'VALIDATION' ); ?></label>
							<div class="controls">
								<select id="validate">
			                      <option value="-1" selected><?php echo JText::_( "SELECT" ); ?></option>
			                      <option value="alpha"><?php echo JText::_( "ONLY_LETTERS" ); ?></option>
			                      <option value="alphanum"><?php echo JText::_( "ONLY_LETTERS_NUMBERS" ); ?></option>
			                      <option value="email"><?php echo JText::_( "EMAIL" ); ?></option>
			                      <option value="url"><?php echo JText::_( "URL" ); ?></option>
			                    </select>
							</div>
						</div>
						<div class="control-group limit0">
							<label class="control-label"><?php echo JText::_( 'DOWN_LIMIT' ); ?></label>
							<div class="controls">
								<input class="input-medium" name="limit0" id="limit0" type="text"/>
							</div>
						</div>
						<div class="control-group limit1">
							<label class="control-label"><?php echo JText::_( 'UP_LIMIT' ); ?></label>
							<div class="controls">
								<input class="input-medium" name="limit1" id="limit1" type="text"/>
							</div>
						</div>
						<div class="control-group default">
							<label class="control-label"><?php echo JText::_( 'DEFAULT' ); ?></label>
							<div class="controls">
								<input class="input-medium" name="defaulttext" id="defaulttext" type="text"/>
							</div>
						</div>
						<div class="control-group placeholder">
							<label class="control-label"><?php echo JText::_( 'PLACEHOLDER' ); ?></label>
							<div class="controls">
								<input class="input-medium" name="placeholder" id="placeholder" type="text"/>
							</div>
						</div>	
						<div class="control-group css">
							<label class="control-label"><?php echo JText::_( 'CSS' ); ?></label>
							<div class="controls">
								<input class="input-medium" id="css" name="css" type="text" />							
							</div>
						</div>	
						<div class="control-group readmore">
							<label class="control-label"><?php echo JText::_( 'READMORE' ); ?></label>
							<div class="controls">
								<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
								    <button type="button" class="btn btn_no active" aria-label="0"><?php echo JText::_('LM_NO')?></button>
								    <button type="button" class="btn btn_yes" aria-label="1"><?php echo JText::_('LM_YES')?></button>
								    <input type="hidden" class="radio_hidden" name="readmore" value="0"/>
							    </div>							
							</div>
						</div>	
						<div class="control-group readmore">
							<label class="control-label"><?php echo JText::_( 'READMORE_WORD_COUNT' ); ?></label>
							<div class="controls">
								<input class="input-mini" id="readmore_word_count" name="readmore_word_count" type="text" />							
							</div>
						</div>	
						<div class="control-group cardview">
							<label class="control-label"><?php echo JText::_( 'CARDVIEW' ); ?></label>
							<div class="controls">
								<div class="btn-group cb_radio" data-toggle="buttons-radio">							    
								    <button type="button" class="btn btn_no active aria-label="0"><?php echo JText::_('NORMAL')?></button>
								    <button type="button" class="btn btn_yes aria-label="1"><?php echo JText::_('LARGE')?></button>
								    <input type="hidden" class="radio_hidden" name="cardview" value="0"/>
							    </div>							
							</div>
						</div>
						<div class="lmlink">
							<h4><?php echo JText::_( 'LM_LINK_TITLE' ); ?></h4>							
						</div>
						<div class="control-group lmlink">
							<label class="control-label"><?php echo JText::_( 'LM_URL_LINK' ); ?></label>
							<div class="controls">
								<input type="text" class="input-large" name="link_url"/>												
							</div>
						</div>
						<div class="control-group lmlink">
							<label class="control-label"><?php echo JText::_( 'LM_LIST_LINK' ); ?></label>
							<div class="controls">							
								<select class="lmlinkselect">
									<option value="-1"><?php echo JText::_('LM_SELECT')?></option>
									<?php foreach ($this->alllist as $list){?>
									<option value="<?php echo $list->id;?>"><?php echo $list->name;?></option>
									<?php }?>
								</select>		
								<select class="fieldlist"></select>
								<input type="hidden" name="link_id_original"/>
								<input type="hidden" name="link_id"/>												
							</div>
						</div>
						<div class="control-group lmlink">
							<label class="control-label"><?php echo JText::_( 'LM_LINK_TYPE' ); ?></label>
							<div class="controls">
								<select name="link_type">
									<option value="-1"><?php echo JText::_('LM_SAME_WINDOW');?></option>
									<option value="0"><?php echo JText::_('LM_NEW_WINDOW');?></option>
									<option value="1"><?php echo JText::_('LM_POPUP');?></option>
								</select>
							</div>
						</div>								
						<div class="control-group lmlink">
							<label class="control-label"><?php echo JText::_( 'LM_WIDTH' ); ?></label>
							<div class="controls">
								<input type="text" class="input-mini" name="link_width"/>
							</div>
						</div>										
						<div class="control-group lmlink">
							<label class="control-label"><?php echo JText::_( 'LM_HEIGHT' ); ?></label>
							<div class="controls">
								<input type="text" class="input-mini" name="link_height"/>
							</div>
						</div>
						<div class="control-group lmlink">
							<label class="control-label"><?php echo JText::_( 'LM_LINK_DETAIL' ); ?></label>
							<div class="controls">
								<select name="link_detail">
									<option value="0"><?php echo JText::_('LM_LINK_TOLIST');?></option>
									<option value="1"><?php echo JText::_('LM_LINK_TODETAIL');?></option>
								</select>
							</div>
						</div>		
					</div>
					<div class="clearfix"></div>
					<div class="mv_block hide" style="padding-left:15px;">
						<blockquote>
					    	<p><?php echo JText::_( 'MULTIVALUES' ); ?></p>
					    </blockquote>
						<div class="span5">
							<div class="control-group">
								<label class="control-label"><?php echo JText::_( 'MULTIVALUES_ACTIONS' ); ?></label>
								<div class="controls">
									<div class="btn-group">
										<button class="btn" type="button" id="multi" onclick="javascript:addMultivalue(this)" >
											<?php echo JText::_( 'MULTIVALUES_ACTIONS_ADD' ); ?>
				                        </button>
				                        <button class="btn btn-danger" type="button" id="multi" onclick="javascript:deleteAllMultivalue(this)" >
											<?php echo JText::_( 'MULTIVALUES_ACTIONS_DELALL' ); ?>
				                        </button>		       
			                        </div>                 		                      
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" rel="tooltip"><?php echo JText::_( 'MULTIVALUES_SQL_ACTIONS' ); ?></label>
								<div class="controls">
									<div class="btn-group">
										<button class="btn" type="button" id="multisql" onclick="javascript:addSQL(this)">
											<?php echo JText::_( 'MULTIVALUES_SQL_ADD' ); ?>
				                        </button>
				                      	<button class="btn btn-danger" type="button" id="multidelsql" onclick="javascript:delSQL(this)">
											<?php echo JText::_( 'MULTIVALUES_SQL_DEL' ); ?>
				                        </button>			                        
				                        <input type="hidden" name="sqltext" class="sqltext"/>		                      
				                    </div>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" rel="tooltip"><?php echo JText::_( 'MULTIVALUES_CSV_ACTIONS' ); ?></label>
								<div class="controls">
									<div class="btn-group">
										<button class="btn" type="button" id="multiimportcsv" onclick="javascript:importCSV(this)">
				                        	<?php echo JText::_( 'MULTIVALUES_CSV' ); ?>
				                        </button>		                      
				                    </div>
								</div>
							</div>			
						</div>					
						<div class="span6 multivalue">
						</div>
					</div>					
				</div>				
			  </div>	
			  <div class="row-fluid">
	           		<div class="span8 offset2 alert">
					  <strong>(1)</strong> <?php echo JText::_('FILTER_PLACEHOLDER');?> 
					</div>
	           </div>		             
		</div>		
		<div id="keycolumns" class="tab-pane form-horizontal">
			<h4><?php echo JText::_( 'SM_KEY_DESC' ); ?></h4>
			<div style="margin-left:8px;min-width:150px;float:left;">
				<h4><?php echo JText::_( 'FIELDS' ); ?></h4>
		        <ul id="fields1" class="connectedSortable" style="border:1px solid #ccc;padding:4px;">
		        <?php $keyfields=($this->item['keyfields']!=null&&count($this->item['keyfields'])>0)?explode(',',$this->item['keyfields']):array();
		        	$excludeType=array(9,15,17,18);
		        	$fldsarr=array(); 
		        	foreach ($this->fields as $field):
		        	$fldsarr[$field->id]=$field->name;
		        	if (!in_array($field->id,$keyfields) && !in_array($field->type,$excludeType)):?>
					<li class="ui-state-default" attr-id="<?php echo $field->id;?>"><?php echo $field->name;?></li>
					<?php endif;?>
				<?php endforeach;?>
				</ul>
			</div>
			<div style="margin-left:8px;float:left;">	
				<h4><?php echo JText::_( 'FIELDS_KEY' ); ?></h4>
				<ul id="fields2" class="connectedSortable" style="border:1px solid #ccc;padding:4px;">
				<?php foreach ($keyfields as $key):
					if (array_key_exists($key,$fldsarr)):?>					
						<li class="ui-state-default" attr-id="<?php echo $key;?>"><?php echo $fldsarr[$key];?></li>					
				<?php endif; 
				endforeach;?>
				</ul>
			</div>        
		</div>
		
    <div id="specialfilters" class="tab-pane form-horizontal">
			<h4><?php echo JText::_( 'SM_SPECIAL_FILTERS_DESC' ); ?></h4>
			<div>
        <select id="specialfilterscombo">
        <option value="1"><?php echo JText::_( 'SM_DATE_MONTHLY' ); ?></option>
        </select>
        <button id="sfadd"><?php echo JText::_( 'SM_SPECIAL_FILTERS_ADD' ); ?></button>
			</div>     
      <?php
          $fields=array(); 
          foreach ($this->fields as $field): 
            $fields[$field->type][]=$field; 
          endforeach;          
        ?>
      <div id="sf" style="margin-top:15px">
        <h3><?php echo JText::_( 'SM_SPECIAL_FILTERS_ITEMS' ); ?></h3>
        <?php
        $sf=json_decode($this->item['specialfilters']);
        if ($sf!=null && count($sf)>0):	        
	        foreach($sf as $sfitem):
	          switch($sfitem->sftype):
	            case '1': // Date: monthly
	            ?>
	            <div class="sfitem">
	              <button type="button" class="btn btn-danger sfitem-delete"><?php echo JText::_( 'SM_SPECIAL_FILTERS_DELETE' ); ?></button>
	              <input type="hidden" name="sfid" value="<?php echo $sfitem->sfid;?>">
	              <input type="hidden" name="sftype" value="1">
	              <?php echo JText::_( 'SM_SPECIAL_FILTERS_1_SELECT_DATE' ); ?>
	              <select name="idfield">   
	            <?php foreach($fields[1] as $field):?>
	                    <option value="<?php echo $field->id;?>" <?php echo ($field->id==$sfitem->idfield)?'selected="selected"':''; ?>><?php echo $field->name;?></option>
	            <?php endforeach;?>
	              </select>
	              <?php echo JText::_( 'SM_SPECIAL_FILTERS_1_LOAD_CURRENT_MONTH' ); ?>
	              <div class="btn-group cb_radio" data-toggle="buttons-radio">							    
					    <button type="button" class="btn btn_no <?php echo ($sfitem->loadcurrent==0)?'active':''; ?>" aria-label="0" ><?php echo JText::_('NO')?></button>
					    <button type="button" class="btn btn_yes <?php echo ($sfitem->loadcurrent==1)?'active':''; ?>" aria-label="1"><?php echo JText::_('YES')?></button>
					    <input type="hidden" class="radio_hidden" name="loadcurrent" value="<?php echo ($sfitem->loadcurrent==1)?'1':'0'; ?>"/>
				    </div>
	            </div>
	          <?php
	              break;
	          endswitch;
	        endforeach;
        endif;
        ?>
      </div>   
      
      <div id="sf1" style="display:none">
        <div class="sfitem">
        <button type="button" class="btn btn-danger sfitem-delete"><?php echo JText::_( 'SM_SPECIAL_FILTERS_DELETE' ); ?></button>
        <input type="hidden" name="sfid" value="-1">
        <input type="hidden" name="sftype" value="1">
        <?php echo JText::_( 'SM_SPECIAL_FILTERS_1_SELECT_DATE' ); ?>
        <select name="idfield">   
      <?php foreach($fields[1] as $field):?>
              <option value="<?php echo $field->id;?>"><?php echo $field->name;?></option>
      <?php endforeach;?>
        </select>
        <?php echo JText::_( 'SM_SPECIAL_FILTERS_1_LOAD_CURRENT_MONTH' ); ?>
	              <div class="btn-group cb_radio" data-toggle="buttons-radio">							    
					    <button type="button" class="btn btn_no active aria-label="0" ><?php echo JText::_('NO')?></button>
					    <button type="button" class="btn btn_yes aria-label="1"><?php echo JText::_('YES')?></button>
					    <input type="hidden" class="radio_hidden" name="loadcurrent" value="0"/>
				    </div>
        </div>
      </div> 
		</div>
</div>	
</div>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="id" value="<?php echo $this->item['id']; ?>" />
<input type="hidden" name="cid[]" value="<?php echo $this->item['id']; ?>" />
<input type="hidden" name="idlisting" value="<?php echo $this->item['id']; ?>" />
<input type="hidden" name="option" value="com_listmanager" />
<input type="hidden" name="task" value="store" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="listing" />
<input type="hidden" name="check" value="post"/>
<input type="hidden" name="class" value="0"/>
<?php echo JHTML::_( 'form.token' ); ?>
</form>