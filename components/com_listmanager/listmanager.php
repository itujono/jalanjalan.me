<?php
 /*
 * @component List Manager 
 * @copyright Copyright (C) November 2017. 
 * @license GPL 3.0 
 * This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version. 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 * See the GNU General Public License for more details. 
 * See <http://www.gnu.org/licenses/>. 
 * More info www.moonsoft.es 
 * gestion@moonsoft.es
 */
   
  // No direct access 
  defined( '_JEXEC' ) or die( 'Restricted access' );
  
  jimport('joomla.filesystem.file');

  if(!defined('DS')){ 
	define('DS',DIRECTORY_SEPARATOR); 
  }
  
  // Require the base controller 
  require_once( JPATH_COMPONENT.DS.'controller.php' );
  
   
  // Require specific controller if requested
  if($controller = JRequest::getWord('controller')) {
      $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
      //if (file_exists($path)) {
      if (JFile::exists($path)) {
          require_once $path;
      } else {
          $controller = '';
      }
  }
   
  // For Joomla 5.2.17
  ini_set('pcre.backtrack_limit', 1000000);
  ini_set('pcre.recursion_limit', 1000000);
  
  // Create the controller
  $classname    = 'ListmanagerController'.$controller;
  $controller   = new $classname( );
   
  // Perform the Request task
  $controller->execute( JRequest::getVar( 'task','show' ) );
   
  // Redirect if set by the controller
  $controller->redirect();

?>
