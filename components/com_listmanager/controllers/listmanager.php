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

class ListmanagerControllerListmanager extends ListmanagerController
{
        /**
         * constructor (registers additional tasks to methods)
         * @return void
         */
        function __construct(){
                parent::__construct(); 
                //$this->registerTask('showPdfFiltered','showPdf'); 
        }
 
        /**
         * display the edit form
         * @return void
         */
        function show(){ 
        	JSession::checkToken('request') or die( 'Invalid Token' );       
          JRequest::setVar( 'layout', 'table'  );
          JRequest::setVar( 'view', 'listmanager' );          
          parent::display();
        }        
        
        function save(){
        	JSession::checkToken('request') or die( 'Invalid Token' );
        	// Check security field
	        $session=JFactory::getSession();
	        $inputname=$session->get('LM_SS_225541');
	        $valss=JRequest::getVar($inputname,null);
	        if ($valss!=null){
	        	JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
	        }
          $model=$this->getModel('listmanager');
          $model->saveData();
          JRequest::setVar( 'layout', 'table'  );
          JRequest::setVar( 'view', 'listmanager' );          
          parent::display();
        }
        
		function saveForm(){
			JSession::checkToken('request') or die( 'Invalid Token' );
			// Check security field
        	$session=JFactory::getSession();
        	$inputname=$session->get('LM_SS_225541');
        	$valss=JRequest::getVar($inputname,null);
        	if ($valss!=null){
        		JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
        	}
          $model=$this->getModel('listmanager');          
          $model->saveDataForm();
          return true;          
        }
        
        function delete(){
        	JSession::checkToken('request') or die( 'Invalid Token' );
          $model=$this->getModel('listmanager');
          $model->deleteData();
          JRequest::setVar( 'layout', 'table'  );
          JRequest::setVar( 'view', 'listmanager' );          
          parent::display();
     	}
        		
}

?>
