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
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="cb_table_wrapper">
<table class="table-hover">
    <thead>
	   <tr>
            <th width="5">
                <?php echo JText::_( 'ID' ); ?>
            </th>
            <th width="50">
                <?php echo JText::_( 'TYPE' ); ?>
            </th>
            <th width="100">
                <?php echo JText::_( 'USER' ); ?>
            </th>
            <th width="120">
                <?php echo JText::_( 'DATETIME' ); ?>
            </th>
            <th width="70">
                <?php echo JText::_( 'IP' ); ?>
            </th>         
            <th width="60%">
                <?php echo JText::_( 'VALUES' ); ?>
            </th>
        </tr> 
        
    </thead>
    <?php    
    $i=0;
    if (isset($this->access)){
	    foreach ($this->access as &$row){
	      ?>
	        <tr>
	            <td><?php echo $row->id; ?></td>
	            <td style="text-align:center;">
	            <?php
	            switch ($row->type){
	            	case '0': echo JText::_('INSERT'); break;
	            	case '1': echo JText::_('UPDATE'); break;
	            	case '2': echo JText::_('DELETE'); break;
	            } 
	            ?>
	            </td>
	            <td><?php echo $row->username?></td>
	            <td><?php echo $row->dt; ?></td>     
	            <td><?php echo $row->ip; ?></td>       
	            <td><?php echo $row->value; ?></td>
	        </tr>
	        <?php
	        $i = 1 - $i;
	    }
    }
    ?>
    <tfoot>
    <tr>
    <?php if ( version_compare( JVERSION, '3.0', '>=' ) == 1) { ?><td><?php echo $this->pagenav->getLimitBox();?></td><?php } ?>
    	<td colspan="7"><?php echo $this->pagenav->getListFooter();?></td>
    </tr>
    </tfoot>
    </table>    
</div>
<input type="hidden" name="cid[]" value="<?php echo JRequest::getVar('cid[]');?>" />
<input type="hidden" name="idlisting" value="<?php echo JRequest::getVar('cid[]');?>" />
<input type="hidden" name="idrecord" value="<?php echo JRequest::getVar('idrecord');?>" />
<input type="hidden" name="option" value="com_listmanager" />
<input type="hidden" name="task" value="access" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="listing" />
<input type="hidden" name="check" value="post"/>
</form>
