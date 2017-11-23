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

class ListmanagerViewListmanager extends ListmanagerViewMain
{
    function display($tpl = null){      
     $layout =$this->getLayout();
     switch($layout){     
	     case 'table':
	     	$moduletype=JRequest::getVar('typemodule');
	     	if ($moduletype=='0'){
		      	$records=&$this->get('DataRecords');
		      	if($records!=null) $this->assignRef('records',str_replace('\'','&#39;',json_encode($records)));
		      	else $this->assignRef('records',$records);
		      	$data=&$this->get('DataFields');
		      	$this->assignRef('data',str_replace('\'','&#39;',json_encode($data)));
	     	} else {
	     		$empty='';
	     		$this->assignRef('records',$empty);
	     		$this->assignRef('data',$empty);
	     	}
	     	break;
     } 
      parent::display($tpl);       
    }                
}

?>
