<?php
//no direct access
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
 
// include the helper file
if(!defined('DS')){ 
	define('DS',DIRECTORY_SEPARATOR); 
  }
require_once(dirname(__FILE__).DS.'helper.php');

$helper = new ModListManagerHelper();


  $data = $helper->getDataFields($params->get('prefsids'));
  $listing = $helper->getData($params->get('prefsids'));
  $acl = $helper->getACLString($params->get('prefsids'));  
  $allfields = $helper->getDataAllFieldsLayout($params->get('prefsids'));  
  $detail = $helper->getDetail($params->get('prefsids'));
  
  $lmhid = JRequest::getVar('lmhid');
  $lmhval = JRequest::getVar('lmhval');
  $filterloaded=null;
  if ($lmhid!=null && $lmhval!=null){
     $filterloaded=array();
     $filterloaded['headerid']=$lmhid;
     $filterloaded['value']=$lmhval;
  }
  
  
/*  
  $records = $helper->getDataRecords($params->get('prefsids'),$data); 
   */
  // include the template for display
  require(JModuleHelper::getLayoutPath('mod_listmanager'));



?>
 
