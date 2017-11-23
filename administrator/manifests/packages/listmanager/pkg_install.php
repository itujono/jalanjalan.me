<?php
/*
 * @component List Manager 
 * @copyright Copyright (C) November 2017. 
 * @license GPL 3.0 
 * This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version. 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 * See the GNU General Public License for more details. 
 * See <http://www.gnu.org/licenses/>. 
 * More info www.moonsoft.es 
 * gestion@moonsoft.es
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
class pkg_listmanagerInstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) {
		//echo 'Install';
		//echo '<br /><br />Installed!';
	}
 
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) {
		// $parent is the class calling this method
		echo '<p>Thanks for using List Manager</p>
    <p><hr/>If you have any question, problem or comment, please send us an email: <a href="mailto:gestion@moonsoft.es">gestion@moonsoft.es</a> or visit our web page <a href="http://www.moonsoft.es" target="_blank">www.moonsoft.es</a><br/>    
    </p>';
	}
 
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) {	
		
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
				Jerror::raiseWarning(null, 'Cannot install this version of Calcbuilder in a Joomla release prior to '.$this->minimum_joomla_release);
				return false;
			} 
			else { $rel = $this->release; }
	
			// Show the essential information at the install/update back-end
			echo '<div style="float:left;width: 150px;margin-left: 20px;margin-top: 12px;">';  
	   		echo '<img src="'.JURI::base().'components/com_listmanager/assets/img/ListManager96x96.png" alt="List Manager"/>';
	   		echo '</div>';
			echo '<div style="float:left;width: 310px;margin-bottom: 5px;">';
			echo '<h2>List Manager</h2>';
			echo '<h5>Component, Module and Plugin Install/Update info</h5>';
			echo 'Installing component version :<b> ' . $this->release.'</b>';
			echo '<br />Current component version :<b> '.$this->getParam('version').'</b>';
			echo '<br />Installing minimum Joomla version :<b> '. $this->minimum_joomla_release.'</b>';
			echo '<br />Current Joomla version :<b>  ' . $jversion->getShortVersion().'</b>';
			echo '</div>';
			echo '<div style="float:left;margin-top: 24px;">';
			echo '<img src="'.JURI::base().'components/com_listmanager/assets/img/logo.png" alt="Moonsoft"/><br />';
			echo '<a href="http://www.moonsoft.es">Moonsoft Software Solutions</a>';
			echo '</div>';
		}
 		
	}
 
	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) {
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		//echo '<p>' . JText::_('COM_HELLOWORLD_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
	}
	
	/*
	 * get a variable from the manifest file (actually, from the manifest cache).
	 */
	function getParam( $name ) {
		$db = JFactory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE element = "com_listmanager" and type="component"');
		$manifest = json_decode( $db->loadResult(), true );
		return $manifest[ $name ];
	}
	
}

?>