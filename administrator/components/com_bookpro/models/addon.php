<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: controller.php 104 2012-08-29 18:01:09Z quannv $
 **/

defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class BookProModelAddon extends JModelAdmin
{
	/**
	 * (non-PHPdoc)
	 * @see JModelForm::getForm()
	 */
	public function getForm($data = array(), $loadData = true)
	{

		$form = $this->loadForm('com_bookpro.addon', 'addon', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		return false;
		return $form;
	}

	/**
	 * (non-PHPdoc)
	 * @see JModelForm::loadFormData()
	 */
	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_bookpro.edit.addon.data', array());
		if (empty($data))
		$data = $this->getItem();
		return $data;
	}

	public function publish(&$pks, $value = 1)
	{
		$user = JFactory::getUser();
		$table = $this->getTable();
		$pks = (array) $pks;

		// Attempt to change the state of the records.
		if (!$table->publish($pks, $value, $user->get('id')))
		{
			$this->setError($table->getError());

			return false;
		}

		return true;
	}
	function unpublish($cids){
		return $this->state('state', $cids, 0, 1);
	}

	public function getItem($pk = null){
		$item = parent::getItem($pk);
		$data = $item->params;
		if ($item)
		{
			$item->type  		= isset($item->params['type'])?$item->params['type']:null;
			$item->unit  		= isset($item->params['unit'])?$item->params['unit']:null;
		}
		return $item;
	}

	public function save($data)
	{
		$app = JFactory::getApplication();
		if ($app->input->get('task') == 'save' || $app->input->get('task') =="apply")
		{
			$object->type 		= $data['type'];
			$object->unit 		= $data['unit'];
			$data['params']		= json_encode($object);
		}


		return parent::save($data);
	}

}