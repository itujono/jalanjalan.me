<?php

/**
 * @version     1.0.0
 * @package     com_bookpro
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ngo <quannv@gmail.com> - http://joombooking.com
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Bookpro.
 */
class BookproViewBuses extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }


        $this->addToolbar();

     
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
        require_once JPATH_COMPONENT . '/helpers/bookpro.php';

        $state = $this->get('State');
        $canDo = BookproHelper::getActions($state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_BOOKPRO_BUSES'), 'car');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/bus';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('bus.add', 'JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('bus.edit', 'JTOOLBAR_EDIT');
            }
        }
			
        
        JToolbarHelper::publish('buses.publish', 'JTOOLBAR_PUBLISH', true);
        JToolbarHelper::unpublish('buses.unpublish', 'JTOOLBAR_UNPUBLISH', true);

              
         
        
        JToolBarHelper::deleteList('', 'buses.delete', 'JTOOLBAR_DELETE');
     	

        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_bookpro&view=buses');

        $this->extra_sidebar = '';
        
 

    }

	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.agent_id' => JText::_('COM_BOOKPRO_BUSES_AGENT_ID'),
		'a.title' => JText::_('COM_BOOKPRO_BUSES_TITLE'),
		'a.seat' => JText::_('COM_BOOKPRO_BUSES_SEAT'),
		'a.state' => JText::_('JSTATUS'),
		'a.code' => JText::_('COM_BOOKPRO_BUSES_CODE'),
		);
	}

}
