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
<script type='text/javascript' src='https://www.google.com/jsapi'></script>
    <script type='text/javascript'>
      google.load('visualization', '1', {packages:['orgchart']});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Name');
        data.addColumn('string', 'Manager');
        data.addColumn('string', 'ToolTip');
        <?php $current_step=null;
        $options_row=array();
        ?>
        data.addRows([
		  [{v:'0', f:'<?php echo JText::_('ROOT')?>'}, '', ''],
          <?php
          $i=0; 
          foreach ($this->steps as $step){
          	if($step->id==$this->item['id']) $current_step=$i+1;
          ?>          
          [{v:'<?php echo $step->id;?>', 
              f:'<a href="index.php?option=com_advisor&controller=step&task=edit&idflow=<?php echo $this->idflow;?>&cid[]=<?php echo $step->id;?>"><?php echo $step->name;?></a><?php if($step->id==$this->item['id']){?><br /> <?php echo JText::_('CURRENT');?><?php }?>'}, 
              '<?php echo $step->idprevstep;?>', ''],          
			<?php
			$i++; 
			for ($o=0;$o<count($this->options);$o++){
				$option=$this->options[$o];
				if ($option['idstep']==$this->item['id']){?>
					[{v:'opt_<?php echo $option['id'];?>', 
	            		  f:'<a href="index.php?option=com_advisor&controller=option&task=edit&idflow=<?php echo $this->idflow;?>&idstep=<?php echo $this->item['id'];?>&cid[]=<?php echo $option['id'];?>"><?php echo $option['desc'];?></a>'},
	              		'<?php echo $this->item['id'];?>', ''],
          	<?php $options_row[]=$i+1; 
          			$i++;
          		}
          	}?>
          	
          <?php  
          }?>          
        ]);
        <?php if ($current_step!=null){?>
        data.setRowProperty(<?php echo $current_step;?>, 'style', 'border: 2px solid #0368c0;font-weight:bolder;background:none;background-color:#96c8e2');
        <?php }?>
        <?php foreach ($options_row as $colored_rows){?>
         data.setRowProperty(<?php echo $colored_rows;?>, 'style', 'border: 1px dashed #02298d;background:none;background-color:#fccd7b');
        <?php }?>
        var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
        chart.draw(data, {allowHtml:true});
      }
</script>
<form action="index.php" method="post" name="adminForm"  id="adminForm">
<div id="editcell">
    <table class="adminlist" style="width:800px; float:left;">
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
                <?php echo JText::_( 'NAME' ); ?>
            </th>
            <td>
            	<input type="text" class="input-medium"  name="name" size="50" value="<?php echo $this->item['name'];?>" />
            </td>
        </tr>        
        <tr>
            <th>
                <?php echo JText::_( 'TEXT' ); ?>
            </th>
            <td>
            	<?php
				$editor =JFactory::getEditor();
				echo $editor->display('text', $this->item['text'], '100%','400','100','6');
				?> 
            </td>                        
        </tr>            
        <tr>
            <th>
                <?php echo JText::_( 'PREVIOUS_STEP' ); ?>
            </th>
            <td>
            	<select name="idprevstep" style="width:auto;">
            	<option value=""><?php echo JText::_('ROOT')?></option>
            	<?php foreach ($this->allsteps as $asteps){?>            	
            	<option value="<?php echo $asteps->id?>" <?php if ($asteps->id==$this->item['idprevstep']){ echo 'selected';}?>><?php echo $asteps->name?></option>
            	<?php } ?>
            	</select>            	 
            </td>                        
        </tr>
        
        <tr>
            <th>
                <?php echo JText::_( 'PRECONDITION' ); ?>
            </th>
            <td>
            	<input type="text" class="input-medium"  name="precondition" size="50" value="<?php echo $this->item['precondition'];?>" />
            </td>                     
        </tr>      
        
        
    </tbody>
 	</table>    
</div> 
<div id="chart_div_wrapper">
	<div id='chart_div'></div>
</div>
<input type="hidden" name="option" value="com_advisor" />
<input type="hidden" name="task" value="edit" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="step" /> 
<input type="hidden" name="idflow" value="<?php echo $this->idflow;?>" />
<input type="hidden" name="idstep" value="<?php echo $this->item['id'];?>" />
</form>