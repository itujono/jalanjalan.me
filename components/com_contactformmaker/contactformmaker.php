<?php

 /**
 * @package Contact Form Maker
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
 
defined( '_JEXEC' ) or die( 'Restricted access' );
defined( 'DS' )  or define('DS', DIRECTORY_SEPARATOR);
require_once( JPATH_COMPONENT.DS.'controller.php' );

jimport( 'joomla.filesystem.folder' );

$controller = JRequest::getVar( 'controller' );

$classname    = 'contactformmakerController'.$controller;

$controller   = new $classname( );

$controller->execute( JRequest::getVar( 'task' ) );

$controller->redirect();

?>