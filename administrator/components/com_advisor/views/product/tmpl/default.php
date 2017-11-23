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

$image_path='components/com_advisor/assets/img/';
include JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'helper'.DS.'helper.php';

?>
<script>
jQuery(document).ready(function(){
	reorder();
});
function reorder(){
	var imageup='<?php echo $image_path.'up.png';?>';
	var imagedown='<?php echo $image_path.'down.png';?>';
	var numElements=$$('.tdorder').length;
	$$('.tdorder').each(function(elem,index){
		if (index>0){
			var elemup=new Element('img',{'src':imageup});
			elemup.setProperty('title','<?php echo JText::_("UP")?>');
			elemup.setStyle('cursor','pointer');
			elemup.addEvent('click',function(elem){
				sendOrder(elem.target,'up');							
			});
			$(elem).grab(elemup);
		} else {
			var elemup=new Element('div');
			elemup.setStyle('width','16px');
			elemup.setStyle('float','left');
			elemup.set('html','&nbsp;');
			$(elem).grab(elemup);
		}		
		if (index<numElements-1){			
			var elemdown=new Element('img',{'src':imagedown});
			elemdown.setProperty('title','<?php echo JText::_("DOWN")?>');
			elemdown.setStyle('cursor','pointer');
			elemdown.setStyle('margin-left','9px');
			elemdown.addEvent('click',function(elem){
				sendOrder(elem.target,'down');							
			});
			$(elem).grab(elemdown);
		}
	});
}

function sendOrder(elem, direction){
	var td=$(elem).getParent();
	var idproduct=$(td).getElement('input[name="pid"]').getProperty('value');
	var prev_pid=$(td).getElement('input[name="prev_pid"]').getProperty('value');
	var next_pid=$(td).getElement('input[name="next_pid"]').getProperty('value');
	$('orderForm').getElement('#idproduct').setProperty('value',idproduct);
	$('orderForm').getElement('#direction').setProperty('value',direction);
	$('orderForm').getElement('#prev_pid').setProperty('value',prev_pid);
	$('orderForm').getElement('#next_pid').setProperty('value',next_pid);
	$('orderForm').submit();
}
</script>
<form action="index.php" method="post" name="orderForm" id="orderForm">
<input type="hidden" name="option" value="com_advisor" />
<input type="hidden" name="task" value="order" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="product" />
<input type="hidden" name="idflow" value="<?php echo $this->idflow;?>" />
<input type="hidden" name="idproduct" id="idproduct" value="">
<input type="hidden" name="direction" id="direction" value="">
<input type="hidden" name="prev_pid" id="prev_pid" value="">
<input type="hidden" name="next_pid" id="next_pid" value="">
</form>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="bg_white">
<div id="table editcell">
    <table class="table table-hover">
    <thead>
        <tr>
            <th width="20">
                <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
            </th>
            <th width="5">
                <?php echo JText::_( 'ID' ); ?>
            </th>
            <th width="90%">
                <?php echo JText::_( 'TITLE' ); ?>
            </th>
            <th width="40">
                <?php echo JText::_( 'ORDER' ); ?>
            </th>                        
        </tr>            
    </thead>
    <?php
    $k=0;
    //foreach ($this->items as &$row){
    for($i=0;$i<count($this->items);$i++){    	
      $row=$this->items[$i];
      $prev_row=-1;
      if($i>0) $prev_row=$this->items[$i-1]->id;
      $next_row=-1;
      if(($i+1)<count($this->items)) $next_row=$this->items[$i+1]->id;      
      $checked = JHTML::_( 'grid.id', -1, $row->id );
      $link = JRoute::_( 'index.php?option=com_advisor&controller=product&task=edit&idflow='.$this->idflow.'&cid[]='. $row->id );
        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $checked; ?>
            </td>            
            <td>
                <?php echo $row->id; ?>
            </td>
            <td>
                <a href="<?php echo $link; ?>"><?php echo $row->title; ?></a>
            </td>
            <td class="tdorder">
            	<input type="hidden" name="prev_pid" value="<?php echo $prev_row; ?>"/>
            	<input type="hidden" name="pid" value="<?php echo $row->id; ?>"/>
            	<input type="hidden" name="next_pid" value="<?php echo $next_row; ?>"/>
            	<!--<?php echo $row->order; ?>-->
            </td>            
        </tr>
        <?php
        $k = 1 - $k;
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
<input type="hidden" name="controller" value="product" />
<input type="hidden" name="idflow" value="<?php echo $this->idflow;?>" /> 
</div>
</form>