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
<?php //echo $this->__getHelp('VIEW');?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="cb_table_wrapper">
    <table class="table">
    <thead>
    	<tr>
            <th width="20">
                <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
            </th>
            <th width="5">
                <?php echo JText::_( 'ID' ); ?>
            </th>
            <th width="20%">
                <?php echo JText::_( 'NAME' ); ?>
            </th>
            <th width="75%">
                <?php echo JText::_( 'COMMENTS' ); ?>
            </th>         
        </tr>            
    </thead>
    <?php    
    $i=0;
    if($this->views!=null){
	    foreach ($this->views as &$row){
	      $checked    = JHTML::_( 'grid.id', $i++, $row->id );
	      $link = JRoute::_( 'index.php?option=com_listmanager&controller=lmvistas&task=edit&idlisting='.$this->item['id'].'&cid[]='. $row->id );
	        ?>
	        <tr class="<?php echo "row$i"; ?>">
	            <td><?php echo $checked; ?></td>
	            <td><?php echo $row->id; ?></td>
	            <td><a href="<?php echo $link; ?>"><?php echo $row->name; ?></a></td>
	            <td align="center" nowrap><?php echo $row->comments; ?></td>            
	        </tr>
	        <?php
	        $i = 1 - $i;
	    }
    }
    ?>
    <tfoot>
    <td colspan="7"><?php echo $this->pagenav->getListFooter();?></td>
    </tfoot>
    </table>    
</div> 
<input type="hidden" name="option" value="com_listmanager" />
<input type="hidden" name="task" value="show" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="lmvistas" />
<input type="hidden" name="idlisting" value="<?php echo $this->item['id'];?>" />
 
</form>

