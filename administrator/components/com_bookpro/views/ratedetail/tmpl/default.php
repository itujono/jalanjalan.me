<?php 
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bookpro.php 80 2012-08-10 09:25:35Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

AImporter::css('magnific-popup');
?>
<script type="text/javascript">

jQuery(document).ready(function($) {

    $('#saverateadd').on("click",function(e){

		var url = "<?php echo JUri::base().'index.php?option=com_bookpro&controller=roomrate&task=savedate'?>"; // the script where you handle the form input.

		/*		
		if($('#adult').val()==''){
			alert("Field adult required!");
			$( '#adult').focus();
			return false;
			}
		*/

	    $.ajax({
	           type: "POST",
	           url: url,
	           data: $("#adminForm").serialize(), // serializes the form's elements.
	           success: function(data)
	           {
	        	   $('#jbmessage').html(data);
	               
	           }
	         });
	    e.preventDefault(); // avoid to execute the actual submit of the form.
	});

    $('.mfp-close').on("click",function(e){
		location.reload();
	});
});
</script>

<style>

.white-popup {
  position: relative;
  background: #FFF;
  padding: 20px;
  width: auto;
  max-width: 500px;
  margin: 20px auto;
}

</style>
<div class="white-popup">

<div id="message"></div>
<h2><?php echo $this->bustrip->title ?>&nbsp-&nbsp<?php echo $this->bustrip->code ?></h2>

<h4><?php echo $this->date ?></h4>

<div id="jbmessage"></div>
<form action="index.php" method="post" name="adminForm" id='adminForm'	class="form-validate">
	
	<fieldset>
	
	<table style="width:400px" id="formvalidate">
				
				<?php echo $this->loadTemplate('tickettype')?>
				
			</table>
	
</fieldset>	

<input type="submit"	class="btn btn-success" value="<?php echo JText::_('JAPPLY')?>" id="saverateadd" /> 
				

</form>
</div>