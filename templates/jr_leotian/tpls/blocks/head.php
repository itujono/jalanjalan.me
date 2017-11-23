<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<!-- META FOR IOS & HANDHELD -->
<?php if ($this->getParam('responsive', 1)): ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<style type="text/stylesheet">
		@-webkit-viewport   { width: device-width; }
		@-moz-viewport      { width: device-width; }
		@-ms-viewport       { width: device-width; }
		@-o-viewport        { width: device-width; }
		@viewport           { width: device-width; }
	</style>	
	<script type="text/javascript">
		//<![CDATA[
		if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
			var msViewportStyle = document.createElement("style");
			msViewportStyle.appendChild(
				document.createTextNode("@-ms-viewport{width:auto!important}")
			);
			document.getElementsByTagName("head")[0].appendChild(msViewportStyle);
		}
		//]]>
	</script>
<?php endif ?>
<meta name="HandheldFriendly" content="true"/>
<meta name="apple-mobile-web-app-capable" content="YES"/>
<!-- //META FOR IOS & HANDHELD -->

<!-- GOOGLE FONT -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600,700">
<!-- //GOOGLE FONT -->

<?php
// SYSTEM CSS
$this->addStyleSheet(JURI::base(true) . '/templates/system/css/system.css');
?>

<?php
// T3 BASE HEAD
$this->addHead();

//remove the frontend-edit.js because it interferes with yes/no options making them all use on/off labels.
$doc = JFactory::getDocument();
unset($doc->_scripts[JURI::base(true) . '/plugins/system/t3/base-bs3/js/frontend-edit.js']);
?>

<?php
// ANIMATIONS
if ($this->getParam('addon_animations_enable',1)) :
	if (is_file(T3_TEMPLATE_PATH . '/css/animate.css')) {
		$this->addStyleSheet(T3_TEMPLATE_URL . '/css/animate.css');
	} 
	?>
	<script type="text/javascript">
	var addon_animations_enable = true;
	</script>
<?php else : ?>
	<script type="text/javascript">
	var addon_animations_enable = false;
	</script>
	<style type="text/css">
	.appear {opacity:1 !important;}
	</style>
<?php endif;
?>

<?php
// CUSTOM CSS
if (is_file(T3_TEMPLATE_PATH . '/css/custom.css')) {
	$this->addStyleSheet(T3_TEMPLATE_URL . '/css/custom.css');
}
?>

<!-- Le HTML5 shim and media query for IE8 support -->
<!--[if lt IE 9]>
<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
<script type="text/javascript" src="<?php echo T3_URL ?>/js/respond.min.js"></script>
<![endif]-->

<?php
// Sticky JS
if (is_file(T3_TEMPLATE_PATH . '/js/jquery.sticky.js')) {
	$this->addScript(T3_TEMPLATE_URL . '/js/jquery.sticky.js');
}
?>

<?php
// Parallax JS
//if (is_file(T3_TEMPLATE_PATH . '/js/jquery.parallax-1.1.3.js')) {
	//$this->addScript(T3_TEMPLATE_URL . '/js/jquery.parallax-1.1.3.js');
//}
?>

<?php
// Nicescroll JS
//if (is_file(T3_TEMPLATE_PATH . '/js/jquery.nicescroll.min.js')) {
	//$this->addScript(T3_TEMPLATE_URL . '/js/jquery.nicescroll.min.js');
//}
?>

<?php
// Waypoints JS
if (is_file(T3_TEMPLATE_PATH . '/js/waypoints.min.js')) {
	$this->addScript(T3_TEMPLATE_URL . '/js/waypoints.min.js');
}
?>

<?php
// Waypoints infinite JS
//if (is_file(T3_TEMPLATE_PATH . '/js/waypoints-infinite.js')) {
	//$this->addScript(T3_TEMPLATE_URL . '/js/waypoints-infinite.js');
//}
?>

<?php
// Template JS
if (is_file(T3_TEMPLATE_PATH . '/js/custom.js')) {
	$this->addScript(T3_TEMPLATE_URL . '/js/custom.js');
}
?>
<!-- You can add Google Analytics here or use T3 Injection feature -->
