<?php 
	/**
	 * @package 	Bookpro
	 * @author 		Ngo Van Quan
	 * @link 		http://joombooking.com
	 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
	 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
	 * @version 	$Id$
	 **/
	
	defined('_JEXEC') or die('Restricted access');
?>

<div class="well well-small wellwhite">

		
		<div class="row-fluid">
				<?php echo BookProHelper::renderLayout('tripinfo', $this->order)?>
		</div>	
		
		<?php if(count($this->order->addons)>0){?>
			<div class="row-fluid">
					<?php echo BookProHelper::renderLayout('addons', $this->order)?>
			</div>	
		<?php } ?>
		
		<div class="row-fluid">
			<?php 
				echo BookProHelper::renderLayout('passengers', $this->order);
			?>
		</div>
		<div class="row-fluid" >
		<div class="span6 offset6">
		<?php echo BookProHelper::renderLayout('charge', $this->order) ?>
		</div>
		</div>
	

</div>