<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 26 2012-07-08 16:07:54Z quannv $
 **/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$document=JFactory::getDocument();
JHtml::_('jquery.framework');
//JFactory::getDocument()->addScript('//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js');
$document->addScript(JURI::root().'components/com_bookpro/assets/js/typeahead.bundle.js');

class BookProViewTest extends JViewLegacy
{
  
    function display($tpl = null)
    {
    	parent::display();
    }
}