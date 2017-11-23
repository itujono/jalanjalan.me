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
 

class AdvisorControllerAdvisor extends AdvisorController
{
        function __construct(){
                parent::__construct(); 
        }
 
        function nextstep(){
          JRequest::setVar( 'layout', 'nextstep'  );
          JRequest::setVar( 'view', 'advisor' );          
          parent::display();
        }
		
		function rewind(){
          JRequest::setVar( 'layout', 'rewind'  );
          JRequest::setVar( 'view', 'advisor' );          
          parent::display();
        }
		
		function config(){
          JRequest::setVar( 'layout', 'config'  );
          JRequest::setVar( 'view', 'advisor' );          
          parent::display();
        }
		function epdf(){
          JRequest::setVar( 'layout', 'epdf'  );
          JRequest::setVar( 'view', 'advisor' );          
          parent::display();
        }        
        
}

?>
