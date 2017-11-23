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

class ListmanagerControllerServerpages extends ListmanagerController
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
          JRequest::setVar( 'view', 'serverpages' );  
          JRequest::setVar( 'layout', 'table'  );                  
          parent::display();
        }  

		function showPdf(){
			JSession::checkToken('request') or die( 'Invalid Token' );
          JRequest::setVar( 'layout', 'pdf'  );
          JRequest::setVar( 'view', 'serverpages' );          
          parent::display();
        }        
        
        function showRtf(){
        	JSession::checkToken('request') or die( 'Invalid Token' );
        	JRequest::setVar( 'layout', 'rtf'  );
        	JRequest::setVar( 'view', 'serverpages' );
        	parent::display();
        }
        
        function showExcel(){
        	JSession::checkToken('request') or die( 'Invalid Token' );
          JRequest::setVar( 'layout', 'excel'  );
          JRequest::setVar( 'view', 'serverpages' );          
          parent::display();
        }
        
        function showExcelFiltered(){
        	JSession::checkToken('request') or die( 'Invalid Token' );
          JRequest::setVar( 'layout', 'excelfiltered'  );
          JRequest::setVar( 'view', 'serverpages' );          
          parent::display();
        }
        
        function showPdfFiltered(){
        	JSession::checkToken('request') or die( 'Invalid Token' );
          JRequest::setVar( 'layout', 'pdffiltered'  );
          JRequest::setVar( 'view', 'serverpages' );          
          parent::display();
        }
        
        function showrtfFiltered(){
        	JSession::checkToken('request') or die( 'Invalid Token' );
        	JRequest::setVar( 'layout', 'rtffiltered'  );
        	JRequest::setVar( 'view', 'serverpages' );
        	parent::display();
        }
		function sendEmail(){		
			JSession::checkToken('request') or die( 'Invalid Token' );  
          JRequest::setVar( 'layout', 'email'  );
          JRequest::setVar( 'view', 'serverpages' );          
          parent::display();
        }
        
        function sendEmailFiltered(){
        	JSession::checkToken('request') or die( 'Invalid Token' );
          JRequest::setVar( 'layout', 'emailfiltered'  );
          JRequest::setVar( 'view', 'serverpages' );          
          parent::display();
          
        }
        
        function detailpdf(){
        	JSession::checkToken('request') or die( 'Invalid Token' );
        	JRequest::setVar( 'layout', 'detailpdf');
        	JRequest::setVar( 'view', 'serverpages' );
        	parent::display();
        }
        
        function detailrtf(){
        	JSession::checkToken('request') or die( 'Invalid Token' );
        	JRequest::setVar( 'layout', 'detailrtf');
        	JRequest::setVar( 'view', 'serverpages' );
        	parent::display();
        }
        
        function bulk(){
        	JSession::checkToken('request') or die( 'Invalid Token' );
			// Check security field
        	$session=JFactory::getSession();
        	$inputname=$session->get('LM_SS_225541');
        	$valss=JRequest::getVar($inputname,null);
        	$delete=JRequest::getVar('delete','0');
        	if ($valss!=null){
        		JError::raiseError(500,JText::_('INTERNAL SERVER ERROR'));
        	}
	          $model=$this->getModel('serverpages');          
	          if ($delete!='0')
	          	$model->deleteBulk();
	          else
	          $model->saveBulk();
	          return true;  
        }
        
        function detail(){
        	JRequest::setVar( 'layout', 'detail'  );
        	JRequest::setVar( 'view', 'serverpages' );
        	parent::display();
        }
        
       
}

?>
