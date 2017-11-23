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
JHtmlBehavior::formvalidation();
AImporter::helper('date');
?>

<script type="text/javascript">
jQuery(document).ready(function($){
	
	$("#newaccount").hide();
	
	$('[name="jform[createacc]"]').change(function(){

		var val=$('[name="jform[createacc]"]:checked').val();
		
		if(val==0){
			$("#existing").hide();
			$("#newaccount").show();
		}
		if(val==1) {
			$("#newaccount").hide();
			$("#existing").show();
		}
	});
	
	
});
</script>
<form action="<?php echo JRoute::_ ( 'index.php?option=com_bookpro&layout=edit&id=' . ( int ) $this->item->id );?>" method="post" name="adminForm" id="adminForm" class="form-validate">	

<div class="span9">


<?php echo JHtml::_('bootstrap.startTabSet', 'myTab',array('active'=>'tab1'));?> 

<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tab1', JText::_('Details')); ?> 	
    <div class="form-horizontal">
    	<?php if ($this->item->id) { ?>
    	<?php if ($this->item->user) { ?>
        <div class="control-group">
			<label class="control-label" for="id"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_USER'); ?></label>
			<div class="controls">
				<a href="index.php?option=com_users&task=user.edit&id=<?php echo $this->item->user; ?>" title=""><?php echo $this->item->firstname; ?></a>
			</div>
		</div>			
       	<?php } else { ?>
		
		<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('user'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('user'); ?></div>
		</div>
        <?php } ?>
       	
       	<?php   ?>
       	
       	
       	<?php } else { ?>
       	
       		<?php echo $this->form->renderField('createacc')?>
       		
       		<div id="existing" class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('user'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('user'); ?></div>
			</div>
			
			<div id="newaccount">
				<div id="existing" class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('email'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('email'); ?></div>
				</div>
				
				<div id="existing" class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('password'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('password'); ?></div>
				</div>
			</div>
       		
       		<?php 
		
		 } ?>
		
		<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('firstname'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('firstname'); ?></div>
			</div>	
        
        <div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('lastname'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('lastname'); ?></div>
			</div>
			
			<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('mobile'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('mobile'); ?></div>
			</div>
        
       
		
		
	</div>
    	<?php echo JHtml::_('bootstrap.endTab');?> 	
      		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tab3', JText::_('Contact')); ?>   
    		<div class="form-horizontal">
    		
    		 <div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('birthday'); ?></div>
					<div class="controls"><?php 
					
					$this->form->setFieldAttribute('birthday', 'format',DateHelper::getConvertDateFormat('M'), $group = null);
					echo $this->form->getInput('birthday'); ?></div>
		</div>
		
		
		<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('gender'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('gender'); ?></div>
			</div>
			
			<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('address'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('address'); ?></div>
			</div>
			
			<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('city'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('city'); ?></div>
			</div>
			
			<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('telephone'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('telephone'); ?></div>
			</div>
			
						
				
			<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('zip'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('zip'); ?></div>
			</div>
			
			<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('country_id'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('country_id'); ?></div>
			</div>
	</div>
    	<?php echo JHtml::_('bootstrap.endTab');?>     	
    	<?php echo JHtml::_('bootstrap.endTabSet');?>   
    	</div>
    	<div class="span3">
    		<?php echo $this->form->renderField('state')?>
    		<?php echo $this->form->renderFieldSet('jparams')?>
    	</div>
    		
		<input type="hidden" name="task" value="" /> 
		<?php echo JHTML::_('form.token'); ?>
</form>

