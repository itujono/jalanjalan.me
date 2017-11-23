<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
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
class com_listmanagerInstallerScript
{
    /**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) 
	{
		/*echo '<img src="'.JURI::base().'/components/com_listmanager/assets/img/ListManager48x48.png" border="0"><div>List Manager</div><p>
    Thanks for installing List Manager. You can get a quick guide at <a href="http://components.moonsoft.es">Moonsoft</a>
    </p>
    <p>
    <hr/>If you have any question, problem or comment, please join our support forums <a href="http://components.moonsoft.es/forums">here</a> or visit our web page <a href="http://components.moonsoft.es" target="_blank">components.moonsoft.es</a><br/>
    <a href="http://www.moonsoft.es" target="_blank"><img src="'.JURI::base().'/components/com_listmanager/assets/img/logo.png" border="0"></a>
    </p>';*/
	}
 
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) 
	{
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
	function update($parent) 
	{
		// $parent is the class calling this method
	}
 
	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) 
	{
		if ($type!='uninstall'){
			$jversion = new JVersion();
	
			// Installing component manifest file version
			$this->release = $parent->get( "manifest" )->version;
			
			// Manifest file minimum Joomla version
			$this->minimum_joomla_release = $parent->get( "manifest" )->attributes()->version;
	
			// abort if the current Joomla release is older
			if( version_compare( $jversion->getShortVersion(), $this->minimum_joomla_release, 'lt' ) ) {
				Jerror::raiseWarning(null, 'Cannot install this version of ListManager in a Joomla release prior to '.$this->minimum_joomla_release);
				return false;
			} 
			else { $rel = $this->release; }
			
			// abort if the component being installed is not newer than the currently installed version
			if ( $type == 'update' ) {
				$this->insertSchema();
				$oldRelease = $this->getParam('version');				
				$rel = $oldRelease . ' to ' . $this->release;
				if ( version_compare( $oldRelease, '1.1.1', 'lt' ) ) {
					Jerror::raiseWarning(null, 'Incorrect version sequence. Cannot upgrade ' . $rel);
					return false;
				}
				$sql_updates=array();
				$db = JFactory::getDbo();
				if (version_compare($oldRelease,'1.2.0','lt')){					
					$sql_updates[]='CREATE TABLE IF NOT EXISTS `#__listmanager_field_multivalue` (
  									`id` int(11) NOT NULL AUTO_INCREMENT,
  									`idfield` int(11) NOT NULL,
  									`idobj` int(11) NOT NULL,
  									`ord` int(11) NOT NULL,
  									`name` varchar(500) DEFAULT NULL,
  									`value` varchar(500) DEFAULT NULL,
  									PRIMARY KEY (`id`)
									) AUTO_INCREMENT=1 DEFAULT CHARSET=utf8';
					$query='select id as idfield, idlisting as idobj, multivalue from #__listmanager_field where multivalue is not null';
					$db->setQuery($query);
      				$multi=$db->loadObjectList();
      				foreach ($multi as $item){
      					$nameval=$item->multivalue;
      					$multivalues=explode('&',$item->multivalue);            		
            			for ($i=0;$i<count($multivalues) ;$i++ ) {
	            			$option=explode('#',$multivalues[$i]);
				            $name=(string)$option[0];
	            			$value=(string)$option[1];
	            			$sql_updates[]='insert into `#__listmanager_field_multivalue` (idfield,idobj,ord,name,value) 
	            				values("'.$item->idfield.'","'.$item->idobj.'","'.$i.'","'.$name.'","'.$value.'")';	            			
            			}			               
      				}
					$sql_updates[]='ALTER TABLE `#__listmanager_field` CHANGE COLUMN `styleclass` `css` VARCHAR(250) NULL DEFAULT NULL';
					$sql_updates[]='ALTER TABLE `#__listmanager_field` CHANGE COLUMN `defvalue` `defaulttext` VARCHAR(250) NULL DEFAULT NULL';					
					$sql_updates[]='ALTER TABLE `#__listmanager_field` ADD COLUMN `placeholder` VARCHAR(500) NULL';					
					$sql_updates[]='ALTER TABLE `#__listmanager_field` ADD COLUMN `exportable` TINYINT NOT NULL DEFAULT 0'  ;
					$sql_updates[]='ALTER TABLE `#__listmanager_field` ADD COLUMN `readmore` TINYINT NOT NULL DEFAULT 0'  ;
					$sql_updates[]='ALTER TABLE `#__listmanager_field` ADD COLUMN `readmore_word_count` INT NOT NULL DEFAULT 100'  ;
					$sql_updates[]='ALTER TABLE `#__listmanager_field` ADD COLUMN `link_url` VARCHAR(1000) NULL '  ;
					$sql_updates[]='ALTER TABLE `#__listmanager_field` ADD COLUMN `link_id` INT NULL '  ;					
					$sql_updates[]='ALTER TABLE `#__listmanager_field` ADD COLUMN `link_type` INT NOT NULL DEFAULT -1 '  ;
					$sql_updates[]='ALTER TABLE `#__listmanager_field` ADD COLUMN `link_width` VARCHAR(50) NOT NULL DEFAULT "800px" '  ;
					$sql_updates[]='ALTER TABLE `#__listmanager_field` ADD COLUMN `link_height` VARCHAR(50) NOT NULL DEFAULT "400px" '  ;
					
					$sql_updates[]='ALTER TABLE `#__listmanager_field_view` CHANGE COLUMN `defvalue` `defaulttext` VARCHAR(250) NULL DEFAULT NULL'  ;
					$sql_updates[]='ALTER TABLE `#__listmanager_field_view` ADD COLUMN `order` INT NOT NULL DEFAULT 0'  ;
					
					$sql_updates[]='ALTER TABLE `#__listmanager_view` ADD COLUMN `default_order` VARCHAR(2500) NULL '  ;
					
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `default_order` VARCHAR(2500) NULL '  ;
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `hidelist` TINYINT NOT NULL DEFAULT 0'  ;
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `list_type` INT NOT NULL DEFAULT 0 '  ;					
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `list_columns` INT NOT NULL DEFAULT 3 '  ;
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `list_height` INT NOT NULL DEFAULT 150 '  ;					
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `list_name_class` VARCHAR(500) NOT NULL DEFAULT "span5" '  ;
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `list_value_class` VARCHAR(500) NOT NULL DEFAULT "span6" '  ;
					
					$sql_updates[]='ALTER TABLE `#__listmanager_access` ADD COLUMN `idrecord` INT NULL '  ;
						
				}
				if (version_compare($oldRelease,'1.2.10','lt')){
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` CHANGE COLUMN `name` `name` VARCHAR(500) NULL DEFAULT NULL '  ;
					$sql_updates[]='ALTER TABLE `#__listmanager_field` ADD COLUMN `innername` VARCHAR(150) NULL '  ;					
					$sql_updates[]='UPDATE #__listmanager_field SET innername=name WHERE innername is null';					
				}
				if (version_compare($oldRelease,'2.0.0','lt')){
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `tool_column_position` INT NULL DEFAULT 0 '  ;										
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `tool_column_name` VARCHAR(2500) DEFAULT NULL '  ;
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `view_toolbar` TINYINT NOT NULL DEFAULT 1 ';
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `view_filter` TINYINT NOT NULL DEFAULT 1 ';
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `view_bottombar` TINYINT NOT NULL DEFAULT 1 ';	
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `detail_onclick` TINYINT NOT NULL DEFAULT 0 ';
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `keyfields` VARCHAR(200) NULL ';
				}
				if (version_compare($oldRelease,'2.0.9','lt')){
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `detailpdf` LONGTEXT NULL ';
				}
				if (version_compare($oldRelease,'2.0.14','lt')){
					$sql_updates[]='ALTER TABLE `#__listmanager_view` ADD COLUMN `detail` LONGTEXT NULL ';
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `detailmode` INT NOT NULL DEFAULT 0 ';					
				}
				if (version_compare($oldRelease,'2.0.15','lt')){
					$sql_updates[]='ALTER TABLE `#__listmanager_field` ADD COLUMN `bulk` TINYINT NOT NULL DEFAULT 0'  ;
				}
				
				if (version_compare($oldRelease,'2.1.0','lt')){
					$sql_updates[]='ALTER TABLE `#__listmanager_view` ADD COLUMN `date_format` VARCHAR(150) NULL '  ;
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `date_format_bbdd` VARCHAR(150) NULL ' ;
					$sql_updates[]='UPDATE `#__listmanager_listing` set  `date_format_bbdd`= date_format';	
				}
				
				if (version_compare($oldRelease,'2.1.4','lt')){
					$sql_updates[]='ALTER TABLE `#__listmanager_field` ADD COLUMN `searchable` TINYINT NOT NULL DEFAULT 1'  ;
					$sql_updates[]='ALTER TABLE `#__listmanager_field` ADD COLUMN `searchtype` TINYINT NOT NULL DEFAULT 0'  ;
				}
				
				if (version_compare($oldRelease,'2.1.6','lt')){
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `detailrtf` VARCHAR(1000) NULL'  ;
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `listrtf` VARCHAR(1000) NULL'  ;
				}
				
				if (version_compare($oldRelease,'2.1.8','lt')){
					$sql_updates[]='ALTER TABLE `#__listmanager_field` ADD COLUMN `cardview` TINYINT NOT NULL DEFAULT 0'  ;					
				}
				
				if (version_compare($oldRelease,'2.1.10','lt')){
					$sql_updates[]='ALTER TABLE `#__listmanager_view` ADD COLUMN `detailpdf` LONGTEXT NULL ';										
				}
				
				if (version_compare($oldRelease,'2.1.13','lt')){
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `search_type` TINYINT NOT NULL DEFAULT 0 ';
				}	
				
				if (version_compare($oldRelease,'2.2.1','lt')){
					$sql_updates[]='ALTER TABLE `#__listmanager_field` ADD COLUMN `link_detail` TINYINT NOT NULL DEFAULT 0 ';
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `view_toolbar_bottom` TINYINT NOT NULL DEFAULT 0 ';
				}
				
				if (version_compare($oldRelease,'2.2.8','lt')){
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `show_filter` TINYINT NOT NULL DEFAULT 0 ';
					$sql_updates[]='ALTER TABLE `#__listmanager_listing` ADD COLUMN `filter_automatic` TINYINT NOT NULL DEFAULT 0 ';
				}
				if (version_compare($oldRelease,'2.2.16','lt')){
					$sql_updates[]="ALTER TABLE `#__listmanager_listing` ADD COLUMN `specialfilters` LONGTEXT NOT NULL DEFAULT ''";
				}			
				
				if (version_compare($oldRelease,'2.3.3','lt')){
					$sql_updates[]="ALTER TABLE `#__listmanager_listing` ADD COLUMN `editor` TINYINT NOT NULL DEFAULT 0";
				}
				
				if (version_compare($oldRelease,'2.5.0','lt')){
					$sql_updates[]="ALTER TABLE `#__listmanager_listing` ADD COLUMN `savesearch` TINYINT NOT NULL DEFAULT 0";
					$sql_updates[]="CREATE TABLE IF NOT EXISTS `#__listmanager_search` (
						  `id` INT NOT NULL AUTO_INCREMENT,
						  `idlisting` INT NULL,
						  `iduser` INT NULL,						  
						  `searchdatetime` DATETIME NULL,
						  `username` VARCHAR(250) NULL,
						  `terms` VARCHAR(2500) NULL,
						  PRIMARY KEY (`id`)
						) CHARACTER SET utf8 COLLATE utf8_general_ci AUTO_INCREMENT=1;";
				}				
				
				
				foreach ($sql_updates as $sql){
					$db->setQuery($sql);
					$db->query();										
				}
			}
			else { 
				$rel = $this->release;
				
			}
		}
	}
 
	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent){
		if ($type!='uninstall'){
			$jversion = new JVersion();
			if( version_compare( $jversion->getShortVersion(), '2.5.5', 'lt' ) ) {
				rename(JPATH_ADMINISTRATOR.'/components/com_listmanager/controller_16.php', JPATH_ADMINISTRATOR.'/components/com_listmanager/controller.php');
				rename(JPATH_ADMINISTRATOR.'/components/com_listmanager/models/main.model_16.php', JPATH_ADMINISTRATOR.'/components/com_listmanager/models/main.model.php');
				rename(JPATH_ADMINISTRATOR.'/components/com_listmanager/views/main.view_16.php', JPATH_ADMINISTRATOR.'/components/com_listmanager/views/main.view.php');
				rename(JPATH_SITE.'/components/com_listmanager/controller_16.php', JPATH_SITE.'/components/com_listmanager/controller.php');
				rename(JPATH_SITE.'/components/com_listmanager/models/main.model_16.php', JPATH_SITE.'/components/com_listmanager/models/main.model.php');
				rename(JPATH_SITE.'/components/com_listmanager/views/main.view_16.php', JPATH_SITE.'/components/com_listmanager/views/main.view.php');
			}
		}
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
 
	/*
	 * sets parameter values in the component's row of the extension table
	 */
	function setParams($param_array) {
		if ( count($param_array) > 0 ) {
			// read the existing component value(s)
			$db = JFactory::getDbo();
			$db->setQuery('SELECT params FROM #__extensions WHERE element = "com_listmanager" and type="component"');
			$params = json_decode( $db->loadResult(), true );
			// add the new variable(s) to the existing one(s)
			foreach ( $param_array as $name => $value ) {
				$params[ (string) $name ] = (string) $value;
			}
			// store the combined new and existing values back as a JSON string
			$paramsString = json_encode( $params );
			$db->setQuery('UPDATE #__extensions SET params = ' .
				$db->quote( $paramsString ) .
				' WHERE element = "com_listmanager" and type="component"' );
				$db->query();
		}
	}
	
	/*insert schema entry if not present*/
	
	function insertSchema(){  
	  	$db = JFactory::getDbo();
		$db->setQuery('select count(*) from #__schemas where extension_id=(select extension_id from #__extensions WHERE element = "com_listmanager" and type="component")');
		$entrada=  $db->loadResult();		
		//extension_id
		if($entrada=="0"){
         	$db->setQuery('insert into #__schemas (extension_id,version_id) values ((select extension_id from #__extensions WHERE element = "com_listmanager" and type="component"),"1.1.1") ');
				  $db->query();      
	    }
	}
	
}
?>