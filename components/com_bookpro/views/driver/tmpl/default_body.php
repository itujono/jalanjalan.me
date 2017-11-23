
<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bookpro.php 27 2012-07-08 17:15:11Z quannv $
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );

?>

<div>

		<div class="form-inline">
			<?php echo $this->loadTemplate('search')?>
		</div>
		
	<form action="<?php echo JRoute::_("index.php?option=com_bookpro&controller=pos&task=confirm_driver")?>" method="POST" name="posform" id="posform" onsubmit="return submitForm()">	
	
		
		<?php echo $this->loadTemplate('result')?>
		
		<div class="well well-small" style="display: none;">
		<div class="form-inline" >
		<label><?php echo JText::_('COM_BOOKPRO_CUSTOMER_NAME') ?>	</label><div id="remote" style="display:inline-block;"> 
		<input id="firstname" name= "jform[firstname]" class="typeahead input" type="text"/></div> 
		<!-- <label><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSPORT') ?>	</label>	<input name="jform[passport]" type="text"	class="passport input" /> -->
		<label><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL') ?>	</label>	<input name="jform[email]"	type="text" class="input" /> 
		<label><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PHONE') ?>	</label>	<input name="jform[telephone]" type="text"	class="input phone" />
		<input name="jform[id]" type="hidden"	value=""/>
		</div>
		</div>
		

<script type="text/javascript">
jQuery(document).ready(function($) {


// fill the price

jQuery('.viewseat').on('click',function(){
var prices= $.parseJSON($(this).attr('data-price'));
jQuery('.person-price').each(function( index ) {
	for (i = 0; i < prices.length; i++) { 
		if(prices[i].pricetype==jQuery(this).attr('data-type'))
			jQuery(this).val(prices[i].adult);
			//console.dir(prices[i]);
	}
  
 });

});
	

});

</script>
		
		<div class="form-inline" style="display:none;">


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
						
				
					
				  <?php if ($this->config->get('ps_birthday')){?>	
					<td><?php echo JText::_('COM_BOOKPRO_PASSENGER_BIRTHDAY')?>
					</td>
					<?php } ?>

				  <?php if ($this->config->get('ps_passport')){?>
					<td><?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT')?></td>
					<?php } ?>
				
					
				
				  <?php if ($this->config->get('ps_country')){?>
					<td><?php echo JText::_('COM_BOOKPRO_PASSENGER_COUNTRY')?>
					</td>
					<?php } ?>
					<td><?php echo JText::_('COM_BOOKPRO_PASSENGER_AGE')?></td>
					<td><?php echo JText::_('COM_BOOKPRO_PASSENGER_PRICE')?></td>
					</tr>
					</thead>
					<tbody>
					<?php for ($i = 0; $i < count($this->types); $i++) {
						
						$type=$this->types[$i];
						$type->price=0;
						if($type->selected>0)
						for ($j = 0; $j < $type->selected; $j++) {
						$fieldname='person['.$type->id.'][' . $j . ']';
						
						
					?>
					
					
					<tr class="busstopclone" style="border-top: 1px solid #ccc;">
					 <?php if ($this->config->get('ps_gender')){?>
					<td>
					<?php echo JHtml::_('select.genericlist',BookProHelper::getGender(), $fieldname.'[gender]','class="inputbox input-small"','value','text',1)?>
					</td>				  
				  <?php }?>
				  
				  <td><input type="text" name="<?php echo $fieldname?>[firstname]" required <?php if ($i==0) echo 'id="firstname_id"'?>
							class="input-medium jbpassenger" value="client1" /></td>
					
				  <?php if ($this->config->get('ps_lastname')){?>
				  <td><input type="text" name="<?php echo $fieldname?>[lastname]" required <?php if ($i==0) echo 'id="lastname_id"'?>
							class="input-medium jbpassenger" /></td>
					<?php } ?>
						
				  <?php if ($this->config->get('ps_email')){?>
				  <td><input type="text" name="<?php echo $fieldname?>[email]" required class="input-medium jbpassenger" /></td>
					<?php } ?>
						
				  
					
				 
				  <?php if ($this->config->get('ps_passport')){?>
					<td><input type="text" name="<?php echo $fieldname?>[passport]" required
							class="input-medium jbpassenger" id="passport_id" /></td>
					<?php } ?>
					
				
				 
					<td><?php echo BookProHelper::getGroupList( $fieldname.'[group_id]',$type->id,'class="input-small jbgroup"')?></td>
					<td><input type="hidden" name="<?php echo $fieldname?>[price]"  value="<?php echo $type->price ?>" data-type="<?php echo $type->id ?>" class="input-small person-price" /></td>
					<!-- 
					<td><button id="remove_pax" type="button"><?php echo JText::_('COM_BOOKPRO_REMOVE')  ?></button></td> -->
					</tr>
					<?php } }?>

				</tbody>


			</table>

		</div>
		<!-- 
		<button type="button" id="add_new_stop" class="btn btn-success">
				<icon class="icon-new"></icon>
							<?php echo JText::_('COM_BOOKPRO_ADD_PASSENGER')?>
					</button>
				 -->	
					
				<div class="center">
			<button type="submit"  class="btn btn-primary">
				<icon class="icon-new"></icon>
							<?php echo JText::_('JSUBMIT')?>
				</button>

		</div>		
		
		

	
	<input type="hidden" name="Itemid" value="<?php echo JFactory::getApplication()->input->get('Itemid') ?>"/>
	

</form>
</div>

<script type="text/javascript">
				jQuery(document).ready(function($) {

					$('select.jbgroup option:not(:selected)').each(function(){
						 $(this).attr('disabled', 'disabled');
					});
										
					$("#add_new_stop").click(function(){
						
						$( ".busstopclone" ).eq(0).clone().insertAfter("tr.busstopclone:last");
						
					});

					$("#remove_pax").live('click', function(event) {
						
						if($('table#pax_table_id tr:last').index() + 1 >2)
						$(this).parent().parent().remove();
						
					});
				
				});

				</script>
