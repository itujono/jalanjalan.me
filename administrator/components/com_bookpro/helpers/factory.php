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
use Joomla\Registry\Registry;

class JBFactory
{
    /**
     * Get Joombooking config
     * @return JRegistry
     */
	static function getConfig()
    {
       return  JComponentHelper::getParams('com_bookpro');
    }
    
    /**
     * Get Joombooking account logined
     * @return Customer
     */
    public static function getAccount()
    {
    	static $instance;
    	if (empty($instance)) {
	    		$user=JFactory::getUser();
	    		JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_bookpro/tables');
	    		$instance= JTable::getInstance('Customer','Table');
	    		$instance->load(array('user'=>$user->id));
	    		
	    		$instance->juser=$user;   
	    		$config=self::getConfig();
	    		$instance->isNormal=true;
	    		$instance->isAgent=false;
	    		$instance->isSupplier=false;
	    		$instance->isDriver=false;
	    		
				if(in_array($config->get('customer_usergroup'), $user->groups)){
					$instance->isNormal=true;
					
				}
				if(in_array($config->get('agent_usergroup'), $user->groups)){
					$instance->isAgent=true;
						
				}
				if(in_array($config->get('supplier_usergroup'), $user->groups)){
					
					$instance= JTable::getInstance('Agent','Table');
					$instance->load(array('user'=>$user->id));
					$instance->isSupplier=true;
						
				}
				
				if(in_array($config->get('driver_usergroup'), $user->groups)){
					$instance->isDriver=true;
				
				}
				
				$registry = new Registry;
				$registry->loadString($instance->params);
				$instance->params = $registry->toArray();
				
				if(isset($instance->params['commission'])){
					$instance->commission=$instance->params['commission'];
				}
				if(isset($instance->params['credit'])){
					$instance->credit=$instance->params['credit'];
				}
				
	    		
    	}
    	return $instance;
    }
    
    
}

?>