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

class AdvisorControllerProduct extends AdvisorController
{
    function __construct(){        	
    	parent::__construct();
        $this->registerTask('add','edit'); 
        $this->registerTask('apply','save');
        $this->registerTask( 'product', 'cancel' );
	}
	
	function show(){
		JRequest::setVar( 'view', 'product' );
        JRequest::setVar( 'layout', 'default' );
        parent::display();
	}
 
	function edit(){
    	JRequest::setVar( 'view', 'product' );
        JRequest::setVar( 'layout', 'edit' ); 
        parent::display();
	}
	
	function cancel(){
		$this->setRedirect('index.php?option=com_advisor&controller=product&idflow='.JRequest::getVar('idflow'));
	}
	
	function flow(){
		$this->setRedirect('index.php?option=com_advisor&controller=advisor&task=edit&cid[]='.JRequest::getVar('idflow'));
	}
	
	function save(){
    	$model = $this->getModel('product');
        $returnid = $model->store();
        if ($returnid) {
        	$msg = JText::_( 'PRODUCT_SAVED' );
        } else {
        	$msg = JText::_( 'PRODUCT_ERROR_SAVE' );
        }
        $task = JRequest::getVar('task');
        switch ($task){
        	case 'apply' :
                $link ='index.php?option=com_advisor&controller=product&task=edit&idflow='.JRequest::getVar('idflow').'&cid[]='.$returnid;
                break;
            default :
                $link = 'index.php?option=com_advisor&controller=product&idflow='.JRequest::getVar('idflow');
                break;
        }
        $this->setRedirect($link, $msg);
    }
    
	function remove(){
		$model = $this->getModel('product');
		$returnid = $model->remove();
        if ($returnid) {
        	$msg = JText::_( 'PRODUCT_DELETED' );
        } else {
        	$msg = JText::_( 'PRODUCT_ERROR_DELETE' );
        }
        $this->setRedirect('index.php?option=com_advisor&controller=product&idflow='.JRequest::getVar('idflow'),$msg);
	}
	
	function order(){
    	$model = $this->getModel('product');
        $returnid = $model->order();
        if ($returnid) {
        	$msg = JText::_( 'PRODUCT_ORDERED' );
        } else {
        	$msg = JText::_( 'PRODUCT_ERROR_ORDER' );
        }
        $task = JRequest::getVar('task');
        $link = 'index.php?option=com_advisor&controller=product&idflow='.JRequest::getVar('idflow');
        $this->setRedirect($link, $msg);
    }
}

?>
