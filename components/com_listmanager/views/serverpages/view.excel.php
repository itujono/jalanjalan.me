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
 
require(JPATH_COMPONENT.DS.'views'.DS.'main.view.php');

class ListmanagerViewServerpages extends ListmanagerViewMain
{
    function display($tpl = null){   

    	$list=$this->get('List');
      $this->assignRef('list',$list);
      
      $data=$this->get('DataFieldsExport');
      $this->assignRef('data',$data);
      
      $fullexport=JRequest::getVar('fullexport');
      $this->assignRef('fullexport',$fullexport);
      
      $layout = $this->getLayout();
      $this->assignRef('txtlayout',$layout);
      switch ($layout) {
        case 'excel':
        	$records=$this->get('AllDataRecords');
      		$this->assignRef('records',$records);  
        	break;
        case 'excelfiltered':
        	$records=$this->get('AllDataRecordsFiltered');
      		$this->assignRef('records',$records);
      		break;
          
      }   
          // get the document
          $document = JFactory::getDocument();          
          $document->setMimeEncoding('application/vnd.ms-excel');
          // set the MIME type
          JResponse::setHeader('Content-type','application/excel');
          JResponse::setHeader('Content-Disposition', 'attachment; filename=listmanager.xls');
          JResponse::setHeader('Content-Transfer-Encoding', 'binary');
          JResponse::setHeader('Expires', '0');
          JResponse::setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
          JResponse::setHeader('Pragma', 'public');      
      parent::display($tpl);       
    }                
}

?>
