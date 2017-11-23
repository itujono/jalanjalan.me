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

AImporter::helper('route', 'bookpro','bus');

class BookProViewSeattemplates extends JViewLegacy
{
   
    var $items;
    
 
    var $pagination;
    
   
    function display($tpl = null)
    {
        
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->addToolbar();
        parent::display($tpl);
    }
    protected function addToolbar()
    {
    	JToolBarHelper::title(JText::_('COM_BOOKPRO_BUSSTATION_MANAGER'), 'user.png');
    	JToolBarHelper::addNew('seattemplate.add');
    	JToolBarHelper::editList('seattemplate.edit');
    	JToolBarHelper::divider();
    	JToolbarHelper::deleteList('', 'seattemplates.delete');
    		
    }
  
}

?>