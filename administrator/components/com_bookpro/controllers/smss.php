<?php
/**
 * @version     1.0.0
 * @package     com_bookpro
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ngo <quannv@gmail.com> - http://joombooking.com
 */

// No direct access.
defined('_JEXEC') or die;

class BookproControllerSmss extends JControllerAdmin
{
	

	public function getModel($name = 'Sms', $prefix = 'BookProModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
    
    
    
}