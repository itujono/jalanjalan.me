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

$document =JFactory::getDocument();                                                                     
$css=$this->__getCSS();          
foreach ($css as $style){                                                        
	$document->addStyleSheet($style);
}
$js=$this->__getJS();
foreach ($js as $jscript){                                                        
	?><script src="<?php echo $jscript;?>"></script><?php 
}
?>
<?php echo $this->__getHelp('LOAD_DATA');?>
<script>
jQuery(document).ready(function($){
	$('#tab_divider').click(function(){
		$('#appendedPrependedInput').val('#TAB#');
	});
});
</script>
<div class="cb_table_wrapper">
<form action="index.php" method="post" name="adminForm" class="form-horizontal" enctype="multipart/form-data" id="adminForm">
	
	<div class="control-group">
		<label class="control-label" for="inputEmail"><?php echo JText::_( 'DIVIDERS' ); ?></label>
		<div class="controls">
			    <div class="input-prepend input-append" style="margin-right:65px;">
				    <span class="add-on"><?php echo JText::_( 'FIELD_DIVIDER' ); ?></span>				    
				    <input class="input-mini" id="appendedPrependedInput" type="text" name="field_separator">
				    <span class="add-on" id="tab_divider" style="cursor:pointer;"><?php echo JText::_( 'FIELD_TAB_DIVIDER' ); ?></span>
			    </div>
			    <div class="input-prepend">
				    <span class="add-on"><?php echo JText::_( 'ROW_DIVIDER' ); ?></span>				    
				    <input class="input-mini" id="appendedPrependedInput" type="text" name="row_separator">
			    </div>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="startAt"><?php echo JText::_( 'START_LINE' ); ?></label>
		<div class="controls">
			<input class="input-mini" id="startAt" type="text" name="start_at" value="0">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputPassword"><?php echo JText::_( 'FORMAT' ); ?></label>
		<div class="controls">
			<p class="text-info" style="margin-top: 4px;font-size: 14px;">
			<?php foreach ($this->fields as $field){ 
 				echo $field->name.";";
			 } ?>
			 </p>
		</div>
	</div>
	<!-- <div class="control-group">
		<label class="control-label" for="inputPassword"><?php echo JText::_( 'VALUES' ); ?></label>
		<div class="controls">
			<textarea name="csvdata" rows="25" cols="100"></textarea>
		</div>
	</div> -->
	<div class="control-group">
		<label class="control-label" for="inputUpload"><?php echo JText::_( 'UPLOADFILE' ); ?></label>
		<div class="controls">
			<input type="file" name="fileupload">
		</div>
	</div>
	
	<input type="hidden" name="option" value="com_listmanager" />
	<input type="hidden" name="task" value="saveloaddata" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="listing" />
	<input type="hidden" name="idlisting" value="<?php echo JRequest::getVar( 'idlisting')?>"/>
	<input type="hidden" name="cid" value="<?php echo JRequest::getVar( 'idlisting')?>"/>
</form>
</div>

