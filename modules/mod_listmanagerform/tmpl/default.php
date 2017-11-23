<?php defined('_JEXEC') or die('Restricted access'); // no direct access 
/*
 * @component List Manager 
 * @copyright Copyright (C) November 2017. 
 * @license GPL 3.0 
 * This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version. 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 * See the GNU General Public License for more details. 
 * See <http://www.gnu.org/licenses/>. 
 * More info www.moonsoft.es 
 * gestion@moonsoft.es
 */
JHTML::_('behavior.formvalidation'); 
$module_name="mod_listmanagerform";
$scriptbase=JURI::base().'modules/'.$module_name.'/assets/js/';
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'modules/mod_listmanager/assets/css/default2.css','text/css','screen');
$document->addStyleSheet(JURI::base().'modules/mod_listmanager/assets/css/lmbootstrap-'.$params->get('theme').'.css','text/css','screen');
$document->addStyleSheet(JURI::base().'modules/mod_listmanager/assets/css/jquery-ui-1.10.0.custom.css','text/css','screen');
$document->addStyleSheet(JURI::base().'modules/mod_listmanager/assets/css/rateit.css','text/css','screen');
$document->addStyleSheet(JURI::base().'modules/'.$module_name.'/assets/css/default.css','text/css','screen');

require_once(JPATH_ROOT.DS.'modules'.DS.'mod_listmanager'.DS.'helper.php');

$helper = new ModListManagerHelper();
$seed='_'.rand();
$_isFormModule=true;
 
?>
<script type="text/javascript" src="<?php echo JRoute::_('index.php?option=com_listmanager&controller=assets&task=formjs&format=raw');?>"></script>
<script type="text/javascript" src="<?php echo $scriptbase.'listmanagerform.js';?>"></script>
<script>
<!--
var dateformat<?php echo $seed;?>='<?php echo $listing["date_format_bbdd"]?>';
var dateformatview<?php echo $seed;?>='<?php echo $listing["date_format"]!=null?$listing["date_format"]:$listing["date_format_bbdd"]?>';
var arrEditores<?php echo $seed;?>="";

LMJQ(document).ready(function($) {
	LMJQ('#<?php echo $seed;?>list_adminForm').validate({
		  debug:true,
		  submitHandler: function(form) {},
	 	  invalidHandler: function(event, validator) {},
		  showErrors: function(errorMap, errorList) {this.defaultShowErrors();}
		});
	//Validator
	LMJQ.validator.addMethod("2limits", function(value, element, params) {
		value_temp=replaceAll(value, params[6], '' );
		return testLimit(value_temp, params[0], params[1], params[2], params[3], params[4], params[5]);		
	}, LMJQ.validator.format(getLangTradsLMF('LM_VALUE_BETWEEN')));
	LMJQ.validator.addMethod("1limit", function(value, element, params) {
		value_temp=replaceAll(value, params[6], '' );
		return testLimit(value_temp, params[0], params[1], params[2], params[3], params[4], params[5]);
	}, LMJQ.validator.format(getLangTradsLMF('LM_VALUE_GREATER')));
	LMJQ.validator.addMethod("limit1", function(value, element, params) {
		value_temp=replaceAll(value, params[6], '' );
		return testLimit(value_temp, params[0], params[1], params[2], params[3], params[4], params[5]);
	}, LMJQ.validator.format(getLangTradsLMF('LM_VALUE_SMALLER')));
	LMJQ.validator.addMethod("lmnumber", function(value, element, params) {
		value_temp=replaceAll(value, params[0], '' );
		//patron=/^[-]?[0-9]*[\,,.]?[0-9]{0, params[1]}$/;
		var patron = new RegExp("^[-]?[0-9]*[\,,.]?[0-9]{0,"+params[1]+"}$");
		return patron.test(value_temp);		
	}, LMJQ.validator.format(getLangTradsLMF('LM_VALUE_DECIMAL')));

	LMJQ('.radgroup').find('input[type=radio]').each(function(index,elem){
		LMJQ(elem).click(function(){
			LMJQ(this).closest('.radgroup').find('input[type=hidden][name='+LMJQ(this).attr('rad_name')+']').val(LMJQ(this).val());
		});
	});

		
	setTodayFields('#<?php echo $seed;?>list_adminForm');

	LMJQ('#<?php echo $seed;?>resetform').click(function(){
		resetForm('#<?php echo $seed;?>list_adminForm');		
	});
	getDefaultValues('#<?php echo $seed;?>list_adminForm');
});

var defaultValues=new Array();

function getDefaultValues(form){
	LMJQ(form).find('input:not([type=radio]):not([type=hidden]):not([type=checkbox]),textarea,select,checkbox:selected').each(function(index,elem){
		var newElem={f:LMJQ(elem).prop('name'),v:LMJQ(elem).val(),t:LMJQ(elem).prop("tagName")};		
		defaultValues.push(newElem);
	});	
	LMJQ(form).find('input[type=radio]:checked').each(function(index,elem){
		var newElem={f:LMJQ(elem).prop('id'),v:LMJQ(elem).val(),t:'RADIO'};		
		defaultValues.push(newElem);
	});		
	LMJQ(form).find('input[type=checkbox]:checked').each(function(index,elem){
		var newElem={f:LMJQ(elem).prop('name'),v:LMJQ(elem).attr('mv_check'),t:'CHECKBOX'};		
		defaultValues.push(newElem);
	});		
}

function resetForm(form){
	LMJQ(form).find('input:not([type=radio]):not([type=hidden]):not([type=checkbox]),textarea').each(function(index,elem){
		LMJQ(elem).val('');
	});
	LMJQ(form).find('input[type=radio],input[type=checkbox]').each(function(index,elem){
		LMJQ(elem).removeProp('checked');
	});
	LMJQ(form).find('textarea.cleditor').each(function(index,elem){
		LMJQ(elem).html('');
		var editorGlobal=LMJQ(elem).cleditor();
		setTimeout(function(){
			LMJQ(editorGlobal)[0].$area.val('');
			LMJQ(editorGlobal)[0].updateFrame();
			LMJQ(editorGlobal)[0].refresh();
				  
		},100);
	});
	
	setTodayFields(form);	
	// set default values
	LMJQ(defaultValues).each(function(index,elem){
		if (elem.t=='RADIO'){			
			LMJQ(form).find('#'+elem.f).prop('checked',true);
			LMJQ(form).find('#'+elem.f).button("refresh");						
		} else if (elem.t=='CHECKBOX'){
			LMJQ(form).find('[name="'+elem.f+'"][mv_check="'+elem.v+'"]').prop('checked',true);
			LMJQ(form).find('[name="'+elem.f+'"][mv_check="'+elem.v+'"]').button("refresh");
		} else {
			LMJQ(form).find('[name="'+elem.f+'"]').val(elem.v);
		}
	});
}

function setTodayFields(form){
	var newdate=new Date();
	LMJQ(form).find('input[lmtype="today"]').each(function(index,elem){
		LMJQ(elem).datepicker({ 
			dateFormat: window['dateformat<?php echo $seed;?>'],			
			onSelect: function() {				
				LMJQ(".ui-datepicker a").removeAttr("href");
				LMJQ(this).change();
			}	
		});
		LMJQ(elem).datepicker('setDate',newdate);
	});
}

function validateForm() {
    if (LMJQ(LMJQ('#<?php echo $seed;?>list_adminForm')).valid()){
        return true;
    } else {
    	/*var msg = '<?php echo JText::_( "LM_INCORRECT_VALUES" ); ?>';
		alert(msg);*/
		 return false;
    }
}

LMFM(function($) {
	$('#<?php echo $seed;?>cb_form').show();
	$('#<?php echo $seed;?>saveform').click(function(){
		if (validateForm()){
			$('#newForm').empty();
			$('#<?php echo $seed;?>list_adminForm').find('input[name="controller"]').val('listmanager');
			$('#<?php echo $seed;?>list_adminForm').find('input[name="task"]').val('saveForm');
			// Create form element
		      var form = $('<form>',{action :"index.php",'id' :'lm_intform'});
		      eval(arrEditores);
		      $('#<?php echo $seed;?>list_adminForm').find('[name^=fld]').each(function(index,elem){
		        elemTag=$(elem).attr('tag');
		        elemName="";
		        elemValue=""; 
		        if (elemTag=='select'){            
		          if ($(elem).attr('multiple')){
		        	  elemName=$(elem).attr('name');
			          elemValue= $(elem).find('option')[0].val();              
			          $(elem).find('option').each(function(ind,opt){
			            if($(opt).attr('selected')){
			            	elemName=$(elem).attr('name')+'[]';
				            elemValue=$(opt).val();
			            	var elemTemp = new Element('input').setProperties({
			                    type:'hidden',
			                    name:elemName,         
			                    value:elemValue
			                  });         
			                //elemTemp.inject(form);	              
			            	$(elemTemp).appendTo(form);
			            }
			          });
		          } else {  
			          elemName=$(elem).attr('name');
			          elemValue= $(elem).find('option')[0].val();              
			          $(elem).find('option').each(function(ind,opt){
			            if($(opt).attr('selected')){
			              elemName=$(elem).attr('name');
			              elemValue=$(opt).val();
			            }
			          });
		          }
		        } else {
		        	if ($(elem).attr('type')=='checkbox'){
		                if($(elem).is(':checked')){
			            	elemName=$(elem).attr('name');          
				            elemValue=$(elem).val();
		                }
		            } else {
			            elemName=$(elem).attr('name');          
			            elemValue=$(elem).val();                
		            }              
		        }
		        if (!(elemTag=='select' && $(elem).attr('multiple')=='multiple')){	        
			        var elemTemp = $('<input>',{
			          type:'hidden',
			          name:elemName,         
			          value:elemValue
			        });         
			        $(elemTemp).appendTo(form);
		        }
		      }); 
		      $('#<?php echo $seed;?>list_adminForm').find('[type=hidden]').each(function(index,elem){
		        var elemTemp = $('<input>',{
		          type:'hidden',
		          name:$(elem).attr('name'),
		          value:$(elem).val()
		        });
		        $(elemTemp).appendTo(form);           
		      });
		      <?php // Security field
		    			$session = JFactory::getSession();
		    			$randSS=rand(0, 99999999999999);
		    			$session->set('LM_SS_225541',$randSS);
		    			?>
		    	$('#<?php echo $seed;?>list_adminForm').find('[name="<?php echo $randSS;?>"]').each(function(index,elem){
		    	  var elemTemp = $('<input>',{
		    	    type:'hidden',
		    	    name:$(elem).attr('name'),
		    	    value:$(elem).val()
		    	  });
		    	  $(elemTemp).appendTo(form);           
		    	});    		        
		      //form.set('html',code);
		      $(form).appendTo($('#newForm'));
		      $.post("index.php", $('#lm_intform').serialize())
			      .done(function( data ) {
			    	  redirectForm();
			      }).fail(function() {			    	  
		      }); 
		      resetForm('#<?php echo $seed;?>list_adminForm');
		      return true;
		}
	});
});

function getLangTradsLMF(trad){
	switch(trad){
		case 'LM_VALUE_BETWEEN': return '<?php echo JText::_('LM_VALUE_BETWEEN')?>';
		case 'LM_VALUE_GREATER': return '<?php echo JText::_('LM_VALUE_GREATER')?>';
		case 'LM_VALUE_SMALLER': return '<?php echo JText::_('LM_VALUE_SMALLER')?>';
		case 'LM_VALUE_DECIMAL': return '<?php echo JText::_('LM_VALUE_DECIMAL')?>';
		default: return '';	
	}
}

function redirectForm(){
	if ("<?php echo $params->get('nexturl');?>"==""){
		LMFM("#formSaved").show().delay(3000).fadeOut();
	} else {
		document.location.href="<?php echo $params->get('nexturl');?>";
	}
}
function getDateFormat(){
	return "<?php echo $listing["date_format"]?>";
}
//-->
</script>
<div id="lm_wrapper_form" class="<?php echo $params->get('moduleclass');?>">
	<div id="formSaved" style="display:none;" class="formSaved alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo JText::_('LM_FORMSAVED');?></div>
<?php include JPATH_ROOT.DS.'modules'.DS.'mod_listmanager'.DS.'tmpl'.DS.'form.php'; ?>	
</div>
<span id="newForm" style="display:none;"></span>
<div id="divToPrint"></div>
