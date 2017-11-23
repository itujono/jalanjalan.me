<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bookpro.php 27 2012-07-08 17:15:11Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');
JHtml::_('jquery.framework');
$lang=BookProHelper::addJqueryValidate();

?>
	<script type="text/javascript">
	jQuery(document).ready(function($){
		$("#itineray-form").validate({
			rules: {
				
			}
		});
		
	});
 </script>
 <div class="container" style="max-width: 980px;">
		 <div class="well well-small" >
		 	<h1 class="page-content-header round-box">
                <i class="icon-user icon-large"></i>
                <span><?php echo JText::_('COM_BOOKPRO_VIEW_ORDER_HEADLINE')?></span></h1>
		 </div>
		 
		 <div class="well">
                
                        <h4><?php echo JText::_('COM_BOOKPRO_VIEW_ORDER_SUBHEADLINE')?></h4>
                        <form id="itineray-form" class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_bookpro&view=orderdetail')?>" method="post">
                                   
                                   <div class="control-group">
            <label class="control-label"><?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER')?></label>
            <div class="controls">
             <input type="text"  name="order_number" required class="input">
            </div>
            </div>
             <div class="control-group">
            <label class="control-label"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL')?></label>
            <div class="controls">
             <input type="email"  name="email" class="input" required>
            </div>
           
            
          </div>
          
          
           <div class="control-group">
    <div class="controls">
      
      <input type="submit"  value="<?php echo JText::_('COM_BOOKPRO_SUBMIT') ?>" class="btn btn-primary"/>
    </div>
  </div>
          
                                    
                        </form>
                       
                </div>
                
             </div>   
