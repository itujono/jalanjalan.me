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
JToolBarHelper::apply();
JToolBarHelper::custom('delete','delete','','Delete Price By Filter',false);
JToolBarHelper::custom('emptylog','delete','','Empty Price History',false);
JToolBarHelper::custom('emptyrate','delete','','Empty Price',false);
JHtmlJquery::framework();
AImporter::js('jquery.magnific-popup.min');
AImporter::css('magnific-popup');

JToolBarHelper::cancel();
JToolBarHelper::title(JText::_('COM_BOOKPRO_RATE_MANAGER'), 'calendar');
$itemsCount = count($this->items);
$pagination = &$this->pagination;

$mainframe=JFactory::getApplication();
$startdate=$mainframe->getUserStateFromRequest ('rate.startdate', 'startdate',JFactory::getDate()->format('Y-m-d') );
$enddate=$mainframe->getUserStateFromRequest ('rate.enddate', 'enddate',JFactory::getDate()->add(new DateInterval('P60D'))->format('Y-m-d') );


?>


<script type="text/javascript">

jQuery( document ).ready(function($) {


	$(document).on('click', '.mgpopup', function (e) {

	    e.preventDefault();

	    $.magnificPopup.open({
	        items: {
	            src: $(this).attr('href')
	        },
	        type: 'ajax',
	        closeOnBgClick: false 
	    });

	});

	
});
</script>


<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		var checked = true;
		var input 	= null; 
		if(task=="apply" || task=="save"){
			jQuery('#formvalidate input').each(function(){
				if(!jQuery(this).val()){
						jQuery(this).focus();
						checked = false; 
						return false; 		
					}
				});
			}
		
		if(checked){
			Joomla.submitform(task, document.getElementById('adminForm'));
			}else{
				alert('Please fill the required fields');
			}
	}
</script>


<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="span4" id="formvalidate">
		<div class="well well-small">
		<div class="row-fluid">
			
			<label><?php echo JText::_('COM_BOOKPRO_ROOM_'); ?> 
			</label>
			<?php echo $this->rooms ?>
			 <?php $linkrd = JUri::base().'index.php?option=com_bookpro&view=roomrates&bustrip_id='.$this->obj->id;?>
						<a href="<?php echo $linkrd;?>" ><i
							class="icon-calendar icon-large"></i>View</a>

			<label><?php echo JText::_('COM_BOOKPRO_START_DATE_'); ?> 
			</label>
			<?php echo JHtml::calendar($startdate, 'startdate', 'startdate','%Y-%m-%d','readonly="readonly"') ?>

			<label><?php echo JText::_('COM_BOOKPRO_END_DATE_'); ?> 
			</label>
			<?php echo JHtml::calendar($enddate, 'enddate', 'enddate','%Y-%m-%d','readonly="readonly"') ?>
			
			<label><?php echo JText::_('COM_BOOKPRO_WEEK_DAY'); ?> 
			</label>
			
			<?php echo $this->getDayWeek('weekday[]') ?>
			<hr/>	
			 
			<?php echo $this->loadTemplate('tickettype') ?>		
			
			</div>
		</div>

	</div>
	
<input type="hidden" name="option" value="com_bookpro" /> 
	<input		type="hidden" name="controller" value="roomrate" />
		 <input	type="hidden" name="task" value="" /> 
		<input type="hidden"	name="boxchecked" value="" />
		 <input type="hidden" name="cid[]"
		value="<?php echo $this->obj->id; ?>" id="cid" />

	<?php echo JHTML::_('form.token'); ?>
</form>	

	<div class="span8">
		
		<div class="row-fluid">
			<?php echo $this->loadTemplate('calendar')?>
		</div>
		
		<h3><?php echo JText::_('COM_BOOKPRO_PRICE_HISTORY')?></h3>	
		<table class="table table-stripped">
			<thead>
				<tr>
					<th width="30%"><?php echo JText::_("COM_BOOKPRO_ROOM_TYPE_NAME");?>
					</th>
					<th><?php echo JText::_("COM_BOOKPRO_DATE__END_DATE");?></th>
					<th><?php echo JText::_("COM_BOOKPRO_PRICE");?>
					</th>
					
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="9"><?php echo $pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>


			<?php if (! is_array($this->items)) { ?>
			<tbody>
				<tr>
					<td colspan="5" class="emptyListInfo"><?php echo JText::_('COM_BOOKPRO_NO_ITEMS_FOUND'); ?>
					</td>
				</tr>
			</tbody>
			<?php 

} else {

                                     for ($i = 0; $i < $itemsCount; $i++) {
                                         $subject = &$this->items[$i];
                                         $reg=new Joomla\Registry\Registry();
                                         $reg->loadString($subject->params);
                                         $params=$reg->toArray();
                                         ?>
			<tbody>
				<tr <?php if($i==0) echo 'class="success"'?>>

					<td><?php echo $subject->from_name ?>-<?php echo $subject->to_name ?></td>
					<td style="font-weight: normal;"><?php echo DateHelper::formatDate($params['start'],'Y-m-d').' -> '.DateHelper::formatDate($params['end'],'Y-m-d'); ?>
					</td>
					<td><?php echo CurrencyHelper::displayPrice($params['adult']) ?></td>
					
				</tr>
			</tbody>
			<?php 
                                    }
                                }
                                ?>
		</table>

	</div>

	

