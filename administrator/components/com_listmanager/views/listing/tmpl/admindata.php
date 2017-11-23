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
$document->addStyleSheet(JURI::base().'components/com_listmanager/assets/css/rateit.css','text/css','screen');
$css=$this->__getCSS();                                                                          
foreach ($css as $style){                                                        
	$document->addStyleSheet($style);
}
$js=$this->__getJS();
foreach ($js as $jscript){                                                        
	?><script src="<?php echo $jscript;?>"></script><?php 
}
$scriptbase=JURI::base().'components/com_listmanager/assets/js/';

function _buildQuerySelectMultivalueById($id,$fieldId){
		$db = JFactory::getDBO();
		$idlisting=JRequest::getVar( 'idlisting');
		$query = "select * from #__listmanager_field_multivalue where idobj=".$idlisting." and idfield=".$fieldId." and value=".$db->quote($id);		
		return $query;
   }
function getMultivalueById($id,$fieldId){  	
  	$db = JFactory::getDBO();
    $query = _buildQuerySelectMultivalueById($id,$fieldId);
    $db->setQuery($query);
    return $db->loadObject();
  }
function getMultivalueSQLById($sqltext,$id){
  	if ($sqltext!=null && count($sqltext)>0){  	
	  	$db = JFactory::getDBO();
	  	$userdata=JFactory::getUser();      
        $sqltext=str_replace('##userid##', $userdata->id, $sqltext);
	    $db->setQuery($sqltext);
	    $res=$db->loadRowList();
	    $fin=null;
	    $finres = new stdClass();
	    for($i=0;$i<count($res);$i++){
	    	$tmpres=$res[$i];
	    	if ($tmpres[0]==$id){	    		
	    		$fin=$tmpres[1];
	    		break;
	    	}
	    }
	    $finres->value=$fin;
	    return $finres; 
  	}
  	return null;
  }
  
?>
<?php //echo $this->__getHelp('ADMIN_DATA'); ?>
<script type="text/javascript" src="<?php echo $scriptbase.'jquery-1.8.0.min.js';?>"></script>
<script>jQuery.noConflict();</script>
<script type="text/javascript" src="<?php echo $scriptbase.'jquery.rateit.min.js';?>"></script>
<script>
window.addEvent('load',function(){
	$('clean').addEvent('click',function(){
		$('lm_search').setProperty('value','');
		$('adminForm').submit();
	});
});
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="cb_table_wrapper">
	<table class="table">
	<tbody>
		<tr>
			<td><input type="text" name="lm_search"  id="lm_search" value="<?php echo JRequest::getVar('lm_search','');?>"/>&nbsp;&nbsp;<input type="submit" value="<?php echo JText::_('SEARCH'); ?>"/>&nbsp;&nbsp;<input type="button" id="clean" value="<?php echo JText::_('CLEAN'); ?>"/></td>
		</tr>
	</tbody>
	</table>
    <table class="table">
    <thead>
        <tr>
            <th width="20">
            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
               <!-- <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />-->
            </th>
             <th>Id</th>
             
            <?php
            
            foreach ($this->fields as $field){
            
            ?>
            
            <th>
            <?php echo $field->name ; ?>
            </th>
            <?php
            }
            
            ?>
                     
        </tr>            
    </thead>
    <tbody>
    <?php
    
    $pathImagen=JURI::base().'components/com_listmanager/assets/img/data.png';
    $k = 0;
    $i=0;
    if($this->records!=null)
    foreach ($this->records as $record){
      $checked    = JHTML::_( 'grid.id', $i++, $record['id'] );
      //$link = JRoute::_( 'index.php?option=com_listmanager&controller=listing&task=edit&cid[]='. $row->id );
        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $checked; ?>
            </td>
            <td>
                <?php echo $record['id']; ?>
            </td>
           
            <?php 
             foreach ($this->fields as $field){
            
            ?>
            <td>
             <?php
             if($field->type=="6"){
              if(isset($record[$field->id])&&$record[$field->id]!=""){
              	if(JFactory::getUser($record[$field->id])!=null)
                	echo JFactory::getUser($record[$field->id])->name;
                else
                	echo '';
              }
             }elseif($field->type=='10'||$field->type=='11'||$field->type=='16'||$field->type=='2'){ //Multivalues
	             if(isset($record[$field->id])&&$record[$field->id]!=null && count($record[$field->id])>0){
	             	/*
			      	$multikeys=explode('#', $record[$field->id]);	      	
			      	$multival=array();
			      	foreach ($multikeys as $key){
			      		$multiTmp=getMultivalueById(trim($key),$field->id);
			      		if ($multiTmp==null) $multiTmp=getMultivalueSQLById($field->sqltext,$key);
			      		if ($multiTmp!=null && isset($multiTmp->value)) $multival[]=$multiTmp->name;
			      	}
			      	$rec_valuem=implode('#', $multival);
			      	echo str_replace("#", "<br/>", $rec_valuem);
			      	*/
	             	echo $record[$field->id];
		      	} else {
             		echo '';
             	}
             }elseif ($field->type=="15"){             	
             	if(isset($record[$field->id])){
             		echo '<div class="rateit" data-rateit-value="'.$record[$field->id].'" data-rateit-ispreset="true" data-rateit-readonly="true"></div>';
             	} else {
             		echo '';
             	}
             }
             else{
             	if(isset($record[$field->id])){
              		echo $record[$field->id];
             	} else {
             		echo '';
             	}
             } 
             
             
             ?>
            </td>
            <?php
            }
            
            ?>
        </tr>
        <?php
        $k = 1 - $k;
    }
    ?>
    </tbody>
    <tfoot>
    <?php if ( version_compare( JVERSION, '3.0', '>=' ) == 1) { ?><td><?php echo $this->pagenav->getLimitBox();?></td><?php } ?>    
    <td colspan="<?php echo count($this->fields)+2;?>"><?php echo $this->pagenav->getListFooter();?></td>    
    </tfoot>
    </table>    
</div>
<input type="hidden" name="option" value="com_listmanager" />
<input type="hidden" name="task" value="admindata" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="listing" />
<!--  <input type="hidden" name="cid" value="<?php echo JRequest::getVar( 'idlisting')?>" /> -->
<input type="hidden" name="idlisting" value="<?php echo JRequest::getVar( 'idlisting')?>"/> 
</form>

