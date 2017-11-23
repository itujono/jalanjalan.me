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
class plgBookproPayment_paypal extends BookproPaymentPlugin
{

	var $_element    = 'payment_paypal';
	var $_params = array();

	function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
		$language=JFactory::getLanguage();
		$language->load('plg_bookpro_payment_paypal',JPATH_ADMINISTRATOR);
	}

	function setParams($params) {
		foreach ($params as $key => $value) {
			$this->_params[$key] = $value ;
		}
	}
	
	function _prePayment( $data )
	{
		$data['total']=number_format($data['total'],2,'.','');
		
		$config=JComponentHelper::getParams('com_bookpro');
		$currency="USD";
		$currency=trim($config->get('main_currency'));
		
		if(!$currency){
			$currency=$this->_getParam('currency');
		}
		$item_name=$data['title']?$data['title']:$data['order_number'];
		
		$this->setParam('business', $this->_getParam('merchant_email'));
				
		$this->setParam('item_name', $item_name);
		if(isset($data['recurring'])){
			$this->setParam('cmd','_xclick-subscriptions');
			$this->setParam('a3', $data['total']);
			$this->setParam('p3', "1");
			$this->setParam('t3', 'M');
			$this->setParam('src', '1');
			
		}else{
			$this->setParam('rm', 2);
			$this->setParam('cmd', '_xclick');
			$this->setParam('amount', $data['total']);
			
		}
			
		$this->setParam('no_shipping', 1);
		$this->setParam('no_note', 1);
		$this->setParam('lc', 'US');
		$this->setParam('currency_code',$currency);
		$return = JURI::root()."index.php?option=com_bookpro&controller=payment&task=postpayment&paction=display_message&nomail=1&order_number=".$data['order_number']."&method=".$this->_element;
		$notify = JURI::root()."index.php?option=com_bookpro&controller=payment&task=postpayment&paction=process&order_number=".$data['order_number']."&method=".$this->_element;
		
		$this->setParam('custom',$data['order_number']);		
		$this->setParam('return', $return);
		$this->setParam('cancel_return', JURI::root());
		$this->setParam('notify_url',$notify);
			
		?>

<form method="post" action="<?php echo $this->_getPostUrl() ?>"
	name="paypal" id="paypal">
	<?php
	foreach ($this->_params as $key=>$val) {
		echo '<input type="hidden" name="'.$key.'" value="'.$val.'" />';
		echo "\n";
	}
	?>
	<script type="text/javascript">
				
				document.paypal.submit();
				
			</script>
</form>
<?php 
	return "";
	

}

	/**
	 * Processes the payment form
	 * and returns HTML to be displayed to the user
	 * generally with a success/failed message
	 *
	 * @param $data     array       form post data
	 * @return string   HTML to display
	 */
	function _postPayment( $data )
	{
		// Process the payment
		$input=JFactory::getApplication()->input;
		$paction = $input->getString('paction');
		
		$vars = new JObject();
		switch ($paction)
		{
			case "display_message":
				$order_number=JRequest::getVar('order_number');
				$status=$_POST['payment_status'];;
				$transactionNo=$_POST['txn_id'];
				JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_bookpro/tables' );
				$order = JTable::getInstance('Orders', 'Table');
				$order->load(array('order_number'=>$order_number));
				$order->sendemail='none';
				return $order;
				break;
			case "process":
				return $this->_processSale();
				$app =JFactory::getApplication();
				$app->close();
				break;
			case "cancel":
				$vars->message = JText::_( 'COM_BOOKPRO_PAYPAL_MESSAGE_CANCEL' );
				$html = $this->_getLayout('message', $vars);
				break;
			default:
				$vars->message = JText::_( 'COM_BOOKPRO_PAYPAL_MESSAGE_INVALID_ACTION' );
				$html = $this->_getLayout('message', $vars);
				break;
		}
		return $html;
	}

	/**
	 * Prepares variables for the payment form
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

	/************************************
	 * Note to 3pd:
	*
	* The methods between here
	* and the next comment block are
	* specific to this payment plugin
	*
	************************************/

	/**
	 * Gets the Paypal gateway URL
	 *
	 * @param boolean $full
	 * @return string
	 * @access protected
	 */
	function _getPostUrl($full = true)
	{
		$url = $this->params->get('sandbox') ? 'www.sandbox.paypal.com' : 'www.paypal.com';

		if ($full)
		{
			$url = 'https://' . $url . '/cgi-bin/webscr';
		}

		return $url;
	}


	/**
	 * Gets the value for the Paypal variable
	 *
	 * @param string $name
	 * @return string
	 * @access protected
	 */
	function _getParam( $name, $default='' )
	{
		$return = $this->params->get($name, $default);
			
		$sandbox_param = "sandbox_$name";
		$sb_value = $this->params->get($sandbox_param);
		if ($this->params->get('sandbox') && !empty($sb_value))
		{
			$return = $this->params->get($sandbox_param, $default);
		}

		return $return;
	}

	private function validateIPN(){
		
		$host = $this->_getPostUrl(true);
		
		$raw_post_data = file_get_contents('php://input');
		$raw_post_array = explode('&', $raw_post_data);
		$myPost = array();
		foreach ($raw_post_array as $keyval) {
			$keyval = explode ('=', $keyval);
			if (count($keyval) == 2)
				$myPost[$keyval[0]] = urldecode($keyval[1]);
		}
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';
		if(function_exists('get_magic_quotes_gpc')) {
			$get_magic_quotes_exists = true;
		}
		foreach ($myPost as $key => $value) {
			if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
				$value = urlencode(stripslashes($value));
			} else {
				$value = urlencode($value);
			}
			$req .= "&$key=$value";
		}


		// STEP 2: Post IPN data back to paypal to validate

		$ch = curl_init($host);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

		// In wamp like environments that do not come bundled with root authority certificates,
		// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
		// of the certificate as shown below.
		// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');

		if( !($res = curl_exec($ch)) ) {
			// error_log("Got " . curl_error($ch) . " when processing IPN data");
			curl_close($ch);
			exit;
		}
		curl_close($ch);
		
		if (strcmp ($res, "VERIFIED") == 0) {
			
			 return true;
		}
		else {
			return false;
		}

	}

	/**
	 * Processes the sale payment
	 *
	 * @param array $data IPN data
	 * @return boolean Did the IPN Validate?
	 * @access protected
	 */

	function _processSale()
	{
		
		jimport('joomla.log.log');
		JLog::addLogger ( array ('text_file' => 'booking.txt'), JLog::ALL, array ('com_bookpro'));
		
		$data = JRequest::get('post');
					
		$order_number=JRequest::getVar('order_number');
					
			if ($order_number)
			{
				// load the orderpayment record and set some values
					
				$item_name = $data['item_name'];
				$item_number = $data['item_number'];
				$payment_status = $data['payment_status'];
				$payment_amount = $data['mc_gross'];
				$payment_currency = $data['mc_currency'];
				$txn_id = $data['txn_id'];
				$receiver_email = $data['receiver_email'];
				$payer_email = $data['payer_email'];
				
					
				JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_bookpro/tables' );
				$order = JTable::getInstance('Orders', 'Table');
				$order->load(array('order_number'=>$order_number));
				$order->tx_id       = $txn_id;
				
				JLog::add ( new JLogEntry ( 'Paypal status:'.$payment_status, JLog::INFO,'com_bookpro' ));
				
				if($payment_status == 'Completed'){
					$order->pay_status   = 'SUCCESS';
					$order->order_status   = 'CONFIRMED';

				
				}
				
				//create paylog
				$db=JFactory::getDbo();
		    	$lists=$db->getTableList();
		    	$prefix=JFactory::getApplication()->get('dbprefix').'bookpro_paylog';
		    	if(in_array($prefix, $lists)){
		    		JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');
		    		$paylog=JTable::getInstance('paylog', 'table');
		    		$paylog->order_id=$order->id;
		    		$paylog->receiver="Merchant";
					$paylog->gateway="offline";
		    		$paylog->tx_id='Not available';
		    		$paylog->store();
		    	}
		    	///
				
				if($payment_status == 'Refunded' || $payment_status == 'Reversed'){
					$order->pay_status   = 'REFUNDED';
				}
				$order->store();
			
			return $order;
	}
}
}
