<?php
 /**
 * @package Form Maker
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('_JEXEC') or die('Restricted access');

class com_contactformmakerInstallerScript
{	
	function postflight($type, $parent)
	{
		if ($type == 'update')
		{
			$db = JFactory::getDBO();
			$sqlfile = JPATH_ADMINISTRATOR.'/components/com_contactformmaker/update.mysql.sql';
		
			$buffer = file_get_contents($sqlfile);
			if ($buffer === false)
			{
				JError::raiseWarning(1, JText::_('JLIB_INSTALLER_ERROR_SQL_READBUFFER'));
				return false;
			}
			
			jimport('joomla.installer.helper');
			$queries = JInstallerHelper::splitSql($buffer);
			if (count($queries) == 0) {
				// No queries to process
				return 0;
			}
			
			
			// Process each query in the $queries array (split out of sql file).
			foreach ($queries as $query)
			{
				$query = trim($query);
				if ($query != '' && $query{0} != '#')
				{
					$db->setQuery($query);
					if (!$db->query())
					{
						JError::raiseWarning(1, JText::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $db->stderr(true)));
						return false;
					}
				}
			}
			
			$query="SELECT public_key, private_key FROM #__contactformmaker";
			$db->setQuery($query);
			$recaptchaKeys=$db->loadObject();
			
			if(isset($recaptchaKeys->public_key) && $recaptchaKeys->public_key){
				
				$query="INSERT INTO `#__contactformmaker_options` ( `public_key`, `private_key`) VALUES( '".$recaptchaKeys->public_key."', '".$recaptchaKeys->private_key."');";
				$db->setQuery($query);$db->query();
				
				$query="UPDATE #__contactformmaker SET `public_key`='', `public_key`=''";
				$db->setQuery($query);
			}
			
			$db->setQuery("SHOW COLUMNS FROM #__contactformmaker LIKE 'mail_cc'");
			if (!$db->query())
			{
				JError::raiseWarning(1, JText::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $db->stderr(true)));
				return false;
			}
		
			if($db->getNumRows()!=1) 
			{
				$db->setQuery("ALTER TABLE #__contactformmaker ADD `mail_cc` varchar(128) NOT NULL "); $db->query(); 
				$db->setQuery("ALTER TABLE #__contactformmaker ADD `mail_cc_user` varchar(128) NOT NULL "); $db->query(); 
				$db->setQuery("ALTER TABLE #__contactformmaker ADD `mail_bcc` varchar(128) NOT NULL "); $db->query(); 
				$db->setQuery("ALTER TABLE #__contactformmaker ADD `mail_bcc_user` varchar(128) NOT NULL "); $db->query(); 
				$db->setQuery("ALTER TABLE #__contactformmaker ADD `mail_subject` varchar(128) NOT NULL "); $db->query(); 
				$db->setQuery("ALTER TABLE #__contactformmaker ADD `mail_subject_user` varchar(128) NOT NULL "); $db->query(); 
				$db->setQuery("ALTER TABLE #__contactformmaker ADD `mail_mode` tinyint(4) NOT NULL DEFAULT '1'"); $db->query(); 
				$db->setQuery("ALTER TABLE #__contactformmaker ADD `mail_mode_user` tinyint(4) NOT NULL DEFAULT '1'"); $db->query(); 
								
				$query = 'UPDATE #__contactformmaker SET `mail_mode` = 1, `mail_mode_user` = 1';
				$db->setQuery( $query );
				if ( !$db->query() ) {
				$msg =$db->getErrorMsg();
				echo $msg;
				return false;
				}
			}
				
		}
		
	}
}