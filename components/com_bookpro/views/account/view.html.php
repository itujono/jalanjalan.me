<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

AImporter::model('customer');

class BookproViewAccount extends JViewLegacy
{

	function display($tpl = null)
	{
		
		$app = JFactory::getApplication();
		$model 			= new BookProModelCustomer();
		
		$user = JFactory::getUser();
		$cookieLogin = $user->get('cookieLogin');
		$return=base64_encode(JRoute::_('index.php?option=com_bookpro&view=account'));
		
		if($user->guest){
			
			$app->enqueueMessage(JText::_('JGLOBAL_REMEMBER_MUST_LOGIN'), 'message');
			$app->redirect(JUri::base() . 'index.php?option=com_users&view=login&return='.$return, '', 302);
			return false;
		}
		
		if (!empty($cookieLogin))
		{
			// If so, the user must login to edit the password and other data.
			// What should happen here? Should we force a logout which detroys the cookies?
			$app->enqueueMessage(JText::_('JGLOBAL_REMEMBER_MUST_LOGIN'), 'message');
			$app->redirect(JUri::base() . 'index.php?option=com_users&view=login&return='.$return, '', 302);
		
			return false;
		}
		$this->account=JBFactory::getAccount();
		
		$this->customer = $model->getItemByUser();
		$doc=JFactory::getDocument();
		$doc->setTitle($this->customer->firstname.' '.$this->customer->lastname);
			
		parent::display($tpl);
	}
	
	
	
}

?>
