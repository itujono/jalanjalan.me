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

/* @var $this BookingViewSubjects */
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('bootstrap.tooltip');
AImporter::helper('date','currency');

BookProHelper::setSubmenu(1);

$itemsCount = count($this->items);
$pagination = &$this->pagination;

$listOrder = $this->escape ( $this->state->get ( 'list.ordering' ) );
$listDirn = $this->escape ( $this->state->get ( 'list.direction' ) );

$default=JFactory::getApplication()->getUserStateFromRequest('reports.depart', 'depart',JFactory::getDate()->format('Y-m-d'));


?>

<div id="j-main-container" class="span10">
	<form action="<?php echo JRoute::_('index.php?option=com_bookpro&view=reports'); ?>" method="post" name="adminForm" id="adminForm">
		<div>
			<div class="form-inline">
				
				<?php echo JHtml::calendar($default,'depart','depart','%Y-%m-%d','')?>
				<?php echo $this->dfrom;?>
				<?php echo $this->dto;?>
				<?php echo $this->agents;?>
				
				<button class="btn" onclick="this.form.submit();"><?php echo JText::_('COM_BOOKPRO_SEARCH'); ?></button>
				
			</div>
		</div>
		<table class="table">
			<thead>
				<tr>

					<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?>

					</th>
					
					<th class="title" width="13%"><?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_BUSTRIP_TITLE'), 'title', $listDirn, $listOrder); ?>
					</th>
					<th width="5%"><?php echo JText::_('COM_BOOKPRO_BUSTRIP_CODE'); ?>
					
					<th width="5%"><?php echo JText::_('COM_BOOKPRO_COMPANY'); ?>
					</th>
					<th width="5%"><?php echo JText::_('COM_BOOKPRO_BUSTRIP_BUS'); ?>
					</th>
					<th width="5%"><?php echo JText::_('COM_BOOKPRO_SEATTEMPLATE'); ?>
					</th>
					<th width="5%"><?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_BUSTRIP_START_TIME'), 'start_time', $listDirn, $listOrder); ?> 
					</th>
					<th width="5%"><?php echo JText::_('COM_BOOKPRO_BUSTRIP_END_TIME'); ?>
					</th>
					<th width="2%"><?php echo JText::_('COM_BOOKPRO_SEATS'); ?>
					</th>
					<th width="2%">
					</th>
					
					 
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="10"><?php echo $pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php 
						 for ($i = 0; $i < count($this->items); $i++) {
				    	 	$subject = &$this->items[$i];
				    	 	?>
				<tr class="<?php if (($subject->price==0) || !$subject->seattemplate_title ) echo "warning"; else echo "success" ?>">

					<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?>
					</td>
					
					<td><?php echo str_repeat('<span class="gi">&mdash;</span>', $subject->level - 1) ?>
						<a	href="<?php echo JRoute::_('index.php?option=com_bookpro&task=bustrip.edit&id='.$subject->id);?>"><?php echo $subject->title; ?> </a>
					</td>
					<td><?php  echo $subject->code;?>				</td>
					
					<td><?php  echo $subject->brandname;?>
					</td>
					<td><?php  echo $subject->bus_name;?>
					</td>
					<td><?php  echo $subject->seattemplate_title;?>
					</td>
					
					<td><?php echo JFactory::getDate($subject->start_time)->format('H:i'); ?></td>
					<td><?php echo JFactory::getDate($subject->end_time)->format('H:i'); ?></td>
					
					<?php 
					$color='';
					if($subject->price > 0){
						$color='style="color:blue;"';
					}else{
						$color='style="color:red"';
						}
					?>
					<td>
					<?php echo count(BusHelper::getBookedSeatSingleRoute($default, $subject->id))  ?>						
					</td>
					
					<td><a href="index.php?option=com_bookpro&controller=report&task=cancelroute&date=<?php echo $default?>&id=<?php echo $subject->id ?>" class="btn btn-success">
					<?php echo JText::_('JCANCEL') ?>	</a>					
					</td>

					 
				<?php 
}

					?>
			</tbody>
		</table>

		
		<input type="hidden" name="task" value="" />		
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHTML::_('form.token'); ?>
	</form>
</div>
