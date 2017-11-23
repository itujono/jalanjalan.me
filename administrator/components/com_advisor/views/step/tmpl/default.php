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
        data.addRows([
		  [{v:'0', f:'<?php echo JText::_('ROOT')?>'}, '', '']
          <?php
          $i=0; 
          foreach ($this->steps as $step){
          	if ($i<=count($this->steps)) echo ',';
          ?>          
          [{v:'<?php echo $step->id;?>', f:'<a href="index.php?option=com_advisor&controller=step&task=edit&idflow=<?php echo $this->idflow;?>&cid[]=<?php echo $step->id;?>"><?php echo $step->name;?></a>'}, '<?php echo $step->idprevstep;?>', '']          
          <?php $i++; 
          }?>          
        ]);
        var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
        chart.draw(data, {allowHtml:true});
      }
</script>
<form action="index.php" method="post" name="adminForm"  id="adminForm">
<div class="bg_white">
<div id="editcell">
    <table class="table table-hover" style="width:800px; float:left;">
    <thead>
        <tr>
            <th width="20">
                <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
            </th>
            <th width="5">
                <?php echo JText::_( 'ID' ); ?>
            </th>
            <th width="150">
                <?php echo JText::_( 'NAME' ); ?>
            </th>
            <th width="30%">
                <?php echo JText::_( 'TEXT' ); ?>
            </th>
            <th width="30%">
                <?php echo JText::_( 'PREVIOUS_STEP' ); ?>
            </th>         
            <th width="30%">
                <?php echo JText::_( 'PRECONDITION' ); ?>
            </th>
        </tr>            
    </thead>
    <?php
    $k=0;
    $i=0;
    foreach ($this->items as &$row){
      $checked = JHTML::_( 'grid.id', -1, $row->id );
      $link = JRoute::_( 'index.php?option=com_advisor&controller=step&task=edit&idflow='.$this->idflow.'&cid[]='. $row->id );
        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $checked; ?>
            </td>            
            <td>
                <?php echo $row->id; ?>
            </td>
            <td>
                <a href="<?php echo $link; ?>"><?php echo $row->name; ?></a>
            </td>
            <td>
                <?php echo $row->text; ?>
            </td>
            <td>
                <?php echo $row->prevstep; ?>
            </td>     
            <td>
                <?php echo $row->precondition; ?>
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
<div id="chart_div_wrapper">
	<div id='chart_div'></div>
</div>
 
<input type="hidden" name="option" value="com_advisor" />
<input type="hidden" name="task" value="show" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="step" />
<input type="hidden" name="idflow" value="<?php echo $this->idflow;?>" />
</div> 
</form>