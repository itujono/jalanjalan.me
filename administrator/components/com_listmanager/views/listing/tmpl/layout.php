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
$js [] = JURI::base () . 'components/com_listmanager/assets/js/jquery.markitup.js';
$js [] = JURI::base () . 'components/com_listmanager/assets/js/markitup/sets/html/set.js';
foreach ($js as $jscript){                                                        
	?><script src="<?php echo $jscript;?>"></script><?php 
}
//$editor =JFactory::getEditor();
?>
<?php echo $this->__getHelp('LAYOUT');?>
<script src="<?php echo JURI::base();?>components/com_listmanager/assets/js/beautify-html.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo JURI::base ();?>components/com_listmanager/assets/js/markitup/skins/markitup/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo JURI::base ();?>components/com_listmanager/assets/js/markitup/sets/html/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo JURI::base ();?>components/com_listmanager/assets/js/markitup/templates/preview.css" />
<style>
<!--
.lm_tips{
	margin-left: 5px;
	margin-top: 80px;
	border:1px solid #ccc;
	border-radius:5px;
	background-color:#f9f9f9;
	padding-bottom: 10px;
	padding-left:5px;
	padding-right:5px;
	}
-->
</style>
<script type="text/javascript">
<!--
window.addEvent('load',function(){
	jQuery('.columns').each(function(event){
		jQuery(this).click(function(e){	
			if(confirm("<?php echo JText::_('LM_CONFIRM_LAYOUT_CHANGE');?>")){				
				var elem=e.target;
				generateTable(jQuery(elem).attr('idlo'));
			}
		});
	});
	<?php 
	$componentParams = JComponentHelper::getParams('com_listmanager');
	$param = $componentParams->get('editor', '0'); 
	if ($param==0): ?>
	jQuery('#html_ta_A').markItUp(HTMLSettings);
	<?php endif;?>
});
function setContentEditor(value){
	//var layout='layout';	
	jQuery('#html_ta_A').html(value);
  <?php 
  /*$value='value';
  echo str_replace("'","",$editor->setContent('layout',$$value));*/
  ?>
}
function generateTable(columns){	
	var arrFields=new Map();
	<?php for($i=0;$i<count($this->allfields);$i++){
	 $field=$this->allfields[$i]; ?>
	 arrFields.put('<?php echo $field->innername;?>','<?php echo $field->name; ?>');	 			
	<?php } ?>
	var containerAll=new Element('div',{'class':'row'});	
	var rowFluid=new Element('div',{'id':'lm_wrapper'});
	/*var formHorizontal=new Element('div',{'class':'form-horizontal','role':'form'});*/

	var numField=Math.ceil(arrFields.size()/columns);
	var lastField=0;
	for(var i=0;i<columns;i++){
		var span=new Element('div',{'class':'col-sm-'+(12/columns)});
		var topLimit=lastField+numField;
		arrFields.each(function(key,value,index){
			var controlGroup=new Element('dl',{'class':'dl-horizontal'});
			if (index>=lastField && index<topLimit){
				var controlLabel=new Element('dt');
				var controls=new Element('dd');
				controlLabel.set('html',value);
				controls.set('html','##'+key+'##');
				controlGroup.adopt(controlLabel);
				controlGroup.adopt(controls);				
				lastField++;
			}
			span.adopt(controlGroup);
		});
		//formHorizontal.adopt(span);
		rowFluid.adopt(span);
	};	
	//rowFluid.adopt(formHorizontal);
	containerAll.adopt(rowFluid);
	pretty_html=style_html(containerAll.get('html'));
	setContentEditor(pretty_html);
	/*var containerAll=new Element('div');
	var rowFluid=new Element('div',{'class':'row-fluid'});
	var formHorizontal=new Element('div',{'class':'form-horizontal','role':'form'});

	var numField=Math.ceil(arrFields.size()/columns);
	var lastField=0;
	for(var i=0;i<columns;i++){
		var span=new Element('div',{'class':'span'+(12/columns)});
		var topLimit=lastField+numField;
		arrFields.each(function(key,value,index){
			if (index>=lastField && index<topLimit){
				var controlGroup=new Element('div',{'class':'form-group'});
				var controlLabel=new Element('label',{'class':'control-label col-sm-3'});
				var controls=new Element('div',{'class':'col-sm-9'});
				controlLabel.set('html',value);
				controls.set('html','##'+key+'##');
				controlGroup.adopt(controlLabel);
				controlGroup.adopt(controls);
				span.adopt(controlGroup);				
				lastField++;
			}
		});
		formHorizontal.adopt(span);  
	};	
	rowFluid.adopt(formHorizontal);
	containerAll.adopt(rowFluid);
	pretty_html=style_html(containerAll.get('html'));
	setContentEditor(pretty_html);*/
		
}
function Map() {
	this.keys = new Array();
	this.data = new Object();
	this.put = function(key, value) {
	    if(this.data[key] == null){ this.keys.push(key); }
	    this.data[key] = value;
	};
	this.get = function(key) { return this.data[key]; };
	this.remove = function(key) {
		this.keys.remove(key);
	    this.data[key] = null;
	};
	this.each = function(fn){
	    if(typeof fn != 'function'){ return; }
	    var len = this.keys.length;
	    for(var i=0;i<len;i++){
	        var k = this.keys[i];
	        fn(k,this.data[k],i);
	    }
	};
	this.entrys = function() {
	    var len = this.keys.length;
	    var entrys = new Array(len);
	    for (var i = 0; i < len; i++) {
	        entrys[i] = {
	            key : this.keys[i],
	            value : this.data[i]
	        };
	    }
	    return entrys;
	};
	this.isEmpty = function() { return this.keys.length == 0; };
	this.size = function(){ return this.keys.length; };
}
//-->
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">

<div class="cb_table_wrapper">	
	<div class="form-horizontal">	
		<div class="control-group">
			<label class="control-label"></label>
			<div class="controls">
				<a href="#layout_modal_info" role="button" class="btn btn-info" data-toggle="modal" style="margin-left: 10px;">
					<?php echo JText::_( 'SECTION_HELP_TITLE' ); ?>
				</a>
				<div id="layout_modal_info" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
				    <div class="modal-header">
				    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				    	<h3><?php echo JText::_( 'SECTION_HELP_TITLE' ); ?></h3>
				    </div>
				    <div class="modal-body">
				    	<p><?php echo JText::_( 'SECTION_HELP' ); ?></p>
				    </div>
				    <div class="modal-footer">
				    <a data-dismiss="modal" class="btn">Close</a>
				    </div>			
				</div>
			</div>
		</div>	
		<div class="control-group">
			<label class="control-label" rel="tooltip" data-original-title="<?php echo JText::_("LM_TABLE_LAYOUT")?>"><?php echo JText::_( 'LM_TABLE_LAYOUT' ); ?></label>
			<div class="controls">
				<div class="btn-group" data-toggle="buttons-radio">
				    <button type="button" idlo="1" class="btn columns"><?php echo JText::_('ONE_COLUMNS');?></button>
				    <button type="button" idlo="2" class="btn columns"><?php echo JText::_('TWO_COLUMNS');?></button>
				    <button type="button" idlo="3" class="btn columns"><?php echo JText::_('THREE_COLUMNS');?></button>
			    </div>
			</div>
		</div>		
		<div id="editcell" class="tab-content">
			<div id="sec_A" class="tab-pane fade in form-horizontal active">
				<div class="span3">
					<blockquote><?php echo JText::_('FIELDS');?></blockquote>
					<ul class="unstyled">
						<?php foreach ($this->allfields as $field){?>
						<li><?php echo $field->innername; ?></li>
						<?php }?>
					</ul>						
				</div>
				<div class="span8">
				<textarea id="html_ta_A" name="layout"
										style="width: 90%" rows="15"><?php echo $this->item['layout'];?></textarea>
				<?php
						/*$editor =JFactory::getEditor();
						echo $editor->display('layout', $this->item['layout'], '600','400','100','6');*/
				?> 
				</div>	
			</div>			
		</div>
	</div>
</div>

<input type="hidden" name="id" value="<?php echo $this->item['id']; ?>" />
<input type="hidden" name="idlisting" value="<?php echo $this->item['id']; ?>" />
<input type="hidden" name="option" value="com_listmanager" />
<input type="hidden" name="task" value="storeLayout" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="listing" />
<input type="hidden" name="check" value="post"/>
</form>

