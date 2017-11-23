<?php
/*
 * @component List Manager 
 * @copyright Copyright (C) March 2016. 
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
$currMonth=date("n");
if($listing['specialfilters']!=''):
  $sf=json_decode($listing['specialfilters']);
  foreach ($sf as $sfitem):
    switch($sfitem->sftype):
      case '1': ?>
        <ul class="list-inline sfitem" id="sf" data-id="<?php echo $sfitem->sfid;?>" data-type="1">
          <li data-id="0" class="first"><?php echo JText::_( "LM_SPF_ALL" ); ?></li>
          <li data-id="1" <?php echo ($sfitem->loadcurrent&&$currMonth=='1')?'class="active"':'';?> ><?php echo JText::_( "LM_JAN" ); ?></li>
          <li data-id="2" <?php echo ($sfitem->loadcurrent&&$currMonth=='2')?'class="active"':'';?> ><?php echo JText::_( "LM_FEB" ); ?></li>
          <li data-id="3" <?php echo ($sfitem->loadcurrent&&$currMonth=='3')?'class="active"':'';?> ><?php echo JText::_( "LM_MAR" ); ?></li>
          <li data-id="4" <?php echo ($sfitem->loadcurrent&&$currMonth=='4')?'class="active"':'';?> ><?php echo JText::_( "LM_APR" ); ?></li>
          <li data-id="5" <?php echo ($sfitem->loadcurrent&&$currMonth=='5')?'class="active"':'';?> ><?php echo JText::_( "LM_MAY" ); ?></li>
          <li data-id="6" <?php echo ($sfitem->loadcurrent&&$currMonth=='6')?'class="active"':'';?> ><?php echo JText::_( "LM_JUN" ); ?></li>
          <li data-id="7" <?php echo ($sfitem->loadcurrent&&$currMonth=='7')?'class="active"':'';?> ><?php echo JText::_( "LM_JUL" ); ?></li>
          <li data-id="8" <?php echo ($sfitem->loadcurrent&&$currMonth=='8')?'class="active"':'';?> ><?php echo JText::_( "LM_AUG" ); ?></li>
          <li data-id="9" <?php echo ($sfitem->loadcurrent&&$currMonth=='9')?'class="active"':'';?> ><?php echo JText::_( "LM_SEP" ); ?></li>
          <li data-id="10" <?php echo ($sfitem->loadcurrent&&$currMonth=='10')?'class="active"':'';?> ><?php echo JText::_( "LM_OCT" ); ?></li>
          <li data-id="11" <?php echo ($sfitem->loadcurrent&&$currMonth=='11')?'class="active"':'';?> ><?php echo JText::_( "LM_NOV" ); ?></li>
          <li data-id="12" class="last  <?php echo ($sfitem->loadcurrent&&$currMonth=='12')?'active':'';?> "><?php echo JText::_( "LM_DEC" ); ?></li>          
        </ul>
        <style>
        ul.sfitem>li{cursor:pointer;}
        </style>  
        <script>
        LMJQ(document).ready(function($){
            <?php if($sfitem->loadcurrent): ?>
            setTimeout(function(){addFilter<?php echo $seed;?>('spfilter_<?php echo $sfitem->sfid;?>','<?php echo $currMonth;?>', false,null,true);}, 500);                        
            <?php endif;?>
          $('ul[data-type="1"]').find('li').each(function(index,elem){
            $(elem).click(function(event){
                var target=event.target;
                $(target).closest('ul').find('li').removeClass('active');
                $(target).addClass('active');
                var id=$(target).closest('ul').data('id');
                if (id=='0')
                	removeFilter<?php echo $seed;?>('spfilter_'+id,$(elem).data('id') );
                else
              		addFilter<?php echo $seed;?>('spfilter_'+id,$(elem).data('id') , false,null,true);
              	
            });
          });
        });
        
        </script>      
      <?php
      break;
    endswitch;
  endforeach;
endif;
?>
