<?php 
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bookpro.php 27 2012-07-08 17:15:11Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');
?>
<h3><?php echo JText::_('COM_BOOKPRO_PASSENGER') ?></h3>
 
<table class="table table-hover" id="passenger" style="border:2px solid #989898;border-collapse:inherit !important;">
	<thead>
		<tr style="background:#D0D0D0  ;">
			 <?php if ($this->config->get('ps_gender')){?>
			<td><?php echo JText::_('COM_BOOKPRO_PASSENGER_GENDER')?>
			</td>
			<?php } ?>
			
			<td><?php echo JText::_('COM_BOOKPRO_PASSENGER_FIRSTNAME')?>
			</td>
			
			 <?php if ($this->config->get('ps_lastname')){?>
			<td><?php echo JText::_('COM_BOOKPRO_PASSENGER_LASTNAME')?>
			</td>
			<?php }?>
			
			
			
			  <?php if ($this->config->get('ps_birthday')){?>	
			<td><?php echo JText::_('COM_BOOKPRO_PASSENGER_BIRTHDAY')?>
			</td>
			<?php }?>
			
			 <?php if ($this->config->get('ps_passport')){?>
			<td><?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT')?>
			</td>
			<?php }?>
			

			<?php if ($this->config->get('ps_country')){?>
			<td><?php echo JText::_('COM_BOOKPRO_PASSENGER_COUNTRY')?>
			</td>
			<?php }?>

			<?php if ($this->config->get('ps_group')){?>
			<td><?php echo JText::_('COM_BOOKPRO_PASSENGER_GROUP')?>
			</td>
			<?php }?>
			
		
			
			<!-- 
			<th width="10%"><?php echo JText::_('COM_BOOKPRO_PASSENGER_PRINT') ?></td>
			 -->
		</tr>
	</thead>
	<?php
 
	if (count($this->order->passengers)>0){
			foreach ($this->order->passengers as $pass)
			{
				?>
	<tr>
		 <?php if ($this->config->get('ps_gender')){?>
		<td><?php 
			echo BookProHelper::formatGender($pass->gender);			
		?></td>
		<?php } ?>
		
		<td><?php echo $pass->firstname; ?></td>
		
		 <?php if ($this->config->get('ps_lastname')){?>
		<td><?php echo $pass->lastname; ?></td>
		<?php }?>
		
		
		
		  <?php if ($this->config->get('ps_birthday')){?>	
		<td><?php 
		if ($pass->birthday != '0000-00-00 00:00:00') {
			echo DateHelper::toShortDate($pass->birthday);	
		}else{
			echo "N/A";
		}
		 ?></td>
		<?php }?>
 

		 <?php if ($this->config->get('ps_passport')){?>
		<td><?php echo $pass->passport; ?></td>
		<?php }?>
		
		<?php if ($this->config->get('ps_country')){?>
		<td><?php echo  $pass->country; ?></td>
		<?php }?>
		<?php if ($this->config->get('ps_group')){?>
		<td><?php echo $pass->group_title;?></td>
		<?php }?>
		
	
		<!-- 
		<td>
			<?php 
			$href = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
			$href = "window.open(this.href,'win2','".$href."'); return false;";
			$href = 'href="index.php?option=com_bookpro&view=printticket&tmpl=component&id='.$pass->id.'&print=1" onclick="'.$href.'"';
			
			?>
			<a class="btn-small btn-primary" <?php echo $href ?>>
			<i class="icon-print"></i>
			<?php echo JText::_('COM_BOOKPRO_PASSENGER_PRINT') ?></a>
		</td>
		 -->
	</tr>
	<?php
			}
		}
		?>
</table>
