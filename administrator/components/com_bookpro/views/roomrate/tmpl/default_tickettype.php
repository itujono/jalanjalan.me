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
AImporter::model ( 'cgroups' );

$model = new BookProModelCGroups ();
$state=$model->getState();
$state->set('filter.state',1);
$state->set('list.ordering','a.id');
$state->set('list.direction','asc');
$cgroups = $model->getItems ();
?>

<table style="width: 250px">
	<tr>
		<td></td>
		<td><?php echo JText::_('COM_BOOKPRO_ONEWAY'); ?></td>
		<td><?php echo JText::_('COM_BOOKPRO_ROUNDTRIP'); ?></td>
	</tr>
	<?php
	
	for($i = 0; $i < count ( $cgroups ); $i ++) {
		
		?>

	<tr>

		<td><?php echo $cgroups[$i]->title?>
		<input type="hidden" value="<?php echo $cgroups[$i]->id ?>"
			name="pricetype[]" /></td>
		<td><input class="input-mini" type="text" name="adult[]"
			required="required" /></td>
		<td><input class="input-mini" type="text"
			name="adult_roundtrip[]" /></td>
	</tr>


<?php } ?>

	<?php if(!$this->obj->parent_id) {?>
	<tr>

		<td><?php echo JText::_('COM_BOOKPRO_SEAT'); ?>
			</td>
		<td><input class="input-mini" type="number" name="seat"	required="required" value="<?php echo $this->obj->seat ?>"/></td>
		
	</tr>
	<?php } ?>
	
		<tr>

		<td><?php echo JText::_('COM_BOOKPRO_DRIVER'); ?>
			</td>
		<td><?php echo $this->getDrivers() ?></td>
		
		
	</tr>
	
	

</table>