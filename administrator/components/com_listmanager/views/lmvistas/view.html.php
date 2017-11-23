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

class ListmanagerViewLmvistas extends HelperListmanagerView
{   
    function display($tpl = null) {
      $listing =$this->get('DataOneListing');
      $this->assignRef('item', $listing);
      $layout = $this->getLayout();
      switch ($layout) {  
          case 'default':          	
          	$views =$this->get('DataViews');
            $this->assignRef('views', $views);
            $pageNav=$this->get('PageNav');
            $this->assignRef('pagenav', $pageNav);
            break;
            
          case 'edit':
          	$view =$this->get('DataOneView');
            $this->assignRef('view', $view);
            $fields =$this->get('DataFields');
            $this->assignRef('fields', $fields);
          	break;
          	
          case 'detail':
          case 'detailpdf':
          	$detail =$this->get('DataOneView');
          	$this->assignRef('item', $detail);
          	$allfields =$this->get('DataFields');
          	$this->assignRef('allfields', $allfields);
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
  		$this->permissions = $this->getActions();
  	  $document = JFactory::getDocument();
      $document->addStyleDeclaration('.icon-48-listmanager {background-image: url('.JURI::base().'components/com_listmanager/assets/img/ListManager48x48.png)}');
      $document->addStyleDeclaration('.icon-32-views {background-image: url('.JURI::base().'components/com_listmanager/assets/img/form_layout.png)}');
      $document->addStyleDeclaration('.icon-32-back {background-image: url('.JURI::base().'components/com_listmanager/assets/img/back.png)}');
      $document->addStyleDeclaration('.icon-32-detail {background-image: url('.JURI::base().'components/com_listmanager/assets/img/detail.png)}');
      $document->addStyleDeclaration('.icon-32-detailpdf {background-image: url('.JURI::base().'components/com_listmanager/assets/img/detailpdf.png)}');
  	  $layout = $this->getLayout();
      switch ($layout) {
          case 'default':
            JToolBarHelper::title( JText::_( 'LISTINGSVIEWS' ).' <small><small>'.$listName.'</small></small>','listmanager' );
            JToolBarHelper::custom( 'back', 'back', 'back', JText::_( "LMVIEWSBACK" ), false, false );
            JToolBarHelper::spacer();
            JToolBarHelper::divider();
            JToolBarHelper::spacer(); 
            if ($this->permissions->get('core.edit')){ 
            	JToolBarHelper::editList();
            }
            if ($this->permissions->get('core.create')){
            	JToolBarHelper::addNew();
            }
            if ($this->permissions->get('core.delete')){
            	JToolBarHelper::deleteList(JText::_( 'LISTINGS_DELETE_MESSAGE_VIEW' ),'delete');
            }
            JToolBarHelper::cancel( 'cancellist', JText::_( 'CLOSE' ) );
            break;
            
         case 'edit':
            JToolBarHelper::title( JText::_( 'LISTINGSEDITVIEWS' ).' <small><small>'.$listName.'</small></small>','listmanager' );
            if ($this->permissions->get('core.edit')){
            	JToolBarHelper::custom( 'detail', 'out-3', 'detail', JText::_( "LISTINGSCONFIGDETAIL" ), false, false );
            	JToolBarHelper::custom( 'detailpdf', 'out-3', 'detailpdf', JText::_( "LISTINGSCONFIGDETAILPDF" ), false, false );
            	JToolBarHelper::spacer();
            	JToolBarHelper::divider();
            	JToolBarHelper::spacer();
            	JToolBarHelper::apply();                
            	JToolBarHelper::save();
            }            
            JToolBarHelper::cancel( 'cancel', JText::_( 'CLOSE' ) );
            break; 
         case 'detail':
            	JToolBarHelper::title( JText::_( 'LISTINGSEDITVIEWS' ),'listmanager' );
            	//JToolBarHelper::custom( 'back', 'back', 'back', JText::_( "LMVIEWSBACK" ), false, false );
            	JToolBarHelper::spacer();
            	JToolBarHelper::divider();
            	JToolBarHelper::spacer();
            	if ($this->permissions->get('core.edit')){
            		JToolBarHelper::apply('applydetail');
            		JToolBarHelper::save('savedetail');
            	}
            	JToolBarHelper::cancel( 'canceldetail', JText::_( 'CLOSE' ) );
            	break;
          case 'detailpdf':
            	JToolBarHelper::title( JText::_( 'LISTINGSEDITVIEWS' ),'listmanager' );
            	//JToolBarHelper::custom( 'back', 'back', 'back', JText::_( "LMVIEWSBACK" ), false, false );
            	JToolBarHelper::spacer();
            	JToolBarHelper::divider();
            	JToolBarHelper::spacer();
            	if ($this->permissions->get('core.edit')){
            		JToolBarHelper::apply('applydetailpdf');
            		JToolBarHelper::save('savedetailpdf');
            	}
            	JToolBarHelper::cancel( 'canceldetailpdf', JText::_( 'CLOSE' ) );
            	break;
      }    	    	  
    }             
}

?>
