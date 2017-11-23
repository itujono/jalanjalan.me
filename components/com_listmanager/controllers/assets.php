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

class ListmanagerControllerAssets extends ListmanagerController
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
        function js(){        
          JRequest::setVar( 'view', 'assets' );  
          JRequest::setVar( 'layout', 'default'  );                  
          parent::display();
        }
		function css(){        
          JRequest::setVar( 'view', 'assets' );  
          JRequest::setVar( 'layout', 'css'  );                  
          parent::display();
        }
		function formjs(){        
          JRequest::setVar( 'view', 'assets' );  
          JRequest::setVar( 'layout', 'formjs'  );                  
          parent::display();
        }
		function compjs(){        
          JRequest::setVar( 'view', 'assets' );  
          JRequest::setVar( 'layout', 'compjs'  );                  
          parent::display();
        }
		function filterjs(){        
          JRequest::setVar( 'view', 'assets' );  
          JRequest::setVar( 'layout', 'filterjs'  );                  
          parent::display();
        }    
        function searchjs(){        
          JRequest::setVar( 'view', 'assets' );  
          JRequest::setVar( 'layout', 'searchjs'  );                  
          parent::display();
        }        
}

?>
