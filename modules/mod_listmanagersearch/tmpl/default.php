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
$module_name="mod_listmanagersearch";
$scriptbase=JURI::base().'modules/'.$module_name.'/assets/js/';
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'modules/'.$module_name.'/assets/css/default.css','text/css','screen');

$document->addScript(JURI::base(true).'/index.php?option=com_listmanager&controller=assets&task=searchjs&format=raw');

?>
<div id="lm_wrapper" class="<?php echo $params->get('moduleclass');?>">	
	<div class="lm_searchmod lm_bootstrap">
		<span class="lm_hidden_data" name="includebutton" value="<?php echo $params->get('includebutton');?>"></span>
		<span class="lm_hidden_data" name="hidesearchonlist" value="<?php echo $params->get('hidesearchonlist');?>"></span>				
		<?php if ($params->get('includetext')!='0'){ ?>
		<p><?php echo JText::_('LMS_PRETEXT')?></p>		
		<?php } ?>
		<div class="form-search">			
		  	<!-- /input-group -->
			<?php if ($params->get('includebutton')!='0'){ ?>
			<div class="input-group">
			<?php } ?>
		    	<input type="text" class="lms_search_input form-control" placeholder="<?php echo JText::_('LMS_SEARCH_PLACEHOLDER')?>">
		    	<?php if ($params->get('includebutton')!='0'){ ?>
		    	<span class="input-group-btn">
		    		<button type="button" class="lms_search btn btn btn-default"><?php echo JText::_('LMS_SEARCH_BUTTON')?></button>	
		    	</span>	    	
		    </div>
		    <?php } ?>
	    </div>
	</div>
</div>