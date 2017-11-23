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

class ListManagerControllerListing extends ListManagerController{
        /**
         * constructor (registers additional tasks to methods)
         * @return void
         */
        function __construct(){
                parent::__construct();
 
                // Register Extra tasks
                $this->registerTask( 'add' , 'edit' );
                $this->registerTask('apply','save');
                $this->registerTask('applyconfig','saveConfig');
                $this->registerTask('applylayout','savelayout');             
                $this->registerTask('applydetail','savedetail');
                $this->registerTask('applydetailpdf','savedetailpdf');
                $this->registerTask('applydetailrtf','savedetailrtf');
                $this->registerTask('applylistrtf','savelistrtf');
                $this->registerTask('applyacl','saveacl');                
        }                                                   
                
        /**
         * display the edit form
         * @return void
         */
        function edit(){
                JRequest::setVar( 'view', 'listing' );
                JRequest::setVar( 'layout', 'edit'  );
                JRequest::setVar('hidemainmenu', 1); 
                parent::display();
        }
        
        /**
         * display the edit form
         * @return void
         */
        function show(){
                JRequest::setVar( 'view', 'listing' );                
                JRequest::setVar( 'layout', 'default' );
                parent::display();
        } 

        function config(){
                JRequest::setVar( 'view', 'listing' );                
                JRequest::setVar( 'layout', 'config' );
                parent::display();
        } 
        
        function saveConfig(){
        	$model = $this->getModel('listing');
            $returnid = $model->storeConfig();
            if ($returnid) {
                $msg = JText::_( 'LIST_SAVED' );
            } else {
                $msg = JText::_( 'ERROR_SAVING_LIST' );
            }
            $task = JRequest::getVar('task');
            switch ($task){
              case 'applyconfig' :
                $link ='index.php?option=com_listmanager&controller=listing&task=config&cid[]='.$returnid;
                break;
              default :
                $link = 'index.php?option=com_listmanager&controller=listing';
                break;
            }
            $this->setRedirect($link, $msg);
        }
        
        /**
         * save a record (and redirect to main page)
         * @return void
         */
        function save()
        {
            $model = $this->getModel('listing');
            $returnid = $model->store();
            if ($returnid) {
                $msg = JText::_( 'LIST_SAVED' );
            } else {
                $msg = JText::_( 'ERROR_SAVING_LIST' );
            }            
            $task = JRequest::getVar('task');
            switch ($task){
              case 'apply' :
                $link ='index.php?option=com_listmanager&controller=listing&task=edit&cid[]='.$returnid;
                break;
              default :
                $link = 'index.php?option=com_listmanager&controller=listing';
                break;
            }
            $this->setRedirect($link, $msg);
        }
        
        /**
         * remove record(s)
         * @return void
         */
        function remove()
        {
            $model = $this->getModel('listing');
            if(!$model->delete()) {
                $msg = JText::_( 'ERROR_DELETING_LIST' );
            } else {
                $msg = JText::_( 'LIST_DELETED' );
            }
         
            $this->setRedirect( 'index.php?option=com_listmanager&controller=listing', $msg );
        }
        
        /**
         * cancel editing a record
         * @return void
         */
        function cancel()
        {
            $msg = JText::_( 'OPERATION_CALCELLED' );
            $this->setRedirect( 'index.php?option=com_listmanager&controller=listing', $msg );
        }
        
        function back(){
        	$this->setRedirect('index.php?option=com_listmanager&controller=listing&task=edit&cid[]='.JRequest::getVar('idlisting'));
        }
        
        function listback(){
        	$this->setRedirect( 'index.php?option=com_listmanager&controller=listing');
        }
        
        
         /**
         * copy list estructure
         * @return void
         */
        function copy()
        {
            $model = $this->getModel('listing');
            $returnid = $model->copyList();
            if ($returnid) {
                $msg = JText::_( 'LIST_SAVED' );
            } else {
                $msg = JText::_( 'ERROR_SAVING_LIST' );
            }
            
            $link = 'index.php?option=com_listmanager&controller=listing';
            
            
            $this->setRedirect($link, $msg);
        }
        
        
        /**
         * manage list data
         * @return void
         */
        function admindata(){
	        $model = $this->getModel('listing');
	        $returnid = $model->_id;
	        JRequest::setVar( 'idlisting', JRequest::getVar('idlisting',$model->_id));
	        
	        JRequest::setVar( 'view', 'listing' );
	        JRequest::setVar( 'layout', 'admindata'  );
	        JRequest::setVar('hidemainmenu', 1); 
	        parent::display();
        }
        
        /**
         * manage list data
         * @return void
         */
        function deletedata(){        
	        $model = $this->getModel('listing');
	        $returnid = $model->deleteRecords();
            if ($returnid) {
                $msg = JText::_( 'LIST_SAVED' );
            } else {
                $msg = JText::_( 'ERROR_SAVING_LIST' );
            }
        
                JRequest::setVar( 'cid', JRequest::getVar('idlisting') );
        
                JRequest::setVar( 'view', 'listing' );
                JRequest::setVar( 'layout', 'admindata'  );
                JRequest::setVar('hidemainmenu', 1); 
                parent::display();            
        }
        
        function deletealldata(){
        	$model = $this->getModel('listing');
	        $returnid = $model->deleteAllRecords();
            if ($returnid) {
                $msg = JText::_( 'LIST_SAVED' );
            } else {
                $msg = JText::_( 'ERROR_SAVING_LIST' );
            }
        
                JRequest::setVar( 'cid', JRequest::getVar('idlisting') );
        
                JRequest::setVar( 'view', 'listing' );
                JRequest::setVar( 'layout', 'admindata'  );
                JRequest::setVar('hidemainmenu', 1); 
                parent::display();            
        }
        
        
        /**
         * manage list data
         * @return void
         */
        function editdata()
        {
        
        $model = $this->getModel('listing');
        //JRequest::setVar( 'idlisting', JRequest::getVar('idlisting') );
        
        JRequest::setVar( 'cid[]', JRequest::getVar('idlisting') );
        
        JRequest::setVar( 'idrecord', $model->_id );
        
        JRequest::setVar( 'view', 'listing' );
        JRequest::setVar( 'layout', 'editdata'  );
        JRequest::setVar('hidemainmenu', 1); 
        parent::display();
            
        }
        
        
        /**
         * manage list data
         * @return void
         */
        function saverecord()
        {
        
        $model = $this->getModel('listing');
        $model->saveRecord();
        JRequest::setVar( 'cid', JRequest::getVar('idlisting') );
        
        JRequest::setVar( 'view', 'listing' );
        JRequest::setVar( 'layout', 'admindata'  );
        JRequest::setVar('hidemainmenu', 1); 
        parent::display();
            
        }
        
        
         /**
         * manage list data
         * @return void
         */
        function cancelrecord()
        {
        
        JRequest::setVar( 'cid', JRequest::getVar('idlisting') );
        
        $model = $this->getModel('listing');
        $model->_id=JRequest::getVar('idlisting');
        
        
        JRequest::setVar( 'idlisting', JRequest::getVar('idlisting') );
        
        JRequest::setVar( 'view', 'listing' );
        JRequest::setVar( 'layout', 'admindata'  );
        JRequest::setVar('hidemainmenu', 1); 
        parent::display();
            
            
            
           
        }
        
        /**
         * manage list data
         * @return void
         */
        function newdata()
        {
        
        $model = $this->getModel('listing');
        //JRequest::setVar( 'idlisting', JRequest::getVar('idlisting') );
        
        JRequest::setVar( 'cid[]', JRequest::getVar('idlisting') );
        
        JRequest::setVar( 'idrecord', "" );
        
        JRequest::setVar( 'view', 'listing' );
        JRequest::setVar( 'layout', 'editdata'  );
        JRequest::setVar('hidemainmenu', 1); 
        parent::display();
            
        }
        
        
        /**
         * manage list data
         * @return void
         */
        function loaddata(){        
	        $model = $this->getModel('listing');
	        JRequest::setVar( 'cid', JRequest::getVar('idlisting') );
	        JRequest::setVar( 'view', 'listing' );
	        JRequest::setVar( 'layout', 'loaddata'  );
	        JRequest::setVar('hidemainmenu', 1); 
	        parent::display();            
        }
        
		function loaddatasql(){        
	        $model = $this->getModel('listing');
	        JRequest::setVar( 'cid', JRequest::getVar('idlisting') );
	        JRequest::setVar( 'view', 'listing' );
	        JRequest::setVar( 'layout', 'loaddatasql'  );
	        JRequest::setVar('hidemainmenu', 1); 
	        parent::display();            
        }
        
        /**
         * manage list data
         * @return void
         */
        function exportdata()
        {
        
        
         //JRequest::setVar( 'cid', JRequest::getVar('idlisting') );
        $model = $this->getModel('listing');
         
        
         JRequest::setVar( 'view', 'listing' );
        JRequest::setVar( 'layout', 'exportdata'  );
        JRequest::setVar('hidemainmenu', 1); 
        parent::display();
        
        
            
        }
        
        
        
        /**
         * copy list estructure
         * @return void
         */
        function saveloaddata(){
            $model = $this->getModel('listing');            
            $model->insertDataCSV(JRequest::getVar('idlisting'));
            $msg = JText::_( 'LIST_SAVED' );            
            $link = 'index.php?option=com_listmanager&controller=listing&task=admindata&cid[]='.JRequest::getVar('idlisting');
            $this->setRedirect($link, $msg);
        }

    	function saveloaddatasql(){
            $model = $this->getModel('listing');            
            $model->insertDataSQL(JRequest::getVar('idlisting'));
            $msg = JText::_( 'LIST_SAVED' );            	            
			$link = 'index.php?option=com_listmanager&controller=listing&task=admindata&cid[]='.JRequest::getVar('idlisting');
            $this->setRedirect($link, $msg);
        }               
        
         /**
         * manage list acl
         * @return void
         */
        function configacl()
        {
        
        $model = $this->getModel('listing');
        //JRequest::setVar( 'idlisting', JRequest::getVar('idlisting') );
        $returnid = $model->_id;
        
        JRequest::setVar( 'idlisting', $returnid );
        
        //JRequest::setVar( 'cid[]', JRequest::getVar('idlisting') );                
        
        JRequest::setVar( 'view', 'listing' );
        JRequest::setVar( 'layout', 'configacl'  );
        JRequest::setVar('hidemainmenu', 1); 
        parent::display();
            
        }
        
        function saveacl()
        {
            $model = $this->getModel('listing');
            $model->saveDataACL(JRequest::getVar('idlisting'));
           
            $msg = JText::_( 'ACL_LIST_SAVED' );
        	$task = JRequest::getVar('task');
            switch ($task){
              case 'applyacl' :
                $link = 'index.php?option=com_listmanager&controller=listing&task=configacl&cid[]='.JRequest::getVar( 'idlisting' );
                break;
              default :
                $link = 'index.php?option=com_listmanager&controller=listing';
                break;            
            }
           
            $this->setRedirect($link, $msg);
        }
        
		function layout(){
            JRequest::setVar( 'view', 'listing' );
            JRequest::setVar( 'layout', 'layout'  );
            parent::display();
        }
        
        function detail(){
            JRequest::setVar( 'view', 'listing' );
            JRequest::setVar( 'layout', 'detail'  );
            parent::display();
        }
        
        function detailpdf(){
        	JRequest::setVar( 'view', 'listing' );
        	JRequest::setVar( 'layout', 'detailpdf'  );
        	parent::display();
        }
        
        function detailrtf(){
        	JRequest::setVar( 'view', 'listing' );
        	JRequest::setVar( 'layout', 'detailrtf'  );
        	parent::display();
        }
        
        function listrtf(){
        	JRequest::setVar( 'view', 'listing' );
        	JRequest::setVar( 'layout', 'listrtf'  );
        	parent::display();
        }
        
        function savelayout(){
        	$model = $this->getModel('listing');            
            $model->savelayout();
            $msg = JText::_( 'LIST_SAVED' );
            $task = JRequest::getVar('task');
            switch ($task){
              case 'applylayout' :
                $link = 'index.php?option=com_listmanager&controller=listing&task=layout&cid[]='.JRequest::getVar('idlisting');
                break;
              default :
                $link = 'index.php?option=com_listmanager&controller=listing&task=edit&cid[]='.JRequest::getVar('idlisting');
                break;            
            }
            $this->setRedirect($link, $msg);        	
        }
        
		function savedetail(){
        	$model = $this->getModel('listing');            
            $model->savedetail();
            $msg = JText::_( 'LIST_SAVED' );
            $task = JRequest::getVar('task');
            switch ($task){
              case 'applydetail' :
                $link = 'index.php?option=com_listmanager&controller=listing&task=detail&cid[]='.JRequest::getVar('idlisting');
                break;
              default :
                $link = 'index.php?option=com_listmanager&controller=listing&task=edit&cid[]='.JRequest::getVar('idlisting');
                break;            
            }
            $this->setRedirect($link, $msg);        	
        }
        
        function savedetailpdf(){
        	$model = $this->getModel('listing');
        	$model->savedetailpdf();
        	$msg = JText::_( 'LIST_SAVED' );
        	$task = JRequest::getVar('task');
        	switch ($task){
        		case 'applydetailpdf' :
        			$link = 'index.php?option=com_listmanager&controller=listing&task=detailpdf&cid[]='.JRequest::getVar('idlisting');
        			break;
        		default :
        			$link = 'index.php?option=com_listmanager&controller=listing&task=edit&cid[]='.JRequest::getVar('idlisting');
        			break;
        	}
        	$this->setRedirect($link, $msg);
        }
        
        function savedetailrtf(){
        	$model = $this->getModel('listing');
        	$model->savedetailrtf();
        	$msg = JText::_( 'RTF_FILE_SAVED' );
        	$task = JRequest::getVar('task');
        	switch ($task){
        		case 'applydetailrtf' :
        			$link = 'index.php?option=com_listmanager&controller=listing&task=detailrtf&cid[]='.JRequest::getVar('idlisting');
        			break;
        		default :
        			$link = 'index.php?option=com_listmanager&controller=listing&task=edit&cid[]='.JRequest::getVar('idlisting');
        			break;
        	}
        	$this->setRedirect($link, $msg);
        }
        
        function savelistrtf(){
        	$model = $this->getModel('listing');
        	$model->savelistrtf();
        	$msg = JText::_( 'RTF_FILE_SAVED' );
        	$task = JRequest::getVar('task');
        	switch ($task){
        		case 'applylistrtf' :
        			$link = 'index.php?option=com_listmanager&controller=listing&task=listrtf&cid[]='.JRequest::getVar('idlisting');
        			break;
        		default :
        			$link = 'index.php?option=com_listmanager&controller=listing&task=edit&cid[]='.JRequest::getVar('idlisting');
        			break;
        	}
        	$this->setRedirect($link, $msg);
        }
        
        function cancellist(){
        	$msg = JText::_( 'OPERATION_CALCELLED' );
            $this->setRedirect( 'index.php?option=com_listmanager&controller=listing&task=edit&cid[]='.JRequest::getVar('idlisting'), $msg );
        }
        
        function views(){
        	$this->setRedirect( 'index.php?option=com_listmanager&controller=lmvistas&task=show&idlisting='.JRequest::getVar('idlisting'));
        }
        
        function access(){
        	$model = $this->getModel('listing');
	        JRequest::setVar( 'cid[]', JRequest::getVar('idlisting') );
	        JRequest::setVar( 'idrecord', $model->_id );
	        JRequest::setVar( 'view', 'listing' );
	        JRequest::setVar( 'layout', 'access'  );
	        parent::display();
        	
        }
        
        function resetrating(){
        	$model = $this->getModel('listing');            
            $model->resetrating();
            $msg = JText::_( 'RATING_RESETED' );            
            $link = 'index.php?option=com_listmanager&controller=listing&task=admindata&cid[]='.JRequest::getVar('idlisting');
            $this->setRedirect($link, $msg);            	
        }
        
		function resetratingone(){
        	$model = $this->getModel('listing');            
            $model->resetratingone();
            $msg = JText::_( 'RATING_RESETED' );
            $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );            
            $link = 'index.php?option=com_listmanager&controller=listing&task=editdata&cid[]='.JRequest::getVar('idrecord').'&idlisting='.JRequest::getVar('idlisting');
            $this->setRedirect($link, $msg);            	
        }
        
        function exportlist(){
        	$model = $this->getModel('listing');
        	//JRequest::setVar( 'cid[]', JRequest::getVar('idlisting') );
	        JRequest::setVar( 'view', 'listing' );
	        JRequest::setVar( 'layout', 'exportlist'  );
	        JRequest::setVar('hidemainmenu', 1); 
	        parent::display();	        	        
        }
        
        function importlist(){
        	JRequest::setVar( 'view', 'listing' );
	        JRequest::setVar( 'layout', 'importlist'  );
	        JRequest::setVar('hidemainmenu', 1); 
	        parent::display();	        	    
        }
        
        function saveimport(){
        	$model = $this->getModel('listing');       
        	$returnid = $model->import();
            if ($returnid) {
                $msg = JText::_( 'LIST_SAVED' );
            } else {
                $msg = JText::_( 'ERROR_SAVING_LIST' );
            }
            $this->setRedirect( 'index.php?option=com_listmanager&controller=listing',$msg);            
        }
        
}

?>
