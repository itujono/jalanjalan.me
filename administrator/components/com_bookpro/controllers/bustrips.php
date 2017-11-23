
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


class BookproControllerBustrips extends JControllerAdmin
{
	/**
	 * (non-PHPdoc)
	 * @see JControllerLegacy::getModel()
	 */
	public function getModel($name = 'Bustrip', $prefix = 'BookproModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	public function getBustrip(){
		 
		$id=JFactory::getApplication()->input->get('id', 0);
 
		AImporter::model('bustrips');
		$input=JFactory::getApplication()->input;
		$agent_id=$input->get('agent_id');

		if ($agent_id) {
			
			$model = new BookProModelBusTrips();
			$state=$model->getState();
			$state->set('filter.agent_id',$agent_id);
			$state->set('filter.price',0);
			$state->set('list.limit',0);
			$lists=$model->getItems();
			
		}else{
			$lists = array();
		}
		
		$items = "";
		
		foreach ($lists as $list){
		
// 			$title = str_repeat('--', $list->level - 1). $list->title;
// 			$title.=' -('.$list->code.')';
// 			$items.= "<option value='".$list->id."'>".$title."</option>";

			$title = str_repeat('--', $list->level - 1). $list->title;
			$title.=' -('.$list->code.')';
			if($list->id==$id){
				$items.= "<option value='".$list->id."' selected>".$title."</option>";
			}else{
				$items.= "<option value='".$list->id."'>".$title."</option>";
			}
		}
		echo $items;
		return;		
	}
	
	
	
}