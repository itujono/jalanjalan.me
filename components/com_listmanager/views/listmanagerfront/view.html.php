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

class ListmanagerViewListmanagerfront extends ListmanagerViewMain{
    function display($tpl = null){
	    $layout =$this->getLayout();
	    $app=JFactory::getApplication();
	    $params = $app->getParams();
	    switch ($layout):
	    	case 'default':
		    	// Regenerate table from desc
		    	/*$model=$this->getModel('listmanagerfront');
	    		$model->setId($params->get('prefsids'));
		    	$model->repopulateList();*/
	    	case 'form':		     	    	
		     	//$model=$this->getModel('listmanagerfront');
	    		$model=$this->getModel('listmanagerfront');
	    		$model->setId($params->get('prefsids'));
		     	$acl = $model->getACLStringParam();
		     	$data = $model->getDataFieldsParam();
		     	$listing = $model->getDataParam();
		     	$allfields = $model->getDataAllFieldsLayout();
		     	
		     	$lmhid = JRequest::getVar('lmhid');
		     	$lmhval = JRequest::getVar('lmhval');
		     	$filterloaded=null;
		     	if ($lmhid!=null && $lmhval!=null){
		     		$filterloaded=array();
		     		$filterloaded['headerid']=$lmhid;
		     		$filterloaded['value']=$lmhval;
		     	}		     	 
		     	$detail = $model->getDetail();
		     	$this->assignRef('filterloaded',$filterloaded);
		     	$this->assignRef('acl',$acl);
		     	$this->assignRef('data',$data);
		     	$this->assignRef('listing',$listing);
		     	$this->assignRef('allfields',$allfields);
		     	$this->assignRef('detail',$detail);
	     	break;
	     	case 'detail':
	     		$id=JRequest::getVar('id',null);
	     		$idrecord=JRequest::getVar('idrecord',null);
	     		$model=$this->getModel('listmanagerfront');
	     		$lmhid = JRequest::getVar('lmhid');
	     		$lmhval = JRequest::getVar('lmhval');
	     		$filterloaded=null;
	     		if ($lmhid!=null && $lmhval!=null){
	     			$filterloaded=array();
	     			$filterloaded['headerid']=$lmhid;
	     			$filterloaded['value']=$lmhval;
	     		}
	     		$detail = $model->getDetail();
	     		$record = $model->getRecord();
	     		$this->assignRef('filterloaded',$filterloaded);
	     		$this->assignRef('detail',$detail);
	     		$this->assignRef('record',$record);
	     		break;
	    endswitch;
      	parent::display($tpl);       
    }                
}

?>
