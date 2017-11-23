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
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div id="editcell">
    <table class="adminlist">
    <tbody>
    	<?php if (isset($this->item['id'])){ ?>
        <tr>        	
        	<th width="250">
                <?php echo JText::_( 'ID' ); ?>
            </th>
            <td>
            	<?php echo $this->item['id'];?>
            </td>
            <td></td><td></td>
        </tr>
        <?php } ?>
        <tr>
            <th  width="250">
                <?php echo JText::_( 'PRODUCT' ); ?>
            </th>
            <td>
            	<select name="idproduct">
            	<option value="0"><?php echo JText::_( 'ADVISOR_PRODUCT' ); ?></option>
            	<?php foreach ($this->products as $product){?>
            	<option value="<?php echo $product->id;?>" <?php if($product->id==$this->item['idproduct']){echo 'selected';}?>><?php echo $product->title;?></option>
            	<?php } ?>
            	</select>
            </td>
            <td>
            	<?php if($this->hikaproducts!=null){?>
	            	<select name="idhikaproduct">
	            	<option value="0"><?php echo JText::_( 'HIKA_PRODUCT' ); ?></option>
	            	<?php foreach ($this->hikaproducts as $hikaproduct){?>
	            	<option value="<?php echo $hikaproduct->product_id;?>" <?php if($hikaproduct->product_id==$this->item['idhikaproduct']){echo 'selected';}?>><?php echo $hikaproduct->product_name;?></option>
	            	<?php } ?>
            		</select>
            	<?php } ?>
            </td>
            
            <td>
            	<?php if($this->virtueproducts!=null){?>
	            	<select name="idvirtueproduct">
	            	<option value="0"><?php echo JText::_( 'VM_PRODUCT' ); ?></option>
	            	<?php foreach ($this->virtueproducts as $virtueproduct){?>
	            	<option value="<?php echo $virtueproduct->virtuemart_product_id;?>" <?php if($virtueproduct->virtuemart_product_id==$this->item['idvirtueproduct']){echo 'selected';}?>><?php echo $virtueproduct->product_name;?></option>
	            	<?php } ?>
	            	</select> 
	            	<!-- <input type="text" name="idvirtueproduct" value="<?php echo $this->item['idvirtueproduct']!=null?$this->item['idvirtueproduct']:'0'?>"/>-->
	            <?php } ?>
            </td>
            
            
            
            <td>
            	<?php if($this->joomlaproducts!=null){?>
	            	<select name="idjoomlaproduct">
	            	<option value="0"><?php echo JText::_( 'JOOMLA_PRODUCT' ); ?></option>
	            	<?php foreach ($this->joomlaproducts as $joomlaproduct){?>
	            	<option value="<?php echo $joomlaproduct->joomla_product_id;?>" <?php if($joomlaproduct->joomla_product_id==$this->item['idjoomlaproduct']){echo 'selected';}?>><?php echo $joomlaproduct->product_name;?></option>
	            	<?php } ?>
	            	</select>
	            <?php } ?>
            </td>
            
            
        </tr>        
        <tr>
            <th colspan="5">
                <?php echo JText::_( 'STEPS' ); ?>
            </th>                        
        </tr>            
        <tr>
        	<table  class="adminlist">
        	<tr>
        	<?php foreach ($this->steps as $asteps){?>
            <td>            	            	
            	<span style="font-weight:bold; font-size:1.1em;"><?php echo $asteps->name?></span><hr />
            	<select name="step_<?php echo $asteps->id;?>" style="width:auto">
            	<option value=""><?php echo JText::_("NOVALUE")?></option>
            	<?php if (isset($this->stepoptions[$asteps->id])){?>
					<?php foreach ($this->stepoptions[$asteps->id] as $option){?>
						<option value="<?php echo $option['id'];?>" 
							<?php if(isset($this->solutions[$asteps->id])&&$option['id']==$this->solutions[$asteps->id]['idoption']){echo 'selected';}?>>
						<?php echo $option['desc'];?>
						</option>
					<?php } ?>
				<?php } ?>
				</select>      	            	 
            </td>
            <?php } ?>
            </tr>
            </table>                        
        </tr>        
    </tbody>
 	</table>    
</div> 
<input type="hidden" name="option" value="com_advisor" />
<input type="hidden" name="task" value="edit" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="solution" /> 
<input type="hidden" name="idflow" value="<?php echo $this->idflow;?>" />
<input type="hidden" name="idsolution" value="<?php echo $this->item['id'];?>" />
</form>