<?php 
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bookpro.php 27 2012-07-08 17:15:11Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');
?>

<form name="busSearchForm" method="get" action="<?php echo JRoute::_('index.php?option=com_bookpro&view=bustrips') ?> " id="busFrm"
	onsubmit="return validateBusSearch();" class="form-inline">
		
								
								<?php $data [] = JHtmlSelect::option ( '0', JText::_ ( 'MOD_JBBUS_BUS_ONEWAY' ) );
	$data [] = JHtmlSelect::option ( '1', JText::_ ( 'MOD_JBBUS_BUS_ROUNDTRIP' ) );
	echo BookProHelper::radiolist ( $data, 'filter_roundtrip', '', 'value', 'text', $default_search, 'roundtrip' ); ?>
								
								
								
								<div class="form-group">
		<div class="input-group">


			<div class="input-group-addon"><i class="fa fa-map-marker"
				aria-hidden="true"></i></div>
 		 <?php echo $from_select?>
 	 </div>
	</div>


	<div class="form-group">
		<div class="input-group">
			<div class="input-group-addon"><i class="fa fa-map-marker"
				aria-hidden="true"></i></div>
 		 <?php echo $desto?>
 	 </div>
	</div>
										
						<div class="form-group">
	<div class="input-group date start_date">
		<div class="input-group-addon"><?php  echo JText::_('MOD_JBBUS_BUS_DEPART') ?></div>
		<input type="text" class="form-control" name="filter_start" id="start"
			value="<?php echo $start  ?>"><div class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></div>
	</div>
	</div>

	
	<div class="form-group" id="returnDate">
	<div class="input-group date end_date" >
		<div class="input-group-addon"><?php  echo JText::_('MOD_JBBUS_BUS_RETURN') ?></div>
		<input type="text" class="form-control" name="filter_end" id="end"
			value="<?php echo $end	?>"><div class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></div>
	</div>
	</div>
						
					<?php 
						$results=array();
						$i=0;
						foreach ( $types as $type ) {
							if($i==0)
								$type->selected=$type->selected?$type->selected:1;
							echo "<label>".$type->title."</label>" ;
							echo JHtmlSelect::integerlist(0, 10, 1, 'filter_adult['.$type->id.']','class="input-mini" id="search_child"',$type->selected);
							$i++;
						}
		
					 ?>
							<button class="btn btn-primary"  type="submit">
								<i class="icon-search"></i>
								<?php echo JText::_('MOD_JBBUS_BUS_SEARCH')?>
							</button>
							
							
					
	
	<input type="hidden" name="Itemid"	value="<?php echo $Itemid ?>" />
	<input type="hidden" name="option"	value="com_bookpro" />
	<input type="hidden" name="view"	value="bustrips" />
	
	<?php echo JHtml::_('form.token')?>
</form>
<script type="text/javascript">

						
jbdatepicker(date_format);

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
		if(jQuery("input:radio[name=filter_roundtrip]:checked").val()==1) {
		if(form.filter_end.value==""){
			alert('<?php echo JText::_('MOD_JBBUS_BUS_RETURN_DATE_WARN')?>');
			return false;
		}

		var start = jQuery('.start_date').datepicker('getDate');
		var end = jQuery('.end_date').datepicker('getDate');

		if(start>end){

			alert('<?php echo JText::_('MOD_JBBUS_BUS_RETURN_DATE_WARN')?>');
			return false;
		}

		
		}
		form.submit();
	}


</script>
