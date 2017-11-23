<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bookpro.php 27 2012-07-08 17:15:11Z quannv $
 **/

defined('_JEXEC') or die;

if(version_compare(PHP_VERSION,'5.3.0')==-1){
	echo 'Need PHP version 5.3.0, your current version: ' . PHP_VERSION . "\n";
	return;
}
$input=JFactory::getApplication()->input;
require_once JPATH_SITE.'/modules/mod_jbbus_bussearch/helper.php';
require_once JPATH_ADMINISTRATOR.'/components/com_bookpro/helpers/date.php';
require_once JPATH_ADMINISTRATOR.'/components/com_bookpro/helpers/factory.php';
require_once JPATH_ADMINISTRATOR.'/components/com_bookpro/helpers/bookpro.php';
$document=JFactory::getDocument();


$config_page = $params->get ('Itemid');
$Itemid=$config_page?$config_page:$input->get('Itemid');

JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework');

$lang=JFactory::getLanguage();
$local=substr($lang->getTag(),0,2);
if($local=="en")
	$local="en-GB";


// $config=JComponentHelper::getParams('com_bookpro');
// echo "<pre>";
// print_r($config);
// exit;
$js_format=DateHelper::getConvertDateFormat('B');

$date_format=DateHelper::getConvertDateFormat('P');

$default_search=$params->get('roundtrip',0);


$js="var date_format='".$js_format."';";
$js.="var local='".$local."';";

$document->addScriptDeclaration($js);
$document->addScript(JURI::root().'components/com_bookpro/assets/js/bootstrap-datepicker.js');
$document->addScript(JURI::root().'components/com_bookpro/assets/js/locales/bootstrap-datepicker.'.$local.'.js');
$document->addStyleSheet(JURI::root().'components/com_bookpro/assets/css/datepicker.css');
$document->addStyleSheet(JURI::root().'modules/mod_jbbus_bussearch/assets/select2.min.css');
$document->addStyleSheet(JURI::root().'modules/mod_jbbus_bussearch/assets/style.css');

JHtml::script(JURI::root().'modules/mod_jbbus_bussearch/assets/search.js');
$document->addScript(JURI::root().'modules/mod_jbbus_bussearch/assets/search.js');

// echo '<script src="'.JURI::root().'modules/mod_jbbus_bussearch/assets/search.js'.'" type="text/javascript"></script>';

$types_qty= JFactory::getApplication()->getUserStateFromRequest ( 'filter.adult', 'filter_adult', array(),'array' );

$from=JFactory::getApplication()->getUserState('filter.from','');
$to=JFactory::getApplication()->getUserState('filter.to','');
$default_search=JFactory::getApplication()->getUserStateFromRequest('filter.roundtrip','filter_roundtrip',$default_search);

$bustype=modBusHelper::getBusType(JFactory::getApplication()->getUserStateFromRequest('filter.bus_id','filter_bus_id',0));

$start=JFactory::getApplication()->getUserState('filter.start');

$end=JFactory::getApplication()->getUserState('filter.end',null);

$types=modBusHelper::getPaxOptions();
foreach ($types as $type){
	$type->selected=0;
	if(count($types_qty)>0){
		foreach ($types_qty as $key=>$value) {
			if($type->id== $key ){
				$type->selected=$value;

			}

		}

	}

}

$from_select=modBusHelper::createDestinationSelectBox($from,'class="input-medium form-control js-example"');

if($to){
	$desto=modBusHelper::getArrivalDestination('filter_to',$to,$from);

}
else{
	$desto=JHtmlSelect::genericlist(array(), 'filter_to','class="input-medium form-control"');
}

$today = JFactory::getDate('now');

if(!$start) {

	$today->add(new DateInterval('P1D'));
	$start= $today->format($date_format);
}

if(!$end){
	$today->add(new DateInterval('P2D'));
	$end= $today->format($date_format);
}
require JModuleHelper::getLayoutPath('mod_jbbus_bussearch', 'custom_layout');
