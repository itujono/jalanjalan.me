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
$order=$displayData;
AImporter::helper('date');
?>

<h3 style="text-align: left;"><?php echo JText::_('COM_BOOKPRO_BUSTRIP_INFO') ?></h3>
				<table  width="100%">
					<tr>
						<td style="border-top:1px solid #ddd;padding:4px 5px;" align="left">
							<?php echo JText::_('COM_BOOKPRO_BUSTRIP_FROM') ?>
						</td>
						<td style="border-top:1px solid #ddd;padding:4px 5px;" align="left">
						
							<?php echo JText::_('COM_BOOKPRO_BUSTRIP_TO') ?>
						</td>
						
						<td style="border-top:1px solid #ddd;padding:4px 5px;" align="left">
							<?php echo JText::_('COM_BOOKPRO_DEPART_DATE') ?>
						</td>
						<td style="border-top:1px solid #ddd;padding:4px 5px;" aligh="left">
							<?php echo JText::_('COM_BOOKPRO_COMPANY') ?>
						</td>
					</tr>
					<?php foreach ($order->bustrips as $subject){ ?>
					<tr>
						<td style="border-top:1px solid #ddd;padding:4px 5px;">
							
							<?php echo $subject->from_name ?>
							<br/>
							 <?php 
									if (isset($subject->boarding)){
										
										//var_dump($subject->boarding);
										echo JText::sprintf('COM_BOOKPRO_BOARDING_TXT',$subject->boarding['location'], DateHelper::formatTime($subject->boarding['depart']));
										if(isset($subject->boarding['private_boarding'])){

											echo "<br/>";
											echo $subject->boarding['private_boarding'];
										}
									}
								?>
								</td>
						
						<td style="border-top:1px solid #ddd;padding:4px 5px;">
						 	<?php echo $subject->to_name?>
						 	<br/>
						 	<?php 
									if (isset($subject->dropping)){
										
										echo JText::sprintf('COM_BOOKPRO_DROPPING_TXT',$subject->dropping['location'], DateHelper::formatTime($subject->dropping['depart']));
										if(isset($subject->dropping['private_dropping'])){
											echo "<br/>";
											echo $subject->dropping['private_dropping'];
										}	
									
									}
								?>
						 	
						 	</td>
						<td style="border-top:1px solid #ddd;padding:4px 5px;">
						
							<?php echo DateHelper::toLongDate($subject->depart_date) ?> - <?php echo DateHelper::formatTime($subject->start_time) ?>
						</td>
						<td style="border-top:1px solid #ddd;padding:4px 5px;">
							<?php echo $subject->company; ?>
						</td>
					</tr>
					<?php } ?>
				</table>