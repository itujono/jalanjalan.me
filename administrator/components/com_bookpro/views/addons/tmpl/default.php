<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: tours.php 21 2012-07-06 04:06:17Z quannv $
 **/

defined ( '_JEXEC' ) or die ();
JHtml::_ ( 'dropdown.init' );
JHtml::_ ( 'formbehavior.chosen', 'select' );

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
BookProHelper::setSubmenu ( 1 );

$client		= $this->state->get('filter.client_id') ? 'administrator' : 'site';
$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder 	= ($listOrder == 'a.ordering' && $listDirn == 'asc');
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_bookpro&task=addons.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'weblinkList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = array(
				'a.state' => JText::_('JSTATUS'),
				'a.title' => JText::_('COM_BOOKPRO_ADDON_TITLE'),
				'a.price' => JText::_('COM_BOOKPRO_ADDON_PRICE'),
				'a.child_price' => JText::_('COM_BOOKPRO_ADDON_CHILD_PRICE'),
				'a.id' => JText::_('JGRID_HEADING_ID')
		);
?>

<div class="span10" id="j-main-container">
<form action="<?php echo JRoute::_('index.php?option=com_bookpro&view=addons'); ?>" method="post" name="adminForm" id="adminForm">



			<table class="table-striped table">
				<thead>
					<tr>
						<th width="1%" class="hidden-phone">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
						<th width="1%" style="min-width: 55px" class="nowrap center">
							<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $this->escape($this->state->get('list.direction')), $this->escape($this->state->get('list.ordering'))); ?>
						</th>
						<th>
							<?php echo JHtml::_('grid.sort', 'COM_BOOKPRO_ADDON_PRICE', 'a.price', $this->escape($this->state->get('list.direction')), $this->escape($this->state->get('list.ordering'))); ?>
						</th>
						<th width="1%" class="nowrap">
							<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $this->escape($this->state->get('list.direction')), $this->escape($this->state->get('list.ordering'))); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="7">
						<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
			
			<?php if($this->items)foreach ( $this->items as $i => $item ) {
				$canChange  = $user->authorise('core.edit.state', 'com_bookpro.addon.'.$item->id);	
			?>
				<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->position?>">
					
						<td class="center">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>

					<td class="center">
						<?php echo JHtml::_('jgrid.published', $item->state, $i, 'addons.', true, 'cb'); ?>
					</td>
						<td>
							<a href="<?php echo JRoute::_('index.php?option=com_bookpro&task=addon.edit&id='.$item->id);?>">
								<?php echo $this->escape($item->title); ?>
							</a>
						</td>
						<td class="left">
							<?php echo CurrencyHelper::formatprice($item->price); ?>
						</td>
						
						
						<td class="center">
						<?php echo (int) $item->id; ?>
					</td>
					</tr>
			<?php } ?>
		</tbody>
		</table>
			<div>
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
				<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
				<?php echo JHtml::_('form.token'); ?>
		</div>
</form>
</div>