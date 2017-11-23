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
AImporter::helper('currency','bookpro','date','passenger');
JHtml::_('behavior.core');
JHtml::_('behavior.framework');
//BookProHelper::setSubmenu(1);


//$toolbar->appendButton( 'Link', 'print', JText::_('COM_BOOKPRO_PRINT'), JUri::base().'index.php?option=com_bookpro&view=pmibustrips&tmpl=component&layout=report' );
//$toolbar->appendButton( 'Link', 'download', JText::_('COM_BOOKPRO_PRINT_CSV'), JUri::base().'index.php?option=com_bookpro&view=pmibustrips&tmpl=component&layout=csv' );
//JToolBarHelper::custom('pmibustrips.printticket', 'tags', 'icon over', JText::_('COM_BOOKPRO_PRINT_TICKET'), true);

//JToolBarHelper::back();


//$pagination = &$this->pagination;
//$date = $this->state->get('filter.date');

$params=JComponentHelper::getParams('com_bookpro');


$input=JFactory::getApplication()->input;

//echo "<pre>"; print_r($_POST);die;

$this->items=PassengertHelper::getPassengers($this->account->id, $this->date, $this->route->id,array('order_number'=>$input->get('order_number')));



//echo "<pre>"; print_r($this->items);die;

$this->items=PassengertHelper::formatPassenger($this->items);

$itemsCount = count($this->items);

//echo "<pre>"; print_r($this->items);die;

?>

<script type="text/javascript">



 jQuery(document).ready(function($) {
	 
	 $("#print-btn").click(function(){
			
			$('#task').val('printticket');
			$('#tmpl').val('component');
			$('#adminForm').submit();
			
	});

	 $("#search-btn").click(function(){
			
			$('#task').val('search');
			$('#tmpl').val(null);
			$('#adminForm').submit();
			
	});

	 $("#checkin-btn").click(function(){
			
			$('#task').val('checkin');
			$('#tmpl').val('');
			$('#adminForm').submit();
			
	});
	
	
});

</script>

<div class="row-fluid">
	<?php echo BookProHelper::renderLayout('menu_driver', null)?>
</div>


<h2><?php echo $this->route->title ?></h2> <h4><?php echo $this->date ?></h4>

<form action="<?php echo JUri::root().'index.php?option=com_bookpro&view=driver&layout=passengers&Itemid='.$this->Itemid ?>" method="post" name="adminForm" id="adminForm">
	
	
	
	<div class="form-inline pull-left">
			<button type="button" id="checkin-btn" class="btn btn-success"><?php echo JText::_('COM_BOOKPRO_CHECKIN')?></button>
			<button type="button" id="print-btn" class="btn btn-success" ><?php echo JText::_('COM_BOOKPRO_PRINT')?></button>
	</div>
	
	
	<div class="form-inline pull-right">
	
			<input type="text" class="input" name="order_number" value="<?php $input->get('order_number')?>"/><button type="button" id="search-btn" class="btn btn-success"><?php echo JText::_('COM_BOOKPRO_SEARCH')?></button>
	</div>
	
		
		<table class="table table-striped" >
			<thead>
				
				
				<tr>
					<th width="3%">#</th>
					<th width="1%">
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
					
					
					<th>
						<?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER')?>
					</th>
					
					
					<th>
						<?php echo JText::_('COM_BOOKPRO_CHECKIN')?>
					</th>
					
					
					<?php if (!$this->config->get('non_seat')){?>
					<th>
						<?php echo JText::_('COM_BOOKPRO_SEAT')?>
					</th>
					<?php } ?>
					
					<th>
						<?php echo JText::_('COM_BOOKPRO_BOARDING')?>
					</th>
					<th>
						<?php echo JText::_('COM_BOOKPRO_DROPPING')?>
					</th>
					
					
					
				</tr>
			</thead>
		
			<tbody>
				<?php if (! is_array($this->items)) { ?>
					<tr><td colspan="10" class="emptyListInfo"><?php echo JText::_('No items found.'); ?></td></tr>
				<?php 
				
					} else {
												
						 for ($i = 0; $i < $itemsCount; $i++) { 
				    	 	$subject = &$this->items[$i]; 
				    	 	$checked = JHtmlGrid::id($i,$subject->id);
				    	 	$edit = 'index.php?option=com_bookpro&view=passenger&task=passenger.edit&id='.$subject->id;
					?>
				    	<tr>
				    		<td  style="text-align: left; white-space: nowrap;"></td>
				    		<td><?php echo $checked;  ?></td>
				    		
				    		<td>
				    			
				    			<?php echo $subject->firstname; ?>
				    			
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
								
								<?php echo $subject->order_number; ?>
								
							</td>
							
							
							<td>
								
								<?php 
								
								if($subject->state==1)
								
								echo '<i class="fa fa-check" aria-hidden="true" style="color:blue;"></i>';
								
								else 
									
									echo '<i class="fa fa-times" style="color:red;" aria-hidden="true"></i>';
									?>
								
								
							</td>
							
							<?php if (!$this->config->get('non_seat')){?>
							<td>
				    			<?php echo $subject->seat ?>
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
	
	<input type="hidden" name="filter_route_id" value="<?php echo $this->route->id ?>"/>
	<input type="hidden" name="controller" id="controller" value="pos"/>
	<input type="hidden" name="tmpl" id="tmpl" value=""/>
	<input type="hidden" name="option" id="option" value="com_bookpro"/>
	<input type="hidden" name="task" id="task"	value="" />
</form>	
<div>

</div>


