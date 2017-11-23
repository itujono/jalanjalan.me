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

class ListmanagerViewAccess extends HelperListmanagerView
{
    
    function display($tpl = null) {
      $listing =$this->get('DataOneListing');
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
      }
      // Set the toolbar
      $this->addToolbar($listing['name']);
      parent::display($tpl);
    }   
    
    /**
  	 * Add the page title and toolbar.
  	 *
  	 * @since	1.6
  	 */
  	protected function addToolbar($listName){
  	  $document = JFactory::getDocument();
      $document->addStyleDeclaration('.icon-48-listmanager {background-image: url('.JURI::base().'components/com_listmanager/assets/img/ListManager48x48.png)}');
      $document->addStyleDeclaration('.icon-32-back {background-image: url('.JURI::base().'components/com_listmanager/assets/img/back.png)}');
      $document->addStyleDeclaration('.icon-32-viewaccess {background-image: url('.JURI::base().'components/com_listmanager/assets/img/viewaccess.png)}');
      $document->addStyleDeclaration('.icon-32-download {background-image: url('.JURI::base().'components/com_listmanager/assets/img/download.png)}');
  	  $layout = $this->getLayout();
      switch ($layout) {
          case 'default':
            JToolBarHelper::title( JText::_( 'LISTINGSACCESS' ).' <small><small>'.$listName.'</small></small>','listmanager' );
            JToolBarHelper::custom( 'back', 'back', 'back', JText::_( "LMVIEWSBACK" ), false);
            JToolBarHelper::spacer();
            JToolBarHelper::divider();
            JToolBarHelper::spacer();  
            JToolBarHelper::custom( 'download', 'download', 'download', JText::_( "DOWNLOAD" ),false );
            JToolBarHelper::custom( 'edit', 'eye', 'viewaccess', JText::_( "VIEW" ),true );
            JToolBarHelper::cancel( 'cancellist', JText::_( 'CLOSE' ) );
            break;
            
         case 'edit':
            JToolBarHelper::title( JText::_( 'LISTINGSACCESSVIEW' ).' <small><small>'.$listName.'</small></small>','listmanager' );
            JToolBarHelper::cancel( 'cancel', JText::_( 'CLOSE' ) );
            break; 
           
      }    	    	  
    }             
}

?>
