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

class ListmanagerViewServerpages extends ListmanagerViewMain
{
    function display($tpl = null){      
     $layout =$this->getLayout();
     switch($layout){     
	     case 'table':
	      	$records=$this->get('DataRecords');
	      	$recordTemp=str_replace('\'','&#39;',json_encode($records,JSON_HEX_APOS));
	      	$recordTemp=str_replace('>','&#62;',$recordTemp);
	      	$recordTemp=str_replace('<','&#60;',$recordTemp);
	      	if($records!=null) $this->assignRef('records',$recordTemp);
	      	else $this->assignRef('records',$records);
	      	$data=$this->get('DataFields');
	      	for ($i=0;$i<count($data);$i++):
	      		$ph=strtoupper('LMFILTERPLACEHOLDER_'.$data[$i]['innername']);
	      		$fph=strtoupper('LMFILTERPLACEFROMHOLDER_'.$data[$i]['innername']);
	      		$tph=strtoupper('LMFILTERPLACETOHOLDER_'.$data[$i]['innername']);
	      		$data[$i]['filterplaceholder']=JText::_($ph);
	      		$data[$i]['filterplaceholderfrom']=JText::_($fph);
	      		$data[$i]['filterplaceholderto']=JText::_($tph);	      		
	      	endfor;
	      	$recordTemp2=str_replace('\'','&#39;',json_encode($data,JSON_HEX_APOS));	      	
	      	$this->assignRef('data',$recordTemp2);
	      	$prefs=$this->get('DataPrefs');
	      	$recordTemp3=str_replace('\'','&#39;',json_encode($prefs,JSON_HEX_APOS));
	      	$this->assignRef('prefs',$recordTemp3);
	      	$log=$this->get('Log');
	      	if ($log!=null) $this->assignRef('log',$log);	      	
	     	break;	      
	     case 'email':	     	
	     	$model=$this->getModel();
	     	$this->assignRef('result', $model->sendEmail());
	     	break;     
	     case 'emailfiltered':	     	
	     	$model=$this->getModel();
	     	$this->assignRef('result', $model->sendEmailFiltered());
	     	break;
     } 
      parent::display($tpl);       
    }                
}

?>
