<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 81 2012-08-11 01:16:36Z quannv $
 **/


defined('_JEXEC') or die('Restricted access');
$start=JFactory::getDate()->format('Y-m-d');

$end=JFactory::getDate()->add(new DateInterval('P3M'))->format('Y-m-d');
?>
<div class="well well-small">

<legend>Availability & Price</legend>
<div class="row-fluid">


			<label><?php echo JText::_('COM_BOOKPRO_START_DATE_'); ?> 
			</label>
			<?php echo JHtml::calendar($start, 'startdate', 'startdate','%Y-%m-%d','readonly="readonly"') ?>

			<label><?php echo JText::_('COM_BOOKPRO_END_DATE_'); ?> 
			</label>
			<?php echo JHtml::calendar($end, 'enddate', 'enddate','%Y-%m-%d','readonly="readonly"') ?>
			
			<label><?php echo JText::_('COM_BOOKPRO_WEEK_DAY'); ?> 
			</label>
			
			<?php echo $this->getDayWeek('weekday[]') ?>
			<hr/>	
			 
				<?php echo $this->loadTemplate('tickettype') ?>		
			
			
			</div>
		</div>

