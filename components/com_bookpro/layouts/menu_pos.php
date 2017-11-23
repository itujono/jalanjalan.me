<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bookpro.php 27 2012-07-08 17:15:11Z quannv $
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );
?>
<ul class="inline">
	<li><a class="btn btn-primary" href="<?php echo JRoute::_('index.php?option=com_bookpro&view=pos')?>"><?php echo JText::_('COM_BOOKPRO_CREATE_TICKET')?></a></li>
	<li><a class="btn btn-primary"
		href="<?php echo  JRoute::_('index.php?option=com_bookpro&view=pos&layout=orders') ?>"><?php echo JText::_('COM_BOOKPRO_ORDERS')?></a></li>
	<li><a class="btn btn-primary"
		href="<?php echo  JRoute::_('index.php?option=com_bookpro&view=pos&layout=report') ?>"><?php echo JText::_('COM_BOOKPRO_REPORT')?></a></li>
	<li><a class="btn btn-primary"
		href="<?php echo JRoute::_('index.php?option=com_bookpro&view=account') ?>"><?php echo JText::_('COM_BOOKPRO_ACCOUNT')?></a></li>
		
	<li><a class="btn btn-primary" href="<?php echo  JRoute::_('index.php?option=com_users&task=user.logout&'.JSession::getFormToken().'=1&return='.base64_encode(JUri::root())) ?>"><?php echo JText::_('COM_BOOKPRO_LOGOUT')?></a></li>
</ul>
