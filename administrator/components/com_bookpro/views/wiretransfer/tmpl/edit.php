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
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

$config=JComponentHelper::getParams('com_bookpro');
$currency_symbol=$config->get('currency_symbol')
?>
<form action="<?php echo JRoute::_('index.php?option=com_bookpro&id=' . (int) $this->item->id); ?>" method="post" id="adminForm" name="adminForm" class="form-validate">
		<div class="form-horizontal">
	
	
		<?php if($this->item->wire_status=='wired') {
			
			
			$this->form->setFieldAttribute('wire_status', 'readonly', 'readonly', $group = null);
			$this->form->setFieldAttribute('payment_date', 'readonly', 'readonly', $group = null);
			$this->form->setFieldAttribute('wire_transfer_date', 'readonly', 'readonly', $group = null);
			$this->form->setFieldAttribute('wire_id', 'readonly', 'readonly', $group = null);
			$this->form->setFieldAttribute('grand_total', 'readonly', 'readonly', $group = null);
			
			
		}?>
		
        	<?php echo $this->form->renderField('company_id');  ?>
        <div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('grand_total'); ?>&nbsp;<span>( <?php echo $currency_symbol; ?>)</span></div>
			<div class="controls"><?php echo $this->form->getInput('grand_total'); ?></div>
		</div>
		
		
		
		
		<?php echo $this->form->renderField('total_agent'); ?>
		<?php echo $this->form->renderField('total_partner'); ?>
		<?php echo $this->form->renderField('wire_id');  ?>
	
		<?php echo $this->form->renderField('wire_status');  ?>
		
		
		<?php echo $this->form->renderField('payment_date');  ?>
		<?php echo $this->form->renderField('wire_transfer_date');  ?>
		
		<?php if($this->item->id) { ?>	
		
			<?php echo $this->form->renderField('create_date');  ?>
			<?php echo $this->form->renderField('lastupdate_date');  ?>
		
		<?php } ?>
       
        
                	
		
	</div>
	<input type="hidden" name="task" value="" />
	 <?php echo JHtml::_('form.token'); ?>
	
</form>