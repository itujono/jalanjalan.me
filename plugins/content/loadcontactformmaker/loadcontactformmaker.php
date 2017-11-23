<?php

 /**

 * @package LoadContactFormMaker

 * @author Web-Dorado

 * @copyright (C) 2011 Web-Dorado. All rights reserved.

 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html

 **/

 

// No direct access allowed to this file

defined( '_JEXEC' ) or die( 'Restricted access' );

 

// Import Joomla! Plugin library file

jimport('joomla.plugin.plugin');

jimport('joomla.filesystem.file');

jimport('joomla.filesystem.folder');

defined( 'DS' )  or define('DS', DIRECTORY_SEPARATOR);



if(JFolder::exists(JPATH_SITE.DS.'components'.DS.'com_formmaker'))
	require_once JPATH_SITE.DS.'components'.DS.'com_formmaker'.DS.'recaptchalib.php';
else
	require_once JPATH_SITE.DS.'components'.DS.'com_contactformmaker'.DS.'recaptchalib.php';

$lang =  JFactory::getLanguage();

$lang->load('com_contactformmaker',JPATH_BASE);



//The Content plugin Loadmodule

class plgContentLoadcontactformmaker extends JPlugin

{

	/**

	* Plugin that loads module positions within content

	*/

// onPrepareContent, meaning the plugin is rendered at the first stage in preparing content for output

	public function onContentPrepare($context, &$row, &$params, $page=0 )

	{

      

	    // A database connection is created

		$db = JFactory::getDBO();

		// simple performance check to determine whether bot should process further

		if ( JString::strpos( $row->text, 'loadcontactform' ) === false ) {

			return true;

		}

	 	// expression to search for

	 	$regex = '/{loadcontactform\s*.*?}/i';

 

		// check whether plugin has been unpublished

		if ( !$this->params->get( 'enabled', 1 ) ) {

			$row->text = preg_replace( $regex, '', $row->text );

			return true;

		}

 

	 	// find all instances of plugin and put in $matches

		preg_match_all( $regex, $row->text, $matches );

		//print_r($matches);

		// Number of plugins

	 	$count = count( $matches[0] );

	 	// plugin only processes if there are any instances of the plugin in the text

	 	if ( $count ) {

			// Get plugin parameters

	 		$this->_process( $row, $matches, $count, $regex );

		}

		// No return value

	}

// The proccessing function

	protected function _process( &$row, &$matches, $count, $regex )

	{

	

		for ( $i=0; $i < $count; $i++ )

		{

	 		$load = str_replace( 'loadcontactform', '', $matches[0][$i] );

	 		$load = str_replace( '{', '', $load );

	 		$load = str_replace( '}', '', $load );

 			$load = trim( $load );

			if(!$load)

				continue;



			$modules	= $this->_load( $load );

			

			$plugin = JPluginHelper::getPlugin('content', 'emailcloak');

			if (isset($plugin->type))

			{ 

			   $modules="{emailcloak=off}".$modules;

			}

	

			$row->text 	= str_replace( $matches[0][$i] , $modules, $row->text );

	 	}

 

	  	// removes tags without matching module positions

		$row->text = preg_replace( $regex, '', $row->text );

	}

// The function who takes care for the 'completing' of the plugins' actions : loading the module(s)

	protected function _load( $form )

	{

		$result = $this->showform( $form);

		if(!$result)

			return;

			

		$ok		= $this->savedata($form, $result[0] );



		if(is_numeric($ok))		
			$this->remove($ok);
			
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__contactformmaker_options"); 
		$globalParams = $db->loadObject();
		if ($db->getErrorNum())	{echo $db->stderr(); return false;}	

		$map_key = isset($globalParams->map_key) ? '&key='.$globalParams->map_key : '';


		$document = JFactory::getDocument();

		$document->addScript(JURI::root(true).'/components/com_contactformmaker/views/contactformmaker/tmpl/contactform.js');

		$document->addScript(JURI::root(true).'/components/com_contactformmaker/views/contactformmaker/tmpl/jquery-ui.js');
		$document->addScript(JURI::root(true).'/components/com_contactformmaker/views/contactformmaker/tmpl/contactformnoconflict.js');
		$document->addScript(JURI::root(true).'/components/com_contactformmaker/views/contactformmaker/tmpl/if_gmap.js');

		$document->addScript( JURI::root(true).'/components/com_contactformmaker/views/contactformmaker/tmpl/contactformmain.js');

		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')
			$document->addScript('https://maps.google.com/maps/api/js?sensor=false'.$map_key);
		else	
			$document->addScript('http://maps.google.com/maps/api/js?sensor=false'.$map_key);

		JHTML::_('behavior.tooltip');	

		JHTML::_('behavior.calendar');


		return $this->defaultphp($result[0], $result[1], $result[2], $result[3], $result[4], $form,$ok);		

	}

	

	protected function showform($id)

	{

		$input_get = JFactory::getApplication()->input;



		$Itemid=$input_get->getString('Itemid'.$id);



		$db = JFactory::getDBO();

		$db->setQuery("SELECT * FROM #__contactformmaker WHERE id=".$db->escape((int)$id) ); 

		$row = $db->loadObject();

		if ($db->getErrorNum())	{echo $db->stderr();return false;}	

		

		if(!$row)

			return false;

			

		if($row->published!=1)

			return false;	

	

		$test_theme = $input_get->getString('test_theme');

		$row->theme = (isset($test_theme) ? $test_theme : $row->theme);

		$db->setQuery("SELECT css FROM #__contactformmaker_themes WHERE id=".$db->escape((int)$row->theme) ); 

		$form_theme = $db->loadResult();

		if ($db->getErrorNum())	{echo $db->stderr(); return false;}	



		$label_id= array();

		$label_type= array();

			

		$label_all	= explode('#****#',$row->label_order);

		$label_all 	= array_slice($label_all,0, count($label_all)-1);   

		

		foreach($label_all as $key => $label_each) 

		{

			$label_id_each=explode('#**id**#',$label_each);

			array_push($label_id, $label_id_each[0]);

			

			$label_order_each=explode('#**label**#', $label_id_each[1]);

			

			array_push($label_type, $label_order_each[1]);

		}

		

		return array($row, $Itemid, $label_id, $label_type, $form_theme);

	}



	protected function savedata($id, $form)

	{	
	
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__contactformmaker_options"); 
		$globalParams = $db->loadObject();
		if ($db->getErrorNum())	{echo $db->stderr(); return false;}	
		$mainframe = JFactory::getApplication();
		$input_get = JFactory::getApplication()->input;

		$all_files=array();

		$correct=false;

		@session_start();



		$captcha_input=$input_get->getString("captcha_input");

		$recaptcha_response_field=$input_get->getString("recaptcha_response_field");
		$recaptcha_response_field_new=$input_get->getString("g-recaptcha-response");
		$counter=$input_get->getString("counter".$id);

		if(isset($counter))

		{	

			if (isset($captcha_input))

			{				

				$session_wd_captcha_code=isset($_SESSION[$id.'_wd_captcha_code'])?$_SESSION[$id.'_wd_captcha_code']:'-';



				if($captcha_input==$session_wd_captcha_code)

				{

					$correct=true;

				}

				else

				{

							echo "<script> alert('".JText::_('WDF_INCORRECT_SEC_CODE')."');

						</script>";

				}

			}	

			

			else

				if(isset($recaptcha_response_field))

				{	

				$privatekey = $form->private_key;

	

					$resp = recaptcha_check_answer ($privatekey,

													$_SERVER["REMOTE_ADDR"],

													$_POST["recaptcha_challenge_field"],

													$recaptcha_response_field);

					if($resp->is_valid)

					{

						$correct=true;

					}

					else

					{

								echo "<script> alert('".JText::_('WDF_INCORRECT_SEC_CODE')."');

							</script>";

					}

				}	
				elseif (isset($recaptcha_response_field_new)){ 
					$privatekey = isset($globalParams->private_key) ? $globalParams->private_key : '';
					
					$url = 'https://www.google.com/recaptcha/api/siteverify'; 
					$data = array( 'secret' => $privatekey, 'response' => $recaptcha_response_field_new, 'remoteip' => $_SERVER['REMOTE_ADDR'] ); 
					$curlConfig = array( CURLOPT_URL => $url, CURLOPT_POST => true, CURLOPT_RETURNTRANSFER => true, CURLOPT_POSTFIELDS => $data ); 
					
					$ch = curl_init();
					curl_setopt_array($ch, $curlConfig); 
					$response = curl_exec($ch); 
					curl_close($ch);
					$jsonResponse = json_decode($response);
					if ($jsonResponse->success == "true"){ 
						$correct = TRUE; 
					} else { 
						echo "<script> alert('".JText::_('WDF_INCORRECT_SEC_CODE')."'); </script>"; 
					} 
				}
			

				else	

				

					$correct=true;				



				



				if($correct)

				{

				

					$ip=$_SERVER['REMOTE_ADDR'];



					$db->setQuery("SELECT ip FROM #__contactformmaker_blocked WHERE ip LIKE '%".$db->escape($ip)."%'"); 
					$db->query();
					$blocked_ip = $db->loadResult();

					if($blocked_ip)		
					$mainframe->redirect($_SERVER["REQUEST_URI"], addslashes(JText::_('WDF_BLOCKED_IP')));
					

					$all_files=$this->save_db($counter, $id);


					if(is_numeric($all_files))		

						$this->remove($all_files);

					else

						if(isset($counter))

							$this->gen_mail($counter, $all_files, $id);

		

				}

	



			return $all_files;

		}



		return $all_files;

			

			

	}

	

	protected function save_db($counter,$id)

	{

		$input_get = JFactory::getApplication()->input;

		$chgnac=true;	

		$all_files=array();


		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_contactformmaker'.DS.'tables');

		$form = JTable::getInstance('contactformmaker', 'Table');

		$form->load( $id);

	
			

		$label_id= array();

		$label_label= array();

		$label_type= array();



		$label_all	= explode('#****#',$form->label_order_current);		
		$label_all 	= array_slice($label_all,0, count($label_all)-1);   

		

		foreach($label_all as $key => $label_each) 

		{

			$label_id_each=explode('#**id**#',$label_each);

			array_push($label_id, $label_id_each[0]);

			

			$label_order_each=explode('#**label**#', $label_id_each[1]);

			

			array_push($label_label, $label_order_each[0]);

			array_push($label_type, $label_order_each[1]);

		}
		
		$disabled_fields=explode(',',$form->disabled_fields);
		$disabled_fields 	= array_slice($disabled_fields,0, count($disabled_fields)-1);  
		

		$db = JFactory::getDBO();

		$db->setQuery("SELECT MAX( group_id ) FROM #__contactformmaker_submits" ); 

		$db->query();

		$max = $db->loadResult();


			foreach($label_type as $key => $type)
			{

				$value='';

				if($type=="type_submit_reset" or $type=="type_map" or $type=="type_editor" or  $type=="type_captcha" or  $type=="type_recaptcha" or $type=="type_send_copy")

					continue;



				$i=$label_id[$key];

				if(!in_array($i,$disabled_fields))
				{
				switch ($type)

				{

					case 'type_text':

					case 'type_password':

					case 'type_textarea':

					case "type_submitter_mail":

					case "type_own_select":								

					case "type_number":				

					{

						$value=$input_get->getString('wdform_'.$i."_element".$id);

						break;

					}

					case "type_phone":

					{

						$value=$input_get->getString('wdform_'.$i."_element_first".$id).' '.$input_get->getString('wdform_'.$i."_element_last".$id);

							

						break;

					}

		

					case "type_name":

					{

				

						$element_title=$input_get->getString('wdform_'.$i."_element_title".$id);

						if(isset($element_title))

							$value=$input_get->getString('wdform_'.$i."_element_title".$id).' '.$input_get->getString('wdform_'.$i."_element_first".$id).' '.$input_get->getString('wdform_'.$i."_element_last".$id).' '.$input_get->getString('wdform_'.$i."_element_middle".$id);

						else

							$value=$input_get->getString('wdform_'.$i."_element_first".$id).' '.$input_get->getString('wdform_'.$i."_element_last".$id);

							

						break;

					}

		

					case 'type_address':

					{

						$value='*#*#*#';

						$element=$input_get->getString('wdform_'.$i."_street1".$id);

						if(isset($element))

						{

							$value=$input_get->getString('wdform_'.$i."_street1".$id);

							break;

						}

						

						$element=$input_get->getString('wdform_'.$i."_street2".$id);

						if(isset($element))

						{

							$value=$input_get->getString('wdform_'.$i."_street2".$id);

							break;

						}

						

						$element=$input_get->getString('wdform_'.$i."_city".$id);

						if(isset($element))

						{

							$value=$input_get->getString('wdform_'.$i."_city".$id);

							break;

						}

						

						$element=$input_get->getString('wdform_'.$i."_state".$id);

						if(isset($element))

						{

							$value=$input_get->getString('wdform_'.$i."_state".$id);

							break;

						}

						

						$element=$input_get->getString('wdform_'.$i."_postal".$id);

						if(isset($element))

						{

							$value=$input_get->getString('wdform_'.$i."_postal".$id);

							break;

						}

						

						$element=$input_get->getString('wdform_'.$i."_country".$id);

						if(isset($element))

						{

							$value=$input_get->getString('wdform_'.$i."_country".$id);

							break;

						}

						

						break;

					}


					case "type_radio":				

					{

						$element=$input_get->getString('wdform_'.$i."_other_input".$id);

						if(isset($element))

						{

							$value=$element;	

							break;

						}

						

						$value=$input_get->getString('wdform_'.$i."_element".$id);

						break;

					}

					

					case "type_checkbox":				

					{

						$start=-1;

						$value='';

						for($j=0; $j<100; $j++)

						{

						

							$element=$input_get->getString('wdform_'.$i."_element".$id.$j);

			

							if(isset($element))

							{

								$start=$j;

								break;

							}

						}

							

						$other_element_id=-1;

						$is_other=$input_get->getString('wdform_'.$i."_allow_other".$id);

						if($is_other=="yes")

						{

							$other_element_id=$input_get->getString('wdform_'.$i."_allow_other_num".$id);

						}

						

						if($start!=-1)

						{

							for($j=$start; $j<100; $j++)

							{

								$element=$input_get->getString('wdform_'.$i."_element".$id.$j);

								if(isset($element))

								if($j==$other_element_id)

								{

									$value=$value.$input_get->getString('wdform_'.$i."_other_input".$id).'***br***';

								}

								else

								

									$value=$value.$input_get->getString('wdform_'.$i."_element".$id.$j).'***br***';

							}

						}

						

						break;

					}

				}



				if($type=="type_address")

					if(	$value=='*#*#*#')

						continue;



				if($type=="type_text" or $type=="type_password" or $type=="type_textarea" or $type=="type_name" or $type=="type_submitter_mail" or $type=="type_number" or $type=="type_phone")

				{

					

					$untilupload = $form->form_fields;



					$untilupload = substr($untilupload, strpos($untilupload,$i.'*:*id*:*'.$type), -1);

					$untilupload = substr($untilupload, 0, strpos($untilupload,'*:*new_field*:'));

					$untilupload = explode('*:*w_required*:*',$untilupload);

					$untilupload = $untilupload[1];

					$untilupload = explode('*:*w_unique*:*',$untilupload);

					$unique_element = $untilupload[0];

			

					if($unique_element=='yes')

					{

						$db->setQuery("SELECT id FROM #__contactformmaker_submits WHERE form_id='".$db->escape((int)$id)."' and element_label='".$db->escape($i)."' and element_value='".$db->escape($value)."'");					

						$unique = $db->loadResult();

						if ($db->getErrorNum()){echo $db->stderr();	return false;}

			

						if ($unique) 

						{

							echo "<script> alert('".JText::sprintf('WDF_UNIQUE', '"'.$label_label[$key].'"')	."');</script>";		

							return array($max+1);

						}

					}

				}

				

				$ip=$_SERVER['REMOTE_ADDR'];

				

				if($form->savedb)

				{

				$db->setQuery("INSERT INTO #__contactformmaker_submits (form_id, element_label, element_value, group_id, date, ip) VALUES('".$id."', '".$i."', '".addslashes($value)."','".($max+1)."', now(), '".$ip."')" ); 

				$rows = $db->query();

				if ($db->getErrorNum()){echo $db->stderr();	return false;}

				}

				$chgnac=false;
			}
		}


		if($chgnac)

		{		$mainframe = JFactory::getApplication();

	

				if(count($all_files)==0)

					$mainframe->redirect($_SERVER["REQUEST_URI"], addslashes(JText::_('WDF_EMPTY_SUBMIT')));

		}

		

		return $all_files;

	}

	

	protected function gen_mail($counter, $all_files, $id)

	{

	

		$input_get = JFactory::getApplication()->input;

		@session_start();

		$mainframe = JFactory::getApplication();

		$Itemid=$input_get->getString('Itemid'.$id);

		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_contactformmaker'.DS.'tables');

		$row = JTable::getInstance('contactformmaker', 'Table');

		$row->load( $id);

		$ip=$_SERVER['REMOTE_ADDR'];


			$cc=array();

			$label_order_original= array();

			$label_order_ids= array();



			$label_all	= explode('#****#',$row->label_order_current);
			$label_all 	= array_slice($label_all,0, count($label_all)-1);   

			foreach($label_all as $key => $label_each) 

			{

				$label_id_each=explode('#**id**#',$label_each);

				$label_id=$label_id_each[0];

				array_push($label_order_ids,$label_id);

				

				$label_oder_each=explode('#**label**#', $label_id_each[1]);							

				$label_order_original[$label_id]=$label_oder_each[0];

				$label_type[$label_id]=$label_oder_each[1];

			}

		
			$disabled_fields=explode(',',$row->disabled_fields);
			$disabled_fields 	= array_slice($disabled_fields,0, count($disabled_fields)-1);   	

			$list='<table border="1" cellpadding="3" cellspacing="0" style="width:600px;">';
			$list_text_mode = '';
			
			foreach($label_order_ids as $key => $label_order_id)

			{

				$i=$label_order_id;

				$type=$label_type[$i];



				if($type!="type_map" and  $type!="type_submit_reset" and  $type!="type_editor" and  $type!="type_captcha" and  $type!="type_recaptcha")

				{	

					$element_label=$label_order_original[$i];

							
					if(!in_array($i,$disabled_fields))		
					switch ($type)

					{

						case 'type_text':

						case 'type_password':

						case 'type_textarea':

						case "type_own_select":							

						case "type_number":	
						{
							$element=$input_get->getString('wdform_'.$i."_element".$id);
							if(isset($element))
							{
								$list=$list.'<tr valign="top"><td >'.$element_label.'</td><td ><pre style="font-family:inherit; margin:0px; padding:0px">'.$element.'</pre></td></tr>';	$list_text_mode=$list_text_mode.$element_label.' - '.$element."\r\n";					
							}
							break;
						
						
						}
				
						case "type_submitter_mail":
						{
							$element=$input_get->getString('wdform_'.$i."_element".$id);
							if(isset($element))
							{
								$list=$list.'<tr valign="top"><td >'.$element_label.'</td><td ><pre style="font-family:inherit; margin:0px; padding:0px">'.$element.'</pre></td></tr>';	
								$list_text_mode=$list_text_mode.$element_label.' - '.$element."\r\n";								
							}
							break;		
						}
							
						case "type_phone":
						{
							$element_first=$input_get->getString('wdform_'.$i."_element_first".$id);
							if(isset($element_first))
							{
									$list=$list.'<tr valign="top"><td >'.$element_label.'</td><td >'.$input_get->getString('wdform_'.$i."_element_first".$id).' '.$input_get->getString('wdform_'.$i."_element_last".$id).'</td></tr>';
									$list_text_mode=$list_text_mode.$element_label.' - '.$input_get->getString('wdform_'.$i."_element_first".$id).' '.$input_get->getString('wdform_'.$i."_element_last".$id)."\r\n";
							}	
							break;
						}
						
						case "type_name":
						{
							$element_first=$input_get->getString('wdform_'.$i."_element_first".$id);
							if(isset($element_first))
							{
								$element_title=$input_get->getString('wdform_'.$i."_element_title".$id);
								if(isset($element_title))
								{
									$list=$list.'<tr valign="top"><td >'.$element_label.'</td><td >'.$input_get->getString('wdform_'.$i."_element_title".$id).' '.$input_get->getString('wdform_'.$i."_element_first".$id).' '.$input_get->getString('wdform_'.$i."_element_last".$id).' '.$input_get->getString('wdform_'.$i."_element_middle".$id).'</td></tr>';
									$list_text_mode=$list_text_mode.$element_label.' - '.$input_get->getString('wdform_'.$i."_element_title".$id).' '.$input_get->getString('wdform_'.$i."_element_first".$id).' '.$input_get->getString('wdform_'.$i."_element_last".$id).' '.$input_get->getString('wdform_'.$i."_element_middle".$id)."\r\n";
								}
								else
								{
									$list=$list.'<tr valign="top"><td >'.$element_label.'</td><td >'.$input_get->getString('wdform_'.$i."_element_first".$id).' '.$input_get->getString('wdform_'.$i."_element_last".$id).'</td></tr>';
									$list_text_mode=$list_text_mode.$element_label.' - '.$input_get->getString('wdform_'.$i."_element_first".$id).' '.$input_get->getString('wdform_'.$i."_element_last".$id)."\r\n";
								}
							}	   
							break;		
						}
						
						case "type_address":
						{

							$element=$input_get->getString('wdform_'.$i."_street1".$id);
						if(isset($element))
						{
							$list=$list.'<tr valign="top"><td >'.$label_order_original[$i].'</td><td >'.$input_get->getString('wdform_'.$i."_street1".$id).'</td></tr>';
							$list_text_mode=$list_text_mode.$label_order_original[$i].' - '.$input_get->getString('wdform_'.$i."_street1".$id)."\r\n";
							break;
						}
						
						$element=$input_get->getString('wdform_'.$i."_street2".$id);
						if(isset($element))
						{
							$list=$list.'<tr valign="top"><td >'.$label_order_original[$i].'</td><td >'.$input_get->getString('wdform_'.$i."_street2".$id).'</td></tr>';
							$list_text_mode=$list_text_mode.$label_order_original[$i].' - '.$input_get->getString('wdform_'.$i."_street2".$id)."\r\n";
							break;
						}
						
						$element=$input_get->getString('wdform_'.$i."_city".$id);
						if(isset($element))
						{
							$list=$list.'<tr valign="top"><td >'.$label_order_original[$i].'</td><td >'.$input_get->getString('wdform_'.$i."_city".$id).'</td></tr>';
							$list_text_mode=$list_text_mode.$label_order_original[$i].' - '.$input_get->getString('wdform_'.$i."_city".$id)."\r\n";
							break;
						}
						
						$element=$input_get->getString('wdform_'.$i."_state".$id);
						if(isset($element))
						{
							$list=$list.'<tr valign="top"><td >'.$label_order_original[$i].'</td><td >'.$input_get->getString('wdform_'.$i."_state".$id).'</td></tr>';
							$list_text_mode=$list_text_mode.$label_order_original[$i].' - '.$input_get->getString('wdform_'.$i."_state".$id)."\r\n";
							break;
						}
						
						$element=$input_get->getString('wdform_'.$i."_postal".$id);
						if(isset($element))
						{
							$list=$list.'<tr valign="top"><td >'.$label_order_original[$i].'</td><td >'.$input_get->getString('wdform_'.$i."_postal".$id).'</td></tr>';
							$list_text_mode=$list_text_mode.$label_order_original[$i].' - '.$input_get->getString('wdform_'.$i."_postal".$id)."\r\n";
							break;
						}
						
						$element=$input_get->getString('wdform_'.$i."_country".$id);
						if(isset($element))
						{
							$list=$list.'<tr valign="top"><td >'.$label_order_original[$i].'</td><td >'.$input_get->getString('wdform_'.$i."_country".$id).'</td></tr>';
							$list_text_mode=$list_text_mode.$label_order_original[$i].' - '.$input_get->getString('wdform_'.$i."_country".$id)."\r\n";
							break;
						}
						
						break;


						}

						case "type_radio":
							{
								$element=$input_get->getString('wdform_'.$i."_other_input".$id);
								if(isset($element))
								{
									$list=$list.'<tr valign="top"><td >'.$element_label.'</td><td >'.$input_get->getString('wdform_'.$i."_other_input".$id).'</td></tr>';
									$list_text_mode=$list_text_mode.$element_label.' - '.$input_get->getString('wdform_'.$i."_other_input".$id)."\r\n";
									break;
								}	
								
								$element=$input_get->getString('wdform_'.$i."_element".$id);
								if(isset($element))
								{
									$list=$list.'<tr valign="top"><td >'.$element_label.'</td><td ><pre style="font-family:inherit; margin:0px; padding:0px">'.$element.'</pre></td></tr>';	$list_text_mode=$list_text_mode.$element_label.' - '.$element."\r\n";				
								}
								break;	
							}
							
							case "type_checkbox":
							{
								$list=$list.'<tr valign="top"><td >'.$element_label.'</td><td >';
								$list_text_mode=$list_text_mode.$element_label.' - ';	
								$start=-1;
								for($j=0; $j<100; $j++)
								{
									$element=$input_get->getString('wdform_'.$i."_element".$id.$j);
									if(isset($element))
									{
										$start=$j;
										break;
									}
								}	
								
								$other_element_id=-1;
								$is_other=$input_get->getString('wdform_'.$i."_allow_other".$id);
								if($is_other=="yes")
								{
									$other_element_id=$input_get->getString('wdform_'.$i."_allow_other_num".$id);
								}
								
						
								if($start!=-1)
								{
									for($j=$start; $j<100; $j++)
									{
										
										$element=$input_get->getString('wdform_'.$i."_element".$id.$j);
										if(isset($element))
										if($j==$other_element_id)
										{
											$list=$list.$input_get->getString('wdform_'.$i."_other_input".$id).'<br>';
											$list_text_mode=$list_text_mode.$input_get->getString('wdform_'.$i."_other_input".$id).', ';	
										}
										else
										{
											$list=$list.$input_get->getString('wdform_'.$i."_element".$id.$j).'<br>';
											$list_text_mode=$list_text_mode.$input_get->getString('wdform_'.$i."_element".$id.$j).', ';
										}	
									}
									
									$list=$list.'</td></tr>';
									$list_text_mode=$list_text_mode."\r\n";
								}
											
								
								break;
							}
							
						default: break;

					}

				

				}	

				

			}

			

			$list=$list.'</table>';

			$config = JFactory::getConfig();

			if($row->mail_from)

				$site_mailfrom = $row->mail_from;

			else

				$site_mailfrom=$config->get( 'mailfrom' );								



			if($row->mail_from_name)

				$site_fromname = $row->mail_from_name;

			else

				$site_fromname=$config->get( 'fromname' );	

				
			$attachment='';
						

			if($row->sendemail)
			if($row->send_to)
			{
				$recipient='';
				$cca = $row->mail_cc_user;
				$bcc = $row->mail_bcc_user;
				$send_tos=explode('**',$row->send_to);
				if($row->mail_from_user)
					$from = $row->mail_from_user;
				else
					$from=$config->get( 'mailfrom' );								

				if($row->mail_from_name_user)
					$fromname = $row->mail_from_name_user;
				else
					$fromname=$config->get( 'fromname' );								

				if($row->mail_subject_user)	
					$subject 	= $row->mail_subject_user;
				else
					$subject 	= $row->title;
					
				if($row->reply_to_user)
					$replyto	= $row->reply_to_user;
												
				if($row->mail_mode_user)
				{
					$mode = 1; 
					$list_user = wordwrap($list, 70, "\n", true);
					$new_script = $row->script_mail_user;
				}	
				else
				{
					$mode = 0; 
					$list_user = wordwrap($list_text_mode, 1000, "\n", true);
					$new_script = str_replace(array('<p>','</p>'),'',$row->script_mail_user);
				}	
				
				
				foreach($label_order_original as $key => $label_each) 
				{	
					$type=$label_type[$key];
						if(strpos($row->script_mail_user, "%".$label_each."%")>-1 && !in_array($key,$disabled_fields))	
						{	
							$new_value = $this->custom_fields_mail($type, $key, $id);				
							$new_script = str_replace("%".$label_each."%", $new_value, $new_script);							
						}
					
						if(strpos($fromname, "%".$label_each."%")>-1 && !in_array($key,$disabled_fields))	
						{	
							$new_value = str_replace('<br>',', ',$this->custom_fields_mail($type, $key, $id));		
							if(substr($new_value, -2)==', ')	
								$new_value = substr($new_value, 0, -2);					
							$fromname = str_replace("%".$label_each."%", $new_value, $fromname);							
						}
						
						if(strpos($subject, "%".$label_each."%")>-1 && !in_array($key,$disabled_fields))	
						{	
							$new_value = str_replace('<br>',', ',$this->custom_fields_mail($type, $key, $id));		
							if(substr($new_value, -2)==', ')	
								$new_value = substr($new_value, 0, -2);						
							$subject = str_replace("%".$label_each."%", $new_value, $subject);							
						}
				}	
				
				if(strpos($new_script, "%ip%")>-1)
					$new_script = str_replace("%ip%", $ip, $new_script);	
					
				if(strpos($new_script, "%all%")>-1)
					$new_script = str_replace("%all%", $list, $new_script);	

				$body      = $new_script;

				$send_copy=$input_get->getString("wdform_send_copy_".$id);
			
				if(isset($send_copy))
					$send=true;
				else
				{
					foreach($send_tos as $send_to)
					{
						$recipient=$input_get->getString('wdform_'.str_replace('*', '', $send_to)."_element".$id);
						if($recipient)
							$send=$this->sendMail($from, $fromname, $recipient, $subject, $body, $mode, $cca, $bcc, $attachment, $replyto, $replytoname); 
					}
				}
				
			}				
					
			if($row->sendemail)
			if($row->mail)
			{
				if($row->mail_from)
				{
					$from      = $input_get->getString('wdform_'.$row->mail_from."_element".$id);
					if(!isset($from))
						$from = $row->mail_from;
				}
				else
				{
					$from      = $config->get( 'mailfrom' );
				}
				
				if($row->mail_from_name)
					$fromname     = $row->mail_from_name;
				else
					$fromname      = $config->get( 'fromname' );
				
				if($row->reply_to)
				{
					$replyto      = $input_get->getString('wdform_'.$row->reply_to."_element".$id);
					if(!isset($replyto))
						$replyto = $row->reply_to;
				}
				$recipient = $row->mail;
				$cca = $row->mail_cc;
				$bcc = $row->mail_bcc;
				if($row->mail_subject)	
					$subject 	= $row->mail_subject;
				else
					$subject 	= $row->title;
					
				if($row->mail_mode)
				{
					$mode = 1; 
					$list = wordwrap($list, 70, "\n", true);
					$new_script = $new_script = $row->script_mail;
				}	
				else
				{
					$mode = 0; 
					$list = $list_text_mode;
					$list = wordwrap($list, 1000, "\n", true);
					$new_script = str_replace(array('<p>','</p>'),'',$row->script_mail);
				}	

				foreach($label_order_original as $key => $label_each) 
				{	
					$type=$label_type[$key];
						if(strpos($row->script_mail, "%".$label_each."%")>-1 && !in_array($key,$disabled_fields))	
						{	
							$new_value = $this->custom_fields_mail($type, $key, $id);				
							$new_script = str_replace("%".$label_each."%", $new_value, $new_script);							
						}
					
						if(strpos($fromname, "%".$label_each."%")>-1 && !in_array($key,$disabled_fields))	
						{	
							$new_value = str_replace('<br>',', ',$this->custom_fields_mail($type, $key, $id));		
							if(substr($new_value, -2)==', ')	
								$new_value = substr($new_value, 0, -2);					
							$fromname = str_replace("%".$label_each."%", $new_value, $fromname);							
						}
						
						if(strpos($subject, "%".$label_each."%")>-1 && !in_array($key,$disabled_fields))	
						{	
							$new_value = str_replace('<br>',', ',$this->custom_fields_mail($type, $key, $id));		
							if(substr($new_value, -2)==', ')	
								$new_value = substr($new_value, 0, -2);				
							$subject = str_replace("%".$label_each."%", $new_value, $subject);							
						}
				}	
				
				if(strpos($new_script, "%ip%")>-1)
					$new_script = str_replace("%ip%", $ip, $new_script);	
					
				if(strpos($new_script, "%all%")>-1)
					$new_script = str_replace("%all%", $list, $new_script);	

				$body      = $new_script;

				if($row->sendemail)
				{
				$send=$this->sendMail($from, $fromname, $recipient, $subject, $body, $mode, $cca, $bcc, $attachment, $replyto, $replytoname);  
				}
			}
		

		//	$msg =JFactory::getApplication()->enqueueMessage(JText::_('WDF_SUBMITTED'),'Success');

			$msg=JText::_('WDF_SUBMITTED');

			$succes = 1;



			if($row->sendemail)

			if($row->mail || $row->send_to)

			{

				if ( $send) 
				{
					if ( $send !== true ) 
					{
						$msg=JText::_('WDF_MAIL_SEND_ERROR');
						$succes = 0;
					}
					else 
						$msg=JText::_('WDF_MAIL_SENT');
				}
			}

			


		switch($row->submit_text_type)

		{

					case "2":

					{

						$redirect_url=JUri::root()."index.php?option=com_content&view=article&id=".$row->article_id."&Itemid=".$Itemid;

						//$mainframe->redirect("index.php?option=com_content&view=article&id=".$row->article_id."&Itemid=".$Itemid, $msg);

						break;

					}

					case "3":

					{

						$_SESSION['show_submit_text'.$id]=1;

						$redirect_url=$_SERVER["HTTP_REFERER"];

						//$mainframe->redirect($_SERVER["REQUEST_URI"], $msg);

						break;

					}											

					case "4":

					{

					    $redirect_url=$row->url;

						//$mainframe->redirect($row->url, $msg);

						break;

					}

					default:

					{

						$redirect_url=$_SERVER["HTTP_REFERER"];

						//$mainframe->redirect($_SERVER["REQUEST_URI"], $msg);

						break;

					}

		}				




				if($msg == JText::_('WDF_SUBMITTED') || $msg == JText::_('WDF_MAIL_SENT'))

					$mainframe->redirect($redirect_url, $msg, 'message');

				else

					$mainframe->redirect($redirect_url, $msg, 'error');

		

	}

	function custom_fields_mail($type, $key, $id)
	{
		$input_get = JFactory::getApplication()->input;
	
		if($type!="type_submit_reset" or $type!="type_map" or $type!="type_editor" or  $type!="type_captcha" or  $type!="type_recaptcha")
		{
			$new_value ="";

			switch ($type)
			{
					case 'type_text':
					case 'type_password':
					case 'type_textarea':
					case "type_own_select":								
					case "type_number":	
					{
						$element=$input_get->getString('wdform_'.$key."_element".$id);
						if(isset($element))
						{
							$new_value = $element;					
						}
						break;
					
					
					}
										
					case "type_submitter_mail":
					{
						$element=$input_get->getString('wdform_'.$key."_element".$id);
						if(isset($element))
						{
							$new_value = $element;					
						}
						break;		
					}
					
					
					case "type_phone":
					{
						$element_first=$input_get->getString('wdform_'.$key."_element_first".$id);
						if(isset($element_first))
						{
								$new_value = $input_get->getString('wdform_'.$key."_element_first".$id).' '.$input_get->getString('wdform_'.$key."_element_last".$id);
						}	
						break;
					}
					
					case "type_name":
					{
						$element_first=$input_get->getString('wdform_'.$key."_element_first".$id);
						if(isset($element_first))
						{
							$element_title=$input_get->getString('wdform_'.$key."_element_title".$id);
							if(isset($element_title))
								$new_value = $input_get->getString('wdform_'.$key."_element_title".$id).' '.$input_get->getString('wdform_'.$key."_element_first".$id).' '.$input_get->getString('wdform_'.$key."_element_last".$id).' '.$input_get->getString('wdform_'.$key."_element_middle".$id);
							else
								$new_value = $input_get->getString('wdform_'.$key."_element_first".$id).' '.$input_get->getString('wdform_'.$key."_element_last".$id);
						}	   
						break;		
					}
					
					case "type_address":
					{

						$street1=$input_get->getString('wdform_'.$key."_street1".$id);

						if(isset($street1))

						{

							$new_value=$input_get->getString('wdform_'.$key."_street1".$id);

							break;

						}
						
						$street2=$input_get->getString('wdform_'.$key."_street2".$id);

						if(isset($street2))

						{

							$new_value=$input_get->getString('wdform_'.$key."_street2".$id);

							break;

						}	
						
						$city=$input_get->getString('wdform_'.$key."_city".$id);
						
						if(isset($city))

						{

							$new_value=$input_get->getString('wdform_'.$key."_city".$id);

							break;

						}
						
						$state=$input_get->getString('wdform_'.$key."_state".$id);
						
						if(isset($state))

						{

							$new_value=$input_get->getString('wdform_'.$key."_state".$id);

							break;

						}
							
							$postal=$input_get->getString('wdform_'.$key."_postal".$id);
						
						if(isset($postal))

						{

							$new_value=$input_get->getString('wdform_'.$key."_postal".$id);

							break;

						}	

						
						$country = $input_get->getString('wdform_'.$key."_country".$id);
						
							if(isset($country))
							{

							$new_value=$input_get->getString('wdform_'.$key."_country".$id);

							break;		
							}
						

						break;

					}

					case "type_radio":
					{
						$element=$input_get->getString('wdform_'.$key."_other_input".$id);
						if(isset($element))
						{
							$new_value = $input_get->getString('wdform_'.$key."_other_input".$id);
							break;
						}	
						
						$element=$input_get->getString('wdform_'.$key."_element".$id);
						if(isset($element))
						{
							$new_value = $element;					
						}
						break;	
					}
					
					case "type_checkbox":
					{
												
						$start=-1;
						for($j=0; $j<100; $j++)
						{
							$element=$input_get->getString('wdform_'.$key."_element".$id.$j);
							if(isset($element))
							{
								$start=$j;
								break;
							}
						}	
						
						$other_element_id=-1;
						$is_other=$input_get->getString('wdform_'.$key."_allow_other".$id);
						if($is_other=="yes")
						{
							$other_element_id=$input_get->getString('wdform_'.$key."_allow_other_num".$id);
						}
						
				
						if($start!=-1)
						{
							for($j=$start; $j<100; $j++)
							{
								
								$element=$input_get->getString('wdform_'.$key."_element".$id.$j);
								if(isset($element))
								if($j==$other_element_id)
								{
									$new_value = $new_value.$input_get->getString('wdform_'.$key."_other_input".$id).'<br>';
								}
								else
								
									$new_value = $new_value.$input_get->getString('wdform_'.$key."_element".$id.$j).'<br>';
							}
							
						}
						break;
						}
					
					default: break;
			}		
		}

		return $new_value;
	}
	

	protected function sendMail(&$from, &$fromname, &$recipient, &$subject, &$body, &$mode=0, &$cc=null, &$bcc=null, &$attachment=null, &$replyto=null, &$replytoname=null)

    {

		$input_get = JFactory::getApplication()->input;

 				$recipient=explode (',', str_replace(' ', '', $recipient ));
				$cc=explode (',', str_replace(' ', '', $cc ));
				$bcc=explode (',', str_replace(' ', '', $bcc ));
                // Get a JMail instance

                $mail = JFactory::getMailer();

 
				if(filter_var($from, FILTER_VALIDATE_EMAIL))
                $mail->setSender(array($from, $fromname));

                $mail->setSubject($subject);

                $mail->setBody($body);

 

                // Are we sending the email as HTML?

                if ($mode) {

                        $mail->IsHTML(true);

                }

 

			if(filter_var($recipient[0], FILTER_VALIDATE_EMAIL))	
              $mail->addRecipient($recipient);
		
			if(filter_var($cc[0], FILTER_VALIDATE_EMAIL))
              $mail->addCC($cc);
		  
			if(filter_var($bcc[0], FILTER_VALIDATE_EMAIL))
             $mail->addBCC($bcc);

				

                // Take care of reply email addresses

                if (is_array($replyto)) {
                        $numReplyTo = count($replyto);
                        for ($i=0; $i < $numReplyTo; $i++){
							if(filter_var($replyto[$i], FILTER_VALIDATE_EMAIL))
                                $mail->addReplyTo($replyto[$i]);
                        }
                } elseif (isset($replyto) and filter_var($replyto, FILTER_VALIDATE_EMAIL) ) {
                        $mail->addReplyTo($replyto);
                }

 

                return  $mail->Send();

        }

		

	protected function remove($group_id)

	{

		$input_get = JFactory::getApplication()->input;



		$db = JFactory::getDBO();

		$db->setQuery('DELETE FROM #__contactformmaker_submits WHERE group_id="'.$db->escape((int)$group_id).'"');

		$db->query();

	}



	protected function defaultphp($row, $Itemid, $label_id,$label_type, $form_theme, $id, $ok)

	{

		ob_start();

        static $embedded;

        if(!$embedded)

        {

            $embedded=true;

        }

	?>

    

<?php 

  		$input_get = JFactory::getApplication()->input;



@session_start();

$mainframe = JFactory::getApplication();


if(isset($_SESSION['show_submit_text'.$id]))

	if($_SESSION['show_submit_text'.$id]==1)

	{

		$_SESSION['show_submit_text'.$id]=0;

		echo $row->submit_text;

		$content=ob_get_contents();
		ob_end_clean();
		return $content;

	}

	

		$db = JFactory::getDBO();
		$db->setQuery("UPDATE #__contactformmaker_views SET  views=views+1 where form_id=".$db->escape($id) ); 

		$db->query();

		if ($db->getErrorNum())	{echo $db->stderr();	return false;}
	

		$document = JFactory::getDocument();

		$editor	= JFactory::getEditor('tinymce');


		$css_rep1=array("[SITE_ROOT]", "}");

		$css_rep2=array(JURI::root(true), "}#contactform".$id." ");

		$order   = array("\r\n", "\n", "\r");

		$form_theme=str_replace($order,'',$form_theme);

		$form_theme=str_replace($css_rep1,$css_rep2,$form_theme);

		$form_theme="#contactform".$id.' '.$form_theme;

		$check_js='';

		$onload_js='';
		$onsubmit_js='';		

		$db->setQuery("SELECT * FROM #__contactformmaker_options"); 
		$globalParams = $db->loadObject();
		if ($db->getErrorNum())	{echo $db->stderr(); return false;}	


			echo '<style>
			.recaptcha_input_area input
			{
			height:initial !important;
			}
			'.$form_theme.'</style>';


//			echo '<h3>'.$row->title.'</h3><br />';

?>

<form name="contactform<?php echo $id; ?>" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']) ?>" method="post" id="contactform<?php echo $id; ?>" enctype="multipart/form-data" onsubmit="check_required<?php echo $id ?>('submit', '<?php echo $id; ?>'); return false;">

<input type="hidden" id="counter<?php echo $id ?>" value="<?php echo $row->counter?>" name="counter<?php echo $id ?>" />

<input type="hidden" id="Itemid<?php echo $id ?>" value="<?php echo $Itemid?>" name="Itemid<?php echo $id ?>" />



<?php


	$is_type	= array();

	$id1s	 	= array();

	$types 		= array();

	$labels 	= array();

	$paramss 	= array();

	$required_sym=$row->requiredmark;

	$fields=explode('*:*new_field*:*',$row->form_fields);

	$fields 	= array_slice($fields,0, count($fields)-1);   

	$disabled_fields=explode(',',$row->disabled_fields);
	$disabled_fields 	= array_slice($disabled_fields,0, count($disabled_fields)-1);   
	
	foreach($fields as $field)

	{

		$temp=explode('*:*id*:*',$field);

		array_push($id1s, $temp[0]);

		$temp=explode('*:*type*:*',$temp[1]);

		array_push($types, $temp[0]);

		$temp=explode('*:*w_field_label*:*',$temp[1]);

		array_push($labels, $temp[0]);

		array_push($paramss, $temp[1]);

	}

	

	$form_id=$id;	

	if($row->autogen_layout==0)

		$form=$row->custom_front;

	else

		$form=$row->form_front;



	foreach($id1s as $id1s_key => $id1)

	{	

		$label=$labels[$id1s_key];

		$type=$types[$id1s_key];

		$params=$paramss[$id1s_key];

		if( strpos($form, '%'.$id1.' - '.$label.'%'))

		{

			$rep='';

			$required=false;

			$param=array();

			$param['attributes'] = '';

			$is_type[$type]=true;

			
			if(!in_array($id1,$disabled_fields))
			switch($type)

			{

				case 'type_section_break':

				{

					$params_names=array('w_editor');

					$temp=$params;



					foreach($params_names as $params_name )

					{	

						$temp=explode('*:*'.$params_name.'*:*',$temp);

						$param[$params_name] = $temp[0];

						$temp=$temp[1];

					}



					$rep ='<div type="type_section_break" class="wdform-field-section-break"><div class="wdform_section_break">'.$param['w_editor'].'</div></div>';

					break;

				}



				case 'type_editor':

				{

					$params_names=array('w_editor');

					$temp=$params;



					foreach($params_names as $params_name )

					{	

						$temp=explode('*:*'.$params_name.'*:*',$temp);

						$param[$params_name] = $temp[0];

						$temp=$temp[1];

					}

					



					$rep ='<div type="type_editor" class="wdform-field">'.$param['w_editor'].'</div>';

					break;

				}

				case 'type_send_copy':
				{
					$params_names=array('w_field_label_size','w_field_label_pos','w_first_val','w_required');
					$temp=$params;

					foreach($params_names as $params_name )
					{	
						$temp=explode('*:*'.$params_name.'*:*',$temp);
						$param[$params_name] = $temp[0];
						$temp=$temp[1];
					}
					
					
					if($temp)
					{	
						$temp	=explode('*:*w_attr_name*:*',$temp);
						$attrs	= array_slice($temp,0, count($temp)-1);   
						foreach($attrs as $attr)
							$param['attributes'] = $param['attributes'].' '.$attr;
					}
					
					$input_active = ($param['w_first_val']=='true' ? "checked='checked'" : "");	
					$post_value=$input_get->getString("counter".$form_id);

					if(isset($post_value))
					{
						$post_temp=$input_get->getString('wdform_'.$id1.'_element'.$form_id);
						$input_active = (isset($post_temp) ? "checked='checked'" : "");	
					}
					
					$param['w_field_label_pos1'] = ($param['w_field_label_pos']=="left" ? "float: left;" : "");	
					$param['w_field_label_pos2'] = ($param['w_field_label_pos']=="left" ? "" : "display:block;");
					
					$required = ($param['w_required']=="yes" ? true : false);	
					
					$rep ='<div type="type_send_copy" class="wdform-field"><div class="wdform-label-section" style="'.$param['w_field_label_pos1'].' width: '.$param['w_field_label_size'].'px;"><span class="wdform-label"><label for="wdform_'.$id1.'_element'.$form_id.'">'.$label.'</label></span>';
					if($required)
						$rep.='<span class="wdform-required">'.$required_sym.'</span>';

					$rep.='</div>
					<div class="wdform-element-section" style="min-width:inherit !important; '.$param['w_field_label_pos2'].'" >
						<div class="checkbox-div" style="left:3px">
						<input type="checkbox" id="wdform_'.$id1.'_element'.$form_id.'" name="wdform_'.$id1.'_element'.$form_id.'" '.$input_active.' '.$param['attributes'].'/>
						<label for="wdform_'.$id1.'_element'.$form_id.'"></label>
						</div>
					</div></div>';

					$onsubmit_js.='
					if(!wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").is(":checked"))
						wdconformjQuery("<input type=\"hidden\" name=\"wdform_send_copy_'.$form_id.'\" value = \"1\" />").appendTo("#contactform'.$form_id.'");
						';
					
					
					if($required)
						$check_js.='
						if(x.find(wdconformjQuery("div[wdid='.$id1.']")).length != 0)
						{
							if(x.find(wdconformjQuery("div[wdid='.$id1.'] input:checked")).length == 0)
							{
								alert("'.addslashes(JText::sprintf('WDF_REQUIRED_FIELD', '"'.$label.'"') ).'");
								old_bg=x.find(wdconformjQuery("div[wdid='.$id1.']")).css("background-color");
								x.find(wdconformjQuery("div[wdid='.$id1.']")).effect( "shake", {}, 500 ).css("background-color","#FF8F8B").animate({backgroundColor: old_bg}, {duration: 500, queue: false });
								
								return false;
							}						
						}
						';	
					
					
					break;
					
				}

				case 'type_text':

				{

					$params_names=array('w_field_label_size','w_field_label_pos','w_size','w_first_val','w_title','w_required','w_unique');

					$temp=$params;



					foreach($params_names as $params_name )

					{	

						$temp=explode('*:*'.$params_name.'*:*',$temp);

						$param[$params_name] = $temp[0];

						$temp=$temp[1];

					}

					

					

					if($temp)

					{	

						$temp	=explode('*:*w_attr_name*:*',$temp);

						$attrs	= array_slice($temp,0, count($temp)-1);   

						foreach($attrs as $attr)

							$param['attributes'] = $param['attributes'].' '.$attr;

					}



					$param['w_first_val']=htmlspecialchars($input_get->getString('wdform_'.$id1.'_element'.$form_id, $param['w_first_val']));

					$wdformfieldsize = ($param['w_field_label_pos']=="left" ? $param['w_field_label_size']+$param['w_size'] : max($param['w_field_label_size'],$param['w_size']));

					

					$param['w_field_label_pos1'] = ($param['w_field_label_pos']=="left" ? "float: left;" : "");	

					$param['w_field_label_pos2'] = ($param['w_field_label_pos']=="left" ? "" : "display:block;");

					

					$input_active = ($param['w_first_val']==$param['w_title'] ? "input_deactive" : "input_active");	

					$required = ($param['w_required']=="yes" ? true : false);	



					$rep ='<div type="type_text" class="wdform-field" style="width:'.$wdformfieldsize.'px"><div class="wdform-label-section" style="'.$param['w_field_label_pos1'].' width: '.$param['w_field_label_size'].'px;"><span class="wdform-label">'.$label.'</span>';

					if($required)

						$rep.='<span class="wdform-required">'.$required_sym.'</span>';

					$rep.='</div><div class="wdform-element-section" style="'.$param['w_field_label_pos2'].' width: '.$param['w_size'].'px;"><input type="text" class="'.$input_active.'" id="wdform_'.$id1.'_element'.$form_id.'" name="wdform_'.$id1.'_element'.$form_id.'" value="'.$param['w_first_val'].'" title="'.$param['w_title'].'"  style="width: 100%" '.$param['attributes'].'></div></div>';

					

					if($required)

						$check_js.='

						if(x.find(wdconformjQuery("div[wdid='.$id1.']")).length != 0)

						{

							if(wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").val()=="'.$param['w_title'].'" || wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").val()=="")

							{

								alert("'.addslashes(JText::sprintf('WDF_REQUIRED_FIELD', '"'.$label.'"') ).'");

								wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").addClass( "form-error" );

								old_bg=x.find(wdconformjQuery("div[wdid='.$id1.']")).css("background-color");

								x.find(wdconformjQuery("div[wdid='.$id1.']")).effect( "shake", {}, 500 ).css("background-color","#FF8F8B").animate({backgroundColor: old_bg}, {duration: 500, queue: false });

								wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").focus();

								wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").change(function() { if( wdconformjQuery(this).val()!="" ) wdconformjQuery(this).removeClass("form-error"); else wdconformjQuery(this).addClass("form-error");});

								return false;

							}

						}

						';		

					

					break;

					

				}



				case 'type_number':

				{

					$params_names=array('w_field_label_size','w_field_label_pos','w_size','w_first_val','w_title','w_required','w_unique','w_class');

					$temp=$params;



					foreach($params_names as $params_name )

					{	

						$temp=explode('*:*'.$params_name.'*:*',$temp);

						$param[$params_name] = $temp[0];

						$temp=$temp[1];

					}

					

					

					if($temp)

					{	

						$temp	=explode('*:*w_attr_name*:*',$temp);

						$attrs	= array_slice($temp,0, count($temp)-1);   

						foreach($attrs as $attr)

							$param['attributes'] = $param['attributes'].' '.$attr;

					}

					

					$param['w_first_val']=htmlspecialchars($input_get->getString('wdform_'.$id1.'_element'.$form_id, $param['w_first_val']));


					$wdformfieldsize = ($param['w_field_label_pos']=="left" ? $param['w_field_label_size']+$param['w_size'] : max($param['w_field_label_size'],$param['w_size']));	

					

					$param['w_field_label_pos1'] = ($param['w_field_label_pos']=="left" ? "float: left;" : "");	

					$param['w_field_label_pos2'] = ($param['w_field_label_pos']=="left" ? "" : "display:block;");

					

					$input_active = ($param['w_first_val']==$param['w_title'] ? "input_deactive" : "input_active");	

					$required = ($param['w_required']=="yes" ? true : false);	

									

					$rep ='<div type="type_number" class="wdform-field" style="width:'.$wdformfieldsize.'px"><div class="wdform-label-section"  class="'.$param['w_class'].'" style="'.$param['w_field_label_pos1'].' width: '.$param['w_field_label_size'].'px;"><span class="wdform-label">'.$label.'</span>';

					if($required)

						$rep.='<span class="wdform-required">'.$required_sym.'</span>';

					$rep.='</div><div class="wdform-element-section '.$param['w_class'].'" style="'.$param['w_field_label_pos2'].' width: '.$param['w_size'].'px;"><input type="text" class="'.$input_active.'" id="wdform_'.$id1.'_element'.$form_id.'" name="wdform_'.$id1.'_element'.$form_id.'" value="'.$param['w_first_val'].'" title="'.$param['w_title'].'" style="width:100%;" '.$param['attributes'].'></div></div>';

					

					if($required)

						$check_js.='

						if(x.find(wdconformjQuery("div[wdid='.$id1.']")).length != 0)

						{

							if(wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").val()=="'.$param['w_title'].'" || wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").val()=="")

							{

								alert("'.addslashes(JText::sprintf('WDF_REQUIRED_FIELD', '"'.$label.'"') ).'");

								wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").addClass( "form-error" );

								old_bg=x.find(wdconformjQuery("div[wdid='.$id1.']")).css("background-color");

								x.find(wdconformjQuery("div[wdid='.$id1.']")).effect( "shake", {}, 500 ).css("background-color","#FF8F8B").animate({backgroundColor: old_bg}, {duration: 500, queue: false });

								wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").focus();

								wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").change(function() { if( wdconformjQuery(this).val()!="" ) wdconformjQuery(this).removeClass("form-error"); else wdconformjQuery(this).addClass("form-error");});

								return false;

							}

						}

						';		

					

					break;

				}

				case 'type_password':

				{

					$params_names=array('w_field_label_size','w_field_label_pos','w_size','w_required','w_unique','w_class');

					$temp=$params;



					foreach($params_names as $params_name )

					{	

						$temp=explode('*:*'.$params_name.'*:*',$temp);

						$param[$params_name] = $temp[0];

						$temp=$temp[1];

					}

					

					

					if($temp)

					{	

						$temp	=explode('*:*w_attr_name*:*',$temp);

						$attrs	= array_slice($temp,0, count($temp)-1);   

						foreach($attrs as $attr)

							$param['attributes'] = $param['attributes'].' '.$attr;

					}

			

					$wdformfieldsize = ($param['w_field_label_pos']=="left" ? $param['w_field_label_size']+$param['w_size'] : max($param['w_field_label_size'],$param['w_size']));	

					

					$param['w_field_label_pos1'] = ($param['w_field_label_pos']=="left" ? "float: left;" : "");	

					$param['w_field_label_pos2'] = ($param['w_field_label_pos']=="left" ? "" : "display:block;");

					

					$required = ($param['w_required']=="yes" ? true : false);	



					$rep ='<div type="type_password" class="wdform-field" style="width:'.$wdformfieldsize.'px"><div class="wdform-label-section"  class="'.$param['w_class'].'" style="'.$param['w_field_label_pos1'].'; width: '.$param['w_field_label_size'].'px;"><span class="wdform-label">'.$label.'</span>';

					if($required)

						$rep.='<span class="wdform-required">'.$required_sym.'</span>';

					$rep.='</div><div class="wdform-element-section '.$param['w_class'].'" style="'.$param['w_field_label_pos2'].' width: '.$param['w_size'].'px;"><input type="password" id="wdform_'.$id1.'_element'.$form_id.'" name="wdform_'.$id1.'_element'.$form_id.'" style="width: 100%;" '.$param['attributes'].'></div></div>';

					

					if($required)

						$check_js.='

						if(x.find(wdconformjQuery("div[wdid='.$id1.']")).length != 0)

						{

							if(wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").val()=="")

							{

								alert("'.addslashes(JText::sprintf('WDF_REQUIRED_FIELD', '"'.$label.'"') ).'");

								wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").addClass( "form-error" );

								old_bg=x.find(wdconformjQuery("div[wdid='.$id1.']")).css("background-color");

								x.find(wdconformjQuery("div[wdid='.$id1.']")).effect( "shake", {}, 500 ).css("background-color","#FF8F8B").animate({backgroundColor: old_bg}, {duration: 500, queue: false });

								wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").focus();

								wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").change(function() { if( wdconformjQuery(this).val()!="" ) wdconformjQuery(this).removeClass("form-error"); else wdconformjQuery(this).addClass("form-error");});

								return false;

							}

						}

						';		

					

					break;

				}



				case 'type_textarea':

				{

					$params_names=array('w_field_label_size','w_field_label_pos','w_size_w','w_size_h','w_first_val','w_title','w_required','w_unique','w_class');

					$temp=$params;



					foreach($params_names as $params_name )

					{	

						$temp=explode('*:*'.$params_name.'*:*',$temp);

						$param[$params_name] = $temp[0];

						$temp=$temp[1];

					}

					

					

					if($temp)

					{	

						$temp	=explode('*:*w_attr_name*:*',$temp);

						$attrs	= array_slice($temp,0, count($temp)-1);   

						foreach($attrs as $attr)

							$param['attributes'] = $param['attributes'].' '.$attr;

					}

				
					$param['w_first_val']=htmlspecialchars($input_get->getString('wdform_'.$id1.'_element'.$form_id, $param['w_first_val']));	

					$wdformfieldsize = ($param['w_field_label_pos']=="left" ? $param['w_field_label_size']+$param['w_size_w'] : max($param['w_field_label_size'],$param['w_size_w']));	

					

					$param['w_field_label_pos1'] = ($param['w_field_label_pos']=="left" ? "float: left;" : "");	

					$param['w_field_label_pos2'] = ($param['w_field_label_pos']=="left" ? "" : "display:block;");

					

					$input_active = ($param['w_first_val']==$param['w_title'] ? "input_deactive" : "input_active");	

					$required = ($param['w_required']=="yes" ? true : false);	

				

					$rep ='<div type="type_textarea" class="wdform-field" style="width:'.$wdformfieldsize.'px"><div class="wdform-label-section" style="'.$param['w_field_label_pos1'].'; width: '.$param['w_field_label_size'].'px;"><span class="wdform-label">'.$label.'</span>';

					if($required)

						$rep.='<span class="wdform-required">'.$required_sym.'</span>';

					$rep.='</div><div class="wdform-element-section '.$param['w_class'].'" style="'.$param['w_field_label_pos2'].' width: '.$param['w_size_w'].'px"><textarea class="'.$input_active.'" id="wdform_'.$id1.'_element'.$form_id.'" name="wdform_'.$id1.'_element'.$form_id.'" title="'.$param['w_title'].'"  style="width: 100%; height: '.$param['w_size_h'].'px;" '.$param['attributes'].'>'.$param['w_first_val'].'</textarea></div></div>';



					

					if($required)

						$check_js.='

						if(x.find(wdconformjQuery("div[wdid='.$id1.']")).length != 0)

						{

							if(wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").val()=="'.$param['w_title'].'" || wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").val()=="")

							{

								alert("'.addslashes(JText::sprintf('WDF_REQUIRED_FIELD', '"'.$label.'"') ).'");

								wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").addClass( "form-error" );

								old_bg=x.find(wdconformjQuery("div[wdid='.$id1.']")).css("background-color");

								x.find(wdconformjQuery("div[wdid='.$id1.']")).effect( "shake", {}, 500 ).css("background-color","#FF8F8B").animate({backgroundColor: old_bg}, {duration: 500, queue: false });

								wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").focus();

								wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").change(function() { if( wdconformjQuery(this).val()!="" ) wdconformjQuery(this).removeClass("form-error"); else wdconformjQuery(this).addClass("form-error");});

								return false;

							}

						}

						';		

					



					break;

				}

			

				case 'type_phone':

				{

					$params_names=array('w_field_label_size','w_field_label_pos','w_size','w_first_val','w_title','w_mini_labels','w_required','w_unique', 'w_class');

					$temp=$params;



					foreach($params_names as $params_name )

					{	

						$temp=explode('*:*'.$params_name.'*:*',$temp);

						$param[$params_name] = $temp[0];

						$temp=$temp[1];

					}

					

					

					if($temp)

					{	

						$temp	=explode('*:*w_attr_name*:*',$temp);

						$attrs	= array_slice($temp,0, count($temp)-1);   

						foreach($attrs as $attr)

							$param['attributes'] = $param['attributes'].' '.$attr;

					}

					

					

					$w_first_val = explode('***',$param['w_first_val']);

					$w_title = explode('***',$param['w_title']);

					

					$param['w_first_val']=htmlspecialchars($input_get->getString('wdform_'.$id1.'_element_first'.$form_id, $w_first_val[0])).'***'.htmlspecialchars($input_get->getString('wdform_'.$id1.'_element_last'.$form_id, $w_first_val[1]));

					$wdformfieldsize = ($param['w_field_label_pos']=="left" ? ($param['w_field_label_size']+$param['w_size']+65) : max($param['w_field_label_size'],($param['w_size']+65)));	

					$param['w_field_label_pos1'] = ($param['w_field_label_pos']=="left" ? "float: left;" : "");	

					$param['w_field_label_pos2'] = ($param['w_field_label_pos']=="left" ? "" : "display:block;");

					

					$input_active = ($param['w_first_val']==$param['w_title'] ? "input_deactive" : "input_active");	

					$required = ($param['w_required']=="yes" ? true : false);	

					

					$w_first_val = explode('***',$param['w_first_val']);

					$w_title = explode('***',$param['w_title']);

					$w_mini_labels = explode('***',$param['w_mini_labels']);

			



					$rep ='<div type="type_phone" class="wdform-field" style="width:'.$wdformfieldsize.'px"><div class="wdform-label-section '.$param['w_class'].'" style="'.$param['w_field_label_pos1'].'; width: '.$param['w_field_label_size'].'px;"><span class="wdform-label" >'.$label.'</span>';

					if($required)

						$rep.='<span class="wdform-required">'.$required_sym.'</span>';

					$rep.='

					</div>

					<div class="wdform-element-section '.$param['w_class'].'" style="'.$param['w_field_label_pos2'].' width: '.($param['w_size']+65).'px;">

						<div style="display: table-cell;vertical-align: middle;">

							<div><input type="text" class="'.$input_active.'" id="wdform_'.$id1.'_element_first'.$form_id.'" name="wdform_'.$id1.'_element_first'.$form_id.'" value="'.$w_first_val[0].'" title="'.$w_title[0].'" style="width: 50px;" '.$param['attributes'].'></div>

							<div><label class="mini_label">'.$w_mini_labels[0].'</label></div>

						</div>

						<div style="display: table-cell;vertical-align: middle;">

							<div class="wdform_line" style="margin: 0px 4px 10px 4px; padding: 0px;">-</div>

						</div>

						<div style="display: table-cell;vertical-align: middle; width:100%;">

							<div><input type="text" class="'.$input_active.'" id="wdform_'.$id1.'_element_last'.$form_id.'" name="wdform_'.$id1.'_element_last'.$form_id.'" value="'.$w_first_val[1].'" title="'.$w_title[1].'" style="width: 100%;" '.$param['attributes'].'></div>

							<div><label class="mini_label">'.$w_mini_labels[1].'</label></div>

						</div>

					</div>

					</div>';

				

					if($required)

						$check_js.='

						if(x.find(wdconformjQuery("div[wdid='.$id1.']")).length != 0)

						{

							if(wdconformjQuery("#wdform_'.$id1.'_element_first'.$form_id.'").val()=="'.$w_title[0].'" || wdconformjQuery("#wdform_'.$id1.'_element_first'.$form_id.'").val()=="" || wdconformjQuery("#wdform_'.$id1.'_element_last'.$form_id.'").val()=="'.$w_title[1].'" || wdconformjQuery("#wdform_'.$id1.'_element_last'.$form_id.'").val()=="")

							{

								alert("'.addslashes(JText::sprintf('WDF_REQUIRED_FIELD', '"'.$label.'"') ).'");

								old_bg=x.find(wdconformjQuery("div[wdid='.$id1.']")).css("background-color");

								x.find(wdconformjQuery("div[wdid='.$id1.']")).effect( "shake", {}, 500 ).css("background-color","#FF8F8B").animate({backgroundColor: old_bg}, {duration: 500, queue: false });

								wdconformjQuery("#wdform_'.$id1.'_element_first'.$form_id.'").focus();

								return false;

							}

							

						}

						';		

					break;

				}



				case 'type_name':

				{

					$params_names=array('w_field_label_size','w_field_label_pos','w_first_val','w_title', 'w_mini_labels','w_size','w_name_format','w_required','w_unique', 'w_class');

					$temp=$params;



					foreach($params_names as $params_name )

					{	

						$temp=explode('*:*'.$params_name.'*:*',$temp);

						$param[$params_name] = $temp[0];

						$temp=$temp[1];

					}

					

					

					if($temp)

					{	

						$temp	=explode('*:*w_attr_name*:*',$temp);

						$attrs	= array_slice($temp,0, count($temp)-1);   

						foreach($attrs as $attr)

							$param['attributes'] = $param['attributes'].' '.$attr;

					}

					

					$w_first_val = explode('***',$param['w_first_val']);

					$w_title = explode('***',$param['w_title']);

					$w_mini_labels = explode('***',$param['w_mini_labels']);



					

					

					$element_title = $input_get->getString('wdform_'.$id1.'_element_title'.$form_id);

					$element_first = $input_get->getString('wdform_'.$id1.'_element_first'.$form_id);

					if(isset($element_title))

						$param['w_first_val']=$input_get->getString('wdform_'.$id1.'_element_title'.$form_id, $w_first_val[0]).'***'.$input_get->getString('wdform_'.$id1.'_element_first'.$form_id, $w_first_val[1]).'***'.$input_get->getString('wdform_'.$id1.'_element_last'.$form_id, $w_first_val[2]).'***'.$input_get->getString('wdform_'.$id1.'_element_middle'.$form_id, $w_first_val[3]);

					else

						if(isset($element_first))

							$param['w_first_val']=$input_get->getString('wdform_'.$id1.'_element_first'.$form_id, $w_first_val[0]).'***'.$input_get->getString('wdform_'.$id1.'_element_last'.$form_id, $w_first_val[1]);

						

					$input_active = ($param['w_first_val']==$param['w_title'] ? "input_deactive" : "input_active");	

					$required = ($param['w_required']=="yes" ? true : false);	

				

					$w_first_val = explode('***',$param['w_first_val']);

					$w_title = explode('***',$param['w_title']);



					

					

					if($param['w_name_format']=='normal')

					{

						$w_name_format = '

						<div style="display: table-cell; width:50%">

							<div><input type="text" class="'.$input_active.'" id="wdform_'.$id1.'_element_first'.$form_id.'" name="wdform_'.$id1.'_element_first'.$form_id.'" value="'.$w_first_val[0].'" title="'.$w_title[0].'"  style="width: 100%;"'.$param['attributes'].'></div>

							<div><label class="mini_label">'.$w_mini_labels[1].'</label></div>

						</div>

						<div style="display:table-cell;"><div style="margin: 0px 8px; padding: 0px;"></div></div>

						<div  style="display: table-cell; width:50%">

							<div><input type="text" class="'.$input_active.'" id="wdform_'.$id1.'_element_last'.$form_id.'" name="wdform_'.$id1.'_element_last'.$form_id.'" value="'.$w_first_val[1].'" title="'.$w_title[1].'" style="width: 100%;" '.$param['attributes'].'></div>

							<div><label class="mini_label">'.$w_mini_labels[2].'</label></div>

						</div>

						';

						$w_size=2*$param['w_size'];



					}

					else

					{

						$w_name_format = '

						<div style="display: table-cell;">

							<div><input type="text" class="'.$input_active.'" id="wdform_'.$id1.'_element_title'.$form_id.'" name="wdform_'.$id1.'_element_title'.$form_id.'" value="'.$w_first_val[0].'" title="'.$w_title[0].'" style="width: 40px;"></div>

							<div><label class="mini_label">'.$w_mini_labels[0].'</label></div>

						</div>

						<div style="display:table-cell;"><div style="margin: 0px 1px; padding: 0px;"></div></div>

						<div style="display: table-cell; width:30%">

							<div><input type="text" class="'.$input_active.'" id="wdform_'.$id1.'_element_first'.$form_id.'" name="wdform_'.$id1.'_element_first'.$form_id.'" value="'.$w_first_val[1].'" title="'.$w_title[1].'" style="width:100%;"></div>

							<div><label class="mini_label">'.$w_mini_labels[1].'</label></div>

						</div>

						<div style="display:table-cell;"><div style="margin: 0px 4px; padding: 0px;"></div></div>

						<div style="display: table-cell; width:30%">

							<div><input type="text" class="'.$input_active.'" id="wdform_'.$id1.'_element_last'.$form_id.'" name="wdform_'.$id1.'_element_last'.$form_id.'" value="'.$w_first_val[2].'" title="'.$w_title[2].'" style="width:  100%;"></div>

							<div><label class="mini_label">'.$w_mini_labels[2].'</label></div>

						</div>

						<div style="display:table-cell;"><div style="margin: 0px 4px; padding: 0px;"></div></div>

						<div style="display: table-cell; width:30%">

							<div><input type="text" class="'.$input_active.'" id="wdform_'.$id1.'_element_middle'.$form_id.'" name="wdform_'.$id1.'_element_middle'.$form_id.'" value="'.$w_first_val[3].'" title="'.$w_title[3].'" style="width: 100%;"></div>

							<div><label class="mini_label">'.$w_mini_labels[3].'</label></div>

						</div>						

						';

						$w_size=3*$param['w_size']+80;

					}

		

					$wdformfieldsize = ($param['w_field_label_pos']=="left" ? ($param['w_field_label_size']+$w_size) : max($param['w_field_label_size'],$w_size));	

					$param['w_field_label_pos1'] = ($param['w_field_label_pos']=="left" ? "float: left;" : "");	

					$param['w_field_label_pos2'] = ($param['w_field_label_pos']=="left" ? "" : "display:block;");

					



					$rep ='<div type="type_name" class="wdform-field"  style="width:'.$wdformfieldsize.'px"><div class="wdform-label-section" style="'.$param['w_field_label_pos1'].'; width: '.$param['w_field_label_size'].'px;"><span class="wdform-label">'.$label.'</span>';

					if($required)

						$rep.='<span class="wdform-required">'.$required_sym.'</span>';

					$rep.='</div><div class="wdform-element-section '.$param['w_class'].'" style="'.$param['w_field_label_pos2'].' width: '.$w_size.'px;">'.$w_name_format.'</div></div>';



					if($required)

					{

						if($param['w_name_format']=='normal')

						{

							$check_js.='

							if(x.find(wdconformjQuery("div[wdid='.$id1.']")).length != 0)

							{

								if(wdconformjQuery("#wdform_'.$id1.'_element_first'.$form_id.'").val()=="'.$w_title[0].'" || wdconformjQuery("#wdform_'.$id1.'_element_first'.$form_id.'").val()=="" || wdconformjQuery("#wdform_'.$id1.'_element_last'.$form_id.'").val()=="'.$w_title[1].'" || wdconformjQuery("#wdform_'.$id1.'_element_last'.$form_id.'").val()=="")

								{

									alert("'.addslashes(JText::sprintf('WDF_REQUIRED_FIELD', '"'.$label.'"') ).'");

									old_bg=x.find(wdconformjQuery("div[wdid='.$id1.']")).css("background-color");

									x.find(wdconformjQuery("div[wdid='.$id1.']")).effect( "shake", {}, 500 ).css("background-color","#FF8F8B").animate({backgroundColor: old_bg}, {duration: 500, queue: false });

									wdconformjQuery("#wdform_'.$id1.'_element_first'.$form_id.'").focus();

									return false;

								}

							}

							';	

						}

						else

						{

							$check_js.='

							if(x.find(wdconformjQuery("div[wdid='.$id1.']")).length != 0)

							{

								if(wdconformjQuery("#wdform_'.$id1.'_element_title'.$form_id.'").val()=="'.$w_title[0].'" || wdconformjQuery("#wdform_'.$id1.'_element_title'.$form_id.'").val()=="" || wdconformjQuery("#wdform_'.$id1.'_element_first'.$form_id.'").val()=="'.$w_title[1].'" || wdconformjQuery("#wdform_'.$id1.'_element_first'.$form_id.'").val()=="" || wdconformjQuery("#wdform_'.$id1.'_element_last'.$form_id.'").val()=="'.$w_title[2].'" || wdconformjQuery("#wdform_'.$id1.'_element_last'.$form_id.'").val()=="" || wdconformjQuery("#wdform_'.$id1.'_element_middle'.$form_id.'").val()=="'.$w_title[3].'" || wdconformjQuery("#wdform_'.$id1.'_element_middle'.$form_id.'").val()=="")

								{

									alert("'.addslashes(JText::sprintf('WDF_REQUIRED_FIELD', '"'.$label.'"') ).'");

									old_bg=x.find(wdconformjQuery("div[wdid='.$id1.']")).css("background-color");

									x.find(wdconformjQuery("div[wdid='.$id1.']")).effect( "shake", {}, 500 ).css("background-color","#FF8F8B").animate({backgroundColor: old_bg}, {duration: 500, queue: false });

									wdconformjQuery("#wdform_'.$id1.'_element_first'.$form_id.'").focus();

									return false;

								}

							}

							';		

						}

					}

					break;

				}

				

				case 'type_address':

				{

					$params_names=array('w_field_label_size','w_field_label_pos','w_size','w_mini_labels','w_disabled_fields','w_required','w_class');

					$temp=$params;



					foreach($params_names as $params_name )

					{	

						$temp=explode('*:*'.$params_name.'*:*',$temp);

						$param[$params_name] = $temp[0];

						$temp=$temp[1];

					}

					

					

					if($temp)

					{	

						$temp	=explode('*:*w_attr_name*:*',$temp);

						$attrs	= array_slice($temp,0, count($temp)-1);   

						foreach($attrs as $attr)

							$param['attributes'] = $param['attributes'].' '.$attr;

					}

					

				

					$wdformfieldsize = ($param['w_field_label_pos']=="left" ? ($param['w_field_label_size']+$param['w_size']) : max($param['w_field_label_size'], $param['w_size']));	

					$param['w_field_label_pos1'] = ($param['w_field_label_pos']=="left" ? "float: left;" : "");	

					$param['w_field_label_pos2'] = ($param['w_field_label_pos']=="left" ? "" : "display:block;");

					

					$required = ($param['w_required']=="yes" ? true : false);	

					$w_mini_labels = explode('***',$param['w_mini_labels']);

					$w_disabled_fields = explode('***',$param['w_disabled_fields']);

				



					$rep ='<div type="type_address" class="wdform-field"  style="width:'.$wdformfieldsize.'px"><div class="wdform-label-section" style="'.$param['w_field_label_pos1'].'; width: '.$param['w_field_label_size'].'px;"><span class="wdform-label">'.$label.'</span>';

					if($required)

						$rep.='<span class="wdform-required">'.$required_sym.'</span>';

						

						

						

			

					$address_fields ='';

					$g=0;

					if($w_disabled_fields[0]=='no')

					{

					$g+=2;

					$address_fields .= '<span style="float: left; width: 100%; padding-bottom: 8px; display: block;"><input type="text" id="wdform_'.$id1.'_street1'.$form_id.'" name="wdform_'.$id1.'_street1'.$form_id.'" value="'.$input_get->getString('wdform_'.$id1.'_street1'.$form_id).'" style="width: 100%;" '.$param['attributes'].'><label class="mini_label" >'.$w_mini_labels[0].'</label></span>';

					}

					

					if($w_disabled_fields[1]=='no')

					{

					$g+=2;

					$address_fields .= '<span style="float: left; width: 100%; padding-bottom: 8px; display: block;"><input type="text" id="wdform_'.$id1.'_street2'.$form_id.'" name="wdform_'.($id1+1).'_street2'.$form_id.'" value="'.$input_get->getString('wdform_'.($id1+1).'_street2'.$form_id).'" style="width: 100%;" '.$param['attributes'].'><label class="mini_label" >'.$w_mini_labels[1].'</label></span>';

					}

					

					if($w_disabled_fields[2]=='no')

					{

					$g++;

					$address_fields .= '<span style="float: left; width: 48%; padding-bottom: 8px;"><input type="text" id="wdform_'.$id1.'_city'.$form_id.'" name="wdform_'.($id1+2).'_city'.$form_id.'" value="'.$input_get->getString('wdform_'.($id1+2).'_city'.$form_id).'" style="width: 100%;" '.$param['attributes'].'><label class="mini_label" >'.$w_mini_labels[2].'</label></span>';

					}

					if($w_disabled_fields[3]=='no')

					{

					$g++;

					



					$w_states = array("","Alabama","Alaska", "Arizona","Arkansas","California","Colorado","Connecticut","Delaware","District Of Columbia","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Carolina","North Dakota","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah","Vermont","Virginia","Washington","West Virginia","Wisconsin","Wyoming");	

					$w_state_options = '';

					foreach($w_states as $w_state)

					{

					

					if($w_state == $input_get->getString('wdform_'.($id1+3).'_state'.$form_id))					

					$selected = 'selected="selected"';

					else

					$selected = '';

					$w_state_options .= '<option value="'.$w_state.'" '.$selected.'>'.$w_state.'</option>';

					}

					if($w_disabled_fields[5]=='yes' && $w_disabled_fields[6]=='yes')

					{

					$address_fields .= '<span style="float: '.(($g%2==0) ? 'right' : 'left').'; width: 48%; padding-bottom: 8px;"><select type="text" id="wdform_'.$id1.'_state'.$form_id.'" name="wdform_'.($id1+3).'_state'.$form_id.'" style="width: 100%;" '.$param['attributes'].'>'.$w_state_options.'</select><label class="mini_label" style="display: block;" id="'.$id1.'_mini_label_state">'.$w_mini_labels[3].'</label></span>';

					}

					else

					$address_fields .= '<span style="float: '.(($g%2==0) ? 'right' : 'left').'; width: 48%; padding-bottom: 8px;"><input type="text" id="wdform_'.$id1.'_state'.$form_id.'" name="wdform_'.($id1+3).'_state'.$form_id.'" value="'.$input_get->getString('wdform_'.($id1+3).'_state'.$form_id).'" style="width: 100%;" '.$param['attributes'].'><label class="mini_label">'.$w_mini_labels[3].'</label></span>';

					}

					if($w_disabled_fields[4]=='no')

					{

					$g++;

					$address_fields .= '<span style="float: '.(($g%2==0) ? 'right' : 'left').'; width: 48%; padding-bottom: 8px;"><input type="text" id="wdform_'.$id1.'_postal'.$form_id.'" name="wdform_'.($id1+4).'_postal'.$form_id.'" value="'.$input_get->getString('wdform_'.($id1+4).'_postal'.$form_id).'" style="width: 100%;" '.$param['attributes'].'><label class="mini_label">'.$w_mini_labels[4].'</label></span>';

					}

					$w_countries = array("","Afghanistan","Albania",	"Algeria","Andorra","Angola","Antigua and Barbuda","Argentina","Armenia","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bhutan","Bolivia","Bosnia and Herzegovina","Botswana","Brazil","Brunei","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Central African Republic","Chad","Chile","China","Colombi","Comoros","Congo (Brazzaville)","Congo","Costa Rica","Cote d'Ivoire","Croatia","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","East Timor (Timor Timur)","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Fiji","Finland","France","Gabon","Gambia, The","Georgia","Germany","Ghana","Greece","Grenada","Guatemala","Guinea","Guinea-Bissau","Guyana","Haiti","Honduras","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Israel","Italy","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Korea, North","Korea, South","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepa","Netherlands","New Zealand","Nicaragua","Niger","Nigeria","Norway","Oman","Pakistan","Palau","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Qatar","Romania","Russia","Rwanda","Saint Kitts and Nevis","Saint Lucia","Saint Vincent","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia and Montenegro","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","Spain","Sri Lanka","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Togo","Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Yemen","Zambia","Zimbabwe");	

					$w_options = '';

					foreach($w_countries as $w_country)

					{

					

					if($w_country == $input_get->getString('wdform_'.($id1+5).'_country'.$form_id))					

					$selected = 'selected="selected"';

					else

					$selected = '';

					$w_options .= '<option value="'.$w_country.'" '.$selected.'>'.$w_country.'</option>';

					}

				

					if($w_disabled_fields[5]=='no')

					{

					$g++;

					$address_fields .= '<span style="float: '.(($g%2==0) ? 'right' : 'left').'; width: 48%; padding-bottom: 8px;display: inline-block;"><select type="text" id="wdform_'.$id1.'_country'.$form_id.'" name="wdform_'.($id1+5).'_country'.$form_id.'" style="width: 100%;" '.$param['attributes'].'>'.$w_options.'</select><label class="mini_label">'.$w_mini_labels[5].'</span>';

					}				



				

					$rep.='</div><div class="wdform-element-section '.$param['w_class'].'" style="'.$param['w_field_label_pos2'].' width: '.$param['w_size'].'px;"><div>

					'.$address_fields.'</div></div></div>';

					

					if($required)

						$check_js.='

						if(x.find(wdconformjQuery("div[wdid='.$id1.']")).length != 0)

						{

							if(wdconformjQuery("#wdform_'.$id1.'_street1'.$form_id.'").val()=="" || wdconformjQuery("#wdform_'.$id1.'_street2'.$form_id.'").val()=="" || wdconformjQuery("#wdform_'.$id1.'_city'.$form_id.'").val()=="" || wdconformjQuery("#wdform_'.$id1.'_state'.$form_id.'").val()=="" || wdconformjQuery("#wdform_'.$id1.'_postal'.$form_id.'").val()=="" || wdconformjQuery("#wdform_'.$id1.'_country'.$form_id.'").val()=="")

							{

								alert("'.addslashes(JText::sprintf('WDF_REQUIRED_FIELD', '"'.$label.'"') ).'");

								old_bg=x.find(wdconformjQuery("div[wdid='.$id1.']")).css("background-color");

								x.find(wdconformjQuery("div[wdid='.$id1.']")).effect( "shake", {}, 500 ).css("background-color","#FF8F8B").animate({backgroundColor: old_bg}, {duration: 500, queue: false });

								wdconformjQuery("#wdform_'.$id1.'_street1'.$form_id.'").focus();

								return false;

							}

							

						}

						';	

						

						$post=$input_get->getString('wdform_'.($id1+5).'_country'.$form_id);

						if(isset($post))

							$onload_js .=' wdconformjQuery("#wdform_'.$id1.'_country'.$form_id.'").val("'.$input_get->getString('wdform_'.($id1+5)."_country".$form_id, '').'");';

						

						if($w_disabled_fields[6]=='yes')

							$onload_js .=' wdconformjQuery("#wdform_'.$id1.'_country'.$form_id.'").change(function() { 

							if( wdconformjQuery(this).val()=="United States") 

							{

								wdconformjQuery("#wdform_'.$id1.'_state'.$form_id.'").parent().append("<select type=\"text\" id=\"wdform_'.$id1.'_state'.$form_id.'\" name=\"wdform_'.($id1+3).'_state'.$form_id.'\" style=\"width: 100%;\" '.$param['attributes'].'>'.addslashes($w_state_options).'</select><label class=\"mini_label\" style=\"display: block;\" id=\"'.$id1.'_mini_label_state\">'.$w_mini_labels[3].'</label>");

								wdconformjQuery("#wdform_'.$id1.'_state'.$form_id.'").parent().children("input:first, label:first").remove();

							}

							else

							{

								if(wdconformjQuery("#wdform_'.$id1.'_state'.$form_id.'").prop("tagName")=="SELECT")

								{

						

									wdconformjQuery("#wdform_'.$id1.'_state'.$form_id.'").parent().append("<input type=\"text\" id=\"wdform_'.$id1.'_state'.$form_id.'\" name=\"wdform_'.($id1+3).'_state'.$form_id.'\" value=\"'.$input_get->getString('wdform_'.($id1+3).'_state'.$form_id).'\" style=\"width: 100%;\" '.$param['attributes'].'><label class=\"mini_label\">'.$w_mini_labels[3].'</label>");

									wdconformjQuery("#wdform_'.$id1.'_state'.$form_id.'").parent().children("select:first, label:first").remove();	

								}

							}

						

						});';

						

					break;

				}



				case 'type_submitter_mail':

				{

					$params_names=array('w_field_label_size','w_field_label_pos','w_size','w_first_val','w_title','w_required','w_unique', 'w_class');

					$temp=$params;



					foreach($params_names as $params_name )

					{	

						$temp=explode('*:*'.$params_name.'*:*',$temp);

						$param[$params_name] = $temp[0];

						$temp=$temp[1];

					}

					

					

					if($temp)

					{	

						$temp	=explode('*:*w_attr_name*:*',$temp);

						$attrs	= array_slice($temp,0, count($temp)-1);   

						foreach($attrs as $attr)

							$param['attributes'] = $param['attributes'].' '.$attr;

					}

					

				

					$param['w_first_val']=htmlspecialchars($input_get->getString('wdform_'.$id1.'_element'.$form_id, $param['w_first_val']));	
					$wdformfieldsize = ($param['w_field_label_pos']=="left" ? ($param['w_field_label_size']+$param['w_size']) : max($param['w_field_label_size'], $param['w_size']));	

					$param['w_field_label_pos1'] = ($param['w_field_label_pos']=="left" ? "float: left;" : "");	

					$param['w_field_label_pos2'] = ($param['w_field_label_pos']=="left" ? "" : "display:block;");

					

					$input_active = ($param['w_first_val']==$param['w_title'] ? "input_deactive" : "input_active");	

					$required = ($param['w_required']=="yes" ? true : false);	

				



					$rep ='<div type="type_submitter_mail" class="wdform-field" style="width:'.$wdformfieldsize.'px"><div class="wdform-label-section" style="'.$param['w_field_label_pos1'].'; width: '.$param['w_field_label_size'].'px;"><span class="wdform-label">'.$label.'</span>';

					if($required)

						$rep.='<span class="wdform-required">'.$required_sym.'</span>';

					$rep.='</div><div class="wdform-element-section '.$param['w_class'].'" style="'.$param['w_field_label_pos2'].' width: '.$param['w_size'].'px;"><input type="text" class="'.$input_active.'" id="wdform_'.$id1.'_element'.$form_id.'" name="wdform_'.$id1.'_element'.$form_id.'" value="'.$param['w_first_val'].'" title="'.$param['w_title'].'"  style="width: 100%;" '.$param['attributes'].'></div></div>';

					

					if($required)

						$check_js.='

						if(x.find(wdconformjQuery("div[wdid='.$id1.']")).length != 0)

						{

							if(wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").val()=="'.$param['w_title'].'" || wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").val()=="")

							{

								alert("'.addslashes(JText::sprintf('WDF_REQUIRED_FIELD', '"'.$label.'"') ).'");

								wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").addClass( "form-error" );

								old_bg=x.find(wdconformjQuery("div[wdid='.$id1.']")).css("background-color");

								x.find(wdconformjQuery("div[wdid='.$id1.']")).effect( "shake", {}, 500 ).css("background-color","#FF8F8B").animate({backgroundColor: old_bg}, {duration: 500, queue: false });

								wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").focus();

								wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").change(function() { if( wdconformjQuery(this).val()!="" ) wdconformjQuery(this).removeClass("form-error"); else wdconformjQuery(this).addClass("form-error");});

								return false;

							}

						}

						';	

							

					$check_js.='

					if(x.find(wdconformjQuery("div[wdid='.$id1.']")).length != 0)

					{

					

					if(wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").val()!="" && wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").val().search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) == -1 )

						{

							alert("'.JText::_("WDF_INVALID_EMAIL").'");

							old_bg=x.find(wdconformjQuery("div[wdid='.$id1.']")).css("background-color");

							x.find(wdconformjQuery("div[wdid='.$id1.']")).effect( "shake", {}, 500 ).css("background-color","#FF8F8B").animate({backgroundColor: old_bg}, {duration: 500, queue: false });

							wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").focus();

							return false;

						}

					

					}

					';		

					

					break;

				}



				case 'type_checkbox':

				{

					$params_names=array('w_field_label_size','w_field_label_pos','w_flow','w_choices','w_choices_checked','w_rowcol', 'w_required','w_randomize','w_allow_other','w_allow_other_num','w_class');

					$temp=$params;



					foreach($params_names as $params_name )

					{	

						$temp=explode('*:*'.$params_name.'*:*',$temp);

						$param[$params_name] = $temp[0];

						$temp=$temp[1];

					}

					

					if($temp)

					{	

						$temp	=explode('*:*w_attr_name*:*',$temp);

						$attrs	= array_slice($temp,0, count($temp)-1);   

						foreach($attrs as $attr)

							$param['attributes'] = $param['attributes'].' '.$attr;

					}

				

					$param['w_field_label_pos1'] = ($param['w_field_label_pos']=="left" ? "float: left;" : "");	

					$param['w_field_label_pos2'] = ($param['w_field_label_pos']=="left" ? "" : "display:block;");

					

					$required = ($param['w_required']=="yes" ? true : false);	

					$param['w_choices']	= explode('***',$param['w_choices']);

					$param['w_choices_checked']	= explode('***',$param['w_choices_checked']);

				

					$post_value=$input_get->getString("counter".$form_id);

					$is_other=false;



					if(isset($post_value))

					{

						if($param['w_allow_other']=="yes")

						{

							$is_other=false;

							$other_element=$input_get->getString('wdform_'.$id1."_other_input".$form_id);

							if(isset($other_element))

								$is_other=true;

						}

					}

					else

						$is_other=($param['w_allow_other']=="yes" && $param['w_choices_checked'][$param['w_allow_other_num']]=='true') ;



					$rep='<div type="type_checkbox" class="wdform-field"><div class="wdform-label-section" style="'.$param['w_field_label_pos1'].'; width: '.$param['w_field_label_size'].'px;"><span class="wdform-label">'.$label.'</span>';

					if($required)

						$rep.='<span class="wdform-required">'.$required_sym.'</span>';

					$rep.='</div><div class="wdform-element-section '.$param['w_class'].'" style="'.$param['w_field_label_pos2'].';">';

				

					$rep.='<div style="display: '.($param['w_flow']=='hor' ? 'inline-block' : 'table-row' ).'; vertical-align:top">';



					foreach($param['w_choices'] as $key => $choice)

					{

						if($key%$param['w_rowcol']==0 && $key>0)

							$rep.='</div><div style="display: '.($param['w_flow']=='hor' ? 'inline-block' : 'table-row' ).';  vertical-align:top">';

						if(!isset($post_value))

							$param['w_choices_checked'][$key]=($param['w_choices_checked'][$key]=='true' ? 'checked="checked"' : '');

						else

						{

							$post_valuetemp=$input_get->getString('wdform_'.$id1."_element".$form_id.$key);

							$param['w_choices_checked'][$key]=(isset($post_valuetemp) ? 'checked="checked"' : '');

						}

						

						$rep.='<div style="display: '.($param['w_flow']!='hor' ? 'table-cell' : 'table-row' ).';"><label class="wdform-ch-rad-label" for="wdform_'.$id1.'_element'.$form_id.''.$key.'">'.$choice.'</label><div class="checkbox-div forlabs"><input type="checkbox" '.(($param['w_allow_other']=="yes" && $param['w_allow_other_num']==$key) ? 'other="1"' : ''	).' id="wdform_'.$id1.'_element'.$form_id.''.$key.'" name="wdform_'.$id1.'_element'.$form_id.''.$key.'" value="'.htmlspecialchars($choice).'" '.(($param['w_allow_other']=="yes" && $param['w_allow_other_num']==$key) ? 'onclick="if(set_checked(&quot;wdform_'.$id1.'&quot;,&quot;'.$key.'&quot;,&quot;'.$form_id.'&quot;)) show_other_input(&quot;wdform_'.$id1.'&quot;,&quot;'.$form_id.'&quot;);"' : '').' '.$param['w_choices_checked'][$key].' '.$param['attributes'].'><label for="wdform_'.$id1.'_element'.$form_id.''.$key.'"></label></div></div>';

					}

					$rep.='</div>';



					$rep.='</div></div>';

					

					if($required)

						$check_js.='

						if(x.find(wdconformjQuery("div[wdid='.$id1.']")).length != 0)

						{

							if(x.find(wdconformjQuery("div[wdid='.$id1.'] input:checked")).length == 0)

							{

								alert("'.addslashes(JText::sprintf('WDF_REQUIRED_FIELD', '"'.$label.'"') ).'");

								old_bg=x.find(wdconformjQuery("div[wdid='.$id1.']")).css("background-color");

								x.find(wdconformjQuery("div[wdid='.$id1.']")).effect( "shake", {}, 500 ).css("background-color","#FF8F8B").animate({backgroundColor: old_bg}, {duration: 500, queue: false });

								

								return false;

							}						

						}

						';	

					if($is_other)

						$onload_js .='show_other_input("wdform_'.$id1.'","'.$form_id.'"); wdconformjQuery("#wdform_'.$id1.'_other_input'.$form_id.'").val("'.$input_get->getString('wdform_'.$id1."_other_input".$form_id, '').'");';

					

					$onsubmit_js.='

						wdconformjQuery("<input type=\"hidden\" name=\"wdform_'.$id1.'_allow_other'.$form_id.'\" value = \"'.$param['w_allow_other'].'\" />").appendTo("#form'.$form_id.'");

						wdconformjQuery("<input type=\"hidden\" name=\"wdform_'.$id1.'_allow_other_num'.$form_id.'\" value = \"'.$param['w_allow_other_num'].'\" />").appendTo("#form'.$form_id.'");

						';

						

					break;

				}



				case 'type_radio':

				{

					$params_names=array('w_field_label_size','w_field_label_pos','w_flow','w_choices','w_choices_checked','w_rowcol', 'w_required','w_randomize','w_allow_other','w_allow_other_num','w_class');

					$temp=$params;



					foreach($params_names as $params_name )

					{	

						$temp=explode('*:*'.$params_name.'*:*',$temp);

						$param[$params_name] = $temp[0];

						$temp=$temp[1];

					}

					

					if($temp)

					{	

						$temp	=explode('*:*w_attr_name*:*',$temp);

						$attrs	= array_slice($temp,0, count($temp)-1);   

						foreach($attrs as $attr)

							$param['attributes'] = $param['attributes'].' '.$attr;

					}

				

					$param['w_field_label_pos1'] = ($param['w_field_label_pos']=="left" ? "float: left;" : "");	

					$param['w_field_label_pos2'] = ($param['w_field_label_pos']=="left" ? "" : "display:block;");

					

					$required = ($param['w_required']=="yes" ? true : false);	

					$param['w_choices']	= explode('***',$param['w_choices']);

					$param['w_choices_checked']	= explode('***',$param['w_choices_checked']);



					$post_value=$input_get->getString("counter".$form_id);

					$is_other=false;



					if(isset($post_value))

					{

						if($param['w_allow_other']=="yes")

						{

							$is_other=false;

							$other_element=$input_get->getString('wdform_'.$id1."_other_input".$form_id);

							if(isset($other_element))

								$is_other=true;

						}

					}

					else

						$is_other=($param['w_allow_other']=="yes" && $param['w_choices_checked'][$param['w_allow_other_num']]=='true') ;

					

					$rep='<div type="type_radio" class="wdform-field"><div class="wdform-label-section" style="'.$param['w_field_label_pos1'].'; width: '.$param['w_field_label_size'].'px;"><span class="wdform-label">'.$label.'</span>';

					if($required)

						$rep.='<span class="wdform-required">'.$required_sym.'</span>';

					$rep.='</div><div class="wdform-element-section '.$param['w_class'].'" style="'.$param['w_field_label_pos2'].';">';

				

					$rep.='<div style="display: '.($param['w_flow']=='hor' ? 'inline-block' : 'table-row' ).'; vertical-align:top">';



					foreach($param['w_choices'] as $key => $choice)

					{			

						if($key%$param['w_rowcol']==0 && $key>0)

							$rep.='</div><div style="display: '.($param['w_flow']=='hor' ? 'inline-block' : 'table-row' ).';  vertical-align:top">';

						if(!isset($post_value))

							$param['w_choices_checked'][$key]=($param['w_choices_checked'][$key]=='true' ? 'checked="checked"' : '');

						else

							$param['w_choices_checked'][$key]=(htmlspecialchars($choice)==htmlspecialchars($input_get->getString('wdform_'.$id1."_element".$form_id)) ? 'checked="checked"' : '');

						

						$rep.='<div style="display: '.($param['w_flow']!='hor' ? 'table-cell' : 'table-row' ).';"><label class="wdform-ch-rad-label" for="wdform_'.$id1.'_element'.$form_id.''.$key.'">'.$choice.'</label><div class="radio-div forlabs"><input type="radio" '.(($param['w_allow_other']=="yes" && $param['w_allow_other_num']==$key) ? 'other="1"' : ''	).' id="wdform_'.$id1.'_element'.$form_id.''.$key.'" name="wdform_'.$id1.'_element'.$form_id.'" value="'.htmlspecialchars($choice).'" onclick="set_default(&quot;wdform_'.$id1.'&quot;,&quot;'.$key.'&quot;,&quot;'.$form_id.'&quot;); '.(($param['w_allow_other']=="yes" && $param['w_allow_other_num']==$key) ? 'show_other_input(&quot;wdform_'.$id1.'&quot;,&quot;'.$form_id.'&quot;);' : '').'" '.$param['w_choices_checked'][$key].' '.$param['attributes'].'><label for="wdform_'.$id1.'_element'.$form_id.''.$key.'"></label></div></div>';

					}

							$rep.='</div>';



					$rep.='</div></div>';

				

					if($required)

						$check_js.='

						if(x.find(wdconformjQuery("div[wdid='.$id1.']")).length != 0)

						{

							if(x.find(wdconformjQuery("div[wdid='.$id1.'] input:checked")).length == 0)

							{

								alert("'.addslashes(JText::sprintf('WDF_REQUIRED_FIELD', '"'.$label.'"') ).'");

								old_bg=x.find(wdconformjQuery("div[wdid='.$id1.']")).css("background-color");

								x.find(wdconformjQuery("div[wdid='.$id1.']")).effect( "shake", {}, 500 ).css("background-color","#FF8F8B").animate({backgroundColor: old_bg}, {duration: 500, queue: false });

								

								return false;

							}						

						}

						';		

					if($is_other)

						$onload_js .='show_other_input("wdform_'.$id1.'","'.$form_id.'"); wdconformjQuery("#wdform_'.$id1.'_other_input'.$form_id.'").val("'.$input_get->getString('wdform_'.$id1."_other_input".$form_id, '').'");';

					

					$onsubmit_js.='

						wdconformjQuery("<input type=\"hidden\" name=\"wdform_'.$id1.'_allow_other'.$form_id.'\" value = \"'.$param['w_allow_other'].'\" />").appendTo("#form'.$form_id.'");

						wdconformjQuery("<input type=\"hidden\" name=\"wdform_'.$id1.'_allow_other_num'.$form_id.'\" value = \"'.$param['w_allow_other_num'].'\" />").appendTo("#form'.$form_id.'");

						';

						

					break;

				}



				case 'type_own_select':

				{

					$params_names=array('w_field_label_size','w_field_label_pos','w_size','w_choices','w_choices_checked', 'w_choices_disabled','w_required','w_class');

					$temp=$params;



					foreach($params_names as $params_name )

					{	

						$temp=explode('*:*'.$params_name.'*:*',$temp);

						$param[$params_name] = $temp[0];

						$temp=$temp[1];

					}

					

					

					if($temp)

					{	

						$temp	=explode('*:*w_attr_name*:*',$temp);

						$attrs	= array_slice($temp,0, count($temp)-1);   

						foreach($attrs as $attr)

							$param['attributes'] = $param['attributes'].' '.$attr;

					}

					

				

					$wdformfieldsize = ($param['w_field_label_pos']=="left" ? ($param['w_field_label_size']+$param['w_size']) : max($param['w_field_label_size'], $param['w_size']));	

					$param['w_field_label_pos1'] = ($param['w_field_label_pos']=="left" ? "float: left;" : "");	

					$param['w_field_label_pos2'] = ($param['w_field_label_pos']=="left" ? "" : "display:block;");

					

					$required = ($param['w_required']=="yes" ? true : false);	

					$param['w_choices']	= explode('***',$param['w_choices']);

					$param['w_choices_checked']	= explode('***',$param['w_choices_checked']);

					$param['w_choices_disabled']	= explode('***',$param['w_choices_disabled']);

					

					$post_value=$input_get->getString("counter".$form_id);

					



					$rep='<div type="type_own_select" class="wdform-field" style="width:'.$wdformfieldsize.'px"><div class="wdform-label-section" style="'.$param['w_field_label_pos1'].'; width: '.$param['w_field_label_size'].'px;"><span class="wdform-label">'.$label.'</span>';

					if($required)

						$rep.='<span class="wdform-required">'.$required_sym.'</span>';

					$rep.='</div><div class="wdform-element-section '.$param['w_class'].'" style="'.$param['w_field_label_pos2'].' width: '.($param['w_size']).'px; "><select id="wdform_'.$id1.'_element'.$form_id.'" name="wdform_'.$id1.'_element'.$form_id.'" style="width: 100%;"  '.$param['attributes'].'>';

					foreach($param['w_choices'] as $key => $choice)

					{

						if(!isset($post_value))

							$param['w_choices_checked'][$key]=($param['w_choices_checked'][$key]=='true' ? 'selected="selected"' : '');

						else

							$param['w_choices_checked'][$key]=(htmlspecialchars($choice)==htmlspecialchars($input_get->getString('wdform_'.$id1."_element".$form_id)) ? 'selected="selected"' : '');



						if($param['w_choices_disabled'][$key]=="true")

							$choice_value='';

						else

							$choice_value=$choice;

					  $rep.='<option id="wdform_'.$id1.'_option'.$key.'" value="'.htmlspecialchars($choice_value).'" '.$param['w_choices_checked'][$key].'>'.$choice.'</option>';

					}

					$rep.='</select></div></div>';

					

					if($required)

						$check_js.='

						if(x.find(wdconformjQuery("div[wdid='.$id1.']")).length != 0)

						{

							if( wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").val()=="")

								{

									alert("'.addslashes(JText::sprintf('WDF_REQUIRED_FIELD', '"'.$label.'"') ).'");

									wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").addClass( "form-error" );

									old_bg=x.find(wdconformjQuery("div[wdid='.$id1.']")).css("background-color");

									x.find(wdconformjQuery("div[wdid='.$id1.']")).effect( "shake", {}, 500 ).css("background-color","#FF8F8B").animate({backgroundColor: old_bg}, {duration: 500, queue: false });

									wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").focus();

									wdconformjQuery("#wdform_'.$id1.'_element'.$form_id.'").change(function() { if( wdconformjQuery(this).val()!="" ) wdconformjQuery(this).removeClass("form-error"); else wdconformjQuery(this).addClass("form-error");});

									return false;

								}

						}

						';		



					break;

				}


				case 'type_captcha':

				{

					$params_names=array('w_field_label_size','w_field_label_pos','w_digit','w_class');

					$temp=$params;



					foreach($params_names as $params_name )

					{	

						$temp=explode('*:*'.$params_name.'*:*',$temp);

						$param[$params_name] = $temp[0];

						$temp=$temp[1];

					}

					

					if($temp)

					{	

						$temp	=explode('*:*w_attr_name*:*',$temp);

						$attrs	= array_slice($temp,0, count($temp)-1);   

						foreach($attrs as $attr)

							$param['attributes'] = $param['attributes'].' '.$attr;

					}

					

					$param['w_field_label_pos1'] = ($param['w_field_label_pos']=="left" ? "float: left;" : "");	

					$param['w_field_label_pos2'] = ($param['w_field_label_pos']=="left" ? "" : "display:block;");

					

					

					$rep ='<div type="type_captcha" class="wdform-field"><div class="wdform-label-section" style="'.$param['w_field_label_pos1'].' width: '.$param['w_field_label_size'].'px;"><span class="wdform-label">'.$label.'</span></div><div class="wdform-element-section '.$param['w_class'].'" style="'.$param['w_field_label_pos2'].'"><div style="display: table;"><div style="display: table-cell;vertical-align: middle;"><div valign="middle" style="display: table-cell; text-align: center; vertical-align:top;"><img type="captcha" digit="'.$param['w_digit'].'" src="index.php?option=com_contactformmaker&amp;view=wdcaptcha&amp;format=raw&amp;tmpl=component&amp;digit='.$param['w_digit'].'&amp;i='.$form_id.'" id="wd_captcha'.$form_id.'" class="captcha_img" style="display:none" '.$param['attributes'].'></div><div valign="middle" style="display: table-cell;"><div class="captcha_refresh" id="_element_refresh'.$form_id.'" '.$param['attributes'].'></div></div></div><div style="display: table-cell;vertical-align: middle;"><div style="display: table-cell;"><input type="text" class="captcha_input" id="wd_captcha_input'.$form_id.'" name="captcha_input" style="width: '.($param['w_digit']*10+15).'px;" '.$param['attributes'].'></div></div></div></div></div>';		

					

					$onload_js .='wdconformjQuery("#wd_captcha'.$form_id.'").click(function() {captcha_refresh("wd_captcha","'.$form_id.'")});';

					$onload_js .='wdconformjQuery("#_element_refresh'.$form_id.'").click(function() {captcha_refresh("wd_captcha","'.$form_id.'")});';

					

					$check_js.='

					if(x.find(wdconformjQuery("div[wdid='.$id1.']")).length != 0)

					{

						if(wdconformjQuery("#wd_captcha_input'.$form_id.'").val()=="")

						{

							alert("'.addslashes(JText::sprintf('WDF_REQUIRED_FIELD', '"'.$label.'"') ).'");

							old_bg=x.find(wdconformjQuery("div[wdid='.$id1.']")).css("background-color");

							x.find(wdconformjQuery("div[wdid='.$id1.']")).effect( "shake", {}, 500 ).css("background-color","#FF8F8B").animate({backgroundColor: old_bg}, {duration: 500, queue: false });

							wdconformjQuery("#wd_captcha_input'.$form_id.'").focus();

							return false;

						}

					}

					';

					

					$onload_js.= 'captcha_refresh("wd_captcha", "'.$form_id.'");';



					break;

				}


case 'type_recaptcha':
				{
					$params_names=array('w_field_label_size','w_field_label_pos','w_public','w_private','w_theme','w_class');
					$temp=$params;

					foreach($params_names as $params_name )
					{	
						$temp=explode('*:*'.$params_name.'*:*',$temp);
						$param[$params_name] = $temp[0];
						$temp=$temp[1];
					}
					
					
					if($temp)
					{	
						$temp	=explode('*:*w_attr_name*:*',$temp);
						$attrs	= array_slice($temp,0, count($temp)-1);   
						foreach($attrs as $attr)
							$param['attributes'] = $param['attributes'].' '.$attr;
					}
					
				
						
					$param['w_field_label_pos1'] = ($param['w_field_label_pos']=="left" ? "float: left;" : "");	
					$param['w_field_label_pos2'] = ($param['w_field_label_pos']=="left" ? "" : "display:block;");
					
				
						$publickey = isset($globalParams->public_key) ? $globalParams->public_key : '';
					$error = null;
				
					$rep ='<script src="https://www.google.com/recaptcha/api.js"></script><div type="type_recaptcha" class="wdform-field"><div class="wdform-label-section" style="'.$param['w_field_label_pos1'].'; width: '.$param['w_field_label_size'].'px;"><span class="wdform-label">'.$label.'</span></div><div class="wdform-element-section '.$param['w_class'].'" style="'.$param['w_field_label_pos2'].';"><div class="g-recaptcha" data-sitekey="'.$publickey.'"></div></div></div>';
			
					break;
				}
				
				case 'type_map':

				{

					$params_names=array('w_center_x','w_center_y','w_long','w_lat','w_zoom','w_width','w_height','w_info','w_class');



					$temp=$params;



					foreach($params_names as $params_name )

					{	

						$temp=explode('*:*'.$params_name.'*:*',$temp);

						$param[$params_name] = $temp[0];

						$temp=$temp[1];

					}

					

					if($temp)

					{	

						$temp	=explode('*:*w_attr_name*:*',$temp);

						$attrs	= array_slice($temp,0, count($temp)-1);   

						foreach($attrs as $attr)

							$param['attributes'] = $param['attributes'].' '.$attr;

					}

					

					$marker='';

					

					$param['w_long']	= explode('***',$param['w_long']);

					$param['w_lat']	= explode('***',$param['w_lat']);

					$param['w_info']	= explode('***',$param['w_info']);

					foreach($param['w_long'] as $key => $w_long )

					{

						$marker.='long'.$key.'="'.$w_long.'" lat'.$key.'="'.$param['w_lat'][$key].'" info'.$key.'="'.$param['w_info'][$key].'"';

					}



					$rep ='<div type="type_map" class="wdform-field" style="width:'.($param['w_width']).'px"><div class="wdform-label-section" style="display: table-cell;"><span id="wdform_'.$id1.'_element_label'.$form_id.'" style="display: none;">'.$label.'</span></div><div class="wdform-element-section '.$param['w_class'].'" style="width: '.$param['w_width'].'px;"><div id="wdform_'.$id1.'_element'.$form_id.'" zoom="'.$param['w_zoom'].'" center_x="'.$param['w_center_x'].'" center_y="'.$param['w_center_y'].'" style="width: 100%; height: '.$param['w_height'].'px;" '.$marker.' '.$param['attributes'].'></div></div></div>';

					

					$onload_js .='if_gmap_init("wdform_'.$id1.'", '.$form_id.');';

					

					foreach($param['w_long'] as $key => $w_long )

					{

						$onload_js .='add_marker_on_map("wdform_'.$id1.'",'.$key.', "'.$w_long.'", "'.$param['w_lat'][$key].'", "'.$param['w_info'][$key].'", '.$form_id.',false);';

					}

					

					break;

				}


				case 'type_submit_reset':

				{

					

					$params_names=array('w_submit_title','w_reset_title','w_class','w_act');

					$temp=$params;



					foreach($params_names as $params_name )

					{	

						$temp=explode('*:*'.$params_name.'*:*',$temp);

						$param[$params_name] = $temp[0];

						$temp=$temp[1];

					}

					

					if($temp)

					{	

						$temp	=explode('*:*w_attr_name*:*',$temp);

						$attrs	= array_slice($temp,0, count($temp)-1);   

						foreach($attrs as $attr)

							$param['attributes'] = $param['attributes'].' '.$attr;

					}

					

					

					$param['w_act'] = ($param['w_act']=="false" ? 'style="display: none;"' : "");	

					

					$rep='<div type="type_submit_reset" class="wdform-field"><div class="wdform-label-section" style="display: table-cell;"></div><div class="wdform-element-section '.$param['w_class'].'" style="display: table-cell;"><button type="button" class="button-submit" onclick="check_required'.$form_id.'(&quot;submit&quot;, &quot;'.$form_id.'&quot;);" '.$param['attributes'].'>'.$param['w_submit_title'].'</button><button type="button" class="button-reset" onclick="check_required'.$form_id.'(&quot;reset&quot;);" '.$param['w_act'].' '.$param['attributes'].'>'.$param['w_reset_title'].'</button></div></div>';

				

					break;

				}



			}

			

			$form=str_replace('%'.$id1.' - '.$labels[$id1s_key].'%', $rep, $form);

		}

		

	}





	$rep1=array('form_id_temp');

	$rep2=array($id.'contact');



	$form = str_replace($rep1,$rep2,$form);



	echo $form;

?>



<div class="wdform_preload"></div>



</form>

<script type="text/javascript">

JURI_ROOT				='<?php echo JURI::root(true) ?>';  


function contactformOnload<?php echo $id; ?>()

{

	if (wdconformjQuery.browser.msie  && parseInt(wdconformjQuery.browser.version, 10) === 8) 

	{

		wdconformjQuery("#form<?php echo $id; ?>").find(wdconformjQuery("input[type='radio']")).click(function() {wdconformjQuery("input[type='radio']+label").removeClass('if-ie-div-label'); wdconformjQuery("input[type='radio']:checked+label").addClass('if-ie-div-label')});	

		wdconformjQuery("#form<?php echo $id; ?>").find(wdconformjQuery("input[type='radio']:checked+label")).addClass('if-ie-div-label');

		

		wdconformjQuery("#form<?php echo $id; ?>").find(wdconformjQuery("input[type='checkbox']")).click(function() {wdconformjQuery("input[type='checkbox']+label").removeClass('if-ie-div-label'); wdconformjQuery("input[type='checkbox']:checked+label").addClass('if-ie-div-label')});	

		wdconformjQuery("#form<?php echo $id; ?>").find(wdconformjQuery("input[type='checkbox']:checked+label")).addClass('if-ie-div-label');

	}



	wdconformjQuery("div[type='type_text'] input, div[type='type_number'] input, div[type='type_phone'] input, div[type='type_name'] input, div[type='type_submitter_mail'] input, div[type='type_textarea'] textarea").focus(function() {delete_value(this)}).blur(function() {return_value(this)});

	wdconformjQuery("div[type='type_number'] input, div[type='type_phone'] input, div[type='type_spinner'] input").keypress(function(evt) {return check_isnum(evt)});


	wdconformjQuery('.wdform-element-section').each(function() {

		if(parseInt(wdconformjQuery(this)[0].style.width.replace('px', '')) < parseInt(wdconformjQuery(this).css('min-width').replace('px', '')))

			wdconformjQuery(this).css('min-width', parseInt(wdconformjQuery(this)[0].style.width.replace('px', ''))-10);

	});

	

	wdconformjQuery('.wdform-label').each(function() {

		if(parseInt(wdconformjQuery(this).height()) >= 2*parseInt(wdconformjQuery(this).css('line-height').replace('px', '')))

		{

			wdconformjQuery(this).parent().css('max-width',wdconformjQuery(this).parent().width());

			wdconformjQuery(this).parent().css('width','');

		}

	});

	

	<?php echo $onload_js; ?>


}



function contactformAddToOnload<?php echo $id ?>()

{ 



	if(contactformOldFunctionOnLoad<?php echo $id ?>){ contactformOldFunctionOnLoad<?php echo $id ?>(); }

	contactformOnload<?php echo $id ?>();

}



function contactformLoadBody<?php echo $id ?>()

{

	contactformOldFunctionOnLoad<?php echo $id ?> = window.onload;

	window.onload = contactformAddToOnload<?php echo $id ?>;

}



var contactformOldFunctionOnLoad<?php echo $id ?> = null;

contactformLoadBody<?php echo $id ?>();

if(document.getElementById(<?php echo $id ?>+'contactform_view1'))

	{

		wdform_page=document.getElementById(<?php echo $id ?>+'contactform_view1');

		remove_whitespace(wdform_page);

		n=wdform_page.childNodes.length-2;



		for(z=0;z<=n;z++)

		{

			if(wdform_page.childNodes[z])

			{

				if(wdform_page.childNodes[z].getAttribute("disabled"))

				{

					var wdform_section_break = wdform_page.childNodes[z];	

						

						move=wdform_section_break.nextSibling;

						to=wdform_section_break.previousSibling;

						l=move.childNodes.length;

						for(k=0;k<l;k++)

						{

							if(to.childNodes[k])

							{

								while(move.childNodes[k].firstChild)

									to.childNodes[k].appendChild(move.childNodes[k].firstChild);

							}

							else

								to.appendChild(move.childNodes[k]);			

						}

						

						wdform_section_break.parentNode.removeChild(wdform_section_break.nextSibling);		

						wdform_section_break.parentNode.removeChild(wdform_section_break);					

				}	

			}

		}	

	}

	

	function check_required<?php echo $form_id ?>(but_type)

	{

		if(but_type=='reset')

		{


			window.location="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']) ?>";

			return;

		}



		x=wdconformjQuery("#contactform<?php echo $form_id; ?>");

		<?php echo $check_js ?> ;



		if(a[<?php echo $form_id ?>]==1)

			return;

		<?php echo $onsubmit_js; ?>;

		a[<?php echo $form_id ?>]=1;

		document.getElementById("contactform"+<?php echo $form_id ?>).submit();

	}

</script>

	<?php


		$content=ob_get_contents();

		ob_end_clean();

		return $content;


}

	

}?>