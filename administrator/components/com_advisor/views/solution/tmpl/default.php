<?php defined('_JEXEC') or die('Restricted access');
/*
 * @component %%COMPONENTNAME%% 
 * @copyright Copyright (C) August 2017. 
 * @license GPL 3.0
 * This program is free software: you can redistribute it and/or modify it under the terms of the 
 * GNU General Public License as published by the Free Software Foundation, either version 3 of the License, 
 * or (at your option) any later version.
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 * See the GNU General Public License for more details.
 * See <http://www.gnu.org/licenses/>.
 * More info www.moonsoft.es gestion@moonsoft.es 
 */
include JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'helper'.DS.'helper.php';
  
?>
<form action="index.php" method="post" name="adminForm"  id="adminForm">
<div class="bg_white">
<div id="editcell">
    <table class="table table-hover">
    <thead>
        <tr>
            <th width="20">
                <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
            </th>
            <th width="5">
                <?php echo JText::_( 'ID' ); ?>
            </th>
            <th width="150">
                <?php echo JText::_( 'PRODUCT' ); ?>
            </th>
            <th width="80%">
                <?php echo JText::_( 'STEPS' ); ?>
            </th>                        
        </tr>            
    </thead>
    <?php
    $k=0;
    $i=0;
    foreach ($this->items as &$row){
      $checked = JHTML::_( 'grid.id', -1, $row->id );
      $link = JRoute::_( 'index.php?option=com_advisor&controller=solution&task=edit&idflow='.$this->idflow.'&cid[]='. $row->id );
        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $checked; ?>
            </td>            
            <td>
                <?php echo $row->id; ?>
            </td>
            <td>
                <a href="<?php echo $link; ?>"><?php echo $row->product; ?></a>
            </td>
            <td>
            	<table class="table table-striped adminlist">
	            	<tr>
	            	<?php
	            	$num_solutions=0; 
	            	foreach ($this->solutions as $solution){
		            	if($solution['idsolution']==$row->id){$num_solutions++;} 
	            	}
	            	if ($num_solutions>0) $width_solutions=100/$num_solutions;
	            	else $width_solutions=100;
	            	?>
		            <?php foreach ($this->solutions as $solution){?>
		            	<?php if($solution['idsolution']==$row->id){?>
		            	<td width="<?php echo $width_solutions?>%"><span style="font-weight:bold; font-size:1.1em;"><?php echo $solution['stepname'];?></span><hr>&nbsp;<?php echo $solution['optionname'];?></td>
		            	<?php } ?>		            	
		            <?php } ?>
		            </tr>
	            </table>
            </td>            
        </tr>
        <?php
        $k = 1 - $k;
        $i++;
    }
    ?>
    <tfoot>
    <td colspan="6"><?php echo $this->pagenav->getListFooter();?></td>
    </tfoot>
    </table>    
</div>
 
<input type="hidden" name="option" value="com_advisor" />
<input type="hidden" name="task" value="show" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="solution" />
<input type="hidden" name="idflow" value="<?php echo $this->idflow;?>" />
</div> 
</form>