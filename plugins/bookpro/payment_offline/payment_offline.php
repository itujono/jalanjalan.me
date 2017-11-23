<?php 
/**
 * @package Bookpro
 * @author Ngo Van Quan
 * @link http://joombooking.com
 * @copyright Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version $Id: request.php 44 2012-07-12 08:05:38Z quannv $
 */
defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ADMINISTRATOR.'/components/com_bookpro/helpers/payment.php');
class plgBookproPayment_offline extends BookproPaymentPlugin
{
	/**
	 * @var $_element  string  Should always correspond with the plugin's filename, 
	 *                         forcing it to be unique 
	 */
    var $_element    = 'payment_offline';
	
	function __construct(& $subject, $config) 
	{
		parent::__construct($subject, $config);
	}
   
    /**
     * Prepares the payment form
     * and returns HTML Form to be displayed to the user
     * generally will have a message saying, 'confirm entries, then click complete order'
     * 
     * @param $data     array       form post data
     * @return string   HTML to display
     */
    function _prePayment( $data )
    {
        $app=JFactory::getApplication();
        $app->redirect(JUri::root().'index.php?option=com_bookpro&controller=payment&task=postpayment&method=payment_offline&order_number='.$data['order_number']);
    }
    
    /**
     * Processes the payment form
     * and returns HTML to be displayed to the user
     * generally with a success/failed message
     *  
     * @param $data     array       form post data
     * @return string   HTML to display
     */
    function _postPayment($data )
    {
    	
    	JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_bookpro/tables' );
    	$order = JTable::getInstance('Orders', 'Table');
    	$order_number=JRequest::getString('order_number');
    	$order->load(array('order_number'=>$order_number));
    	$order->order_status=$this->params->get('order_status');
    	$order->store();
    	
    	//creating paylog
    	$db=JFactory::getDbo();
    	$lists=$db->getTableList();
    	$prefix=JFactory::getApplication()->get('dbprefix').'bookpro_paylog';
    	//var_dump($lists);die;
    	if(in_array($prefix, $lists)){
    		JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');
    		$paylog=JTable::getInstance('paylog', 'table');
    		$paylog->order_id=$order->id;
    		$paylog->receiver="Merchant";
			$paylog->gateway="offline";
    		$paylog->tx_id='Not available';
    		$paylog->store();
    	}
    	return $order;
       
    }
    
    /**
     * Prepares variables and 
     * Renders the form for collecting payment info
     * 
     * @return unknown_type
     */
    function _renderForm( $data )
    {
    	$user = JFactory::getUser();  	
        $vars = new JObject();
        $html = $this->_getLayout('form', $vars);
        return $html;
    }
   
   
   
}
