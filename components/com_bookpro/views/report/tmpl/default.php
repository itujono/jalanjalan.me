
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

<div class="row-fluid">
	<?php echo BookProHelper::renderLayout('menu_pos', null)?>
</div>
<fieldset>

	<legend>
		<?php echo JText::_('COM_BOOKPRO_ORDERS')?>
	</legend>

	<form name="agentOrder"
		action="index.php?option=com_bookpro&view=pos&layout=report" method="POST">
		<div class="well well-small">
			<div class="row-fluid">
				<div class="row-fluid form-inline">
							
							<input type="hidden" name="filter_date_type" value="1"/>
							
							<?php //echo JHtmlSelect::booleanlist('filter_date_type','class="btn-group"',$this->state->get('filter.date_type'),JText::_('COM_BOOKPRO_BOOKING_DATE'),JText::_('COM_BOOKPRO_DEPART_DATE'))?>
							<?php 
							
							echo $this->getResellers(JFactory::getApplication()->getUserStateFromRequest('filter.created_by', 'created_by'));
							$default=JFactory::getDate()->format('Y-m-d');
							
							if($this->state->get('filter.from_date'))
								$from_date= JFactory::getDate(DateHelper::createFromFormat($this->state->get('filter.from_date'))->getTimestamp())->toSql();
							else $from_date=$default;
							
							
							
							$placeholder='placeholder="'.JText::_('COM_BOOKPRO_FROM_DATE').'" style="width: 100px;"' ;
							echo JHtml::calendar($from_date, 'filter_from_date','filter_from_date',DateHelper::getConvertDateFormat('M'),$placeholder);
							//var_dump($from_date);die;
							if($this->state->get('filter.to_date'))
							
							$to_date= JFactory::getDate(DateHelper::createFromFormat($this->state->get('filter.to_date',$default))->getTimestamp())->toSql();
							else $to_date=$default;
							
							$placeholder='placeholder="'.JText::_('COM_BOOKPRO_TO_DATE').'" style="width: 100px;"' ;
							echo JHtml::calendar($to_date, 'filter_to_date','filter_to_date',DateHelper::getConvertDateFormat('M'),$placeholder);
												?>
							<span>
								<?php echo $this->orderstatus?>
							</span> 
					  		 <button onclick="this.agentOrder.submit();" class="btn btn-success">
								<?php echo JText::_('COM_BOOKPRO_SEARCH'); ?>
							</button>
							
							 <a href="#" class="btn btn-success">
								<?php echo JText::_('COM_BOOKPRO_PRINT_PDF'); ?>
							</a>

				</div>
			</div>

		</div>
		<table class="table table-stripped">
			<thead>
				<tr>
					<th><?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER'); ?></th>
					<th><?php echo JText::_('COM_BOOKPRO_ORDER_ORDER_TIME'); ?></th>
					<th><?php echo JText::_('COM_BOOKPRO_CUSTOMER'); ?></th>
					<th><?php echo JText::_('COM_BOOKPRO_SEATS'); ?></th>
					<th><?php echo JText::_("COM_BOOKPRO_BUSTRIP"); ?></th>
					<th><?php echo JText::_("COM_BOOKPRO_DEPART_DATE"); ?></th>
					<th><?php echo JText::_('COM_BOOKPRO_ORDER_SUBTOTAL'); ?></th>
					<th><?php echo JText::_('COM_BOOKPRO_ORDER_TAX'); ?></th>
					<th><?php echo JText::_('COM_BOOKPRO_ORDER_TOTAL'); ?></th>
					
				</tr>
			</thead>
			<tbody>
				<?php
				
				$total=0;
				if (count ( $this->orders ) > 0) {
					
					
					foreach ( $this->orders as $order ) {
						$total+=$order->total;
						$orderlink = BookProHelper::getOrderLink ( $order->order_number, $order->email );
						
						?>
				<tr>
					<td>
					
					<?php
						
						echo JHtml::link ( $orderlink, $order->order_number, 'class="cancelbt"' );
						?>
											
						
					</td>
					
					<td><?php echo $order->created; ?></td>
					
					<td>
						<?php echo 	$order->ufirstname;		
						?>
					</td>
					
						<td>
						<?php echo 	$order->seats;		
						?>
					</td>
					
					<td>
						<?php
							echo BusHelper::getRouteFromParams ( (json_decode ( $order->params, true )) );
						?>
					</td>
					<td><?php echo DateHelper::toShortDate($order->depart) ?> <?php echo DateHelper::formatTime($order->depart) ?></td>

					<td align="right"><?php echo CurrencyHelper::formatprice($order->subtotal)?>
					
					</td>
					<td><?php echo CurrencyHelper::formatprice($order->tax)?></td>
					<td align="right"><?php echo CurrencyHelper::formatprice($order->total)?>
					
					</td>
					
					
				
				
				</tr>
				<?php
					
}
				} else {
					?>
				<tr>
					<td colspan="7"><?php echo JText::_('COM_BOOKPRO_ORDER_UNAVAILABLE')?>
					</td>
				</tr>
				<?php
				}
				?>
			</tbody>
		</table>

	</form>
</fieldset>

<?php 
$customer=JBFactory::getAccount();
$commission=($customer->commission*$total)/100;
$grandtotal=$total-$commission;

?>

<table class="table table-condensed span4">
		
		<tr>
		<td>
				<?php echo JText::_('COM_BOOKPRO_TOTAL');?>
		</td>
			<td>
				<?php echo  CurrencyHelper::formatprice($total); ?>
			</td>
		</tr>
			
		<tr>
		<td>
				<?php echo JText::_('COM_BOOKPRO_COMMISSION');?>
		</td>
			<td>
				<?php echo  CurrencyHelper::formatprice($commission); ?>
			</td>
		</tr>
			
		<tr>
		<td>
				<?php echo JText::_('COM_BOOKPRO_GRAND_TOTAL');?>
			</td>
			<td>
				<?php echo  CurrencyHelper::formatprice($grandtotal); ?>
			</td>
		</tr>
	</table>
