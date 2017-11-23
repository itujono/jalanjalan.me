<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 81 2012-08-11 01:16:36Z quannv $
 **/

// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

JHtml::_ ( 'behavior.tooltip' );
JHtml::_ ( 'behavior.formvalidation' );
$config = JComponentHelper::getParams ( 'com_bookpro' );
AImporter::helper ( 'date' );
// Set toolbar items for the page
$input = JFactory::getApplication ()->input;
$edit = $input->get ( 'edit', true );
JToolBarHelper::title ( JText::_ ( 'COM_BOOKPRO_PASSENGER' ),'user' );
JToolBarHelper::apply ( 'passenger.apply' );
JToolBarHelper::save ( 'passenger.save' );
if (! $edit) {
	JToolBarHelper::cancel ( 'passenger.cancel' );
} else {
	// for existing items the button is renamed `close`
	JToolBarHelper::cancel ( 'passenger.cancel' );
}

?>

<script type="text/javascript">


Joomla.submitbutton = function(task)
{
	if (task == 'passenger.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
		Joomla.submitform(task, document.getElementById('adminForm'));
	}
}

</script>

<form method="post"
	action="<?php echo JRoute::_('index.php?option=com_bookpro1&layout=edit&id='.(int) $this->item->id);  ?>"
	id="adminForm" name="adminForm">
	<div class="row-fluid">
		<div class="span7 form-horizontal">
			<fieldset>
		   <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_BOOKPRO_PASSENGER_NEW', true) : JText::sprintf('COM_BOOKPRO_PASSENGER_EDIT', $this->item->id, true)); ?>
					<?php echo $this->form->renderField('firstname'); ?>
					<?php echo $this->form->renderField('lastname'); ?>
					<?php echo $this->form->renderField('phone'); ?>
					<?php echo $this->form->renderField('email'); ?>
					
					<?php if ($config->get('ps_gender')){?>
						<?php echo $this->form->renderField('gender');  ?>
					<?php }?>
					
						<?php echo $this->form->renderField('seat');  ?>
						<?php echo $this->form->renderField('return_seat');  ?>
					
					<?php if ($config->get('ps_passport')){?>
						<?php echo $this->form->renderField('passport'); ?>
					<?php }?>
					
					 <?php if ($config->get('ps_ppvalid')){?>
						<?php
							$this->form->setFieldAttribute ( 'ppvalid', 'format', DateHelper::getConvertDateFormat ( 'M' ), $group = null );
							echo $this->form->renderField ( 'ppvalid' );
							?>
					<?php }?>
					<?php if ($config->get('ps_birthday')){?>
						<?php
						
						$this->form->setFieldAttribute ( 'birthday', 'format', DateHelper::getConvertDateFormat ( 'M' ), $group = null );
						echo $this->form->renderField ( 'birthday' );
						?>
					<?php }?>
					
					 <?php if ($config->get('ps_country')){?>
						<?php echo $this->form->renderField('country_id'); ?>
					<?php }?>
					<?php if ($config->get('ps_group')){?>
						<?php echo $this->form->renderField('group_id'); ?>
					<?php }?>
					 <?php if ($config->get('ps_flightno')){?>
						<?php echo $this->form->renderField('fightno');  ?>
					<?php }?>
					
					<?php if ($config->get('ps_notes')){?>
						<?php echo $this->form->renderField('notes');  ?>
					<?php }?>
					    <?php echo JHtml::_('bootstrap.endTab'); ?>
                
						
          </fieldset>
		</div>

		<div class="span5">
			<?php 
					
			AImporter::model('order');
			$model=new BookProModelOrder();
			$data=$model->getComplexItem($this->item->order_id);
			echo BookProHelper::renderLayout('tripinfo', $data);
		
				?>
			
			
		</div>
	</div>

	<input type="hidden" name="option" value="com_bookpro" /> <input
		type="hidden" name="cid[]" value="<?php echo $this->item->id ?>" /> <input
		type="hidden" name="task" value="" /> <input type="hidden" name="view"
		value="passenger" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>