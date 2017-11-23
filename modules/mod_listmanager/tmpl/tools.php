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
$targetrand=rand(1,9999999);

$currentUser = JFactory::getUser();
$isroot = $currentUser->get('isRoot');
?>
<script>
/*
LMJQ(document).ready(function($) {
	$('.collapse.in').height('auto !important;');
	$('.tool-bottom-wrapper').find(".navbar-toggle").click(function(event) {  	
	  	event.preventDefault()    
	    var target=LMJQ(event.target).closest('.navbar-toggle');
	    var datatarget=LMJQ(target).attr('data-target');
	    $(datatarget).addClass('in');
	    event.stopPropagation();
	  });    
});
*/
</script>
<style>
<!--
#lm_wrapper .input-group[class*="col-"].lm-marginleft2,
#lm_wrapper .btn-group.lm-marginleft2{
	padding-left:2px;
}
-->
</style>
<nav class="navbar navbar-default" id="lm_tool" role="navigation">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#lm-navbar-collapse_<?php echo $targetrand; ?>">
      <span class="sr-only"><?php echo JText::_('LM_TOGGLE_NAVIGATION');?></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>    
  </div>
  <div class="collapse navbar-collapse container-fluid" id="lm-navbar-collapse_<?php echo $targetrand; ?>">
  	<ul class="nav row">
  		<?php //if($params->get('viewonly')!='1' && strrpos($acl,'#add#')!==false ):?>
  		<li class="col-md-3 col-sm-4 col-xs-8 lm_search">
  		<?php /*else:?>
  		<li class="col-xs-12 col-sm-8 col-sm-offset-2 lm_search">
  		<?php endif;*/?>
	  		<div class="input-group">
		      <input type="text" id="<?php echo $seed;?>filtertext" class="form-control input-sm <?php echo $seed;?>filtertext" value="<?php echo $params->get('filter_by');?>">
		      <span class="input-group-btn">
		        <button class="btn btn-default btn-sm" type="button"><span class="glyphicon glyphicon-search"></span> <?php echo JText::_('LM_SEARCH')?></button>
		      </span>
		    </div>  
  		</li>
  			<?php if($params->get('viewonly')!='1' && strrpos($acl,'#add#')!==false ):?>
	  		<li class="col-md-1 col-sm-2 col-xs-4">
	  			<div class="btn-group navbar-right">		  		
						<button type="button" class="btn btn-default btn-sm <?php echo $seed;?>btadd" id="<?php echo $seed;?>btadd">
				  			<span class="glyphicon glyphicon-plus"></span> <?php echo JText::_('LM_ADD')?>
						</button>
				</div>		
	  		</li> 
	  		<?php endif;?> 			
	  		<li class="col-md-3 col-sm-6 col-xs-12">
	  			<div class="btn-group navbar-right lm-marginleft2 col-xs-4">
			  		<button type="button" class="btn btn-default btn-sm" id="<?php echo $seed;?>btshowAll" onclick="javascript:showAll<?php echo $seed;?>(this);">
			  			<span class="glyphicon glyphicon-fullscreen"></span> <?php echo JText::_('LM_SHOW_ALL')?>
					</button>
				</div>
	  			<div class="input-group lm-marginleft2 col-xs-8 navbar-right">
			      <input type="text" class="form-control input-sm span1 <?php echo $seed;?>porpag" id="<?php echo $seed;?>porpag" size="3" name="rp" value="<?php echo $params->get('items_view');?>"/>
			      <span class="input-group-btn">
			        <button class="btn btn-default btn-sm" type="button"><?php echo JText::_('LM_SHOW')?></button>
			      </span>
			    </div>				
	  		</li>
	  		<li class="col-md-5 col-sm-12 col-xs-12">
	  			<div class="btn-group navbar-right">
				  <button type="button" class="btn btn-default btn-sm" onclick="javascript:goToPage<?php echo $seed;?>(1)"><span class="glyphicon glyphicon-fast-backward"></span>&nbsp;</button>
				  <button type="button" class="btn btn-default btn-sm" onclick="javascript:changePage<?php echo $seed;?>(-1)"><span class="glyphicon glyphicon-step-backward"></span>&nbsp;</button>
				  <button type="button" class="btn btn-default btn-sm disabled"><span id="<?php echo $seed;?>pagactual" class="<?php echo $seed;?>pagactual">1</span><strong>/</strong><span id="<?php echo $seed;?>pagtotales" class="<?php echo $seed;?>pagtotales"></span></button>
				  <button type="button" class="btn btn-default btn-sm" onclick="javascript:changePage<?php echo $seed;?>(1)"><span class="glyphicon glyphicon-step-forward"></span>&nbsp;</button>
				  <button type="button" class="btn btn-default btn-sm" onclick="javascript:goToPage<?php echo $seed;?>(0)"><span class="glyphicon glyphicon-fast-forward"></span>&nbsp;</button>			  			  
				</div>
	  		</li>
	  		<?php /*else:?>
	  		<li>
		  		<input type="hidden" class="form-control input-sm span1 <?php echo $seed;?>porpag" id="<?php echo $seed;?>porpag" size="3" name="rp" value="<?php echo $params->get('items_view');?>"/>
		  		<span id="<?php echo $seed;?>pagactual" class="<?php echo $seed;?>pagactual" style="display:none">1</span>
		  		<span id="<?php echo $seed;?>pagtotales" class="<?php echo $seed;?>pagtotales" style="display:none"></span>
	  		</li>
	  		<?php endif;*/ ?>	  			  	
  	</ul>
  </div>
</nav>
<div class="lm_spinner" id="<?php echo $seed;?>spinner"></div>


