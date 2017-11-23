<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );
require_once JPATH_COMPONENT_SITE.'/models/bustrips.php' ;
AImporter::helper ( 'form' );

AImporter::js('view-bustrips','jquery-create-seat','jquery.session','jquery.tablesorter.min','jquery.tablesorter.widgets.min');
AImporter::css ( 'bus', 'jbbus','jquery-create-seat','view-bustrips','theme.bootstrap');

$app = JFactory::getApplication ();
$input=$app->input;


$from = JFactory::getApplication()->getUserStateFromRequest('filter.from','filter_from' );
$to = JFactory::getApplication()->getUserStateFromRequest('filter.to', 'filter_to', null );
//$this->roundtrip = JFactory::getApplication()->getUserStateFromRequest('filter.roundtrip','filter_roundtrip', 0 );
$this->start =JFactory::getApplication()->getUserStateFromRequest('filter.start', 'filter_start',$this->date );
//$this->adults=JFactory::getApplication()->getUserStateFromRequest('filter.adult','filter_adult', array(),'array' );

$this->adults=JFactory::getApplication()->getUserStateFromRequest('filter.adult','filter_adult',array('1'=>1),'array' );


$this->total_pax=0;
foreach ($this->adults as $key=>$value) {
	$this->total_pax+=$value;
}

//$this->adults=JFactory::getApplication()->getUserStateFromRequest('filter.adult','filter_adult',array('1'=>1),'array' );


$this->start =  ($this->date );

//echo $this->start;


$model=new BookProModelBustrips();

$state = $model->getState( );
$state->set ( 'filter.depart_date', $this->date );
$state->set ( 'filter.ids', implode(',', array($this->route->id) ));






$going_trip = $model->getItems();

$this->going_trips = $going_trip;


$this->from_to = BusHelper::getRoutePair ( $this->route->from, $this->route->to );


// No direct access to this file


$lang = JFactory::getLanguage ();
$local = substr ( $lang->getTag (), 0, 2 );
$doc = JFactory::getDocument ();

$doc->addScriptDeclaration ( '
		var adult=' . ($this->total_pax) . ';
			var msg_select_again="' . JText::sprintf ( "COM_BOOKPRO_SELECT_AGAIN", $this->total_pax ) . '";
		' );

$today = JFactory::getDate ()->getTimestamp ();
$from_title = $this->from_to [0]->title;
$to_title = $this->from_to [1]->title;

$noseat=$this->config->get('no_seat');
$departlayout=$noseat?'depart_noseat':'depart';
$returnlayout=$noseat?'return_noseat':'return';



?>

<div class="bustrip_form">
	
	

		<div id="tabs">
			<!-- Display oneway trip -->
			<?php
			
			echo JLayoutHelper::render($departlayout,$this->going_trips,$basePath = JPATH_ROOT . '/components/com_bookpro/layouts/bus',array('value'=>1));
		
			?>
		</div>
		<?php 
		
		
		
		?>
		<p></p>

	
		
		
	<input type="hidden" name="bustrip_id" value="<?php echo $this->route->id ?>"  />
		<input type="hidden" name="seat" value="" id="order_seat" />
		<input type="hidden" name="return_seat" id="order_return_seat" value="" />
		
		<input type="hidden" name="filter_start" value="<?php echo $this->start ?>"/>
	
</div>
<script type="text/javascript">

jQuery('.tr_viewseat').show();
jQuery('.busitem').find('.radio_bus').prop('checked', true);

function checkBoarding(name,id){
if (jQuery('#'+name+'boarding'+id).length)

	if(jQuery('#'+name+'boarding'+id).val()==0){

		return false;
	}


if (jQuery('#'+name+'dropping'+id).length)

	if(jQuery('#'+name+'dropping'+id).val()==0){

		return false;
	}

	return true;
}




function submitForm(){
	
	var no_seat= <?php echo $this->config->get("no_seat",0) ?>;
	var form= document.bustrip_form;
	if(jQuery("input:radio[name='bustrip_id']").is(":checked")==false)
	{
		alert("<?php echo JText::_('COM_BOOKPRO_SELECT_BUSTRIPS_WARN')?>");
	 		return false; 
	 		
	}
	if(jQuery("input:radio[name='return_bustrip_id']").is("*")){
		if(jQuery("input:radio[name='return_bustrip_id']").is(":checked")==false)
		{
			alert("<?php echo JText::_('COM_BOOKPRO_SELECT_BUSTRIPS_WARN')?>");
		 		return false; 
		}


		var id=jQuery("input:radio[name=return_bustrip_id]:checked").val();
		if(!checkBoarding('return_',id)){
			
			alert("<?php echo JText::_('COM_BOOKPRO_SELECT_BOARDING_DROPPING_WARN')?>");
			return false; 

		}

		
	}
	var id=jQuery("input:radio[name=bustrip_id]:checked").val();
	if(!checkBoarding('',id)){
		
		alert("<?php echo JText::_('COM_BOOKPRO_SELECT_BOARDING_DROPPING_WARN')?>");
		return false; 

	}


	
	
	var stop=0;
	jQuery("input:radio[name='bustrip_id'],input:radio[name='return_bustrip_id']").each(function () {

		if(no_seat==0){
		if(jQuery(this).is(":checked"))
		{
			var tr_viewseat=jQuery(this).closest('.busitem').next('.tr_viewseat');
			if(tr_viewseat.find('.bodybuyt .choose').length<adult)
			{
				alert('<?php echo JText::_('COM_BOOKPRO_SELECT_SEAT_WARN') ?>');
				stop=1;
				tr_viewseat.find('.bodybuyt').focus();
				return false;
				
			}
		}
		}
	});
	
	
	if(stop==1)
	{
		return false;
	}	
	form.submit();
}
</script>


