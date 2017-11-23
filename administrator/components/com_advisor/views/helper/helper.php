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
$ad_help=JComponentHelper::getComponent('com_advisor');
$registry=$ad_help->params;
$isHelper=$registry->get('helper');
if ($isHelper==1){
	 
	$document =JFactory::getDocument();                                                                       
	$document->addStyleSheet('components/com_advisor/assets/css/default.css','text/css','');
	$image_path='components/com_advisor/assets/img/';
	$controller=JRequest::getVar('controller');
	$arr_styles_fieldset=array('1'=>'','2'=>'','3'=>'','4'=>'','5'=>'');
	switch ($controller){
		case 'solution':
			$arr_styles_fieldset['5']=' ad_open';
		case 'product':
			$arr_styles_fieldset['4']=' ad_open';
		case 'option':
			$arr_styles_fieldset['3']=' ad_open';
		case 'step':
			$arr_styles_fieldset['2']=' ad_open';
		default:
			$arr_styles_fieldset['1']=' ad_open';
	}
?>
<div class="ad_explain_wrapper">	
	<fieldset class="ad_explain_step <?php echo $arr_styles_fieldset['1'];?>">
		<legend class="ad_title ad_title_1"><div class="ad_number"><img src="<?php echo $image_path.'number_1.png';?>"></div><div class="ad_number_text">First Step</div></legend>
		<div class="ad_content">Create a Flow</div>
		<div class="ad_status">You have <?php echo $this->helper_content['num_flows']?> Flows</div>
		<div class="ad_actions"><a href="index.php?option=com_advisor&controller=advisor&task=add">Add Flow</a> | <a href="index.php?option=com_advisor&controller=advisor">Flow List</a></div>
	</fieldset>	
	<fieldset class="ad_explain_step <?php echo $arr_styles_fieldset['2'];?>">
		<legend class="ad_title ad_title_2"><div class="ad_number"><img src="<?php echo $image_path.'number_2.png';?>"></div><div class="ad_number_text">Second Step</div></legend>
		<div class="ad_content">Create a Step </div>
		<div class="ad_content">An advisor flow contains steps that user needs to follow.</div>
		<?php if (isset($this->helper_content['num_steps'])){?>
		<?php $flow=JRequest::getVar("idflow");
			  if ($flow==null){
		      	$array=JRequest::getVar('cid',  -1, '', 'array');
		      	$flow=(int)$array[0];
		      }?>
		<div class="ad_status">You have <?php echo $this->helper_content['num_steps']?> Steps in <?php echo $this->helper_content['name_flow']?> Flow</div>
		<div class="ad_actions"><a href="index.php?option=com_advisor&controller=step&task=add&idflow=<?php echo $flow;?>">Add Step</a> | <a href="index.php?option=com_advisor&controller=step&idflow=<?php echo $flow;?>">Step List</a></div>
		<?php }?>
	</fieldset>
	<fieldset class="ad_explain_step <?php echo $arr_styles_fieldset['3'];?>">
		<legend class="ad_title ad_title_3"><div class="ad_number"><img src="<?php echo $image_path.'number_3.png';?>"></div><div class="ad_number_text">Third Step</div></legend>
		<div class="ad_content">Create a Options for Step</div>
		<div class="ad_content">Each step has it´s own options to choose from. User must select one to go to the next step.</div>
		<?php if (isset($this->helper_content['num_options'])){?>
		<?php $flow=JRequest::getVar("idflow");
			  $step=JRequest::getVar("idstep");
			  if ($step==null){
		      	$array=JRequest::getVar('cid',  -1, '', 'array');
		      	$step=(int)$array[0];
		      }?>
		<div class="ad_status">You have <?php echo $this->helper_content['num_options']?> Options in <?php echo $this->helper_content['name_step']?> Step</div>
		<div class="ad_actions"><a href="index.php?option=com_advisor&controller=option&task=add&idflow=<?php echo $flow;?>&idstep=<?php echo $step;?>">Add Option</a> | <a href="index.php?option=com_advisor&controller=option&idflow=<?php echo $flow;?>&idstep=<?php echo $step;?>">Option List</a></div>
		<?php }?>
	</fieldset>
	<fieldset class="ad_explain_step <?php echo $arr_styles_fieldset['4'];?>">
		<legend class="ad_title ad_title_4"><div class="ad_number"><img src="<?php echo $image_path.'number_4.png';?>"></div><div class="ad_number_text">Fourth Step</div></legend>
		<div class="ad_content">Create Product</div>
		<div class="ad_content">At the end of the flow, user gets products depending on options selected.</div>
		<?php if (isset($this->helper_content['num_products'])){?>
		<?php $flow=JRequest::getVar("idflow");
			  if ($flow==null){
		      	$array=JRequest::getVar('cid',  -1, '', 'array');
		      	$flow=(int)$array[0];
		      }?>
		<div class="ad_status">You have <?php echo $this->helper_content['num_products'];?> Products in <?php echo $this->helper_content['name_flow']?> Flow</div>
		<div class="ad_actions"><a href="index.php?option=com_advisor&controller=product&task=add&idflow=<?php echo $flow;?>">Add Product</a> | <a href="index.php?option=com_advisor&controller=product&idflow=<?php echo $flow;?>">Product List</a></div>
		<?php } ?>
	</fieldset>
	<fieldset class="ad_explain_step <?php echo $arr_styles_fieldset['5'];?>">
		<legend class="ad_title ad_title_5"><div class="ad_number"><img src="<?php echo $image_path.'number_5.png';?>"></div><div class="ad_number_text">Fifth Step</div></legend>
		<div class="ad_content">Create Solutions</div>
		<div class="ad_content">Solutions link products with options from different steps.</div>
		<?php if (isset($this->helper_content['num_solutions'])){?>
		<?php $flow=JRequest::getVar("idflow");
			  if ($flow==null){
		      	$array=JRequest::getVar('cid',  -1, '', 'array');
		      	$flow=(int)$array[0];
		      }?>
		<div class="ad_status">You have <?php echo $this->helper_content['num_solutions'];?> Solutions in <?php echo $this->helper_content['name_flow']?> Flow</div>
		<div class="ad_actions"><a href="index.php?option=com_advisor&controller=solution&task=add&idflow=<?php echo $flow;?>">Add Solution</a> | <a href="index.php?option=com_advisor&controller=solution&idflow=<?php echo $flow;?>">Solution List</a></div>
		<?php } ?>
	</fieldset>
	<fieldset class="ad_explain_step <?php echo $arr_styles_fieldset['5'];?>">
		<legend class="ad_title ad_title_6"><div class="ad_number"><img src="<?php echo $image_path.'number_6.png';?>"></div><div class="ad_number_text">Sixth Step</div></legend>
		<div class="ad_content">Publish module</div>
		<div class="ad_actions"><a href="index.php?option=com_modules">Go to Modules</a></div>
	</fieldset>	
</div>
<?php }?>