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
$station = $displayData->stations;
$config=JBFactory::getConfig();
$check=$config->get('door2door');
$boardings = BusHelper::getStationSelectByType ( $station, 1 );
$droppings = BusHelper::getStationSelectByType ( $station, 2 );
if (count ( $boardings ) > 0) {
	
	?>

<div class="<?php echo BookProHelper::bsrow() ?>">


	<div class="<?php echo BookProHelper::bscol(6) ?>">
	<label class="text-highlight"><?php echo $displayData->fromName ?>:</label> 
	
	<div class="row-fluid">
	<?php echo AHtml::getFilterSelect ( $displayData->board_field, JText::_ ( 'COM_BOOKPRO_SELECT_BOARDING_POINT' ), $boardings, '', false, '', 'id', 'stoptitle' )?>
   </div>
  
    <?php if($check) { ?>
    <label class="text-warning"><?php echo JText::_('COM_BOOKPRO_BOARDING_NOTE') ?></label>
    <label><?php echo JText::_('COM_BOOKPRO_PRIVATE_ADDRESS')?></label>
     <input type="text" class="span12" name="<?php echo $displayData->private_board_field ?>">
     <?php }?>
	</div>
							
<?php } ?>
							
<?php if (count ( $droppings ) > 0) {	?>			
<div class="<?php echo BookProHelper::bscol(6) ?>">

<label class="text-highlight"><?php echo $displayData->toName ?>:</label> 

<div class="<?php echo BookProHelper::bsrow() ?>">
<?php
	echo AHtml::getFilterSelect ( $displayData->dropping_field, JText::_ ( 'COM_BOOKPRO_SELECT_DROPPING_POINT' ), $droppings, '', false, 'class="input input-large"', 'id', 'stoptitle' )?>
  </div>
  <?php if($check)  {
  	
  	
  	?>
  	<label class="text-warning"><?php echo JText::_('COM_BOOKPRO_DROPPING_NOTE') ?></label> 
  	<label><?php echo JText::_('COM_BOOKPRO_PRIVATE_ADDRESS')?></label>
	<input type="text" class="span12" name="<?php echo $displayData->private_drop_field ?>" >
	<?php }?>
	</div><?php
}
?>
</div>