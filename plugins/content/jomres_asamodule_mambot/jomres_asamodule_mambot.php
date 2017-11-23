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

class plgContentJomres_asamodule_mambot extends JPlugin
{
	/**
	 * @param	string	The context of the content being passed to the plugin.
	 * @param	mixed	An object with a "text" property.
	 * @param	array	Additional parameters. 
	 * @param	int		Optional page number. Unused. Defaults to zero.
	 * @return	boolean	True on success.
	 */
	public function onContentPrepare($context, &$row, &$params, $page = 0)
		{
		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer') 
			{
			return true;
			}
		
		if (is_object($row)) 
			{
			return $this->_replace($row->text, $params);
			}
		return $this->_replace($row, $params);
		}

	/*
	Prevents modules from showing, so disabled for now
	public function onRenderModule(&$module, $attribs)
		{
		return $this->_replace($module->content);
		} */
		
	protected function _replace(&$text)
		{
		if (isset($text) )
			{
			if ( strpos( $text, 'jomres' ) === false )
				{
				return true;
				}
			}
		else
			{
			return true;
			}
	
		// {jomres remoteavailability "&id=1"}
		// expression to search for
		$regex = '/{jomres\s*.*?}/i';

		// find all instances of mambot and put in $matches
		preg_match_all( $regex, $text, $matches );

		if (count($matches)>0)
			{
			foreach ($matches[0] as $m)
				{
				ob_start();
				
				$match = str_replace(array("{","}"),"",$m);
				$match = str_replace("&amp;","&",$match);
				$bang = explode (" ",$match);
				$our_task = $bang[1];
				$arguments = '';
				if (isset($bang[2]))
					$arguments = $bang[2];

				if ($arguments!='')
					{
					$args_array = explode("&",$arguments);
					foreach ($args_array as $arg)
						{
						$vals = explode ("=",$arg);
						if(array_key_exists(1,$vals))
							{
							$vals[1] = str_replace("+","-",$vals[1]);
							$_REQUEST[$vals[0]]=$vals[1];
							$_GET[$vals[0]]=$vals[1];
							}
						}
					}
				
				if (!defined('_JOMRES_INITCHECK'))
					define('_JOMRES_INITCHECK', 1 );
				
				if (!defined('JOMRES_ROOT_DIRECTORY'))
					{
					if (file_exists(JPATH_ROOT.'jomres_root.php'))
						require_once (JPATH_ROOT.'jomres_root.php');
					else
						define ( 'JOMRES_ROOT_DIRECTORY' , "jomres" ) ;
					}

				if (file_exists(JPATH_ROOT. DIRECTORY_SEPARATOR .JOMRES_ROOT_DIRECTORY. DIRECTORY_SEPARATOR .'core-plugins'. DIRECTORY_SEPARATOR .'alternative_init'. DIRECTORY_SEPARATOR .'alt_init.php'))
					{
					require_once(JPATH_ROOT. DIRECTORY_SEPARATOR .JOMRES_ROOT_DIRECTORY. DIRECTORY_SEPARATOR .'core-plugins'. DIRECTORY_SEPARATOR .'alternative_init'. DIRECTORY_SEPARATOR .'alt_init.php');
					}
				else
					echo "Error: Alternative Init plugin is not installed.";

				$MiniComponents = jomres_getSingleton('mcHandler');
				set_showtime('task',$our_task);
				
				//$MiniComponents->specificEvent('06000',$our_task);
				
				$thisJRUser = jomres_singleton_abstract::getInstance('jr_user');
				
				if ($MiniComponents->eventSpecificlyExistsCheck('06000', get_showtime('task'))) {
					$MiniComponents->specificEvent('06000', get_showtime('task'));
				} elseif ($MiniComponents->eventSpecificlyExistsCheck('06001', get_showtime('task')) && $thisJRUser->userIsManager) { // Receptionist and manager tasks
					if (get_showtime('task') == 'cpanel') {
						$MiniComponents->specificEvent('06001', get_showtime('task'));
					} else {
						$MiniComponents->specificEvent('06001', get_showtime('task'));
					}
				} elseif ($MiniComponents->eventSpecificlyExistsCheck('06002', get_showtime('task')) && $thisJRUser->userIsManager && $thisJRUser->accesslevel >= 70) { // Manager only tasks (higher than receptionist)
					$MiniComponents->specificEvent('06002', get_showtime('task'));
				} elseif ($MiniComponents->eventSpecificlyExistsCheck('06005', get_showtime('task')) && $thisJRUser->userIsRegistered) { // Registered only user tasks
					$MiniComponents->specificEvent('06005', get_showtime('task'));
				} else {
					return;
				}
	
				$contents = ob_get_contents();
				
				$text = str_replace($m,$contents, $text);
				
				unset($contents);
				ob_end_clean();
				}
			}
		
		return true;
		}
	}
