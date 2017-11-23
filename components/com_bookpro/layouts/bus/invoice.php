<?php 
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 23 2012-07-08 02:20:56Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

$config=JComponentHelper::getParams('com_bookpro');
AImporter::model('bustrips','bustrip');
AImporter::css('invoice');
AImporter::helper('date','currency','refund');
$order = $displayData;

$isModal = JRequest::getVar( 'print' ) == 1;


?>

<div style="width: 768px;border: 1px solid #ddd;">
			<div style="margin: 20px;">
				<div style="padding-bottom: 20px;border-bottom: 1px solid #ddd;">
					<div>
						<div style="width:66,9%;float:left;">
							<div style="float: left;">
								<div style="border: 1px solid #ddd;padding:1px;">
									<img alt="" src="<?php echo JUri::root().$config->get('company_logo'); ?>">
								</div>
							</div>
							<div style="float: left;">
								<div style="text-align: left;margin-left: 10px;font-size: 12px;">
								<div>
									<?php echo $config->get('company_name') ?>
								</div>
								<div>
									<?php echo $config->get('company_address') ?>
								</div>
								</div>
							</div>
						</div>
						<div style="width:33%;float:right;">
							<h1 class="invoice-title" style="font-size: 50px;font-weight: bold;text-transform: uppercase;text-align: left;"><?php echo JText::_('COM_BOOKPRO_INVOICE') ?></h1>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
				<div style="margin-top: 20px;">
					<div>
						<div style="float: left;width:66%;">
							<label style="text-align: left;font-weight: bold;">
								<?php echo JText::sprintf('COM_BOOKPRO_INVOICE_BUYER',$order->firstname.' '.$order->lastname) ?>
							</label>
							<label style="text-align: left;font-weight: bold;">
								<?php echo JText::sprintf('COM_BOOKPRO_INVOICE_BUYER_PHONE',$order->mobile); ?>
							</label>
							<label style="text-align: left;font-weight: bold;">
								<?php echo JText::sprintf('COM_BOOKPRO_INVOICE_BUYER_EMAIL',$order->email); ?>
							</label>
						</div>
						<div style="width: 33%;float:right;">
							<label style="text-align: left;font-weight: bold;"><?php echo JText::sprintf('COM_BOOKPRO_INVOICE_ORDER_NUMBER',$order->order_number); ?></label>
							<label style="text-align: left;font-weight: bold;"><?php echo JText::sprintf('COM_BOOKPRO_INVOICE_ORDER_DATE',JFactory::getDate($order->created)->format('d M Y')); ?></label>
						</div>
						<div style="clear: both;"></div>
					</div>
				</div>
				
				<h3 style="text-align: left;"><?php echo JText::_('COM_BOOKPRO_INVOICE_BUS_INFOMATION') ?></h3>
				<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<th style="border-top:1px solid #ddd;padding:4px 5px;" align="left">
							<?php echo JText::_('COM_BOOKPRO_BUSTRIP_FROM') ?>
						</th>
						<th style="border-top:1px solid #ddd;padding:4px 5px;" align="left">
						
							<?php echo JText::_('COM_BOOKPRO_BUSTRIP_TO') ?>
						</th>
						
						<th style="border-top:1px solid #ddd;padding:4px 5px;" align="left">
							<?php echo JText::_('COM_BOOKPRO_DEPART_DATE') ?>
						</th>
						<th style="border-top:1px solid #ddd;padding:4px 5px;" aligh="left">
							<?php echo JText::_('COM_BOOKPRO_COMPANY') ?>
						</th>
					</tr>
					<?php foreach ($order->orderinfo as $subject){ ?>
					<tr>
						<td style="border-top:1px solid #ddd;padding:4px 5px;">
							
							<?php echo JText::sprintf('COM_BOOKPRO_BUSTRIP_FROM_TXT',$subject->from_name,JFactory::getDate($subject->start_time)->format('H:i'),$subject->start_loc); ?></td>
						
						<td style="border-top:1px solid #ddd;padding:4px 5px;">
						 	<?php echo JText::sprintf('COM_BOOKPRO_BUSTRIP_FROM_TXT',$subject->to_name,JFactory::getDate($subject->end_time)->format('H:i'),$subject->end_loc); ?></td>
						<td style="border-top:1px solid #ddd;padding:4px 5px;">
						
							<?php echo DateHelper::toLongDate($subject->depart_date) ?>
						</td>
						<td style="border-top:1px solid #ddd;padding:4px 5px;">
							<?php echo $subject->company; ?>
						</td>
					</tr>
					<?php } ?>
				</table>
				<div>
					<h3 style="text-align: left;"><?php echo JText::_('COM_BOOKPRO_INVOICE_PASSENGER_INFOMATION') ?></h3>
					<table class="table table-condensed" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<th style="border-top:1px solid #ddd;padding:4px 5px;">
							<?php echo JText::_('No')?>
							</th>
							 <?php if ($config->get('ps_gender')){?>
							<th style="border-top:1px solid #ddd;padding:4px 5px;">
								<?php echo JText::_('COM_BOOKPRO_PASSENGER_GENDER')?>
							</th>
							<?php } ?>
							
							<th style="border-top:1px solid #ddd;padding:4px 5px;">
								<?php echo JText::_('COM_BOOKPRO_PASSENGER_NAME')?>
							</th>
								
							
							  <?php if ($config->get('ps_birthday')){?>	
							<th style="border-top:1px solid #ddd;padding:4px 5px;">
								<?php echo JText::_('COM_BOOKPRO_PASSENGER_BIRTHDAY')?>
							</th>
							<?php }?>
							
				 
						
							 <?php if ($config->get('ps_passport')){?>
							<th style="border-top:1px solid #ddd;padding:4px 5px;">
								<?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT')?>
							</th>
							<?php }?>
							<?php if ($config->get('ps_ppvalid')){?>
							<th style="border-top:1px solid #ddd;padding:4px 5px;">
								<?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT_EXPIRED')?>
							</th>
							<?php }?>
				
							<?php if ($config->get('ps_country')){?>
							<th style="border-top:1px solid #ddd;padding:4px 5px;">
								<?php echo JText::_('COM_BOOKPRO_PASSENGER_COUNTRY')?>
							</th>
							<?php }?>
				
							<?php if ($config->get('ps_group')){?>
							<th style="border-top:1px solid #ddd;padding:4px 5px;">
								<?php echo JText::_('COM_BOOKPRO_PASSENGER_GROUP')?>
							</th>
							<?php }?>
							<th style="border-top:1px solid #ddd;padding:4px 5px;">
								<?php echo JText::_('COM_BOOKPRO_PASSENGER_SEAT')?>
							
							
							 <?php if($config->get('ps_bag')){ ?>
							<th style="border-top:1px solid #ddd;padding:4px 5px;">
								<?php echo JText::_('COM_BOOKPRO_PASSENGER_BAGGAGE')?>
							</th>
							<th style="border-top:1px solid #ddd;padding:4px 5px;">
								<?php echo JText::_('COM_BOOKPRO_PASSENGER_BAGGAGE_RETURN')?>
							</th>
							<?php } ?>
														
							<th style="border-top:1px solid #ddd;padding:4px 5px;">
								<?php echo JText::_('COM_BOOKPRO_PASSENGER_PRICE')?>
							</th>
							
						</tr>
						<?php foreach ($order->passengers as $pass){ ?>
						<tr>
								
								<td style="border-top:1px solid #ddd;padding:4px 5px;">
									<?php echo '#'.$order->order_number.'-'.$pass->id ?>
								</td>
								 <?php if ($config->get('ps_gender')){?>
								<td style="border-top:1px solid #ddd;padding:4px 5px;">
									<?php echo BookProHelper::formatGender($pass->gender) ?>
								</td>
								<?php } ?>
								
								<td style="border-top:1px solid #ddd;padding:4px 5px;">
									<?php echo $pass->firstname.' '.$pass->lastname; ?>
								</td>
								
								 <?php if ($config->get('ps_birthday')){?>	
								<td style="border-top:1px solid #ddd;padding:4px 5px;">
								<?php 
								if ($pass->birthday != '0000-00-00 00:00:00') {
									echo DateHelper::toShortDate($pass->birthday);	
								}else{
									echo "N/A";
								}
								 ?></td>
								<?php }?>
						 
						
								 <?php if ($config->get('ps_passport')){?>
								<td style="border-top:1px solid #ddd;padding:4px 5px;">
									<?php echo $pass->passport; ?></td>
								<?php }?>
								<?php if ($config->get('ps_ppvalid')){?>
								<td style="border-top:1px solid #ddd;padding:4px 5px;">
									
									<?php echo  $pass->ppvalid	; ?>
								</td>
								<?php }?>
								<?php if ($config->get('ps_country')){?>
								<td style="border-top:1px solid #ddd;padding:4px 5px;">
									<?php echo  $pass->country; ?>
								</td>
								<?php }?>
								<?php if ($config->get('ps_group')){?>
								<td style="border-top:1px solid #ddd;padding:4px 5px;">
									<?php echo $pass->group_title;?></td>
								<?php }?>
								<td style="border-top:1px solid #ddd;padding:4px 5px;">
									<?php echo $pass->seat.'/'.$pass->return_seat;?>
									
								</td>
								
								
								 <?php if($config->get('ps_bag')){ ?>
									<td style="border-top:1px solid #ddd;padding:4px 5px;">
									<?php 
									echo $pass->bag_qty ? $pass->bag_qty:JText::_('COM_BOOKPRO_BAGGAGE_FREE');
									?>
								</td>
								<td style="border-top:1px solid #ddd;padding:4px 5px;">
									<?php echo $pass->return_bag_qty ? $pass->return_bag_qty:JText::_('COM_BOOKPRO_BAGGAGE_FREE'); ?>
								</td>
								<?php } ?>
																
								<td style="border-top:1px solid #ddd;padding:4px 5px;">
								<?php echo CurrencyHelper::formatPrice($pass->price+$pass->return_price+ $pass->price_bag + $pass->return_price_bag);	
										?>
								</td>
								
							</tr>
						<?php } ?>
					</table>
				</div>
				
				<div>
					<div style="float: right;width:33%;">
						 <?php if($order->tax){?>
						<div>
							<label style="font-weight: bold;">
								<span style="float:left;width:150px;">
									<?php echo JText::_('COM_BOOKPRO_ORDER_SUBTOTAL') ?>
								</span>
								<span style="float: left;">
									<?php echo CurrencyHelper::displayPrice((int) $order->subtotal); ?>
								</span>
								<span style="clear: both;"></span>
							</label>
						</div>
						<?php } ?>
						
						<?php if($order->tax){?>
						<div>
							<label style="font-weight: bold;">
								<span style="float:left;width:150px;">
									<?php echo JText::_('COM_BOOKPRO_INVOICE_TOTAL_GST') ?>
								</span>
								<span style="float: left;">
									<?php echo CurrencyHelper::displayPrice((int) $order->tax); ?>
								</span>
								<span style="clear: both;"></span>
							</label>
						</div>
						<?php } ?>
						
						<div>
							<label style="font-weight: bold;">
								<span style="float:left;width:150px;">
									<?php echo JText::_('COM_BOOKPRO_INVOICE_TOTAL_FARES') ?>
								</span>
								<span style="float: left;"><?php echo CurrencyHelper::displayPrice($order->total); ?></span>
								<span style="clear: both;"></span>
							</label>
							
						</div>
					</div>
					<div style="clear: both;"></div>
				</div>
				
				
				
				<?php if ($isModal) {
					
				 ?>
				<div>
					<?php echo $config->invoice_footer; ?>
				</div>
				<?php } ?>
				 <form action="index.php" method="post" name="adminForm" id="adminForm">
					<input type="hidden" name="controller" value="order"/>
					 <?php echo JHTML::_('form.token'); ?>
				  </form>	
			</div>
			
		</div>
