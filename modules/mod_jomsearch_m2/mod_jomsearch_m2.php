<?php
/**
* Jomres CMS Agnostic Plugin
* @author Woollyinwales IT <sales@jomres.net>
* @version Jomres 9 
* @package Jomres
* @copyright	2005-2015 Woollyinwales IT
* Jomres (tm) PHP files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project.
**/

// ################################################################
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
if (!defined('_JOMRES_INITCHECK'))
	define('_JOMRES_INITCHECK', 1 );
// ################################################################

if (!defined('_JOMRES_INITCHECK'))
	define('_JOMRES_INITCHECK', 1 );

if (!defined('JOMRES_ROOT_DIRECTORY'))
	{
	if (file_exists(dirname(__FILE__).'/../../jomres_root.php'))
		require_once (dirname(__FILE__).'/../../jomres_root.php');
	else
		define ( 'JOMRES_ROOT_DIRECTORY' , "jomres" ) ;
	}


require_once(JOMRES_ROOT_DIRECTORY.DIRECTORY_SEPARATOR.'core-plugins'.DIRECTORY_SEPARATOR.'alternative_init'.DIRECTORY_SEPARATOR.'alt_init.php');

$calledByModule="mod_jomsearch_m2";
$doSearch=false;
$includedInModule=true;

$MiniComponents =jomres_getSingleton('mcHandler');
$componentArgs=array('doSearch'=>$doSearch,'includedInModule'=>$includedInModule,'calledByModule'=>$calledByModule);
$MiniComponents->specificEvent('06000', "search" , $componentArgs); 

