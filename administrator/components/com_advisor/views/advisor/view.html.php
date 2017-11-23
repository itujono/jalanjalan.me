<?php
/*
* @component %%COMPONENTNAME%% 
 * @copyright Copyright (C) August 2017. 
 * @license GPL 3.0
 * This program is free software: you can redistribute it and/or modify it under the terms of the 
 * GNU General Public License as published by the Free Software Foundation, either version 3 of the License, 
 * or (at your option) any later version.
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 * See the GNU General Public License for more details.
 * See <http://www.gnu.org/licenses/>.
 * More info www.moonsoft.es gestion@moonsoft.es 
 */
 
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view' );

require JPATH_COMPONENT_ADMINISTRATOR.'/views/wrapper.view.php'; 

class AdvisorViewAdvisor extends wrapperMainView
{
	protected $permissions;
  function display($tpl = null){
  	$this->permissions = $this->getActions();  	
  	$layout = $this->getLayout();
  	$items=null;
  	switch ($layout) {
  		case 'default':
  			$items=$this->get('Data');
  			$this->assignRef('items', $items);
  			$pageNav=$this->get('PageNav');
            $this->assignRef('pagenav', $pageNav); 
  			break;
  		case 'edit':          	
            $items =$this->get('DataOne');
            $this->assignRef('item', $items);
            $steps =$this->get('_AllSteps');
            $this->assignRef('steps', $steps);                   
            break;
            
      case 'stats':
     
          $items=$this->get('DataStats');
          $this->assignRef('datos', $items);   
          break;
            
               
  			
  	}
    $this->addToolbar($items);
    parent::display($tpl);
  }
  
  protected function addToolbar($items){
  	  $document = JFactory::getDocument();
      $document->addStyleDeclaration('.icon-48-advisor {background-image: url(components/com_advisor/assets/img/Advisor48x48.png)}');
      $document->addStyleDeclaration('.icon-32-step {background-image: url(components/com_advisor/assets/img/step.png)}');
      $document->addStyleDeclaration('.icon-32-flow {background-image: url(components/com_advisor/assets/img/flow_back.png)}');
  	  $document->addStyleDeclaration('.icon-32-solution {background-image: url(components/com_advisor/assets/img/solution.png)}');
  	  $document->addStyleDeclaration('.icon-32-product {background-image: url(components/com_advisor/assets/img/product.png)}');
  	  $document->addStyleDeclaration('.icon-32-import {background-image: url(components/com_advisor/assets/img/import.png)}');
      $layout = $this->getLayout();
      switch ($layout) {              
          case 'default':
            JToolBarHelper::title( JText::_( 'ADVISOR' ).': <small><small>'.JText::_( 'FLOW_LIST' ).'</small></small>','advisor' );
            JToolBarHelper::spacer(8);
            JToolBarHelper::divider();            
            JToolBarHelper::spacer(6); 
            
            JToolBarHelper::custom( 'stats', 'health', 'health', JText::_( "FLOW_STATS" ), false, false );
                                   
            JToolBarHelper::custom( 'import', 'upload', 'upload', JText::_( "FLOW_IMPORT" ), false, false );
            JToolBarHelper::spacer(8);
            JToolBarHelper::divider();
            JToolBarHelper::editList('edit');
            if ($this->permissions->get('core.admin')){
	            JToolBarHelper::deleteList();	            
	            JToolBarHelper::addNew('add');
	            JToolBarHelper::preferences('com_advisor');            	
            }            
            break;
          case 'add':
          case 'edit':
            JToolBarHelper::title( JText::_( 'ADVISOR' ).': <small><small>'.JText::_( 'FLOW_LIST_ADD' ).'</small></small> ','advisor' );
            JToolBarHelper::custom( 'flow', 'back', 'back', JText::_( "RETURN_TO" ).JText::_( "FLOW_LIST" ), false, false );
            if($items!=null){
	            JToolBarHelper::spacer(8);
	            JToolBarHelper::divider();            
	            JToolBarHelper::spacer(6);
	            JToolBarHelper::custom( 'step', 'arrow-right-2', 'arrow-right-2', JText::_( "STEP" ), false, false );
	            JToolBarHelper::spacer(6);
	            JToolBarHelper::custom( 'product', 'cube', 'cube', JText::_( "PRODUCT" ), false, false );
	            JToolBarHelper::spacer(6);
	            JToolBarHelper::custom( 'solution', 'grid', 'grid', JText::_( "SOLUTION" ), false, false );
	            JToolBarHelper::spacer(8);
            }
            JToolBarHelper::divider();
            JToolBarHelper::spacer(8);
            if ($this->permissions->get('core.admin')){
	            JToolBarHelper::apply();
	            JToolBarHelper::save();	            
            }
            JToolBarHelper::cancel( 'cancel', JText::_( 'CLOSE' ) );
            break;  
           case 'import':
            JToolBarHelper::title( JText::_( 'ADVISOR' ).': <small><small>'.JText::_( 'FLOW_IMPORT' ).'</small></small>','advisor' );
            JToolBarHelper::custom( 'flow', 'back', 'back', JText::_( "RETURN_TO" ).JText::_( "FLOW_LIST" ), false, false );
            break;    
            
            
             case 'stats':
            JToolBarHelper::title( JText::_( 'ADVISOR' ).': <small><small>'.JText::_( 'FLOW_STATS' ).'</small></small>','advisor' );
            JToolBarHelper::custom( 'flow', 'flow', 'flow', JText::_( "RETURN_TO" ).JText::_( "FLOW_LIST" ), false, false );
            JToolBarHelper::divider();
            if($items!=null){
            	JToolBarHelper::custom( 'removestats', 'delete', 'delete', JText::_( "CLEAN_STATS" ), false, false );
            }            
            break;
            
                
      }    	    	  
    }     
}

?>
