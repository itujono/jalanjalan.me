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
$item=$this->item;
?>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="cb_table_wrapper">	
	<div class="form-horizontal">	
		<div class="control-group">
			<label class="control-label"><?php echo JText::_("LM_UPLOAD_RTF")?></label>
			<div class="controls">		
			  <input type="file" id="listrtf" name="listrtf">
		      <?php if (!is_null($item)&&isset($item['listrtf'])):?>
		      <label><h4><?php echo JText::_('RTF_FILE_UPLOADED');?></h4><a href="<?php echo JURI::root().DS."media".DS.'com_calcbuilder'.DS.$item['listrtf'];?>"> <?php echo $item['listrtf'];?></a></label>
		      <?php endif;?>		
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"></label>
			<div class="controls"><h4><?php echo JText::_("LM_RTF_VARIABLESLIST")?></h4></div>
		</div>
		<div class="control-group">
			<label class="control-label"></label>
			<div class="controls">		
			  <ul>
			  	<li><?php echo JText::_("LM_RTF_USERNAME")?> : ##u_username## </li>
			  	<li><?php echo JText::_("LM_RTF_NAME")?> : ##u_name##</li>
			  	<li><?php echo JText::_("LM_RTF_USERID")?> : ##u_id##</li>
			  	<li><?php echo JText::_("LM_RTF_USEREMAIL")?> : ##u_email##</li>
			  </ul>		
			</div>
		</div>
	</div>
</div>
<input type="hidden" name="id" value="<?php echo $this->item['id']; ?>" />
<input type="hidden" name="idlisting" value="<?php echo $this->item['id']; ?>" />
<input type="hidden" name="option" value="com_listmanager" />
<input type="hidden" name="task" value="storeListRTF" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="listing" />
<input type="hidden" name="check" value="post"/>
</form>

