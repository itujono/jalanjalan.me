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
JHTML::_('behavior.framework', true);

$document =JFactory::getDocument();                                                                       
$css=$this->__getCSS();          
foreach ($css as $style){                                                        
	$document->addStyleSheet($style);
}
$js=$this->__getJS();
foreach ($js as $jscript){                                                        
	?><script src="<?php echo $jscript;?>"></script><?php 
}
$pathImagen=JURI::base().'components/com_listmanager/assets/img/data.png';
$pathImagen2=JURI::base().'components/com_listmanager/assets/img/export.png';
$pathImagen3=JURI::base().'components/com_listmanager/assets/img/groups.png';
$pathImagen4=JURI::base().'components/com_listmanager/assets/img/config.png';
$pathImagen5=JURI::base().'components/com_listmanager/assets/img/access_table.png';
$pathImagen6=JURI::base().'components/com_listmanager/assets/img/exportlist.png';
$pathImagen7=JURI::base().'components/com_listmanager/assets/img/search-icon.png';
$pathImagenedit=JURI::base().'components/com_listmanager/assets/img/edit.png';

$arrHelp=array();
$arrHelp[JText::_( 'EDIT' )]=$pathImagenedit;
$arrHelp[JText::_( 'MANAGE_DATA' )]=$pathImagen;
$arrHelp[JText::_( 'CONFIG_ACL' )]=$pathImagen3;
$arrHelp[JText::_( 'CONFIG_PDF_EXCEL' )]=$pathImagen4;
$arrHelp[JText::_( 'EXPORT_DATA' )]=$pathImagen2;
$arrHelp[JText::_( 'ACCESS_DATA' )]=$pathImagen5;
?>
<?php echo $this->__getHelp('MAIN',$arrHelp);?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div id="deflisting">
    <table class="table adminlist">
    <thead>        
        <tr>
        	<th style="width:20px"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
            <th style="width:5px"><?php echo JText::_( 'ID' ); ?></th>
            <th style="width:10%"><?php echo JText::_( 'NAME' ); ?></th>
            <th style="width:15%"><?php echo JText::_( 'INFORMATION' ); ?></th>      
        	<th class="def_edit">
                <?php echo JText::_( 'EDIT' ); ?>
            </th>
            <th class="def_manage_data">
                <?php echo JText::_( 'MANAGE_DATA' ); ?>
            </th>
            <th class="def_acl">
                <?php echo JText::_( 'CONFIG_ACL' ); ?>
            </th>
            <th class="def_config">
                <?php echo JText::_( 'CONFIG_PDF_EXCEL' ); ?>
            </th>
            <th class="def_export">
                <?php echo JText::_( 'EXPORT_DATA' ); ?>
            </th>
            <th class="def_access">
                <?php echo JText::_( 'ACCESS_DATA' ); ?>
            </th>
            <th class="def_search">
                <?php echo JText::_( 'SEARCH_DATA' ); ?>
            </th>
            <th class="def_backup">
                <?php echo JText::_( 'BACKUP_LIST' ); ?>
            </th>         
        </tr>            
    </thead>
    <?php
    $k = 0;
    $i=0;
    foreach ($this->items as &$row){
      $checked    = JHTML::_( 'grid.id', $i++, $row->id );
      $link = JRoute::_( 'index.php?option=com_listmanager&controller=listing&task=edit&cid[]='. $row->id );
      $linkadmin = JRoute::_( 'index.php?option=com_listmanager&controller=listing&task=admindata&cid[]='. $row->id );
      $linkacl = JRoute::_( 'index.php?option=com_listmanager&controller=listing&task=configacl&cid[]='. $row->id );
      $linkexport = JRoute::_( 'index.php?option=com_listmanager&controller=listing&format=ecsv&task=exportdata&cid[]='. $row->id );
      $linkconfig = JRoute::_( 'index.php?option=com_listmanager&controller=listing&task=config&cid[]='. $row->id );
      $linkaccess = JRoute::_( 'index.php?option=com_listmanager&controller=access&task=show&cid[]='. $row->id.'&idlisting='. $row->id );
      $linksearch = JRoute::_( 'index.php?option=com_listmanager&controller=search&task=show&cid[]='. $row->id.'&idlisting='. $row->id );
      $linkbackup = JRoute::_( 'index.php?option=com_listmanager&controller=listing&format=exml&task=exportlist&cid[]='. $row->id );
        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td><?php echo $checked; ?></td>            
            <td><?php echo $row->id; ?></td>
            <td><a href="<?php echo $link; ?>"><?php echo $row->name; ?></a></td>
            <td><?php echo $row->info; ?></td>
            <td align="center" nowrap class="def_edit">   
               <a href="<?php echo $link; ?>" title="<?php echo JText::_( 'EDIT' ); ?>"><img src="<?php echo $pathImagenedit; ?>"/></a>
            </td><td align="center" nowrap class="def_manage_data">   
               <a href="<?php echo $linkadmin; ?>" title="<?php echo JText::_( 'MANAGE_DATA' ); ?>"><img src="<?php echo $pathImagen; ?>"/></a>
            </td><td align="center" nowrap class="def_acl">
               <a href="<?php echo $linkacl; ?>"  title="<?php echo JText::_( 'CONFIG_ACL' ); ?>"><img src="<?php echo $pathImagen3; ?>"/></a>
            </td><td align="center" nowrap class="def_config">
               <a href="<?php echo $linkconfig; ?>"  title="<?php echo JText::_( 'CONFIG_PDF_EXCEL' ); ?>"><img src="<?php echo $pathImagen4; ?>"/></a>
            </td><td align="center" nowrap class="def_export">
               <a href="<?php echo $linkexport; ?>"  title="<?php echo JText::_( 'EXPORT_DATA' ); ?>"><img src="<?php echo $pathImagen2; ?>"/></a>
            </td><td align="center" nowrap class="def_access">
               <a href="<?php echo $linkaccess; ?>"  title="<?php echo JText::_( 'ACCESS_DATA' ); ?>"><img src="<?php echo $pathImagen5; ?>"/></a>
            </td>
            <td align="center" nowrap class="def_search">
               <a href="<?php echo $linksearch; ?>"  title="<?php echo JText::_( 'SEARCH_DATA' ); ?>"><img src="<?php echo $pathImagen7; ?>"/></a>
            </td>            
            <td class="def_backup">
            	<a href="<?php echo $linkbackup; ?>"  title="<?php echo JText::_( 'BACKUP_LIST' ); ?>"><img src="<?php echo $pathImagen6; ?>"/></a>
            </td>
        </tr>
        <?php
        $k = 1 - $k;
    }
    ?>
    <tfoot>
    <?php if ( version_compare( JVERSION, '3.0', '>=' ) == 1) { ?><td><?php echo $this->pagenav->getLimitBox();?></td><?php } ?>
    <td colspan="9"><?php echo $this->pagenav->getListFooter();?></td>
    </tfoot>
    </table>    
</div>
 
<input type="hidden" name="option" value="com_listmanager" />
<input type="hidden" name="task" value="show" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="listing" />
 
</form>

