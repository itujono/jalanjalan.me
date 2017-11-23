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
<?php echo $this->__getHelp('CONFIG_ACL');?>
<div class="cb_table_wrapper cb_acl">
<script>
function cambiaCheck(nombre,grupo){
		nombre='#'+nombre;
        <?php
        foreach ($this->fields as $field) {
        ?>        
        if(jQuery(nombre).is(':checked')){
        	jQuery("#ch_"+grupo+"_<?php echo $field->id;?>").prop('checked',!(jQuery(nombre).is(':checked')));
        }
        jQuery("#ch_"+grupo+"_<?php echo $field->id;?>").prop('disabled',jQuery(nombre).is(':checked'));        
        <?php
        }
        ?>
}
function checkAllColumn(input){	
	var is_checked=jQuery(input).is(':checked');	
	var th_up=jQuery(input).closest('th');
	var check_index=jQuery('#tr_checkall th').index(th_up);	
	jQuery('#acltable tr').each(function(){
		var check=jQuery(this).find('td:eq('+check_index+') input[type="checkbox"]');
		jQuery(check).prop('checked',is_checked);
	}); 
}

window.addEvent('domready', function() {
                $('adminForm').addEvent('submit',function(event){
                	createForm();          
                 });
 });        
Joomla.submitbutton = function(task){	
	if (task == ''){
		return false;    
	} else {		
		createForm();
		Joomla.submitform(task);
        return true;
    }
}
function createForm(){
	var acldatastr="";
    <?php
       foreach ($this->acl as $group) {
    ?>
       acldatastr+="<?php echo $group['idgroup']?>;#";
       if(jQuery("#ch_<?php echo $group['idgroup'];?>_detail").is(':checked')) acldatastr+="detail#";
       if(jQuery("#ch_<?php echo $group['idgroup'];?>_detailpdf").is(':checked')) acldatastr+="detailpdf#";
       if(jQuery("#ch_<?php echo $group['idgroup'];?>_detailrtf").is(':checked')) acldatastr+="detailrtf#";
       if(jQuery("#ch_<?php echo $group['idgroup'];?>_add").is(':checked')) acldatastr+="add#";
       if(jQuery("#ch_<?php echo $group['idgroup'];?>_delete").is(':checked')) acldatastr+="delete#";
       if(jQuery("#ch_<?php echo $group['idgroup'];?>_edit").is(':checked')) acldatastr+="edit#";       
       if(jQuery("#ch_<?php echo $group['idgroup'];?>_bulk").is(':checked')) acldatastr+="bulk#";
        <?php
           foreach ($this->fields as $field) {                      
       ?>
       if(jQuery("#ch_<?php echo $group['idgroup'];?>_<?php echo $field->id;?>").is(':checked')) acldatastr+="<?php echo $field->id;?>#";
        <?php
         }
       ?>
       acldatastr+="@";
    <?php
       }
     ?>
     jQuery('#acldata').val(acldatastr); 
}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">


<div style="overflow:auto;width:95%">
<table class="table" id="acltable">

<thead>
<tr>
<th><?php echo JText::_( "GROUP_NAME" ); ?></th>
<th class="basic"><?php echo JText::_( "ACL_DETAIL" ); ?></th>
<th class="basic"><?php echo JText::_( "ACL_DETAILPDF" ); ?></th>
<th class="basic"><?php echo JText::_( "ACL_DETAILRTF" ); ?></th>
<th class="basic"><?php echo JText::_( "ACL_ADD" ); ?></th>
<th class="basic"><?php echo JText::_( "ACL_DELETE" ); ?></th>
<th class="basic"><?php echo JText::_( "ACL_EDIT" ); ?></th>
<th class="basic"><?php echo JText::_( "ACL_BULK" ); ?></th>


<?php
foreach ($this->fields as $field) {
?>

<th><?php echo $field->name ?></th>

<?php
}
?>
</tr>
<tr id="tr_checkall">
<th></th>
<th class="basic"><input type="checkbox" onclick="checkAllColumn(this);" /></th>
<th class="basic"><input type="checkbox" onclick="checkAllColumn(this);" /></th>
<th class="basic"><input type="checkbox" onclick="checkAllColumn(this);" /></th>
<th class="basic"><input type="checkbox" onclick="checkAllColumn(this);" /></th>
<th class="basic"><input type="checkbox" onclick="checkAllColumn(this);" /></th>
<th class="basic"><input type="checkbox" onclick="checkAllColumn(this);" /></th>
<th class="basic"><input type="checkbox" onclick="checkAllColumn(this);" /></th>


<?php
foreach ($this->fields as $field) {
?>

<th><input type="checkbox" onclick="checkAllColumn(this);" /></th>

<?php
}
?>
</tr>


<thead>
<tbody>
<?php

$dis='disabled="disabled"';
foreach ($this->acl as $group) {
?>
<tr>

<td><?php echo $group['title']; ?></td>
<td align="center" class="basic">
<input type="checkbox" name="ch_<?php echo $group['idgroup'];?>_detail" id="ch_<?php echo $group['idgroup'];?>_detail" value="1"
<?php 
$pos=strrpos($group['acl'],'#detail#'); 
if (!($pos === false)) { 
echo "checked";
}

?>
/></td>

<td align="center" class="basic">
<input type="checkbox" name="ch_<?php echo $group['idgroup'];?>_detail" id="ch_<?php echo $group['idgroup'];?>_detailpdf" value="1"
<?php 
$pos=strrpos($group['acl'],'#detailpdf#'); 
if (!($pos === false)) { 
echo "checked";
}

?>
/></td>

<td align="center" class="basic">
<input type="checkbox" name="ch_<?php echo $group['idgroup'];?>_detail" id="ch_<?php echo $group['idgroup'];?>_detailrtf" value="1"
<?php 
$pos=strrpos($group['acl'],'#detailrtf#'); 
if (!($pos === false)) { 
echo "checked";
}

?>
/></td>

<td align="center" class="basic">
<input type="checkbox" name="ch_<?php echo $group['idgroup'];?>_add" id="ch_<?php echo $group['idgroup'];?>_add" value="1"
<?php 
$pos=strrpos($group['acl'],'#add#'); 
if (!($pos === false)) { 
echo "checked";
}

?>
/></td>

</td>
<td align="center" class="basic">
<input type="checkbox" name="ch_<?php echo $group['idgroup'];?>_delete" id="ch_<?php echo $group['idgroup'];?>_delete" value="1"
<?php 
$pos=strrpos($group['acl'],'#delete#'); 
if (!($pos === false)) { 
echo "checked";
}

?>


/>
</td>
<td align="center" class="basic">
<input type="checkbox" name="ch_<?php echo $group['idgroup'];?>_edit" id="ch_<?php echo $group['idgroup'];?>_edit" value="1"
onChange="javascript:cambiaCheck('ch_<?php echo $group['idgroup'];?>_edit','<?php echo $group['idgroup'];?>')"
<?php 
$pos=strrpos($group['acl'],'#edit#'); 
if (!($pos === false)) { 
echo "checked";
}

?>


/>
</td>
  
</td>
<td align="center" class="basic">
<input type="checkbox" name="ch_<?php echo $group['idgroup'];?>_bulk" id="ch_<?php echo $group['idgroup'];?>_bulk" value="1"
<?php 
$pos=strrpos($group['acl'],'#bulk#'); 
if (!($pos === false)) { 
echo "checked";
}

?>


/>
</td>
  
<?php
foreach ($this->fields as $field) {
?>

<td  align="center">
<input type="checkbox" name="ch_<?php echo $group['idgroup'];?>_<?php echo $field->id;?>" id="ch_<?php echo $group['idgroup'];?>_<?php echo $field->id;?>" value="1"

<?php
 
$posedit=strrpos($group['acl'],'#edit#'); 
if (!($posedit === false)) { 
echo $dis;
}


$pos=strrpos($group['acl'],'#'.$field->id.'#'); 
if (!($pos === false)) { 
echo "checked";
}

?>


/>
</td>
<?php
}
?>  
  
  
  
</tr>
<?php
}
?>
</tbody>
</table>   

<input type="hidden" name="option" value="com_listmanager" />
<input type="hidden" name="task" value="saveacl" />
<input type="hidden" name="acldata" value="" id="acldata"/>
<input type="hidden" name="controller" value="listing" />
<input type="hidden" name="idlisting" value="<?php echo JRequest::getVar( 'idlisting')?>"/>

</form>
</div>
</div>
