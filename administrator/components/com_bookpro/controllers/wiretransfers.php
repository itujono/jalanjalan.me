<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_weblinks
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class BookproControllerWiretransfers extends JControllerAdmin {


    public function getModel($name = 'wiretransfer', $prefix = 'BookproModel', $config = array('ignore_request' => true)) {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }
    
    
    	public function changewire_status()
	{
		$input = JFactory::getApplication()->input;
        
        $wireid = $input->getVar('wireid','','int');
        
        $wire_status = $input->getVar('wire_status','','string');
        
		if ($wireid and $wire_status)
		{
			$db = JFactory::getDbo();
            $query = $db->getQuery(true);
          
            $fields = array(
                $db->quoteName('wire_status') . ' = ' . $db->quote($wire_status)
            );
           
            $conditions = array(
                $db->quoteName('id') . ' = '.$wireid);
            $query->update($db->quoteName('#__bookpro_wiretransfer'))->set($fields)->where($conditions);
            $db->setQuery($query);
             $db->execute();
            
            echo JText::_('success');
		}else {
		      echo JText::_('error');
		}
        
        die;
	}

}