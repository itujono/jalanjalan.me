<?php
 /* @component %%COMPONENTNAME%% 
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
jimport( 'joomla.filesystem.file' );

require_once("mpdf/mpdf.php");

require JPATH_COMPONENT_ADMINISTRATOR.'/views/main.view.php';

class AdvisorViewAdvisor extends AdvisorMainView
{
    function display($tpl = null){
    	$session =JFactory::getSession();
    	$flow=JRequest::getVar('idflow'); 
    	$resume=$session->get('ad_'.$flow);
      	$this->assignRef('resume', $resume);
      	$flow=&$this->get('ConfigWrapper');      
      	$this->assignRef('flow', $flow);
      	$solutions=$this->get('SolutionsPDF');      
      	$this->assignRef('solutions', $solutions); 
        
          
      	$layout = $this->getLayout();
      	
      	
          // get the document
          $document =& JFactory::getDocument();
          $document->setMimeEncoding('application/pdf');
          // set the MIME type
          JResponse::setHeader('Content-type','application/pdf');
          //JResponse::setHeader('Content-Disposition', 'attachment; filename=calcbuilder.pdf');
          JResponse::setHeader('Content-Transfer-Encoding', 'binary');
          JResponse::setHeader('Expires', '0');
          JResponse::setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
          JResponse::setHeader('Pragma', 'public');
	    parent::display($tpl);       
    }                
}

?>

