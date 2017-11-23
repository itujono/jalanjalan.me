<?php

 /**
 * @package Contact Form Maker Module
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
defined('_JEXEC') or die('Restricted access');
defined( 'DS' )  or define('DS', DIRECTORY_SEPARATOR);

jimport( 'joomla.filesystem.folder' );

require_once (dirname(__FILE__).DS.'helper.php');
if(JFolder::exists('components/com_formmaker'))
	require_once('components/com_formmaker/recaptchalib.php');
else
	require_once JPATH_SITE.DS.'components'.DS.'com_contactformmaker'.DS.'recaptchalib.php';

$id=$params->get('id');
$modules	= modContactFormmaker::load( $id );
echo $modules;
 
// removes tags without matching module positions

?>