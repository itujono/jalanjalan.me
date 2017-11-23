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

require JPATH_COMPONENT_ADMINISTRATOR.'/views/wrapper.view.php';

class AdvisorViewMain extends wrapperMainView
{
  function display($tpl = null){  	
  	$helper=JComponentHelper::getComponent('com_advisor');
	$registry=$helper->params;
	$isHelper=$registry->get('helper');
	if ($isHelper==1){  	
	  	$helper=$this->get('_helperContent');
	  	$this->assignRef('helper_content', $helper);
	}
  	parent::display($tpl);  	
  }  
  
	function __getCSS(){
		$version=new JVersion();
		$ret=array();
		if (version_compare($version->getShortVersion(), '3.0.0') >= 0)
			$ret[]=JURI::base().'components/com_advisor/assets/css/default.css';
		else
			$ret[]=JURI::base().'components/com_advisor/assets/css/default2.css';
		
		return $ret;
		
	}
	
	function __getVersion(){
		$version=new JVersion();
		$arr_version=explode('.', $version->getShortVersion());
		return $arr_version[0];
	}
     
}

?>
