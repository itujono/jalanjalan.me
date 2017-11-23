<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: currency.php 16 2012-06-26 12:45:19Z quannv $
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );
AImporter::helper ( 'currency' );

?>
<style>
dl dd {
	display: block;
	font-weight: bold;
}
</style>
<div class="<?php echo BookProHelper::bsrow() ?> bpcart">
	<h2 class='block_head'>
		<span><?php echo JText::_("COM_BOOKPRO_CART_SUMMARY")?> </span>
	</h2>

	<dl class="dl-horizontal">
		
		<?php if ($this->order->discount){?>
		<dt>
			<?php echo JText::_('COM_BOOKPRO_ORDER_TOTAL_ORIGINAL')?>
		</dt>
		<dd>
			<?php echo CurrencyHelper::formatprice($this->order->subtotal)?>
		</dd>
		<?php } ?>
		<?php if($this->order->discount>0){?>	
		<dt>
			<?php echo JText::_('COM_BOOKPRO_ORDER_DISCOUNT')?>
		</dt>
		<dd>
			<?php echo CurrencyHelper::formatprice($this->order->discount)?>
		</dd>
		<?php } ?>
		
		<?php if($this->order->tax){?>	
		<dt>
				<?php echo JText::_('COM_BOOKPRO_ORDER_TAX');?>
		</dt>
		<dd>
				<?php echo  CurrencyHelper::formatprice($this->order->tax); ?>
			</dd>
		
		<?php }?>
		
		<dt>
			<?php echo JText::_('COM_BOOKPRO_ORDER_TOTAL')?>
		</dt>
		<dd>
			<?php echo CurrencyHelper::formatprice($this->order->total)?>
		</dd>



	</dl>


</div>
<div class="<?php echo BookProHelper::bsrow() ?>">
	<div class="form-inline">
<?php if($this->bs=="bs3") echo '<div class="form-group">'?>
			<label><?php echo JText::_('COM_BOOKPRO_COUPON_CODE')?></label> <input
			type="text" value="" class="input-small form-control" name="coupon">
		<button type="submit" class="btn btn-default" id="couponbt"><?php echo JText::_('COM_BOOKPRO_SUBMIT') ?></button>
			
			<?php if($this->bs=="bs3") echo '</div>'?>
			
			</div>
</div>
