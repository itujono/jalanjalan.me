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
<div class="cb_table_wrapper">
<form action="index.php" method="post" name="adminForm" class="form-horizontal" id="adminForm"  enctype="multipart/form-data">
	
	<div class="control-group">
		<label class="control-label" for="inputFile"><?php echo JText::_( 'FILE_IMPORT' ); ?></label>
		<div class="controls">
			<input class="input-large" id="inputFile" type="file" name="import">			
		</div>
	</div>	
<input type="hidden" name="option" value="com_listmanager" />
<input type="hidden" name="task" value="saveimport" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="listing" />
</form>
</div>

