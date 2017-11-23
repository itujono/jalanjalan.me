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



$document=JFactory::getDocument();

$document->addScript(JURI::root().'components/com_bookpro/assets/js/bootstrap-datepicker.js');
$document->addScript(JURI::root().'components/com_bookpro/assets/js/locales/bootstrap-datepicker.'.$this->local.'.js');
$document->addStyleSheet(JURI::root().'components/com_bookpro/assets/css/datepicker.css');
$document->addScript(JURI::root().'modules/mod_jbbus_bussearch/assets/search.js');
$document->addScript(JURI::root().'components/com_bookpro/assets/js/typeahead.bundle.js');


?>

<?php if($this->account->credit==0){?>
<p class="text-error">

<?php echo JText::_('COM_BOOKPRO_CREDIT_EXHAUSTED')?>

</p>
<?php } ?>


<div class="<?php echo BookProHelper::bsrow()?>">
<div class="<?php echo BookProHelper::bscol(6)?>">
	<?php echo BookProHelper::renderLayout('menu_pos', null)?>
</div>
</div>

<div class="order">
	<?php echo $this->loadTemplate('body')?>
</div>
