<?php // no direct access

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bookpro.php 27 2012-07-08 17:15:11Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

foreach ($this->destinations as $value) {
	if($value->latitude && $value->longitude )
		$geo[]= array($value->latitude,$value->longitude);
}


?>

	
	<style>

.white-popup {
  position: relative;
  background: #FFF;
  padding: 20px;
  width: auto;
  max-width: 500px;
  margin: 20px auto;
}

</style>
<div class="white-popup">
	
 <?php echo JLayoutHelper::render('routemap',$geo,JPATH_COMPONENT_SITE.'/layouts'); ?>
	



 </div>