<?php 
  
 /**
 * @package Contact Form Maker
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT . '/admin.contactformmaker.html.php';
require_once JPATH_COMPONENT . '/toolbar.contactformmaker.html.php';
require_once JPATH_COMPONENT . '/controller.php' ;

JTable::addIncludePath( JPATH_COMPONENT.'/tables' );


$controller = new contactformmakerController();


$task	= JRequest::getCmd('task'); 

$id = JRequest::getVar('id', 0, 'get', 'int');



// checks the $task variable and 
// choose an appropiate function


switch ( $task )

{

	case 'themes'  :
	{
		TOOLBAR_contactformmaker::_THEMES();
	}
		break;
		
	case 'add_themes'  :
	case 'edit_themes'  :
	{
		TOOLBAR_contactformmaker::_NEW_THEMES();
	}
		break;
		
	case 'blocked_ips'  :

	{

		TOOLBAR_contactformmaker::_Blocked_ips();

	}	
		break;

		

	case 'add_blocked_ips'  :

	case 'edit_blocked_ips'  :

	{

		TOOLBAR_contactformmaker::_NEW_Blocked_ips();

	}

		break;
		
	case 'submits'  :
	{
		TOOLBAR_contactformmaker::_SUBMITS();
	}
		break;
		
		
	case 'add'  :
	case 'edit'  :	
	{
		TOOLBAR_contactformmaker::_NEW();
	}
		break;

	case 'form_options'  :
	{
	TOOLBAR_contactformmaker::_NEW_Form_options();
	}
	break;

	case 'form_layout':
	{
	TOOLBAR_contactformmaker::_NEW_Form_form_layout();
	}
	break;

	case 'global_options':
	{
	TOOLBAR_contactformmaker::_NEW_Form_global_options();
	}
	break;
	default:
		TOOLBAR_contactformmaker::_DEFAULT();
		break;

}



switch($task){

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	case 'global_options':
	{
		global_options();
	}
	break;

	case 'generate_xml':
		generate_xml();
		break;
		
	case 'generate_csv':
		generate_csv();
		break;
		
	case 'default':
		setdefault();
		break;
		
	case 'edit_css':
		edit_css();
		break;		
	
	case 'themes':
		show_themes();
		break;
		
	case 'add_themes':
		add_themes();
		break;
		
	case 'edit_themes':
		edit_themes();
		break;

	case 'apply_blocked_ips':	
	case 'save_blocked_ips':		
		save_blocked_ips($task);

		break;			
	
	case 'apply_themes':	
	case 'save_themes':		
		save_themes($task);
		break;
		 
	case 'save_for_edit':	
	case 'apply_for_edit':
	case 'save_new_theme':		
	save_new_theme($task);
	
		break;		 
	
	case 'remove_themes':
		remove_themes();
		break;
		
	case 'cancel_themes':
		cancel_themes();
		break;
		
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	case 'save_global_options':
		save_global_options();
		break;	
	case 'cancel_blocked_ips':
		cancel_blocked_ips();
		break;	

	case 'forms':
		show();
		
		break;
		
	case 'submits':
		show_submits();
		
		break;

	case 'element':

		$controller->execute( $task );

		$controller->redirect();

		break;


	case 'select_article':

		select_article();
		break;

	case 'add':

		add();

		break;

	case 'add_blocked_ips':
		add_blocked_ips();
		break;	
		
	case 'cancel':		

		cancel();

		break;

	case 'apply':	
	case 'save':		

		save($task);

		break;

	case 'edit':

    		edit();

    		break;
	case 'edit_blocked_ips':
    	edit_blocked_ips();
    	break;		
	case 'save_as_copy':

    		save_as_copy();
    		break;
			
	case 'copy':

    		copy_form();
    		break;
			
	case 'form_options':
			form_options();
			break;
	case 'form_options_temp':
    		form_options_temp();
    		break;
			
	case 'form_layout':
			form_layout();
			break;	
	case 'form_layout_temp':
			form_layout_temp();
			break;	
	case 'blocked_ips':
		show_blocked_ips();
		break;			
//////////////////////////////////////////////////////////////////		
	case 'apply_form_options':
	case 'save_form_options':
    		save_form_options($task);
    		break;
	case 'apply_form_layout':
	case 'save_form_layout':
    		save_form_layout($task);
    		break;
//////////////////////////////////////////////////////////////////		
	case 'cancel_secondary':
    		cancelSecondary();
    		break;
		

//////////////////////////////////////////////////////////////////		
	case 'remove':

		remove();

		break;

	case 'remove_blocked_ips':
		remove_blocked_ips();
		break;	
	case 'remove_submit':
		removeSubmit();
		break;
	
	case 'block_ip':
		blockIP();
		break;	
	

	case 'save_submit':
	case 'apply_submit':
		saveSubmit($task);
		break;

	case 'cancel_submit':
		cancelSubmit();
		break;

 	 case 'publish':
   		change(1 );
    		break;

	 case 'unpublish':
	   	change(0 );
	    	break;				

	 case 'gotoedit':
	   	gotoedit();
	    	break;	
	 
	 case 'submit_info':
	   	submit_info();
	    	break;

	default:
		showredirect();
		break;



}

function global_options(){
	$row = JTable::getInstance('contactformmaker_options', 'Table');
	$row->load(1);
	
	HTML_contact::global_options($row);
}

function save_global_options(){
	$mainframe = JFactory::getApplication();
	$row = JTable::getInstance('contactformmaker_options', 'Table');
	$row->load(1);
	if(!$row->bind(JRequest::get('post')))
	{
		JError::raiseError(500, $row->getError() );

	}
	
	if(!$row->store()){
		JError::raiseError(500, $row->getError() );

	}
	
	$msg ='Global Options have been saved successfully.';
	$link ='index.php?option=com_contactformmaker&task=global_options';
		
	$mainframe->redirect($link, $msg);
}

function generate_csv()
{

	$db		= JFactory::getDBO();
	$id 	= JRequest::getVar('id');

	$checked_ids = JRequest::getVar('checked_ids');
	if($checked_ids)
	$checked_ids = trim($checked_ids, ",");


$user = JFactory::getUser();
 
if ($user->guest)
{
	echo 'You have no permissions to download csv';
	return;
}
	
if(!(in_array(7,$user->groups) || in_array(8,$user->groups)))
{ 
	echo 'You have no permissions to download csv';
	return;
}

$form_id=$id;

	if ($checked_ids) 
		$query = "SELECT * FROM #__contactformmaker_submits where form_id=".$form_id." AND group_id IN ( ". $checked_ids ." )";
	else
		$query = "SELECT * FROM #__contactformmaker_submits where form_id=$form_id ";
		
	$db->setQuery( $query);
	$rows = $db->loadObjectList();
	if($db->getErrorNum()){
		echo $db->stderr();
		return false;
	}

	$n=count($rows);

	$labels= array();
	for($i=0; $i < $n ; $i++)
	{
		$row = &$rows[$i];
		if(!in_array($row->element_label, $labels))
		{
			array_push($labels, $row->element_label);
		}
	}
	$label_titles=array();
	$sorted_labels= array();
 
 $query_lable = "SELECT label_order,title FROM #__contactformmaker where id=$form_id ";

	$db->setQuery( $query_lable);

	$rows_lable = $db->loadObjectList();

	if($db->getErrorNum()){

		echo $db->stderr();

		return false;

	}
	
	$ptn = "/[^a-zA-Z0-9_]/";
			$rpltxt = "";
		
	
	$title=preg_replace($ptn, $rpltxt, $rows_lable[0]->title);

	$sorted_labels_id= array();
	$sorted_labels= array();
	$label_titles=array();
	if($labels)
	{
	
		$label_id= array();
		$label_order= array();
		$label_order_original= array();
		$label_type= array();
	
		///stexic
		$label_all	= explode('#****#',$rows_lable[0]->label_order);
		$label_all 	= array_slice($label_all,0, count($label_all)-1);   
	
	
	
		foreach($label_all as $key => $label_each) 
		{
			$label_id_each=explode('#**id**#',$label_each);
			array_push($label_id, $label_id_each[0]);
		
			$label_oder_each=explode('#**label**#', $label_id_each[1]);
		
			array_push($label_order_original, $label_oder_each[0]);
		
			$ptn = "/[^a-zA-Z0-9_]/";
			$rpltxt = "";
			$label_temp=preg_replace($ptn, $rpltxt, $label_oder_each[0]);
			array_push($label_order, $label_temp);
		
			array_push($label_type, $label_oder_each[1]);

	
			//echo $label."<br>";
		
		}
	
		foreach($label_id as $key => $label) 
			if(in_array($label, $labels))
			{
				array_push($sorted_labels, $label_order[$key]);
				array_push($sorted_labels_id, $label);
				array_push($label_titles, $label_order_original[$key]);
			}
		

	}

 	$m=count($sorted_labels);
	$group_id_s= array();
	$l=0;
	 

	if(count($rows)>0 and $m)
	for($i=0; $i <count($rows) ; $i++)
	{

		$row = &$rows[$i];

		if(!in_array($row->group_id, $group_id_s))
		{
	
			array_push($group_id_s, $row->group_id);
		
		}
	}
 

  
	 $data=array();

 
	for($www=0;  $www < count($group_id_s); $www++)
	{
	$i=$group_id_s[$www];

	$temp= array();
	for($j=0; $j < $n ; $j++)
	{

		$row = &$rows[$j];

		if($row->group_id==$i)
		{

			array_push($temp, $row);
		}
	}



	$f=$temp[0];
	$date=$f->date;
	$ip=$f->ip;
	$data_temp['Submit date']=$date;
	$data_temp['Ip']=$ip;


	$ttt=count($temp);

	for($h=0; $h < $m ; $h++)
	{	
		$data_temp[$label_titles[$h]]='';

		for($g=0; $g < $ttt ; $g++)
		{		
			$t = $temp[$g];
			if($t->element_label==$sorted_labels_id[$h])
			{
				if(strpos($t->element_value,"*@@url@@*"))
				{
					$new_file=str_replace("*@@url@@*",'', $t->element_value);
					$new_filename=explode('/', $new_file);
					$data_temp[$label_titles[$h]]=$new_file;
				}
				else
					if(strpos($t->element_value,"***br***"))
					{
						$element_value = str_replace("***br***",', ', $t->element_value);
							
						

							if(substr($element_value, -2) == ', ')
								$data_temp[$label_titles[$h]]= substr($element_value, 0, -2);
							else
								$data_temp[$label_titles[$h]]= $element_value;	
					}
						else
						if(strpos($t->element_value,"***map***"))
						{
							$data_temp[$label_titles[$h]]= 'Longitude:'.substr(str_replace("***map***",', Latitude:', $t->element_value), 0, -2);
						}
					
						else
							$data_temp[$label_titles[$h]]=' '.$t->element_value;
			}
		}
	}

	$data[]=$data_temp;
	}


  // file name for download
  $filename = $title."_" . date('Ymd') . ".csv";
 
 	header('Content-Encoding: Windows-1252');
	header('Content-type: text/csv; charset=Windows-1252');
	header("Content-Disposition: attachment; filename=\"$filename\"");

  $flag = false;
  foreach($data as $row) {
    if(!$flag) {
      # display field/column names as first row
  	//  echo "sep=,\r\n";
    echo '"'.implode('","', array_keys($row));

	  echo "\"\r\n";
      $flag = true;
    }
    array_walk($row, 'cleanData');
    echo '"'.implode('","',array_values($row))."\"\r\n";
  }


}

function generate_xml()
{

	$db		= JFactory::getDBO();
	$id 	= JRequest::getVar('id');

		$checked_ids = JRequest::getVar('checked_ids');
	
	if($checked_ids)
		$checked_ids = trim($checked_ids, ",");
	
$user = JFactory::getUser();
 
if ($user->guest)
{
	echo 'You have no permissions to download xml';
	return;
}
	
if(!(in_array(7,$user->groups) || in_array(8,$user->groups)))
{ 
	echo 'You have no permissions to download xml';
	return;
}


$form_id=$id;

	if ($checked_ids) 
		$query = "SELECT * FROM #__contactformmaker_submits where form_id=".$form_id." AND group_id IN ( ". $checked_ids ." )";
	else
		$query = "SELECT * FROM #__contactformmaker_submits where form_id=$form_id ";

	$db->setQuery( $query);

	$rows = $db->loadObjectList();

	if($db->getErrorNum()){

		echo $db->stderr();

		return false;

	}
	
	$n=count($rows);
	
	$labels= array();
	for($i=0; $i < $n ; $i++)

	{
		$row = &$rows[$i];
		if(!in_array($row->element_label, $labels))
		{
			array_push($labels, $row->element_label);
		}
	}
	$label_titles=array();
	$sorted_labels= array();
 
 $query_lable = "SELECT label_order,title FROM #__contactformmaker where id=$form_id ";

	$db->setQuery( $query_lable);

	$rows_lable = $db->loadObjectList();

	if($db->getErrorNum()){

		echo $db->stderr();

		return false;

	}
		
	
	$ptn = "/[^a-zA-Z0-9_]/";
			$rpltxt = "";
			
		
	$title=preg_replace($ptn, $rpltxt, $rows_lable[0]->title);
	
	$sorted_labels_id= array();
	$sorted_labels= array();
	$label_titles=array();
	if($labels)
	{
		
		$label_id= array();
		$label_order= array();
		$label_order_original= array();
		$label_type= array();
		
		///stexic
		$label_all	= explode('#****#',$rows_lable[0]->label_order);
		$label_all 	= array_slice($label_all,0, count($label_all)-1);   
		
		
		
		foreach($label_all as $key => $label_each) 
		{
			$label_id_each=explode('#**id**#',$label_each);
			array_push($label_id, $label_id_each[0]);
			
			$label_oder_each=explode('#**label**#', $label_id_each[1]);
			
			array_push($label_order_original, $label_oder_each[0]);
			
			$ptn = "/[^a-zA-Z0-9_]/";
			$rpltxt = "";
			$label_temp=preg_replace($ptn, $rpltxt, $label_oder_each[0]);
			array_push($label_order, $label_temp);
			
			array_push($label_type, $label_oder_each[1]);

		
			//echo $label."<br>";
			
		}
		
		foreach($label_id as $key => $label) 
			if(in_array($label, $labels))
			{
				array_push($sorted_labels, $label_order[$key]);
				array_push($sorted_labels_id, $label);
				array_push($label_titles, $label_order_original[$key]);
			}
			

	}
	
 	$m=count($sorted_labels);
	$group_id_s= array();
	$l=0;
	 
//var_dump($label_titles);
	if(count($rows)>0 and $m)
	for($i=0; $i <count($rows) ; $i++)
	{
	
		$row = &$rows[$i];
	
		if(!in_array($row->group_id, $group_id_s))
		{
		
			array_push($group_id_s, $row->group_id);
			
		}
	}
 

  
 $data=array();

 
 for($www=0;  $www < count($group_id_s); $www++)
	{	
	$i=$group_id_s[$www];
	
		$temp= array();
		for($j=0; $j < $n ; $j++)
		{
		
			$row = &$rows[$j];
			
			if($row->group_id==$i)
			{
			
				array_push($temp, $row);
			}
		}
		
		
		
		$f=$temp[0];
		$date=$f->date;
		$ip=$f->ip;
 $data_temp['Submit date']=$date;
 $data_temp['Ip']=$ip;
  
 
 $ttt=count($temp);
 
// var_dump($temp);
		for($h=0; $h < $m ; $h++)
		{		
			
			for($g=0; $g < $ttt ; $g++)
			{			
				$t = $temp[$g];
				if($t->element_label==$sorted_labels_id[$h])
				{
					if(strpos($t->element_value,"*@@url@@*"))
					{
						$new_file=str_replace("*@@url@@*",'', $t->element_value);
						$new_filename=explode('/', $new_file);
						$data_temp[$label_titles[$h]]=$new_file;
					}
					else
						if(strpos($t->element_value,"***br***"))					
						{						
							$element_value = str_replace("***br***",', ', $t->element_value);

							if(substr($element_value, -2) == ', ')
								$data_temp[$label_titles[$h]]= substr($element_value, 0, -2);
							else
								$data_temp[$label_titles[$h]]= $element_value;			
						}					
						else					
						if(strpos($t->element_value,"***map***"))		
						{								
						$data_temp[$label_titles[$h]]= 'Longitude:'.substr(str_replace("***map***",', Latitude:', $t->element_value), 0, -2);		
						}
						
                       	
						else				
						$data_temp[$label_titles[$h]]=$t->element_value;
			

				}
			}
			
			
		}


		$data[]=$data_temp;
 }

 

  // file name for download
	$filename = $title."_" . date('Ymd') . ".xml";

  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type:text/xml,  charset=utf-8");

 
  $flag = false;
echo '<?xml version="1.0" encoding="utf-8" ?> 
  <form title="'.$title.'">';
 
  foreach ($data as $key1 => $value1){
  echo  '<submition>';
	 
  foreach ($value1 as $key => $value){
  echo  '<field title="'.$key.'">';
		echo   '<![CDATA['.$value."]]>";
 echo  '</field>';
  }  
  
   echo  '</submition>';
  }
	
	  echo '';
echo ' </form>
';

}

function cleanData(&$str)
{
	$str = preg_replace("/\t/", "\\t", $str);
	$str = preg_replace("/\r?\n/", "\\n", $str);
	if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

function form_layout()
{
	$db		= JFactory::getDBO();
	$cid 	= JRequest::getVar('cid', array(0), '', 'array');
	JArrayHelper::toInteger($cid, array(0));
	$id 	= $cid[0];
	$row = JTable::getInstance('contactformmaker', 'Table');
	// load the row from the db table
	$row->load( $id);
	
	$ids = array();
	$types = array();
	$labels = array();
	
	
	$fields=explode('*:*new_field*:*',$row->form_fields);
	$fields 	= array_slice($fields,0, count($fields)-1);   
	foreach($fields as $field)
	{
		$temp=explode('*:*id*:*',$field);
		array_push($ids, $temp[0]);
		$temp=explode('*:*type*:*',$temp[1]);
		array_push($types, $temp[0]);
		$temp=explode('*:*w_field_label*:*',$temp[1]);
		array_push($labels, $temp[0]);
	}
	
	
	$fields = array('ids' => $ids, 'types' => $types, 'labels' => $labels);
	
	HTML_contact::form_layout($row, $fields);
}


function form_options(){

	$db		= JFactory::getDBO();
	$cid 	= JRequest::getVar('cid', array(0), '', 'array');
	JArrayHelper::toInteger($cid, array(0));

	$id 	= $cid[0];
	$row = JTable::getInstance('contactformmaker', 'Table');

	// load the row from the db table
	$row->load( $id);

	
	$query = "SELECT * FROM #__contactformmaker_themes ORDER BY title";
	$db->setQuery( $query);
	$themes = $db->loadObjectList();
	if($db->getErrorNum()){
		echo $db->stderr();
		return false;
	}
	


	HTML_contact::form_options($row, $themes);

}

function submit_info(){

	$group_id = JRequest::getVar('group_id');
	
	$db		= JFactory::getDBO();
	$query = "SELECT * FROM #__contactformmaker_submits WHERE group_id=".$group_id;
	$db->setQuery( $query);
	$rows = $db->loadObjectList();
	if($db->getErrorNum()){echo $db->stderr();return false;}
	
	$form = JTable::getInstance('contactformmaker', 'Table');
	$form->load( $rows[0]->form_id);
	
		$label_id= array();
		$label_order_original= array();
		$label_type= array();
		
		$label_all	= explode('#****#',$form->label_order);
		$label_all 	= array_slice($label_all,0, count($label_all)-1);   
			
		
		foreach($label_all as $key => $label_each) 
		{
			$label_id_each=explode('#**id**#',$label_each);
			array_push($label_id, $label_id_each[0]);
			
			$label_oder_each=explode('#**label**#', $label_id_each[1]);
			array_push($label_order_original, $label_oder_each[0]);
			array_push($label_type, $label_oder_each[1]);
		}
	
	HTML_contact::submit_info($rows, $label_id,$label_order_original,$label_type);
}


//////////////////////////////////////////////////////////////
function gotoedit(){
	$mainframe = JFactory::getApplication();

	$id 	= JRequest::getVar('id');

	$msg ="The form was saved successfully.";
	$link ='index.php?option=com_contactformmaker&task=edit&cid[]='.$id;

	$mainframe->redirect($link, $msg, 'message');

}

function showredirect(){
	$mainframe = JFactory::getApplication();

	$link = 'index.php?option=com_contactformmaker&task=forms';

	$mainframe->redirect($link);

}


function add_blocked_ips(){

    $db = JFactory::getDBO();

	$query = "SELECT * FROM #__contactformmaker_blocked ORDER BY id";

	$db->setQuery( $query);

	$rows = $db->loadObjectList();

	if($db->getErrorNum()){

		echo $db->stderr();

		return false;

	}

// display function


	HTML_contact::add_blocked_ips($rows);

}


function show_submits(){

	$mainframe = JFactory::getApplication();
    $db = JFactory::getDBO();
	$query = "SELECT id, title FROM #__contactformmaker order by title";
	$db->setQuery( $query);
	$forms = $db->loadObjectList();
	if($db->getErrorNum()){
		echo $db->stderr();
		return false;
	}
//	$form_id = JRequest::getVar('form_id');

	$option='com_contactformmaker';
	$task	= JRequest::getCmd('task'); 
	$form_id= $mainframe-> getUserStateFromRequest( $option.'form_id', 'form_id','id','cmd' );
	
	if($form_id){
	
	$query = "SELECT id FROM #__contactformmaker where id=".$form_id;
	$db->setQuery( $query);
	$exists = $db->LoadResult();
	
	if(!$exists)
		$form_id=0;
	}
	
	$filter_order= $mainframe-> getUserStateFromRequest( $option.'filter_order2', 'filter_order2','id','cmd' );
	$filter_order_Dir= $mainframe-> getUserStateFromRequest( $option.'filter_order_Dir2', 'filter_order_Dir2','','word' );
	$search_submits = $mainframe-> getUserStateFromRequest( $option.'search_submits', 'search_submits','','string' );
	$search_submits = JString::strtolower( $search_submits );
	
	$ip_search = $mainframe-> getUserStateFromRequest( $option.'ip_search', 'ip_search','','string' );
	$ip_search = JString::strtolower( $ip_search );
	
	$limit= $mainframe-> getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
	$limitstart= $mainframe-> getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');

	$where = array();
	$lists['startdate']= JRequest::getVar('startdate', "");
	$lists['enddate']= JRequest::getVar('enddate', "");
	$lists['hide_label_list']= JRequest::getVar('hide_label_list', "");
	
	if ( $search_submits ) {
		$where[] = 'element_label LIKE "%'.$db->escape($search_submits).'%"';
	}	
	
	if ( $ip_search ) {
		$where[] = 'ip LIKE "%'.$db->escape($ip_search).'%"';
	}	
	
	if($lists['startdate']!='')
		$where[] ="  `date`>='".$lists['startdate']." 00:00:00' ";
	if($lists['enddate']!='')
		$where[] ="  `date`<='".$lists['enddate']." 23:59:59' ";
	
	if ($form_id=='')
		if($forms)
		$form_id=$forms[0]->id;
	
	$where[] = 'form_id="'.$form_id.'"';

	$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

	$orderby 	= ' ';
	if ($filter_order == 'id' or $filter_order == 'title' or $filter_order == 'mail')
	{
		$orderby 	= ' ORDER BY `date` desc';
	} 
	else 
		if(!strpos($filter_order,"_field")) 
		{
			$orderby 	= ' ORDER BY '.$filter_order .' '. $filter_order_Dir .'';
		}	
	
	$query = "SELECT * FROM #__contactformmaker_submits". $where;
	$db->setQuery( $query);
	$rows = $db->loadObjectList();
	if($db->getErrorNum()){echo $db->stderr();return false;}
	
	$where_labels=array();
	$n=count($rows);
	$labels= array();
	for($i=0; $i < $n ; $i++)
	{
		$row = &$rows[$i];
		if(!in_array($row->element_label, $labels))
		{
			array_push($labels, $row->element_label);
		}
	}
	
	
	$sorted_labels_type= array();
	$sorted_labels_id= array();
	$sorted_labels= array();
	$label_titles=array();
	if($labels)
	{
		
		$label_id= array();
		$label_order= array();
		$label_order_original= array();
		$label_type= array();
		
		$this_form = JTable::getInstance('contactformmaker', 'Table');
		$this_form->load( $form_id);
		

		$label_all	= explode('#****#',$this_form->label_order);
		$label_all 	= array_slice($label_all,0, count($label_all)-1);   
		
		
		
		foreach($label_all as $key => $label_each) 
		{
			$label_id_each=explode('#**id**#',$label_each);
			array_push($label_id, $label_id_each[0]);
			
			$label_order_each=explode('#**label**#', $label_id_each[1]);
			
			array_push($label_order_original, $label_order_each[0]);
			
			$ptn = "/[^a-zA-Z0-9_]/";
			$rpltxt = "";
			$label_temp=preg_replace($ptn, $rpltxt, $label_order_each[0]);
			array_push($label_order, $label_temp);
			
			array_push($label_type, $label_order_each[1]);
		}
		
		foreach($label_id as $key => $label) 
			if(in_array($label, $labels))
			{
				array_push($sorted_labels_type, $label_type[$key]);
				array_push($sorted_labels, $label_order[$key]);
				array_push($sorted_labels_id, $label);
				array_push($label_titles, $label_order_original[$key]);
				$search_temp = $mainframe-> getUserStateFromRequest( $option.$form_id.'_'.$label.'_search', $form_id.'_'.$label.'_search','','string' );
				$search_temp = JString::strtolower( $search_temp );
				$lists[$form_id.'_'.$label.'_search']	 = $search_temp;
				
				if ( $search_temp ) {
					$where_labels[] = '(group_id in (SELECT group_id FROM #__contactformmaker_submits WHERE element_label="'.$label.'" AND element_value LIKE "%'.$db->escape($search_temp).'%"))';
				}	

			}
	}
	
	$where_labels 		= ( count( $where_labels ) ? ' ' . implode( ' AND ', $where_labels ) : '' );
	if($where_labels)
		$where=  $where.' AND '.$where_labels;

	$rows_ord = array();
	if(strpos($filter_order,"_field"))
	{
		
		$query = "insert into #__contactformmaker_submits (form_id,	element_label, element_value, group_id,`date`,ip) select $form_id,'".str_replace("_field","",$filter_order)."', '', group_id,`date`,ip from  #__contactformmaker_submits where `form_id`=$form_id and group_id not in (select group_id from #__contactformmaker_submits where `form_id`=$form_id and element_label='".str_replace("_field","",$filter_order)."' group by  group_id) group by group_id";
	
		$db->setQuery( $query);
		$db->query();
		if($db->getErrorNum()){	echo $db->stderr();	return false;}
		
		$query = "SELECT group_id, date, ip FROM #__contactformmaker_submits ". $where." and element_label='".str_replace("_field","",$filter_order)."' order by element_value ".$filter_order_Dir;
		//echo $query;
		$db->setQuery( $query);
		$rows_ord = $db->loadObjectList();
		if($db->getErrorNum()){	echo $db->stderr();	return false;}
	
	}

	$query = 'SELECT group_id, date, ip FROM #__contactformmaker_submits'. $where.' group by group_id'. $orderby;
	$db->setQuery( $query );
	$group_ids=$db->loadObjectList();
	$total = count($group_ids);
	
	
	$query = 'SELECT count(distinct group_id) FROM #__contactformmaker_submits where form_id ="'.$form_id.'"';
	$db->setQuery( $query );
	$total_entries=$db->LoadResult();
	
	if(count($rows_ord)!=0){
	$group_ids=$rows_ord;
	$total = count($rows_ord);
	
	$query = "SELECT group_id, date, ip FROM #__contactformmaker_submits ". $where." and element_label='".str_replace("_field","",$filter_order)."' order by element_value ".$filter_order_Dir." limit $limitstart, $limit ";
	$db->setQuery( $query);
	$rows_ord = $db->loadObjectList();

	if($db->getErrorNum()){

		echo $db->stderr();

		return false;

	}
	
	
	
	
	
	}
	jimport('joomla.html.pagination');
	$pageNav = new JPagination( $total, $limitstart, $limit );	
	
	
	
	$where2 = array();
	
	for($i=$pageNav->limitstart; $i<$pageNav->limitstart+$pageNav->limit; $i++)
	{
		if($i<$total)
$where2 [] ="group_id='".$group_ids[$i]->group_id."'";
 
	}
	$where2 = ( count( $where2 ) ? ' AND ( ' . implode( ' OR ', $where2 ).' )' : '' );
	$where3=$where;
	$where=$where.$where2;
	$query = "SELECT * FROM #__contactformmaker_submits ". $where.$orderby.'';
	$db->setQuery( $query);
	$rows = $db->loadObjectList();
	if($db->getErrorNum()){

		echo $db->stderr();

		return false;

	
	}
	
	$query = 'SELECT views FROM #__contactformmaker_views WHERE form_id="'.$form_id.'"'	;
	$db->setQuery( $query );
	$total_views = $db->loadResult();	
	

	$lists['order_Dir']	= $filter_order_Dir;

	$lists['order']		= $filter_order;	



	// search filter	

$lists['search_submits']= $search_submits;	
$lists['ip_search']=$ip_search;

	if(count($rows_ord)==0)
		$rows_ord=$rows;
    // display function
	

	HTML_contact::show_submits($rows, $forms, $lists, $pageNav, $sorted_labels, $label_titles, $rows_ord, $filter_order_Dir,$form_id, $sorted_labels_id, $sorted_labels_type, $total_entries, $total_views,$where, $where3);

}

function show(){



	$mainframe = JFactory::getApplication();
	
	
    $db = JFactory::getDBO();
	$option='com_contactformmaker';
	$filter_order= $mainframe-> getUserStateFromRequest( $option.'filter_order', 'filter_order','id','cmd' );
	

	$filter_order_Dir= $mainframe-> getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir','','word' );
	$filter_state = $mainframe->getUserStateFromRequest( $option.'filter_state', 'filter_state', '','word' );
	$search = $mainframe-> getUserStateFromRequest( $option.'search', 'search','','string' );
	
	
	$search = JString::strtolower( $search );
	$limit= $mainframe-> getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
	$limitstart= $mainframe-> getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');

	$where = array();
	if ( $search ) {

		$where[] = 'title LIKE "%'.$db->escape($search).'%"';

	}	


	$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

	if ($filter_order == 'id' or $filter_order == 'group_id' or $filter_order == 'date' or $filter_order == 'ip'){

		$orderby 	= ' ORDER BY id';

	} else {

		$orderby 	= ' ORDER BY '. 

         $filter_order .' '. $filter_order_Dir .', id';

	}	

	

	// get the total number of records

	$query = 'SELECT COUNT(*)'

	. ' FROM #__contactformmaker'

	. $where

	;
	$db->setQuery( $query );

	$total = $db->loadResult();



	jimport('joomla.html.pagination');

	$pageNav = new JPagination( $total, $limitstart, $limit );	

	$query = "SELECT * FROM #__contactformmaker". $where. $orderby;

	$db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );

	$rows = $db->loadObjectList();

	if($db->getErrorNum()){

		echo $db->stderr();

		return false;

	}



	// table ordering

	$lists['order_Dir']	= $filter_order_Dir;

	$lists['order']		= $filter_order;	



	// search filter	

        $lists['search']= $search;	

	

    // display function

	HTML_contact::show($rows, $pageNav, $lists);

}

function show_blocked_ips(){


	$mainframe = JFactory::getApplication();

	

    $db = JFactory::getDBO();



	$option='com_contactformmaker';



	$filter_order= $mainframe-> getUserStateFromRequest( $option.'filter_order_ips', 'filter_order_ips','id','cmd' );

	$filter_order_Dir= $mainframe-> getUserStateFromRequest( $option.'filter_order_Dir_ips', 'filter_order_Dir_ips','desc','word' );

	$filter_state = $mainframe->getUserStateFromRequest( $option.'filter_state', 'filter_state', '','word' );

	$search_ip = $mainframe-> getUserStateFromRequest( $option.'search_ip', 'search_ip','','string' );

	$search_ip = JString::strtolower( $search_ip );

	$limit= $mainframe-> getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');

	$limitstart= $mainframe-> getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');

	$where = array();


	if ( $search_ip ) {

		$where[] = 'ip LIKE "%'.$db->escape($search_ip).'%"';

	}	


	$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

	if ($filter_order == 'id'){

		$orderby 	= ' ORDER BY id';

	} else {

		$orderby 	= ' ORDER BY '. $filter_order .' '. $filter_order_Dir .', id';

	}	

	// get the total number of records


	$query = 'SELECT COUNT(*) FROM #__contactformmaker_blocked'. $where;

	$db->setQuery( $query );

	$total = $db->loadResult();


	jimport('joomla.html.pagination');
	$pageNav = new JPagination( $total, $limitstart, $limit );	


	$query = "SELECT * FROM #__contactformmaker_blocked". $where. $orderby;


	$db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );


	$rows = $db->loadObjectList();


	if($db->getErrorNum()){


		echo $db->stderr();


		return false;

	}

	// table ordering

	$lists['order_Dir']	= $filter_order_Dir;

	$lists['order']		= $filter_order;	

	// search filter	

        $lists['search_ip']= $search_ip;	


    // display function

	HTML_contact::show_blocked_ips($rows, $pageNav, $lists);

}



function show_themes(){

	$mainframe = JFactory::getApplication();
	$option='com_contactformmaker';
	
    $db = JFactory::getDBO();
	
	$filter_order= $mainframe-> getUserStateFromRequest( $option.'filter_order_themes', 'filter_order_themes','id','cmd' );
	$filter_order_Dir= $mainframe-> getUserStateFromRequest( $option.'filter_order_Dir_themes', 'filter_order_Dir_themes','desc','word' );
	$filter_state = $mainframe-> getUserStateFromRequest( $option.'filter_state', 'filter_state', '','word' );
	$search_theme = $mainframe-> getUserStateFromRequest( $option.'search_theme', 'search_theme','','string' );
	$search_theme = JString::strtolower( $search_theme );
	$limit= $mainframe-> getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
	$limitstart= $mainframe-> getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');
	$where = array();

	if ( $search_theme ) {
		$where[] = '#__contactformmaker_themes.title LIKE "%'.$db->escape($search_theme).'%"';
	}	
	
	$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
	if ($filter_order == 'id'){
		$orderby 	= ' ORDER BY id '.$filter_order_Dir;
	} else {
		$orderby 	= ' ORDER BY '. 
         $filter_order .' '. $filter_order_Dir .', id';
	}	
	
	// get the total number of records
	$query = 'SELECT COUNT(*)'. ' FROM #__contactformmaker_themes'. $where;
	$db->setQuery( $query );
	$total = $db->loadResult();

	jimport('joomla.html.pagination');
	$pageNav = new JPagination( $total, $limitstart, $limit );	
	
	$query = "SELECT * FROM #__contactformmaker_themes". $where. $orderby;
	$db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
	$rows = $db->loadObjectList();
	if($db->getErrorNum()){		echo $db->stderr();		return false;	}

	// table ordering

	$lists['order_Dir']	= $filter_order_Dir;
	$lists['order']		= $filter_order;	

	// search filter	
        $lists['search_theme']= $search_theme;	

    // display function

	HTML_contact::show_themes($rows, $pageNav, $lists);

}


function edit_css(){

$mainframe = JFactory::getApplication();

$getparams=JRequest::get('get');
	

    $db = JFactory::getDBO();



	$query = "SELECT * FROM #__contactformmaker_themes WHERE id=".$getparams['theme'];

	$db->setQuery($query);

	$theme = $db->loadObject();
	
	
	$query = "SELECT * FROM #__contactformmaker WHERE id=".$getparams['form_id'];

	$db->setQuery($query);

	$form = $db->loadObject();	


	HTML_contact::editCss($theme,$form);


}


function setdefault()
{
	$mainframe = JFactory::getApplication();
	$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
	JArrayHelper::toInteger($cid);
	
	if (isset($cid[0]) && $cid[0]) 
		$id = $cid[0];
	else 
	{
		$mainframe->redirect(  'index.php?option=com_contactformmaker&task=themes',JText::_('No Items Selected'), 'message' );
		return false;
	}
	
	$db = JFactory::getDBO();

	// Clear home field for all other items
	$query = 'UPDATE #__contactformmaker_themes SET `default` = 0 WHERE 1';
	$db->setQuery( $query );
	if ( !$db->query() ) {
		$msg =$db->getErrorMsg();
		echo $msg;
		return false;
	}

	// Set the given item to home
	$query = 'UPDATE #__contactformmaker_themes SET `default` = 1 WHERE id = '.(int) $id;
	$db->setQuery( $query );
	if ( !$db->query() ) {
		$msg = $db->getErrorMsg();
		return false;
	}
		
	$msg = JText::_( 'Default Theme Seted' );
	$mainframe->redirect( 'index.php?option=com_contactformmaker&task=themes' ,$msg, 'message');
}

function add_themes(){

	//$lists['published'] = JHTML::_('select.booleanlist', 'published' , 'class="inputbox"', 1);
		
	$db		= JFactory::getDBO();
	$query = "SELECT * FROM #__contactformmaker_themes where `default`=1";
	$db->setQuery($query);
	$def_theme = $db->loadObject();
// display function
		
	HTML_contact::add_themes($def_theme);
}

function edit_themes(){
	$db		= JFactory::getDBO();
	$cid 	= JRequest::getVar('cid', array(0), '', 'array');
	JArrayHelper::toInteger($cid, array(0));

	$id 	= $cid[0];
	$row = JTable::getInstance('contactformmaker_themes', 'Table');
	// load the row from the db table
	$row->load( $id);
	
	// display function 
	HTML_contact::edit_themes( $row);
}

function edit_blocked_ips(){

	$db		= JFactory::getDBO();

	$cid 	= JRequest::getVar('cid', array(0), '', 'array');

	JArrayHelper::toInteger($cid, array(0));



	$id 	= $cid[0];

	$row = JTable::getInstance('contactformmaker_blocked', 'Table');

	// load the row from the db table

	$row->load( $id);

	

	// display function 

	HTML_contact::edit_blocked_ips($row);

}



function remove_themes(){
	$mainframe = JFactory::getApplication();
  // Initialize variables	
  $db = JFactory::getDBO();
  // Define cid array variable
  $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
  // Make sure cid array variable content integer format
  JArrayHelper::toInteger($cid);
  $query = 'SELECT id FROM #__contactformmaker_themes WHERE `default`=1 LIMIT 1';
  $db->setQuery( $query );
  $def = $db->loadResult();
  if($db->getErrorNum()){
	  echo $db->stderr();
	  return false;
  }
  $msg='';
  $k=array_search($def, $cid);
  if ($k>0)
  {
	  $cid[$k]=0;
	  $msg="You can't delete default theme";
  }
  
  if ($cid[0]==$def)
  {
	  $cid[0]=0;
	  $msg="You can't delete default theme";
  }
  
  // If any item selected
  if (count( $cid )) {
    // Prepare sql statement, if cid array more than one, 
    // will be "cid1, cid2, ..."
    $cids = implode( ',', $cid );
    // Create sql statement

    $query = 'DELETE FROM #__contactformmaker_themes'.' WHERE id IN ( '. $cids .' )';
    // Execute query
    $db->setQuery( $query );
    if (!$db->query()) {
      echo "<script> alert('".$db->getErrorMsg(true)."'); 
      window.history.go(-1); </script>\n";
    }
	
  }
  // After all, redirect again to frontpage
  if($msg)
  $mainframe->redirect( "index.php?option=com_contactformmaker&task=themes",  $msg, 'message');
  else
  $mainframe->redirect( "index.php?option=com_contactformmaker&task=themes");
}




function save_as_copy(){

	$mainframe = JFactory::getApplication();
	$cid 	= JRequest::getVar('cid', array(0), '', 'array');
	JArrayHelper::toInteger($cid, array(0));
	$id 	= $cid[0];
	$row = JTable::getInstance('contactformmaker', 'Table');
	// load the row from the db table
	$row->load( $id);
	if(!$row->bind(JRequest::get('post')))
	{
		JError::raiseError(500, $row->getError() );
	}
	
	
	$row->id='';
	$new=true;
	
	$row->form_fields = JRequest::getVar( 'form_fields', '','post', 'string', JREQUEST_ALLOWRAW );
	$row->form_front = JRequest::getVar( 'form_front', '','post', 'string', JREQUEST_ALLOWRAW );
	
	$fid = JRequest::getVar( 'id',0 );
	if(!$row->store()){
		JError::raiseError(500, $row->getError() );
	}
	
	if($new)
	{
		$db = JFactory::getDBO();
		$db->setQuery("INSERT INTO #__contactformmaker_views (form_id, views) VALUES('".$row->id."', 0)" ); 
		$db->query();
		if ($db->getErrorNum())
		{
			echo $db->stderr();
			return false;
		}
	}
		$msg = 'The form has been saved successfully.';
		$link = 'index.php?option=com_contactformmaker';
		$mainframe->redirect($link, $msg, 'message');
		
		
}

function save($task){

    $old = false;
	$mainframe = JFactory::getApplication();
	$row = JTable::getInstance('contactformmaker', 'Table');

	if(!$row->bind(JRequest::get('post')))
	{
		JError::raiseError(500, $row->getError() );
	}
	$new=(!isset($row->id));

	$fid=$row->id;

	$row->form_fields = JRequest::getVar( 'form_fields', '','post', 'string', JREQUEST_ALLOWRAW );

	$row->form_front = JRequest::getVar( 'form_front', '','post', 'string', JREQUEST_ALLOWRAW );

	if(!$row->store()){
		JError::raiseError(500, $row->getError() );
	}
	
	if($new)
	{
		$db = JFactory::getDBO();
		$db->setQuery("INSERT INTO #__contactformmaker_views (form_id, views) VALUES('".$row->id."', 0)" ); 
		$db->query();
		if ($db->getErrorNum())
		{
			echo $db->stderr();
			return false;
		}
	}
	
	
	switch ($task)
	{
		case 'apply':
		HTML_contact::forchrome($row->id);
		break;

		
		case 'save':
		$msg = 'The form has been saved successfully.';
		$link = 'index.php?option=com_contactformmaker';
		$mainframe->redirect($link, $msg, 'message');
		break;
		
		case 'return_id':
			return $row->id;
		break;
		default:
		break;
	}
}

function save_blocked_ips($task){



	$mainframe = JFactory::getApplication();

	$row = JTable::getInstance('contactformmaker_blocked', 'Table');

	if(!$row->bind(JRequest::get('post')))

	{

		JError::raiseError(500, $row->getError() );

	}



	if(!$row->store()){

		JError::raiseError(500, $row->getError() );

	}

	switch ($task)

	{

		case 'apply_blocked_ips':

		$msg ='IP has been saved successfully.';

		$link ='index.php?option=com_contactformmaker&task=edit_blocked_ips&cid[]='.$row->id;

		break;

		default:

		$msg = 'IP has been saved successfully.';

		$link ='index.php?option=com_contactformmaker&task=blocked_ips';

		break;
	}


	$mainframe->redirect($link, $msg, 'message');

}



function save_themes($task){

	$mainframe = JFactory::getApplication();
	$row = JTable::getInstance('contactformmaker_themes', 'Table');
	if(!$row->bind(JRequest::get('post')))
	{
		JError::raiseError(500, $row->getError() );
	}

	if(!$row->store()){
		JError::raiseError(500, $row->getError() );
	}

	switch ($task)

	{
		case 'apply_themes':
		$msg ='Theme has been saved successfully.';
		$link ='index.php?option=com_contactformmaker&task=edit_themes&cid[]='.$row->id;
		break;

		default:
		$msg = 'Theme has been saved successfully.';
		$link ='index.php?option=com_contactformmaker&task=themes';
		break;
	}
	
	$mainframe->redirect($link, $msg, 'message');

}

function save_new_theme($task){



	$mainframe = JFactory::getApplication();

	$id 	= JRequest::getVar('form_id');

	$form = JTable::getInstance('contactformmaker', 'Table');
	
	$form->load($id);
	
	
	
	$row = JTable::getInstance('contactformmaker_themes', 'Table');

	if(!$row->bind(JRequest::get('post')))

	{
		JError::raiseError(500, $row->getError() );

	}

	if($task=='save_new_theme')
	$row->title = ($row->title).' '.($form->title);

	if(!$row->store()){

		JError::raiseError(500, $row->getError() );

	}

	
	
	$form->theme = $row->id;
	
	if(!$form->store()){

		JError::raiseError(500, $row->getError() );

	}
		
	
		switch ($task)
		{
			case 'save_new_theme':
			$msg = '';
			$link ='index.php?option=com_contactformmaker&task=edit_css&tmpl=component&theme='.$row->id.' &form_id='.$id.'&new=1';
			break;
		
			case 'save_for_edit':
			$msg = '';
			$link ='index.php?option=com_contactformmaker&task=edit_css&tmpl=component&theme='.$row->id.' &form_id='.$id.'&new=1';
			break;
			
			case 'apply_for_edit':
			$msg = '';
			$link ='index.php?option=com_contactformmaker&task=edit_css&tmpl=component&theme='.$row->id.' &form_id='.$id.'&new=0';
			break;
		}
	$mainframe->redirect($link, $msg);


}



function save_form_options($task){

	$mainframe = JFactory::getApplication();
	$row = JTable::getInstance('contactformmaker', 'Table');
	if(!$row->bind(JRequest::get('post')))
	{
		JError::raiseError(500, $row->getError() );
	}
	
	if($row->mail_from=="other")
		$row->mail_from=JRequest::getVar( 'mail_from_other');
	if($row->reply_to=="other")
		$row->reply_to=JRequest::getVar( 'reply_to_other');

	$row->script_mail = JRequest::getVar( 'script_mail', '','post', 'string', JREQUEST_ALLOWRAW );
	$row->submit_text = JRequest::getVar( 'submit_text', '','post', 'string', JREQUEST_ALLOWRAW );
	$row->script_mail_user = JRequest::getVar( 'script_mail_user', '','post', 'string', JREQUEST_ALLOWRAW );

	$row->send_to="";
	for($i=0; $i<20; $i++)
	{
		$send_to=JRequest::getVar( 'send_to'.$i);
		if(isset($send_to))
		{
			$row->send_to.='*'.$send_to.'*';
		}
	}

	
	if(!$row->store()){
		JError::raiseError(500, $row->getError() );
	}

	switch ($task)

	{
		case 'apply_form_options':
		$msg ='Form options have been saved successfully.';
		$link ='index.php?option=com_contactformmaker&task=form_options&cid[]='.$row->id;
		break;
		case 'save_form_options':
		default:
		$msg = 'Form options have been saved successfully.';
		$link ='index.php?option=com_contactformmaker&task=edit&cid[]='.$row->id;
		break;
	}
	
	$mainframe->redirect($link, $msg, 'message');

}

function save_form_layout($task){
	$mainframe = JFactory::getApplication();
	$row = JTable::getInstance('contactformmaker', 'Table');

	if(!$row->bind(JRequest::get('post')))
	{
		JError::raiseError(500, $row->getError() );
	}
	$row->custom_front = JRequest::getVar( 'custom_front', '','post', 'string', JREQUEST_ALLOWRAW );
	$autogen_layout=JRequest::getVar( 'autogen_layout');
	if(!isset($autogen_layout))
		$row->autogen_layout = 0;
		
	if(!$row->store()){
		JError::raiseError(500, $row->getError() );
	}
	switch ($task)
	{
		case 'apply_form_layout':
		$msg ='Form layout have been saved successfully.';
		$link ='index.php?option=com_contactformmaker&task=form_layout&cid[]='.$row->id;
		break;
		case 'save_form_layout':
		default:
		$msg = 'Form layout have been saved successfully.';
		$link ='index.php?option=com_contactformmaker&task=edit&cid[]='.$row->id;
		break;
	}

	$mainframe->redirect($link, $msg, 'message');
}

function edit(){
	$old = false;
	$db		= JFactory::getDBO();
	$cid 	= JRequest::getVar('cid', array(0), '', 'array');
	JArrayHelper::toInteger($cid, array(0));
	$id 	= $cid[0];
	$row = JTable::getInstance('contactformmaker', 'Table');
	// load the row from the db table
	$row->load( $id);
		
		$labels2= array();
		
		$label_id= array();
		$label_order_original= array();
		$label_type= array();
		
		$label_all	= explode('#****#',$row->label_order);
		$label_all 	= array_slice($label_all,0, count($label_all)-1);   

		foreach($label_all as $key => $label_each) 
		{
			$label_id_each=explode('#**id**#',$label_each);
			array_push($label_id, $label_id_each[0]);
			
			$label_oder_each=explode('#**label**#', $label_id_each[1]);
			array_push($label_order_original, addslashes($label_oder_each[0]));
			array_push($label_type, $label_oder_each[1]);
		
			
		}
		
	$labels2['id']='"'.implode('","',$label_id).'"';
	$labels2['label']='"'.implode('","',$label_order_original).'"';
	$labels2['type']='"'.implode('","',$label_type).'"';
	

	$ids = array();
	$types = array();
	$labels = array();
	$paramss = array();
	$fields=explode('*:*new_field*:*',$row->form_fields);
	$fields 	= array_slice($fields,0, count($fields)-1);   
	foreach($fields as $field)
	{
		$temp=explode('*:*id*:*',$field);
		array_push($ids, $temp[0]);
		$temp=explode('*:*type*:*',$temp[1]);
		array_push($types, $temp[0]);
		$temp=explode('*:*w_field_label*:*',$temp[1]);
		array_push($labels, $temp[0]);
		array_push($paramss, $temp[1]);
	}
	
	$form=$row->form_front;
	foreach($ids as $ids_key => $id)
	{	
		$label=$labels[$ids_key];
		$type=$types[$ids_key];
		$params=$paramss[$ids_key];
		if( strpos($form, '%'.$id.' - '.$label.'%'))
		{
			$rep='';
			$param=array();
			$param['attributes'] = '';
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
					$rep ='<div id="wdform_field'.$id.'" type="type_section_break" class="wdform_field_section_break"><span id="'.$id.'_element_labelform_id_temp" style="display: none;">custom_'.$id.'</span><div id="'.$id.'_element_sectionform_id_temp" align="left" class="wdform_section_break">'.$param['w_editor'].'</div></div>';
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
					
					$rep ='<div id="wdform_field'.$id.'" type="type_editor" class="wdform_field" style="display: table-cell;">'.$param['w_editor'].'</div><span id="'.$id.'_element_labelform_id_temp" style="display: none;">custom_'.$id.'</span>';
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
							$param['attributes'] = $param['attributes'].' add_'.$attr;
					}

					$param['w_field_label_pos'] = ($param['w_field_label_pos']=="left" ? "table-cell" : "block");	
					$input_active = ($param['w_first_val']=='true' ? "checked='checked'" : "");	
					$required_sym = ($param['w_required']=="yes" ? " *" : "");	
				
					$rep ='<div id="wdform_field'.$id.'" type="type_send_copy" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;"><span id="'.$id.'_element_labelform_id_temp" class="wd_form_label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" style="display: '.$param['w_field_label_pos'].'"><input type="hidden" value="type_send_copy" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="checkbox" id="'.$id.'_elementform_id_temp" name="'.$id.'_elementform_id_temp" onclick="set_checked(&quot;'.$id.'&quot;,&quot;&quot;,&quot;form_id_temp&quot;)" '.$input_active.' '.$param['attributes'].'></div></div>';
				
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
							$param['attributes'] = $param['attributes'].' add_'.$attr;
					}

					$param['w_field_label_pos'] = ($param['w_field_label_pos']=="left" ? "table-cell" : "block");	
					$input_active = ($param['w_first_val']==$param['w_title'] ? "input_deactive" : "input_active");	
					$required_sym = ($param['w_required']=="yes" ? " *" : "");	
				
					$rep ='<div id="wdform_field'.$id.'" type="type_text" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;"><span id="'.$id.'_element_labelform_id_temp" class="wd_form_label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" style="display: '.$param['w_field_label_pos'].'; vertical-align:top;"><input type="hidden" value="type_text" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="hidden" value="'.$param['w_unique'].'" name="'.$id.'_uniqueform_id_temp" id="'.$id.'_uniqueform_id_temp"><input type="text" class="'.$input_active.'" id="'.$id.'_elementform_id_temp" name="'.$id.'_elementform_id_temp" value="'.$param['w_first_val'].'" title="'.$param['w_title'].'" onfocus="delete_value(&quot;'.$id.'_elementform_id_temp&quot;)" onblur="return_value(&quot;'.$id.'_elementform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_elementform_id_temp&quot;)" style="width: '.$param['w_size'].'px;" '.$param['attributes'].'></div></div>';
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
							$param['attributes'] = $param['attributes'].' add_'.$attr;
					}

					$param['w_field_label_pos'] = ($param['w_field_label_pos']=="left" ? "table-cell" : "block");	
					$input_active = ($param['w_first_val']==$param['w_title'] ? "input_deactive" : "input_active");	
					$required_sym = ($param['w_required']=="yes" ? " *" : "");	
									
					$rep ='<div id="wdform_field'.$id.'" type="type_number" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp"  class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;"><span id="'.$id.'_element_labelform_id_temp" class="wd_form_label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'"><input type="hidden" value="type_number" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="hidden" value="'.$param['w_unique'].'" name="'.$id.'_uniqueform_id_temp" id="'.$id.'_uniqueform_id_temp"><input type="text" class="'.$input_active.'" id="'.$id.'_elementform_id_temp" name="'.$id.'_elementform_id_temp" value="'.$param['w_first_val'].'" title="'.$param['w_title'].'" onkeypress="return check_isnum(event)" onfocus="delete_value(&quot;'.$id.'_elementform_id_temp&quot;)" onblur="return_value(&quot;'.$id.'_elementform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_elementform_id_temp&quot;)" style="width: '.$param['w_size'].'px;" '.$param['attributes'].'></div></div>';
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
							$param['attributes'] = $param['attributes'].' add_'.$attr;
					}
					$param['w_field_label_pos'] = ($param['w_field_label_pos']=="left" ? "table-cell" : "block");	
					$required_sym = ($param['w_required']=="yes" ? " *" : "");	
					$rep ='<div id="wdform_field'.$id.'" type="type_password" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp"  class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;"><span id="'.$id.'_element_labelform_id_temp" class="wd_form_label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; vertical-align:top;"><input type="hidden" value="type_password" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="hidden" value="'.$param['w_unique'].'" name="'.$id.'_uniqueform_id_temp" id="'.$id.'_uniqueform_id_temp"><input type="password" id="'.$id.'_elementform_id_temp" name="'.$id.'_elementform_id_temp" style="width: '.$param['w_size'].'px;" '.$param['attributes'].'></div></div>';
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
							$param['attributes'] = $param['attributes'].' add_'.$attr;
					}
					
					$param['w_field_label_pos'] = ($param['w_field_label_pos']=="left" ? "table-cell" : "block");	
					$input_active = ($param['w_first_val']==$param['w_title'] ? "input_deactive" : "input_active");	
					$required_sym = ($param['w_required']=="yes" ? " *" : "");	
				
					$rep ='<div id="wdform_field'.$id.'" type="type_textarea" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display:'.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;"><span id="'.$id.'_element_labelform_id_temp" class="wd_form_label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: table-cell;"><input type="hidden" value="type_textarea" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="hidden" value="'.$param['w_unique'].'" name="'.$id.'_uniqueform_id_temp" id="'.$id.'_uniqueform_id_temp"><textarea class="'.$input_active.'" id="'.$id.'_elementform_id_temp" name="'.$id.'_elementform_id_temp" title="'.$param['w_title'].'"  onfocus="delete_value(&quot;'.$id.'_elementform_id_temp&quot;)" onblur="return_value(&quot;'.$id.'_elementform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_elementform_id_temp&quot;)" style="width: '.$param['w_size_w'].'px; height: '.$param['w_size_h'].'px;" '.$param['attributes'].'>'.$param['w_first_val'].'</textarea></div></div>';
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
							$param['attributes'] = $param['attributes'].' add_'.$attr;
					}
					
					$w_first_val = explode('***',$param['w_first_val']);
					$w_title = explode('***',$param['w_title']);
					$w_mini_labels = explode('***',$param['w_mini_labels']);
					
					$param['w_field_label_pos'] = ($param['w_field_label_pos']=="left" ? "table-cell" : "block");	
					$input_active = ($param['w_first_val']==$param['w_title'] ? "input_deactive" : "input_active");	
					$required_sym = ($param['w_required']=="yes" ? " *" : "");	
				
					$rep ='<div id="wdform_field'.$id.'" type="type_phone" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;"><span id="'.$id.'_element_labelform_id_temp" class="wd_form_label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; vertical-align:top;"><input type="hidden" value="type_phone" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="hidden" value="'.$param['w_unique'].'" name="'.$id.'_uniqueform_id_temp" id="'.$id.'_uniqueform_id_temp"><div id="'.$id.'_table_name" style="display: table;"><div id="'.$id.'_tr_name1" style="display: table-row;"><div id="'.$id.'_td_name_input_first" style="display: table-cell;"><input type="text" class="'.$input_active.'" id="'.$id.'_element_firstform_id_temp" name="'.$id.'_element_firstform_id_temp" value="'.$w_first_val[0].'" title="'.$w_title[0].'" onfocus="delete_value(&quot;'.$id.'_element_firstform_id_temp&quot;)"onblur="return_value(&quot;'.$id.'_element_firstform_id_temp&quot;)"onchange="change_value(&quot;'.$id.'_element_firstform_id_temp&quot;)" onkeypress="return check_isnum(event)"style="width: 50px;" '.$param['attributes'].'><span class="wdform_line" style="margin: 0px 4px; padding: 0px;">-</span></div><div id="'.$id.'_td_name_input_last" style="display: table-cell;"><input type="text" class="'.$input_active.'" id="'.$id.'_element_lastform_id_temp" name="'.$id.'_element_lastform_id_temp" value="'.$w_first_val[1].'" title="'.$w_title[1].'" onfocus="delete_value(&quot;'.$id.'_element_lastform_id_temp&quot;)"onblur="return_value(&quot;'.$id.'_element_lastform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_element_lastform_id_temp&quot;)" onkeypress="return check_isnum(event)"style="width: '.$param['w_size'].'px;" '.$param['attributes'].'></div></div><div id="'.$id.'_tr_name2" style="display: table-row;"><div id="'.$id.'_td_name_label_first" align="left" style="display: table-cell;"><label class="mini_label" id="'.$id.'_mini_label_area_code">'.$w_mini_labels[0].'</label></div><div id="'.$id.'_td_name_label_last" align="left" style="display: table-cell;"><label class="mini_label" id="'.$id.'_mini_label_phone_number">'.$w_mini_labels[1].'</label></div></div></div></div></div>';
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
							$param['attributes'] = $param['attributes'].' add_'.$attr;
					}
					
					$param['w_field_label_pos'] = ($param['w_field_label_pos']=="left" ? "table-cell" : "block");	
					$required_sym = ($param['w_required']=="yes" ? " *" : "");	
				
					$w_first_val = explode('***',$param['w_first_val']);
					$w_title = explode('***',$param['w_title']);
					$w_mini_labels = explode('***',$param['w_mini_labels']);
					

					if($param['w_name_format']=='normal')
					{
						$w_name_format = '<div id="'.$id.'_td_name_input_first" style="display: table-cell;"><input type="text" class="'.($w_first_val[0]==$w_title[0] ? "input_deactive" : "input_active").'" id="'.$id.'_element_firstform_id_temp" name="'.$id.'_element_firstform_id_temp" value="'.$w_first_val[0].'" title="'.$w_title[0].'" onfocus="delete_value(&quot;'.$id.'_element_firstform_id_temp&quot;)"onblur="return_value(&quot;'.$id.'_element_firstform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_element_firstform_id_temp&quot;)" style="margin-right: 10px; width: '.$param['w_size'].'px;"'.$param['attributes'].'></div><div id="'.$id.'_td_name_input_last" style="display: table-cell;"><input type="text" class="'.($w_first_val[1]==$w_title[1] ? "input_deactive" : "input_active").'" id="'.$id.'_element_lastform_id_temp" name="'.$id.'_element_lastform_id_temp" value="'.$w_first_val[1].'" title="'.$w_title[1].'" onfocus="delete_value(&quot;'.$id.'_element_lastform_id_temp&quot;)"onblur="return_value(&quot;'.$id.'_element_lastform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_element_lastform_id_temp&quot;)" style="margin-right: 10px; width: '.$param['w_size'].'px;" '.$param['attributes'].'></div>';
						$w_name_format_mini_labels = '<div id="'.$id.'_tr_name2" style="display: table-row;"><div id="'.$id.'_td_name_label_first" align="left" style="display: table-cell;"><label class="mini_label" id="'.$id.'_mini_label_first">'.$w_mini_labels[1].'</label></div><div id="'.$id.'_td_name_label_last" align="left" style="display: table-cell;"><label class="mini_label" id="'.$id.'_mini_label_last">'.$w_mini_labels[2].'</label></div></div>';
					}
					else
					{
						$w_name_format = '<div id="'.$id.'_td_name_input_title" style="display: table-cell;"><input type="text" class="'.($w_first_val[0]==$w_title[0] ? "input_deactive" : "input_active").'" id="'.$id.'_element_titleform_id_temp" name="'.$id.'_element_titleform_id_temp" value="'.$w_first_val[0].'" title="'.$w_title[0].'" onfocus="delete_value(&quot;'.$id.'_element_titleform_id_temp&quot;)" onblur="return_value(&quot;'.$id.'_element_titleform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_element_titleform_id_temp&quot;)" style="margin: 0px 10px 0px 0px; width: 40px;"></div><div id="'.$id.'_td_name_input_first" style="display: table-cell;"><input type="text" class="'.($w_first_val[1]==$w_title[1] ? "input_deactive" : "input_active").'" id="'.$id.'_element_firstform_id_temp" name="'.$id.'_element_firstform_id_temp" value="'.$w_first_val[1].'" title="'.$w_title[1].'" onfocus="delete_value(&quot;'.$id.'_element_firstform_id_temp&quot;)" onblur="return_value(&quot;'.$id.'_element_firstform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_element_firstform_id_temp&quot;)" style="margin-right: 10px; width: '.$param['w_size'].'px;"></div><div id="'.$id.'_td_name_input_last" style="display: table-cell;"><input type="text" class="'.($w_first_val[2]==$w_title[2] ? "input_deactive" : "input_active").'" id="'.$id.'_element_lastform_id_temp" name="'.$id.'_element_lastform_id_temp" value="'.$w_first_val[2].'" title="'.$w_title[2].'" onfocus="delete_value(&quot;'.$id.'_element_lastform_id_temp&quot;)" onblur="return_value(&quot;'.$id.'_element_lastform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_element_lastform_id_temp&quot;)" style="margin-right: 10px; width: '.$param['w_size'].'px;"></div><div id="'.$id.'_td_name_input_middle" style="display: table-cell;"><input type="text" class="'.($w_first_val[3]==$w_title[3] ? "input_deactive" : "input_active").'" id="'.$id.'_element_middleform_id_temp" name="'.$id.'_element_middleform_id_temp" value="'.$w_first_val[3].'" title="'.$w_title[3].'" onfocus="delete_value(&quot;'.$id.'_element_middleform_id_temp&quot;)" onblur="return_value(&quot;'.$id.'_element_middleform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_element_middleform_id_temp&quot;)" style="width: '.$param['w_size'].'px;"></div>';
						$w_name_format_mini_labels ='<div id="'.$id.'_tr_name2" style="display: table-row;"><div id="'.$id.'_td_name_label_title" align="left" style="display: table-cell;"><label class="mini_label" id="'.$id.'_mini_label_title">'.$w_mini_labels[0].'</label></div><div id="'.$id.'_td_name_label_first" align="left" style="display: table-cell;"><label class="mini_label" id="'.$id.'_mini_label_first">'.$w_mini_labels[1].'</label></div><div id="'.$id.'_td_name_label_last" align="left" style="display: table-cell;"><label class="mini_label" id="'.$id.'_mini_label_last">'.$w_mini_labels[2].'</label></div><div id="'.$id.'_td_name_label_middle" align="left" style="display: table-cell;"><label class="mini_label" id="'.$id.'_mini_label_middle">'.$w_mini_labels[3].'</label></div></div>';
					}
		
					$rep ='<div id="wdform_field'.$id.'" type="type_name" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;"><span id="'.$id.'_element_labelform_id_temp" class="wd_form_label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; vertical-align:top;"><input type="hidden" value="type_name" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="hidden" value="'.$param['w_unique'].'" name="'.$id.'_uniqueform_id_temp" id="'.$id.'_uniqueform_id_temp"><div id="'.$id.'_table_name" cellpadding="0" cellspacing="0" style="display: table;"><div id="'.$id.'_tr_name1" style="display: table-row;">'.$w_name_format.'    </div>'.$w_name_format_mini_labels.'   </div></div></div>';
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
							$param['attributes'] = $param['attributes'].' add_'.$attr;
					}
					$param['w_field_label_pos'] = ($param['w_field_label_pos']=="left" ? "table-cell" : "block");	
					$required_sym = ($param['w_required']=="yes" ? " *" : "");	
					
					
					$w_mini_labels = explode('***',$param['w_mini_labels']);
					$w_disabled_fields = explode('***',$param['w_disabled_fields']);
					
					$hidden_inputs = '';

					$labels_for_id = array('street1', 'street2', 'city', 'state', 'postal', 'country');
					foreach($w_disabled_fields as $key=>$w_disabled_field)
					{
						if($key!=6)
						{
							if($w_disabled_field=='yes')
							$hidden_inputs .= '<input type="hidden" id="'.$id.'_'.$labels_for_id[$key].'form_id_temp" value="'.$w_mini_labels[$key].'" id_for_label="'.($id+$key).'">';
						}
					}
					
										
					$address_fields ='';
					$g=0;
					if($w_disabled_fields[0]=='no')
					{
					$g+=2;
					$address_fields .= '<span style="float: left; width: 100%; padding-bottom: 8px; display: block;"><input type="text" id="'.$id.'_street1form_id_temp" name="'.$id.'_street1form_id_temp" onchange="change_value(&quot;'.$id.'_street1form_id_temp&quot;)" style="width: 100%;" '.$param['attributes'].'><label class="mini_label" id="'.$id.'_mini_label_street1" style="display: block;">'.$w_mini_labels[0].'</label></span>';
					}
					
					if($w_disabled_fields[1]=='no')
					{
					$g+=2;
					$address_fields .= '<span style="float: left; width: 100%; padding-bottom: 8px; display: block;"><input type="text" id="'.$id.'_street2form_id_temp" name="'.($id+1).'_street2form_id_temp" onchange="change_value(&quot;'.$id.'_street2form_id_temp&quot;)" style="width: 100%;" '.$param['attributes'].'><label class="mini_label" style="display: block;" id="'.$id.'_mini_label_street2">'.$w_mini_labels[1].'</label></span>';
					}
					
					if($w_disabled_fields[2]=='no')
					{
					$g++;
					$address_fields .= '<span style="float: left; width: 48%; padding-bottom: 8px;"><input type="text" id="'.$id.'_cityform_id_temp" name="'.($id+2).'_cityform_id_temp" onchange="change_value(&quot;'.$id.'_cityform_id_temp&quot;)" style="width: 100%;" '.$param['attributes'].'><label class="mini_label" style="display: block;" id="'.$id.'_mini_label_city">'.$w_mini_labels[2].'</label></span>';
					}
					if($w_disabled_fields[3]=='no')
					{
					$g++;
					if($w_disabled_fields[5]=='yes' && $w_disabled_fields[6]=='yes')
					$address_fields .= '<span style="float: '.(($g%2==0) ? 'right' : 'left').'; width: 48%; padding-bottom: 8px;"><select type="text" id="'.$id.'_stateform_id_temp" name="'.($id+3).'_stateform_id_temp" onchange="change_value(&quot;'.$id.'_stateform_id_temp&quot;)" style="width: 100%;" '.$param['attributes'].'><option value=""></option><option value="Alabama">Alabama</option><option value="Alaska">Alaska</option><option value="Arizona">Arizona</option><option value="Arkansas">Arkansas</option><option value="California">California</option><option value="Colorado">Colorado</option><option value="Connecticut">Connecticut</option><option value="Delaware">Delaware</option><option value="Florida">Florida</option><option value="Georgia">Georgia</option><option value="Hawaii">Hawaii</option><option value="Idaho">Idaho</option><option value="Illinois">Illinois</option><option value="Indiana">Indiana</option><option value="Iowa">Iowa</option><option value="Kansas">Kansas</option><option value="Kentucky">Kentucky</option><option value="Louisiana">Louisiana</option><option value="Maine">Maine</option><option value="Maryland">Maryland</option><option value="Massachusetts">Massachusetts</option><option value="Michigan">Michigan</option><option value="Minnesota">Minnesota</option><option value="Mississippi">Mississippi</option><option value="Missouri">Missouri</option><option value="Montana">Montana</option><option value="Nebraska">Nebraska</option><option value="Nevada">Nevada</option><option value="New Hampshire">New Hampshire</option><option value="New Jersey">New Jersey</option><option value="New Mexico">New Mexico</option><option value="New York">New York</option><option value="North Carolina">North Carolina</option><option value="North Dakota">North Dakota</option><option value="Ohio">Ohio</option><option value="Oklahoma">Oklahoma</option><option value="Oregon">Oregon</option><option value="Pennsylvania">Pennsylvania</option><option value="Rhode Island">Rhode Island</option><option value="South Carolina">South Carolina</option><option value="South Dakota">South Dakota</option><option value="Tennessee">Tennessee</option><option value="Texas">Texas</option><option value="Utah">Utah</option><option value="Vermont">Vermont</option><option value="Virginia">Virginia</option><option value="Washington">Washington</option><option value="West Virginia">West Virginia</option><option value="Wisconsin">Wisconsin</option><option value="Wyoming">Wyoming</option></select><label class="mini_label" style="display: block;" id="'.$id.'_mini_label_state">'.$w_mini_labels[3].'</label></span>';
					else
					$address_fields .= '<span style="float: '.(($g%2==0) ? 'right' : 'left').'; width: 48%; padding-bottom: 8px;"><input type="text" id="'.$id.'_stateform_id_temp" name="'.($id+3).'_stateform_id_temp" onchange="change_value(&quot;'.$id.'_stateform_id_temp&quot;)" style="width: 100%;" '.$param['attributes'].'><label class="mini_label" style="display: block;" id="'.$id.'_mini_label_state">'.$w_mini_labels[3].'</label></span>';
					}
					if($w_disabled_fields[4]=='no')
					{
					$g++;
					$address_fields .= '<span style="float: '.(($g%2==0) ? 'right' : 'left').'; width: 48%; padding-bottom: 8px;"><input type="text" id="'.$id.'_postalform_id_temp" name="'.($id+4).'_postalform_id_temp" onchange="change_value(&quot;'.$id.'_postalform_id_temp&quot;)" style="width: 100%;" '.$param['attributes'].'><label class="mini_label" style="display: block;" id="'.$id.'_mini_label_postal">'.$w_mini_labels[4].'</label></span>';
					}
					if($w_disabled_fields[5]=='no')
					{
					$g++;
					$address_fields .= '<span style="float: '.(($g%2==0) ? 'right' : 'left').'; width: 48%; padding-bottom: 8px;"><select type="text" id="'.$id.'_countryform_id_temp" name="'.($id+5).'_countryform_id_temp" onchange="change_state_input(&quot;'.$id.'_countryform_id_temp&quot;)" style="width: 100%;" '.$param['attributes'].'><option value=""></option><option value="Afghanistan">Afghanistan</option><option value="Albania">Albania</option><option value="Algeria">Algeria</option><option value="Andorra">Andorra</option><option value="Angola">Angola</option><option value="Antigua and Barbuda">Antigua and Barbuda</option><option value="Argentina">Argentina</option><option value="Armenia">Armenia</option><option value="Australia">Australia</option><option value="Austria">Austria</option><option value="Azerbaijan">Azerbaijan</option><option value="Bahamas">Bahamas</option><option value="Bahrain">Bahrain</option><option value="Bangladesh">Bangladesh</option><option value="Barbados">Barbados</option><option value="Belarus">Belarus</option><option value="Belgium">Belgium</option><option value="Belize">Belize</option><option value="Benin">Benin</option><option value="Bhutan">Bhutan</option><option value="Bolivia">Bolivia</option><option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option><option value="Botswana">Botswana</option><option value="Brazil">Brazil</option><option value="Brunei">Brunei</option><option value="Bulgaria">Bulgaria</option><option value="Burkina Faso">Burkina Faso</option><option value="Burundi">Burundi</option><option value="Cambodia">Cambodia</option><option value="Cameroon">Cameroon</option><option value="Canada">Canada</option><option value="Cape Verde">Cape Verde</option><option value="Central African Republic">Central African Republic</option><option value="Chad">Chad</option><option value="Chile">Chile</option><option value="China">China</option><option value="Colombi">Colombi</option><option value="Comoros">Comoros</option><option value="Congo (Brazzaville)">Congo (Brazzaville)</option><option value="Congo">Congo</option><option value="Costa Rica">Costa Rica</option><option value="Cote d\'Ivoire">Cote d\'Ivoire</option><option value="Croatia">Croatia</option><option value="Cuba">Cuba</option><option value="Cyprus">Cyprus</option><option value="Czech Republic">Czech Republic</option><option value="Denmark">Denmark</option><option value="Djibouti">Djibouti</option><option value="Dominica">Dominica</option><option value="Dominican Republic">Dominican Republic</option><option value="East Timor (Timor Timur)">East Timor (Timor Timur)</option><option value="Ecuador">Ecuador</option><option value="Egypt">Egypt</option><option value="El Salvador">El Salvador</option><option value="Equatorial Guinea">Equatorial Guinea</option><option value="Eritrea">Eritrea</option><option value="Estonia">Estonia</option><option value="Ethiopia">Ethiopia</option><option value="Fiji">Fiji</option><option value="Finland">Finland</option><option value="France">France</option><option value="Gabon">Gabon</option><option value="Gambia, The">Gambia, The</option><option value="Georgia">Georgia</option><option value="Germany">Germany</option><option value="Ghana">Ghana</option><option value="Greece">Greece</option><option value="Grenada">Grenada</option><option value="Guatemala">Guatemala</option><option value="Guinea">Guinea</option><option value="Guinea-Bissau">Guinea-Bissau</option><option value="Guyana">Guyana</option><option value="Haiti">Haiti</option><option value="Honduras">Honduras</option><option value="Hungary">Hungary</option><option value="Iceland">Iceland</option><option value="India">India</option><option value="Indonesia">Indonesia</option><option value="Iran">Iran</option><option value="Iraq">Iraq</option><option value="Ireland">Ireland</option><option value="Israel">Israel</option><option value="Italy">Italy</option><option value="Jamaica">Jamaica</option><option value="Japan">Japan</option><option value="Jordan">Jordan</option><option value="Kazakhstan">Kazakhstan</option><option value="Kenya">Kenya</option><option value="Kiribati">Kiribati</option><option value="Korea, North">Korea, North</option><option value="Korea, South">Korea, South</option><option value="Kuwait">Kuwait</option><option value="Kyrgyzstan">Kyrgyzstan</option><option value="Laos">Laos</option><option value="Latvia">Latvia</option><option value="Lebanon">Lebanon</option><option value="Lesotho">Lesotho</option><option value="Liberia">Liberia</option><option value="Libya">Libya</option><option value="Liechtenstein">Liechtenstein</option><option value="Lithuania">Lithuania</option><option value="Luxembourg">Luxembourg</option><option value="Macedonia">Macedonia</option><option value="Madagascar">Madagascar</option><option value="Malawi">Malawi</option><option value="Malaysia">Malaysia</option><option value="Maldives">Maldives</option><option value="Mali">Mali</option><option value="Malta">Malta</option><option value="Marshall Islands">Marshall Islands</option><option value="Mauritania">Mauritania</option><option value="Mauritius">Mauritius</option><option value="Mexico">Mexico</option><option value="Micronesia">Micronesia</option><option value="Moldova">Moldova</option><option value="Monaco">Monaco</option><option value="Mongolia">Mongolia</option><option value="Morocco">Morocco</option><option value="Mozambique">Mozambique</option><option value="Myanmar">Myanmar</option><option value="Namibia">Namibia</option><option value="Nauru">Nauru</option><option value="Nepa">Nepa</option><option value="Netherlands">Netherlands</option><option value="New Zealand">New Zealand</option><option value="Nicaragua">Nicaragua</option><option value="Niger">Niger</option><option value="Nigeria">Nigeria</option><option value="Norway">Norway</option><option value="Oman">Oman</option><option value="Pakistan">Pakistan</option><option value="Palau">Palau</option><option value="Panama">Panama</option><option value="Papua New Guinea">Papua New Guinea</option><option value="Paraguay">Paraguay</option><option value="Peru">Peru</option><option value="Philippines">Philippines</option><option value="Poland">Poland</option><option value="Portugal">Portugal</option><option value="Qatar">Qatar</option><option value="Romania">Romania</option><option value="Russia">Russia</option><option value="Rwanda">Rwanda</option><option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option><option value="Saint Lucia">Saint Lucia</option><option value="Saint Vincent">Saint Vincent</option><option value="Samoa">Samoa</option><option value="San Marino">San Marino</option><option value="Sao Tome and Principe">Sao Tome and Principe</option><option value="Saudi Arabia">Saudi Arabia</option><option value="Senegal">Senegal</option><option value="Serbia and Montenegro">Serbia and Montenegro</option><option value="Seychelles">Seychelles</option><option value="Sierra Leone">Sierra Leone</option><option value="Singapore">Singapore</option><option value="Slovakia">Slovakia</option><option value="Slovenia">Slovenia</option><option value="Solomon Islands">Solomon Islands</option><option value="Somalia">Somalia</option><option value="South Africa">South Africa</option><option value="Spain">Spain</option><option value="Sri Lanka">Sri Lanka</option><option value="Sudan">Sudan</option><option value="Suriname">Suriname</option><option value="Swaziland">Swaziland</option><option value="Sweden">Sweden</option><option value="Switzerland">Switzerland</option><option value="Syria">Syria</option><option value="Taiwan">Taiwan</option><option value="Tajikistan">Tajikistan</option><option value="Tanzania">Tanzania</option><option value="Thailand">Thailand</option><option value="Togo">Togo</option><option value="Tonga">Tonga</option><option value="Trinidad and Tobago">Trinidad and Tobago</option><option value="Tunisia">Tunisia</option><option value="Turkey">Turkey</option><option value="Turkmenistan">Turkmenistan</option><option value="Tuvalu">Tuvalu</option><option value="Uganda">Uganda</option><option value="Ukraine">Ukraine</option><option value="United Arab Emirates">United Arab Emirates</option><option value="United Kingdom">United Kingdom</option><option value="United States">United States</option><option value="Uruguay">Uruguay</option><option value="Uzbekistan">Uzbekistan</option><option value="Vanuatu">Vanuatu</option><option value="Vatican City">Vatican City</option><option value="Venezuela">Venezuela</option><option value="Vietnam">Vietnam</option><option value="Yemen">Yemen</option><option value="Zambia">Zambia</option><option value="Zimbabwe">Zimbabwe</option></select><label class="mini_label" style="display: block;" id="'.$id.'_mini_label_country">'.$w_mini_labels[5].'</span>';
					}				
				
					$rep ='<div id="wdform_field'.$id.'" type="type_address" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px; vertical-align:top;"><span id="'.$id.'_element_labelform_id_temp" class="wd_form_label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].';"><input type="hidden" value="type_address" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="hidden" name="'.$id.'_disable_fieldsform_id_temp" id="'.$id.'_disable_fieldsform_id_temp" street1="'.$w_disabled_fields[0].'" street2="'.$w_disabled_fields[1].'" city="'.$w_disabled_fields[2].'" state="'.$w_disabled_fields[3].'" postal="'.$w_disabled_fields[4].'" country="'.$w_disabled_fields[5].'" us_states="'.$w_disabled_fields[6].'"><div id="'.$id.'_div_address" style="width: '.$param['w_size'].'px;">'.$address_fields.$hidden_inputs.'</div></div></div>';
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
							$param['attributes'] = $param['attributes'].' add_'.$attr;
					}

					$param['w_field_label_pos'] = ($param['w_field_label_pos']=="left" ? "table-cell" : "block");	
					$input_active = ($param['w_first_val']==$param['w_title'] ? "input_deactive" : "input_active");	
					$required_sym = ($param['w_required']=="yes" ? " *" : "");	
				
					$rep ='<div id="wdform_field'.$id.'" type="type_submitter_mail" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;"><span id="'.$id.'_element_labelform_id_temp" class="wd_form_label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; vertical-align:top;"><input type="hidden" value="type_submitter_mail" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="hidden" value="'.$param['w_unique'].'" name="'.$id.'_uniqueform_id_temp" id="'.$id.'_uniqueform_id_temp"><input type="text" class="'.$input_active.'" id="'.$id.'_elementform_id_temp" name="'.$id.'_elementform_id_temp" value="'.$param['w_first_val'].'" title="'.$param['w_title'].'" onfocus="delete_value(&quot;'.$id.'_elementform_id_temp&quot;)" onblur="return_value(&quot;'.$id.'_elementform_id_temp&quot;)" onchange="change_value(&quot;'.$id.'_elementform_id_temp&quot;)" style="width: '.$param['w_size'].'px;" '.$param['attributes'].'></div></div>';
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
							$param['attributes'] = $param['attributes'].' add_'.$attr;
					}

					$param['w_field_label_pos'] = ($param['w_field_label_pos']=="left" ? "table-cell" : "block");	
					$required_sym = ($param['w_required']=="yes" ? " *" : "");	
					$param['w_choices']	= explode('***',$param['w_choices']);
					$param['w_choices_checked']	= explode('***',$param['w_choices_checked']);
					
					foreach($param['w_choices_checked'] as $key => $choices_checked )
					{
						if($choices_checked=='true')
							$param['w_choices_checked'][$key]='checked="checked"';
						else
							$param['w_choices_checked'][$key]='';
					}
					
					$rep='<div id="wdform_field'.$id.'" type="type_checkbox" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;"><span id="'.$id.'_element_labelform_id_temp" class="wd_form_label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].';"><input type="hidden" value="type_checkbox" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="hidden" value="'.$param['w_randomize'].'" name="'.$id.'_randomizeform_id_temp" id="'.$id.'_randomizeform_id_temp"><input type="hidden" value="'.$param['w_allow_other'].'" name="'.$id.'_allow_otherform_id_temp" id="'.$id.'_allow_otherform_id_temp"><input type="hidden" value="'.$param['w_allow_other_num'].'" name="'.$id.'_allow_other_numform_id_temp" id="'.$id.'_allow_other_numform_id_temp"><input type="hidden" value="'.$param['w_rowcol'].'" name="'.$id.'_rowcol_numform_id_temp" id="'.$id.'_rowcol_numform_id_temp"><div style="display: table;"><div id="'.$id.'_table_little" style="display: table-row-group;" '.($param['w_flow']=='hor' ? 'for_hor="'.$id.'_hor"' : '').'>';
				
				if($param['w_flow']=='hor')
				{
						$j = 0;
						for($i=0; $i<(int)$param['w_rowcol']; $i++)
						{
							$rep.='<div id="'.$id.'_element_tr'.$i.'" style="display: table-row;">';
							
								for($l=0; $l<=(int)(count($param['w_choices'])/$param['w_rowcol']); $l++)
								{
									if($j >= count($param['w_choices'])%$param['w_rowcol'] && $l==(int)(count($param['w_choices'])/$param['w_rowcol']))
									continue;
									
									if($param['w_allow_other']=="yes" && $param['w_allow_other_num']==(int)$param['w_rowcol']*$i+$l)
										$rep.='<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$l+$i).'" idi="'.((int)$param['w_rowcol']*$l+$i).'" style="display: table-cell;"><input type="checkbox" value="'.$param['w_choices'][(int)$param['w_rowcol']*$l+$i].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$l+$i).'" name="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$l+$i).'" other="1" onclick="if(set_checked(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$l+$i).'&quot;,&quot;form_id_temp&quot;)) show_other_input(&quot;'.$id.'&quot;,&quot;form_id_temp&quot;);" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$l+$i].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$l+$i).'" class="ch-rad-label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$l+$i).'">'.$param['w_choices'][(int)$param['w_rowcol']*$l+$i].'</label></div>';
									else						
										$rep.='<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$l+$i).'" idi="'.((int)$param['w_rowcol']*$l+$i).'" style="display: table-cell;"><input type="checkbox" value="'.$param['w_choices'][(int)$param['w_rowcol']*$l+$i].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$l+$i).'" name="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$l+$i).'" onclick="set_checked(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$l+$i).'&quot;,&quot;form_id_temp&quot;)" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$l+$i].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$l+$i).'" class="ch-rad-label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$l+$i).'">'.$param['w_choices'][(int)$param['w_rowcol']*$l+$i].'</label></div>';		
								}
							
							$j++;
							$rep.='</div>';	
							
						}
			
				}
				else
				{
						for($i=0; $i<(int)(count($param['w_choices'])/$param['w_rowcol']); $i++)
						{
							$rep.='<div id="'.$id.'_element_tr'.$i.'" style="display: table-row;">';
							
							if(count($param['w_choices']) > (int)$param['w_rowcol'])
								for($l=0; $l<$param['w_rowcol']; $l++)
								{
									if($param['w_allow_other']=="yes" && $param['w_allow_other_num']==(int)$param['w_rowcol']*$i+$l)
										$rep.='<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$i+$l).'" idi="'.((int)$param['w_rowcol']*$i+$l).'" style="display: table-cell;"><input type="checkbox" value="'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" name="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" other="1" onclick="if(set_checked(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$i+$l).'&quot;,&quot;form_id_temp&quot;)) show_other_input(&quot;'.$id.'&quot;,&quot;form_id_temp&quot;);" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$i+$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$i+$l).'" class="ch-rad-label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'">'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'</label></div>';
									else						
										$rep.='<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$i+$l).'" idi="'.((int)$param['w_rowcol']*$i+$l).'" style="display: table-cell;"><input type="checkbox" value="'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" name="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" onclick="set_checked(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$i+$l).'&quot;,&quot;form_id_temp&quot;)" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$i+$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$i+$l).'" class="ch-rad-label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'">'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'</label></div>';
								}
							else
								for($l=0; $l<count($param['w_choices']); $l++)
								{
									if($param['w_allow_other']=="yes" && $param['w_allow_other_num']==(int)$param['w_rowcol']*$i+$l)
										$rep.='<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$i+$l).'" idi="'.((int)$param['w_rowcol']*$i+$l).'" style="display: table-cell;"><input type="checkbox" value="'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" name="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" other="1" onclick="if(set_checked(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$i+$l).'&quot;,&quot;form_id_temp&quot;)) show_other_input(&quot;'.$id.'&quot;,&quot;form_id_temp&quot;);" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$i+$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$i+$l).'" class="ch-rad-label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'">'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'</label></div>';
									else	
										$rep.='<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$i+$l).'" idi="'.((int)$param['w_rowcol']*$i+$l).'" style="display: table-cell;"><input type="checkbox" value="'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" name="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" onclick="set_checked(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$i+$l).'&quot;,&quot;form_id_temp&quot;)" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$i+$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$i+$l).'" class="ch-rad-label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'">'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'</label></div>';
								}
							
							$rep.='</div>';	
						}
						
						if(count($param['w_choices'])%$param['w_rowcol']!=0)
						{
							$rep.='<div id="'.$id.'_element_tr'.((int)(count($param['w_choices'])/(int)$param['w_rowcol'])).'" style="display: table-row;">';
							
							for($k=0; $k<count($param['w_choices'])%$param['w_rowcol']; $k++)
							{
								$l = count($param['w_choices']) - count($param['w_choices'])%$param['w_rowcol'] + $k;
								if($param['w_allow_other']=="yes" && $param['w_allow_other_num']==$l)
									$rep.='<div valign="top" id="'.$id.'_td_little'.$l.'" idi="'.$l.'" style="display: table-cell;"><input type="checkbox" value="'.$param['w_choices'][$l].'" id="'.$id.'_elementform_id_temp'.$l.'" name="'.$id.'_elementform_id_temp'.$l.'" other="1" onclick="if(set_checked(&quot;'.$id.'&quot;,&quot;'.$l.'&quot;,&quot;form_id_temp&quot;)) show_other_input(&quot;'.$id.'&quot;,&quot;form_id_temp&quot;);" '.$param['w_choices_checked'][$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.$l.'" class="ch-rad-label" for="'.$id.'_elementform_id_temp'.$l.'">'.$param['w_choices'][$l].'</label></div>';
								else	
									$rep.='<div valign="top" id="'.$id.'_td_little'.$l.'" idi="'.$l.'" style="display: table-cell;"><input type="checkbox" value="'.$param['w_choices'][$l].'" id="'.$id.'_elementform_id_temp'.$l.'" name="'.$id.'_elementform_id_temp'.$l.'" onclick="set_checked(&quot;'.$id.'&quot;,&quot;'.$l.'&quot;,&quot;form_id_temp&quot;)" '.$param['w_choices_checked'][$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.$l.'" class="ch-rad-label" for="'.$id.'_elementform_id_temp'.$l.'">'.$param['w_choices'][$l].'</label></div>';
							}
							
							$rep.='</div>';	
						}
						
	
					
				}
				$rep.='</div></div></div></div>';
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
							$param['attributes'] = $param['attributes'].' add_'.$attr;
					}

					$param['w_field_label_pos'] = ($param['w_field_label_pos']=="left" ? "table-cell" : "block");	
					$required_sym = ($param['w_required']=="yes" ? " *" : "");	
					$param['w_choices']	= explode('***',$param['w_choices']);
					$param['w_choices_checked']	= explode('***',$param['w_choices_checked']);
					
					foreach($param['w_choices_checked'] as $key => $choices_checked )
					{
						if($choices_checked=='true')
							$param['w_choices_checked'][$key]='checked="checked"';
						else
							$param['w_choices_checked'][$key]='';
					}	
					
					$rep='<div id="wdform_field'.$id.'" type="type_radio" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;"><span id="'.$id.'_element_labelform_id_temp" class="wd_form_label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].';"><input type="hidden" value="type_radio" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><input type="hidden" value="'.$param['w_randomize'].'" name="'.$id.'_randomizeform_id_temp" id="'.$id.'_randomizeform_id_temp"><input type="hidden" value="'.$param['w_allow_other'].'" name="'.$id.'_allow_otherform_id_temp" id="'.$id.'_allow_otherform_id_temp"><input type="hidden" value="'.$param['w_allow_other_num'].'" name="'.$id.'_allow_other_numform_id_temp" id="'.$id.'_allow_other_numform_id_temp"><input type="hidden" value="'.$param['w_rowcol'].'" name="'.$id.'_rowcol_numform_id_temp" id="'.$id.'_rowcol_numform_id_temp"><div style="display: table;"><div id="'.$id.'_table_little" style="display: table-row-group;" '.($param['w_flow']=='hor' ? 'for_hor="'.$id.'_hor"' : '').'>';
				
				
					if($param['w_flow']=='hor')
					{
						$j = 0;
						for($i=0; $i<(int)$param['w_rowcol']; $i++)
						{
							$rep.='<div id="'.$id.'_element_tr'.$i.'" style="display: table-row;">';
							
								for($l=0; $l<=(int)(count($param['w_choices'])/$param['w_rowcol']); $l++)
								{
									if($j >= count($param['w_choices'])%$param['w_rowcol'] && $l==(int)(count($param['w_choices'])/$param['w_rowcol']))
									continue;
									
									if($param['w_allow_other']=="yes" && $param['w_allow_other_num']==(int)$param['w_rowcol']*$i+$l)
											$rep.='<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$l+$i).'" idi="'.((int)$param['w_rowcol']*$l+$i).'" style="display: table-cell;"><input type="radio" value="'.$param['w_choices'][(int)$param['w_rowcol']*$l+$i].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$l+$i).'" name="'.$id.'_elementform_id_temp" other="1" onclick="set_default(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$l+$i).'&quot;,&quot;form_id_temp&quot;); show_other_input(&quot;'.$id.'&quot;,&quot;form_id_temp&quot;);" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$l+$i].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$l+$i).'" class="ch-rad-label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$l+$i).'">'.$param['w_choices'][(int)$param['w_rowcol']*$l+$i].'</label></div>';
										else						
											$rep.='<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$l+$i).'" idi="'.((int)$param['w_rowcol']*$l+$i).'" style="display: table-cell;"><input type="radio" value="'.$param['w_choices'][(int)$param['w_rowcol']*$l+$i].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$l+$i).'" name="'.$id.'_elementform_id_temp" onclick="set_default(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$l+$i).'&quot;,&quot;form_id_temp&quot;)" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$l+$i].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$l+$i).'" class="ch-rad-label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$l+$i).'">'.$param['w_choices'][(int)$param['w_rowcol']*$l+$i].'</label></div>';		
								}
								
							$j++;
							$rep.='</div>';	
							
						}
		
					}
					else
					{
						for($i=0; $i<(int)(count($param['w_choices'])/$param['w_rowcol']); $i++)
						{
							$rep.='<div id="'.$id.'_element_tr'.$i.'" style="display: table-row;">';
							
							if(count($param['w_choices']) > (int)$param['w_rowcol'])
								for($l=0; $l<$param['w_rowcol']; $l++)
								{
									if($param['w_allow_other']=="yes" && $param['w_allow_other_num']==(int)$param['w_rowcol']*$i+$l)
										$rep.='<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$i+$l).'" idi="'.((int)$param['w_rowcol']*$i+$l).'" style="display: table-cell;"><input type="radio" value="'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" name="'.$id.'_elementform_id_temp" other="1" onclick="set_default(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$i+$l).'&quot;,&quot;form_id_temp&quot;); show_other_input(&quot;'.$id.'&quot;,&quot;form_id_temp&quot;);" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$i+$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$i+$l).'" class="ch-rad-label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'">'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'</label></div>';
									else						
										$rep.='<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$i+$l).'" idi="'.((int)$param['w_rowcol']*$i+$l).'" style="display: table-cell;"><input type="radio" value="'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" name="'.$id.'_elementform_id_temp" onclick="set_default(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$i+$l).'&quot;,&quot;form_id_temp&quot;)" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$i+$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$i+$l).'" class="ch-rad-label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'">'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'</label></div>';
								}
							else
								for($l=0; $l<count($param['w_choices']); $l++)
								{
									if($param['w_allow_other']=="yes" && $param['w_allow_other_num']==(int)$param['w_rowcol']*$i+$l)
										$rep.='<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$i+$l).'" idi="'.((int)$param['w_rowcol']*$i+$l).'" style="display: table-cell;"><input type="radio" value="'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" name="'.$id.'_elementform_id_temp" other="1" onclick="set_default(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$i+$l).'&quot;,&quot;form_id_temp&quot;); show_other_input(&quot;'.$id.'&quot;,&quot;form_id_temp&quot;);" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$i+$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$i+$l).'" class="ch-rad-label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'">'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'</label></div>';
									else	
										$rep.='<div valign="top" id="'.$id.'_td_little'.((int)$param['w_rowcol']*$i+$l).'" idi="'.((int)$param['w_rowcol']*$i+$l).'" style="display: table-cell;"><input type="radio" value="'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'" id="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'" name="'.$id.'_elementform_id_temp" onclick="set_default(&quot;'.$id.'&quot;,&quot;'.((int)$param['w_rowcol']*$i+$l).'&quot;,&quot;form_id_temp&quot;)" '.$param['w_choices_checked'][(int)$param['w_rowcol']*$i+$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.((int)$param['w_rowcol']*$i+$l).'" class="ch-rad-label" for="'.$id.'_elementform_id_temp'.((int)$param['w_rowcol']*$i+$l).'">'.$param['w_choices'][(int)$param['w_rowcol']*$i+$l].'</label></div>';
								}
							
							$rep.='</div>';	
						}
						
						if(count($param['w_choices'])%$param['w_rowcol']!=0)
						{
							$rep.='<div id="'.$id.'_element_tr'.((int)(count($param['w_choices'])/(int)$param['w_rowcol'])).'" style="display: table-row;">';
							
							for($k=0; $k<count($param['w_choices'])%$param['w_rowcol']; $k++)
							{
								$l = count($param['w_choices']) - count($param['w_choices'])%$param['w_rowcol'] + $k;
								if($param['w_allow_other']=="yes" && $param['w_allow_other_num']==$l)
									$rep.='<div valign="top" id="'.$id.'_td_little'.$l.'" idi="'.$l.'" style="display: table-cell;"><input type="radio" value="'.$param['w_choices'][$l].'" id="'.$id.'_elementform_id_temp'.$l.'" name="'.$id.'_elementform_id_temp" other="1" onclick="set_default(&quot;'.$id.'&quot;,&quot;'.$l.'&quot;,&quot;form_id_temp&quot;); show_other_input(&quot;'.$id.'&quot;,&quot;form_id_temp&quot;);" '.$param['w_choices_checked'][$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.$l.'" class="ch-rad-label" for="'.$id.'_elementform_id_temp'.$l.'">'.$param['w_choices'][$l].'</label></div>';
								else	
									$rep.='<div valign="top" id="'.$id.'_td_little'.$l.'" idi="'.$l.'" style="display: table-cell;"><input type="radio" value="'.$param['w_choices'][$l].'" id="'.$id.'_elementform_id_temp'.$l.'" name="'.$id.'_elementform_id_temp" onclick="set_default(&quot;'.$id.'&quot;,&quot;'.$l.'&quot;,&quot;form_id_temp&quot;)" '.$param['w_choices_checked'][$l].' '.$param['attributes'].'><label id="'.$id.'_label_element'.$l.'" class="ch-rad-label" for="'.$id.'_elementform_id_temp'.$l.'">'.$param['w_choices'][$l].'</label></div>';
							}
							
							$rep.='</div>';	
						}
						
					}
							
					
		
				$rep.='</div></div></div></div>';
				
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
							$param['attributes'] = $param['attributes'].' add_'.$attr;
					}

					$param['w_field_label_pos'] = ($param['w_field_label_pos']=="left" ? "table-cell" : "block");	
					$required_sym = ($param['w_required']=="yes" ? " *" : "");	
					$param['w_choices']	= explode('***',$param['w_choices']);
					$param['w_choices_checked']	= explode('***',$param['w_choices_checked']);
					$param['w_choices_disabled']	= explode('***',$param['w_choices_disabled']);
				
					foreach($param['w_choices_checked'] as $key => $choices_checked )
					{
						if($choices_checked=='true')
							$param['w_choices_checked'][$key]='selected="selected"';
						else
							$param['w_choices_checked'][$key]='';
					}
					
					$rep='<div id="wdform_field'.$id.'" type="type_own_select" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;"><span id="'.$id.'_element_labelform_id_temp" class="wd_form_label" style="vertical-align: top;">'.$label.'</span><span id="'.$id.'_required_elementform_id_temp" class="required" style="vertical-align: top;">'.$required_sym.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; vertical-align:top;"><input type="hidden" value="type_own_select" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><input type="hidden" value="'.$param['w_required'].'" name="'.$id.'_requiredform_id_temp" id="'.$id.'_requiredform_id_temp"><select id="'.$id.'_elementform_id_temp" name="'.$id.'_elementform_id_temp" onchange="set_select(this)" style="width: '.$param['w_size'].'px;"  '.$param['attributes'].'>';
					foreach($param['w_choices'] as $key => $choice)
					{
						if($param['w_choices_disabled'][$key]=="true")
							$choice_value='';
						else
							$choice_value=$choice;
					  $rep.='<option id="'.$id.'_option'.$key.'" value="'.$choice_value.'" onselect="set_select(&quot;'.$id.'_option'.$key.'&quot;)" '.$param['w_choices_checked'][$key].'>'.$choice.'</option>';
					}
					$rep.='</select></div></div>';
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
							$param['attributes'] = $param['attributes'].' add_'.$attr;
					}

					$param['w_field_label_pos'] = ($param['w_field_label_pos']=="left" ? "table-cell" : "block");	
					
					$rep ='<div id="wdform_field'.$id.'" type="type_captcha" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display:'.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;"><span id="'.$id.'_element_labelform_id_temp" class="wd_form_label" style="vertical-align: top;">'.$label.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; vertical-align:top;"><input type="hidden" value="type_captcha" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><div style="display: table;"><div style="display: table-row;"><div valign="middle" style="display: table-cell; vertical-align:top;"><img type="captcha" digit="'.$param['w_digit'].'" src="../index.php?option=com_contactformmaker&amp;view=wdcaptcha&amp;format=raw&amp;tmpl=component&amp;digit='.$param['w_digit'].'&amp;i=form_id_temp" id="_wd_captchaform_id_temp" class="captcha_img" onclick="captcha_refresh(&quot;_wd_captcha&quot;,&quot;form_id_temp&quot;)" '.$param['attributes'].'></div><div valign="middle" style="display: table-cell;"><div class="captcha_refresh" id="_element_refreshform_id_temp" onclick="captcha_refresh(&quot;_wd_captcha&quot;,&quot;form_id_temp&quot;)" '.$param['attributes'].'></div></div></div><div style="display: table-row;"><div style="display: table-cell;"><input type="text" class="captcha_input" id="_wd_captcha_inputform_id_temp" name="captcha_input" style="width: '.($param['w_digit']*10+15).'px;" '.$param['attributes'].'></div></div></div></div></div>';
					
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
							$param['attributes'] = $param['attributes'].' add_'.$attr;
					}

					$param['w_field_label_pos'] = ($param['w_field_label_pos']=="left" ? "table-cell" : "block");	
					
					$rep ='<div id="wdform_field'.$id.'" type="type_recaptcha" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].'; width: '.$param['w_field_label_size'].'px;"><span id="'.$id.'_element_labelform_id_temp" class="wd_form_label" style="vertical-align: top;">'.$label.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: '.$param['w_field_label_pos'].';"><input type="hidden" value="type_recaptcha" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><div id="wd_recaptchaform_id_temp" public_key="'.$param['w_public'].'" private_key="'.$param['w_private'].'" theme="'.$param['w_theme'].'" '.$param['attributes'].'><span style="color: red; font-style: italic;">Recaptcha doesn\'t display in back end</span></div></div></div>';
					
					break;
				}
				
				case 'type_hidden':
				{
					$params_names=array('w_name','w_value');
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
							$param['attributes'] = $param['attributes'].' add_'.$attr;
					}
					
					$rep ='<div id="wdform_field'.$id.'" type="type_hidden" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" style="display: table-cell;"><span id="'.$id.'_element_labelform_id_temp" style="display: none;">'.$param['w_name'].'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" style="display: table-cell;"><input type="hidden" value="'.$param['w_value'].'" id="'.$id.'_elementform_id_temp" name="'.$param['w_name'].'" '.$param['attributes'].'><input type="hidden" value="type_hidden" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"></div></div>';
					
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
							$param['attributes'] = $param['attributes'].' add_'.$attr;
					}
					
					$marker='';
					
					$param['w_long']	= explode('***',$param['w_long']);
					$param['w_lat']	= explode('***',$param['w_lat']);
					$param['w_info']	= explode('***',$param['w_info']);
					foreach($param['w_long'] as $key => $w_long )
					{
						$marker.='long'.$key.'="'.$w_long.'" lat'.$key.'="'.$param['w_lat'][$key].'" info'.$key.'="'.$param['w_info'][$key].'"';
					}
				
					$rep ='<div id="wdform_field'.$id.'" type="type_map" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display: table-cell;"><span id="'.$id.'_element_labelform_id_temp" style="display: none;">'.$label.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: table-cell;"><input type="hidden" value="type_map" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><div id="'.$id.'_elementform_id_temp" zoom="'.$param['w_zoom'].'" center_x="'.$param['w_center_x'].'" center_y="'.$param['w_center_y'].'" style="width: '.$param['w_width'].'px; height: '.$param['w_height'].'px;" '.$marker.' '.$param['attributes'].'></div></div></div>';
					
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
							$param['attributes'] = $param['attributes'].' add_'.$attr;
					}

					$param['w_act'] = ($param['w_act']=="false" ? 'style="display: none;"' : "");	
					
					$rep='<div id="wdform_field'.$id.'" type="type_submit_reset" class="wdform_field" style="display: table-cell;"><div align="left" id="'.$id.'_label_sectionform_id_temp" class="'.$param['w_class'].'" style="display: table-cell;"><span id="'.$id.'_element_labelform_id_temp" style="display: none;">type_submit_reset_'.$id.'</span></div><div align="left" id="'.$id.'_element_sectionform_id_temp" class="'.$param['w_class'].'" style="display: table-cell;"><input type="hidden" value="type_submit_reset" name="'.$id.'_typeform_id_temp" id="'.$id.'_typeform_id_temp"><button type="button" class="button-submit" id="'.$id.'_element_submitform_id_temp" value="'.$param['w_submit_title'].'" onclick="check_required(&quot;submit&quot;, &quot;form_id_temp&quot;);" '.$param['attributes'].'>'.$param['w_submit_title'].'</button><button type="button" class="button-reset" id="'.$id.'_element_resetform_id_temp" value="'.$param['w_reset_title'].'" onclick="check_required(&quot;reset&quot;);" '.$param['w_act'].' '.$param['attributes'].'>'.$param['w_reset_title'].'</button></div></div>';
				
					break;
				}
			}
			
			///// Arrows
			
			switch($type)
			{
				case 'type_submit_reset':
				{
					$rep =$rep.'<div id="wdform_arrows'.$id.'" class="wdform_arrows" style="display: table-cell;"><div id="left_'.$id.'" valign="middle" class="element_toolbar"><img src="components/com_contactformmaker/images/left.png" title="Move the field to the left" onclick="left_row(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;left&quot;)" onmouseout="chnage_icons_src(this,&quot;left&quot;)"></div><div id="up_'.$id.'" valign="middle" class="element_toolbar"><img src="components/com_contactformmaker/images/up.png" title="Move the field up" onclick="up_row(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;up&quot;)" onmouseout="chnage_icons_src(this,&quot;up&quot;)"></div><div id="down_'.$id.'" valign="middle" class="element_toolbar"><img src="components/com_contactformmaker/images/down.png" title="Move the field down" onclick="down_row(&quot;'.$id.'&quot;)"  onmouseover="chnage_icons_src(this,&quot;down&quot;)" onmouseout="chnage_icons_src(this,&quot;down&quot;)"></div><div id="right_'.$id.'" valign="middle" class="element_toolbar"><img src="components/com_contactformmaker/images/right.png" title="Move the field to the right" onclick="right_row(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;right&quot;)" onmouseout="chnage_icons_src(this,&quot;right&quot;)"></div><div id="edit_'.$id.'" valign="middle" class="element_toolbar"><img src="components/com_contactformmaker/images/edit.png" title="Edit the field" onclick="edit(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;edit&quot;)" onmouseout="chnage_icons_src(this,&quot;edit&quot;)"></div><div id="X_'.$id.'" valign="middle" align="right" class="element_toolbar" style="visibility:hidden;"><input type="checkbox" id="disable_field'.$id.'" title="Disable the field" onclick="remove_row(&quot;'.$id.'&quot;)" style="vertical-align:top; margin: 5px;"></div></div>';
					break;
				}
			
				case 'type_section_break':
				{
					$rep =$rep.'<div id="wdform_arrows'.$id.'" class="wdform_arrows"><div id="edit_'.$id.'" class="element_toolbar"><img src="components/com_contactformmaker/images/edit.png" title="Edit the field" onclick="edit(&quot;'.$id.'&quot;)"><span id="'.$id.'_element_labelform_id_temp" style="display: none;">custom_'.$id.'</span></div><div id="X_'.$id.'" class="element_toolbar"><input type="checkbox" id="disable_field'.$id.'" title="Disable the field" onclick="remove_row(&quot;'.$id.'&quot;)" style="vertical-align: top; margin: 5px;"></div><div class="element_toolbar" style="color:red; vertical-align: top;">(section break)</div></div>';
					break;
				}
				
				case 'type_captcha':
				case 'type_recaptcha':
				{
					$rep =$rep.'<div id="wdform_arrows'.$id.'" class="wdform_arrows" style="display: table-cell;"><div id="left_'.$id.'" valign="middle" class="element_toolbar"><img src="components/com_contactformmaker/images/left.png" title="Move the field to the left" onclick="left_row(&quot;'.$id.'&quot;)"></div><div id="up_'.$id.'" valign="middle" class="element_toolbar"><img src="components/com_contactformmaker/images/up.png" title="Move the field up" onclick="up_row(&quot;'.$id.'&quot;)"></div><div id="down_'.$id.'" valign="middle" class="element_toolbar"><img src="components/com_contactformmaker/images/down.png" title="Move the field down" onclick="down_row(&quot;'.$id.'&quot;)"></div><div id="right_'.$id.'" valign="middle" class="element_toolbar"><img src="components/com_contactformmaker/images/right.png" title="Move the field to the right" onclick="right_row(&quot;'.$id.'&quot;)"></div><div id="edit_'.$id.'" valign="middle" class="element_toolbar"><img src="components/com_contactformmaker/images/edit.png" title="Edit the field" onclick="edit(&quot;'.$id.'&quot;)"></div><div id="X_'.$id.'" valign="middle" align="right" class="element_toolbar"><input type="checkbox" id="disable_field'.$id.'" title="Disable the field" onclick="remove_row(&quot;'.$id.'&quot;)" style="vertical-align: middle; margin: 5px;"></div></div>';
					break;
				}
				case 'type_editor':
				{
					$rep =$rep.'<div id="wdform_arrows'.$id.'" class="wdform_arrows" style="display: table-cell;"><div id="left_'.$id.'" valign="middle" class="element_toolbar"><img src="components/com_contactformmaker/images/left.png" title="Move the field to the left" onclick="left_row(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;left&quot;)" onmouseout="chnage_icons_src(this,&quot;left&quot;)"></div><div id="up_'.$id.'" valign="middle" class="element_toolbar"><img src="components/com_contactformmaker/images/up.png" title="Move the field up" onclick="up_row(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;up&quot;)" onmouseout="chnage_icons_src(this,&quot;up&quot;)"></div><div id="down_'.$id.'" valign="middle" class="element_toolbar"><img src="components/com_contactformmaker/images/down.png" title="Move the field down" onclick="down_row(&quot;'.$id.'&quot;)"  onmouseover="chnage_icons_src(this,&quot;down&quot;)" onmouseout="chnage_icons_src(this,&quot;down&quot;)"></div><div id="right_'.$id.'" valign="middle" class="element_toolbar"><img src="components/com_contactformmaker/images/right.png" title="Move the field to the right" onclick="right_row(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;right&quot;)" onmouseout="chnage_icons_src(this,&quot;right&quot;)"></div><div id="edit_'.$id.'" valign="middle" class="element_toolbar"><img src="components/com_contactformmaker/images/edit.png" title="Edit the field" onclick="edit(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;edit&quot;)" onmouseout="chnage_icons_src(this,&quot;edit&quot;)"></div><div id="X_'.$id.'" valign="middle" align="right" class="element_toolbar"><input type="checkbox" id="disable_field'.$id.'" title="Disable the field" onclick="remove_row(&quot;'.$id.'&quot;)" style="vertical-align: top; margin: 5px;"></div><div class="element_toolbar" style="color:red; vertical-align: middle;">(custom HTML)</div></div>';
					break;
				}
				default :
				{
					$rep =$rep.'<div id="wdform_arrows'.$id.'" class="wdform_arrows" style="display: table-cell;"><div id="left_'.$id.'" valign="middle" class="element_toolbar"><img src="components/com_contactformmaker/images/left.png" title="Move the field to the left" onclick="left_row(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;left&quot;)" onmouseout="chnage_icons_src(this,&quot;left&quot;)"></div><div id="up_'.$id.'" valign="middle" class="element_toolbar"><img src="components/com_contactformmaker/images/up.png" title="Move the field up" onclick="up_row(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;up&quot;)" onmouseout="chnage_icons_src(this,&quot;up&quot;)"></div><div id="down_'.$id.'" valign="middle" class="element_toolbar"><img src="components/com_contactformmaker/images/down.png" title="Move the field down" onclick="down_row(&quot;'.$id.'&quot;)"  onmouseover="chnage_icons_src(this,&quot;down&quot;)" onmouseout="chnage_icons_src(this,&quot;down&quot;)"></div><div id="right_'.$id.'" valign="middle" class="element_toolbar"><img src="components/com_contactformmaker/images/right.png" title="Move the field to the right" onclick="right_row(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;right&quot;)" onmouseout="chnage_icons_src(this,&quot;right&quot;)"></div><div id="edit_'.$id.'" valign="middle" class="element_toolbar"><img src="components/com_contactformmaker/images/edit.png" title="Edit the field" onclick="edit(&quot;'.$id.'&quot;)" onmouseover="chnage_icons_src(this,&quot;edit&quot;)" onmouseout="chnage_icons_src(this,&quot;edit&quot;)"></div><div id="X_'.$id.'" valign="middle" align="right" class="element_toolbar"><input type="checkbox" id="disable_field'.$id.'" title="Disable the field" onclick="remove_row(&quot;'.$id.'&quot;)" style="vertical-align: middle; margin: 5px;"></div></div>';
					break;
				}
				
			}
			
			$form=str_replace('%'.$id.' - '.$labels[$ids_key].'%', $rep, $form);
		}
		
	}
	$row->form_front=$form;
	HTML_contact::edit($row, $labels2);
	
	

}


function copy_form()
{

	$db		= JFactory::getDBO();
	$cid 	= JRequest::getVar('cid', array(0), '', 'array');
	JArrayHelper::toInteger($cid, array(0));
	$id 	= $cid[0];
	$row = JTable::getInstance('contactformmaker', 'Table');

	// load the row from the db table
	$row->load( $id);
		
	$mainframe = JFactory::getApplication();
	
	$row->id='';
	$new=true;

	if(!$row->store()){

		JError::raiseError(500, $row->getError() );

	}
	
	if($new)
	{
		$db = JFactory::getDBO();
		$db->setQuery("INSERT INTO #__contactformmaker_views (form_id, views) VALUES('".$row->id."', 0)" ); 
		$db->query();
		if ($db->getErrorNum())
		{
			echo $db->stderr();
			return false;
		}

	}
	
		$msg = 'The form has been saved successfully.';

		$link = 'index.php?option=com_contactformmaker';
		$mainframe->redirect($link, $msg, 'message');
}


function form_options_temp(){
	$mainframe = JFactory::getApplication();
	$row_id=save('return_id');
	$link = 'index.php?option=com_contactformmaker&task=form_options&cid[]='.$row_id;

	$mainframe->redirect($link);

}
function form_layout_temp(){
	
	$mainframe = JFactory::getApplication();
	$row_id=save('return_id');
	$link = 'index.php?option=com_contactformmaker&task=form_layout&cid[]='.$row_id;
	$mainframe->redirect($link);
}

function removeSubmit(){

  $mainframe = JFactory::getApplication();

  // Initialize variables	

  $db = JFactory::getDBO();

  // Define cid array variable

  $form_id = JRequest::getVar( 'form_id');
  $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );

  // Make sure cid array variable content integer format

  JArrayHelper::toInteger($cid);

  // If any item selected

  if (count( $cid )) {


    $cids = implode( ',', $cid );

    // Create sql statement

    $query = 'DELETE FROM #__contactformmaker_submits'

    . ' WHERE group_id IN ( '. $cids .' )'

    ;

    // Execute query

    $db->setQuery( $query );

    if (!$db->query()) {

      echo "<script> alert('".$db->getErrorMsg(true)."'); 

      window.history.go(-1); </script>\n";

    }
	
    $query = 'DELETE FROM #__contactformmaker_sessions'

    . ' WHERE group_id IN ( '. $cids .' )'

    ;

    // Execute query

    $db->setQuery( $query );

    if (!$db->query()) {

      echo "<script> alert('".$db->getErrorMsg(true)."'); 

      window.history.go(-1); </script>\n";

    }
	
	

  }

  // After all, redirect again to frontpage

  $mainframe->redirect( "index.php?option=com_contactformmaker&task=submits&form_id=".$form_id );


}

function blockIP(){



	$mainframe = JFactory::getApplication();

	$db		= JFactory::getDBO();

	$id 	= JRequest::getVar('id');




  $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );



  // Make sure cid array variable content integer format



  JArrayHelper::toInteger($cid);



  // If any item selected



  if (count( $cid )) {

    $cids = implode( ',', $cid );


    // Create sql statement


    $query = 'SELECT * FROM #__contactformmaker_submits'


    . ' WHERE group_id IN ( '. $cids .' )';

			$db->setQuery($query); 
						
		$rows = $db->loadObjectList();	


    if($db->getErrorNum()){	echo $db->stderr();	return false;}
	


			foreach($rows as $row)
			{
				
				$query = 'SELECT ip FROM #__contactformmaker_blocked WHERE ip="'.($row->ip).'"';
				$db->setQuery($query); 			
				$ips = $db->loadObjectList();

				if (!$ips)
				{
				
					$query = "INSERT INTO #__contactformmaker_blocked (ip) VALUES('".$row->ip."')" ;
					$db->setQuery( $query);
					$db->query();
					if($db->getErrorNum()){	echo $db->stderr();	return false;}		
					
				}
				
			}


	}


		$link ='index.php?option=com_contactformmaker&task=submits';

	
	$mainframe->redirect($link);
	
	
}

function remove_blocked_ips(){


  $mainframe = JFactory::getApplication();


  // Initialize variables	



  $db = JFactory::getDBO();



  // Define cid array variable



  $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );



  // Make sure cid array variable content integer format



  JArrayHelper::toInteger($cid);







  // If any item selected



  if (count( $cid )) {



    // Prepare sql statement, if cid array more than one, 



    // will be "cid1, cid2, ..."



    $cids = implode( ',', $cid );



    // Create sql statement



    $query = 'DELETE FROM #__contactformmaker_blocked' . ' WHERE id IN ( '. $cids .' )'  ;



    // Execute query



    $db->setQuery( $query );



    if (!$db->query()) {



      echo "<script> alert('".$db->getErrorMsg(true)."'); 



      window.history.go(-1); </script>\n";



    }




  }


  // After all, redirect again to frontpage


  $mainframe->redirect( "index.php?option=com_contactformmaker&task=blocked_ips" );



}



function remove(){



  $mainframe = JFactory::getApplication();

  // Initialize variables	

  $db = JFactory::getDBO();

  // Define cid array variable

  $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );

  // Make sure cid array variable content integer format

  JArrayHelper::toInteger($cid);



  // If any item selected

  if (count( $cid )) {

    // Prepare sql statement, if cid array more than one, 

    // will be "cid1, cid2, ..."

    $cids = implode( ',', $cid );

    // Create sql statement

    $query = 'DELETE FROM #__contactformmaker' . ' WHERE id IN ( '. $cids .' )'  ;

    // Execute query

    $db->setQuery( $query );

    if (!$db->query()) {

      echo "<script> alert('".$db->getErrorMsg(true)."'); 

      window.history.go(-1); </script>\n";

    }
	
    $query = 'DELETE FROM #__contactformmaker_views' . ' WHERE form_id IN ( '. $cids .' )'  ;

    // Execute query

    $db->setQuery( $query );

    if (!$db->query()) {

      echo "<script> alert('".$db->getErrorMsg(true)."'); 

      window.history.go(-1); </script>\n";

    }
	

  }

	remove_submits_all( $cids );

  // After all, redirect again to frontpage

  $mainframe->redirect( "index.php?option=com_contactformmaker" );

}

function remove_submits_all( $cids ){
  $db = JFactory::getDBO();
	$query = 'DELETE FROM #__contactformmaker_submits'

    . ' WHERE form_id IN ( '. $cids .' )'

    ;

    // Execute query

    $db->setQuery( $query );

    if (!$db->query()) {

      echo "<script> alert('".$db->getErrorMsg(true)."'); 

      window.history.go(-1); </script>\n";

    }

}

function change( $state=0 ){

  $mainframe = JFactory::getApplication();



  // Initialize variables

  $db 	= JFactory::getDBO();



  // define variable $cid from GET

  $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );	

  JArrayHelper::toInteger($cid);



  // Check there is/are item that will be changed. 

  //If not, show the error.

  if (count( $cid ) < 1) {

    $action = $state ? 'publish' : 'unpublish';

    JError::raiseError(500, JText::_( 'Select an item 

    to' .$action, true ) );

  }



  // Prepare sql statement, if cid more than one, 

  // it will be "cid1, cid2, cid3, ..."

  $cids = implode( ',', $cid );



  $query = 'UPDATE #__contactformmaker'

  . ' SET published = ' . (int) $state

  . ' WHERE id IN ( '. $cids .' )'

  ;

  // Execute query

  $db->setQuery( $query );

  if (!$db->query()) {

    JError::raiseError(500, $db->getErrorMsg() );

  }



  if (count( $cid ) == 1) {

    $row = JTable::getInstance('contactformmaker', 'Table');

    $row->checkin( intval( $cid[0] ) );

  }



  // After all, redirect to front page

  $mainframe->redirect( 'index.php?option=com_contactformmaker' );

}

function cancel(){

  $mainframe = JFactory::getApplication();

  $mainframe->redirect( 'index.php?option=com_contactformmaker&task=forms' );



}

function cancel_themes(){

  $mainframe = JFactory::getApplication();
  $mainframe->redirect( 'index.php?option=com_contactformmaker&task=themes' );
}

function cancel_blocked_ips(){
  $mainframe = JFactory::getApplication();
  $mainframe->redirect( 'index.php?option=com_contactformmaker&task=blocked_ips' );
}
	


function cancelSecondary(){

	$mainframe = JFactory::getApplication();

	if(JRequest::getVar('id')==0)

	$link = 'index.php?option=com_contactformmaker&task=add';

	else

	$link = 'index.php?option=com_contactformmaker&task=edit&cid[]='.JRequest::getVar('id');

	$mainframe->redirect($link);

}

function cancelSubmit(){
	$mainframe = JFactory::getApplication();
	$link = 'index.php?option=com_contactformmaker&task=submits';
	$mainframe->redirect($link);

}
		
function select_article(){


	$mainframe = JFactory::getApplication();
	
	    $db = JFactory::getDBO();

	$option='com_contactformmaker';

	$filter_order= $mainframe-> getUserStateFromRequest( $option.'filter_order', 'filter_order','id','cmd' );
	$filter_order_Dir= $mainframe-> getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir','','word' );
	$filter_state = $mainframe->getUserStateFromRequest( $option.'filter_state', 'filter_state', '','word' );
	$search = $mainframe-> getUserStateFromRequest( $option.'search', 'search','','string' );
	$search = JString::strtolower( $search );
	$limit= $mainframe-> getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
	$limitstart= $mainframe-> getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');

	$where = array();

	if ( $search ) {

		$where[] = 'title LIKE "%'.$db->escape($search).'%"';

	}	

	

	$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

	if ($filter_order == 'id' or $filter_order == 'group_id' or $filter_order == 'group_id' or $filter_order == 'date' or $filter_order == 'ip'){

		$orderby 	= ' ORDER BY id';

	} else {

		$orderby 	= ' ORDER BY '. 

         $filter_order .' '. $filter_order_Dir .', id';

	}	

	

	// get the total number of records

	$query = 'SELECT COUNT(*)'

	. ' FROM #__content'

	. $where

	;

	$db->setQuery( $query );

	$total = $db->loadResult();



	jimport('joomla.html.pagination');

	$pageNav = new JPagination( $total, $limitstart, $limit );	

	$query = "SELECT * FROM #__content". $where. $orderby;

	$db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );

	$rows = $db->loadObjectList();

	if($db->getErrorNum()){

		echo $db->stderr();

		return false;

	}



	// table ordering

	$lists['order_Dir']	= $filter_order_Dir;

	$lists['order']		= $filter_order;	



	// search filter	

        $lists['search']= $search;	

	

    // display function

	HTML_contact::select_article($rows, $pageNav, $lists);

}
?>