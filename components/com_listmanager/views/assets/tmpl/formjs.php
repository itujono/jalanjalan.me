<?php defined('_JEXEC') or die('Restricted access'); 
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
$basepath=JPATH_COMPONENT_SITE.DS.'assets'.DS.'js'.DS;
/*include_once  $basepath.'mootools_wrapper.js';
include_once  $basepath.'headers.js';
include_once  $basepath.'contents.js';
include_once  $basepath.'acl.js';
include_once  $basepath.'filter.js';
include_once  $basepath.'sorter.js';*/
?>
var antJQ=null;
if (window.jQuery){antJQ=jQuery;}
<?php 
include_once  $basepath.'date.js';
include_once  $basepath.'json2.js';
include_once  $basepath.'jquery-1.9.0.js';
//include_once  $basepath.'jquery.rateit.min.js';
include_once  $basepath.'jquery.rateit.js';
//include_once  $basepath.'jquery.readmore.js';
include_once  $basepath.'jquery.ddslick.js';
//include_once  $basepath.'jquery.nanoscroller.min.js';
include_once  $basepath.'jquery-ui-1.10.0.custom.min.js';
?>
jQuery.widget.bridge('uitooltip', $.ui.tooltip);
jQuery.widget.bridge('uibutton', $.ui.button);
<?php 
include_once  $basepath.'lmbootstrap.min.js';
include_once  $basepath.'jquery.validate.min.js';
include_once  $basepath.'jquery.ui.datepicker.js';
include_once  $basepath.'jquery.PrintArea.js';
include_once  $basepath.'jquery.cleditor.js';

?>
var LMFM=jQuery.noConflict();
if (typeof (LMJQ)==='undefined') LMJQ=jQuery.noConflict();
if (antJQ!=null){jQuery=antJQ;}
