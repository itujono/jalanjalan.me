<?php 
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id$
 **/
defined('_JEXEC') or die('Restricted access');
AImporter::helper('currency');
$addons = $displayData->addons;

if (count($addons)>0)
{ ?>
					<div class="box-heading">
									<?php echo JText::_('COM_BOOKPRO_ADDONS_INFO')?>
								</div>
						
	<table class="table table-bordered" >
		<tr>
			<td>
				<?php echo JText::_('COM_BOOKPRO_ADDON_NAME')?>
			</td>
			<td ><?php echo JText::_('COM_BOOKPRO_QUANTITY')?></td>
			<td ><?php echo JText::_('COM_BOOKPRO_ADDON_PRICE')?></td>
		</tr>

	<?php
	
		foreach ($addons as $addon)
		{
	?>
	
	<tr>
			<td style="border: 1px #ccc solid;"><?php echo $addon->title; ?></td>
			<td style="border: 1px #ccc solid;"><?php echo $addon->qty; ?></td>
			<td style="border: 1px #ccc solid;"><?php echo CurrencyHelper::formatprice($addon->price); ?></td>
	</tr>	
	<?php
		}
	?>
</table>
<?php } ?>
