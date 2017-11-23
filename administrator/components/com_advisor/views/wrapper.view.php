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
 
jimport( 'joomla.application.component.view' );
require 'main.view.php';

class wrapperMainView extends AdvisorMainView{
	private static $COMPNAME='com_advisor'; 
	function display($tpl = null){
		$format=JRequest::getVar('format','html');
		if ($format=='html'){		
			$document =JFactory::getDocument();                                                                       
			$css=$this->__getCSS();          
			foreach ($css as $style){                                                        
				$document->addStyleSheet($style);
			}
			$js=$this->__getJS();
			foreach ($js as $jscript){                                                        
				echo '<script src="'.$jscript.'"></script>'; 
			}		
			$helper=JComponentHelper::getComponent('com_advisor');
			$registry=$helper->params;
			$isHelper=$registry->get('helper');
			if ($isHelper==1){  	
			  	$helper=$this->get('_helperContent');
			  	$this->assignRef('helper_content', $helper);
			}
		}
		parent::display($tpl);
  	}	
  	
	private function __getCSS(){
		$version=new JVersion();
		$ret=array();
		$ret[]=JURI::base().'components/'.wrapperMainView::$COMPNAME.'/assets/css/default.css';
		/*$ret[]=JURI::base().'components/'.wrapperMainView::$COMPNAME.'/assets/css/bootstrap-responsive.min.css';
		$ret[]=JURI::base().'components/'.wrapperMainView::$COMPNAME.'/assets/css/bootstrap.css';*/
		return $ret;		
	}
	
	private function __getJS(){
		$version=new JVersion();
		$ret=array();		
		/*if (version_compare($version->getShortVersion(), '3.0.0') < 0){			
			$ret[]='http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js';
			$ret[]=JURI::base().'components/'.wrapperMainView::$COMPNAME.'/assets/js/jquery.noConflict.js';
			$ret[]=JURI::base().'components/'.wrapperMainView::$COMPNAME.'/assets/js/jquery-ui-1.10.0.custom.min.js';
			$ret[]=JURI::base().'components/'.wrapperMainView::$COMPNAME.'/assets/js/bootstrap.min.js';
		} else {
			$ret[]=JURI::base().'components/'.wrapperMainView::$COMPNAME.'/assets/js/jquery-ui-1.10.0.custom.min.js';
		}*/
		$ret[]=JURI::base().'components/'.wrapperMainView::$COMPNAME.'/assets/js/jquery-ui-1.10.0.custom.min.js';
		return $ret;		
	}	
	
	/**
	* Get the actions
    */
    public static function getActions(){       
    	jimport('joomla.access.access');
        $user   = JFactory::getUser();
        $result = new JObject; 
        $assetName = 'com_advisor';
        $actions = JAccess::getActions($assetName, 'component');
        foreach ($actions as $action) {
         	$result->set($action->name, $user->authorise($action->name, $assetName));
        } 
        return $result;
	}
}

?>