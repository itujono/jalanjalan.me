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

?>
<div id="<?php echo $seed;?>cb_bulkform" class="lm_bootstrap" style="display:none;text-align:left;" title="<?php echo $listing['name']; ?>">
<div id="lm_wrapper">
<div class="panel-default">
  <div class="panel-body">
	<div class="col-sm-12">
	<?php
	  foreach ($data as $field):
		if ($field->bulk=='1'):
		?>
		<dl class="dl-horizontal"><dt><?php echo $field->name;?></dt>
		<?php 
	  		$input=$helper->getFieldHTML($data,false,$allfields[$field->id],$seed,$listing,$acl,true);
	  		?><dd><?php echo $input;?></dd></dl><?php 
		endif;
	  endforeach;
	?>
	</div>

<div style="clear:both"></div>
	<nav class="navbar nav-justified " role="navigation" style="margin-bottom:0px;">	
		<div style="text-align:center;">
		<button type="button" class="btn btn-default navbar-btn btn-info btn-sm" id="<?php echo $seed;?>bulkcancelform" name="bulkcancelform"><?php echo JText::_('LM_CANCEL')?></button>
		<button type="button" class="btn btn-default navbar-btn btn-primary btn-sm" id="<?php echo $seed;?>bulksaveform" name="bulksaveform" ><?php echo JText::_('LM_SAVE')?></button>
		<button type="button" class="btn btn-default navbar-btn btn-danger btn-sm" id="<?php echo $seed;?>bulkdeleteform" name="bulkdeleteform" ><?php echo JText::_('LM_DELETE')?></button>
		</div>	
	</nav>		
	</div>
</div>	
</div>
</div>