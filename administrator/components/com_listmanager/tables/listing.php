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

class TableListing extends JTable {
    var $id = null;
    var $name = null;       
    var $info = null;    
    var $layout = null;
    var $preprint = null;
    var $postprint = null;
    var $pdforientation = null;
    var $date_format = null;
    var $decimal = null;
    var $thousand = null;
    var $modalform = null;
    var $savehistoric = null;
    var $deletehistoric = null;
    var $ratemethod = null;
    var $detail = null;
    var $hidelist = null;
    var $list_type= null;
    var $keyfields= null;
    var $detailmode= null;
 
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct( &$db ) {
        parent::__construct('#__listmanager_listing', 'id', $db);
    }
}


?>
