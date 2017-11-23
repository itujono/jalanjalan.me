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
defined('_JEXEC') or die('Restricted access');

class TableField extends JTable
{
    var $id = null;
    var $idlisting = null;
    var $name = null;
    var $type = null;
    var $decimal = null;
    var $limit0 = null;
    var $limit1 = null;
    var $multivalue = null;
    var $mandatory = null;
    var $visible = null;
    var $size = null;
    var $order = null;
    var $autofilter = null;
    var $total = null;    
    var $sqltext = null;
    var $showorder = null;
    var $defaulttext = null;    
    var $validate = null;    
    var $css = null;
    
    
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct( &$db ) {
        parent::__construct('#__listmanager_field', 'id', $db);
    }
}


?>
