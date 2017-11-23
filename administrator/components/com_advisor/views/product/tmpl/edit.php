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
<div id="editcell">
    <table class="adminlist">
    <tbody>
    	<?php if (isset($this->item['id'])){ ?>
        <tr>        	
        	<th width="250">
                <?php echo JText::_( 'ID' ); ?>
            </th>
            <td>
            	<?php echo $this->item['id'];?>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <th  width="250">
                <?php echo JText::_( 'TITLE' ); ?>
            </th>
            <td>
            	<input type="text"  name="title" size="50" value="<?php echo $this->item['title'];?>" />
            </td>
        </tr>        
        <tr>
            <th>
                <?php echo JText::_( 'CONTENT' ); ?>
            </th>
            <td>
            	<?php
				$editor =JFactory::getEditor();
				echo $editor->display('contentproduct', $this->item['content'], '100%','400','100','6');
				?> 
            </td>                        
        </tr>
    </tbody>
 	</table>    
</div> 
<input type="hidden" name="option" value="com_advisor" />
<input type="hidden" name="task" value="edit" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="product" /> 
<input type="hidden" name="idflow" value="<?php echo $this->idflow;?>" />
<input type="hidden" name="idproduct" value="<?php echo $this->item['id'];?>" />
<input type="hidden" name="order" value="<?php echo $this->item['order'];?>" />
</form>