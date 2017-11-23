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

class AdvisorViewOption extends wrapperMainView
{
	protected $permissions;
  function display($tpl = null){
  	$this->permissions = $this->getActions();
  	$layout = $this->getLayout();
  	$idflow=$this->get('IdFlow');
  	$idstep=$this->get('IdStep');
  	$this->assignRef('idflow', $idflow);
  	$this->assignRef('idstep', $idstep);
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
            $allsteps =$this->get('AllSteps');
            $this->assignRef('allsteps', $allsteps);       
            break;   
  			
  	}
    $this->addToolbar();
    parent::display($tpl);
  }
  
  protected function addToolbar(){
  	  $document = JFactory::getDocument();
      $document->addStyleDeclaration('.icon-48-advisor {background-image: url(components/com_advisor/assets/img/Advisor48x48.png)}');
  	  $document->addStyleDeclaration('.icon-32-step {background-image: url(components/com_advisor/assets/img/step_back.png)}');
  	  $document->addStyleDeclaration('.icon-32-option {background-image: url(components/com_advisor/assets/img/option_back.png)}');
      $layout = $this->getLayout();
      switch ($layout) {              
          case 'default':
            JToolBarHelper::title( JText::_( 'ADVISOR' ).': <small><small>'.JText::_( 'OPTION_LIST' ).'</small></small>','advisor' );
            JToolBarHelper::custom( 'step', 'back', 'back', JText::_( "RETURN_TO" ).JText::_( "STEP" ), false, false );
      		if ($this->permissions->get('core.admin')){
	            JToolBarHelper::custom( 'order', 'apply', 'apply', JText::_( "SAVE_ORDER" ), false, false );
            }
            JToolBarHelper::spacer(8);
            JToolBarHelper::divider();
            JToolBarHelper::spacer(8);
            JToolBarHelper::editList('edit');
            if ($this->permissions->get('core.admin')){
	            JToolBarHelper::deleteList();	            
	            JToolBarHelper::addNew('add');	            
            }
            break;
          case 'add':
          case 'edit':
            JToolBarHelper::title( JText::_( 'ADVISOR' ).': <small><small>'.JText::_( 'OPTION_LIST_ADD' ).'</small></small>','advisor' );
            JToolBarHelper::custom( 'option', 'back', 'back', JText::_( "RETURN_TO" ).JText::_( "OPTION" ), false, false );            
            JToolBarHelper::spacer(8);
            JToolBarHelper::divider();
            JToolBarHelper::spacer(8);
            if ($this->permissions->get('core.admin')){
	            JToolBarHelper::apply();
	            JToolBarHelper::custom( 'savenew', 'save-new', 'save-new', JText::_( "SAVENEW" ), false, false );
	            JToolBarHelper::save();            
            }
            JToolBarHelper::cancel( 'cancel', JText::_( 'CLOSE' ) );
            break;          
      }    	    	  
    }     
}

?>
