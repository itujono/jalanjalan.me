<?php

/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bookpro.php 80 2012-08-10 09:25:35Z quannv $
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );
class BookProHelper {
	
	/**
	 * Helper to render layout
	 * 
	 * @param unknown $name        	
	 * @param unknown $data        	
	 * @param string $path        	
	 * @return string
	 */
	static function getOrderLink($order_number,$email){
		return JURI::root () . 'index.php?option=com_bookpro&view=orderdetail&email='.$email.'&order_number=' . $order_number;
	}
	static function renderLayout($name, $data, $path = '/components/com_bookpro/layouts') {
		$path = JPATH_ROOT . $path;
		return JLayoutHelper::render ( $name, $data, $path );
	}
	static function isAgent() {
		$checked = false;
		$user = JFactory::getUser ();
		if (JComponentHelper::getParams ( 'com_bookpro' )->get ( 'agent_usergroup' ) && $user->groups) {
			if (in_array ( JComponentHelper::getParams ( 'com_bookpro' )->get ( 'agent_usergroup' ), $user->groups )) {
				$checked = true;
			}
		}
		
		return $checked;
	}
	static function getCustomerGroupSelect($selected) {
		$config = JComponentHelper::getParams ( 'com_bookpro' );
		$agent_group = $config->get ( 'agent_usergroup' );
		$option [] = JHtml::_ ( 'select.option', 0, JText::_ ( "COM_BOOKPRO_SELECT_CUSTOMER_GROUP" ) );
		$option [] = JHtml::_ ( 'select.option', $agent_group, JText::_ ( "COM_BOOKPRO_RESELLER" ) );
		$option [] = JHtml::_ ( 'select.option',$config->get ( 'driver_usergroup' ), JText::_ ( "COM_BOOKPRO_DRIVER" ) );
		
		//$option [] = JHtml::_ ( 'select.option', - 1, JText::_ ( "COM_BOOKPRO_GUEST" ) );
		
		return JHtml::_ ( 'select.genericlist', $option, 'filter_group_id', 'class="input input-medium" id="filter_group_id"', 'value', 'text', $selected );
	}
	static function getRangeSelect($selected) {
		$option [] = JHtml::_ ( 'select.option', 0, JText::_ ( "COM_BOOKPRO_QUICK_FILTER_DATE" ) );
		$option [] = JHtml::_ ( 'select.option', 'today', JText::_ ( "COM_BOOKPRO_TODAY" ) );
		$option [] = JHtml::_ ( 'select.option', 'past_week', JText::_ ( "COM_BOOKPRO_LAST_WEEK" ) );
		$option [] = JHtml::_ ( 'select.option', 'past_1month', JText::_ ( "COM_BOOKPRO_LAST_MONTH" ) );
		return JHtml::_ ( 'select.genericlist', $option, 'filter_range', 'class="input input-medium"', 'value', 'text', $selected );
	}
	static function setSubmenu($set= null) {
		AImporter::helper ('adminui');
		AdminUIHelper::startAdminArea ($set);
		
	}
	static function getCountrySelect($select) {
		$model = new BookProModelCountries ();
		$state = $model->getState ();
		$state->set ( 'list.start', 0 );
		$state->set ( 'list.limit', 0 );
		$fullList = $model->getItems ();
		return AHtml::getFilterSelect ( 'country_id', JText::_ ( 'COM_BOOKPRO_SELECT_COUNTRY' ), $fullList, $select, true, '', 'id', 'country_name' );
	}
	
	/**
	 * Execute sql file
	 * @param unknown $sqlfile
	 */
	static function runsql($sqlfile) {
		$db = JFactory::getDbo ();
		$filename=JPATH_COMPONENT_ADMINISTRATOR.'/sql/'.$sqlfile;
		$templine = '';
		// Read in entire file
		$lines = file ( $filename );
		// Loop through each line
		foreach ( $lines as $line ) {
			// Skip it if it's a comment
			if (substr ( $line, 0, 2 ) == '--' || $line == '')
				continue;
			// Add this line to the current segment
			$templine .= $line;
			// If it has a semicolon at the end, it's the end of the query
			if (substr ( trim ( $line ), - 1, 1 ) == ';') {
				// Perform the query
				$db->setQuery($templine);
				$db->execute();
				echo "insert row";
				$templine = '';
			}
		}
	}
	
	
	static function getGender() {
		return array (
				array (
						'value' => 'M',
						'text' => JText::_ ( 'COM_BOOKPRO_MALE' ) 
				),
				array (
						'value' => 'F',
						'text' => JText::_ ( 'COM_BOOKPRO_FEMALE' ) 
				) 
		);
	}
	static function getGenderSelect($selected) {
		$data = array (
				array (
						'value' => 'M',
						'text' => JText::_ ( 'COM_BOOKPRO_MALE' ) 
				),
				array (
						'value' => 'F',
						'text' => JText::_ ( 'COM_BOOKPRO_FEMALE' ) 
				) 
		);
		return JHtml::_ ( 'select.genericlist', $data, 'psform[gender][]', 'class="input input-small"', 'value', 'text', 1 );
	}
	static function formatGender($value) {
		if ($value == "M") {
			return JText::_ ( 'COM_BOOKPRO_MALE' );
		} else if ($value == "F") {
			return JText::_ ( 'COM_BOOKPRO_FEMALE' );
		} else
			return 'N/A';
	}

	static function getCountryList($name, $select, $att = '', $ordering = "id") {
		if (! class_exists ( 'BookProModelCountries' )) {
			AImporter::model ( 'countries' );
		}
		$model = new BookProModelCountries ();
		
		$state = $model->getState ();
		$state->set ( 'list.order', $ordering );
		$state->set ( 'list.order_dir', 'ASC' );
		$state->set ( 'list.limit', NULL );
		
		$fullList = $model->getItems ();
		
		return AHtml::getFilterSelect ( $name, JText::_ ( "COM_BOOKPRO_SELECT_COUNTRY" ), $fullList, $select, false, $att, 'id', 'country_name' );
	}
	static function getGroupList($name, $select = 0, $att = '', $ordering = "id") {
		$db = JFactory::getDbo ();
		
		$query = $db->getQuery ( true );
		$query->select ( '*' )->from ( '#__bookpro_cgroup' )->order ( 'id ASC' );
		$db->setQuery ( $query );
		$list = $db->loadObjectList ();
		
		return JHtml::_ ( 'select.genericlist', $list, $name, $att, 'id', 'title', $select );
	}
	static function getOrderStatusList($name, $select = 0, $att = '') {
		AImporter::helper ( 'orderstatus' );
		OrderStatus::init ();
		return JHtml::_ ( 'select.genericlist', OrderStatus::$map, $name, $att, 'value', 'text', $select );
	}
	static function displayPaymentStatus($value) {
		return JText::_ ( 'COM_BOOKPRO_PAYMENT_STATUS_' . strtoupper ( $value ) );
	}
	static function displayOrderStatus($value) {
		return JText::_ ( 'COM_BOOKPRO_ORDER_STATUS_' . strtoupper ( $value ) );
	}
	static function checkAgentStatus(){
		
		$agent_plg= JPluginHelper::getPlugin('joombooking','jb_agent');
		if($agent_plg){
			return json_decode($agent_plg->params,true);
		}else {
			return false;
		}
	}
	
	
	/**
	 * Clean code from SUP tag.
	 *
	 * @param string $code        	
	 * @return string cleaned code
	 */
	static function cleanSupTag($code) {
		$code = str_replace ( array (
				'<sup>',
				'</sup>' 
		), '', $code );
		return $code;
	}
	
	
	
	
	static function formatName($person, $safe = false) {
		$parts = array ();
		
		$person->firstname = JString::trim ( $person->firstname );
		$person->lastname = JString::trim ( $person->lastname );
		
		if ($person->firstname) {
			$parts [] = $person->firstname;
		}
		if ($person->lastname) {
			$parts [] = $person->lastname;
		}
		
		$name = JString::trim ( implode ( ' ', $parts ) );
		if ($safe) {
			$name = htmlspecialchars ( $name, ENT_QUOTES, ENCODING );
		}
		return $name;
	}
	function formatPassengerName(&$flight, $safe = false) {
		$parts = array ();
		
		$flight->desto = JString::trim ( $person->firstname );
		$flight->lastname = JString::trim ( $person->lastname );
		
		if ($person->firstname) {
			$parts [] = $person->firstname;
		}
		if ($person->lastname) {
			$parts [] = $person->lastname;
		}
		
		$name = JString::trim ( implode ( ' ', $parts ) );
		if ($safe) {
			$name = htmlspecialchars ( $name, ENT_QUOTES, ENCODING );
		}
		return $name;
	}
	
	
	
	
	static function getBlockObjectTypes($name, $selected = null, $attr = '', $text = '') {
		$pgroups = explode ( ';', $text != '' ? $text : JText::_ ( 'COM_BOOKPRO_BLOCK_OBJECTTYPES' ) );
		
		$result = array ();
		for($i = 0; $i < count ( $pgroups ); $i ++) {
			$tmp = explode ( ':', $pgroups [$i] );
			$obj = new stdClass ();
			$obj->value = $tmp [0];
			$obj->text = JText::_ ( 'COM_BOOKPRO_BLOCK_OBJECTTYPE_' . $tmp [1] );
			$result [] = $obj;
		}
		
		return JHtml::_ ( 'select.genericlist', $result, $name, $attr, 'value', 'text', $selected );
	}
	
	
	
	
	
	
	/**
	 * Get difference between two dates in days count.
	 *
	 * @param $dateEnd BookingDate        	
	 * @param $dateStart BookingDate        	
	 * @return int
	 */
	function getCountDays(&$dateEnd, &$dateStart) {
		$difference = $dateEnd->uts - $dateStart->uts;
		$countDays = $difference ? round ( $difference / DAY_LENGTH ) : 1;
		return $countDays;
	}
	
	static function bscol($span='12'){
		
		$config=JBFactory::getConfig();
		$bs=$config->get('bs');
		if($bs=="bs2"){
			
			return "span".$span;
		}else{
			
			return "col-md-".$span;
		}
		
	}
	
	static function bsrow(){
	
		$config=JBFactory::getConfig();
		$bs=$config->get('bs');
		if($bs=="bs2"){
				
			return "row-fluid";
		}else{
				
			return "row";
		}
	
	}
	

	
	
	function timeToFloat($time, $tzoffset = 0) {
		$unixTimeOffset = ($unixTime = strtotime ( $time )) + $tzoffset;
		$timeToFloat = round ( date ( 'G', $unixTimeOffset ) + date ( 'i', $unixTimeOffset ) / 60, 2 );
		if (date ( 'H:i:s', $unixTimeOffset ) < date ( 'H:i:s', $unixTime ))
			$timeToFloat += 24;
		return $timeToFloat;
	}
	
	/**
	 * Convert float value to MySQL time value.
	 *
	 * @param float $value        	
	 * @return string
	 */
	function floatToTime($value) {
		if (($hour = floor ( $value )) < 10)
			$hour = '0' . $hour;
		if (($minute = round ( ($value - $hour) * 60 )) < 10)
			$minute = '0' . $minute;
		return $hour . ':' . $minute;
	}
	
	
		
	
	/**
	 * Number into database format.
	 * For example: 4 return like 04
	 *
	 * @param $number int        	
	 * @return string
	 */
	function intToDBFormat($number) {
		$number = ( int ) $number;
		if ($number < 10) {
			$number = '0' . $number;
		}
		return $number;
	}
	
	
	
	/**
	 * Save if user wiev subject into browser cookies.
	 *
	 * @param $id subject
	 *        	id
	 * @param $model BookingModelSubject
	 *        	model to store hits into database
	 */
	
	
	

	
	/**
	 * Convert HTML code to plain text.
	 * Paragraphs (tag <p>) and
	 * break line (tag <br/>) replace by end line sign (\n or \r\n)
	 * and remove all others HTML tags.
	 *
	 * @param $string to
	 *        	convert
	 * @return $string converted to plain text
	 */
	function html2text($string) {
		$string = str_replace ( '</p>', '</p>' . PHP_EOL, $string );
		$string = str_replace ( '<br />', PHP_EOL, $string );
		$string = strip_tags ( $string );
		
		return $string;
	}
	function getFileThumbnail($filename) {
		$ext = strtolower ( JFile::getExt ( $filename ) );
		
		// icons taken from JoomDOC
		$icons = array ();
		$icons ['32-pdf.png'] = array (
				'pdf' 
		);
		$icons ['32-ai-eps-jpg-gif-png.png'] = array (
				'ai',
				'eps',
				'jpg',
				'jpeg',
				'gif',
				'png',
				'bmp' 
		);
		$icons ['32-xls-xlsx-csv.png'] = array (
				'xls',
				'xlsx',
				'csv' 
		);
		$icons ['32-ppt-pptx.png'] = array (
				'ppt',
				'pptx' 
		);
		$icons ['32-doc-rtf-docx.png'] = array (
				'doc',
				'rtf',
				'docx' 
		);
		$icons ['32-mpeg-avi-wav-ogg-mp3.png'] = array (
				'mpeg',
				'avi',
				'ogg',
				'mp3' 
		);
		$icons ['32-tar-gzip-zip-rar.png'] = array (
				'tar',
				'gzip',
				'zip',
				'rar' 
		);
		$icons ['32-mov.png'] = array (
				'mov' 
		);
		$icons ['32-fla'] = array (
				'fla' 
		);
		$icons ['32-fw'] = array (
				'fw' 
		);
		$icons ['32-indd.png'] = array (
				'indd' 
		);
		$icons ['32-mdb-ade-mda-mde-mdp.png'] = array (
				'mdb',
				'ade',
				'mda',
				'mde',
				'mdp' 
		);
		$icons ['32-psd.png'] = array (
				'psd' 
		);
		$icons ['32-pub.png'] = array (
				'pub' 
		);
		$icons ['32-swf.png'] = array (
				'swf' 
		);
		$icons ['32-asp-php-js-asp-css.png'] = array (
				'asp',
				'php',
				'js',
				'css' 
		);
		
		foreach ( $icons as $icon => $extension )
			if (in_array ( $ext, $extension )) {
				$thumb = $icon;
				break;
			}
		
		if (! isset ( $thumb ))
			$thumb = '32-default.png';
		
		return IMAGES . 'icons_file/' . $thumb;
	}
	
	
	/**
	 *
	 * @param string $from        	
	 * @param string $fromname        	
	 * @param string $email        	
	 * @param string $subject        	
	 * @param string $body        	
	 * @param boolean $htmlMode        	
	 * @return boolean
	 */
	static function sendMail($from, $fromname, $email, $subject, $body, $htmlMode) {
		if (! $htmlMode)
			$body = BookProHelper::html2text ( $body );
		
		$mainframe = JFactory::getApplication ();
		$from = $mainframe->getCfg ( 'mailfrom' );
		
		if (is_array ( ($emails = explode ( ',', str_replace ( ';', ',', $email ) )) )) {
			$mail = JFactory::getMailer ();
			//foreach ( $emails as $email ) {
				$mail->sendMail ( $from, $fromname, $emails, $subject, $body, $htmlMode, null, null, null );
			//}
		}
	}
	public static function getActions() {
		$user = JFactory::getUser ();
		$result = new JObject ();
		
		$assetName = 'com_bookpro';
		
		$actions = array (
				'core.admin',
				'core.manage',
				'core.create',
				'core.edit',
				'core.edit.own',
				'core.edit.state',
				'core.delete' 
		);
		
		foreach ( $actions as $action ) {
			$result->set ( $action, $user->authorise ( $action, $assetName ) );
		}
		
		return $result;
	}
	
	static function addJqueryValidate(){
		$lang=JFactory::getLanguage();
		$local=substr($lang->getTag(),0,2);
	
		$document = JFactory::getDocument();
		$document->addScript("//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js");
		if($local !='en'){
			$document->addScript("//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/localization/messages_".$local.".js");
		}
		$document->addScript("http://jqueryvalidation.org/files/dist/additional-methods.min.js");
	
	}
	
	
	public static function booleanlist($name, $attribs = array(), $selected = null, $yes = 'JYES', $no = 'JNO', $id = false)
	{
		$arr = array(JHtml::_('select.option', '0', JText::_($no)), JHtml::_('select.option', '1', JText::_($yes)));
	
		return BookProHelper::radiolist('select.radiolist', $arr, $name, $attribs, 'value', 'text', (int) $selected, $id);
	}
	public static function radiolist($data, $name, $attribs = null, $optKey = 'value', $optText = 'text', $selected = null, $idtag = false,
			$translate = false)
	{
	
		if (is_array($attribs))
		{
			$attribs = ArrayHelper::toString($attribs);
		}
	
		$id_text = $idtag ? $idtag : $name;
	
		$html = '';
	
		foreach ($data as $obj)
		{
			$k = $obj->$optKey;
			$t = $translate ? JText::_($obj->$optText) : $obj->$optText;
			$id = (isset($obj->id) ? $obj->id : null);
	
			$extra = '';
			$id = $id ? $obj->id : $id_text . $k;
			
			
	
			if (is_array($selected))
			{
				foreach ($selected as $val)
				{
					$k2 = is_object($val) ? $val->$optKey : $val;
	
					if ($k == $k2)
					{
						$extra .= ' selected="selected" ';
						break;
					}
				}
			}
			else
			{
				$extra .= ((string) $k == (string) $selected ? ' checked="checked" ' : '');
			}
	
			$html .= "\n\t" . '<label for="' . $id . '" id="' . $id . '-lbl" class="radio-inline">';
			$html .= "\n\t\n\t" . '<input type="radio" name="' . $name . '" id="' . $id . '" value="' . $k . '" ' . $extra
			. $attribs . ' />' . $t;
			$html .= "\n\t" . '</label>';
		}
	
		$html .= "\n";
		//$html .= '</div>';
		$html .= "\n";
	
		return $html;
	}
	static function getLocal(){
	
		$lang=JFactory::getLanguage();
		$local=substr($lang->getTag(),0,2);
		if($local=="en")
			$local="en-GB";
	
		return $local;
	
	}
}

?>
