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



JHtml::_('jquery.framework');
$document = JFactory::getDocument();
$document->addScript(JUri::base().'components/com_bookpro/assets/js/bootstrap-timepicker.min.js');
$document->addStyleSheet(JUri::base().'components/com_bookpro/assets/css/bootstrap-timepicker.min.css');
JToolBarHelper::title(JText::_('Genarate route tool'), 'stack');
JToolBarHelper::cancel('generate.cancel');
JToolBarHelper::save('generate.save');

AImporter::js('jquery.validate.min');





?>
<script type="text/javascript">
 Joomla.submitbutton = function(task) {
	
      var form = document.adminForm;

      var check=true;
      if (task == 'generate.cancel') {
         form.task.value = task;
         form.submit();
      }
      if (task == 'generate.save') {
          
    	  //jQuery("#adminForm").validate();
    	  
          jQuery('input.priceinput').each(function(){
			if(!jQuery(this).val()){
					
					checked = false; 
					
				}
			});
				
         
          jQuery( ".destination" ).each(function() {
        	 if( jQuery( this ).val()==0){
        		 check=false;
        	 }
        	 
         	});
         
          if(check == false)
          {
        	  alert("<?php echo JText::_( 'Please fill up correct data', true ); ?>");
              
              return false;
          }
          if(check==true){
          
	          form.task.value = task;
	          form.submit();
	          return true;
          }
       }
      
     
   };

</script>
<form action="<?php echo JRoute::_('index.php?option=com_bookpro&view=generate'); ?>" method="post" name="adminForm" id="adminForm">
<div class="generate">
	<div class="row-fluid">
		<div class="span7">
			<?php echo $this->loadTemplate('destination')		?>
			
			
		</div>
		<div class="span5">
		
			<?php echo $this->loadTemplate('price')		?>
		<?php
		
	
			if (!empty($this->bustrips)){
				$layout = new JLayoutFile ( 'generateroute', $basePath = JPATH_ROOT . '/components/com_bookpro/layouts' );
					
				$html = $layout->render ( $this->bustrips );
				echo $html;
			}
			?>
			<?php 
			if (!empty($this->bustrips)){
			?>
			<div class="row-fluid">
				<div class="span12" align="center">
					<button onclick="Joomla.submitbutton('generate.save')" class="btn btn-medium btn-primary">
						<span class="icon-apply icon-white"></span>
						<?php echo JText::_('COM_BOOKPRO_SAVE'); ?>
					</button>
				</div>
			</div>
			<?php } ?>
			
		</div>
		
	</div>
</div>
	 	<input type="hidden" name="task" id="task" value="" /> 
        <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return'); ?>" />
	<?php echo JHtml::_('form.token'); ?>

</form>

