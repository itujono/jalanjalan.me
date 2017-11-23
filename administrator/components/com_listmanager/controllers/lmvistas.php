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

class ListManagerControllerLmvistas extends ListManagerController{
        /**
         * constructor (registers additional tasks to methods)
         * @return void
         */
        function __construct(){
                parent::__construct();
 
                // Register Extra tasks
                $this->registerTask( 'add' , 'edit' );
                $this->registerTask('apply','save');    
                $this->registerTask('applydetail','savedetail');
                $this->registerTask('applydetailpdf','savedetailpdf');
        }                                                   
        
        /**
         * display the edit form
         * @return void
         */
        function show(){
                JRequest::setVar( 'view', 'lmvistas' );                
                JRequest::setVar( 'layout', 'default' );
                parent::display();
        } 
        
		function edit(){
                JRequest::setVar( 'view', 'lmvistas' );
                JRequest::setVar( 'layout', 'edit'  );
                JRequest::setVar('hidemainmenu', 1); 
                parent::display();
        }
        
		function cancel() {
            $msg = JText::_( 'OPERATION_CALCELLED' );
            $this->setRedirect( 'index.php?option=com_listmanager&controller=lmvistas&task=show&idlisting='.JRequest::getVar('idlisting'), $msg );
        }
        
        function back(){
        	$this->setRedirect( 'index.php?option=com_listmanager&controller=listing&task=edit&cid[]='.JRequest::getVar('idlisting'));
        }
        function cancellist() {
           	$msg = JText::_( 'OPERATION_CALCELLED' );
            $this->setRedirect( 'index.php?option=com_listmanager&controller=listing&task=edit&cid[]='.JRequest::getVar('idlisting'), $msg );
        }
        
		function save(){
        	$model = $this->getModel('lmvistas');            
            $idview=$model->save();
            $msg = JText::_( 'VIEW_SAVED' );
            $task = JRequest::getVar('task');
            switch ($task){
              case 'apply' :
                $link = 'index.php?option=com_listmanager&controller=lmvistas&task=edit&cid[]='.$idview.'&idlisting='.JRequest::getVar('idlisting');
                break;
              default :
                $link = 'index.php?option=com_listmanager&controller=lmvistas&task=show&idlisting='.JRequest::getVar('idlisting');
                break;            
            }
            $this->setRedirect($link, $msg);        	
        }
        
		function delete(){
            $model = $this->getModel('lmvistas');
            if(!$model->delete()) {
                $msg = JText::_( 'ERROR_DELETING_VIEW' );
            } else {
                $msg = JText::_( 'VIEW_DELETED' );
            }
         
            $this->setRedirect( 'index.php?option=com_listmanager&controller=lmvistas&task=show&idlisting='.JRequest::getVar('idlisting'), $msg );
        }
        
        function detail(){
        	JRequest::setVar( 'view', 'lmvistas' );
        	JRequest::setVar( 'layout', 'detail'  );
        	parent::display();
        }
        
        function savedetail(){
        	$model = $this->getModel('lmvistas');
        	$iddetail=$model->savedetail();
        	$msg = JText::_( 'VIEW_SAVED' );
        	$task = JRequest::getVar('task');
        	switch ($task){
        		case 'applydetail' :
        			$link = 'index.php?option=com_listmanager&controller=lmvistas&task=detail&cid[]='.$iddetail.'&idlisting='.JRequest::getVar('idlisting');
        			break;
        		default :
        			$link = 'index.php?option=com_listmanager&controller=lmvistas&task=edit&cid[]='.$iddetail.'&idlisting='.JRequest::getVar('idlisting');
        			break;
        	}
        	$this->setRedirect($link, $msg);
        }
        
        function canceldetail() {
        	$msg = JText::_( 'OPERATION_CALCELLED' );
        	$this->setRedirect( 'index.php?option=com_listmanager&controller=lmvistas&task=edit&cid[]='.JRequest::getVar('idview').'&idlisting='.JRequest::getVar('idlisting'), $msg );
        }
        
		function detailpdf(){
        	JRequest::setVar( 'view', 'lmvistas' );
        	JRequest::setVar( 'layout', 'detailpdf'  );
        	parent::display();
        }
        
        function savedetailpdf(){
        	$model = $this->getModel('lmvistas');
        	$iddetail=$model->savedetailpdf();
        	$msg = JText::_( 'VIEW_SAVED' );
        	$task = JRequest::getVar('task');
        	switch ($task){
        		case 'applydetailpdf' :
        			$link = 'index.php?option=com_listmanager&controller=lmvistas&task=detailpdf&cid[]='.$iddetail.'&idlisting='.JRequest::getVar('idlisting');
        			break;
        		default :
        			$link = 'index.php?option=com_listmanager&controller=lmvistas&task=edit&cid[]='.$iddetail.'&idlisting='.JRequest::getVar('idlisting');
        			break;
        	}
        	$this->setRedirect($link, $msg);
        }
        
        function canceldetailpdf() {
        	$msg = JText::_( 'OPERATION_CALCELLED' );
        	$this->setRedirect( 'index.php?option=com_listmanager&controller=lmvistas&task=edit&cid[]='.JRequest::getVar('idview').'&idlisting='.JRequest::getVar('idlisting'), $msg );
        }
                
}

?>
