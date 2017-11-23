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
 
jimport( 'joomla.application.component.view' );
jimport( 'joomla.version' );

class HelperListmanagerView extends JView {
	
	public static function getActions(){       
    	jimport('joomla.access.access');
        $user   = JFactory::getUser();
        $result = new JObject; 
        $assetName = 'com_listmanager';
        $actions = JAccess::getActions('com_listmanager', 'component');
        foreach ($actions as $action) {
         	$result->set($action->name, $user->authorise($action->name, $assetName));
        } 
        return $result;
	}
	
	function __getCSS(){
		$version=new JVersion();
		$ret=array();
		$ret[]=JURI::base().'components/com_listmanager/assets/css/default.css';
		$ret[]=JURI::base().'components/com_listmanager/assets/css/bootstrap-responsive.min.css';
		$ret[]=JURI::base().'components/com_listmanager/assets/css/bootstrap.css';
		return $ret;		
	}

	function __getJS(){
		$version=new JVersion();
		$ret=array();		
		if (version_compare($version->getShortVersion(), '3.0.0') < 0){			
			$ret[]='http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js';			
			$ret[]=JURI::base().'components/com_listmanager/assets/js/jquery.noConflict.js';
			$ret[]=JURI::base().'components/com_listmanager/assets/js/jquery-ui-1.10.0.custom.min.js';
			$ret[]=JURI::base().'components/com_listmanager/assets/js/bootstrap.min.js';
		} else {
			$ret[]=JURI::base().'components/com_listmanager/assets/js/jquery-ui-1.10.0.custom.min.js';
		}
		return $ret;		
	}
	
	function __getVersion(){
		$version=new JVersion();
		$arr_version=explode('.', $version->getShortVersion());
		return $arr_version[0];
	}
	
	function __getHelp($page,$elements=null){		
		$componentParams = JComponentHelper::getParams('com_listmanager');
		$param = $componentParams->get('helper', '0');
		if ($param=='0'){
			$function='__getHelpPage'.$page;
			return $this->$function($page,$elements);
		} else {
			return '';
		}
	}
	
	private function __getHelpPageMAIN($page,$elements){
		$content=array();
		$content[]=JText::_('HELP_MAIN_EDIT');
		$content[]=JText::_('HELP_MAIN_MANAGE_DATA');
		$content[]=JText::_('HELP_MAIN_CONFIG_ACL');
		$content[]=JText::_('HELP_MAIN_CONFIG_PDF_EXCEL');
		$content[]=JText::_('HELP_MAIN_EXPORT_DATA');
		$content[]=JText::_('HELP_MAIN_ACCESS_DATA');
		$result=$this->__createContainer('header');
		$result.=$this->__createTitle(JText::_('HELP_'.$page.'_TITLE'));
		$result.=$this->__createTable('header');
		$counter=0;		
		foreach ($elements as $name=>$img){
			$result.=$this->__createTableRow($img,$name,$content[$counter]);
			$counter++;	
		}		
		$result.=$this->__createTable('footer');
		$result.=$this->__createContainer('footer');
		return $result;
	}
	
	private function __getHelpPageEDIT($page,$elements){
		$result=$this->__createContainer('header');
		$result.=$this->__createTitle(JText::_('HELP_'.$page.'_TITLE_FIELDS'));
		$result.=$this->__createTable('header');
		$counter=0;		
		foreach ($elements['fields'] as $name){
			$result.=$this->__createTableRowWOImg(JText::_($name),JText::_('HELP_'.$page.'_'.$name));
			$counter++;		
		}	
		$result.=$this->__createTable('footer');
		$result.=$this->__createTitle(JText::_('HELP_'.$page.'_TITLE_TYPE'));
		$result.=$this->__createTable('header');
		$counter=0;		
		foreach ($elements['types'] as $name){
			$result.=$this->__createTableRowWOImg(JText::_($name),JText::_('HELP_'.$page.'_'.$name));
			$counter++;		
		}	
		$result.=$this->__createTable('footer');
		$result.=$this->__createContainer('footer');
		return $result;
	}
	
	private function __getHelpPageLAYOUT($page,$elements){
		$result=$this->__createContainer('header');
		$result.=$this->__createTitle(JText::_('HELP_'.$page.'_TITLE'));
		$result.=$this->__createTable('header');
		$result.=$this->__createContent(JText::_('HELP_'.$page.'_CONTENT'));
		$result.=$this->__createTable('footer');
		$result.=$this->__createContainer('footer');
		return $result;
	}
	
	private function __getHelpPageDETAIL($page,$elements){
		$result=$this->__createContainer('header');
		$result.=$this->__createTitle(JText::_('HELP_'.$page.'_TITLE'));
		$result.=$this->__createTable('header');
		$result.=$this->__createContent(JText::_('HELP_'.$page.'_CONTENT'));
		$result.=$this->__createTable('footer');
		$result.=$this->__createContainer('footer');
		return $result;
	}
	
	
	/** Views **/
	/*private function __getHelpPageVIEW($page,$elements){
		$result=$this->__createContainer('header');
		$result.=$this->__createTitle(JText::_('HELP_'.$page.'_TITLE'));
		$result.=$this->__createTable('header');
		$result.=$this->__createTable('footer');
		$result.=$this->__createContainer('footer');
		return $result;
	}*/
	
	private function __getHelpPageVIEW_EDIT($page,$elements){
		$result=$this->__createContainer('header');
		$result.=$this->__createTitle(JText::_('HELP_'.$page.'_TITLE'));
		$result.=$this->__createTable('header');
		$counter=0;		
		foreach ($elements as $name){
			$result.=$this->__createTableRowWOImg(JText::_($name),JText::_('HELP_EDIT_'.$name));
			$counter++;		
		}	
		$result.=$this->__createTable('footer');
		$result.=$this->__createContainer('footer');
		return $result;
	}
	
	/** Config **/
	private function __getHelpPageCONFIG($page,$elements){
		$result=$this->__createContainer('header');
		$result.=$this->__createTitle(JText::_('HELP_'.$page.'_TITLE'));
		$result.=$this->__createTable('header');
		$counter=0;		
		foreach ($elements as $name){
			$result.=$this->__createTableRowWOImg(JText::_($name),JText::_('HELP_EDIT_'.$name));
			$counter++;		
		}
		$result.=$this->__createTable('footer');
		$result.=$this->__createContainer('footer');
		return $result;
	}
	
	private function __getHelpPageCONFIG_ACL($page,$elements){
		$result=$this->__createContainer('header');
		$result.=$this->__createTitle(JText::_('HELP_'.$page.'_TITLE'));
		$result.=$this->__createContent(JText::_('HELP_'.$page.'_CONTENT'));
		$result.=$this->__createContainer('footer');
		return $result;
	}
	
	/** Data **/	
	/*private function __getHelpPageADMIN_DATA($page,$elements){
		$result=$this->__createContainer('header');
		$result.=$this->__createTitle(JText::_('HELP_'.$page.'_TITLE'));
		$result.=$this->__createTable('header');
		$result.=$this->__createTable('footer');
		$result.=$this->__createContainer('footer');
		return $result;
	}*/
	
	/*private function __getHelpPageEDIT_DATA($page,$elements){
		$result=$this->__createContainer('header');
		$result.=$this->__createTitle(JText::_('HELP_'.$page.'_TITLE'));
		$result.=$this->__createTable('header');
		$result.=$this->__createTable('footer');
		$result.=$this->__createContainer('footer');
		return $result;
	}*/
	
	/** Access **/	
	private function __getHelpPageACCESS($page,$elements){
		$result=$this->__createContainer('header');
		$result.=$this->__createTitle(JText::_('HELP_'.$page.'_TITLE'));
		$result.=$this->__createContent(JText::_('HELP_'.$page.'_CONTENT'));
		$result.=$this->__createContainer('footer');
		return $result;
	}
	/*
	private function __getHelpPageACCESS_EDIT($page,$elements){
		$result=$this->__createContainer('header');
		$result.=$this->__createTitle(JText::_('HELP_'.$page.'_TITLE'));
		$result.=$this->__createContent(JText::_('HELP_'.$page.'_CONTENT'));
		$result.=$this->__createContainer('footer');
		return $result;
	}*/
	
	/** Load Data **/	
	private function __getHelpPageLOAD_DATA($page,$elements){
		$result=$this->__createContainer('header');
		$result.=$this->__createTitle(JText::_('HELP_'.$page.'_TITLE'));
		$result.=$this->__createContent(JText::_('HELP_'.$page.'_CONTENT'));
		$result.=$this->__createContainer('footer');
		return $result;
	}
	
	private function __getHelpPageLOAD_SQL($page,$elements){
		$result=$this->__createContainer('header');
		$result.=$this->__createTitle(JText::_('HELP_'.$page.'_TITLE'));
		$result.=$this->__createContent(JText::_('HELP_'.$page.'_CONTENT'));
		$result.=$this->__createContainer('footer');
		return $result;
	}
	
	
	
	private function __createContainer($type){
		$result='';
		switch($type){
			case 'header':
				$result.='<script type="text/javascript" src="'.JURI::base().'components/com_listmanager/assets/js/listmanager_admin.js"></script>';
				$result.='<div id="lm_open" class="lm_help_open">'.JText::_('LM_OPEN_HELP').'</div>';				
				$result.='<div id="lm_help" class="lm_help" style="display:none">';
				$result.='<div class="lm_help_content">';
				break;
			case 'footer':
				$result.='</div></div>';
				break;
				
		}		
		return $result;
	}
	
	private function __createTitle($content){
		$result='<div class="lm_help_title">'.$content.'</div>';
		return $result;
	}
	
	private function __createContent($content){
		$result='<div class="lm_help_content">'.$content.'</div>';
		return $result;
	}
	
	private function __createTable($type){
		$result='';
		switch($type){
			case 'header':
				$result.='<table>';
				break;
			case 'footer':
				$result.='</table>';
				break;
				
		}		
		return $result;
	}
	
	private function __createTableRowWOImg($element_name,$content){
		$result='<tr><td class="lm_table_name">'.$element_name.'</td>';
		$result.='<td class="lm_table_content">'.$content.'</td></tr>';
		return $result;
	}
	
	private function __createTableRow($img,$element_name,$content){
		$result='<tr><td class="lm_table_img"><img src="'.$img.'"/></td>';
		$result.='<td class="lm_table_name">'.$element_name.'</td>';
		$result.='<td class="lm_table_content">'.$content.'</td></tr>';
		return $result;
	}
	
}

?>
