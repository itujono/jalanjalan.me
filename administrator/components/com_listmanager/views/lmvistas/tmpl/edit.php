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
JHTML::_('behavior.formvalidation');
JHTML::_('behavior.calendar');
$pathImagen=JURI::base().'components/com_listmanager/assets/img/';
$pathImagenCalendar=$pathImagen.'calendar.png';
$document =JFactory::getDocument();                                                                       
$css=$this->__getCSS();          
foreach ($css as $style){                                                        
	$document->addStyleSheet($style);
}
$js=$this->__getJS();
foreach ($js as $jscript){                                                        
	?><script src="<?php echo $jscript;?>"></script><?php 
}
JHTML::_('behavior.framework', true);
JHTML::_('behavior.modal');
$arrMultivalues=array(2,10,11,16);
$arrElements=array();
$arrElements[]='VISIBLE';
$arrElements[]='DEFAULT_VALUE';
$arrElements[]='AUTOFILTER';
$arrElements[]='SHOW_ORDER';
$arrElements[]='FILTER';
$arrElements[]='ORDER';
$arrElements[]='DEFAULT_VIEW_ORDER';
function v_getMultivalueById($id){
	$db=JFactory::getDbo();
	$query='select * from #__listmanager_field_multivalue where idfield='.$id.' order by ord asc';
	$db->setQuery($query);
	return $db->loadObjectList();	
}

?>
<?php echo $this->__getHelp('VIEW_EDIT',$arrElements);?>
<script>
function switchValue(to){
	//$(to).value=='0'?$(to).value='1':$(to).value='0';	
	jQuery(to).val()=='0'?jQuery(to).val('1'):jQuery(to).val('0');
}
jQuery(document).ready(	function(){
	jQuery('#add_order').click(function(){
		jQuery('#order_copy').clone().css('display','').appendTo('#order_list');
		delOrder();
	});
	jQuery('.check').each(function(index,elem){
		jQuery(elem).click(function(event){
			switchValue('#'+jQuery(this).data('id'));
		});
	});
	setOrder();
	delOrder();
	 /*jQuery("#dateformatbutton").click(function() {
			jQuery( "#dateinfo" ).modal();
	 });*/
});
setOrder();
delOrder();
function delOrder(){
	jQuery('.del_order').click(function(){
		jQuery(this).closest('.controls').remove();
	});
}
function setOrder(){
	if ('<?php echo $this->view['default_order']?>'!=''){
		var a_elems=jQuery.parseJSON('<?php echo $this->view['default_order']?>');
		if(a_elems!=null){
			for(var i=0;i<a_elems.length;i++){
				var tmpControl=jQuery('#order_copy').clone().css('display','').appendTo('#order_list');
				jQuery(tmpControl).find('select[name=default_order_id]').find('option[value="'+a_elems[i].headerId+'"]').attr('selected', 'selected');
				jQuery(tmpControl).find('select[name=default_order_dir]').find('option[value="'+a_elems[i].order+'"]').attr('selected', 'selected');		
			}
		}
	}
}
function order_wrapper(){
	var a_elems=new Array();
	jQuery('#order_list').find('.controls').each(function(i,controls){		
		var tmp=new Object();
		tmp.headerId=jQuery(controls).find('select[name=default_order_id]').val();
		if (tmp.headerId=='-1') return;
		tmp.order=jQuery(controls).find('select[name=default_order_dir]').val();
		a_elems.push(tmp);
	});
	jQuery('#default_order').val(jQuery.stringify(a_elems));
}
Joomla.submitbutton = function(task){	
	if (task == ''){
		return false;    
	} else {		
		order_wrapper();
		Joomla.submitform(task);
        return true;
    }
}
jQuery.extend({
    stringify  : function stringify(obj) {
    var t = typeof (obj);
    if (t != "object" || obj === null) {
    // simple data type
    if (t == "string") obj = '"' + obj + '"';
    return String(obj);
} else {
    // recurse array or object
    var n, v, json = [], arr = (obj && obj.constructor == Array);

    for (n in obj) {
        v = obj[n];
        t = typeof(v);
        if (obj.hasOwnProperty(n)) {
            if (t == "string") v = '"' + v + '"'; else if (t == "object" && v !== null) v = jQuery.stringify(v);
            json.push((arr ? "" : '"' + n + '":') + String(v));
        }
    }
    return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
}
}
});   
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="cb_table_wrapper">
<div id="editcell">
    <div class="form-horizontal">
    	<div class="control-group">
			<label class="control-label"><?php echo JText::_( 'NAME' ); ?></label>
			<div class="controls">
				<input type="text" class="input-medium" name="name" value="<?php echo $this->view['name']; ?>"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?php echo JText::_( 'COMMENTS' ); ?></label>
			<div class="controls">
				<textarea rows="5" cols="75" name="comments"><?php echo $this->view['comments']; ?></textarea>
			</div>
		</div>
		<div class="control-group">
				<label class="control-label"><?php echo JText::_( 'LM_DATE_FORMAT' ); ?></label>
				<div class="controls">
					<input type="text" id="date_format" name="date_format" value="<?php echo $this->view['date_format']?>" style="width:90px;"/>
					<a href="#dateinfo" id="dateformatbutton" role="button" class="btn" data-toggle="modal" style="cursor:pointer;"><?php echo JText::_( 'LM_DATE_FORMAT_INFO' ); ?></a>
				</div>
			</div>
		<div class="row-fluid">			
			<div class="span12">				
				<div class="span4 offset1" style="max-height:150px;overflow:auto;margin-top:8px;background-color: #ddd;">
					<table class="table">
						<thead>
							<tr>
								<th colspan="3"><?php echo JText::_('LM_ORDER_LIST')?></th>
							</tr>
							<tr>
								<th><?php echo JText::_('LM_ORDER')?></th>
								<th><?php echo JText::_('LM_ORDER_FIELD')?></th>
								<th><?php echo JText::_('LM_ORDER_ORD')?></th>
							</tr>
						</thead>	
						<tbody>
						<?php
						$arr_fields=array(); 
						foreach ($this->fields as $field){
							$arr_fields[$field->id]=$field->name;
						}
						$arr_ord=array();
						$arr_ord['desc']=JText::_('LM_DESC');
						$arr_ord['asc']=JText::_('LM_ASC');
						$list_sort=json_decode($this->item['default_order']);					 
						for ($i=0;$i<count($list_sort);$i++){
							?><tr>
							<td><?php echo $i+1?></td>
							<td><?php echo $arr_fields[$list_sort[$i]->headerId]?></td>
							<td><?php echo $arr_ord[$list_sort[$i]->order]?></td>
							</tr><?php
						}
						?>		
						</tbody>	
					</table>
				</div>
				<div class="span7">
					<div class="span3">
						<label class="control-label"><?php echo JText::_( 'LM_DEFAULT_ORDER_VIEW_BY' ); ?><p>
						<a class="btn btn-primary" id="add_order"><?php echo JText::_( 'LM_ADD' ); ?></a></p>
						</label>					
					</div>
					<div class="span9" id="order_list"  style="max-height:150px;overflow:auto;margin-top:8px; float:left;">				
					</div>				
					<div id="order_copy" class="controls" style="display:none;margin-left: 20px;margin-top:8px;">
						<a class="btn btn-danger del_order"><?php echo JText::_( 'LM_DELETE' ); ?></a>
						<select name="default_order_id">
							<option value="-1"><?php echo JText::_('LM_NONE');?></option>
							<?php foreach ($this->fields as $field){?>
							<option value="<?php echo $field->id;?>"><?php echo $field->name;?></option>
							<?php }?>
						</select>
						<select name="default_order_dir">
							<option value="asc"><?php echo JText::_('LM_ASC');?></option>
							<option value="desc"><?php echo JText::_('LM_DESC');?></option>
						</select>
					</div>
				</div>
			</div>      	
      </div>  
      <div class="row-fluid">     
	      <div class="span12">
		      <div style="text-align:center">
		      	<h4><?php echo JText::_( 'FIELDS' ); ?></h4>
		      </div>
		      <div class="cb_acl">
		      	<table class="table table-hover">
		            	  <thead>
		            	  	  <tr>
					              <th colspan="7" class="lm_list"><?php echo JText::_( 'LM_DATA_LIST' ); ?></th>
					              <th colspan="8" class="lm_view"><?php echo JText::_( 'LM_VIEW_DATA' ); ?></th>
					          </tr>
					          <tr>
					              <th class="lm_list"><?php echo JText::_( 'ID' ); ?></th>
					              <th class="lm_list"><?php echo JText::_( 'NAME' ); ?></th>
					              <th class="lm_list"><?php echo JText::_( 'VISIBLE' ); ?></th>
					              <th class="lm_list"><?php echo JText::_( 'DEFAULT_VALUE' ); ?></th>
					              <th class="lm_list"><?php echo JText::_( 'AUTOFILTER' ); ?></th>
					              <th class="lm_list"><?php echo JText::_( 'SHOW_ORDER' ); ?></th>
					              <th class="lm_list"><?php echo JText::_( 'ORDER' ); ?></th>
					              <th class="lm_view"><?php echo JText::_( 'VISIBLE' ); ?></th>
					              <th class="lm_view"><?php echo JText::_( 'DEFAULT_VALUE' ); ?></th>
					              <th class="lm_view"><?php echo JText::_( 'AUTOFILTER' ); ?></th>
					              <th class="lm_view"><?php echo JText::_( 'SHOW_ORDER' ); ?></th>
					              <th class="lm_view"><?php echo JText::_( 'FILTER' ); ?></th>
					              <th class="lm_view"><?php echo JText::_( 'ORDER' ); ?></th>
					          </tr>            
					      </thead> 
					      <tbody>
					      	<?php
					      	if ($this->fields!=null){
						      	foreach ($this->fields as $field){?>
						      	<tr>
						      		<td class="lm_list"><?php echo $field->id;?></td>
						      		<td class="lm_list"><?php echo $field->name;?></td>
						      		<td class="lm_list">
						      			<input type="checkbox" disabled <?php if($field->list_visible==1) echo 'checked';?> />			      			
						      		</td>
						      		<td class="lm_list">
						      			<?php echo $field->list_defvalue;?>
						      		</td>
						      		<td class="lm_list">
					                      <?php if ($field->list_autofilter=='0') echo JText::_( "AF_SELECT" ); ?>
					                      <?php if ($field->list_autofilter=='1') echo JText::_( "AF_TEXT" ); ?>
					                      <?php if ($field->list_autofilter=='2') echo JText::_( "AF_MULTIPLE" ); ?>
					                      <?php if ($field->list_autofilter=='3') echo JText::_( "AF_RANGE" ); ?>
						      		</td>
						      		<td class="lm_list">
						      			<input type="checkbox" disabled <?php if($field->list_showorder==1) echo 'checked';?> >
						      		</td>	
						      		<td class="lm_list">
						      			<?php echo $field->list_order;?>
						      		</td>		      		
						      		<td>
						      			<?php if ($field->visible==null) $field->visible=$field->list_visible;?>
						      			<input name="v_<?php echo $field->id;?>" id="v_<?php echo $field->id;?>" class="check" type="checkbox" data-id="visible<?php echo $field->id;?>" <?php if($field->visible==1) echo 'checked';?>>
				                       	<input type="hidden" name="visible<?php echo $field->id; ?>" id="visible<?php echo $field->id; ?>" value="<?php echo $field->visible;?>" />	                       	
			                    	</td>
			                    	<td>
						      			<input type="text"  class="input-small" name="defaulttext<?php echo $field->id; ?>" id="defaulttext<?php echo $field->id; ?>" value="<?php echo $field->defaulttext;?>" />
						      		</td>
						      		<td>
						      			<?php if ($field->autofilter==null) $field->autofilter=$field->list_autofilter;?>
						      			<select id="autofilter<?php echo $field->id; ?>" name="autofilter<?php echo $field->id; ?>">
					                      <option value="-1"><?php echo JText::_( "SELECT" ); ?></option>
					                      <option value="0" <?php if ($field->autofilter=='0') echo 'selected';?>><?php echo JText::_( "AF_SELECT" ); ?></option>
					                      <option value="1" <?php if ($field->autofilter=='1') echo 'selected';?>><?php echo JText::_( "AF_TEXT" ); ?></option>
					                      <option value="2" <?php if ($field->autofilter=='2') echo 'selected';?>><?php echo JText::_( "AF_MULTIPLE" ); ?></option>
					                      <?php 
					                      $typesAllowed=array(0,14,1,4,19);
					                      if (in_array($field->type, $typesAllowed)):
					                      ?>	<option value="3" <?php if ($field->autofilter==3) echo 'selected';?>><?php echo JText::_( "AF_RANGE" ); ?></option>
					                      <?php endif;?>
					                    </select>		                    
				                    </td>
						      		<td>
						      			<?php if ($field->showorder==null) $field->showorder=$field->list_showorder;?>
						      			<input name="s_<?php echo $field->id;?>" id="s_<?php echo $field->id;?>" class="check" type="checkbox" data-id="showorder<?php echo $field->id; ?>" <?php if($field->showorder==1) echo 'checked';?>>
				                       	<input type="hidden" name="showorder<?php echo $field->id; ?>" id="showorder<?php echo $field->id; ?>" value="<?php echo $field->showorder;?>" />	                       	
						      		</td>
						      		<td>						  
						      			<?php if($field->type!=15):?>    		
							      			<select id="filter_type<?php echo $field->id; ?>" name="filter_type<?php echo $field->id; ?>">						      			
						                      <option value="-1"><?php echo JText::_( "SELECT" ); ?></option>					                      
						                      <option value="0" <?php if ($field->filter_type=='0') echo 'selected';?>><?php echo JText::_( "FT_EQUAL" ); ?></option>
						                      <?php if(!in_array($field->type,$arrMultivalues)):?>
						                      <option value="1" <?php if ($field->filter_type=='1') echo 'selected';?>><?php echo JText::_( "FT_LESS" ); ?></option>
						                      <option value="2" <?php if ($field->filter_type=='2') echo 'selected';?>><?php echo JText::_( "FT_MORE" ); ?></option>
						                      <option value="3" <?php if ($field->filter_type=='3') echo 'selected';?>><?php echo JText::_( "FT_STARTS_WITH" ); ?></option>
						                      <option value="4" <?php if ($field->filter_type=='4') echo 'selected';?>><?php echo JText::_( "FT_CONTAINS" ); ?></option>
						                      <?php endif;?>
						                    </select>
						                    <?php if(!in_array($field->type,$arrMultivalues)):?>
						                    	<input type="text" class="input-mini" id="filter_value<?php echo $field->id; ?>" name="filter_value<?php echo $field->id; ?>" value="<?php echo $field->filter_value;?>"/>
						                    <?php else:?>
						                    	<?php $mvalues=v_getMultivalueById($field->id);?>
						                    	<select id="filter_value<?php echo $field->id; ?>" name="filter_value<?php echo $field->id; ?>">						      			
							                      <option value="-1"><?php echo JText::_( "SELECT" ); ?></option>
							                 		<?php foreach ($mvalues as $mval):?>
							                      	<option value="<?php echo $mval->id;?>" <?php if($field->filter_value==$mval->id){echo 'selected="selected"';}?>><?php echo $mval->name;?></option>
							                      	<?php endforeach;?>
							                    </select>
						                    <?php endif;?>
					                    <?php endif;?>
						      		</td>
						      		<td>
						      			<?php if ($field->order==null) $field->order=$field->list_order;?>
						      			<input type="text"  class="input-mini" name="order<?php echo $field->id; ?>" id="order<?php echo $field->id; ?>" value="<?php echo $field->order;?>" />	                       	
						      		</td>
						      	</tr>
						      	<?php
						      	} 
						      }?>
					      </tbody> 
		            </table>
		          </div>
		      </div>	
		   </div>       
	</div>
</div>
<input type="hidden" name="idlisting" value="<?php echo  JRequest::getVar( 'idlisting' ); ?>" />
<input type="hidden" name="idview" value="<?php echo $this->view['id']; ?>" />
<input type="hidden" name="cid[]" value="<?php echo $this->view['id']; ?>" />
<input type="hidden" name="option" value="com_listmanager" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="lmvistas" />
<input type="hidden" name="check" value="post"/>
<input type="hidden" name="default_order" id="default_order"/>
</form>



<div id="dateinfo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="dateformatbutton"  role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="height:15px;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>        
      </div>
      <div class="modal-body" style="padding-top:0px;">        
		    <?php echo JText::_('LM_DATE_FORMAT_INFO_EXTENDED');?>
      </div>      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->