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
        $this->registerTask('add','edit'); 
        $this->registerTask('apply','save');
        $this->registerTask( 'unpublish', 'publish' ); 
        
        
        
        
	}
 
	function edit(){
    	JRequest::setVar( 'view', 'advisor' );
        JRequest::setVar( 'layout', 'edit' );
        //JRequest::setVar('hidemainmenu', 1); 
        parent::display();
	}
	
	function flow(){
		$this->setRedirect('index.php?option=com_advisor&controller=advisor');
	}
	
	function step(){
		$this->setRedirect('index.php?option=com_advisor&controller=step&idflow='.JRequest::getVar('idflow'));
	}
	
	function product(){
		$this->setRedirect('index.php?option=com_advisor&controller=product&idflow='.JRequest::getVar('idflow'));
	}
	
	function solution(){
		$this->setRedirect('index.php?option=com_advisor&controller=solution&idflow='.JRequest::getVar('idflow'));
	}
	
	function remove(){
		$model = $this->getModel('advisor');
		$returnid = $model->remove();
        if ($returnid) {
        	$msg = JText::_( 'FLOW_DELETED' );
        } else {
        	$msg = JText::_( 'FLOW_ERROR_DELETE' );
        }
        $this->setRedirect('index.php?option=com_advisor&controller=advisor',$msg);
	}
	
	function publish(){
		$model = $this->getModel('advisor');
		$publish=0;
		if( $this->getTask() == 'publish') $publish = 1;
		$returnid = $model->publish($publish);
        if ($returnid) {
        	if($publish==0) $msg = JText::_( 'FLOW_PUBLISHED' );
        	else $msg = JText::_( 'FLOW_UNPUBLISHED' );
        } else {
        	$msg = JText::_( 'FLOW_ERROR_PUBLISH' );
        }
        $this->setRedirect('index.php?option=com_advisor&controller=advisor');
	}
	
	function save(){
    	$model = $this->getModel('advisor');
        $returnid = $model->store();
        if ($returnid) {
        	$msg = JText::_( 'FLOW_SAVED' );
        } else {
        	$msg = JText::_( 'FLOW_ERROR_SAVE' );
        }
        $task = JRequest::getVar('task');
        switch ($task){
        	case 'apply' :
                $link ='index.php?option=com_advisor&controller=advisor&task=edit&cid[]='.$returnid;
                break;
            default :
                $link = 'index.php?option=com_advisor&controller=advisor';
                break;
        }
        $this->setRedirect($link, $msg);
    }
    
	function export(){
         JRequest::setVar( 'view', 'advisor' );
         JRequest::setVar( 'layout', 'export'  );                
         parent::display();
    }
	function import(){
         JRequest::setVar( 'view', 'advisor' );
         JRequest::setVar( 'layout', 'import'  );                
         parent::display();
    }
	function importsave(){
         $model = $this->getModel('advisor'); 
         $returnid = $model->importsave();         
		 if ($returnid) {
        	$msg = JText::_( 'FLOW_SAVED' );
         } else {
        	$msg = JText::_( 'FLOW_ERROR_SAVE' );
         }
         $link = 'index.php?option=com_advisor&controller=advisor';
         $this->setRedirect($link, $msg);
    }
    
    
  function stats(){
         JRequest::setVar( 'view', 'advisor' );
         JRequest::setVar( 'layout', 'stats'  );                
         parent::display();
    }  
    
    function removestats(){
         JRequest::setVar( 'view', 'advisor' );
         JRequest::setVar( 'layout', 'stats'  );    
         JRequest::setVar( 'removestats', '1'  );             
         parent::display();
    
    
    }
    
    	function exportcsv(){
         JRequest::setVar( 'view', 'advisor' );
         JRequest::setVar( 'layout', 'exportcsv'  );                
         parent::display();
    }
    
}

?>
