
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
AImporter::helper ( 'bus', 'html' );
AImporter::model('orders');
$this->orderstatus=$this->getOrderStatusSelect(null);
$orderModel=new BookProModelOrders();
$customer=JBFactory::getAccount();
$this->state = $orderModel->getState();
$this->state->set('filter.created_by',$customer->id?$customer->id:-1);
$this->orders=$orderModel->getItems();
$this->pagination=$orderModel->getPagination();

OrderStatus::init();
	
?>
<script>
function getChangeOrderStatus(_this){
	document.getElementById("order_status").value = _this.value;
}

function getSubmit(obj){
	document.getElementById("order_id").value = obj;
	document.getElementById('task').value = 'updateorderstatus';
	document.agentOrder.submit();
}
function getPayStatus(obj){
	document.getElementById("order_id").value = obj;
	document.getElementById('task').value = 'updatepaystatus';
	document.agentOrder.submit();
}
function getDeleteOrder(obj){
	document.getElementById("order_id").value = obj;
	document.getElementById('task').value = 'deleteorder';
	document.agentOrder.submit();
}
</script>


<script type="text/javascript">
	jQuery(document).ready(function($) {

		 var olddata;
		    $(".td_orderstatus").change(function(){		
			var value=$(this).val();
			var id_all= $(this).attr('id');
			console.log('call1');
			$.ajax({
				type: 'GET',
				url: 'index.php?option=com_bookpro&controller=order&task=changestatus&order_status='+value+'&id='+id_all,
				success : function(result) {

					
				if(result){
					
					$(this).attr("selected","selected");
				  }
				}
				 
				 
			});
			//}
			//else{
				//this.form.submit();
			//}
		});
	});
</script>


<div class="row-fluid">
	<?php echo BookProHelper::renderLayout('menu_pos', null)?>
</div>
<fieldset>

	<legend>
		<?php echo JText::_('COM_BOOKPRO_ORDERS')?>
	</legend>

	<form name="agentOrder"	action="<?php echo JRoute::_("index.php?option=com_bookpro&view=pos&layout=orders")?>" method="POST">
		<div class="well well-small">
			<div class="row-fluid">
				<div class="row-fluid form-inline">
							<?php echo JHtmlSelect::booleanlist('filter_date_type','class="btn-group"',$this->state->get('filter.date_type'),JText::_('COM_BOOKPRO_BOOKING_DATE'),JText::_('COM_BOOKPRO_DEPART_DATE'))?>
							<?php 
							
							
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

				</div>
			</div>

		</div>
		<table class="table table-stripped">
			<thead>
				<tr>
					<th><?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER'); ?></th>
					<th><?php echo JText::_('COM_BOOKPRO_ORDER_STATUS'); ?></th>
					<th><?php echo JText::_('COM_BOOKPRO_CUSTOMER'); ?></th>
					<th><?php echo JText::_("COM_BOOKPRO_BUSTRIP"); ?></th>
					<th><?php echo JText::_("COM_BOOKPRO_DEPART_DATE"); ?></th>
					<th><?php echo JText::_('COM_BOOKPRO_ORDER_TOTAL'); ?></th>
					<th><?php echo JText::_('COM_BOOKPRO_RESELLER'); ?></th>
					
					<th><?php echo JText::_('COM_BOOKPRO_ORDER_ORDER_TIME'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if (count ( $this->orders ) > 0) {
					
					foreach ( $this->orders as $order ) {
						
						$orderlink = BookProHelper::getOrderLink ( $order->order_number, $order->email );
						
						?>
				<tr>
					<td>
					
					<?php
						
						echo JHtml::link (JRoute::_($orderlink), $order->order_number, 'class="cancelbt"' );
						?>
						<a href="#"><i class="icon-print"></i></a>
						<a href="<?php echo JRoute::_('index.php?option=com_bookpro&view=pos&layout=edit&order_id='.$order->id) ?>"><i class="icon-edit"></i></a>
						
						
					</td>
					
					<td>
					
					<?php if($order->order_status=='CONFIRMED'){
						echo OrderStatus::format($order->order_status);
					}else {
							echo JHtmlSelect::genericlist(OrderStatus::$map,'order_'.$order->id, 'class="td_orderstatus input-small"','value', 'text',$order->order_status);
					
					}
					
					
					?>
					
					</td>
					
						<td>
						<?php echo 	$order->ufirstname;		
						?>
					</td>
					
					<td>
						<?php
							echo BusHelper::getRouteFromParams ( (json_decode ( $order->params, true )) );
						?>
					</td>
					<td><?php echo DateHelper::toShortDate($order->depart) ?> <?php echo DateHelper::formatTime($order->depart) ?></td>

					<td align="right"><?php echo CurrencyHelper::formatprice($order->total)?>
					
					</td>
				<td><?php echo $order->created_firstname; ?>
				
					
					<td><?php echo $order->created; ?></td>
				
				
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
