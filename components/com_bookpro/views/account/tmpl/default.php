<?php 
defined( '_JEXEC' ) or die( 'Restricted access' );

AImporter::helper('date','bookpro','currency');
  

?>

	<div class="row-fluid">
	<a href="index.php?option=com_bookpro&view=pos" class="btn btn-success"><?php echo JText::_('Back to Dashboard') ?></a>;
	
	</div>
        <?php 
			$layout = new JLayoutFile('account_menu', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
			$html = $layout->render($this->customer);
			echo $html;
		?>
		
		<?php echo $this->loadTemplate(JRequest::getVar('form','profile'))?>
