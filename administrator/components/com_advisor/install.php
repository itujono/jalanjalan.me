<?php
/*
 * @component %%COMPONENTNAME%% 
 * @copyright Copyright (C) August 2017. 
 * @license GPL 3.0
 * This program is free software: you can redistribute it and/or modify it under the terms of the 
 * GNU General Public License as published by the Free Software Foundation, either version 3 of the License, 
 * or (at your option) any later version.
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 * See the GNU General Public License for more details.
 * See <http://www.gnu.org/licenses/>.
 * More info www.moonsoft.es gestion@moonsoft.es 
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
class com_advisorInstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) {
		//echo 'Install';
	}
 
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) {
		// $parent is the class calling this method
		echo '<p>Thanks for using Advisor</p>
    <p><hr/>If you have any question, problem or comment, please send us an email: <a href="mailto:gestion@moonsoft.es">gestion@moonsoft.es</a> or visit our web page <a href="http://www.moonsoft.es" target="_blank">www.moonsoft.es</a><br/>    
    </p>';
	}
 
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) {
		// $parent is the class calling this method
		//echo '<h3>Updated</h3>';
	}
 
	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) {
		if ($type!='uninstall'){		
			$jversion = new JVersion();
	
			// Installing component manifest file version
			$this->release = $parent->get( "manifest" )->version;
			
			// Manifest file minimum Joomla version
			$this->minimum_joomla_release = $parent->get( "manifest" )->attributes()->version;
	
			// abort if the current Joomla release is older
			if( version_compare( $jversion->getShortVersion(), $this->minimum_joomla_release, 'lt' ) ) {
				Jerror::raiseWarning(null, 'Cannot install this version of Advisor in a Joomla release prior to '.$this->minimum_joomla_release);
				return false;
			} 
			else { $rel = $this->release; }
			
			// abort if the component being installed is not newer than the currently installed version
			if ( $type == 'update' ) {
				$this->insertSchema();
				$oldRelease = $this->getParam('version');
				$rel = $oldRelease . ' to ' . $this->release;
				if ( version_compare( $oldRelease, '0.1.0', 'lt' ) ) {
					Jerror::raiseWarning(null, 'Incorrect version sequence. Cannot upgrade ' . $rel);
					return false;
				}
				$sql_updates=array();
				if (version_compare($oldRelease,'0.2.0','lt')){
					$sql_updates[]='ALTER table `#__advisor_step` add column `precondition` varchar(1500) DEFAULT NULL';					
				}
				if (version_compare($oldRelease,'0.2.1','lt')){
					$sql_updates[]='ALTER table `#__advisor_solution` add column `idhikaproduct` int(11) DEFAULT 0';
	          		$sql_updates[]='ALTER table `#__advisor_solution` add column `idvirtueproduct` int(11) DEFAULT 0';					
				}
				
				if (version_compare($oldRelease,'0.2.2','lt')){
					$sql_updates[]='ALTER table `#__advisor_solution` add column `idjoomlaproduct` int(11) DEFAULT 0';
	          							
				}
				
				if (version_compare($oldRelease,'0.2.3','lt')){
					$sql_updates[]='CREATE TABLE   IF NOT EXISTS #__advisor_stats (
                          id int(10) unsigned NOT NULL auto_increment,
                          optionvalues varchar(500) default NULL,
                          flow int(10) unsigned NOT NULL,
                          date timestamp NOT NULL default CURRENT_TIMESTAMP,
                          PRIMARY KEY  (`id`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';
	          							
				}
				
				if (version_compare($oldRelease,'0.2.4','lt')){
					$sql_updates[]='ALTER TABLE `#__advisor_option` ADD COLUMN `order` INT NOT NULL DEFAULT 0';
	          							
				}
				
				$db = JFactory::getDbo();
				foreach ($sql_updates as $sql){
					$db->setQuery($sql);
					$db->query();
				}
			}
			else { $rel = $this->release; }
		}
		
	}
 
	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) {
		if ($type!='uninstall'){
			$jversion = new JVersion();
			if( version_compare( $jversion->getShortVersion(), '2.5.5', 'lt' ) ) {
				$component_name='com_advisor';
				rename(JPATH_ADMINISTRATOR.'/components/'.$component_name.'/controller_16.php', JPATH_ADMINISTRATOR.'/components/'.$component_name.'/controller.php');
				rename(JPATH_ADMINISTRATOR.'/components/'.$component_name.'/models/main.model_16.php', JPATH_ADMINISTRATOR.'/components/'.$component_name.'/models/main.model.php');
				rename(JPATH_ADMINISTRATOR.'/components/'.$component_name.'/views/main.view_16.php', JPATH_ADMINISTRATOR.'/components/'.$component_name.'/views/main.view.php');
				rename(JPATH_SITE.'/components/'.$component_name.'/controller_16.php', JPATH_SITE.'/components/'.$component_name.'/controller.php');
				rename(JPATH_SITE.'/components/'.$component_name.'/models/main.model_16.php', JPATH_SITE.'/components/'.$component_name.'/models/main.model.php');
				rename(JPATH_SITE.'/components/'.$component_name.'/views/main.view_16.php', JPATH_SITE.'/components/'.$component_name.'/views/main.view.php');
			}
		}
	}
	
	/*
	 * get a variable from the manifest file (actually, from the manifest cache).
	 */
	function getParam( $name ) {
		$db = JFactory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "advisor" and type="component"');
		$manifest = json_decode( $db->loadResult(), true );
		return $manifest[ $name ];
	}
 
	/*
	 * sets parameter values in the component's row of the extension table
	 */
	function setParams($param_array) {
		if ( count($param_array) > 0 ) {
			// read the existing component value(s)
			$db = JFactory::getDbo();
			$db->setQuery('SELECT params FROM #__extensions WHERE element = "com_advisor" and type="component"');
			$params = json_decode( $db->loadResult(), true );
			// add the new variable(s) to the existing one(s)
			foreach ( $param_array as $name => $value ) {
				$params[ (string) $name ] = (string) $value;
			}
			// store the combined new and existing values back as a JSON string
			$paramsString = json_encode( $params );
			$db->setQuery('UPDATE #__extensions SET params = ' .
				$db->quote( $paramsString ) .
				' WHERE element = "com_advisor" and type="component"' );
				$db->query();
		}
	}
	
	/*insert schema entry if not present*/	
	function insertSchema(){  
	  	$db = JFactory::getDbo();
		$db->setQuery('select count(*) from #__schemas where extension_id=(select extension_id from #__extensions WHERE element = "com_advisor"  and type="component")');
		$entrada=  $db->loadResult();		
		//extension_id
		if($entrada=="0"){
         	$db->setQuery('insert into #__schemas (extension_id,version_id) values ((select extension_id from #__extensions WHERE element = "com_advisor" and type="component"),"0.1.0") ');
				  $db->query();      
	    }
	}
}

?>