<?php 
  
 /**
 * @package Contact Form Maker
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');


class contactformmakerController extends JControllerLegacy
{


	function element()
	{
		JSubMenuHelper::addEntry('Blocked Ips','index.php?option=com_contactformmaker&task=blocked_ips' ); 
		JSubMenuHelper::addEntry(JText::_('Global Options'), 'index.php?option=com_contactformmaker&amp;task=global_options' );
		$model	= $this->getModel( 'element' );
		$view	= $this->getView( 'element');
		$view->setModel( $model, true );
		$view->display();
	}

	function select_article()
	{
		$model	= $this->getModel( 'select_article' );
		$view	= $this->getView( 'select_article');
		$view->setModel( $model, true );
		$view->display();
	}

}

?>