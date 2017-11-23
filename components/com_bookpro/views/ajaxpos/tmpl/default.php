<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php  23-06-2012 23:33:14
 **/
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );
AImporter::helper ( 'form' );
JHtml::_ ( 'jquery.framework' );
JHtml::_ ( 'jquery.ui' );
AImporter::js('view-bustrips','jquery-create-seat','jquery.session','jquery.tablesorter.min','jquery.tablesorter.widgets.min');
AImporter::css ( 'bus', 'jbbus','jquery-create-seat','view-bustrips','theme.bootstrap');
 
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
	<form name="bustrip_form" id="bustrip_form" method="post"
		action='<?php echo JRoute::_('index.php?option=com_bookpro&view=busconfirm')?> '
		onsubmit="return submitForm()">
		<div class="well well-small" style="text-align: center;">
				<span><?php echo JText::sprintf('COM_BOOKPRO_BUS_FROM_TO',$from_title,$to_title)?></span><br/>
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
				</span> <br/>
			<?php echo JFactory::getDate($this->end)->format('D, d M Y'); ?>
		</div>

		<div id="tabs_return">
			<!-- Display return trip -->
			
			<?php echo JLayoutHelper::render($returnlayout,$this->return_trips,$basePath = JPATH_ROOT . '/components/com_bookpro/layouts/bus');
			
			?>
		</div>
		<?php } ?>
		
		<?php if ($this->config->get("no_seat")) { ?>
		<div class="text-center">
			<button onclick="submitForm()" type="button" name="btnSubmit" class="btn btn-primary"><?php echo JText::_('COM_BOOKPRO_CONTINUE') ?></button>
			</div>
		<?php } ?>
		
		<input type="hidden" name="seat" value="" id="order_seat" />
		<input type="hidden" name="return_seat" id="order_return_seat" value="" />
		<input type="hidden" name= 'Itemid' value="<?php echo JRequest::getVar('Itemid')?>"/>
	</form>
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

