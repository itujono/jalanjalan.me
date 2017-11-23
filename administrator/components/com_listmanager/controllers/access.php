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

class ListManagerControllerAccess extends ListManagerController{
        /**
         * constructor (registers additional tasks to methods)
         * @return void
         */
        function __construct(){
                parent::__construct();
 
                // Register Extra tasks
                $this->registerTask( 'add' , 'edit' );
                $this->registerTask('apply','save');                            
        }                                                   
        
        /**
         * display the edit form
         * @return void
         */
        function show(){
                JRequest::setVar( 'view', 'access' );                
                JRequest::setVar( 'layout', 'default' );
                parent::display();
        } 
        
		function edit(){
                JRequest::setVar( 'view', 'access' );
                JRequest::setVar( 'layout', 'edit'  );
                parent::display();
        }
        
		function cancel() {
            $this->setRedirect( 'index.php?option=com_listmanager&controller=access&task=show&idlisting='.JRequest::getVar('idlisting'));
        }
        
		function download() {
            JRequest::setVar( 'view', 'access' );
            JRequest::setVar( 'layout', 'download');
            parent::display();
        }
        
		function cancellist(){			
        	$this->back();
        }
        function back(){
        	$this->setRedirect( 'index.php?option=com_listmanager&controller=listing');
        }
        
        
        
}

?>
