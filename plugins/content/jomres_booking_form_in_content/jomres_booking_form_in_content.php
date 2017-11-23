<?php
/**
* Jomres CMS Agnostic Plugin
* @author Woollyinwales IT <sales@jomres.net>
* @version Jomres 9 
* @package Jomres
* @copyright	2005-2015 Woollyinwales IT
* Jomres (tm) PHP files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project.
**/

defined('_JEXEC') or die;

class plgContentJomres_booking_form_in_content extends JPlugin
	{
	public function onContentPrepare($context, &$row, &$params, $page = 0)
		{
		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer')
			return true;

		if (isset($row->text) )
			{
			if ( strpos( $row->text, 'bot_jomres_bookingform' ) === false )
				return true;
			}
		else
			return true;
		
		$regex = '/{bot_jomres_bookingform\s*.*?}/i';
		preg_match_all( $regex, $row->text, $matches );
		$count = count( $matches[0] );
		// mambot only processes if there are any instances of the mambot in the text
		if ( $count == 1)
			{
			if (!defined('_JOMRES_INITCHECK'))
				define('_JOMRES_INITCHECK', 1 );
	
			require_once (dirname(__FILE__).'/../../../jomres_root.php');
			require_once(JOMRES_ROOT_DIRECTORY.DIRECTORY_SEPARATOR.'core-plugins'.DIRECTORY_SEPARATOR.'alternative_init'.DIRECTORY_SEPARATOR.'alt_init.php');
			
			$tmptxt= str_replace("{bot_jomres_bookingform ", "", $matches[0][0]);
			$property_uid= intval(str_replace("}", "", $tmptxt) );
			set_showtime('property_uid',$property_uid);
			$mrConfig=getPropertySpecificSettings($property_uid);
			
			set_showtime('include_room_booking_functionality',true);
			$MiniComponents->triggerEvent('00005');
			
			if (!defined("DOBOOKING_IN_DETAILS"))
				define("DOBOOKING_IN_DETAILS",1);
			
			require_once(JOMRESCONFIG_ABSOLUTE_PATH.JRDS.'jomres'.JRDS.'libraries'.JRDS.'jomres'.JRDS.'functions'.JRDS.'dobooking.php');
			
			$row->text = str_replace($matches[0][0],BOOKING_FORM_FOR_PROPERTY_DETAILS, $row->text);
			}
		else
			echo "Error, you can only call bot_jomres_bookingform once in the content area";
		return true;
		}
	}
