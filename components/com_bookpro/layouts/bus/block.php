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
$a_row = $displayData;
$array_deny_select=null;

if($a_row->booked_seat_location){
$a_row->booked_seat_location=str_replace(array('[', ']','"'), '', $a_row->booked_seat_location);
//$array_deny_select=explode(',', trim($a_row->booked_seat_location));
}

 $block_layout =json_decode($a_row->block_layout);
 $upper_block_layout =json_decode($a_row->upper_block_layout);
 
 $config=JComponentHelper::getParams('com_bookpro');
 $driver_hand = $config->def('driver_hand',0);
 

 //$maxselect = 
 
 
 ?>
 
 <style>
 
 .jbdriver {

    -webkit-transform: rotate(90deg);
    -webkit-transform-origin: left top;
    -moz-transform: rotate(90deg);
    -moz-transform-origin: left top;
    -ms-transform: rotate(90deg);
    -ms-transform-origin: left top;
    -o-transform: rotate(90deg);
    -o-transform-origin: left top;
    transform: rotate(90deg);
    transform-origin: left top;

    position: absolute;
    top: 30px;
   	left:20px;
    white-space: nowrap;    
    font-size: 18px;
}
 
 </style>
 
<div class="listseat">
	<!--  
	<div class="selectmsg hidden-phone"><span><?php echo JText::_('COM_BOOKPRO_SEAT_SELECT_TIPS') ?></span></div>
	-->
	
	<div class="row-fluid hidden-desktop mob-legend">
				<span class="avaiableseat"><?php echo JText::_('COM_BOOKPRO_SEAT_AVAILABLE') ?></span>
				<span class="selectedseat"><?php echo JText::_('COM_BOOKPRO_SEAT_SELECTED') ?></span>
				<span class="bookedseat"><?php echo JText::_('COM_BOOKPRO_SEAT_BOOKED') ?></span>
			</div>
	
	<div class="formchooseseat">
	
		
		<div class="bodybuyt"  style="width: <?php echo $block_layout->column*30+30 ?>px">
		   <!-- 
		    <div class="control">
			 <div class="lowerlabel <?php echo $driver_hand == 1 ? "lowerlabel-right":"lowerlabel-left"; ?>"></div>
			 
			</div>
			 -->
			 
			 <div class="jbdriver" style=""><?php echo JText::_('COM_BOOKPRO_DRIVER') ?></div>
			
			<div class="seats">
                <div class="block_layout<?php echo $a_row->id ?> <?php echo rand(5, 15); ?>" id="show-block-<?php echo $a_row->id ?>">
                </div>
			</div>
		</div>
		<div class="noteseats hidden-phone">
			<ol class="seatsDefn">
				<li class="avaiableseat seat_seleeper"><?php echo JText::_('COM_BOOKPRO_SEAT_AVAILABLE') ?></li>
				<li class="selectedseat seat_seleeper"><?php echo JText::_('COM_BOOKPRO_SEAT_SELECTED') ?></li>
				<li class="bookedseat seat_seleeper"><?php echo JText::_('COM_BOOKPRO_SEAT_BOOKED') ?></li>
			</ol>
			
		</div>
		<div class="payout">
				<div class="yourseat_<?php echo $a_row->id?>"><span><?php echo JText::_('COM_BOOKPRO_SEAT_CHOSEN') ?></span><span class="yourseat_<?php echo $a_row->id?>"></span><div class="spanlistseat"></div></div>
		</div>	
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($){
    /*
    $("#show-block.block_layout<?php echo $a_row->block_layout_id ?>").css({
        width:($('#show-block.block_layout<?php echo $a_row->block_layout_id ?> .block_item').width()+10)*<?php echo $block_layout->column ?>,
        display:"lock"
        
        
    });
    */
    $('#show-block-<?php echo $a_row->id ?>').creteseat({
        row:<?php echo $block_layout->row ?>,
       	areturn:<?php echo $a_row->return?1:0?>,
        column:<?php echo $block_layout->column ?>,
        block_type: $.parseJSON('<?php echo json_encode($block_layout->block_type) ?>'),
        seatnumber: $.parseJSON('<?php echo json_encode($block_layout->seatnumber) ?>'),
<?php if($a_row->upper_block_layout){?>
        upper_row:<?php echo $upper_block_layout->row ?>,
        upper_column:<?php echo $upper_block_layout->column ?>,
        upper_block_type: $.parseJSON('<?php echo json_encode($upper_block_layout->block_type) ?>'),
        upper_seatnumber: $.parseJSON('<?php echo json_encode($upper_block_layout->seatnumber) ?>'),
<?php }?>
		        
        listselected:$.parseJSON('<?php echo json_encode(explode(",", $a_row->booked_seat_location)) ?>'),
        hidden_input_submit:"<?php echo $a_row->hidden_input_submit_name ?>",
        show_lable:'span.yourseat_<?php echo $a_row->id?>',
        maxselect:adult,
        callbacks:{
        	onclickseat:function(selected,areturn){
           	 
           	if(areturn == 0){
           		$('#order_seat').val(selected);  	
            }else{
            	$('#order_return_seat').val(selected);    
            }
           	
       		
                 
            }
        }
    });
    // $('#show-block-<?php echo $a_row->id ?>').creteseat('option','onclickseat');
    /*
    $('#show-block-<?php echo $a_row->block_layout_id ?>').creteseat('option',{
        listselected:[1,3,8]
    });
    
    $('#show-block-<?php echo $a_row->block_layout_id ?>').creteseat('destroy');
    */
});
</script> 
