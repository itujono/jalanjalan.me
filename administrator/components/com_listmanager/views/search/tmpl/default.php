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

$document = JFactory::getDocument();                                                                       
$css=$this->__getCSS();                                                                          
foreach ($css as $style){                                                        
	$document->addStyleSheet($style);
}
$js=$this->__getJS();
foreach ($js as $jscript){                                                        
	?><script src="<?php echo $jscript;?>"></script><?php 
}
?>
<?php //echo $this->__getHelp('ACCESS'); ?>
<script>
    function resetForm(){
    	jQuery('#search').val('');
      //$('type').getElements('option')[0].setProperty('selected','selected');
      jQuery('#user').find('option:selected').removeAttr("selected");
      jQuery('form[name=adminForm]').submit();
    }    
    Joomla.submitbutton = function(pressbutton) {
        var form = document.adminForm;
        if (pressbutton == 'download') {
           jQuery('input[name="format"]').val('csv');
        }       
        jQuery('input[name="task"]').val(pressbutton);
        form.submit();
        jQuery('input[name="format"]').val('html');
     }
</script>
    
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="cb_table_wrapper">
    <table class="table table-hover">
    <thead>
	   <tr>
            <th></th>
            <th></th>            
            <th >
            	<?php
            	$arrnames=array();
            	if (isset($this->acc)){
	    			foreach ($this->acc as &$row){
	    				$arrnames[$row->iduser]=$row->username;			
	    			} 
            	}            	
            	?>
            	<select id="user" name="user" onchange="document.adminForm.submit();" style="width:auto;">
            		<option value="-1" <?php if (JRequest::getVar('user')=='-1') echo 'selected="selected"'?>><?php echo JText::_('SELECT')?></option>
            		<?php 
            		foreach ($arrnames as $key=>$value){
            			?><option value="<?php echo $key;?>" <?php if (JRequest::getVar('user')==$key) echo 'selected="selected"'?>><?php echo $value;?></option><?php 
            		}
            		?>
            	</select>
            </th>
            <th colspan="3" style="text-align:right; vertical-align:top;">
            <div class="">            	
				<div class="input-append">					
					<input type="text"
			            name="search"
			            id="search"
			            value="<?php echo JRequest::getVar('search');?>"
			            class="input-medium"
			            onchange="document.adminForm.submit();" size="100"/>
					<button class="btn" type="button" onclick="this.form.submit();" >
	              		<?php echo JText::_("SEARCH"); ?>
	            	</button>
	            	<button class="btn" type="button" onclick="javascript:resetForm();">
		              <?php echo JText::_('RESET'); ?>
		            </button>
				</div>
			</div>
	        </th>
        </tr>              	     
    	<tr>
            <th width="5">
                <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
            </th>
            <th width="5">
                <?php echo JText::_( 'ID' ); ?>
            </th>
            <th width="5">
                <?php echo JText::_( 'USER' ); ?>
            </th>
            <th width="50">
                <?php echo JText::_( 'DATETIME' ); ?>
            </th>
            <th width="60%">
                <?php echo JText::_( 'VALUES' ); ?>
            </th>
        </tr> 
        
    </thead>
    <?php    
    $i=0;
    if (isset($this->acc)){
	    foreach ($this->acc as &$row){
	      $checked    = JHTML::_( 'grid.id', $i++, $row->id );
	      //$link = JRoute::_( 'index.php?option=com_listmanager&controller=access&task=edit&idlisting='.$this->item['id'].'&cid[]='. $row->id );
	        ?>
	        <tr>
	            <td><?php echo $checked; ?></td>
	            <td><?php echo $row->id; ?></td>	            
	            <td><?php echo $row->username.' (#'.$row->iduser.')';?></td>
	            <td><?php echo $row->searchdatetime; ?></a></td>     
	            <td><?php $terms=json_decode($row->terms, true);
	            foreach ($terms as $key=>$value):
	            	echo $key.' : '.$value.'<br/>';
	            endforeach;	            
	            ?></td>
	        </tr>
	        <?php
	        $i = 1 - $i;
	    }
    }
    ?>
    <tfoot>
    <tr>
    	<td colspan="8"><?php echo $this->pagenav->getListFooter();?></td>
    </tr>
    </tfoot>
    </table>    
</div>
 
<input type="hidden" name="option" value="com_listmanager" />
<input type="hidden" name="task" value="show" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="search" />
<input type="hidden" name="format" value="html" />
<input type="hidden" name="idlisting" value="<?php echo $this->item['id'];?>" /> 
</form>

