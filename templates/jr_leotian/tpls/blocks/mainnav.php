<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// get params
$sitename  = $this->params->get('sitename');
$slogan    = $this->params->get('slogan', '');
$logotype  = $this->params->get('logotype', 'text');
$logoimage = $logotype == 'image' ? $this->params->get('logoimage', T3Path::getUrl('images/logo.png', '', true)) : '';
$logoimgsm = ($logotype == 'image' && $this->params->get('enable_logoimage_sm', 0)) ? $this->params->get('logoimage_sm', T3Path::getUrl('images/logo-sm.png', '', true)) : false;

if (!$sitename) {
	$sitename = JFactory::getConfig()->get('sitename');
}
?>

<!-- MAIN NAVIGATION -->
<nav id="t3-mainnav" class="navbar navbar-default t3-mainnav">

	<div class="navbar-header">
	
		<?php if ($this->getParam('navigation_collapse_enable', 1) && $this->getParam('responsive', 1)) : ?>
			<?php $this->addScript(T3_URL.'/js/nav-collapse.js'); ?>
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".t3-navbar-collapse">
				<i class="fa fa-bars"></i>
			</button>
		<?php endif ?>
		
		<a href="<?php echo JURI::base() ?>" title="<?php echo strip_tags($sitename) ?>" class="navbar-brand">
			<?php if($logotype == 'image'): ?>
				<img class="logo-img" src="<?php echo JURI::base(true) . '/' . $logoimage ?>" alt="<?php echo strip_tags($sitename) ?>" />
			<?php endif ?>
			<?php if($logoimgsm) : ?>
				<img class="logo-img-sm" src="<?php echo JURI::base(true) . '/' . $logoimgsm ?>" alt="<?php echo strip_tags($sitename) ?>" />
			<?php endif ?>
		</a>
		
		<?php if ($this->getParam('addon_offcanvas_enable')) : ?>
			<?php $this->loadBlock ('off-canvas') ?>
		<?php endif ?>

	</div>

	<?php if ($this->getParam('navigation_collapse_enable')) : ?>
		<div class="t3-navbar-collapse navbar-collapse collapse"></div>
	<?php endif ?>

	<div class="t3-navbar navbar-collapse collapse">
		<jdoc:include type="<?php echo $this->getParam('navigation_type', 'megamenu') ?>" name="<?php echo $this->getParam('mm_type', 'mainmenu') ?>" />
	</div>

</nav>
<!-- //MAIN NAVIGATION -->
