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
 $document=JFactory::getDocument();                                                                       
?>
<style>
<!--
#chart_div .google-visualization-orgchart-node{
	border:1px solid black;
}
#chart_div .google-visualization-orgchart-table {
  border: 0;
  text-align: center;
}
#chart_div .google-visualization-orgchart-table * {
  margin: 0;
  padding: 2px;
}
#chart_div .google-visualization-orgchart-space-small {
  width: 4px;
  height: 1px;
  border: 0;
}
#chart_div .google-visualization-orgchart-space-medium {
  width: 10px;
  height: 1px;
  border: 0;
}
#chart_div .google-visualization-orgchart-space-large {
  width: 16px;
  height: 1px;
  border: 0;
}
#chart_div .google-visualization-orgchart-noderow-small {
  height: 12px;
  border: 0;
}
#chart_div .google-visualization-orgchart-noderow-medium {
  height: 30px;
  border: 0;
}
#chart_div .google-visualization-orgchart-noderow-large {
  height: 46px;
  border: 0;
}
#chart_div .google-visualization-orgchart-connrow-small {
  height: 2px;
  font-size: 1px;
}
#chart_div .google-visualization-orgchart-connrow-medium {
  height: 6px;
  font-size: 4px;
}
#chart_div .google-visualization-orgchart-connrow-large {
  height: 10px;
  font-size: 8px;
}
#chart_div .google-visualization-orgchart-node {
  text-align: center;
  vertical-align: middle;
  font-family: arial,helvetica;
  cursor: default;
  border: 2px solid #b5d9ea;
  -moz-border-radius: 5px;
  -webkit-border-radius: 5px;
  -webkit-box-shadow: rgba(0, 0, 0, 0.5) 3px 3px 3px;
  -moz-box-shadow: rgba(0, 0, 0, 0.5) 3px 3px 3px;
  background-color: #edf7ff;
  background: -webkit-gradient(linear, left top, left bottom, from(#edf7ff), to(#cde7ee));
}
#chart_div .google-visualization-orgchart-nodesel {
  border: 2px solid #e3ca4b;
  background-color: #fff7ae;
  background: -webkit-gradient(linear, left top, left bottom, from(#fff7ae), to(#eee79e));
}
#chart_div .google-visualization-orgchart-node-small {
  font-size: 0.6em;
}
#chart_div .google-visualization-orgchart-node-medium {
  font-size: 0.8em;
}
#chart_div .google-visualization-orgchart-node-large {
  font-size: 1.2em;
  font-weight: bold;
}
#chart_div .google-visualization-orgchart-linenode {
  border: 0;
}
#chart_div .google-visualization-orgchart-lineleft {
  border-left: 1px solid #3388dd;
}
#chart_div .google-visualization-orgchart-lineright {
  border-right: 1px solid #3388dd;
}
#chart_div .google-visualization-orgchart-linebottom {
  border-bottom: 1px solid #3388dd;
}

-->
</style>
<form action="index.php" method="post" name="adminForm" id="adminForm"  class="form-horizontal">
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
          [{v:'<?php echo $step->id;?>', f:'<a href="index.php?option=com_advisor&controller=step&task=edit&idflow=<?php echo $this->item['id'];?>&cid[]=<?php echo $step->id;?>"><?php echo $step->name;?></a>'}, '<?php echo $step->idprevstep;?>', '']          
          <?php $i++; 
          }?>          
        ]);
        var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
        chart.draw(data, {allowHtml:true});        
      }
      window.addEvent('load',function(){
          jQuery('#tabsdiv a:first').tab('show');
      });
</script>
<div class="bg_white">
<div class="row-fluid">
      <div class="span8">
      
<ul class="nav nav-tabs" id="tabsdiv">
<li class="tab"><a href="#main"  data-toggle="tab"><?php echo JText::_('MAIN')?></a></li>
<li class="tab"><a href="#mainpage"  data-toggle="tab"><?php echo JText::_('MAINPAGE')?></a></li>
<li class="tab"><a href="#pdf"  data-toggle="tab"><?php echo JText::_('PDF')?></a></li>
</ul>    
    <div class="tab-content">
    <div class="tab-pane" id="main">
    	<table class="table adminlist">
    <tbody>
    	<?php if (isset($this->item['id'])){ ?>
        <tr>        	
        	<th>
                <?php echo JText::_( 'ID' ); ?>
            </th>
            <td>
            	<?php echo $this->item['id'];?>
            </td>            
        </tr>
        <?php } ?>
        <tr>
            <th  width="200">
                <?php echo JText::_( 'TITLE' ); ?>
            </th>
            <td>
            	<input type="text"  name="title" class="input-medium" value="<?php echo $this->item['title'];?>" />
            </td>
        </tr>
        <tr>
            <th>
                <?php echo JText::_( 'PUBLISHED' ); ?>
            </th>
            <td>
            	<select name="published" style="width:auto">
            	<option value="0" <?php if ($this->item['published']==0) echo 'selected';?>><?php echo JText::_( 'AD_NO' ); ?></option>
            	<option value="1" <?php if ($this->item['published']==1) echo 'selected';?>><?php echo JText::_( 'AD_YES' ); ?></option>
            	</select> 
            </td>                        
        </tr>
        <tr>
            <th>
                <?php echo JText::_( 'VIEWRESUME' ); ?>
            </th>
            <td>
            	<select name="viewresume" style="width:auto">
            	<option value="0" <?php if ($this->item['viewresume']==0) echo 'selected';?>><?php echo JText::_( 'AD_NO' ); ?></option>
            	<option value="1" <?php if ($this->item['viewresume']==1) echo 'selected';?>><?php echo JText::_( 'AD_YES' ); ?></option>
            	</select> 
            </td>                        
        </tr>
        <tr>
            <th>
                <?php echo JText::_( 'VIEWPDF' ); ?>
            </th>
            <td>
            	<select name="viewpdf" style="width:auto">
            	<option value="0" <?php if ($this->item['viewpdf']==0) echo 'selected';?>><?php echo JText::_( 'AD_NO' ); ?></option>
            	<option value="1" <?php if ($this->item['viewpdf']==1) echo 'selected';?>><?php echo JText::_( 'AD_YES' ); ?></option>
            	</select> 
            </td>                        
        </tr>
        <tr>
            <th rowspan="2">
                <?php echo JText::_( 'CONTAINER' ); ?>
            </th>
            <td>
            	<div class="columns">
            		<?php echo JText::_( 'NUM_COLUMNS' ); ?>
            	</div>
            	<div class="radio_col">
            		<label for="c0"><?php echo JText::_( 'ONE_COLUMN' ); ?></label>
            		<input type="radio" id="c0" name="container" value="0" <?php if($this->item['container']==0) echo 'checked';?>>
            	</div>
            	<div class="radio_col">
            		<label for="c1"><?php echo JText::_( 'TWO_COLUMNS' ); ?></label>
            		<input type="radio" id="c1" name="container" value="1" <?php if($this->item['container']==1) echo 'checked';?>>
            	</div>
            	<div class="radio_col">
            		<label for="c2"><?php echo JText::_( 'THREE_COLUMNS' ); ?></label>
            		<input type="radio" id="c2" name="container" value="2" <?php if($this->item['container']==2) echo 'checked';?>>
            	</div>
            	<div class="radio_col">
            		<label for="c3"><?php echo JText::_( 'FOUR_COLUMNS' ); ?></label>
            		<input type="radio" id="c3" name="container" value="3" <?php if($this->item['container']==3) echo 'checked';?>>
            	</div>
            </td>
         </tr>
         <tr>
            <td>
            	<div class="columns_normal">
            		<?php echo JText::_( 'CONTAINER_WIDTH' ); ?>
            	</div>
            	<div class="input_normal">
            		<input type="text" size="10" name="containerwidth" class="input-mini" value="<?php echo $this->item['containerwidth'];?>">
            	</div>
            	<div class="input_normal">
            		<?php echo JText::_('CONTAINER_WIDTH_EXAMPLE');?>
            	</div>            	
            </td>
        </tr>
        <tr>
            <th rowspan="2">
                <?php echo JText::_( 'CONTAINER_STEP' ); ?>
            </th>
            <td>
            	<div class="columns">
            		<?php echo JText::_( 'NUM_COLUMNS' ); ?>
            	</div>
            	<div class="radio_col">
            		<label for="c0"><?php echo JText::_( 'ONE_COLUMN' ); ?></label>
            		<input type="radio" id="c0" name="containerstep" value="0" <?php if($this->item['containerstep']==0) echo 'checked';?>>
            	</div>
            	<div class="radio_col">
            		<label for="c1"><?php echo JText::_( 'TWO_COLUMNS' ); ?></label>
            		<input type="radio" id="c1" name="containerstep" value="1" <?php if($this->item['containerstep']==1) echo 'checked';?>>
            	</div>
            	<div class="radio_col">
            		<label for="c2"><?php echo JText::_( 'THREE_COLUMNS' ); ?></label>
            		<input type="radio" id="c2" name="containerstep" value="2" <?php if($this->item['containerstep']==2) echo 'checked';?>>
            	</div>
            	<div class="radio_col">
            		<label for="c3"><?php echo JText::_( 'FOUR_COLUMNS' ); ?></label>
            		<input type="radio" id="c3" name="containerstep" value="3" <?php if($this->item['containerstep']==3) echo 'checked';?>>
            	</div>
            </td>
         </tr>
         <tr>
            <td>
            	<div class="columns_normal">
            		<?php echo JText::_( 'CONTAINER_WIDTH' ); ?>
            	</div>
            	<div class="input_normal">
            		<input type="text" size="10" name="containerstepwidth" class="input-mini" value="<?php echo $this->item['containerstepwidth'];?>">
            	</div>
            	<div class="input_normal">
            		<?php echo JText::_('CONTAINER_WIDTH_EXAMPLE');?>
            	</div>            	
            </td>
        </tr>        
        <tr>
            <th rowspan="2">
                <?php echo JText::_( 'CONTAINER_MAIN' ); ?>
            </th>            
            <td>
            	<div class="columns_normal">
            		<?php echo JText::_( 'CONTAINER_WIDTH_RESUME' ); ?>
            	</div>
            	<div class="input_normal">
            		<input type="text" size="10" name="containerstepresume" class="input-mini" value="<?php echo $this->item['containerstepresume'];?>">
            	</div>
            	<div class="input_normal">
            		<?php echo JText::_('CONTAINER_WIDTH_EXAMPLE');?>
            	</div>            	
            </td>
        </tr>
        <tr>
        	<td>
            	<div class="columns_normal">
            		<?php echo JText::_( 'CONTAINER_HEIGHT' ); ?>
            	</div>
            	<div class="input_normal">
            		<input type="text" size="10" name="containerheight" class="input-mini" value="<?php echo $this->item['containerheight'];?>">
            	</div>
            	<div class="input_normal">
            		<?php echo JText::_('CONTAINER_WIDTH_EXAMPLE');?>
            	</div>            	
            </td>
        </tr>
     </tbody>
 	</table>  
        </div>
        <div class="tab-pane" id="mainpage">
     <table class="table adminlist">
    <tbody>
        <tr>
            <th>
                <?php echo JText::_( 'FIRST_PAGE' ); ?>
            </th>
            <td>
            	<?php
				$editor =JFactory::getEditor();
				echo $editor->display('firstpage', $this->item['firstpage'], '100%','400','100','6');
				?> 
            </td>                        
        </tr>
        </tbody>
 	</table>  
        </div>
        <div class="tab-pane" id="pdf"> 
        <table class="table adminlist">
    <tbody>
        <tr>
            <th>
                <?php echo JText::_( 'PREHTML' ); ?>
            </th>
            <td>
            	<?php
				$editor =JFactory::getEditor();
				echo $editor->display('prehtml', $this->item['prehtml'], '100%','400','100','6');
				?> 
            </td>                        
        </tr>
        <tr>
            <th>
                <?php echo JText::_( 'POSTHTML' ); ?>
            </th>
            <td>
            	<?php
				$editor =JFactory::getEditor();
				echo $editor->display('posthtml', $this->item['posthtml'], '100%','400','100','6');
				?> 
            </td>                        
        </tr>
          </tbody>
 	</table> 
        </div>
        </div>
 
<input type="hidden" name="option" value="com_advisor" />
<input type="hidden" name="task" value="edit" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="advisor" /> 
<input type="hidden" name="idflow" value="<?php echo $this->item['id'];?>" />
</div>
<div class="span4"><div id='chart_div' style="margin-top:10px;"></div>
</div>
</div>  
</form>




