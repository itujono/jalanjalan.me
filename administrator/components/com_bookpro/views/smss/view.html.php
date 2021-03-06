<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 26 2012-07-08 16:07:54Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');


class BookProViewSmss extends JViewLegacy
{
    protected $items;
    	
    	protected $pagination;
    	
    	protected $state;
        function display($tpl = null)
        {

         $this->state		= $this->get('State');
			$this->sortDirection = $this->state->get('list.direction');
			$this->sortColumn = $this->state->get('list.id');

		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		$this->addToolbar();
		parent::display($tpl);
        }
        protected function getSortFields()
        {
        	return array(
        			'sms.title' => JText::_('JGRID_HEADING_ORDERING'),
        			'sms.state' => JText::_('JSTATUS'),
        			'sms.id' => JText::_('JGRID_HEADING_ID')
        	);
        }
        protected function addToolbar(){
        	JToolbarHelper::addNew('sms.add');
        	JToolbarHelper::editList('sms.edit');
        	JToolbarHelper::deleteList('sms.delete');
        }
}

?>