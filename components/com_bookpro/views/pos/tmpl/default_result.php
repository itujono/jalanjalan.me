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
$this->roundtrip = JFactory::getApplication()->getUserStateFromRequest('filter.roundtrip','filter_roundtrip', 0 );
$this->start =JFactory::getApplication()->getUserStateFromRequest('filter.start', 'filter_start' );
$this->adults=JFactory::getApplication()->getUserStateFromRequest('filter.adult','filter_adult', array(),'array' );

$this->bustrip_id= JFactory::getApplication()->getUserStateFromRequest ( 'filter.bustrip_id', 'bustrip_id',null);


$this->total_pax=0;
foreach ($this->adults as $key=>$value) {
	$this->total_pax+=$value;
}

$this->start = DateHelper::createFromFormat ($this->start )->format ('Y-m-d');
	
if($this->roundtrip==1) {
	$this->end = JFactory::getApplication ()->getUserStateFromRequest ( 'filter.end', 'filter_end' );
	$this->end = DateHelper::createFromFormat ( $this->end )->format ( 'Y-m-d' );
}
$model=new BookProModelBustrips();

$state = $model->getState( );
$state->set ( 'filter.depart_date', $this->start );

if($this->bustrip_id){
		
	$state->set ( 'filter.ids', implode(',', array($this->bustrip_id )));
		
}else {
		
	$state->set ( 'filter.from', $from );
	$state->set ( 'filter.to', $to );
		
}


if (JFactory::getDate ()->format ( 'Y-m-d' ) ==  $this->start ) {
		
	$state->set ( 'filter.cutofftime', $this->config->get ( 'cutofftime' ) );
}

$going_trip = $model->getItems();

$this->going_trips = $going_trip;

//var_dump($this->going_trips);

if ($this->roundtrip==1) {
		
		
	$model=new BookProModelBustrips();
	$state = $model->getState();
	$state->set ( 'filter.depart_date', $this->end );
	$state->set ( 'filter.from', $to );
	$state->set ( 'filter.to', $from );
	if (JFactory::getDate ()->format ( 'Y-m-d' ) ==  $this->start ) {
		$state->set ( 'filter.cutofftime', $this->config->get ( 'cutofftime' ) );
	}
	$return_trips = $model->getItems();
	$this->return_trips = $return_trips;
}
//$this->from_to = BusHelper::getRoutePair ( $from, $to );


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
	
		<div class="well well-small" style="text-align: center;">
				<span><?php echo JText::sprintf('COM_BOOKPRO_BUS_FROM_TO',$from_title,$to_title)?></span> -
				<?php echo JFactory::getDate($this->start)->format('D, d M Y'); ?>
		</div>

		<div id="tabs">
			<!-- Display oneway trip -->
			<?php
			
			echo JLayoutHelper::render($departlayout,$this->going_trips,$basePath = JPATH_ROOT . '/components/com_bookpro/layouts/bus',array('value'=>1));
			?>
		</div>
		<?php 
		
		?>
		<p></p>

		<?php 
		
		if ($this->roundtrip==1) {?>
		<div class="well well-small" style="text-align: center;">
				<span><?php echo JText::sprintf('COM_BOOKPRO_BUS_FROM_TO',$to_title,$from_title)?>
				</span> -
			<?php echo JFactory::getDate($this->end)->format('D, d M Y'); ?>
		</div>

		<div id="tabs_return">
			<!-- Display return trip -->
			
			<?php echo JLayoutHelper::render($returnlayout,$this->return_trips,$basePath = JPATH_ROOT . '/components/com_bookpro/layouts/bus');
			
			?>
		</div>
		<?php } ?>
		
		
		
		<input type="hidden" name="seat" value="" id="order_seat" />
		<input type="hidden" name="return_seat" id="order_return_seat" value="" />
	
</div>
<script type="text/javascript">


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


