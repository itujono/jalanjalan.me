<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 81 2012-08-11 01:16:36Z quannv $
 **/

defined ( '_JEXEC' ) or die ( 'Restricted access' );
AImporter::js('typeahead.bundle');
AImporter::css('common');


//Get all busstop for auto suggesttion

$db = JFactory::getDbo ();
$query = $db->getQuery ( true );
$query->select ( 'obj.location' );
$query->from ( '#__bookpro_busstop AS obj' );

$db->setQuery ( $query );
$locations = array ();
$items = $db->loadColumn ();
$items=array_unique($items);
$suggest=( json_encode($items));

?>


<script type="text/javascript">


			

				jQuery(document).ready(function($) {


					var substringMatcher = function(strs) {
						  return function findMatches(q, cb) {
						    var matches, substringRegex;

						    // an array that will be populated with substring matches
						    matches = [];

						    // regex used to determine if a string contains the substring `q`
						    substrRegex = new RegExp(q, 'i');

						    // iterate through the pool of strings and for any string that
						    // contains the substring `q`, add it to the `matches` array
						    $.each(strs, function(i, str) {
						      if (substrRegex.test(str)) {
						        matches.push(str);
						      }
						    });

						    cb(matches);
						  };
						};

					//jQuery.fn.bootstrapTypeahead = jQuery.fn.typeahead.noConflict();
					var states = <?php echo $suggest ?>;

					var states = new Bloodhound({
						  datumTokenizer: Bloodhound.tokenizers.whitespace,
						  queryTokenizer: Bloodhound.tokenizers.whitespace,
						  // `states` is an array of state names defined in "The Basics"
						  local: states
						});

			            $('.jb-suggest').typeahead({
			              hint: true,
			              highlight: true,
			              minLength: 1
			            },
			            {
			              name: 'states',
			              source: states
			                         });
					

					$('.timepicker').timepicker({
						 
					    template: false,
					    modalBackdrop:false,
					    showMeridian: false
					    
					});
					
					$("#add_new_stop").click(function(){
						
						$( ".busstopclone" ).eq(0).clone().insertAfter("div.busstopclone:last");
						$('.jb-suggest').typeahead('destroy');
						 $('.jb-suggest').typeahead({
				              hint: true,
				              highlight: true,
				              minLength: 1
				            },
				            {
				              name: 'states',
				              source: states
				                         });
						
					});
				
				});

				</script>


<?php 

if($this->item->id && count($this->item->busstops)>=1) {

	for ($i = 0; $i < count($this->item->busstops); $i++) {
		
		$stop=$this->item->busstops[$i];
		
?>




<div class="container-fluid" id="busstop" style="margin-top: 10px;">
	<div class="form-inline">
					
			<?php 	echo BusHelper::getBusstopType('busstop[type][]',$stop->type);		?>
								
			<label><?php echo JText::_('COM_BOOKPRO_BUSSTOP_LOCATION') ?></label>
		<input class="input-medium jb-suggest jb-location<?php echo $i?>" autocomplete="off" type="text" name="busstop[location][]" value="<?php echo $stop->location ?>"/> <label><?php echo JText::_('COM_BOOKPRO_BUSSTOP_DEPART'); ?></label>

		<div class="input-append bootstrap-timepicker">
			<input type="text" class="input-mini timepicker"
				name="busstop[depart][]" value="<?php echo $stop->depart ?>" /> <span class="add-on"><i class="icon-clock"></i></span>
		</div>

		<label><?php echo JText::_('COM_BOOKPRO_BUSSTOP_PRICE') ?></label> <input
			class="input-mini" type="text" name="busstop[price][]" value="<?php echo $stop->price ?>" />
			
			<input type="hidden" name="busstop[id][]" value="<?php echo $stop->id ?>" />

	</div>

</div>


<?php 
	}	
}else {
?>



<?php 
}
?>

<div class="container-fluid busstopclone" id="busstop" style="margin-top: 10px;">
	<div class="form-inline">
					
			<?php 	echo BusHelper::getBusstopType('busstop[type][]',0);		?>
								
		<label><?php echo JText::_('COM_BOOKPRO_BUSSTOP_LOCATION') ?></label>
		<input class="input-medium jb-suggest" type="text" name="busstop[location][]" /> <label><?php echo JText::_('COM_BOOKPRO_BUSSTOP_DEPART'); ?></label>

		<div class="input-append bootstrap-timepicker">
			<input type="text" class="input-mini timepicker"
				name="busstop[depart][]" /> <span class="add-on"><i class="icon-clock"></i></span>
		</div>


		<label><?php echo JText::_('COM_BOOKPRO_BUSSTOP_PRICE') ?></label> <input
			class="input-mini" type="text" name="busstop[price][]" />
			
		<input type="hidden" name="busstop[id][]" value="0" />

	</div>

</div>
<hr />
<div class="form-action pull-left">
	<button type="button" id="add_new_stop" class="btn btn-success">
		<icon class="icon-new"></icon>
							<?php echo JText::_('COM_BOOKPRO_ADD')?>
					</button>
</div>

