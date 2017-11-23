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

$js_format=DateHelper::getConvertDateFormat('B');
$lang=JFactory::getLanguage();
$local=substr($lang->getTag(),0,2);
if($local=="en")
	$local="en-GB";
$date_format=DateHelper::getConvertDateFormat('P');
$js="var date_format='".$js_format."';";
$js.="var local='".$local."';";
$document=JFactory::getDocument();
$document->addScriptDeclaration($js);

$input=JFactory::getApplication()->input;

$start=JFactory::getApplication()->getUserStateFromRequest('filter.start','filter_start');
$end=JFactory::getApplication()->getUserStateFromRequest('filter.end','filter_end');
$to=$input->get('filter_to');
$default_search=$input->get('filter_roundtrip',0);
$from=$input->get('filter_from');

if($to){
	$desto=$this->getArrivalDestination('filter_to',$to,$from);
}
else{
	$desto=JHtmlSelect::genericlist(array(), 'filter_to','class="input-medium"');
}





?>

<form action="<?php echo JRoute::_("index.php?option=com_bookpro&view=pos")?>" method="POST" name="searchform" id="searchform">
<div class="well well-small">
		
				<div class="<?php echo BookProHelper::bsrow()?> form-inline">
								
					<?php echo  JHtmlSelect::booleanlist('filter_roundtrip','',$input->get('filter_roundtrip',0),JText::_('COM_BOOKPRO_ROUNDTRIP'),JText::_('COM_BOOKPRO_ONEWAY'),'roundtrip'); ?>
					<?php echo $this->createDestinationSelectBox($input->get('filter_from')) ?>
					<?php echo $desto ?>	
										
					<div class="input-prepend input-append date start_date">
							<span class="add-on"><?php  echo JText::_('COM_BOOKPRO_DEPART') ?></span>
 					  <input type="text" class="input-small" name="filter_start" id="start" value="<?php echo $start  ?>"><span class="add-on"><i class="icon-th"></i></span>
	 		 		</div>
	 		 			
	 		 			<div class="input-prepend input-append date end_date" id="returnDate">
					 <span class="add-on"><?php  echo JText::_('COM_BOOKPRO_RETURN') ?></span>
 					  <input type="text" class="input-small" name="filter_end" id="end" value="<?php echo $end	?>"><span class="add-on"><i class="icon-th"></i></span>
					  </div>
					  
					  
						
						<?php 
						$results=array();
						$i=0;
						foreach ( $this->types as $type ) {
							
							echo "<label>".$type->title."</label>" ;
							echo JHtmlSelect::integerlist(0, 10, 1, 'filter_adult['.$type->id.']','class="input-mini" id="search_child"',$type->selected);
							$i++;
						}
		
					 ?>
						
						
						
							<button class="btn btn-primary"  type="button" id="search_id">
								<i class="icon-search"></i>
								<?php echo JText::_('COM_BOOKPRO_SEARCH')?>
							</button>
							
				</div>
	
			<input type="hidden" name="check" value="1"/>
	
		</div>
		</form>
	
	<script type="text/javascript">

						
	jbdatepicker(date_format);

 	jQuery(document).ready(function($) {
		 <?php if($default_search==1) {?>
		 $("#returnDate").show();
		<?php } else {?>
		$("#returnDate").hide();
		<?php } ?>
		
		$("input:radio[name=filter_roundtrip]").change(function(){
			if($("input:radio[name=filter_roundtrip]:checked").val()==0)
				$("#returnDate").hide();
			if($("input:radio[name=filter_roundtrip]:checked").val()==1) 
				$("#returnDate").show();
		});
		

	$("#search_id").click(function(){

		validateBusSearch();
		
		}
	);
		
	$("#filter_from").change(function(){
			$.ajax({
				type:"GET",
				url: "index.php?option=com_bookpro&controller=bus&task=findDestination&format=raw",
				data:"desfrom="+jQuery(this).val(),
				beforeSend : function() {
					$("select#filter_to")
							.html('<option><?php echo JText::_('COM_BOOKPRO_LOADING') ?></option>');
				},
				success:function(result){
						$("select#filter_to").html(result);
					}
				});
		});
	
});

  function validateBusSearch(){	
		var form= document.searchform;
		
		if(!form.filter_from.value){
			alert('<?php echo JText::_('COM_BOOKPRO_SELECT_DEPARTURE_WARN')?>');
			form.filter_from.focus();
			return false ;
		}
		if(!form.filter_to.value){
			alert('<?php echo JText::_('COM_BOOKPRO_SELECT_RETURN_WARN')?>');
			form.filter_to.focus();
			return false;
		
		}
		
		
		if(form.filter_start.value==""){
			alert('<?php echo JText::_('COM_BOOKPRO_DEPART_DATE_WARN')?>');
			return false;
		}
		
		form.submit();
	}


</script>
