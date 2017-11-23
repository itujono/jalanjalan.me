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
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="bg_white">
<div id="editcell">
    <table class="table table-hover">
    <thead>
        <tr>
            <th width="5">
                <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
            </th>
            <th width="5">
                <?php echo JText::_( 'ID' ); ?>
            </th>
            <th width="70%">
                <?php echo JText::_( 'DESC' ); ?>
            </th>
            <th width="25%">
                <?php echo JText::_( 'VALUE' ); ?>
            </th>
            <th width="5">
                <?php echo JText::_( 'ORDER' ); ?>
            </th>            
        </tr>            
    </thead>
    <?php
    $k=0;
    $i=0;
    foreach ($this->items as &$row){
      $checked = JHTML::_( 'grid.id', -1, $row->id );
      $link = JRoute::_( 'index.php?option=com_advisor&controller=option&task=edit&idflow='.$this->idflow.'&idstep='.$this->idstep.'&cid[]='. $row->id );
        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $checked; ?>
            </td>            
            <td>
                <?php echo $row->id; ?>
            </td>
            <td>
                <a href="<?php echo $link; ?>"><?php echo $row->desc; ?></a>
            </td>
            <td>
                <?php echo $row->value; ?>
            </td>
            <td>
                <input type="text" name="ord<?php echo $row->id; ?>" style="width:20px;" value="<?php echo $row->order; ?>"/>
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
<input type="hidden" name="controller" value="option" />
<input type="hidden" name="idflow" value="<?php echo $this->idflow;?>" /> 
<input type="hidden" name="idstep" value="<?php echo $this->idstep;?>" />
</div>
</form>