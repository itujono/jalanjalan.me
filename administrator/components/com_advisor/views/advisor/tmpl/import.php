<?php defined('_JEXEC') or die('Restricted access');
/*
* @component %%COMPONENTNAME%% 
 * @copyright Copyright (C) August 2017. 
 * @license GPL 3.0
 * This program is free software: you can redistribute it and/or modify it under the terms of the 
 * GNU General Public License as published by the Free Software Foundation, either version 3 of the License, 
 * or (at your option) any later version.
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 * See the GNU General Public License for more details.
 * See <http://www.gnu.org/licenses/>.
 * More info www.moonsoft.es gestion@moonsoft.es 
 */
?>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="bg_white">
<div id="editcell">
    <table class="table">
    <thead>
        <tr>
            <th width="100%">
                <?php echo JText::_( 'IMPORT_FILE' ); ?>
            </th>            
        </tr>            
    </thead>
    <tbody>
        <tr>
            <td>
             <input type="file" name="datafile" size="100">   
            </td>
        </tr>
        <tr>
            <td>
             <input type="submit" name="<?php echo JText::_( 'SUBMIT_FILE' ); ?>" value="<?php echo JText::_( 'SUBMIT_FILE' ); ?>"/>
            </td>
        </tr>
     </tbody>        
    </table>        
</div>
<input type="hidden" name="option" value="com_advisor" />
<input type="hidden" name="task" value="importsave" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="advisor" />
</div>
</form>