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
 
jimport( 'joomla.application.component.view' );
include JPATH_COMPONENT_ADMINISTRATOR.'/views/main.view.php';

class ListmanagerViewListmanager extends HelperListmanagerView
{
  
  function display($tpl = null)
  {
    $document = JFactory::getDocument();
    $document->addStyleDeclaration('.icon-48-listmanager {background-image: url('.JURI::base().'components/com_listmanager/assets/img/ListManager48x48.png)}');

    JToolBarHelper::title(   JText::_( 'LISTMANAGER' ),'listmanager' );
    JToolBarHelper::preferences( 'com_listmanager' );
    parent::display($tpl);
  }
}

?>
