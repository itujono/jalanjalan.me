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
?> 
 <advisor>
  <flow>
  	<?php foreach ($this->flow as $key=>$value){ ?>
  	<<?php echo $key;?>><![CDATA[<?php echo $value;?>]]></<?php echo $key;?>>
  	<?php } ?>
  </flow> 
  <steps>
  	<?php foreach ($this->steps as $step){ ?>
	  	<step>
	  		<?php foreach ($step as $key=>$value){ ?>
	  			<<?php echo $key;?>><![CDATA[<?php echo $value;?>]]></<?php echo $key;?>>
	  		<?php } ?>	  			  			  		
	  	</step>
  	<?php } ?>
  </steps>   
  <options>
  	<?php foreach ($this->options as $option){?>
	  	<option>
	  		<?php foreach ($option as $k=>$v){ ?>
	  			<<?php echo $k;?>><![CDATA[<?php echo $v;?>]]></<?php echo $k;?>>
	  		<?php } ?>	  			  		
	  	</option>
  	<?php } ?>
  </options>
  <products>
  	<?php foreach ($this->products as $product){ ?>
	  	<product>
	  		<?php foreach ($product as $key=>$value){ ?>
	  			<<?php echo $key;?>><![CDATA[<?php echo $value;?>]]></<?php echo $key;?>>
	  		<?php } ?>	  			  			  		
	  	</product>
  	<?php } ?>
  </products> 
  <solutions>
  	<?php foreach ($this->solutions as $solution){ ?>
	  	<solution>
	  		<?php foreach ($solution as $key=>$value){ ?>
	  			<<?php echo $key;?>><![CDATA[<?php echo $value;?>]]></<?php echo $key;?>>
	  		<?php } ?>	  			  			  		
	  	</solution>
  	<?php } ?>
  </solutions>        
  <solutionsoptions>
  	<?php foreach ($this->solutionsoptions as $solutionsoption){ ?>
	  	<solutionsoption>
	  		<?php foreach ($solutionsoption as $k=>$v){ ?>
	  			<<?php echo $k;?>><![CDATA[<?php echo $v;?>]]></<?php echo $k;?>>
	  		<?php } ?>	  			  		
	  	</solutionsoption>
  	<?php } ?>
  </solutionsoptions> 
</advisor>
