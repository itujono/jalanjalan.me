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

AImporter::js('jquery.magnific-popup.min');
AImporter::css('magnific-popup');
AImporter::model('order');

$id = JFactory::getApplication ()->input->get ( 'order_id' );
$model = new BookProModelOrder ();
$this->order = $model->getComplexItem ( $id );
?>
<div class="">
	<?php echo BookProHelper::renderLayout('menu_pos', null)?>
</div>
<div class="row-fluid">
<div class="span9">

<div class="well well-small wellwhite">
<div class="row-fluid">
<div class="span6">
	<dl class="dl-horizontal">
        <dt> <?php echo JText::_('COM_BOOKPRO_ORDER_STATUS') ?></dt>
        <dd><label class=""> <span id="order_status"> <?php echo OrderStatus::format($this->order->order_status) ?></span></label></dd>
        <dt><?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER') ?></dt>
        <dd><?php echo $this->order->order_number ?></dd>
        <dt><?php echo JText::_('COM_BOOKPRO_CUSTOMER_NAME') ?></dt>
        <dd><?php echo $this->order->customer->firstname.' '. $this->customer->lastname ?></dd>
        
        
        <dt><?php echo JText::_('COM_BOOKPRO_RESELLER') ?></dt>
        <dd><?php echo $this->order->created_by_account->firstname ?></dd>
    </dl>
    </div>
    
    <div class="span6">
    
    <table class="table">
        
        <?php foreach ($this->order->bustrips as $subject){ ?>
					<tr>
						<td style="border-top:1px solid #ddd;padding:4px 5px;">
							
							<?php echo $subject->from_name ?>
							<br/>
							 <?php 
									if (isset($subject->boarding)){
										
										echo JText::sprintf('COM_BOOKPRO_BOARDING_TXT',$subject->boarding->location, DateHelper::formatTime($subject->boarding->depart));
									}
								?>
								</td>
						
						<td style="border-top:1px solid #ddd;padding:4px 5px;">
						 	<?php echo $subject->to_name?>
						 	<br/>
						 	<?php 
									if (isset($subject->dropping)){
										
										echo JText::sprintf('COM_BOOKPRO_DROPPING_TXT',$subject->dropping->location, DateHelper::formatTime($subject->dropping->depart));
									}
								?>
						 	
						 	</td>
						<td style="border-top:1px solid #ddd;padding:4px 5px;">
						
							<?php echo DateHelper::toLongDate($subject->depart_date) ?> - <?php echo DateHelper::formatTime($subject->start_time) ?>
						</td>
						
						<td style="border-top:1px solid #ddd;padding:4px 5px;">
						
							<?php echo JText::sprintf('COM_BOOKPRO_SEAT_TEXT',$subject->seat) ?>
						</td>
						
					</tr>
					<?php } ?>
        </table>
    
    </div>
    </div>
			
		<?php if(count($this->order->addons)>0){?>
			<div class="row-fluid">
					<?php echo BookProHelper::renderLayout('addons', $this->order)?>
			</div>	
		<?php } ?>
		
		<div class="row-fluid">
			<?php
			echo $this->loadTemplate('passengers');
			?>
		</div>
	<div class="row-fluid">
		<div class="span6 offset6">
		<?php echo BookProHelper::renderLayout('charge', $this->order)?>
		</div>
	</div>


</div>
</div>
<div class="span3">

<?php echo $this->loadTemplate('header')?>


</div>
</div>