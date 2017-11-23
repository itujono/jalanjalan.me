<?php

/**
 * @version     1.0.0
 * @package     com_bookpro
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Ngo <quannv@gmail.com> - http://joombooking.com
 */
// No direct access
defined ( '_JEXEC' ) or die ();
class BookproViewSms extends JViewLegacy {
	protected $state;
	protected $item;
	protected $form;
	
	/**
	 * Display the view
	 */
	public function display($tpl = null) {
		$this->state = $this->get ( 'State' );
		$this->item = $this->get ( 'Item' );
		$this->form = $this->get ( 'Form' );
		
		// Check for errors.
		if (count ( $errors = $this->get ( 'Errors' ) )) {
			throw new Exception ( implode ( "\n", $errors ) );
		}
		
		$this->addToolbar ();
		parent::display ( $tpl );
	}
	
	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar() {
		JFactory::getApplication ()->input->set ( 'hidemainmenu', true );
		
		JToolBarHelper::title ( JText::_ ( 'COM_BOOKPRO_SMS' ), 'mail-2' );
		
		JToolBarHelper::apply ( 'sms.apply', 'JTOOLBAR_APPLY' );
		JToolBarHelper::save ( 'sms.save', 'JTOOLBAR_SAVE' );
		
		if (empty ( $this->item->id )) {
			JToolBarHelper::cancel ( 'sms.cancel', 'JTOOLBAR_CANCEL' );
		} else {
			JToolBarHelper::cancel ( 'sms.cancel', 'JTOOLBAR_CLOSE' );
		}
	}
}
