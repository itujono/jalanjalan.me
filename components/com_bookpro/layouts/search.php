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
$document=JFactory::getDocument();

$config_page = $params->get ('Itemid');
$Itemid=$config_page?$config_page:$input->get('Itemid');

JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework');
$lang=JFactory::getLanguage();
$local=substr($lang->getTag(),0,2);
if($local=="en")
	$local="en-GB";


$config=JComponentHelper::getParams('com_bookpro');

$js_format=DateHelper::getConvertDateFormat('B');
$date_format=DateHelper::getConvertDateFormat('P');

$default_search=$params->get('roundtrip',0);
$document->addScript(JURI::root().'components/com_bookpro/assets/js/bootstrap-datepicker.js');
$document->addScript(JURI::root().'components/com_bookpro/assets/js/locales/bootstrap-datepicker.'.$local.'.js');
$document->addStyleSheet(JURI::root().'components/com_bookpro/assets/css/datepicker.css');
$document->addScript(JURI::root().'components/com_bookpro/assets/js/bootstrap-switch.min.js');
$document->addStyleSheet(JURI::root().'components/com_bookpro/assets/css/bootstrap-switch.min.css');
$document->addStyleSheet(JURI::root().'modules/mod_jbbus_bussearch/assets/style.css');

$from=JFactory::getApplication()->getUserState('filter.from');
$to=JFactory::getApplication()->getUserState('filter.to',null);
$roundtrip=JFactory::getApplication()->getUserStateFromRequest('filter.roundtrip','filter_roundtrip',$default_search);
$start=JFactory::getApplication()->getUserState('filter.start');
$end=JFactory::getApplication()->getUserState('filter.end',null);
$adult = JFactory::getApplication()->getUserStateFromRequest ( 'filter.adult', 'filter_adult', 1 );
$child = JFactory::getApplication()->getUserStateFromRequest ( 'filter.child', 'filter_child', 0 );
$senior = JFactory::getApplication()->getUserStateFromRequest ( 'filter.senior', 'filter_senior',0 );

$from_select=modBusHelper::createDestinationSelectBox($from,'class="input-medium"');

if($to){
	$desto=modBusHelper::getArrivalDestination('filter_to',$to,$from);
}
else{
	$desto=JHtmlSelect::genericlist(array(), 'filter_to','class="input-medium"');
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
?>
<script lang="text/javascript">
	jQuery(document).ready(function($) {
		$("[name='roundtrip']").bootstrapSwitch();

		$('input[name="roundtrip"]').on('switchChange.bootstrapSwitch', function(event, state) {
			  console.log(state);
				if(state) {
					$("[name='filter_roundtrip']").attr('value','1');
					$("#busFrm #returnDate").show();
				}
					else {
						$("[name='filter_roundtrip']").attr('value','0');
						$("#busFrm #returnDate").hide();
					}
			  
			});
		$('.start_date').datepicker({
			format: "<?php echo $js_format ?>",
		    autoclose: true,
		    startDate: "new Date()",
		    endDate: "+12m"
		});
  });
  </script>
  <h1>andhana ganteng</h1>
<form name="busSearchForm" method="post" action="<?php echo JRoute::_('index.php?option=com_bookpro&view=bustrips') ?> " id="busFrm"
	onsubmit="return validateBusSearch();">
	
		
				<div class="row-fluid form-inline">
								
								<input class="input" type="checkbox" data-off-text="<?php echo JText::_('MOD_JBBUS_BUS_ONEWAY') ?>" data-on-text="<?php echo JText::_('MOD_JBBUS_BUS_ROUNDTRIP') ?>" name="roundtrip" <?php if($roundtrip) echo 'checked' ?>/>
								<?php echo $from_select ?>
								<?php echo $desto ?>	
										
						<div class="input-prepend input-append date start_date">
						<span class="add-on"><?php  echo JText::_('MOD_JBBUS_BUS_DEPART') ?></span>
 					  <input type="text" class="input-small" name="filter_start" id="start" value="<?php echo $start  ?>"><span class="add-on"><i class="icon-th"></i></span>
	 		 			</div>
	 		 			
	 		 			<div class="input-prepend input-append date start_date" id="returnDate">
					 <span class="add-on"><?php  echo JText::_('MOD_JBBUS_BUS_RETURN') ?></span>
 					  <input type="text" class="input-small" name="filter_end" id="end" value="<?php echo $end	?>"><span class="add-on"><i class="icon-th"></i></span>
					  </div>
											
						
						<div class="inline">
						<?php echo JText::_('MOD_JBBUS_BUS_ADULT') ?><br/>
						<?php echo JHtmlSelect::integerlist(0, 10, 1, 'filter_adult','class="input-mini" id="search_adult"',$adult)?>
						</div>
						
						<div class="inline">
						<?php 
						if($params->get('child')){
							echo JText::_('MOD_JBBUS_BUS_CHILD')."<br/>" ;
							echo JHtmlSelect::integerlist(0, 10, 1, 'filter_child','class="input-mini" id="search_child"',$child);
						}
						?>
						</div>
						<div class="inline">
						<?php 						
						if($params->get('senior')){
							echo JText::_('MOD_JBBUS_BUS_SENIOR')."<br/>" ;
							echo JHtmlSelect::integerlist(0, 10, 1, 'filter_senior','class="input-mini" id="search_senior"',$senior);
						}
						?>
						
						
							<button class="btn btn-primary"  type="submit">
								<i class="icon-search"></i>
								<?php echo JText::_('MOD_JBBUS_BUS_SEARCH')?>
							</button>
							
								
							
					</div>
			
		
	
	<input type="hidden" name="option" value="com_bookpro" />  
	<input type="hidden" name="filter_roundtrip" value="<?php echo $roundtrip ?>" />
	<input type="hidden" name="Itemid"	value="<?php echo $Itemid ?>" />
	<?php echo JHtml::_('form.token')?>
</form>
<script type="text/javascript">

 jQuery(document).ready(function($) {
		 <?php if($default_search==1) {?>
		 $("#busFrm #returnDate").show();
		<?php } else {?>
		$("#busFrm #returnDate").hide();
		<?php } ?>
		
		$("#busFrm input:radio[name=filter_roundtrip]").change(function(){
			if($("#busFrm input:radio[name=filter_roundtrip]:checked").val()==0)
				$("#busFrm #returnDate").hide();
			if($("#busFrm input:radio[name=filter_roundtrip]:checked").val()==1) 
				$("#busFrm #returnDate").show();
		});
		


	$("#busFrm #filter_from").change(function(){
			$.ajax({
				type:"GET",
				url: "index.php?option=com_bookpro&controller=bus&task=findDestination&format=raw",
				data:"desfrom="+jQuery(this).val(),
				beforeSend : function() {
					$("#busFrm select#filter_to")
							.html('<option><?php echo JText::_('MOD_BOOKPRO_LOADING') ?></option>');
				},
				success:function(result){
						$("#busFrm select#filter_to").html(result);
					}
				});
		});
	
});

  function validateBusSearch(){	
		var form= document.busSearchForm;
		var end= form.end;

		
		if(!form.filter_from.value){
			alert('<?php echo JText::_('MOD_JBBUS_BUS_SELECT_DEPARTURE_WARN')?>');
			form.filter_from.focus();
			return false ;
		}
		if(!form.filter_to.value){
			alert('<?php echo JText::_('MOD_JBBUS_BUS_SELECT_RETURN_WARN')?>');
			form.filter_to.focus();
			return false;
		
		}
		var total=jQuery('#search_adult').val()+jQuery('#search_child').val()+jQuery('#search_senior').val();
		if(total==0){

			alert('<?php echo JText::_('MOD_JBBUS_BUS_SELECT_PASSENGER_WARN')?>');
			return false;
		}
		
		if(form.filter_start.value==""){
			alert('<?php echo JText::_('MOD_JBBUS_BUS_DEPART_DATE_WARN')?>');
			return false;
		}
		if(form.filter_end.value==""){
			alert('<?php echo JText::_('MOD_JBBUS_BUS_RETURN_DATE_WARN')?>');
			return false;
		}
		form.submit();
	}


</script>
