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
$pathImagen='components/com_advisor/assets/img/export.png';


?>


 <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
      
      
      
      <?php
      foreach($this->datos as $flow){
      
      
      ?>
       var data = google.visualization.arrayToDataTable([
          ['Option', 'Times selected'],
      <?php
      
        foreach($flow['countvalues'] as $key=>$count){
        
        /*echo "//".$key.":".$count;
         ['2004',  1000],
          ['2005',  1170],
          ['2006',  660],
          ['2007',  1030]
        */
        
        echo "['".$key."',".$count."],";
        
        }
      ?>
      
       
         
        ]);

        var options = {
          title: '<?php echo $flow["title"];?>',
          hAxis: {title: 'Option', titleTextStyle: {color: 'black'}}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div<?php echo $flow["id"];?>'));
        <?php if(count($flow['countvalues'])>0){ ?>
        
        chart.draw(data, options);
        
        <?php } ?>
        
        <?php
        } 
        ?>
      }

 
 function getCSV(idflow){
 
	$('idflow').setProperty('value',idflow);
	$('csvform').submit();
}
    </script>
    <form action="index.php" method="post" id="csvform" name="csvform">
    <input type="hidden" name="option" value="com_advisor"/>
    <input type="hidden" name="controller" value="advisor"/>
    <input type="hidden" name="task" value="exportcsv"/>
    <input type="hidden" name="format" value="csv"/>
    <input type="hidden" name="boxchecked" value="0" />    
    <input type="hidden" name="idflow" id="idflow" value="" />
    <?php echo JHtml::_( 'form.token' ); ?>
    </form> 



<?php

$pathImagen=JURI::base().'components/com_advisor/assets/img/';


//var_dump($this->datos);

foreach($this->datos as $flow){

echo "<h1>".$flow['title']."</h1><hr>";
   $indatos=$flow['datos'];
   
   ?>
   <p style="text-align:center">
   
   <?php 
   
   $sizedivchart=0;
   
    if(count($flow['countvalues'])>0) $sizedivchart=200;
   ?>
   
   
  <div id="chart_div<?php echo $flow['id']?>" style=" height: <?php echo $sizedivchart;?>px;width:100%;"></div>
  
  </p>  
  <span style='text-align:right'><img style="cursor:pointer;" onclick="getCSV('<?php echo $flow['id'];?>')" src='<?php echo $pathImagen;?>export-csv.png'/> <a href="javascript:getCSV('<?php echo $flow['id'];?>')"> <?php echo JText::_( 'EXPORT_CSV' ); ?></a></span>
  
   <table class="table table-hover">
<thead>
<tr>

<th> Date </th>
<th colspan="40"> Steps </th>
</tr>
</thead>
  <?php 
   foreach($indatos as $dato){
   
   
   
   
     echo "<tr><td>".$dato['date']."</td>";
     
     $pasos=$dato['lstvalues'];
     
     foreach($pasos as $paso){
      echo "<td>".$paso['desc']." (".$paso['value'].")</td>";
     
     }
     
      echo "</tr>";
     //var_dump($dato);
   }
   
   echo "</table>";


} 
?>


<form action="index.php" method="post" name="adminForm" id="adminForm">

<input type="hidden" name="option" value="com_advisor" />
<input type="hidden" name="task" value="show" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="advisor" />


</form>
