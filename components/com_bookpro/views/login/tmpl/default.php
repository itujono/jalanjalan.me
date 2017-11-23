<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id$
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );
AImporter::css('common');
$doc = JFactory::getDocument();
$action=JURI::base().'index.php?option=com_bookpro&controller=customer&task=bplogin';
?>

<div class="row-fluid">
<div class="form-box">

<form name="myform" id="myform" method="post" action="<?php echo $action?>" >
		
			<div class="form-top">
				<div class="form-top-left">
			
				<h3><?php echo JText::_("COM_BOOKPRO_VIEW_LOGIN_HEADLINE") ?></h3>
				
				<p><?php echo JText::_("COM_BOOKPRO_VIEW_LOGIN_SUB_HEADLINE") ?></p>
				</div>
				<div class="form-top-right">
				<i class="fa fa-key"></i>
				
				</div>
			</div>
			
			
			<div class="form-bottom">
			
				
				<div class="controls">
					<input type="text" class="required span12" id="username" name="username"
						
						placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?>"
						required />
				</div>
				<div class="controls">
					<input type="password" class="required span12" id="password" name="password" value=""
						placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSWORD'); ?>"  required />
				</div>
			
				<div class="controls">
					<label class="checkbox"> <input type="checkbox"> <?php echo JText::_('COM_BOOKPRO_CUSTOMER_REMEMBER_ME') ?>
					</label>
				</div>

				
				<div class="controls">
					<label class="checkbox"> <a
						href="<?php echo JRoute::_(JURI::root().'index.php?option=com_users&view=reset&Itemid='.JRequest::getVar('Itemid')) ?>">
						<?php echo JText::_('COM_BOOKPRO_FORGOT_YOUR_PASSWORD'); ?> </a> </label>
					 
						
				</div>
				
				 <button type="submit" class="btn btn-primary"  type="submit"> <?php echo JText::_('COM_BOOKPRO_LOGIN'); ?> </button>
				
			
			</div>
			
			
			
			
			<div class="social-login">
	                        	
	        		<?php 
	        		jimport('joomla.plugin.plugin');
        			$dispatcher	= JDispatcher::getInstance();
        			JPluginHelper::importPlugin('joombooking');
        			$data=array('return'=>JFactory::getApplication()->input->get('return'));
        			
        			$results = $dispatcher->trigger('onJBSocialForm',array($data));
        			if($results)
        			echo $results[0];
        		?>                	
	                        	
	                        </div>
			


		<input type="hidden" name="return"
			value="<?php echo JRequest::getVar('return',0) ;?>" /> <input
			type="hidden" name="Itemid"
			value="<?php echo JRequest::getVar('Itemid') ;?>" />

			<?php echo JHtml::_('form.token'); ?>
</form>
</div>
</div>
