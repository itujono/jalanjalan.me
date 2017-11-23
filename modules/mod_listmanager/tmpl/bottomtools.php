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
<div <?php echo !$listing['view_bottombar']?'style="display:none"':"";?>>
	<div class="lm-bloq">
		<div class="lm-toolbar-bottom"> 
		  	<ul class="nav navbar-nav form-inline col-xs-push-1 col-xs-10 col-sm-8 col-md-12 ">
		  		<li <?php if ($params->get('showpdf')=='0') echo "style='display:none'"?> id="<?php echo $seed;?>exportpdf">
		  			<button type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon glyphicon-cloud-download"></span> <?php echo JText::_('LM_EXPORT_TO_PDF')?></button>
		  		</li>
		  		<li <?php if ($params->get('showpdffiltered')=='0') echo "style='display:none'"?> id="<?php echo $seed;?>exportpdffiltered">
			    	<button type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon glyphicon-cloud-download"></span> <?php echo JText::_('LM_EXPORT_TO_PDF_FILTERED')?></button>
			    </li>
			    <li class="divider"></li>
			    <li <?php if ($params->get('showexcel')=='0') echo "style='display:none'"?>  id="<?php echo $seed;?>exportexcel">
			    	<button type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-list-alt"></span> <?php echo JText::_('LM_EXPORT_TO_EXCEL')?></button>
			    </li>
			    <li <?php if ($params->get('showexcelfiltered')=='0') echo "style='display:none'"?>  id="<?php echo $seed;?>exportexcelfiltered">
			    	<button type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-list-alt"></span> <?php echo JText::_('LM_EXPORT_TO_EXCEL_FILTERED')?></button>
			    </li>			   	
		  	</ul>
		  	<ul class="nav navbar-nav form-inline col-xs-push-1 col-xs-10 col-sm-8 col-md-12 " style="margin-top:8px;">		  		
			    <li <?php if ($params->get('showrtf')=='0') echo "style='display:none'"?> id="<?php echo $seed;?>exportrtf">
		  			<button type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon glyphicon-cloud-download"></span> <?php echo JText::_('LM_EXPORT_TO_RTF')?></button>
		  		</li>
		  		<li <?php if ($params->get('showrtffiltered')=='0') echo "style='display:none'"?> id="<?php echo $seed;?>exportrtffiltered">
			    	<button type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon glyphicon-cloud-download"></span> <?php echo JText::_('LM_EXPORT_TO_RTF_FILTERED')?></button>
			    </li>  		
		  	</ul>
		</div>
	</div>
	
	<div class="lm-bloq">
		<div class="lm-toolbar-bottom"> 
		  	<ul class="nav navbar-nav form-inline">
		  		<li class="col-md-5 col-md-push-1 col-xs-9 col-xs-push-1" <?php if ($params->get('showemail')=='0') echo "style='display:none'"?>>
		  			<div class="input-group">
		  			  <span class="input-group-btn">
				        <button class="btn btn-default btn-sm" type="button" id="<?php echo $seed;?>email_button"><span class="glyphicon glyphicon-envelope"></span> <?php echo JText::_('LM_SEND_EMAIL')?></button>
				      </span>			    
			      		<input type="text" class="form-control input-sm" name="email" id="<?php echo $seed;?>email"/>
			      	</div>
		  		</li>
		  		<li class="col-md-5 col-md-push-1 col-xs-9 col-xs-push-1" <?php if ($params->get('showemailfiltered')=='0') echo "style='display:none'"?>>
			    	<div class="input-group">
		  			  <span class="input-group-btn">
				        <button class="btn btn-default btn-sm" type="button"  id="<?php echo $seed;?>emailfiltered_button"><span class="glyphicon glyphicon-envelope"></span> <?php echo JText::_('LM_SEND_EMAIL_FILTERED')?></button>
				      </span>
				      <input type="text" class="form-control input-sm" name="emailfiltered" id="<?php echo $seed;?>emailfiltered"/>
				    </div>	
			    </li>	    
		  	</ul>
		</div>
	</div>
</div>
