<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 23 2012-07-08 02:20:56Z quannv $
 **/


defined ( '_JEXEC' ) or die ( 'Restricted access' );
AImporter::helper('currency','date');
$passengers=$displayData;
?>
<style>
table {
	width: 375px;
	font-size: 14px;
	font-family: times new roman;
}
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
table .tr1 {
	font-family: arial;
    font-size: 18px;
}
</style>

<?php for ($i = 0; $i < count($passengers); $i++) {
	
	$this->ticket=$passengers[$i];
	
  	//echo "<pre>";print_r($this->ticket);
	//die;
?>
	
	
<table style="border:1px;">
	<tbody>
		<tr>
			<th class="tr1" colspan="4"><?php echo $this->ticket->bustrip->brandname ?></th>
		</tr>
		<tr>
			<td class="tr1" align="center" colspan="4"><?php echo JText::sprintf('COM_BOOKPRO_TICKET_NO_TXT',$this->ticket->order_number)?></td>
		</tr>
		<!-- 
		<tr>
			<td class="tr1" align="center" colspan="4"><?php echo JText::sprintf('COM_BOOKPRO_PNR_TXT',$this->ticket->pnr)?></td>
		</tr>
		 -->
		<tr>
			<td align="center" colspan="4"><?php echo JText::_('COM_BOOKPRO_NON_REFUNDABLE') ?></td>
		</tr>
		<tr>
			<th colspan="4"><?php echo JText::_('COM_BOOKPRO_BOOKING_INFORMATION') ?></th>
		</tr>
		<tr>
			<td colspan="4"><?php echo JText::sprintf('COM_BOOKPRO_PASSENGER_NAME_TXT',$this->ticket->name)?></td>
		</tr>
		
		
		<tr>
			<td colspan="4"><b>FROM:</b> <?php echo $this->ticket->bustrip->from_title; ?> 
			 <?php 
					if (isset($this->ticket->bustrip->boarding)){
										
										echo "<br/>";
										echo JText::sprintf('COM_BOOKPRO_BOARDING_TXT',$this->ticket->bustrip->boarding['location'],$this->ticket->bustrip->boarding['depart']);
									}
								?>
			 
			 </td>
		</tr>
		<tr>
			<td colspan="4"><b>TO:</b> <?php echo $this->ticket->bustrip->to_title ?>
			
			<?php 
									if (isset($this->ticket->bustrip->dropping)){
										echo "<br/>";
										
										echo JText::sprintf('COM_BOOKPRO_DROPPING_TXT',$this->ticket->bustrip->dropping['location'],$this->ticket->bustrip->dropping['depart']);
									}
								?>
			</td>
		</tr>
		
		
		<tr>

			<td align="center"><?php echo JText::_('COM_BOOKPRO_SEAT') ?></td>
			<td align="center"><?php echo JText::_('COM_BOOKPRO_BUSTRIP_CODE') ?></td>
			<td align="center"><?php echo JText::_('COM_BOOKPRO_DATE') ?></td>
			<td align="center"><?php echo JText::_('COM_BOOKPRO_TIME') ?></td>
		</tr>
		<tr>
			<td align="center"><?php echo $this->ticket->seat ?></td>
			<td align="center"><?php echo $this->ticket->bustrip->code ?></td>
			<td align="center"><?php echo DateHelper::toShortDate($this->ticket->start) ?></td>
			<td align="center"><?php 
			$depart='';
			if(isset($this->ticket->bustrip->boarding))
				$depart=$this->ticket->bustrip->boarding['depart'];
			$depart=$depart?$depart:$this->ticket->bustrip->start_time;
			$depart  = date("g:i A", strtotime($depart));
			echo $depart  ?></td>
		</tr>
		
		<?php if($this->ticket->roundtrip) {?>
		
			<tr>
			<td colspan="4"><b>FROM:</b> <?php echo $this->ticket->rbustrip->from_title; ?> 
			 <?php 
					if (isset($this->ticket->rbustrip->boarding)){
										
										echo "<br/>";
										echo JText::sprintf('COM_BOOKPRO_BOARDING_TXT',$this->ticket->rbustrip->boarding->location,$this->ticket->rbustrip->boarding->depart);
									}
								?>
			 
			 </td>
		</tr>
		<tr>
			<td colspan="4"><b>TO:</b> <?php echo $this->ticket->rbustrip->to_title ?>
			
			<?php 
									if (isset($this->ticket->rbustrip->dropping)){
										
										echo "<br/>";
										
										echo JText::sprintf('COM_BOOKPRO_DROPPING_TXT',$this->ticket->rbustrip->dropping->location,$this->ticket->rbustrip->dropping->depart);
									}
								?>
			</td>
		</tr>
		
		<tr>

			<td align="center"><?php echo JText::_('COM_BOOKPRO_SEAT') ?></td>
			<td align="center"><?php echo JText::_('COM_BOOKPRO_BUSTRIP_CODE') ?></td>
			<td align="center"><?php echo JText::_('COM_BOOKPRO_DATE') ?></td>
			<td align="center"><?php echo JText::_('COM_BOOKPRO_TIME') ?></td>
		</tr>
		<tr>
			<td align="center"><?php echo $this->ticket->return_seat ?></td>
			<td align="center"><?php echo $this->ticket->rbustrip->code ?></td>
			<td align="center"><?php echo DateHelper::toShortDate($this->ticket->return_start) ?></td>
			<td align="center"><?php 
			
			$depart=$this->ticket->rbustrip->boarding->depart;
			$depart=$depart?$depart:$this->ticket->rbustrip->start_time;
			$depart  = date("g:i A", strtotime($depart));
			echo $depart
			
			
			
			?></td>
		</tr>	
				
		
		<?php } ?>
		
		
		<tr>
			<td colspan="4"><b><?php echo JText::sprintf('COM_BOOKPRO_PASSENGER_PRICE_TXT', CurrencyHelper::formatprice($this->ticket->price))?>
			</b></td>
		</tr>
		
		<tr>
			<td colspan="4"><b><?php echo JText::sprintf('COM_BOOKPRO_BOOKING_TYPE_TXT',$this->ticket->type)?></b></td>
		</tr>
		
		<tr>
			<td align="center" colspan="4"><?php echo JHtml::_('date',$this->ticket->created) ?>-<?php echo $this->ticket->pay_method ?></td>
		</tr>
		<tr>
			<td align="center" colspan="4"><b><?php echo JText::_('COM_BOOKPRO_TICKET_THANKYOU') ?></b></td>
		</tr>

	</tbody>
</table>

<hr/>

<div class="row-fluid">
		
		
		<img alt="" src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=<?php echo $this->ticket->order_number ?>"/>
		
		<img alt="" src="http://www.barcodes4.me/barcode/c39/<?php echo $this->ticket->order_number ?>.png" align="right"/>
		
		
		</div>


<?php 
}
?>

