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
 
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'modules/mod_listmanager/assets/css/jquery.cleditor.css','text/css','screen');

?>

<div id="<?php echo $seed;?>cb_form" class="lm_bootstrap" style="display:none;text-align:left;" title="<?php echo $listing['name']; ?>">

<div id="lm_wrapper" class="panel-default">
  <div class="panel-body">
	<form action="#" method="get" name="<?php echo $seed;?>list_adminForm" id="<?php echo $seed;?>list_adminForm" class="form-validate">
	<div id="<?php echo $seed;?>divPrint" class="divPrint">
	<?php
	  $arrkeys=array();
	  $arrvals=array();
	  foreach ($data as $field) {
	  	$arrkeys[]='##'.$field->innername.'##';
		$arrvals[]=$helper->getFieldHTML($data,false,$allfields[$field->id],$seed,$listing,$acl);
	  }
	  $first_replace=str_replace($arrkeys, $arrvals, $listing['layout']);
	  // Search for not in layout    
	  foreach ($data as $field) {
	  	if(strrpos($first_replace,'name="fld_'.$field->id.'"')===false && strrpos($first_replace,'name="fld_'.$field->id.'[]"')===false){
	  		$first_replace.=$helper->createHidden($field);
	  	}
	  }
	  echo $first_replace;
	?>
	</div>
	<table id="<?php echo $seed;?>list_tableform" class="lm_list_tableform">
	<tr class="lm_tdseparation">
	  <td colspan="3"></td>
	</tr>
	<tr>
	  <td></td>  
	  <td class='lm_value'>
		  
	  </td>
	 <!-- <td class='lm_asterisk_text'><span class="lm_asterisk">*</span><?php echo JText::_('LM_ASTERISK_TEXT')?></td>-->
	</tr>
	</table>
	<nav class="navbar nav-justified " role="navigation" style="margin-bottom:0px;">	
		<div style="text-align:center;">
		<?php if (isset($_isFormModule)):?>
			<button type="button" class="btn btn-default navbar-btn btn-sm" id="<?php echo $seed;?>resetform" name="resetform"><?php echo JText::_('LM_RESET')?></button>
		<?php else:?>
			<button type="button" class="btn btn-default navbar-btn btn-danger btn-sm" id="<?php echo $seed;?>cancelform" name="cancelform"><?php echo JText::_('LM_CANCEL')?></button>
		<?php endif;?>		
		<button type="button" class="btn btn-default navbar-btn btn-primary btn-sm" id="<?php echo $seed;?>saveform" name="saveform" ><?php echo JText::_('LM_SAVE')?></button>
		<?php if ($params->get('showprintform')&&$listing['modalform']=='0'):?>
		<button type="button" class="btn btn-default navbar-btn btn-sm" id="<?php echo $seed;?>printform" name="printform" ><?php echo JText::_('LM_PRINT')?></button>
		<?php endif;?>
		</div>	
	</nav>
		
	<!-- 
	<div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix lm_buttonbar">
		  <input type="button" id="<?php echo $seed;?>cancelform" name="cancelform" value="<?php echo JText::_('LM_CANCEL')?>" class="lm_button_cancel hasTip" title="<?php echo JText::_('LM_CANCEL')?> :: <?php echo JText::_('LM_CANCEL_TOOLTIP')?>"/>
		  <input type="button" id="<?php echo $seed;?>saveform" name="saveform" value="<?php echo JText::_('LM_SAVE')?>" class="lm_button_save hasTip" title="<?php echo JText::_('LM_SAVE')?> :: <?php echo JText::_('LM_SAVE_TOOLTIP')?>"/>
		  </div>-->
	<input type="hidden" name="check" value="post"/> 
	<input type="hidden" name="option" value="com_listmanager"/>
	<input type="hidden" name="controller" value="serverpages"/>
	<input type="hidden" name="format" value="raw"/>
	<input type="hidden" name="id" value="<?php echo $params->get('prefsids');?>"/>
	<input type="hidden" name="idrecord" value=""/>
	<input type="hidden" name="task" value="show"/>
	
	<input type="hidden" name="access_type" value="<?php echo $params->get('access_type');?>"/>
	<input type="hidden" name="user_on" value="<?php echo  JFactory::getUser()->id;?>"/>
	<input type="hidden" name="from" value="0"/>
	<input type="hidden" name="filter" value=""/>
	<input type="hidden" name="sort" value=""/>
	<input type="hidden" name="recalc" value="0"/>
	<input type="hidden" name="typemodule" value="1"/>
	<?php echo JHTML::_( 'form.token' ); ?>	
	<div class="lmsshide">
		<input type="text" class="lmsshide" name="<?php echo $randSS;?>" value=""/>
	</div>
	</form>
	</div>
</div>	
</div>
 