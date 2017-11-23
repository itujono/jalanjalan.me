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
$pathImagen=JURI::base().'components/com_listmanager/assets/img/central.png';
?>
<div style="width:100%;text-align:center">
<a href="index.php?option=com_listmanager&controller=listing"><h2>
<?php echo JText::_( 'GO_TO_LISTINGS' ); ?>
</h2> </a>
</div>
<div style="width:100%;text-align:center"><a href="index.php?option=com_listmanager&controller=listing">
<img src="<?php echo $pathImagen; ?>" border="0"/>
</a>
</div>
