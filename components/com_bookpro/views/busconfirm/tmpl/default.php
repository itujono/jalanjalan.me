<?php
/**
 * @package     Bookpro
 * @author         Ngo Van Quan
 * @link         http://joombooking.com
 * @copyright     Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version     $Id: default.php  23-06-2012 23:33:14
 **/
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );
AImporter::helper ( 'date', 'form', 'currency', 'bookpro' );
AImporter::css ( 'customer', 'jbbus' );

JHtml::_ ( 'jquery.framework' );

$local=BookProHelper::getLocal();

$document = JFactory::getDocument ();
$document->addScript ( JURI::root () . 'components/com_bookpro/assets/js/bootstrap-datepicker.js' );
$document->addScript ( JURI::root () . 'components/com_bookpro/assets/js/locales/bootstrap-datepicker.' . $local . '.js' );
$document->addStyleSheet ( JURI::root () . 'components/com_bookpro/assets/css/datepicker.css' );
$document->addScript("//cdn.jsdelivr.net/jquery.validation/1.14.0/jquery.validate.js");

$js_format = DateHelper::getConvertDateFormat ( 'B' );
$date_format = DateHelper::getConvertDateFormat ( 'P' );
$bs=$this->config->get('bs')

?>


<script lang="text/javascript">

	
	jQuery(document).ready(function($) {


		if($('.birthday').length>0)
		$('.birthday').datepicker({
			format: "<?php echo $js_format ?>",
		    autoclose: true,
		    endDate: "-3m"
		});

		if($('.expired').length>0)
			$('.expired').datepicker({
				format: "<?php echo $js_format ?>",
			    autoclose: true
		});

		
		$("#frontBusForm").validate();
   
  });
  </script>


<form name="frontBusForm" action="<?php echo JRoute::_('index.php')?>"
	method="post" id="frontBusForm">
	<div class="well well-small">
		<div class="<?php if($bs=="bs3") echo "row"; else echo "row-fluid" ?>">
			<div class="<?php if($bs=="bs3") echo "col-md-4"; else echo "span4"?>">
				<!-- Summary -->
			<?php echo $this->loadTemplate('sumary')?>
		</div>
			<div class="<?php if($bs=="bs3") echo "col-md-8"; else echo "span8"?>">
				<?php if(count($this->addons)>0){ ?>			
				<div class="addon well" style="background-color: white;">
				<?php echo $this->loadTemplate('addon')?>
				
				</div>
				<?php } ?>
				<div class="passenger well" style="background-color: white;">
			<?php
				
				
				$layout = new JLayoutFile ( 'passenger_form_'.$bs, $basePath = JPATH_ROOT . '/components/com_bookpro/layouts' );
				$html = $layout->render ( $this->passengers );
				echo $html;
			?>
		</div>
		
		
		
		<?php
		
		
		$user = JFactory::getUser ();
		if ($user->guest) {
			if($this->config->get('is_lead')){
					
				}
				else{
					echo BookProHelper::renderLayout ( 'customer', $user );
				}
		} else {
			$account = JBFactory::getAccount ();
			if($account->id){

				if ($account->isAgent) {

				}

			}else{
				
				$user->firstname=$user->name;
				if($this->config->get('is_lead')){
				}
				
				else{
					echo BookProHelper::renderLayout ( 'customer', $user );
				}
			}
			
		}
		
		?>
		
		<div style="text-align: center;">
					<button type="submit" class="btn btn-primary"><?php echo JText::_('COM_BOOKPRO_CONTINUE')?></button>
				</div>
			</div>
		</div>

	</div>
	<?php
	$hidden = array (
			'controller' => 'bus',
			'task' => 'confirm',
			"Itemid" => JRequest::getVar ( 'Itemid' ) 
	);
	echo FormHelper::bookproHiddenField ( $hidden );
	
	?>
</form>

