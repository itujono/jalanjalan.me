<?php 
  
 /**
 * @package Contact Form Maker
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('_JEXEC') or die('Restricted access');

function cancel_secondary()
{
	JToolBarHelper :: custom( 'cancel_secondary', 'cancel.png', '', 'Cancel', '', '' );
}

function edit_submit_text()
{
	JToolBarHelper :: custom( 'edit_my_submit_text', 'edit.png', '', 'Actions after submission', '', '' );
}

function remove_submit()
{
	JToolBarHelper :: custom( 'remove_submit', 'delete.png', '', 'Delete', '', '' );
}

function edit_submit()
{
	JToolBarHelper :: custom( 'edit_submit', 'edit.png', '', 'Edit', '', '' );
}

function undo()
{
	JToolBarHelper :: custom( 'undo', 'back.png', '', 'Undo', '', '' );
}

function redo()
{
	JToolBarHelper :: custom( 'redo', 'forward.png', '', 'Redo', '', '' );
}

class TOOLBAR_contactformmaker {

	public static function _THEMES() {
		$document = JFactory::getDocument();
		$document->addStyleSheet('components/com_contactformmaker/ContactFormMakerLogo.css');
		JToolBarHelper::title( 'Contact Form Maker: Themes');		
		JToolBarHelper::addNew('add_themes');
		JToolBarHelper::editList('edit_themes');
		JToolBarHelper::makeDefault();
		JToolBarHelper::deleteList('Are you sure you want to delete? ', 'remove_themes');

	}

	public static function _Blocked_ips() {
		
		$document = JFactory::getDocument();

		$document->addStyleSheet('components/com_contactformmaker/ContactFormMakerLogo.css');

		JToolBarHelper::title( 'Contact Form Maker: Blocked Ips');		
		
		JToolBarHelper::addNew('add_blocked_ips');
		
		JToolBarHelper::editList('edit_blocked_ips');

		JToolBarHelper::deleteList('Are you sure you want to delete? ', 'remove_blocked_ips');

	}
	
	public static function _NEW_Blocked_ips()
	{
		$document = JFactory::getDocument();

		$document->addStyleSheet('components/com_contactformmaker/ContactFormMakerLogo.css');

		JToolBarHelper::title( 'Contact Form Maker');		

		JToolBarHelper::apply('apply_blocked_ips');
	
		JToolBarHelper::save('save_blocked_ips');

		JToolBarHelper::cancel('cancel_blocked_ips');		


	}
	
	
	
	public static function _NEW_Form_options() 
	{		
		JToolBarHelper::title( 'Contact Form Maker: Form Options');		
		JToolBarHelper::save('save_form_options');
		JToolBarHelper::apply('apply_form_options');
		cancel_secondary();
	}
	public static function _NEW_Form_global_options() 
	{		
		$document =JFactory::getDocument();
		$document->addStyleSheet('components/com_contactformmaker/FormMakerLogo.css');
		JToolBarHelper::title( 'Global Options', 'FormMakerLogo' );		
		JToolBarHelper::apply('save_global_options');
	}
	


	public static function _NEW_Form_form_layout() 
	{		
		$document =JFactory::getDocument();
		$document->addStyleSheet('components/com_contactformmaker/ContactFormMakerLogo.css');
		JToolBarHelper::title( 'Contact Form Maker: Form Layout');		
		JToolBarHelper::save('save_form_layout');
		JToolBarHelper::apply('apply_form_layout');
		cancel_secondary();
	}
	
	public static function _NEW_THEMES() {
		JToolBarHelper::title( 'Contact Form Maker: Add Theme');		
		JToolBarHelper::apply('apply_themes');
		JToolBarHelper::save('save_themes');
		JToolBarHelper::cancel('cancel_themes');		
	}
	

	
	public static function _SUBMITS() 
	{
		$document = JFactory::getDocument();
		$document->addStyleSheet('components/com_contactformmaker/ContactFormMakerLogo.css');
		JToolBarHelper::title( 'Contact Form Maker: Submissions' );
		remove_submit();
		JToolBarHelper :: custom( 'block_ip', 'lock.png', '', 'Block IP', '', '' );
	}

	public static function _NEW() 
	{	
		JToolBarHelper::title( 'Contact Form Maker: Edit Form');		
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper :: custom( 'save_as_copy', 'copy.png', '', 'Save as Copy', '', '' );
		JToolBarHelper :: spacer(5);
		JToolBarHelper :: divider();
		JToolBarHelper :: spacer(5);
		JToolBarHelper :: custom( 'form_options_temp', 'cog.png', '', 'Form Options', '', '' );	
		JToolBarHelper :: custom( 'form_layout_temp', 'file-2.png', '', 'Form Layout', '', '' );	
		JToolBarHelper :: spacer(5);
		JToolBarHelper :: divider();
		JToolBarHelper :: spacer(5);
		JToolBarHelper::cancel();		
	}

	public static function _DEFAULT() {
		//$document = JFactory::getDocument();
		//$document->addStyleSheet('components/com_contactformmaker/FormMakerLogo.css');
		JToolBarHelper::title( 'Contact Form Maker');		
		JToolBarHelper::editList();
		JToolBarHelper::custom( 'copy', 'copy.png', 'copy_f2.png', 'Copy' );
		JToolBarHelper::deleteList();

	}

}

?>