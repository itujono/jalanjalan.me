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
$itemid=JFactory::getApplication()->input->get('Itemid');
?>
<ul class="inline">
	<li><a class="btn btn-primary" href="<?php echo JRoute::_('index.php?option=com_bookpro&view=driver&Itemid='.$itemid) ?>"><?php echo JText::_('COM_BOOKPRO_CREATE_TICKET')?></a></li>
	
	<li><a class="btn btn-primary"
		href="<?php echo JUri::root().'index.php?option=com_bookpro&view=driver&layout=passengers&Itemid='.$itemid ?>"><?php echo JText::_('COM_BOOKPRO_CHECKIN')?></a></li>
	<li><a class="btn btn-primary"
		href="<?php echo JRoute::_('index.php?option=com_bookpro&view=account') ?>"><?php echo JText::_('COM_BOOKPRO_ACCOUNT')?></a></li>
		
	<li><a class="btn btn-primary" href="<?php echo  JRoute::_('index.php?option=com_users&task=user.logout&'.JSession::getFormToken().'=1&return='.base64_encode(JUri::root())) ?>"><?php echo JText::_('COM_BOOKPRO_LOGOUT')?></a></li>
</ul>
