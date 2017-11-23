<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: customer.php 23 2012-07-08 02:20:56Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');


class BookProControllerCustomer extends JControllerForm
{
	function agent(){
	
		AImporter::model('customer');
		$config		 		= JComponentHelper::getParams('com_bookpro');
		$agent_group 		= $config->get('agent_usergroup');
	
		$db		= JFactory::getDbo();
		$app	= JFactory::getApplication();
		$input	= $app->input;
		$cid	= $input->get('cid','','array');
	
	
		if($cid){
			foreach ($cid as $key => $customer_id){
				$customerModel 	= new BookProModelCustomer();
				$customer 		= $customerModel->getItem($customer_id);
					
				if($customer->user){
				 $user=JUser::getInstance($customer->user);
				 $user->groups=array($agent_group);
				 $user->save();
				}
			}
		}
		
		JFactory::getApplication ()->enqueueMessage ( JText::_('Update successful'), 'message');
		$mainframe = JFactory::getApplication();
		$mainframe->redirect('index.php?option=com_bookpro&view=customers');
	}
	
	function driver(){
	
		AImporter::model('customer');
		$config		 		= JComponentHelper::getParams('com_bookpro');
		$agent_group 		= $config->get('driver_usergroup');
	
		$db		= JFactory::getDbo();
		$app	= JFactory::getApplication();
		$input	= $app->input;
		$cid	= $input->get('cid','','array');
	
	
		if($cid){
			foreach ($cid as $key => $customer_id){
				$customerModel 	= new BookProModelCustomer();
				$customer 		= $customerModel->getItem($customer_id);
					
				if($customer->user){
					$user=JUser::getInstance($customer->user);
					$user->groups=array($agent_group);
					$user->save();
				}
			}
		}
	
		JFactory::getApplication ()->enqueueMessage ( JText::_('Update successful'), 'message');
		$mainframe = JFactory::getApplication();
		$mainframe->redirect('index.php?option=com_bookpro&view=customers');
	}
	
  
}

?>