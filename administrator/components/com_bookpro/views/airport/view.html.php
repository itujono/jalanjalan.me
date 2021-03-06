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

AImporter::model('airport',"countries");
AImporter::helper('bookpro', 'airport');

class BookProViewAirport extends JViewLegacy
{
	function display($tpl = null)
	{
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Prepare to display page.
	 *
	 * @param string $tpl name of used template
	 * @param TableCustomer $customer
	 * @param JUser $user
	 */
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);
		JToolBarHelper::title(JText::_('Destination Edit'), 'location');
		
		
		
		JToolBarHelper::apply('airport.apply');
		JToolBarHelper::save('airport.save');
		
		JToolBarHelper::cancel('airport.cancel');
		
	
		
	}
	function _displayForm($tpl, $airport)
	{
		$document = &JFactory::getDocument();
		/* @var $document JDocument */

		$error = JRequest::getInt('error');
		$data = JRequest::get('post');
		
		JFilterOutput::objectHTMLSafe($airport);
		$document->setTitle(BookProHelper::formatName($airport));
		/* @var $params JParameter */
		$countrybox=BookProHelper::getCountrySelect($airport->country_id);
		$this->countries=$countrybox;
		//$this->assignRef("parents",$this->getParentBox($airport->parent_id));
		//$this->assignRef('obj', $airport);
		parent::display($tpl);
	}
	
	function getParentBox($select){
		 
		 
		$options = array();
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		 
		$query->select('a.id as value, a.title as text, a.level');
		$query->from('#__bookpro_dest AS a');
		$query->join('LEFT', $db->quoteName('#__bookpro_dest').' AS b ON a.lft > b.lft AND a.rgt < b.rgt');

		$query->where('a.state IN (0,1)');
		$query->group('a.id, a.title, a.level, a.lft, a.rgt, a.parent_id');
		$query->order('a.lft ASC');
		 
		// Get the options.
		$db->setQuery($query);
		 
		$options = $db->loadObjectList();
		 
		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		 
		// Pad the option text with spaces using depth level as a multiplier.
		for ($i = 0, $n = count($options); $i < $n; $i++)
		{
			// Translate ROOT
			if ($options[$i]->level == 0) {
				$options[$i]->text = JText::_('JGLOBAL_ROOT_PARENT');
			}
			 
			$options[$i]->text = str_repeat('- ', $options[$i]->level).$options[$i]->text;
		}
		array_unshift($options, JHtml::_('select.option', 1, JText::_('JGLOBAL_ROOT')));
		 
		return  JHtmlSelect::genericlist($options, 'parent_id','','value','text',$select);
		 
	}
	 
}

?>