<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: controller.php 104 2012-08-29 18:01:09Z quannv $
 **/
// No direct access to this file

defined('_JEXEC') or die;


class BookproControllerAddons extends JControllerAdmin
{
	/**
	 * (non-PHPdoc)
	 * @see JControllerLegacy::getModel()
	 */
	public function getModel($name = 'Addon', $prefix = 'BookproModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	
	
	
}