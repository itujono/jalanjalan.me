<?php 

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id$
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );
$config=JComponentHelper::getParams('com_bookpro');
?>
<h3><?php echo JText::_('COM_BOOKPRO_PASSENGER') ?></h3>
<table width="100%" class="table" id="passenger">
	<thead>
		<tr>
			<?php if ($config->get('ps_gender')){?>
			<th align="left"><?php echo JText::_('COM_BOOKPRO_PASSENGER_GENDER')?>
			</th>
			<?php } ?>
			<th align="left"><?php echo JText::_('COM_BOOKPRO_PASSENGER_NAME')?>
			</th>
		
			<?php if ($config->get('ps_birthday')){?>
			<th align="left"><?php echo JText::_('COM_BOOKPRO_PASSENGER_BIRTHDAY')?>
			</th>
			<?php }?>
			
		
			<?php if ($config->get('ps_passport')){?>
			<th align="left"><?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT')?>
			</th>
			<?php }?>
			<?php if ($config->get('ps_ppvalid')){?>
			<th align="left"><?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT_EXPIRED')?>
			</th>
			<?php }?>

			<?php if ($config->get('ps_country')){?>
			<th align="left"><?php echo JText::_('COM_BOOKPRO_PASSENGER_COUNTRY')?>
			</th>
			<?php }?>

			<th align="left"><?php echo JText::_('COM_BOOKPRO_PASSENGER_GROUP')?>
			</th>
			
			<th align="left"><?php echo JText::_('COM_BOOKPRO_PASSENGER_PRICE')?>
			</th>
			
			
		</tr>
	</thead>
	<?php
	if (count($displayData->passengers)>0){
			foreach ($displayData->passengers as $pass)
			{
				?>
	<tr>
		<?php if ($config->get('ps_gender')){?>
		<td align="left"><?php echo BookProHelper::formatGender($pass->gender); ?></td>
		<?php } ?>
		
		<td align="left"><?php echo JText::sprintf('COM_BOOKPRO_PASSENGER_NAME_TXT',$pass->firstname,$pass->lastname); ?></td>
		
		<?php if ($config->get('ps_birthday')){?>
		<td><?php echo DateHelper::toShortDate($pass->birthday) ?></td>
		<?php }?>
		
		
		<?php if ($config->get('ps_passport')){?>
		<td><?php echo $pass->passport; ?></td>
		<?php }?>
		
		<?php if ($config->get('ps_ppvalid')){?>
		<td><?php echo  $pass->ppvalid	; ?></td>
		<?php }?>
		<?php if ($config->get('ps_country')){?>
		<td><?php echo  $pass->country; ?></td>
		<?php }?>
		<td><?php echo $pass->group_title;?></td>
		
		<td>
		<?php echo CurrencyHelper::formatPrice($pass->price+$pass->return_price);
				?>
		</td>
		
	</tr>
	<?php
			}
		}
		?>
</table>
