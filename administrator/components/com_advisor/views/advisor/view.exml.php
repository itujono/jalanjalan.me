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
    
    function display($tpl = null) {
      $flow =$this->get('DataFlows');
      $products =$this->get('DataProducts');      
      $solutions =$this->get('DataSolutions');
      $solutionsoptions =$this->get('DataSolutionsOptions');
      $steps =$this->get('DataSteps');
      $options =$this->get('DataOptions');
      
      $this->assignRef('flow', $flow);      
      $this->assignRef('products', $products);
      $this->assignRef('solutions', $solutions);
      $this->assignRef('solutionsoptions', $solutionsoptions);
      $this->assignRef('steps', $steps);
      $this->assignRef('options', $options);
      $layout = $this->getLayout();
          // get the document
          $document =JFactory::getDocument();
          //$document->setMimeEncoding('application/xhtml+xml');
          // set the MIME type
          JResponse::setHeader('Content-type','application/octet-stream');
          JResponse::setHeader('Content-Disposition', 'attachment; filename='.trim(str_replace(' ', '_', $flow['title'])).'_export.xml');
          JResponse::setHeader('Content-Transfer-Encoding', 'binary');
          JResponse::setHeader('Expires', '0');
          JResponse::setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
          JResponse::setHeader('Pragma', 'public');          
      parent::display($tpl); 
    }   
}

?>
