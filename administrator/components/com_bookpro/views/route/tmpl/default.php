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
JHtml::_('behavior.formvalidation');
JToolBarHelper::title('Route map');
JToolBarHelper::cancel();
$config=JBFactory::getConfig();
foreach ($this->destinations as $value) {
	if($value->latitude && $value->longitude )
		$geo[]= array($value->latitude,$value->longitude);
}
//$strgeo='["'.implode('|', $geo).'"]';

?>



   
  


<center>
<form action="index.php" method="post" name="adminForm" id="adminForm"
	class="form-validate">
	
	<div class="container-fluid">
	<div class="span4">
	<div class="well">
	<?php 
	echo JText::_('COM_BOOKPRO_NAME_CODE').$this->obj->code;
	
	foreach ($this->destinations as $value) {
	 ?>
		<div style="border:1px solid gray;border-radius:10px;padding: 10px;">
		<a href="<?php echo JUri::base().'index.php?option=com_bookpro&view=bustrip&layout=edit&id='.$value->id ?>">
		<?php echo $value->title ?> </a> <a class="btn btn-small" href="index.php?option=com_bookpro&controller=bustrip&task=remove&code=<?php echo $this->obj->code ?>&dest_id=<?php echo $value->id ?>">Delete node</a>
		</div>
		<?php 
	}	

	?>
	</div>
	
	<div id="directions-panel"></div>
	
	</div>	
	<div class="span8">
	
	 <?php echo JLayoutHelper::render('routemap',$geo,JPATH_COMPONENT_SITE.'/layouts'); ?>
	
	</div>

	
	</div>
	<input type="hidden" name="option" value="com_bookpro" /> <input
		type="hidden" name="controller"
		value="bustrip" /> <input type="hidden"
		name="task" value="save" /> <input type="hidden" name="boxchecked"
		value="1" /> <input type="hidden" name="cid[]"
		value="<?php echo $this->obj->id; ?>" id="cid" />

	<?php echo JHtml::_('form.token'); ?>
</form>
</center>
