<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 26 2012-07-08 16:07:54Z quannv $
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/* @var $this BookingViewReservations */

JHTML::_ ( 'behavior.tooltip' );

$bar = JToolBar::getInstance('toolbar');
BookProHelper::setSubmenu ( 2 );

JToolBarHelper::title ( JText::_ ( 'COM_BOOKPRO_WIRE_MANAGER' ), 'user.png' );

$itemsCount = count($this->items);
$pagination = &$this->pagination;
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));


?>

<div id="j-main-container" class="span10">
	<form action="index.php?option=com_bookpro&view=wiretransfers" method="post" name="adminForm" id="adminForm">

        <div class="well well-small">
    		<div class="form-inline">
    			<label for="company_name" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER');?></label>
				<input type="text" name="company_name" id="company_name" placeholder="<?php echo JText::_('COM_BOOKPRO_COMPANY_NAME'); ?>" value="<?php echo $this->escape($this->state->get('filter.company_name')); ?>" title="<?php echo JText::_('COM_BOOKPRO_COMPANY_NAME'); ?>" />
    			<?php
    			if ($this->state->get('filter.fromdate')){
    				$fromdate = DateHelper::createFromFormat($this->state->get('filter.fromdate'))->format('d-m-Y');
    			}else{
    				$fromdate = $this->state->get('filter.fromdate');
    			}
    			if ($this->state->get('filter.todate')){
    				$todate = DateHelper::createFromFormat($this->state->get('filter.todate'))->format('d-m-Y');
    			}else{
    				$todate = $this->state->get('filter.todate');
    			}
    			
    			echo JHtml::calendar($fromdate, 'filter_from_date','filter_from_date',DateHelper::getConvertDateFormat('M'),'placeholder="From date" style="width: 100px;"') ?>
    			<?php echo JHtml::calendar($todate, 'filter_to_date','filter_to_date',DateHelper::getConvertDateFormat('M'),'placeholder="To date" style="width: 100px;"') ?>
                
                
                <?php 
                echo AHtml::getFilterSelect('wire_status', JText::_('COM_BOOKPRO_WIRE_STATUS'),array("Wired"=>JText::_('COM_BOOKPRO_WIRE_STATUS_WIRED'),"Processing"=>JText::_('COM_BOOKPRO_WIRE_STATUS_PROCESSING'),"Block"=>JText::_('COM_BOOKPRO_WIRE_STATUS_BLOCK')), $this->state->get('filter.wire_status'), false, 'class="input input-medium"', 'value', 'text')
                ?>
    			
				<button onclick="this.form.submit();" class="btn btn-success">
					<?php echo JText::_('COM_BOOKPRO_SEARCH'); ?>
				</button>
                <button class="btn hasTooltip" type="button" title="" onclick="document.id('company_name').value='';document.id('filter_from_date').value='';document.id('filter_to_date').value='';document.id('wire_status').value='0';this.form.submit();" data-original-title="Clear"><i class="icon-remove"></i></button>
    				
    			</div>
    		
            </div>
      
		
			<table class="table">
				<thead>
					<tr>
						
					<th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?>
					</th>
					
						<th >
				        <?php echo JText::_('COM_BOOKPRO_PAYMENT_DATE'); ?>
					</th>
                    	<th class="title" >
				        <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_COMPANY_NAME'), 'company_name', $listDirn, $listOrder); ?>
					</th>
                     <th >
				        <?php echo JText::_('COM_BOOKPRO_TOTAL_PARTNER'); ?>
					</th>
                    
                     <th >
				        <?php echo JText::_('COM_BOOKPRO_TOTAL_AGENT'); ?>
					</th>
                    
                    
                    
                    <th >
				        <?php echo JText::_('COM_BOOKPRO_GRAND_TOTAL'); ?>
					</th>
                   
                   	<th >
				        <?php echo JText::_('COM_BOOKPRO_WIRE_STATUS'); ?>
					</th>
                    
                    <th >
				        <?php echo JText::_('COM_BOOKPRO_WIRE_ID'); ?>
					</th>
                    
                    <th >
				        <?php echo JText::_('COM_BOOKPRO_WIRE_TRANSFER_DATE'); ?>
					</th>
                    

					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="10">
    				    <?php echo $pagination->getListFooter(); ?>
    				</td>
					</tr>
				</tfoot>
				<tbody>
				<?php if (! is_array($this->items) || ! $itemsCount) { ?>
					<tr>
						<td colspan="10"><?php echo JText::_('COM_BOOKPRO_NO_ITEM'); ?></td>
					</tr>
				<?php } else { ?>
				    <?php for ($i = 0; $i < $itemsCount; $i++) { ?>
				    
				    	<?php
						
						$subject = &$this->items [$i];
						$link = JRoute::_ ( ARoute::edit ( CONTROLLER_COUPON, $subject->id ) );
						?>
	
				    			<td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
				    <td><?php echo JFactory::getDate($subject->payment_date)->format('d M Y') ; ?> </td>
						<td><a href="<?php echo JRoute::_('index.php?option=com_bookpro&task=wiretransfer.edit&id='.$subject->id); ?>"><?php echo $subject->company; ?></a></td>
                        <td><?php echo $subject->total_partner; ?> </td>
                         <td><?php echo $subject->total_agent; ?> </td>
                        <td><?php echo CurrencyHelper::displayPrice($subject->grand_total) ; ?> </td>
                       <td><?php 
                       echo AHtml::getFilterSelect('changewire_status'.$subject->id,'',array("wired"=>JText::_('COM_BOOKPRO_WIRE_STATUS_WIRED'),"processing"=>JText::_('COM_BOOKPRO_WIRE_STATUS_PROCESSING'),"block"=>JText::_('COM_BOOKPRO_WIRE_STATUS_BLOCK')), $subject->wire_status, false, 'wireid="'.$subject->id.'" class="input input-small changewire_status"', 'value', 'text');
                       ?>
                      
                        </td>
                        <td><?php echo $subject->wire_id; ?> </td>
                        <td><?php echo JFactory::getDate($subject->wire_transfer_date)->format('d M Y') ; ?> </td>
					</tr>
				    <?php } ?>
				<?php } ?>
			</tbody>
			</table>
		
		<input type="hidden" name="option" value="<?php echo OPTION; ?>" /> 
		<input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>" />
	
	<input type="hidden" name="reset" value="0" /> 
	 <input type="hidden" name="boxchecked" value="0" /> 
	 <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" /> 
	 <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" /> 
	<?php echo JHTML::_('form.token'); ?>
</form>
</div>

<script>


jQuery(document).ready(function(){
    jQuery('.changewire_status').change(function(){
                var item_this = jQuery(this);
                var wireid = jQuery(this).attr('wireid');
                var wire_status = jQuery(this).val();
                    jQuery.ajax({
                            'type': 'post',
                            data: 'wireid='+wireid+'&wire_status='+wire_status,   
                            'url': 'index.php?option=com_bookpro&controller=wiretransfers&task=changewire_status&format=raw&tmpl=component',
                            success: function(data){
                                    alert(data);
                                location.reload();
                            } 
                });
      });
});



</script>
