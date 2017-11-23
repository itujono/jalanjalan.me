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
$pathImagen=JURI::base().'components/com_listmanager/assets/img/';
$pathImagenCalendar=$pathImagen.'calendar.png';
$document =& JFactory::getDocument();                                                                       
$css=$this->__getCSS();                                                                          
foreach ($css as $style){                                                        
	$document->addStyleSheet($style);
}
$js=$this->__getJS();
foreach ($js as $jscript){                                                        
	?><script src="<?php echo $jscript;?>"></script><?php 
}
?>
<?php //echo $this->__getHelp('ACCESS_EDIT');?>
<script>
function switchValue(to){
	$(to).value=='0'?$(to).value='1':$(to).value='0';
}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="cb_table_wrapper">
    <table class="table">       
        <tr>
            <td width="5%"><?php echo JText::_( 'ID' ); ?></td>
            <td width="40%"><?php echo $this->acc['id']; ?></td>            
        </tr>
        <tr>
            <td width="5%"><?php echo JText::_( 'TYPE' ); ?></td>
            <td width="40%">
            <?php
	            switch ($this->acc['type']){
	            	case '0': echo JText::_('INSERT'); break;
	            	case '1': echo JText::_('UPDATE'); break;
	            	case '2': echo JText::_('DELETE'); break;
	            } 
	            ?>
	         </td>            
        </tr>
        <tr>
            <td width="5%"><?php echo JText::_( 'USER' ); ?></td>
            <td width="40%"><?php echo $this->acc['username']; ?></td>            
        </tr>
        <tr>
            <td width="5%"><?php echo JText::_( 'DATETIME' ); ?></td>
            <td width="40%"><?php echo $this->acc['dt']; ?></td>            
        </tr>
        <tr>
            <td width="5%"><?php echo JText::_( 'IP' ); ?></td>
            <td width="40%"><?php echo $this->acc['ip']; ?></td>            
        </tr>
        <tr>
            <td width="5%"><?php echo JText::_( 'IDRECORD' ); ?></td>
            <td width="40%"><?php echo $this->acc['idrecord']; ?></td>            
        </tr>
        <tr>
            <td width="5%"><?php echo JText::_( 'VALUES' ); ?></td>
            <td width="40%"><!--<?php echo $this->acc['value']; ?>-->
            <table class="table-striped table-condensed" style="width:100%">
             <thead>
		          <tr>
		              <th style="width:50%"><?php echo JText::_( 'FIELD' ); ?></th>
		              <th style="width:50%"><?php echo JText::_( 'VALUE' ); ?></th>
		          </tr>            
		      </thead> 
		      <tbody>
		      <?php
		      	$rows = explode(']', $this->acc['value']);		      	
		      	for ($i=0;$i<count($rows);$i++){
		      		$rows_tmp = trim(str_replace(' [ ', '', $rows[$i]));		      		
		      		$rows_splitted=explode(' : ', $rows_tmp);
		      		if (!isset($rows_splitted[0]) || strlen($rows_splitted[0])<=0) continue;
		      		?>
		      		<tr>
						<td width="200"><?php if(!isset($rows_splitted[1])) echo str_replace(':', '', $rows_splitted[0]); 
									else echo $rows_splitted[0];?></td>		      		
						<td><?php if(isset($rows_splitted[1])) echo $rows_splitted[1];?></td>
		      		</tr>
		      		<?php 
		      	}
		      ?>
		      </tbody> 
            </table>            
            </td>            
        </tr>
    </table>     
</div>
<input type="hidden" name="idlisting" value="<?php echo $this->item['id']; ?>" />
<input type="hidden" name="idview" value="<?php echo $this->acc['id']; ?>" />
<input type="hidden" name="cid[]" value="<?php echo $this->acc['id']; ?>" />
<input type="hidden" name="option" value="com_listmanager" />
<input type="hidden" name="task" value="edit" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="access" />
<input type="hidden" name="check" value="post"/>
</form>
