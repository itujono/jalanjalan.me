<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id$
 **/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');


/**
 */
class BookProViewPostPayment extends JViewLegacy
{
	// Overwriting JViewLegacy display method
	function display($tpl = null)
	{
		$this->config=JComponentHelper::getParams('com_bookpro');
		AImporter::model('order');
		$orderModel=new BookProModelOrder();
		if(JFactory::getApplication()->input->get('order_id')){
			
			$this->order=$orderModel->getComplexItem(JFactory::getApplication()->input->get('order_id'));
		}
		
		parent::display($tpl);
	}


}
