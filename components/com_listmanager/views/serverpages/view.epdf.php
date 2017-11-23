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
 
require_once('helper'.DS.'mpdf'.DS.'mpdf.php');
require(JPATH_COMPONENT.DS.'views'.DS.'main.view.php');

class ListmanagerViewServerpages extends ListmanagerViewMain
{
    function display($tpl = null){
    	$layout=JRequest::getVar('layout');
    	$list=$this->get('List');
    	$this->assignRef('list',$list);
    	$dataTotalsParams=array();
    	$dataTotalsParams['thousand']=$list->thousand;
    	$dataTotalsParams['decimal']=$list->decimal;
    	$data=$this->get('DataFieldsExport');
    	$this->assignRef('data',$data);
    	if ($layout=='pdf' || $layout=='pdffiltered'):	      
	      $title=JRequest::getVar('title');      
	      $this->assignRef('title',$title);
	      
	      $fullexport=JRequest::getVar('fullexport');
	      $this->assignRef('fullexport',$fullexport);
	      
	      $layout = $this->getLayout();
	      $this->assignRef('txtlayout',$layout);
	    endif;
      switch ($layout) {
        case 'pdf':
            $records=$this->get('AllDataRecords');
            $this->assignRef('records',$records);
            $ids=array();
            foreach ($records as $record):
            	$ids[]=$record['id'];
            endforeach;
            $model=$this->getModel();
            $totals=$model->getDataTotals(null,$ids,$dataTotalsParams,false);
            $this->assignRef('totals',$totals);
          break;
        case 'pdffiltered':
            $records=$this->get('AllDataRecordsFiltered');            
            $this->assignRef('records',$records);
            $ids=array();
            foreach ($records as $record):
            	$ids[]=$record['id'];
            endforeach;
            $model=$this->getModel();            
            $totals=$model->getDataTotals(null,$ids,$dataTotalsParams,true);
            $this->assignRef('totals',$totals);
          break;
          case 'detailpdf':
          	$data=$this->get('DataFields');
          	$this->assignRef('data',$data);
          	$records=$this->get('Record');          	
          	$this->assignRef('records',$records);
          	$detailpdf=$this->get('DetailPDF');          	          	
          	$this->assignRef('detailpdf',$detailpdf);
          	break;
      }
      // get the document
      $document = JFactory::getDocument();
      $document->setMimeEncoding('application/pdf');
      // set the MIME type
      JResponse::setHeader('Content-type','application/pdf');
      //JResponse::setHeader('Content-Disposition', 'attachment; filename=listmanager.pdf');
      JResponse::setHeader('Content-Transfer-Encoding', 'binary');
      JResponse::setHeader('Expires', '0');
      JResponse::setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
      JResponse::setHeader('Pragma', 'public');
                   
      parent::display($tpl);       
                    
    }                
}

?>
