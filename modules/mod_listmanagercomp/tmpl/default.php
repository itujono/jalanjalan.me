<?php defined('_JEXEC') or die('Restricted access'); // no direct access 
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
$module_name="mod_listmanagercomp";
$scriptbase=JURI::base().'modules/'.$module_name.'/assets/js/';
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'modules/'.$module_name.'/assets/css/default.css','text/css','screen');

//$document->addScript(JURI::base(true).'/index.php?option=com_listmanager&controller=assets&task=compjs&format=raw');
 
?>
<script src="<?php echo JURI::base(true)?>/index.php?option=com_listmanager&controller=assets&task=compjs&format=raw"></script>
<div id="lm_wrapper" class="<?php echo $params->get('moduleclass');?>">
	<div class="lm_compmod lm_bootstrap">
		<span class="lm_hidden_data" name="numcomp" value="<?php echo $params->get('numcomp');?>"></span>
		<span class="lm_hidden_data" name="comptable" value="<?php echo $params->get('comptable');?>"></span>
		<span class="lm_hidden_data" name="draghelper" value="<?php echo JText::_('LMCOMP_COMPARE_DRAG_HELPER')?>"></span>
		<span class="lm_hidden_data" name="delete" value="<?php echo JText::_('LMCOMP_DELETE')?>"></span>
		<span class="lm_hidden_data" name="expand" value="<?php echo JText::_('LMCOMP_EXPAND')?>"></span>
		<span class="lm_hidden_data" name="seebutton" value="<?php echo $params->get('seebutton');?>"></span>
		<span class="lm_hidden_data" name="seetext" value="<?php echo $params->get('seetext');?>"></span>
		<span class="lm_hidden_data" name="seeexpand" value="<?php echo $params->get('seeexpand');?>"></span>
		<span class="lm_hidden_data" name="seeremove" value="<?php echo $params->get('seeremove');?>"></span>
		<div class="lm_compare_tools">
			<div class="btn-group">
				<button class="btn btn-link lm_comp_compare"><?php echo JText::_('LMCOMP_COMPARE')?></button>
			</div>
		</div>
		<div class="lm_droppable_wrapper">
			<div class="lm_droppable" id="lm_droppable">
			 	<div>
					<h5 class="lm_comp_placeholder">Add your items here</h5>
				</div>
			</div>
		</div>
		<div id="lm_advise_colums" class="lm_advise_colums" title="<?php echo JText::_('LMCOMP_COLUMNS_TITLE');?>">
			<p><?php echo JText::_('LMCOMP_COLUMNS');?></p>
		</div>
		<div id="lm_compare" class="lm_compare lm_bootstrap" title="<?php echo $params->get('comptabletitle');?>">
			<div id="lm_wrapper" class="lm_comp_wrapper">
			</div>
		</div>
	</div>
</div>