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

 //$export = JUri::root () . 'index.php?option=com_bookpro&controller=order&task=exportpdf&order_id=' . $this->order->id;
 //$print = JUri::root () . 'index.php?option=com_bookpro&controller=order&task=exportpdf&order_id=' . $this->order->id;
 $sendemail= JUri::root () . 'index.php?option=com_bookpro&controller=order&task=sendemail&order_id=' . $this->order->id;
 $order_status=OrderStatus::format($this->order->order_status);

 $isModal=JFactory::getApplication()->input->getInt('print');
 $receipt="#";
 if( $isModal) {
 	$print="#";
 	echo '<script type="text/javascript">window.onload = function() { window.print(); }</script>';
 	
 } else {
 	$print = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
 	$print = "window.open(this.href,'win2','".$print."'); return false;";
 	$print = '"index.php?option=com_bookpro&view=orderdetail&tmpl=component&print=1&order_number='.$this->order->order_number.'&email='.$this->customer->email.'" '.$print;
 	
 	$receipt = '"index.php?option=com_bookpro&view=orderdetail&layout=receipt&tmpl=component&print=1&order_number='.$this->order->order_number.'&email='.$this->customer->email.'" '.$print;
 }
 
 $print_ticket=JUri::root().'index.php?option=com_bookpro&controller=order&task=print_ticket&tmpl=component&order_id='.$this->order->id;
 
?>
<style>

.white-popup {
  position: relative;
  background: #FFF;
  padding: 20px;
  width: auto;
  max-width: 500px;
  margin: 20px auto;
}

</style>
<div id="test-popup" class="white-popup mfp-hide">
	<p class="lead text-center">Payment confirmation</p>
	<dl class="dl-horizontal">
        <dt><?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER') ?></dt>
        <dd><?php echo $this->order->order_number ?></dd>
        <dt><?php echo JText::_('COM_BOOKPRO_ORDER_TOTAL') ?></dt>
        <dd><?php echo CurrencyHelper::formatprice($this->order->total) ?></dd>
        
    </dl>
    <div class="text-center">
    <button class="btn btn-small" id="process_payment"><?php echo JText::_('JSUBMIT')?></button> 
    </div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {



		$('.open-popup-link').magnificPopup({
			  type:'inline',
			  midClick: true,
			  closeBtnInside:true
		});

		var value="CONFIRMED";

		    $("#process_payment").click(function () {

		    	
		      
				jQuery.ajax({
					type: 'POST',
					url: "index.php?option=com_bookpro&controller=order&task=update",
					
					data: 'order_status='+value+'&order_id='+<?php echo $this->order->id ?>,
				 
					dataType: 'json',
					success : function(result) {
						var magnificPopup = $.magnificPopup.instance;
						magnificPopup.close();
						$('#order_status').html(result);
					  	}
					 
					 
				});
			
		});
	});
</script>

<div class="well well-small">
	
	<?php if(!$isModal){?>
	<div class="row-fluid">
		<div class="row-fluid">
				
				<div class="reservation-action-menu-text">
					 <a href="#test-popup" class="open-popup-link btn btn-primary span12" id="process_payment" ><?php echo JText::_('COM_BOOKPRO_PROCESS_PAYMENT')?></a>
				</div>
		</div>
		<br/>
		
		<div class="row-fluid">
				<div class="reservation-action-menu-text">
					 <a  class="btn btn-primary span12"	href="<?php echo JRoute::_('index.php?option=com_bookpro&view=pos&layout=edit&order_id='.$this->order->id) ?>"><?php echo JText::_('COM_BOOKPRO_EDIT')?></a>
				</div>
		</div>
		<br/>
		
		<div class="row-fluid">
				
				<div class="reservation-action-menu-text">
					 <a  target="_blank" class="btn btn-primary span12"
						title="Print Receipt" href=<?php echo $receipt ?>><?php echo JText::_('COM_BOOKPRO_PRINT')?></a>
				</div>
		</div>
		<br/>
		<?php //if($this->order->order_status=='CONFIRMED'){?>
		<div class="row-fluid">
				<div class="reservation-action-menu-text">
					 <a  target="_blank" class="btn btn-primary span12"
						 href=<?php echo $print_ticket ?>><?php echo JText::_('COM_BOOKPRO_PRINT_TICKET')?></a>
				</div>
		</div>
		<?php //} ?>
		
		<br/>
		
		<!-- 
		<div class="row-fluid">
			<div class="well well-small wellwhite">
				<i class="icon-remove-sign icon-fixed-width icon-2x icon-large"></i>
				<div class="reservation-action-menu-text">

					<a id="cancel-reservation-link"
						href="<?php echo JRoute::_('index.php?option=com_bookpro&view=order&layout=cancel') ?>">Cancel
						your reservation</a>
				</div>
			</div>
		</div>
		 -->
	</div>
	<?php } ?>

</div>