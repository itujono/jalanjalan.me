<?php
AImporter::model('order');
$id = JFactory::getApplication ()->input->get ( 'order_id' );
$model = new BookProModelOrder ();
$this->order = $model->getComplexItem ( $id );
OrderStatus::init();

?>

<form class="form-horizontal" action="index.php" method="post">

<div class="row-fluid">
	<?php echo BookProHelper::renderLayout('menu_pos', null)?>

</div>
<div class="well well-small">

<div class="text-center">
<ul class="inline">
        <li> <?php echo JText::_('COM_BOOKPRO_ORDER_STATUS') ?></li>
        <li><?php if($this->order->order_status=='CONFIRMED'){
        				
						echo OrderStatus::format($this->order->order_status);
					}else {
							echo JHtmlSelect::genericlist(OrderStatus::$map,'order_status', 'class="td_orderstatus input-medium"','value', 'text',$this->order->order_status);
					
					}
					
					
					?> </dd>
        <li><?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER') ?></li>
        <li><?php echo $this->order->order_number ?></li>
        
        <li><input type="submit" class="btn btn-primary" value="<?php echo JText::_('JSAVE') ?>"></li>
    </ul>
    
    
    </div>
    <hr/>
 <div class="row-fluid">
<div class="span6">
<?php echo $this->loadTemplate('customer') ?>
</div>
<div class="span6">
<?php echo $this->loadTemplate('tripinfo') ?>
</div>
</div>

<?php

$passengers=$this->order->passengers;
?>

<div class="lead">
	<span><?php echo JText::_('COM_BOOKPRO_PASSENGER_INFO')?> </span>
</div>

<table class="table" id="pax_table_id">
				<thead>

					<tr class="passenger_title">
					 <?php if ($this->config->get('ps_gender')){?>
					<td><?php echo JText::_('COM_BOOKPRO_PASSENGER_GENDER')?>
					
					</td>				  
				  <?php }?>
				  
				  <td><?php echo JText::_('COM_BOOKPRO_PASSENGER_FIRSTNAME')?></td>
					
				  <?php if ($this->config->get('ps_lastname')){?>
				  <td><?php echo JText::_('COM_BOOKPRO_PASSENGER_LASTNAME')?></td>
					<?php } ?>
						
				  <?php if ($this->config->get('ps_email')){?>
				  <td><?php echo JText::_('COM_BOOKPRO_PASSENGER_EMAIL')?></td>
					<?php } ?>
					
				 
						
				  <?php if ($this->config->get('ps_notes')){?>
				  <td><?php echo JText::_('COM_BOOKPRO_PASSENGER_NOTES')?></td>
					<?php } ?>
					
				  <?php if ($this->config->get('ps_birthday')){?>	
					<td><?php echo JText::_('COM_BOOKPRO_PASSENGER_BIRTHDAY')?>
					</td>
					<?php } ?>

				  <?php if ($this->config->get('ps_passport')){?>
					<td><?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT')?></td>
					<?php } ?>
				  <?php if ($this->config->get('ps_ppvalid')){?>
					<td><?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT_EXPIRED')?></td>
					<?php } ?>
				  <?php if ($this->config->get('ps_country')){?>
					<td><?php echo JText::_('COM_BOOKPRO_PASSENGER_COUNTRY')?>
					</td>
					<?php } ?>
					<td><?php echo JText::_('COM_BOOKPRO_PASSENGER_GROUP')?></td>
					

					</tr>
					</thead>
					<tbody>
					<?php foreach ($passengers as $pax){ {
						
						$fieldname="person[".$pax->id."]";
						
					?>
					
					
					<tr class="busstopclone" style="border-top: 1px solid #ccc;">
					 <?php if ($this->config->get('ps_gender')){?>
					<td>
					
					<?php echo JHtml::_('select.genericlist',BookProHelper::getGender(), $fieldname.'[gender]','class="inputbox input-small"','value','text',1)?>
					</td>				  
				  <?php }?>
				  
				  <td>
				  <input type="hidden" name="<?php echo $fieldname ?>[id]" value="<?php echo $pax->id ?>"/>
				  <input type="text" name="<?php echo $fieldname?>[firstname]" required value="<?php echo $pax->firstname?>"
							class="input-medium" /></td>
					
				  <?php if ($this->config->get('ps_lastname')){?>
				  <td><input type="text" name="<?php echo $fieldname?>[lastname]" required value="<?php echo $pax->lastname?>"
							class="input-small" /></td>
					<?php } ?>
						
				  <?php if ($this->config->get('ps_email')){?>
				  <td><input type="text" name="<?php echo $fieldname?>[email]" required class="input-small" value="<?php echo $pax->email?>" /></td>
					<?php } ?>
					
					
					<!-- <td><input type="text" name="<?php echo $fieldname?>[pnr]" required class="input-small" value="<?php echo $pax->pnr?>" /></td> -->
						
				  <?php if ($this->config->get('ps_notes')){?>
				  <td><input type="text" name="<?php echo $fieldname?>[notes]" required class="input-small" value="<?php echo $pax->notes?>" /></td>
					<?php } ?>
					
				  <?php if ($this->config->get('ps_birthday')){?>	
					<td><input type="text" class="input-small birthday"
							name="<?php echo $fieldname?>[birthday]" id="<?php echo 'birthday'?>" value="<?php echo DateHelper::toShortDate($pax->birthday) ?>" /></td>
					<?php } ?>

				  <?php if ($this->config->get('ps_passport')){?>
					<td><input type="text" name="<?php echo $fieldname?>[passport]" required value="<?php echo $pax->passport?>"
							class="input-medium" /></td>
					<?php } ?>
				 
					<td><?php echo BookProHelper::getGroupList( $fieldname.'[group_id]',$pax->group_id,'class="input-small"')?></td>
					
					<!-- 
					<td><input type="text" name="<?php echo $fieldname?>[price]" required value="<?php echo $pax->price ?>"
							class="input-medium" /></td>
							
					-->

					
					</tr>
					<?php } }?>

				</tbody>


			</table>
	
	<input type="hidden" name="order_id" value="<?php echo $this->order->id?>"/>
	<input type="hidden" name="controller" value="pos"/>
	<input type="hidden" name="option" value="com_bookpro"/>
	<input type="hidden" name="task" value="save"/>
	
	<input type="hidden" name="Itemid" value="<?php echo JFactory::getApplication()->input->get('Itemid') ?>"/>

</form>
</div>
