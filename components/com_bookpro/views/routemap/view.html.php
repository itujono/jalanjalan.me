<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bookpro.php 27 2012-07-08 17:15:11Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');
AImporter::model('bustrip');
AImporter::helper('bus');
class BookProViewRoutemap extends JViewLegacy
{
	function display($tpl = null)
	{
		$this->config=JBFactory::getConfig();
		
		
		
		$input = JFactory::getApplication()->input;
		 
		$model = new BookProModelBusTrip();
		$item = $model->getItem($input->get('id'));
		 
		
		 
		$this->obj= $item;
		
		$route=explode(',', $item->route);
		
		
		
		for ($i = 0; $i < count($route); $i++) {
			
			if($item->from==$route[$i]){
				
				break;
			}else {
				
				array_shift($route);
			}
			
		}
		
		//echo $item->from;
		//var_dump($route);die;
		
		$this->destinations=BusHelper::getRouteDestination(implode(',', $route));
		
		
		
		$this->_prepare();
		
		parent::display($tpl);
	}
	private function _prepare(){
		$doc=JFactory::getDocument();
		$doc->setTitle($this->obj->title);
		
	}

	
}
