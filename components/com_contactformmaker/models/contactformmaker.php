<?php 
  
 /**
 * @package Contact Form Maker
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('_JEXEC') or die('Restricted access'); 
jimport( 'joomla.application.component.model' );
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
class contactformmakerModelcontactformmaker extends JModelLegacy
{	
	function showform()
	{
		$input_get = JFactory::getApplication()->input;
		$id=$input_get->getString('id',0);
		$Itemid=$input_get->getString('Itemid'.$id);
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__contactformmaker WHERE id=".$db->escape((int)$id)); 
		$row = $db->loadObject();
		if ($db->getErrorNum())	{echo $db->stderr(); return false;}	
		if(!$row)
			return false;
		
		if($row->published!=1)
			return false;
		
		$test_theme = $input_get->getString('test_theme');
		$row->theme = (isset($test_theme) ? $test_theme : $row->theme);
		$db->setQuery("SELECT css FROM #__contactformmaker_themes WHERE id=".$db->escape((int)$row->theme)); 
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

	function savedata($form)
	{	
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__contactformmaker_options"); 
		$globalParams = $db->loadObject();
		if ($db->getErrorNum())	{echo $db->stderr(); return false;}	
		
		$input_get = JFactory::getApplication()->input;
		$correct=false;
		$all_files=array();
		$mainframe = JFactory::getApplication();
		@session_start();
		$id=$input_get->getString('id',0);
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
				{
					
					
					$correct=true;

		

				}

				

				if($correct)

			{
			
				$ip=$_SERVER['REMOTE_ADDR'];

				$db->setQuery("SELECT ip FROM #__contactformmaker_blocked WHERE ip LIKE '%".$db->escape($ip)."%'"); 
				$db->query();
				$blocked_ip = $db->loadResult();

				if($blocked_ip)		
				$mainframe->redirect($_SERVER["REQUEST_URI"], addslashes(JText::_('WDF_BLOCKED_IP')));

					$all_files=$this->save_db($counter);

					if(is_numeric($all_files))		
						$this->remove($all_files);
					else					
						if(isset($counter))
						{
							$this->gen_mail($counter, $all_files);
						}
		
				}
	

			return $all_files;
		}

		return $all_files;
			
			
	}
	
	function save_db($counter)
	{
		$input_get = JFactory::getApplication()->input;

		$id=$input_get->getString('id');
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
			if($type=="type_submit_reset" or $type=="type_map" or $type=="type_editor" or  $type=="type_captcha" or  $type=="type_recaptcha" or  $type=="type_button" or $type=="type_paypal_total"  or $type=="type_send_copy")
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
		{		
			$mainframe = JFactory::getApplication();
	
			if(count($all_files)==0)
				$mainframe->redirect($_SERVER["REQUEST_URI"], addslashes(JText::_('WDF_EMPTY_SUBMIT')));
		}
		
		return $all_files;
	}
	
	
	function gen_mail($counter, $all_files)
	{
		$input_get = JFactory::getApplication()->input;
		@session_start();
		$mainframe = JFactory::getApplication();
		$id=$input_get->getString('id');
		$Itemid=$input_get->getString('Itemid'.$id);
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_contactformmaker'.DS.'tables');
		$row = JTable::getInstance('contactformmaker', 'Table');
		$ip=$_SERVER['REMOTE_ADDR'];
		
		$row->load( $id);
	
			$cc=array();
			$label_order_original= array();
			$label_order_ids= array();
			$label_type= array();
			

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

				if($type!="type_map" and  $type!="type_submit_reset" and  $type!="type_editor" and  $type!="type_captcha" and  $type!="type_recaptcha" and  $type!="type_button")
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
					
						break;
					}
					case "3":
					{
						$_SESSION['show_submit_text'.$id]=1;
						$redirect_url=$_SERVER["HTTP_REFERER"];
						
						break;
					}											
					case "4":
					{
						$redirect_url=$row->url;
						
						break;
					}
					default:
					{
						$redirect_url=$_SERVER["HTTP_REFERER"];
						
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
	
	
	function sendMail($from, $fromname, $recipient, $subject, $body, $mode=0, $cc=null, $bcc=null, $attachment=null, $replyto=null, $replytoname=null)
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
	
	
	
	function remove($group_id)
	{
		$input_get = JFactory::getApplication()->input;

		$db = JFactory::getDBO();
		$db->setQuery('DELETE FROM #__contactformmaker_submits WHERE group_id="'.$db->escape((int)$group_id).'"');
		$db->query();
	}
	
}
	
	?>
