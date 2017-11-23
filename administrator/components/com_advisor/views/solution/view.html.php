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

class AdvisorViewSolution extends wrapperMainView
{
	protected $permissions;
  function display($tpl = null){
  	$this->permissions = $this->getActions();
  	$layout = $this->getLayout();
  	$idflow=$this->get('IdFlow');
  	$this->assignRef('idflow', $idflow);
  	switch ($layout) {
  		case 'default':
  			$items=$this->get('Data');
  			$this->assignRef('items', $items);
  			$solutions=$this->get('SolutionListDefault');
  			$this->assignRef('solutions', $solutions);  			
  			$pageNav=$this->get('PageNav');
            $this->assignRef('pagenav', $pageNav); 
  			break;
  		case 'edit': 	
            $items =$this->get('DataOne');
            $this->assignRef('item', $items);
            $products =$this->get('ProductList');
            $this->assignRef('products', $products);  
            $hikaproducts =$this->get('HikaProductList');
            $this->assignRef('hikaproducts', $hikaproducts); 
            $joomlaproducts =$this->get('JoomlaProductList');
            $this->assignRef('joomlaproducts', $joomlaproducts); 
            
            
            $mod=$this->getModel('solution');  
            $virtueproducts=null;
            if ($mod->validateComponent("virtuemart")){
	            if (!class_exists( 'VmConfig' )) require(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');
	            VmConfig::loadConfig();
	            $productModel = VmModel::getModel('Product');
	            $virtueproducts=$productModel->getProductListing(); 
	            
            } 
            $this->assignRef('virtueproducts', $virtueproducts);
                         
            $steps =$this->get('StepList');
            $this->assignRef('steps', $steps);
            $stepoptions =$this->get('StepOptionList');
            $this->assignRef('stepoptions', $stepoptions);    
            $solutions =$this->get('SolutionList');
            $this->assignRef('solutions', $solutions);                    
            break;   
  			
  	}
    $this->addToolbar();
    parent::display($tpl);
  }
  
  protected function addToolbar(){
  	  $document = JFactory::getDocument();
      $document->addStyleDeclaration('.icon-48-advisor {background-image: url(components/com_advisor/assets/img/Advisor48x48.png)}');
  	  $document->addStyleDeclaration('.icon-32-flow {background-image: url(components/com_advisor/assets/img/flow_back.png)}');
  	  $document->addStyleDeclaration('.icon-32-solution {background-image: url(components/com_advisor/assets/img/solution_back.png)}');
      $layout = $this->getLayout();
      switch ($layout) {              
          case 'default':
            JToolBarHelper::title( JText::_( 'ADVISOR' ).': <small><small>'.JText::_( 'SOLUTION_LIST' ).'</small></small>','advisor' );
            JToolBarHelper::custom( 'flow', 'back', 'back', JText::_( "RETURN_TO" ).JText::_( "FLOW" ), false, false );
            JToolBarHelper::spacer(8);
            JToolBarHelper::divider();
            JToolBarHelper::spacer(8);
            JToolBarHelper::editList('edit');
            if ($this->permissions->get('core.admin')){	            	            
	            JToolBarHelper::addNew('add');
	            JToolBarHelper::deleteList();
            }
            break;
          case 'add':
          case 'edit':
            JToolBarHelper::title( JText::_( 'ADVISOR' ).': <small><small>'.JText::_( 'SOLUTION_LIST_ADD' ).'</small></small>','advisor' );
            JToolBarHelper::custom( 'solution', 'back', 'back', JText::_( "RETURN_TO" ).JText::_( "SOLUTION" ), false, false );
            JToolBarHelper::spacer(8);
            JToolBarHelper::divider();
            JToolBarHelper::spacer(8);
            if ($this->permissions->get('core.admin')){
	            JToolBarHelper::apply();
	            JToolBarHelper::save();
            }
            JToolBarHelper::cancel( 'cancel', JText::_( 'CLOSE' ) );
            break;          
      }    	    	  
    }     
}

?>
