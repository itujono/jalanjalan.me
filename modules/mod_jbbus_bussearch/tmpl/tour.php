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
$document->addScript('https://use.fontawesome.com/0fb85aa764.js');

$db = JFactory::getDbo ();
$query = $db->getQuery ( true );
$query->select ( '*' );
$query->from ( '#__bookpro_bustrip AS b' );
$query->where('b.state=1');
$sql = ( string ) $query;
$db->setQuery ( $sql );
$items = $db->loadObjectList ();

$tours = JHtml::_ ( 'select.genericlist', $items, 'bustrip_id', 'class="span12 form-control"', 'id', 'title',0, false );




?>



<form name="busSearchForm" method="post" action="<?php echo JRoute::_('index.php?option=com_bookpro&view=bustrips') ?> " id="busFrm"
	onsubmit="return validateBusSearch();">
				<div class="container form-inline">
							
						<?php echo $tours; ?>			
										
						
					<label>	<?php  echo JText::_('MOD_JBBUS_BUS_DEPART') ?></label>
						<div class="input-append date start_date">
							
 					   <input type="text" class="input-small" name="filter_start" id="start" value="<?php echo $start  ?>" readonly="readonly"><span class="add-on">
 					   <i class="fa fa-calendar" aria-hidden="true"></i>
 					   </span>
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
							
							
							
							
					
			</div>
	
	<input type="hidden" name="option" value="com_bookpro" />  
	<input type="hidden" name="Itemid"	value="<?php echo $Itemid ?>" />
	
	<?php echo JHtml::_('form.token')?>
</form>
<script type="text/javascript">

						
jbdatepicker(date_format);

 jQuery(document).ready(function($) {



	  jQuery.ajax({
			type:"GET",
			url: "index.php?option=com_bookpro&task=getdepart_date&format=raw",
			data:"bustrip_id="+jQuery('#bustrip_id').val(),
			success:function(result){
					
					var array = JSON.parse(result);
					jQuery('.start_date').datepicker({
						
						startDate: "",
					    endDate: "+2m",
					    autoclose: true,
					    language: local,
						format: date_format,
						beforeShowDay: function(date) {            
			                dmy = addLeadingZero(date.getDate()) + "-" + addLeadingZero((date.getMonth()+1)) + "-" + date.getFullYear();
				            var check=jQuery.inArray(dmy, array);
				            //console.log(check);
					           if(check==-1){
				               	 return {
			                         enabled : false
			                      };
					           }
			               }
			          		
						});
					//	jQuery('.start_date').datepicker('show');
				}
			});
		
		
	
});

  function validateBusSearch(){	
		var form= document.busSearchForm;
		var end= form.end;

		
		var total=jQuery('#search_adult').val()+jQuery('#search_child').val()+jQuery('#search_senior').val();
		if(total==0){

			alert('<?php echo JText::_('MOD_JBBUS_BUS_SELECT_PASSENGER_WARN')?>');
			return false;
		}
		
		if(form.filter_start.value==""){
			alert('<?php echo JText::_('MOD_JBBUS_BUS_DEPART_DATE_WARN')?>');
			return false;
		}
		
		form.submit();
	}


</script>
