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
include JPATH_COMPONENT_ADMINISTRATOR.'/views/main.view.php';

class ListmanagerViewSearch extends HelperListmanagerView
{
    
    function display($tpl = null) {
    	$data =$this->get('AllHistoric');
      	$this->assignRef('datahs', $data);
    	$document = JFactory::getDocument();          
        $document->setMimeEncoding('application/vnd.ms-excel');
        JResponse::setHeader('Content-type','application/excel');
        JResponse::setHeader('Content-Disposition', 'attachment; filename=listmanagerhistoric.xls');
        JResponse::setHeader('Content-Transfer-Encoding', 'binary');
        JResponse::setHeader('Expires', '0');
        JResponse::setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
        JResponse::setHeader('Pragma', 'public');
        parent::display($tpl); 
      /*$listing =$this->get('DataOneListing');
      $this->assignRef('item', $listing);
      $layout = $this->getLayout();
      switch ($layout) {  
          case 'default':          	
          	$acc =$this->get('DataAccess');
            $this->assignRef('acc', $acc);
            $pageNav=$this->get('PageNav');
            $this->assignRef('pagenav', $pageNav);
            break;
            
          case 'edit':
          	$acc =$this->get('DataOneAccess');
            $this->assignRef('acc', $acc);
          	break;           
          default:
            break;
      }*/
      //parent::display($tpl);
    }
}

?>
