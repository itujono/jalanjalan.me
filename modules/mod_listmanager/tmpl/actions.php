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

?>
<form action="#" method="post" id="<?php echo $seed;?>exportdocuments" name="exportdocuments" target="blank">
<input type="hidden" name="check" value="post"/> 
<input type="hidden" name="option" value="com_listmanager"/>
<input type="hidden" name="controller" value="serverpages"/>
<input type="hidden" name="format" value="pdf"/>
<input type="hidden" name="id" value="<?php echo $params->get('prefsids');?>"/>
<input type="hidden" name="task" value="showPdf"/>
<input type="hidden" name="fullexport" value="<?php echo $params->get('fullexport');?>"/>
<input type="hidden" name="title" value="<?php echo $listing['name']; ?>"/>
<input type="hidden" name="filter" value=""/>
<input type="hidden" name="sort" value=""/>
<input type="hidden" name="access_type" value="<?php echo $params->get('access_type');?>"/>
<input type="hidden" name="user_on" value="<?php echo  JFactory::getUser()->id;?>"/>
<input type="hidden" name="ids_filtered" id="<?php echo $seed;?>ids_filtered" value=""/>
<?php echo JHTML::_( 'form.token' ); ?>
</form>

<form action="index.php" method="post" id="<?php echo $seed;?>maildocuments" name="maildocuments">
<input type="hidden" name="check" value="post"/> 
<input type="hidden" name="option" value="com_listmanager"/>
<input type="hidden" name="controller" value="serverpages"/>
<input type="hidden" name="format" value="raw"/>
<input type="hidden" name="filter" value=""/>
<input type="hidden" name="sort" value=""/>
<input type="hidden" name="id" value="<?php echo $params->get('prefsids');?>"/>
<input type="hidden" name="task" id="<?php echo $seed;?>taskSend" value=""/>
<input type="hidden" name="fullexport" value="<?php echo $params->get('fullexport');?>"/>
<input type="hidden" name="title" value="<?php echo $listing['name']; ?>"/>
<input type="hidden" name="access_type" value="<?php echo $params->get('access_type');?>"/>
<input type="hidden" name="user_on" value="<?php echo  JFactory::getUser()->id;?>"/>
<input type="hidden" name="ids_filtered" id="<?php echo $seed;?>mail_ids_filtered" value=""/>
<input type="hidden" name="email" id="<?php echo $seed;?>emailValueSend" value=""/>
<?php echo JHTML::_( 'form.token' ); ?>
</form>
