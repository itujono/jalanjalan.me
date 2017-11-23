<?php
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
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
 
class ModListManagerFilterHelper { 
	
	protected function _buildQuerySelectAutofilterFields($id, $isView){
		if (!is_numeric($id)) JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
		$query='';
		if ($isView){
			$query='select idfield from #__listmanager_field_view where idview='.$id.' and autofilter!="-1"';
		} else {
			$query='select id from #__listmanager_field where idlisting='.$id.' and autofilter!="-1"';
		}
		return $query;
	}
	
	protected function checkId($id){
  		if (!$this->isView($id))
    		return $id;
    	else
    		return substr($id, 2);
  	}
  	protected function isView($id){
  		return !(strrpos($id,'v_')===false);
  	}
 	
	public function getAutoFilterData($id){
		$db = JFactory::getDBO();
      	$query=$this->_buildQuerySelectAutofilterFields($this->checkId($id), $this->isView($id));	
      	$db->setQuery($query);
      	return $db->loadObjectList();
	}
   
} 
?>
