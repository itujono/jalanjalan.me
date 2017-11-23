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
 
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view' );
jimport( 'joomla.filesystem.file' );

require(JPATH_COMPONENT.DS.'views'.DS.'main.view.php');

class ListmanagerViewServerPages extends ListmanagerViewMain{
    function display($tpl = null){
	    $layout =$this->getLayout();
	    $app=JFactory::getApplication();
	    switch ($layout):
	    	case 'detail':
	    		$model=$this->getModel('serverpages');
	    		$id=JRequest::getVar('id',null);
	     		/*$id=JRequest::getVar('id',null);
	     		$idrecord=JRequest::getVar('idrecord',null);
	     		$model=$this->getModel('serverpages');
	     		$lmhid = JRequest::getVar('lmhid');
	     		$lmhval = JRequest::getVar('lmhval');
	     		$filterloaded=null;
	     		if ($lmhid!=null && $lmhval!=null){
	     			$filterloaded=array();
	     			$filterloaded['headerid']=$lmhid;
	     			$filterloaded['value']=$lmhval;
	     		}*/
	     		$detail = $model->getDetail($id);
	     		$record = $model->getRecord();
	     		$fields=$model->getDataFields();
	     		//$this->assignRef('filterloaded',$filterloaded);
	     		$this->assignRef('detail',$detail);
	     		$this->assignRef('record',$record);
	     		$this->assignRef('fields',$fields);
	     		break;
	    endswitch;
      	parent::display($tpl);       
    }                
}

?>
