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
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );

require 'main.model.php';

class ListmanagerModelHelper extends ListmanagerMain{

	function _buildQueryFieldsList($idlist){
		if (!is_numeric($idlist)) JError::raiseError(505,JText::_('INTERNAL SERVER ERROR'));
		$query="select * from #__listmanager_field where idlisting=".$idlist;
		return $query;
	}
	
	function getSqlquery(){
		$request_string=JRequest::getVar('rstring');
		$db = JFactory::getDBO();
      	$db->setQuery($request_string);
      	return $db->loadAssocList();
	}
	function getFields(){
		$idlist=JRequest::getVar('idlist');
		$db = JFactory::getDBO();
      	$db->setQuery($this->_buildQueryFieldsList($idlist));
      	return $db->loadObjectList();
	}
}
?>

