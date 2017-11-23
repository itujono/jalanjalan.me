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
JHtml::_('behavior.modal');
JHTML::_('behavior.tooltip');
AImporter::helper('currency','bookpro','date');
//BookProHelper::setSubmenu(1);
JToolbarHelper::title(JText::_('COM_BOOKPRO_PASSENGERS'), 'user');

if(JPluginHelper::isEnabled('bookpro','product_sms')){
	JToolBarHelper::custom('pmibustrips.sendsms', 'tags', 'icon over',JText::_('COM_BOOKPRO_SEND_SMS'), true);
}

$toolbar = JToolbar::getInstance('toolbar');
$toolbar->appendButton( 'Link', 'print', JText::_('COM_BOOKPRO_PRINT'), JUri::base().'index.php?option=com_bookpro&view=pmibustrips&tmpl=component&layout=report' );
$toolbar->appendButton( 'Link', 'download', JText::_('COM_BOOKPRO_PRINT_CSV'), JUri::base().'index.php?option=com_bookpro&view=pmibustrips&tmpl=component&layout=csv' );

JToolBarHelper::custom('pmibustrips.printticket', 'tags', 'icon over', JText::_('COM_BOOKPRO_PRINT_TICKET'), true);


JToolBarHelper::back();

$itemsCount = count($this->items);
$pagination = &$this->pagination;
$date = $this->state->get('filter.date');
 
$params=JComponentHelper::getParams('com_bookpro');


$state = $this->get('State');
$route_id=$state->get('filter.route_id');
$segment=$this->state->get('filter.children') ;
?>

<script type="text/javascript">



 jQuery(document).ready(function($) {
	 
	 $("a#seatmap").click(function(){
			var depart_date	=jQuery('#depart_date').val();
			var router_id	=jQuery('#filter_route_id').val();
			var agent_id	=jQuery('#filter_agent_id').val();
			var children	=jQuery('#filter_children').val();
			var pay_status	=jQuery('#filter_pay_status').val();
			
		if(depart_date=="" &&router_id==0&&agent_id==0){
				alert ('Please enter a agent id and router id and depart date');	 
		}
		
		if(depart_date=="" &&router_id!=0&&agent_id!=0 ){
			alert('Please enter a depart date'); 
		}

		if(depart_date != "" && router_id !="0" && agent_id != "0"){
			 
			$link="index.php?option=com_bookpro&view=seatallocations&depart_date="+depart_date+"&route_id="+router_id+"&agent_id="+agent_id+"&children="+children+"&tmpl=component";
			
			SqueezeBox.fromElement(this, {handler:'iframe', size: {x: 600, y: 450}, url: $link});
		}
			 
		  
		});

	 

		if($("#filter_agent_id").val()>0){

			$.ajax({
				type:"GET",
				url: "<?php echo JUri::base()?>index.php?option=com_bookpro&controller=bustrips&task=getBustrip&id=<?php echo $route_id; ?>&format=raw",
				data:"agent_id="+$("#filter_agent_id").val(),
				beforeSend : function() {
					$("#filter_route_id")
							.html('<option>Loading route</option>');
				},
				success:function(result){
						$("#filter_route_id").html(result);
					}
				});
			
			}

		$("#filter_agent_id").change(function(){
			$.ajax({
				type:"GET",
				url: "<?php echo JUri::base()?>index.php?option=com_bookpro&controller=bustrips&task=getBustrip&id=<?php echo $route_id; ?>&format=raw",
				data:"agent_id="+jQuery(this).val(),
				beforeSend : function() {
					$("#filter_route_id")
							.html('<option>Loading route</option>');
				},
				success:function(result){
						$("#filter_route_id").html(result);
					}
				});
		});

	
	
});

</script>

<form action="index.php?option=com_bookpro&view=pmibustrips" method="post" name="adminForm" id="adminForm">
	
	<div class="well well-small">
				<div class="row-fluid">
				<div class="form-inline">
				<?php echo $this->agentbox;?>
				<select name="filter_route_id" id="filter_route_id" class="input-xlarge">
				<option value="0"><?php echo JText::_('COM_BOOKPRO_SELECT_ROUTE') ?></option>
				</select>
				<label><?php echo JText::_('COM_BOOKPRO_DEPART_DATE')?></label>
				<?php
				if ($this->state->get('filter.depart_date')){
					$date = DateHelper::createFromFormat($this->state->get('filter.depart_date'))->format('d-m-Y');
				}
				else{
					$date = $this->state->get('filter.depart_date');
				}
				echo JHtml::calendar($date, 'filter_depart_date', 'depart_date',DateHelper::getConvertDateFormat('M'),'style="width:80px;"') ?>
				
					<?php 
										
					$item[]=JHtmlSelect::option('1',JText::_('COM_BOOKPRO_ALL_SEGMENT'));
					$item[]=JHtmlSelect::option('0',JText::_('COM_BOOKPRO_SINGLE_SEGMENT'));
					echo JHtmlSelect::genericlist($item, 'filter_children',$attribs = 'class="input-medium"', $optKey = 'value', $optText = 'text', $selected = $segment);
					
					?>
					<button onclick="this.form.submit();" class="btn btn-success"><?php echo JText::_('COM_BOOKPRO_SEARCH'); ?></button>
					
					<?php if ($this->config->get('non_seat')){?>
					<a id="seatmap" class="btn btn-info" >
						Seat allocation
					</a>
					<?php }  ?>
				</div>
				
				<?php 
				
					if(JPluginHelper::isEnabled('bookpro','product_sms')){
						?>
						<fieldset>
						<legend><?php echo JText::_('COM_BOOKPRO_NOTIFICATION')?></legend>
						<?php 
						
						AImporter::model('smss');
						$model=new BookproModelSmss();
						$items=$model->getItems();
						echo JText::_('COM_BOOKPRO_SMSS');
						echo JHtmlSelect::genericlist($items,'sms_id','','id','title');
						
						?>
						
						</fieldset>
						<?php 
					}
				
				?>
		 	
				
					
	
				</div>
	</div>
		
	
	<br/>
	<div class="row-fluid">
		
		<table class="table table-striped" >
			<thead>
				
				
				<tr>
					<th width="3%">#</th>
					<th width="1%"><input type="checkbox" name="checkall-toggle"
						value="" title="(<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
						onclick="Joomla.checkAll(this)" />
					</th>
					<th class="title">
				        <?php echo JText::_('COM_BOOKPRO_PASSENGER_FIRSTNAME'); ?>
					</th>
					<?php if($params->get('ps_lastname')){?>
					<th width="10%">
				        <?php echo JText::_('COM_BOOKPRO_PASSENGER_LASTNAME'); ?>
					</th>
					<?php } ?>
					
					<?php if($params->get('ps_gender')){?>
					<th class="title">
				        <?php echo JText::_('COM_BOOKPRO_PASSENGER_GENDER') ?>
					</th>
					<?php } ?>
					<?php if($params->get('ps_phone')){?>
					<th width="10%">
				        <?php echo JText::_('COM_BOOKPRO_PASSENGER_PHONE'); ?>
					</th>
					<?php } ?>
					
					<?php if($params->get('ps_email')){?>
					<th width="10%">
				        <?php echo JText::_('COM_BOOKPRO_PASSENGER_EMAIL'); ?>
					</th>
					<?php } ?>
					
					<?php if($params->get('ps_passport')){?>
					<th width="10%">
				        <?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT'); ?>
					</th>
					<?php } ?>
					
					
					<th width="10%">
				        <?php echo JText::_('COM_BOOKPRO_PASSENGER_GROUP'); ?>
					</th>
					
					<!-- 
					<th><?php echo JText::_('COM_BOOKPRO_PASSENGER_PRICE')?>
					</th>
					 -->
					<th><?php echo JText::_('COM_BOOKPRO_DEPART_DATE')?>
					</th>
					<td>
						<?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER')?>
					</td>
					<?php if ($this->config->get('non_seat')){?>
					<td>
						<?php echo JText::_('COM_BOOKPRO_SEAT')?>
					</td>
					<?php } ?>
					<td>
						<?php echo JText::_('COM_BOOKPRO_BOARDING')?>
					</td>
					<td>
						<?php echo JText::_('COM_BOOKPRO_DROPPING')?>
					</td>
					
					
				</tr>
			</thead>
		
			<tbody>
				<?php if (! is_array($this->items)) { ?>
					<tr><td colspan="10" class="emptyListInfo"><?php echo JText::_('No items found.'); ?></td></tr>
				<?php 
				
					} else {
												
						 for ($i = 0; $i < $itemsCount; $i++) { 
				    	 	$subject = &$this->items[$i]; 
				    	 	$checked = JHTML::_('grid.id', $i, $subject->id);
				    	 	$edit = 'index.php?option=com_bookpro&view=passenger&task=passenger.edit&id='.$subject->id;
					?>
				    	<tr>
				    		<td  style="text-align: left; white-space: nowrap;"><?php echo number_format($this->pagination->getRowOffset($i), 0, '', ' '); ?></td>
				    		<td><?php echo $checked;  ?></td>
				    		
				    		<td>
				    			<a href="<?php echo $edit; ?>">
				    			<?php echo $subject->firstname; ?>
				    			</a>
							</td>
							<?php if($params->get('ps_lastname')){?>
							<td>
					    		<?php  echo $subject->lastname;?>
				    		</td>
				    		<?php } ?>
				    			<?php if($params->get('ps_gender')){?>
				    		<td><?php echo BookProHelper::formatGender($subject->gender) ?></td>
							<?php }?>
							<?php if($params->get('ps_phone')){?>
				    		<td>
					    		<?php 	echo $subject->phone ?>
					    		
				    		</td>
				    		<?php } ?>
				    		
				    		<?php if($params->get('ps_email')){?>
				    		<td>
					    		<?php 	echo $subject->email ?>
					    		
				    		</td>
				    		<?php } ?>
				    		
				    		<?php if($params->get('ps_passport')){?>
				    		<td>
					    		<?php 
								echo $subject->passport; ?>
					    		
				    		</td>
				    		<?php } ?>
				    		
				    		<td>
				    			<?php echo $subject->group_title;?>
				    		</td>
				    		
				    
				    		<td>
				    			<?php echo DateHelper::toShortDate($subject->start_date); ?>
				    		</td>
				    		<td>
								<a href="<?php echo JUri::base()?>index.php?option=com_bookpro&view=order&id=<?php echo $subject->order_id ?>">
									<?php echo $subject->order_number; ?>
								</a>
							</td>
							<?php if ($this->config->get('non_seat')){?>
							<td>
				    			<?php echo $subject->aseat ?>
				    		</td>
				    		<?php } ?>
							
							<td>
								<?php 
									if (isset($subject->boarding)){
										
										echo JText::sprintf('COM_BOOKPRO_BOARDING_TXT',$subject->boarding['location'],$subject->boarding['depart']);
										
										if(isset($subject->boarding['private_boarding'])){
											echo "<br/>";
											echo $subject->boarding['private_boarding'];
										}
										
									}
								?>
							</td>
							
							<td>
								<?php 
									if (isset($subject->dropping)){
										
										echo JText::sprintf('COM_BOOKPRO_DROPPING_TXT',$subject->dropping['location'],$subject->dropping['depart']);
										
										if(isset($subject->dropping['private_dropping'])){
											echo "<br/>";
											echo $subject->dropping['private_dropping'];
										}
									}
								?>
							</td>
							
							
				    		
				    	</tr>
				    <?php 
				    	}
					} 
					?>
			</tbody>
		</table>
		</div>
	<?php echo $this->pagination->getLimitBox(); ?>
	<?php echo $this->pagination->getListFooter(); ?>	
	<input type="hidden" name="option" value="com_bookpro"/>
	<input type="hidden" name="task"	value="" />
	<input type="hidden" name="boxchecked" value="0"> 
	<?php echo JHTML::_('form.token'); ?>
</form>	
<div>

</div>


