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

class ListmanagerViewListing extends HelperListmanagerView 
{
    
    function display($tpl = null) {    	
      $layout = $this->getLayout();
      switch ($layout) {              
          case 'default':
            $listings =$this->get('Data');
            $pageNav=$this->get('PageNav');
            $this->assignRef('items', $listings);                
            $this->assignRef('pagenav', $pageNav);
            break;
          case 'edit':
            $listing =$this->get('DataOne');
            $fields=$this->get('DataFields');
            $multivalues=$this->get('Multivalues');
            $alllist=$this->get('Alllist');
            $this->assignRef('item', $listing);       
            $this->assignRef('fields', $fields);
            $this->assignRef('multivalues', $multivalues);               
            $this->assignRef('alllist', $alllist);
            break;              
          case 'config':
          	$listing =$this->get('DataOne');
          	$fields=$this->get('DataFields');
            $this->assignRef('item', $listing); 
            $this->assignRef('fields', $fields); 
            break;   
          case 'admindata':
            $records =$this->get('DataRecords');
            $fields=$this->get('DataFields');            
            $this->assignRef('records', $records);       
            $this->assignRef('fields', $fields);
            $pageNav=$this->get('PageNav');
            $this->assignRef('pagenav', $pageNav);
            break; 
            
          case 'editdata':
            $record =$this->get('Record');            
            //$fields=&$this->get('DataFields');            
            $this->assignRef('record', $record);
            $users =$this->get('Users');
            $this->assignRef('users', $users);            
            //$this->assignRef('fields', $fields);
            $listing =$this->get('DataOneListing');
            $this->assignRef('item', $listing);
            break;  
           
          case 'access':
          	$access=$this->get('DataAccess');                               
            $this->assignRef('access', $access);
            $pageNav=$this->get('PageNav');
            $this->assignRef('pagenav', $pageNav);
          	break;
          	
          case 'loaddata': case 'loaddatasql':            
            $fields=$this->get('DataFields');                               
            $this->assignRef('fields', $fields);            
            break;
            
          case 'configacl' :
            $fields=$this->get('DataFields');                               
            $this->assignRef('fields', $fields);            
            $lstacl=$this->get('DataACL');
            $this->assignRef('acl', $lstacl);              
            break;
            
          case 'layout':
          	$listing =$this->get('DataOne');
            $this->assignRef('item', $listing);    
          	$allfields =$this->get('DataFields');
            $this->assignRef('allfields', $allfields);
            break;
          
          case 'detail':
          case 'detailpdf':
          	$listing =$this->get('DataOne');
            $this->assignRef('item', $listing);    
          	$allfields =$this->get('DataFields');
            $this->assignRef('allfields', $allfields);
            break;  	
          case 'detailrtf':
          case 'listrtf':          
            $listing =$this->get('DataOne');
            $this->assignRef('item', $listing);            	
            break;    
          case 'importlist': break;
          default:
            break;
      }
      // Set the toolbar
      $this->addToolbar();
      parent::display($tpl);
    }   
    
    /**
  	 * Add the page title and toolbar.
  	 *
  	 * @since	1.6
  	 */
  	protected function addToolbar(){
  		$this->permissions = $this->getActions();
  	  $document = JFactory::getDocument();
      $document->addStyleDeclaration('.icon-48-listmanagerlisting {background-image: url('.JURI::base().'components/com_listmanager/assets/img/ListManagerListing.png)}');
      $document->addStyleDeclaration('.icon-48-listmanager {background-image: url('.JURI::base().'components/com_listmanager/assets/img/ListManager48x48.png)}');
      $document->addStyleDeclaration('.icon-48-listmanagerconfig {background-image: url('.JURI::base().'components/com_listmanager/assets/img/ListManager48x48.png)}');
      $document->addStyleDeclaration('.icon-32-layout {background-image: url('.JURI::base().'components/com_listmanager/assets/img/display.png)}');
      $document->addStyleDeclaration('.icon-32-views {background-image: url('.JURI::base().'components/com_listmanager/assets/img/views.png)}');
      //$document->addStyleDeclaration('.icon-32-back {background-image: url('.JURI::base().'components/com_listmanager/assets/img/back.png)}');
      $document->addStyleDeclaration('.icon-32-access {background-image: url('.JURI::base().'components/com_listmanager/assets/img/access.png)}');
      $document->addStyleDeclaration('.icon-32-forwardcsv {background-image: url('.JURI::base().'components/com_listmanager/assets/img/forwardcsv.png)}');
      $document->addStyleDeclaration('.icon-32-forwardsql {background-image: url('.JURI::base().'components/com_listmanager/assets/img/forwardsql.png)}');
      $document->addStyleDeclaration('.icon-32-detail {background-image: url('.JURI::base().'components/com_listmanager/assets/img/detail.png)}');
      $document->addStyleDeclaration('.icon-32-detailpdf {background-image: url('.JURI::base().'components/com_listmanager/assets/img/detailpdf.png)}');
      $document->addStyleDeclaration('.icon-32-detailrtf {background-image: url('.JURI::base().'components/com_listmanager/assets/img/detailrtf.png)}');
      $document->addStyleDeclaration('.icon-32-resetrating {background-image: url('.JURI::base().'components/com_listmanager/assets/img/resetrating.png)}');      
      $document->addStyleDeclaration('.icon-32-importlist {background-image: url('.JURI::base().'components/com_listmanager/assets/img/import.png)}');
  	  $layout = $this->getLayout();
      switch ($layout) {              
          case 'default':
            JToolBarHelper::title( JText::_( 'LISTINGS' ). ': <small><small>' . JText::_ ( 'LIST' ) . '</small></small>','listmanager' );
            if ($this->permissions->get('core.create')){
            	JToolBarHelper::custom('copy','copy','copy',JText::_( 'COPY' ),true,false);
            	JToolBarHelper::custom('importlist','upload','importlist',JText::_( 'IMPORTLIST' ),false,false);
            }
            /*JToolBarHelper::spacer();
            JToolBarHelper::divider();
            JToolBarHelper::spacer(); 
			JToolBarHelper::preferences( 'com_listmanager' );*/
            JToolBarHelper::spacer();
            JToolBarHelper::divider();
            JToolBarHelper::spacer();
            if ($this->permissions->get('core.delete')){
            	JToolBarHelper::deleteList();
            }
            if ($this->permissions->get('core.edit')){
            	JToolBarHelper::editList('edit');
            }
            if ($this->permissions->get('core.create')){
            	JToolBarHelper::addNew('add');
            }            
            //JToolBarHelper::custom('admindata','publish','publish',JText::_( 'ADMINDATA' ),true,false);
            break;
          case 'edit': 
            JToolBarHelper::title( JText::_( 'LISTINGS' ).':'.JText::_( 'EDIT' ),'listmanagerlisting' );
            JToolBarHelper::custom( 'listback', 'back', 'back', JText::_( "LMVIEWSBACK" ), false, false );            
            JToolBarHelper::spacer();
            JToolBarHelper::custom( 'views', 'tree-2', 'views', JText::_( "LISTINGSVIEWS" ), false, false );
            JToolBarHelper::spacer();
            JToolBarHelper::custom( 'layout', 'list-2', 'layout', JText::_( "LISTINGSCONFIGLAYOUT" ), false, false );
            JToolBarHelper::spacer();
            JToolBarHelper::custom( 'detail', 'out-3', 'detail', JText::_( "LISTINGSCONFIGDETAIL" ), false, false );
            JToolBarHelper::custom( 'detailpdf', 'out-3', 'detailpdf', JText::_( "LISTINGSCONFIGDETAILPDF" ), false, false );
            JToolBarHelper::custom( 'detailrtf', 'out-3', 'detailrtf', JText::_( "LISTINGSCONFIGDETAILRTF" ), false, false );
            JToolBarHelper::custom( 'listrtf', 'file', 'detailrtf', JText::_( "LISTINGSCONFIGLISTRTF" ), false, false );
            JToolBarHelper::spacer();
            JToolBarHelper::divider();
            JToolBarHelper::spacer();
            if ($this->permissions->get('core.edit')){
            	JToolBarHelper::apply();
            	JToolBarHelper::save();
            }
            JToolBarHelper::cancel( 'cancel', JText::_( 'CLOSE' ) );
            break;
          case 'config': 
            JToolBarHelper::title( JText::_( 'LISTINGS' ).': '.JText::_( 'CONFIG' ),'listmanagerconfig' );
            JToolBarHelper::custom( 'listback', 'back', 'back', JText::_( "LMVIEWSBACK" ), false, false );
            JToolBarHelper::spacer();
            JToolBarHelper::divider();
            JToolBarHelper::spacer();
            if ($this->permissions->get('core.edit')){
            	JToolBarHelper::apply('applyconfig');
            	JToolBarHelper::save('saveConfig');
            }
            JToolBarHelper::cancel( 'cancel', JText::_( 'CLOSE' ) );
            break;
          case 'new':
            JToolBarHelper::title( JText::_( 'LISTINGS' ).': '.JText::_( 'NEW' ),'listmanagerlisting' );
            if ($this->permissions->get('core.edit')){
            	JToolBarHelper::apply();              
            	JToolBarHelper::save();
            }
            JToolBarHelper::cancel();
            break;
            
          case 'admindata':          	
            JToolBarHelper::title( JText::_( 'LISTINGSDATA' ),'listmanager' );
            JToolBarHelper::custom( 'listback', 'back', 'back', JText::_( "LMVIEWSBACK" ), false, false );
            JToolBarHelper::spacer();
            JToolBarHelper::divider();
            JToolBarHelper::spacer();     
            if ($this->permissions->get('core.delete')){                   
            	JToolBarHelper::custom('deletealldata','delete','delete',JText::_( 'DELETEALLDATA' ),false,false);
            	JToolBarHelper::deleteList(JText::_( 'DELETEDATA' ),'deletedata');            	
            }
            if ($this->permissions->get('core.edit')){
            	JToolBarHelper::editList('editdata');
            }
            if ($this->permissions->get('core.create')){
            	JToolBarHelper::addNew('newdata');
            }
            JToolBarHelper::spacer();
            JToolBarHelper::divider();
            JToolBarHelper::spacer(); 
            JToolBarHelper::custom('access','clock','access',JText::_( 'ACCESSDATA' ),true,false);
            JToolBarHelper::custom('loaddata','upload','forwardcsv',JText::_( 'LOADDATA' ),false,false);
            JToolBarHelper::custom('loaddatasql','database','forwardsql',JText::_( 'LOADDATASQL' ),false,false);
            JToolBarHelper::custom('resetrating','loop','resetrating',JText::_( 'RESETRATING' ),false,false);
            //JToolBarHelper::custom('exportdata','export','export',JText::_( 'EXPORTDATA' ),false,false);
            JToolBarHelper::cancel( 'cancel', JText::_( 'CLOSE' ) );
            break;
            
          case 'editdata':
            JToolBarHelper::title( JText::_( 'LISTINGSDATARECORD' ),'listmanager' );
            if ($this->permissions->get('core.edit')){            
            	JToolBarHelper::save('saverecord');
            }
            JToolBarHelper::custom('resetratingone','loop','resetrating',JText::_( 'RESETRATING' ),false,false);
            JToolBarHelper::cancel( 'cancelrecord', JText::_( 'CLOSE' ) );
            break;
            
          case 'loaddata':
            JToolBarHelper::title( JText::_( 'LISTINGSLOADDATA' ),'listmanager' );
            if ($this->permissions->get('core.edit')){            
            	JToolBarHelper::save('saveloaddata');
            }
            JToolBarHelper::cancel( 'cancelrecord', JText::_( 'CLOSE' ) );
            break;
          
          case 'loaddatasql':
            JToolBarHelper::title( JText::_( 'LISTINGSLOADDATASQL' ),'listmanager' );
            if ($this->permissions->get('core.edit')){            
            	JToolBarHelper::save('saveloaddatasql');
            }
            JToolBarHelper::cancel( 'cancelrecord', JText::_( 'CLOSE' ) );
            break;
            
          case 'configacl':
            JToolBarHelper::title( JText::_( 'LISTINGSCONFIGACL' ),'listmanager' );
            JToolBarHelper::custom( 'listback', 'back', 'back', JText::_( "LMVIEWSBACK" ), false, false );
            JToolBarHelper::spacer();
            JToolBarHelper::divider();
            JToolBarHelper::spacer();
            if ($this->permissions->get('core.edit')){  
            	JToolBarHelper::apply('applyacl'); 
            	JToolBarHelper::save('saveacl');
            }
            JToolBarHelper::cancel( 'cancel', JText::_( 'CLOSE' ) );
            break;         
               
		  case 'layout':
            JToolBarHelper::title( JText::_( 'LISTINGSCONFIGLAYOUT' ),'listmanager' );
            JToolBarHelper::custom( 'back', 'back', 'back', JText::_( "LMVIEWSBACK" ), false, false );
            JToolBarHelper::spacer();
            JToolBarHelper::divider();
            JToolBarHelper::spacer();
            if ($this->permissions->get('core.edit')){
            	JToolBarHelper::apply('applylayout');                
            	JToolBarHelper::save('savelayout');
            }            
            JToolBarHelper::cancel( 'cancellist', JText::_( 'CLOSE' ) );
            break;  

         case 'detail':
            JToolBarHelper::title( JText::_( 'LISTINGSCONFIGDETAIL' ),'listmanager' );
            JToolBarHelper::custom( 'back', 'back', 'back', JText::_( "LMVIEWSBACK" ), false, false );
            JToolBarHelper::spacer();
            JToolBarHelper::divider();
            JToolBarHelper::spacer();
            if ($this->permissions->get('core.edit')){
            	JToolBarHelper::apply('applydetail');                
            	JToolBarHelper::save('savedetail');
            }            
            JToolBarHelper::cancel( 'cancellist', JText::_( 'CLOSE' ) );
            break; 
            
          case 'detailpdf':
            	JToolBarHelper::title( JText::_( 'LISTINGSCONFIGDETAILPDF' ),'listmanager' );
            	JToolBarHelper::custom( 'back', 'back', 'back', JText::_( "LMVIEWSBACK" ), false, false );
            	JToolBarHelper::spacer();
            	JToolBarHelper::divider();
            	JToolBarHelper::spacer();
            	if ($this->permissions->get('core.edit')){
            		JToolBarHelper::apply('applydetailpdf');
            		JToolBarHelper::save('savedetailpdf');
            	}
            	JToolBarHelper::cancel( 'cancellist', JText::_( 'CLOSE' ) );
            	break;
            
           case 'detailrtf':
            		JToolBarHelper::title( JText::_( 'LISTINGSCONFIGDETAILRTF' ),'listmanager' );
            		JToolBarHelper::custom( 'back', 'back', 'back', JText::_( "LMVIEWSBACK" ), false, false );
            		JToolBarHelper::spacer();
            		JToolBarHelper::divider();
            		JToolBarHelper::spacer();
            		if ($this->permissions->get('core.edit')){
            			JToolBarHelper::apply('applydetailrtf');
            			JToolBarHelper::save('savedetailrtf');
            		}
            		JToolBarHelper::cancel( 'cancellist', JText::_( 'CLOSE' ) );
            		break;
           case 'listrtf':
            			JToolBarHelper::title( JText::_( 'LISTINGSCONFIGLISTRTF' ),'listmanager' );
            			JToolBarHelper::custom( 'back', 'back', 'back', JText::_( "LMVIEWSBACK" ), false, false );
            			JToolBarHelper::spacer();
            			JToolBarHelper::divider();
            			JToolBarHelper::spacer();
            			if ($this->permissions->get('core.edit')){
            				JToolBarHelper::apply('applylistrtf');
            				JToolBarHelper::save('savelistrtf');
            			}
            			JToolBarHelper::cancel( 'cancellist', JText::_( 'CLOSE' ) );
            			break;
            
         case 'access':
            JToolBarHelper::title( JText::_( 'LISTINGSACCESS' ),'listmanager' );
            JToolBarHelper::cancel( 'cancelrecord', JText::_( 'CLOSE' ) );
            break;
         
         case 'importlist':
         	JToolBarHelper::title( JText::_( 'LISTINGSIMPORT' ),'listmanager' );
       		JToolBarHelper::custom( 'listback', 'back', 'back', JText::_( "LMVIEWSBACK" ), false, false );
            JToolBarHelper::spacer();
            JToolBarHelper::divider();
            JToolBarHelper::spacer();
            if ($this->permissions->get('core.edit')){  
            	JToolBarHelper::save('saveimport');
            }
            JToolBarHelper::cancel( 'cancel', JText::_( 'CLOSE' ) );
         	break;
                     
      }    	    	  
    }    

	public function getExecuteQuery($query) {       
      $db = JFactory::getDBO();
      $db->setQuery($query);
      return $db->loadRowList();      	
    }
}

?>
