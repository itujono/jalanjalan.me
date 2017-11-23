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


    class BookProModelAgent extends JModelAdmin
    {
        /**
        * (non-PHPdoc)
        * @see JModelForm::getForm()
        */
        public function getForm($data = array(), $loadData = true)
        {

            $form = $this->loadForm('com_bookpro.agent', 'agent', array('control' => 'jform', 'load_data' => $loadData));
            
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
            $data = JFactory::getApplication()->getUserState('com_bookpro.edit.agent.data', array());
            if (empty($data))
                $data = $this->getItem();
            return $data;
        }
        public function getItem($pk = null)
        {
            if ($item = parent::getItem($pk))
            {
                // Convert the metadata field to an array.
                if (isset($item->metadata)){
                	$registry = new JRegistry;
                	$registry->loadString($item->metadata);
                	$item->metadata = $registry->toArray();
                }
                

                
                if (isset($item->images)){
                	
                	// Convert the images field to an array.
                	$registry = new JRegistry;
	                $registry->loadString($item->images);
	                $item->images = $registry->toArray();
                }
                if (!empty($item->id))
                {
                    $item->tags = new JHelperTags;
                    $item->tags->getTagIds($item->id, 'com_bookpro.agent');
                    $item->metadata['tags'] = $item->tags;
                }
            }

            return $item;
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


}